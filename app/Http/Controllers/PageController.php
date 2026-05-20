<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\EventTicket;
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
            ->orderBy('order_id', 'desc') // Diubah ke order_id karena kolom 'id' tidak ada
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

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'payment_method' => $isFree ? 'nullable' : 'required',
            'ticket_id' => 'required',
            'payment_proof' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $total = $ticket->price;
        $file = null;

        if ($isFree) {
            $payment = 'free_pass';
        } else {
            $payment = 'transfer';
            if ($request->payment_method == 'qris') {
                $payment = 'ewallet';
            }

            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof')->store('payments', 'public');
            }
        }

        $currentUserId = auth()->user()->user_id ?? auth()->id();
        $uniqueOrderCode = 'ORD-' . time() . rand(10, 99);

        // Simpan data order baru
        DB::table('orders')->insert([
            'order_code' => $uniqueOrderCode,
            'user_id' => $currentUserId,
            'event_id' => $ticket->event_id,
            'ticket_id' => $request->ticket_id, 
            'quantity' => 1,
            'total_amount' => $total,
            'payment_method' => $payment,
            'payment_proof' => $file,
            'order_status' => 'paid', 
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Ambil data yang baru disimpan berdasarkan order_code unik
        $savedOrder = DB::table('orders')->where('order_code', $uniqueOrderCode)->first();
        
        // Menggunakan order_id sebagai key utama utama tabel orders kamu
        $actualOrderId = $savedOrder->order_id ?? $savedOrder->id ?? null;

        // Hubungkan rincian data ke order_items
        try {
            DB::table('order_items')->insert([
                'order_id' => $actualOrderId, 
                'event_ticket_id' => $request->ticket_id, 
                'quantity' => 1,
                'subtotal' => $total,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            try {
                DB::table('order_items')->insert([
                    'order_id' => $actualOrderId, 
                    'ticket_id' => $request->ticket_id, 
                    'quantity' => 1,
                    'subtotal' => $total,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } catch (\Exception $fallbackException) {
                Log::error('Gagal mencatat rincian transaksi pada order_items: ' . $fallbackException->getMessage());
            }
        }

        return redirect()
            ->route('invoice', $actualOrderId)
            ->with('success', 'Pembayaran berhasil diproses!');
    }

    public function invoice($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $currentUserId = auth()->user()->user_id ?? auth()->id();

        // Cari data invoice murni menggunakan order_id & order_code (Bebas dari kolom 'id' palsu)
        $order = Order::with(['event', 'ticket'])
            ->where('user_id', $currentUserId)
            ->where(function($query) use ($id) {
                $query->where('order_id', $id)
                      ->orWhere('order_code', $id);
            })
            ->first();

        // Jalur Cadangan Query Builder murni jika Model Eloquent bermasalah
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

        $orders = Order::where('user_id', $currentUserId)
            ->latest()
            ->get();

        return view('pages.profile', compact('orders'));
    }

    public function dashboard()
    {
        $totalEvent = Event::count();
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_amount');

        $recentOrders = Order::latest()->take(5)->get();
        $recentEvents = Event::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalEvent', 'totalUsers', 'totalOrders', 'totalRevenue', 'recentOrders', 'recentEvents'
        ));
    }

    public function usermanage()
    {
        return view('admin.users');
    }

    public function eventmanage()
    {
        return view('admin.events');
    }
}