<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        // Mengambil ulasan yang disetujui untuk tampil ('show')
        $reviews = Review::with('user')->where('status', 'show')->latest()->get();

        $hasOrdered = false;

        if (Auth::check()) {
            // Memastikan user sudah bayar tiket sukses
            $purchased = Order::where('user_id', Auth::id())
                ->where('order_status', 'paid')
                ->exists();

            // Memastikan user belum pernah membuat review sebelumnya
            $alreadyReviewed = Review::where('user_id', Auth::id())->exists();

            if ($purchased && !$alreadyReviewed) {
                $hasOrdered = true;
            }
        }

        return view('pages.review', compact('reviews', 'hasOrdered'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input - Pastikan mendeteksi field 'comment'
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000', 
        ]);

        // 2. Proteksi ganda agar user tidak spam review
        $alreadyReviewed = Review::where('user_id', Auth::id())->exists();
        if ($alreadyReviewed) {
            return redirect()->back()->with('error', 'Anda sudah pernah memberikan ulasan sebelumnya!');
        }

        // 3. Ambil data order berbayar milik user
        $lastOrder = Order::where('user_id', Auth::id())
            ->where('order_status', 'paid')
            ->latest()
            ->first();

        if (!$lastOrder) {
            return redirect()->back()->with('error', 'Riwayat pemesanan tiket tidak ditemukan!');
        }

        // 4. Simpan ke Database
        Review::create([
            'user_id'  => Auth::id(),
            'event_id' => $lastOrder->event_id,
            'rating'   => $request->rating,
            'comment'  => $request->comment, // Menyimpan isi kalimat ulasan asli ke database
            'status'   => 'show'
        ]);

        return redirect()->back()->with('success', 'Ulasan Anda berhasil dikirim!');
    }
}