@extends('layouts.app')

@section('content')
<nav class="text-xs text-gray-500 mb-4">
    <a href="{{ route('home') }}" class="hover:underline text-indigo-600">Home</a> /
    <a href="#" class="hover:underline text-indigo-600">{{ $event->category->name ?? 'Event' }}</a> /
    <span>{{ $event->title }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Gambar utama saja -->
    <div>
        <div class="bg-gray-200 h-64 md:h-96 rounded-xl flex items-center justify-center overflow-hidden border shadow-sm">
            @php
                // Mengambil gambar utama (primary)
                $primaryImage = $event->images->where('is_primary', 1)->first() ?? $event->images->first();
            @endphp

            @if($primaryImage && file_exists(public_path($primaryImage->image_path)))
                <img src="{{ asset($primaryImage->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
            @else
                <div class="text-center">
                    <span class="text-6xl block">🎟️</span>
                    <span class="text-xs text-gray-400">Gambar tidak tersedia</span>
                </div>
            @endif
        </div>
        {{-- Bagian galeri gambar kecil sudah dihapus dari sini --}}
    </div>
    
    <!-- Detail event -->
    <div>
        <span class="inline-block bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded-full mb-2 font-medium">
            {{ $event->category->name ?? 'Uncategorized' }}
        </span>
        <h1 class="text-2xl font-bold mb-2 text-gray-800">{{ $event->title }}</h1>
        
        <div class="flex flex-wrap items-center gap-4 text-gray-500 mb-3 text-sm">
            <span class="flex items-center gap-1">📅 {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('d M Y') : 'TBA' }}</span>
            <span class="flex items-center gap-1">🕐 {{ $event->start_time ?? '09.00' }} - {{ $event->end_time ?? '16.00' }} WIB</span>
        </div>
        
        <div class="mb-3 text-gray-500 flex items-center gap-1 text-sm">
            <span>📍</span> {{ $event->location ?? 'Lokasi belum ditentukan' }}
        </div>
        
        <p class="mb-6 text-gray-700 leading-relaxed">{{ $event->description ?? 'Deskripsi event belum tersedia.' }}</p>

        <!-- Harga Utama -->
        <div class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-5 border border-indigo-100/50">
            <div class="text-sm text-gray-500">Harga mulai dari</div>
            <div class="text-4xl font-black text-indigo-600">
                {{ $event->price ? 'Rp' . number_format($event->price, 0, ',', '.') : 'Gratis' }}
            </div>
        </div>

        <div class="mb-6">
            <h2 class="font-semibold mb-3 text-gray-800">Pilih Jenis Tiket</h2>
            <div class="space-y-3">
                <!-- VIP Ticket -->
                <div class="flex items-center justify-between border-2 border-indigo-100 rounded-xl px-4 py-3 hover:border-indigo-300 transition bg-white shadow-sm">
                    <div>
                        <div class="font-bold text-gray-800">VIP</div>
                        <div class="text-xs text-gray-500">Fasilitas: Kursi VIP, Lunch, Goodie Bag, Sertifikat</div>
                        <div class="text-xs text-green-600 mt-1 font-medium">✓ Stok tersedia</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-lg text-indigo-600">Rp500.000</div>
                        <button class="ml-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-bold">Pilih</button>
                    </div>
                </div>
                <!-- Regular Ticket -->
                <div class="flex items-center justify-between border border-gray-200 rounded-xl px-4 py-3 hover:border-gray-300 transition bg-white">
                    <div>
                        <div class="font-bold text-gray-800">REGULAR</div>
                        <div class="text-xs text-gray-500">Fasilitas: Kursi Reguler, Sertifikat</div>
                        <div class="text-xs text-green-600 mt-1 font-medium">✓ Stok tersedia</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-lg text-gray-700">{{ $event->price ? 'Rp' . number_format($event->price, 0, ',', '.') : 'Gratis' }}</div>
                        <button class="ml-4 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition text-sm font-bold">Pilih</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('home') }}" class="px-5 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 transition text-gray-700 font-bold text-sm">← Kembali</a>
            <a href="{{ route('checkout') }}" class="flex-grow text-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-lg hover:opacity-90 transition font-bold shadow-md">Beli Tiket Sekarang</a>
        </div>
    </div>
</div>
@endsection