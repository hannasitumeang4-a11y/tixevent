<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\User;

class PageController extends Controller
{
    public function home(Request $request)
    {
        $categories = Category::all();
        $query = Event::with(['category', 'images']);
        
        if ($request->category && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }
        
        $events = $query->where('status', 'published')
                        ->latest()
                        ->take(8)
                        ->get();
        
        return view('pages.home', compact('events', 'categories'));
    }

    public function detail($id)
    {
        $event = Event::with(['category', 'images'])->findOrFail($id);
        return view('pages.detail', compact('event'));
    }

    public function checkout()
    {
        return view('pages.checkout');
    }

    /**
     * Memproses data dari form checkout
     */
    public function processPayment(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'payment_method' => 'required',
        ]);

        // LOGIKA: Di sini kamu nantinya bisa menyimpan data ke tabel 'transactions'
        // Untuk sekarang, kita asumsikan berhasil dan lempar ke halaman sukses.
        
        return redirect()->route('payment.success');
    }

    /**
     * Menampilkan halaman sukses
     */
    public function success()
    {
        return view('pages.success');
    }

    // Stub fungsi agar route admin tidak error
    public function dashboard() { return view('admin.dashboard'); }
    public function usermanage() { return view('admin.users'); }
    public function eventmanage() { return view('admin.events'); }
    public function history() { return view('pages.history'); }
    public function login() { return view('auth.login'); }
    public function register() { return view('auth.register'); }
}