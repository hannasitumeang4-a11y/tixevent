<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsOrganizer
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sudah login dan rolenya adalah organizer, izinkan lewat
        if (Auth::check() && Auth::user()->role === 'organizer') {
            return $next($request);
        }

        // Jika bukan organizer, lempar kembali ke halaman home dengan pesan error
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut!');
    }
}