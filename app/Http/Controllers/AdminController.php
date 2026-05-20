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

        // 5. Log riwayat pesanan tiket paling baru masuk ke sistem
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

        // 6. Return ke view dashboard admin yang tepat
        return view('admin.dashboard', compact(
            'myEvents', 'totalEvent', 'recentEvents', 'totalOrders', 'totalRevenue', 'totalUsers', 'recentOrders'
        ));
    }
}