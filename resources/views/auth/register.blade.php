@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-[70vh]">
    <form method="POST" action="{{ route('register.process') }}" class="w-full max-w-sm bg-white border rounded p-8 shadow">
        @csrf

        <h1 class="text-2xl font-bold mb-6 text-center">Register</h1>

        @if($errors->any())
            <div class="text-red-500 text-sm mb-2">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="mb-4">
            <label class="block mb-1 text-sm">Nama lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-4 py-2" placeholder="Masukkan nama lengkap" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-sm">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-4 py-2" placeholder="Masukkan email Anda" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-sm font-semibold">Daftar Sebagai</label>
            <select name="role" class="w-full border rounded px-4 py-2 bg-white" required>
                <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer (Pembeli Tiket)</option>
                <option value="organizer" {{ old('role') == 'organizer' ? 'selected' : '' }}>Organizer (Penyelenggara Acara)</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-sm">Password</label>
            <input type="password" name="password" class="w-full border rounded px-4 py-2" placeholder="Masukkan password Anda" required>
        </div>

        <div class="mb-6">
            <label class="block mb-1 text-sm">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-4 py-2" placeholder="Konfirmasi password Anda" required>
        </div>

        <button type="submit" class="w-full bg-black text-white py-2 rounded font-semibold mb-2">Register</button>

        <div class="text-center text-xs mt-2">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-black font-semibold hover:underline">Login di sini</a>
        </div>
    </form>
</div>
@endsection