@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] py-12">
    <div class="bg-green-100 text-green-600 w-24 h-24 flex items-center justify-center rounded-full text-5xl mb-6">
        ✓
    </div>
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Pemesanan Berhasil!</h1>
    <p class="text-gray-500 text-center max-w-md mb-8">
        Terima kasih, Rakha! Pesananmu sedang diproses. Silakan cek email kamu untuk detail instruksi pembayaran.
    </p>
    <div class="flex gap-4">
        <a href="{{ route('home') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition">
            Balik ke Home
        </a>
    </div>
</div>
@endsection