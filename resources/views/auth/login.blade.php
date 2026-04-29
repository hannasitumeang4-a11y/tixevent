@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-[70vh]">
    <form class="w-full max-w-sm bg-white border rounded p-8 shadow">
        <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>
        <div class="mb-4">
            <label class="block mb-1 text-sm">Email</label>
            <input type="email" class="w-full border rounded px-4 py-2" placeholder="Masukkan email Anda">
        </div>
        <div class="mb-2">
            <label class="block mb-1 text-sm">Password</label>
            <input type="password" class="w-full border rounded px-4 py-2" placeholder="Masukkan password Anda">
        </div>
        <div class="flex items-center justify-between mb-4">
            <label class="flex items-center text-xs">
                <input type="checkbox" class="mr-2"> Inget saya
            </label>
            <a href="#" class="text-xs text-gray-500 hover:underline">Lupa password?</a>
        </div>
        <button class="w-full bg-black text-white py-2 rounded font-semibold mb-2">Login</button>
        <div class="text-center text-xs mt-2">
            Belum punya akun? <a href="{{ route('register') }}" class="text-black font-semibold hover:underline">Register di sini</a>
        </div>
    </form>
</div>
@endsection
