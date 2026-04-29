<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\User;

class PageController extends Controller
{
    // ==========================================
    //  ZONA FRONTEND
    // ==========================================

    public function home(Request $request)
    {
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

    // ==========================================
    // ADMIN UI
    // ==========================================

    public function dashboard()
    {
        $totalEvent = Event::count();
        $totalOrders = 136;
        $totalRevenue = 45250000;
        $totalUsers = User::count();
        
        $recentOrders = [];
        $recentEvents = Event::latest()->take(3)->get();
        
        return view('admin.dashboard', compact(
            'totalEvent',
            'totalOrders',
            'totalRevenue',
            'totalUsers',
            'recentOrders',
            'recentEvents'
        ));
    }

    public function usermanage()
    {
        $users = User::latest()->get(); // 🔥 ambil user dari DB
        return view('admin.usermanage', compact('users'));
    }

    public function eventmanage()
    {
        $events = Event::latest()->get(); // 🔥 ambil event
        $categories = Category::all();

        return view('admin.eventmanage', compact('events', 'categories'));
    }

    // ==========================================
    // TESTING
    // ==========================================

    public function homeTesting(Request $request)
    {
        $query = Event::query();

        if ($request->category && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }

        $events = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('pages_testing.home', compact('events', 'categories'));
    }

    public function eventDetailTesting($id)
    {
        $event = Event::findOrFail($id);
        return view('events_testing.show', compact('event'));
    }
}