<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; // Tambahan untuk validasi email unik

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
                return redirect()->route('organizer.dashboard'); 
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

    // UPDATE PROFILE (Fungsi Baru untuk Halaman Akun)
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // 1. Validasi inputan form dari Halaman Akun
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                // Cek agar email tidak dipakai user lain, abaikan jika email itu milik user ini sendiri
                // CATATAN: Jika nama primary key di database kamu 'id' (bukan 'user_id'), ganti kata 'user_id' di bawah ini menjadi 'id'
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id'),
            ],
            // Password opsional, tapi kalau diisi minimal 6 karakter
            'password' => 'nullable|min:6|confirmed', 
        ]);

        // 2. Timpa data lama dengan data baru
        $user->name = $request->name;
        $user->email = $request->email;

        // 3. Jika form ganti password diisi, enkripsi dan simpan
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 4. Simpan perubahan
        $user->save();

        // 5. Kembalikan ke halaman profil dengan notifikasi sukses
        return redirect()->route('profile')->with('success', 'Berhasil! Data profil dan pengaturan akun Anda sudah diperbarui.');
    }
}