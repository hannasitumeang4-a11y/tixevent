<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\EventTicket;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FRONTEND
    |--------------------------------------------------------------------------
    |*/

    public function home(Request $request)
    {
        $categories = Category::all();

        $locations = Event::whereIn('status', ['published', 'draft'])
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location');

        $query = Event::with([
            'category',
            'images',
            'tickets'
        ]);

        if ($request->filled('category') && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('filter_location') && $request->filter_location != 'all') {
            $query->where('location', $request->filter_location);
        }

        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('tickets', function ($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }

        $events = $query->whereIn('status', ['published', 'draft'])
            ->orderBy('event_date', 'desc')
            ->paginate(12)
            ->withQueryString();

        $totalEventsCount = Event::whereIn('status', ['published', 'draft'])->count();

        $popular = Event::whereIn('status', ['published', 'draft'])
            ->latest()
            ->take(3)
            ->get();

        return view('pages.home', compact('events', 'categories', 'popular', 'locations', 'totalEventsCount'));
    }

    public function detail($id)
    {
        $event = Event::with(['category', 'images', 'tickets'])->findOrFail($id);
        return view('pages.detail', compact('event'));
    }

    public function history()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $currentUserId = auth()->user()->user_id ?? auth()->id();

        $orders = Order::with(['event.category', 'ticket']) 
            ->where('user_id', $currentUserId)
            ->orderBy('order_id', 'desc')
            ->paginate(10);

        return view('pages.history', compact('orders'));
    }

    public function about()
    {
        return view('pages.about');
    }

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT & PAYMENT
    |--------------------------------------------------------------------------
    |*/

    public function checkout(Request $request)
    {
        if (!$request->event || !$request->ticket) {
            return redirect()->route('home')->with('error', 'Silakan pilih tiket terlebih dahulu');
        }

        $event = Event::findOrFail($request->event);
        $ticket = EventTicket::findOrFail($request->ticket);

        return view('pages.checkout', compact('event', 'ticket'));
    }

