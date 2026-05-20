<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // REGISTER
    public function registerProcess(Request $request)
    {
        // 1. Tambahkan validasi untuk input 'role' (hanya boleh customer atau organizer)
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:customer,organizer' 
        ]);

        // 2. Simpan role sesuai dengan pilihan dropdown yang dipilih pengguna
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role 
        ]);

        return redirect()->route('login')->with('success', 'Register berhasil! Silakan login.');
    }

    // LOGIN
    public function loginProcess(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            $request->session()->regenerate();

            $user = Auth::user();

            // 🔥 PENGALIHAN BERDASARKAN 3 ROLE
            
            // Jika Admin / Superadmin masuk ke Dashboard Admin
            if ($user->role === 'admin' || $user->role === 'superadmin') {
                return redirect()->route('admin.dashboard');
            }

            // Jika Organizer masuk ke Dashboard Organizer
            if ($user->role === 'organizer') {
                return redirect()->route('organizer.dashboard'); // Pastikan Anda nanti membuat nama route ini
            }

            // Jika Customer (pembeli) masuk ke halaman utama / landing page
            return redirect()->route('home');
        }

        return back()->with('error', 'Email atau password salah')->withInput();
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}