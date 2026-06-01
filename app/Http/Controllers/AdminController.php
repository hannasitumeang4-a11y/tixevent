<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Mengambil semua data event global untuk konsumsi dashboard admin
        $myEvents = Event::latest()->get();
        $totalEvent = $myEvents->count();
        $recentEvents = Event::latest()->take(5)->get();

        // 2. Hitung total pesanan berstatus paid
        $totalOrders = DB::table('orders')
            ->where('order_status', 'paid')
            ->count();

        // 3. Hitung akumulasi total pendapatan masuk global
        $totalRevenue = DB::table('orders')
            ->where('order_status', 'paid')
            ->sum('total_amount');

        // 4. Hitung jumlah pembeli unik
        $totalUsers = DB::table('orders')
            ->where('order_status', 'paid')
            ->distinct('user_id')
            ->count('user_id');

        // 5. Log riwayat pesanan tiket paling baru masuk ke sistem (Struktur Awal Tetap Utuh)
        $recentOrders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.user_id')
            ->select(
                'orders.order_id', 
                'orders.created_at', 
                'users.name as user_name', 
                'orders.order_status'
            )
            ->latest('orders.created_at')
            ->take(5)
            ->get();

        // =========================================================================
        // 🛡️ LOGIKA BARU: SINKRONISASI DATA PENDING DAN WARNING BOX DASHBOARD
        // =========================================================================
        
        // Mengambil data pesanan PENDING untuk disalurkan ke Tabel Verifikasi Real-time
        $pendingPayments = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.user_id')
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

        // Hitung jumlah transaksi menggantung yang butuh tindakan segera
        $stuckTransactionsCount = $pendingPayments->count();

        // 6. Return ke view dashboard admin dengan menyertakan variabel bawaan & variabel baru
        return view('admin.dashboard', compact(
            'myEvents', 'totalEvent', 'recentEvents', 'totalOrders', 
            'totalRevenue', 'totalUsers', 'recentOrders', 'pendingPayments', 'stuckTransactionsCount'
        ));
    }

    /**
     * FUNGSI BARU: OTORITAS MENYETUJUI PEMBAYARAN SAH (APPROVE)
     */
    public function approveOrder($id)
    {
        DB::table('orders')
            ->where('order_id', $id)
            ->update([
                'order_status' => 'paid',
                'updated_at' => now()
            ]);

        return redirect()->route('admin.dashboard')->with('success', 'Pembayaran sah! E-Tiket diterbitkan ke customer.');
    }

    /**
     * FUNGSI BARU: MENOLAK BUKTI PALSU (REJECT & HAPUS PERMANEN DARI RIWAYAT USER)
     */
    public function rejectOrder($id)
    {
        $order = DB::table('orders')->where('order_id', $id)->first();

        if ($order) {
            // Hapus berkas gambar fisiknya dari direktori server jika ada
            if ($order->payment_proof && file_exists(storage_path('app/public/' . $order->payment_proof))) {
                @unlink(storage_path('app/public/' . $order->payment_proof));
            }

            // Hapus record pesanan dari database agar lenyap dari riwayat transaksi profile user
            DB::table('orders')->where('order_id', $id)->delete();

            return redirect()->route('admin.dashboard')->with('success', 'Transaksi curang berhasil ditolak dan dihapus dari sistem.');
        }

        return redirect()->route('admin.dashboard')->with('error', 'Gagal memproses penolakan.');
    }
}