public function processPayment(Request $request) 
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $ticket = EventTicket::findOrFail($request->ticket_id);
    $isFree = $ticket->price == 0;

    // 1. VALIDASI QUANTITY & BUKTI TRANSFER
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'payment_method' => $isFree ? 'nullable' : 'required',
        'ticket_id' => 'required',
        'quantity' => 'required|integer|min:1', 
        'payment_proof' => $isFree ? 'nullable' : 'required|image|mimes:jpg,png,jpeg|max:2048' // Wajib upload jika berbayar
    ]);

    $requestedQty = (int) $request->quantity;

    // Cek ketersediaan stok tiket
    if ($ticket->stock < $requestedQty) {
        return redirect()->back()->with('error', 'Maaf, stok tiket tidak mencukupi. Sisa stok saat ini: ' . $ticket->stock . ' tiket.');
    }

    // Hitung total nominal belanja
    $total = $ticket->price * $requestedQty;
    $file = null;
    
    // LOGIKA PENENTUAN STATUS (KUNCI UTAMA FIX ADMIN KOSONG)
    $orderStatus = 'pending'; 

    if ($isFree) {
        $payment = 'free_pass';
        $orderStatus = 'paid'; // Jika gratis, langsung otomatis Lunas/Paid
    } else {
        $payment = 'transfer';
        $orderStatus = 'pending'; // Jika berbayar, WAJIB Pending agar diverifikasi admin terlebih dahulu
        
        if ($request->payment_method == 'qris') {
            $payment = 'ewallet';
        }

        // Simpan file gambar fisik ke dalam "Wadah" folder storage/app/public/payments
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof')->store('payments', 'public');
        }
    }

    $currentUserId = auth()->user()->user_id ?? auth()->id();
    $uniqueOrderCode = 'ORD-' . time() . rand(10, 99);

    // 2. SIMPAN KE TABEL ORDERS DENGAN STATUS DINAMIS
    DB::table('orders')->insert([
        'order_code' => $uniqueOrderCode,
        'user_id' => $currentUserId,
        'event_id' => $ticket->event_id,
        'ticket_id' => $request->ticket_id, 
        'quantity' => $requestedQty,
        'total_amount' => $total,
        'payment_method' => $payment,
        'payment_proof' => $file, // Menyimpan path wadah gambar
        'order_status' => $orderStatus, // Menggunakan status dinamis (bukan 'paid' terus-menerus)
        'created_at' => now(),
        'updated_at' => now()
    ]);

    $savedOrder = DB::table('orders')->where('order_code', $uniqueOrderCode)->first();
    $actualOrderId = $savedOrder->order_id ?? $savedOrder->id ?? null;

    // 3. SIMPAN DETAIL DI ORDER ITEMS
    try {
        DB::table('order_items')->insert([
            'order_id' => $actualOrderId, 
            'event_ticket_id' => $request->ticket_id, 
            'quantity' => $requestedQty, 
            'subtotal' => $total,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    } catch (\Exception $e) {
        try {
            DB::table('order_items')->insert([
                'order_id' => $actualOrderId, 
                'ticket_id' => $request->ticket_id, 
                'quantity' => $requestedQty, 
                'subtotal' => $total,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $fallbackException) {
            \Log::error('Gagal mencatat rincian transaksi pada order_items: ' . $fallbackException->getMessage());
        }
    }

    // 4. PENGURANGAN STOK TIKET
    // Catatan: Jika ingin stok berkurang HANYA setelah admin menyetujui transfer, 
    // pindahkan baris decrement ini ke dalam fungsi approveOrder di PageController.
    $ticket->decrement('stock', $requestedQty);

    // 5. REDIRECT HALAMAN SESUAI STATUS
    if ($orderStatus === 'paid') {
        return redirect()
            ->route('invoice', $actualOrderId)
            ->with('success', 'Pembayaran sukses, tiket instan Anda langsung aktif!');
    }

    return redirect()
        ->route('profile') // Diarahkan ke profile/riwayat agar user melihat statusnya masih "pending"
        ->with('success', 'Pesanan berhasil dibuat! Menunggu verifikasi bukti pembayaran oleh admin.');
}
    public function invoice($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $currentUserId = auth()->user()->user_id ?? auth()->id();

        $order = Order::with(['event', 'ticket'])
            ->where('user_id', $currentUserId)
            ->where(function($query) use ($id) {
                $query->where('order_id', $id)
                    ->orWhere('order_code', $id);
            })
            ->first();

        if (!$order) {
            $rawOrder = DB::table('orders')
                ->where('user_id', $currentUserId)
                ->where(function($query) use ($id) {
                    $query->where('order_id', $id)
                        ->orWhere('order_code', $id);
                })
                ->first();

            if ($rawOrder) {
                $order = Order::with(['event', 'ticket'])->find($rawOrder->order_id);
            }
        }

        if (!$order) {
            abort(404, 'Data invoice tidak ditemukan di database atau bukan milik akun Anda.');
        }

        return view('pages.invoice', compact('order'));
    }

    public function success()
    {
        return view('pages.success');
    }

    /*
    |--------------------------------------------------------------------------
    | AUTH & DASHBOARD MANAGEMENT
    |--------------------------------------------------------------------------
    |*/

    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

  public function profile()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $currentUserId = auth()->user()->user_id ?? auth()->id();

        // Tambahkan with(['event', 'ticket']) agar data nama konser dan tiket terambil dari database
        $orders = Order::with(['event', 'ticket']) 
            ->where('user_id', $currentUserId)
            ->latest()
            ->get();

        return view('pages.profile', compact('orders'));
    }

    public function dashboard()
{
    // === KODE BAWAAN UTUH (TIDAK DIUBAH) ===
    $totalEvent = Event::count();
    $totalUsers = User::count();
    $totalOrders = Order::where('order_status', 'paid')->count();
    $totalRevenue = Order::where('order_status', 'paid')->sum('total_amount');

    $recentOrders = Order::latest()->take(5)->get();
    $recentEvents = Event::latest()->take(5)->get();


    // === TAMBAHAN LOGIKA BARU AGAR TIDAK KOSONG ===
    
    // 1. Ambil data pesanan berstatus 'pending' beserta relasi user & event
    $pendingPayments = Order::join('users', 'orders.user_id', '=', 'users.user_id')
        ->join('events', 'orders.event_id', '=', 'events.event_id')
        ->select(
            'orders.order_id', 
            'orders.created_at', 
            'users.name as user_name', 
            'events.title as event_title',
            'orders.payment_proof',
            'orders.order_status'
        )
        ->where('orders.order_status', 'pending')
        ->latest('orders.created_at')
        ->get();

    // 2. Hitung jumlah transaksi menggantung untuk mengaktifkan kotak warning kuning di atas
    $stuckTransactionsCount = $pendingPayments->count();


    // === KIRIM SEMUA VARIABEL KE VIEW DASHBOARD ===
    return view('admin.dashboard', compact(
        'totalEvent', 'totalUsers', 'totalOrders', 'totalRevenue', 
        'recentOrders', 'recentEvents', 'pendingPayments', 'stuckTransactionsCount'
    ));
}

/**
 * FUNGSI: MENYETUJUI PEMBAYARAN USER
 */
public function approveOrder($id)
{
    \Illuminate\Support\Facades\DB::table('orders')
        ->where('order_id', $id)
        ->update([
            'order_status' => 'paid',
            'updated_at' => now()
        ]);

    return redirect()->back()->with('success', 'Pembayaran sukses diverifikasi!');
}

/**
 * FUNGSI: MENOLAK STRUK PALSU & HAPUS PERMANEN
 */
public function rejectOrder($id)
{
    $order = \Illuminate\Support\Facades\DB::table('orders')->where('order_id', $id)->first();

    if ($order) {
        // Hapus file gambar di storage jika ada
        if ($order->payment_proof && file_exists(storage_path('app/public/' . $order->payment_proof))) {
            @unlink(storage_path('app/public/' . $order->payment_proof));
        }

        // Hapus dari database agar bersih dari riwayat profile user
        \Illuminate\Support\Facades\DB::table('orders')->where('order_id', $id)->delete();

        return redirect()->back()->with('success', 'Transaksi palsu ditolak dan dihapus.');
    }

    return redirect()->back()->with('error', 'Gagal memproses.');
}

    public function usermanage(Request $request)
    {
        $userKey = (new User())->getKeyName();
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy($userKey, 'desc')->get();
        return view('admin.usermanage', compact('users'));
    }

    public function eventmanage()
    {
        $events = Event::orderBy('created_at', 'desc')->get();
        return view('admin.eventmanage', compact('events'));
    }

    /*
    |--------------------------------------------------------------------------
    | FITUR KURASI ADMIN (PUBLISH, REJECT, TAKE DOWN)
    |--------------------------------------------------------------------------
    |*/

    public function publishEvent($id)
    {
        $event = Event::where('event_id', $id)->firstOrFail();
        $event->status = 'published';
        $event->save();

        return redirect()->back()->with('success', 'Event "' . $event->title . '" berhasil diverifikasi dan ditayangkan!');
    }

    public function rejectEvent($id)
    {
        $event = Event::where('event_id', $id)->firstOrFail();
        $event->status = 'rejected';
        $event->save();

        return redirect()->back()->with('with', 'Pendaftaran event telah ditolak.');
    }

    public function takedownEvent($id)
    {
        $event = Event::where('event_id', $id)->firstOrFail();
        $event->status = 'draft'; 
        $event->save();

        return redirect()->back()->with('success', 'Event "' . $event->title . '" berhasil diturunkan paksa dari web utama!');
    }

    /*
    |--------------------------------------------------------------------------
    | FITUR KONTROL PENGGUNA (UBAH ROLE & SUSPEND/BANNED ACCOUNT)
    |--------------------------------------------------------------------------
    |*/

    public function updateUserRole(Request $request, $id)
    {
        $userKey = (new User())->getKeyName();
        $user = User::where($userKey, $id)->firstOrFail();
        
        $request->validate([
            'role' => 'required|in:admin,organizer,customer'
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'Peran/Role dari pengguna bernama "' . $user->name . '" berhasil diperbarui!');
    }

    public function toggleUserStatus($id)
    {
        $userKey = (new User())->getKeyName();
        $user = User::where($userKey, $id)->firstOrFail();

        $user->is_banned = $user->is_banned ? 0 : 1;
        $user->save();

        $statusMessage = $user->is_banned 
            ? 'Akun milik "' . $user->name . '" telah berhasil ditangguhkan/BANNED!' 
            : 'Akses login akun milik "' . $user->name . '" berhasil dipulihkan kembali.';

        return redirect()->back()->with('success', $statusMessage);
    }

    /*
    |--------------------------------------------------------------------------
    | MODERASI REVIEW & TESTIMONI OLEH ADMIN
    |--------------------------------------------------------------------------
    |*/

    public function reviewmanage()
    {
        $reviews = Review::with(['user', 'event'])->orderBy('created_at', 'desc')->get();
        return view('admin.reviewmanage', compact('reviews'));
    }

    public function destroyReview($id)
    {
        $reviewKey = (new Review())->getKeyName();
        $review = Review::where($reviewKey, $id)->firstOrFail();
        $review->delete();

        return redirect()->back()->with('success', 'Ulasan negatif atau spam berhasil dihapus oleh moderator admin!');
    }

    /*
    |--------------------------------------------------------------------------
    | BARU: SISTEM AGREGASI LAPORAN UNTUK DEMO APLIKASI (DOSEN/KELOMPOK)
    |--------------------------------------------------------------------------
    |*/

    public function reportmanage()
    {
        // 1. Ambil seluruh riwayat transaksi sukses untuk tabel laporan keuangan utama
        $allOrders = Order::with(['event', 'ticket', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Totalkan Keuangan global
        $totalRevenue = $allOrders->sum('total_amount');
        $totalTicketsSold = $allOrders->count();

        // 2. LAPORAN PERFORMA EVENT: Event Terlaris (Paling Banyak Menyumbang Transaksi)
        $topEvents = Order::select('event_id', DB::raw('count(*) as total_sales'), DB::raw('sum(total_amount) as revenue'))
            ->groupBy('event_id')
            ->orderBy('total_sales', 'desc')
            ->with('event')
            ->take(5)
            ->get();

        // 3. LAPORAN PERFORMA PROMOTOR: Promotor Paling Aktif Membuat Event
        $topPromoters = Event::select('organizer_id', DB::raw('count(*) as total_events'))
            ->groupBy('organizer_id')
            ->orderBy('total_events', 'desc')
            ->with(['organizer' => function($query) {
                // Menghubungkan ke tabel user karena organizer_id merujuk pada user_id dengan peran promotor
                $query->select('user_id', 'name', 'email');
            }])
            ->take(5)
            ->get();

        return view('admin.reportmanage', compact(
            'allOrders', 'totalRevenue', 'totalTicketsSold', 'topEvents', 'topPromoters'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | AKSI EKSPOR: MENGHASILKAN FILE SPREADSHEET EXCEL (.XLSX / .XLS)
    |--------------------------------------------------------------------------
    |*/
    public function exportExcel()
    {
        // Ambil data transaksi keuangan global
        $allOrders = Order::with(['event', 'ticket', 'user'])->orderBy('created_at', 'desc')->get();
        $totalRevenue = $allOrders->sum('total_amount');
        $totalTicketsSold = $allOrders->count();

        // Ambil data Top 5 Event Terlaris
        $topEvents = Order::select('event_id', DB::raw('count(*) as total_sales'), DB::raw('sum(total_amount) as revenue'))
            ->groupBy('event_id')
            ->orderBy('total_sales', 'desc')
            ->with('event')
            ->take(5)->get();

        // Ambil data Top 5 Promotor Teraktif (Menggunakan relasi organizer untuk menghindari error)
        $topPromoters = Event::select('organizer_id', DB::raw('count(*) as total_events'))
            ->groupBy('organizer_id')
            ->orderBy('total_events', 'desc')
            ->with(['organizer' => function($query) {
                $query->select('user_id', 'name', 'email');
            }])
            ->take(5)->get();

        // Set instruksi Header agar dibaca sebagai file unduhan Excel oleh browser
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Eksekutif_TixEvent.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        ?>
        <h3>Pusat Laporan Eksekutif (Financial & Performance Report)</h3>
        <p>Tanggal Unduh: <?= date('d-m-Y H:i') ?> WIB</p>
        <br>

        <table border="1" cellpadding="5">
            <tr style="background-color: #e2e8f0; font-weight: bold;">
                <th>Metrik Analisis Keuangan</th>
                <th>Total Akumulasi Terhitung</th>
            </tr>
            <tr>
                <td>Total Uang Masuk (Gross Revenue)</td>
                <td><b>Rp<?= number_format($totalRevenue, 0, ',', '.') ?></b></td>
            </tr>
            <tr>
                <td>Total Tiket Terjual (Volume Transaksi)</td>
                <td><b><?= $totalTicketsSold ?> Tiket</b></td>
            </tr>
        </table>

        <br><br>

        <table border="1" cellpadding="5">
            <thead>
                <tr style="background-color: #1e3a8a; color: white; font-weight: bold;">
                    <th colspan="3">🔥 Top 5 Event Terlaris (Sumbangsih Tertinggi)</th>
                </tr>
                <tr style="background-color: #f3f4f6; font-weight: bold;">
                    <th>Nama Event</th>
                    <th>Tiket Terjual</th>
                    <th>Total Omset</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($topEvents as $topEvent): ?>
                <tr>
                    <td><?= $topEvent->event->title ?? 'Event Dihapus' ?></td>
                    <td align="center"><?= $topEvent->total_sales ?>x Transaksi</td>
                    <td align="right">Rp<?= number_format($topEvent->revenue, 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <br><br>

        <table border="1" cellpadding="5">
            <thead>
                <tr style="background-color: #065f46; color: white; font-weight: bold;">
                    <th colspan="3">🏢 Top 5 Promotor / Organizer Teraktif</th>
                </tr>
                <tr style="background-color: #f3f4f6; font-weight: bold;">
                    <th>Nama Instansi / Akun</th>
                    <th>Email Kontak</th>
                    <th>Event Dibuat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($topPromoters as $promoter): ?>
                <tr>
                    <td><?= $promoter->organizer->name ?? 'Promotor Default' ?></td>
                    <td><?= $promoter->organizer->email ?? 'N/A' ?></td>
                    <td align="center"><?= $promoter->total_events ?> Event</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <br><br>

        <table border="1" cellpadding="5">
            <thead>
                <tr style="background-color: #374151; color: white; font-weight: bold;">
                    <th colspan="6">📜 Journal Log Transaksi Real-time (Audit Trail)</th>
                </tr>
                <tr style="background-color: #f3f4f6; font-weight: bold;">
                    <th>Kode Order</th>
                    <th>Pembeli</th>
                    <th>Konser & Tiket</th>
                    <th>Metode</th>
                    <th>Uang Masuk</th>
                    <th>Waktu Berhasil</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allOrders as $order): ?>
                <tr>
                    <td>'<?= $order->order_code ?></td>
                    <td><?= $order->user->name ?? 'Anonymous' ?> (<?= $order->user->email ?? '' ?>)</td>
                    <td><?= $order->event->title ?? 'N/A' ?> - <?= $order->ticket->ticket_name ?? 'Regular' ?></td>
                    <td align="center"><?= strtoupper($order->payment_method) ?></td>
                    <td align="right">Rp<?= number_format($order->total_amount, 0, ',', '.') ?></td>
                    <td align="center"><?= $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        exit;
    }
}