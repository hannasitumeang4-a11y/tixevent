<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;

class PageController extends Controller
{
    // ==========================================
    //  ZONA FRONTEND
    // ==========================================

    public function home(Request $request)
    {
        // Dengan filter kategori dari backend
        $query = Event::query();
        
        if ($request->category && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }
        
        $events = $query->latest()->take(8)->get();
        $categories = Category::all();
        
        return view('pages.home', compact('events', 'categories'));
    }
    
    public function detail($id)
    {
        $event = Event::with('category')->findOrFail($id);
        return view('pages.detail', compact('event'));
    }

    public function history()
    {
        return view('pages.history');
    }

    public function checkout()
    {
        return view('pages.checkout');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    // Halaman Admin (UI)
    public function dashboard()
    {
        $totalEvent = Event::count();
        $totalOrders = 136; // Placeholder - bisa hubungkan ke model Order jika ada
        $totalRevenue = 45250000; // Placeholder
        $totalUsers = 128; // Placeholder
        
        $recentOrders = []; // Placeholder - bisa hubungkan ke model Order
        $recentEvents = Event::latest()->take(3)->get();
        
        return view('admin.dashboard', compact('totalEvent', 'totalOrders', 'totalRevenue', 'totalUsers', 'recentOrders', 'recentEvents'));
    }


    // ==========================================
    // ZONA BACKEND (LABORATORIUM / TESTING)
    // ==========================================

    public function homeTesting(Request $request)
    {
        // Tempat backend bereksperimen filter & logic
        $query = Event::query();

        if ($request->category && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }

        $events = $query->latest()->paginate(10);

        $categories = Category::all();

        // Mengarah ke folder: views/pages_testing/home.blade.php
        return view('pages_testing.home', compact('events', 'categories'));
    }

    public function eventDetailTesting($id)
    {
        $event = Event::findOrFail($id);
        // Mengarah ke folder: views/events_testing/show.blade.php
        return view('events_testing.show', compact('event'));
    }
}