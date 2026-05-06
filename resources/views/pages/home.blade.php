@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

    <!-- Hero Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b pb-10 items-center mt-6">
        <div>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight text-indigo-900">
                Temukan Event Seru <br> Untukmu
            </h1>
            <p class="mt-4 text-gray-600 text-lg">
                Berbagai event menarik menantimu.<br>
                Pesan tiket sekarang sebelum kehabisan!
            </p>
            <a href="#event-list" class="inline-block mt-6 bg-indigo-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-indigo-700 transition shadow-lg">
                Jelajahi Event
            </a>
        </div>

        <!-- Banner Visual -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 h-64 rounded-2xl flex flex-col items-center justify-center shadow-xl text-white">
            <span class="text-6xl mb-2">🚀</span>
            <span class="text-xl font-bold">Eventix Platform</span>
            <p class="text-indigo-100 text-sm">Cari, Pesan, Hadiri.</p>
        </div>
    </div>

    <!-- Kategori Filter -->
    <div class="py-8 border-b" id="event-list">
        <h2 class="font-bold mb-5 text-xl text-gray-800">Kategori Populer</h2>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('home', ['category' => 'all']) }}" 
               class="px-6 py-2 rounded-full text-sm font-semibold transition border
               {{ !request('category') || request('category') == 'all' 
                  ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' 
                  : 'bg-white text-gray-700 border-gray-200 hover:border-indigo-400 hover:text-indigo-600' }}">
                Semua
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('home', ['category' => $cat->category_id]) }}" 
                   class="px-6 py-2 rounded-full text-sm font-semibold transition border
                   {{ request('category') == $cat->category_id 
                      ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' 
                      : 'bg-white text-gray-700 border-gray-200 hover:border-indigo-400 hover:text-indigo-600' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Event Cards Grid -->
    <div class="py-10">
        <div class="flex justify-between items-center mb-8">
            <h2 class="font-bold text-2xl text-gray-800">Event Terbaru</h2>
            <span class="bg-gray-100 text-gray-600 px-4 py-1 rounded-full text-xs font-medium border">
                {{ $events->count() }} event tersedia
            </span>
        </div>

        @if($events->isEmpty())
            <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                <span class="text-6xl">🔍</span>
                <p class="text-xl font-semibold text-gray-600 mt-4">Belum ada event ditemukan</p>
                <p class="text-gray-400 text-sm mt-1">Coba pilih kategori lain atau cek kembali beberapa saat lagi.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-block text-indigo-600 font-medium hover:underline">Lihat Semua Event</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($events as $event)
                <a href="{{ route('events.detail', $event->event_id) }}" class="group">
                    <div class="h-full border border-gray-100 rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-300 bg-white flex flex-col">
                        
                        <!-- Thumbnail dengan Logika Relasi Gambar -->
                        <div class="relative h-48 w-full overflow-hidden bg-gray-200">
                            @php
                                // Mengambil gambar is_primary=1 atau gambar pertama yang tersedia
                                $primaryImage = $event->images->where('is_primary', 1)->first() ?? $event->images->first();
                            @endphp

                            @if($primaryImage && file_exists(public_path($primaryImage->image_path)))
                                <img src="{{ asset($primaryImage->image_path) }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            @else
                                <!-- Fallback jika gambar tidak ditemukan di database atau folder public -->
                                <div class="bg-gradient-to-br from-indigo-100 to-purple-100 w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <span class="text-5xl block">🎟️</span>
                                        <span class="text-[10px] text-indigo-400 uppercase tracking-widest font-bold">No Preview</span>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Badge Kategori di Atas Gambar -->
                            <div class="absolute top-3 left-3">
                                <span class="bg-white/90 backdrop-blur-sm text-indigo-700 text-[10px] font-bold px-3 py-1 rounded-lg shadow-sm uppercase">
                                    {{ $event->category->name ?? 'Event' }}
                                </span>
                            </div>
                        </div>

                        <!-- Konten Card -->
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-gray-800 group-hover:text-indigo-600 transition line-clamp-2 mb-2 min-h-[3rem]">
                                {{ $event->title }}
                            </h3>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-gray-500 text-xs">
                                    <span class="mr-2">📅</span>
                                    {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('d M Y') : 'Coming Soon' }}
                                </div>
                                <div class="flex items-center text-gray-500 text-xs">
                                    <span class="mr-2">📍</span>
                                    <span class="truncate">{{ $event->location ?? 'Lokasi Belum Ditentukan' }}</span>
                                </div>
                            </div>

                            <div class="mt-auto pt-4 border-t border-gray-50 flex justify-between items-center">
                                <div class="text-indigo-600 font-extrabold text-lg">
                                    {{ $event->price > 0 ? 'Rp' . number_format($event->price, 0, ',', '.') : 'Gratis' }}
                                </div>
                                <div class="bg-indigo-50 text-indigo-600 p-2 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection