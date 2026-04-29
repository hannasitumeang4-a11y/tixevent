@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-[70vh]">
    <form class="w-full max-w-sm bg-white border rounded p-8 shadow">
        <h1 class="text-2xl font-bold mb-6 text-center">Register</h1>
        <div class="mb-4">
            <label class="block mb-1 text-sm">Nama lengkap</label>
            <input type="text" class="w-full border rounded px-4 py-2" placeholder="Masukkan nama lengkap">
        </div>
        <div class="mb-4">
            <label class="block mb-1 text-sm">Email</label>
            <input type="email" class="w-full border rounded px-4 py-2" placeholder="Masukkan email Anda">
        </div>
        <div class="mb-4">
            <label class="block mb-1 text-sm">Password</label>
            <input type="password" class="w-full border rounded px-4 py-2" placeholder="Masukkan password Anda">
        </div>
        <div class="mb-6">
            <label class="block mb-1 text-sm">Konfirmasi Password</label>
            <input type="password" class="w-full border rounded px-4 py-2" placeholder="Konfirmasi password Anda">
        </div>
        <button class="w-full bg-black text-white py-2 rounded font-semibold mb-2">Register</button>
        <div class="text-center text-xs mt-2">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-black font-semibold hover:underline">Login di sini</a>
        </div>
    </form>
</div>
@endsection
