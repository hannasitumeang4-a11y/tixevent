<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class PageController extends Controller
{
    // ==========================================
    //  ZONA FRONTEND
    // ==========================================

    public function home()
    {
        // UI Bersih tanpa intervensi filter backend
        $events = Event::latest()->take(6)->get();
        return view('pages.home', compact('events'));
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
        return view('admin.dashboard', compact('totalEvent'));
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

        $events = $query->latest()->get();

        // Mengarah ke folder: views/pages_testing/home.blade.php
        return view('pages_testing.home', compact('events'));
    }

    public function eventDetailTesting($id)
    {
        $event = Event::findOrFail($id);
        // Mengarah ke folder: views/events_testing/show.blade.php
        return view('events_testing.show', compact('event'));
    }
}