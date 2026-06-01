@extends('layouts.app')

@section('content')

{{-- STYLE TAMBAHAN UNTUK ANIMASI MODERN --}}
<style>
    /* Animasi Ken Burns untuk gambar Hero */
    .ken-burns {
        animation: kenBurns 15s ease-out infinite alternate;
    }
    @keyframes kenBurns {
        0% { transform: scale(1); }
        100% { transform: scale(1.15); }
    }
    
    /* Animasi mengambang untuk elemen dekoratif */
    .animate-float {
        animation: float 4s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* Efek teks gradasi bergerak */
    .animate-gradient-text {
        background-size: 200% auto;
        animation: shineText 3s linear infinite;
    }
    @keyframes shineText {
        to { background-position: 200% center; }
    }
</style>

{{-- SIDEBAR KATEGORI (FIXED ACCORDION) --}}
<aside class="group fixed left-0 top-16 md:top-20 h-[calc(100vh-4rem)] bg-[#07080e] border-r border-slate-800/60 shadow-[4px_0_24px_rgba(0,0,0,0.6)] flex flex-col justify-start pt-6 z-30 w-16 md:w-20 hover:w-64 transition-all duration-300 ease-in-out overflow-y-auto min-h-0">
    
    <div class="px-5 mb-6 opacity-40 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap overflow-hidden">
        <span class="text-[10px] font-bold tracking-[0.2em] text-indigo-400 block pl-1 uppercase font-digital">Kategori Digital</span>
    </div>

    <div class="flex flex-col space-y-2 px-3">
        {{-- Semua Event Button --}}
        <a href="{{ route('home',['category'=>'all']) }}#popular"
           class="flex items-center gap-4 px-3.5 py-3.5 rounded-xl transition-all duration-300 group/item {{ !request('category') || request('category')=='all' ? 'bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white shadow-lg shadow-indigo-600/30 border border-indigo-400/30' : 'text-slate-400 hover:bg-slate-800/40 hover:text-indigo-400' }}">
            <svg class="w-5 h-5 flex-shrink-0 group-hover/item:scale-110 group-hover/item:rotate-3 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
            </svg>
            <span class="font-bold text-xs tracking-wide opacity-0 group-hover:opacity-100 whitespace-nowrap transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                Semua Event
            </span>
        </a>

        @foreach($categories as $cat)
            @php
                $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>';
                $nameLower = strtolower($cat->name);
                
                if(str_contains($nameLower, 'musik') || str_contains($nameLower, 'concert')) {
                    $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>';
                } elseif(str_contains($nameLower, 'seminar') || str_contains($nameLower, 'talk')) {
                    $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>';
                } elseif(str_contains($nameLower, 'workshop') || str_contains($nameLower, 'kelas')) {
                    $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>';
                }
            @endphp
            <a href="{{ route('home',['category'=>$cat->category_id]) }}#popular"
                class="flex items-center gap-4 px-3.5 py-3.5 rounded-xl transition-all duration-300 group/item {{ request('category') == $cat->category_id ? 'bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white shadow-lg shadow-indigo-600/30 border border-indigo-400/30' : 'text-slate-400 hover:bg-slate-800/40 hover:text-indigo-400' }}">
                <svg class="w-5 h-5 flex-shrink-0 group-hover/item:scale-110 group-hover/item:rotate-3 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $iconPath !!}
                </svg>
                <span class="font-bold text-xs tracking-wide opacity-0 group-hover:opacity-100 whitespace-nowrap transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                    {{ $cat->name }}
                </span>
            </a>
        @endforeach
    </div>
</aside>

{{-- WRAPPER KONTEN UTAMA --}}
<div class="pl-[4.5rem] md:pl-[5.5rem] pr-4 md:pr-12 pt-4 md:pt-6 -mt-2 md:-mt-4 transition-all duration-300 max-w-[1500px] mx-auto">
    
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
        
        {{-- SISI KIRI (HERO, FILTER, CATALOG) --}}
        <div class="lg:col-span-4 space-y-8">
            
            {{-- HERO CAROUSEL --}}
            <div class="rounded-[28px] overflow-hidden relative shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-slate-700/60 bg-[#101222] aspect-[16/8] md:aspect-[21/8.5] group">
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff02_1px,transparent_1px),linear-gradient(to_bottom,#ffffff02_1px,transparent_1px)] bg-[size:40px_40px] z-20 pointer-events-none"></div>
                
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-600/30 rounded-full blur-[100px] z-10 pointer-events-none"></div>

                <div id="heroCarousel" class="flex h-full w-full transition-transform duration-[1000ms] ease-[cubic-bezier(0.25,1,0.5,1)]">
                    <div class="w-full h-full flex-shrink-0 relative overflow-hidden">
                        <img src="{{asset('assets/img/events/banner-hero-all.jpg')}}" class="w-full h-full object-cover brightness-[0.7] ken-burns">
                    </div>
                    <div class="w-full h-full flex-shrink-0 relative overflow-hidden">
                        <img src="{{asset('assets/img/events/banner-concert.jpg')}}" class="w-full h-full object-cover brightness-[0.7] ken-burns">
                    </div>
                    <div class="w-full h-full flex-shrink-0 relative overflow-hidden">
                        <img src="{{asset('assets/img/events/banner-seminar.jpg')}}" class="w-full h-full object-cover brightness-[0.7] ken-burns">
                    </div>
                    <div class="w-full h-full flex-shrink-0 relative overflow-hidden">
                        <img src="{{asset('assets/img/events/banner-workshop.jpg')}}" class="w-full h-full object-cover brightness-[0.7] ken-burns">
                    </div>
                </div>

                <div class="absolute inset-0 bg-gradient-to-t from-[#05060b] via-[#090a10]/60 to-transparent z-20 flex flex-col justify-end p-8 md:p-14">
                    <div class="max-w-3xl space-y-4 relative">
                        <div class="flex items-center gap-3 animate-float inline-flex bg-indigo-950/50 border border-indigo-500/30 backdrop-blur-sm px-4 py-1.5 rounded-full">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 shadow-[0_0_10px_#818cf8] animate-ping"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 absolute"></span>
                            <span class="text-[10px] uppercase font-digital tracking-[0.25em] text-indigo-300 font-extrabold">NEXT-GEN TICKET PLATFORM</span>
                        </div>

                        {{-- PERBAIKAN: Font diubah dari md:text-6xl ke text-4xl dan lg:text-[2.75rem] agar jauh lebih proporsional --}}
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-[2.75rem] font-black tracking-tight text-white leading-snug drop-shadow-2xl">
                            Temukan Event <br class="hidden sm:block">
                            {{-- PERBAIKAN: Gradasi diperkuat dan ditambahkan efek glow shadow --}}
                            <span class="bg-gradient-to-r from-cyan-400 via-indigo-400 to-pink-400 bg-clip-text text-transparent animate-gradient-text drop-shadow-[0_0_15px_rgba(168,85,247,0.4)]">Favoritmu Disini</span>
                        </h1>
                        
                        <p class="text-xs md:text-sm text-slate-300 font-light max-w-xl leading-relaxed opacity-90 drop-shadow-lg">
                            Rasakan kemudahan akses digital menuju panggung hiburan konser musik akbar, ruang seminar edukatif, hingga workshop interaktif masa kini.
                        </p>

                        <div class="flex flex-wrap gap-4 pt-3">
                            <a href="#event-list" class="relative overflow-hidden group/btn bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:scale-105 transition-all duration-300 px-8 py-3.5 rounded-xl font-bold text-xs shadow-[0_0_30px_rgba(99,102,241,0.4)] border border-white/20 text-white">
                                <span class="relative z-10 flex items-center gap-2">Jelajahi Digital Feed <svg class="w-4 h-4 group-hover/btn:translate-x-1 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></span>
                                <div class="absolute inset-0 h-full w-full bg-white/20 transform scale-x-0 group-hover/btn:scale-x-100 transition-transform origin-left duration-300"></div>
                            </a>
                            <a href="#popular" class="bg-white/5 hover:bg-white/10 text-white border border-slate-600/60 backdrop-blur-md hover:border-slate-400/80 transition-all duration-300 px-8 py-3.5 rounded-xl font-bold text-xs flex items-center gap-2">
                                Tren Populer
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Indikator Navigasi Bawah Carousel --}}
                <div class="absolute bottom-6 right-8 z-30 flex gap-2">
                    <button class="carousel-dot w-8 h-1.5 rounded-full bg-indigo-500 transition-all duration-300" data-index="0"></button>
                    <button class="carousel-dot w-2 h-1.5 rounded-full bg-white/30 hover:bg-white/60 transition-all duration-300" data-index="1"></button>
                    <button class="carousel-dot w-2 h-1.5 rounded-full bg-white/30 hover:bg-white/60 transition-all duration-300" data-index="2"></button>
                    <button class="carousel-dot w-2 h-1.5 rounded-full bg-white/30 hover:bg-white/60 transition-all duration-300" data-index="3"></button>
                </div>
            </div>

            {{-- MINI STATUS PANEL --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-gradient-to-b from-[#131524] to-[#0e101b] p-5 rounded-2xl border border-slate-800/80 shadow-sm relative group overflow-hidden hover:border-indigo-500/50 transition duration-300">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-indigo-500/10 rounded-full blur-xl group-hover:bg-indigo-500/20 transition duration-300"></div>
                    <div class="text-2xl md:text-3xl font-black font-digital text-indigo-400 group-hover:scale-105 transition duration-300 origin-left">
                        {{ $events->total() }}
                    </div>
                    <div class="text-[9px] font-bold text-slate-500 mt-1.5 tracking-widest uppercase font-digital">EVENT TERSEDIA</div>
                </div>

                <div class="bg-gradient-to-b from-[#131524] to-[#0e101b] p-5 rounded-2xl border border-slate-800/80 shadow-sm relative group overflow-hidden hover:border-purple-500/50 transition duration-300">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition duration-300"></div>
                    <div class="text-2xl md:text-3xl font-black font-digital text-purple-400 group-hover:scale-105 transition duration-300 origin-left">
                        {{ $categories->count() }}
                    </div>
                    <div class="text-[9px] font-bold text-slate-500 mt-1.5 tracking-widest uppercase font-digital">KATEGORI AKTIF</div>
                </div>

                <div class="bg-gradient-to-b from-[#131524] to-[#0e101b] p-5 rounded-2xl border border-slate-800/80 shadow-sm relative group overflow-hidden hover:border-emerald-500/50 transition duration-300">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition duration-300"></div>
                    <div class="text-2xl md:text-3xl font-black font-digital text-emerald-400 group-hover:scale-105 transition duration-300 origin-left">
                        1K+
                    </div>
                    <div class="text-[9px] font-bold text-slate-500 mt-1.5 tracking-widest uppercase font-digital">SINKRONISASI USER</div>
                </div>
            </div>

            {{-- SEARCH & FILTER FORM --}}
            <form action="{{ route('home') }}#popular" method="GET" class="space-y-3 relative z-10">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1 group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci pencarian konser, seminar, atau aktivitas digital..."
                               class="w-full pl-12 pr-6 py-4 rounded-2xl bg-[#121424] border border-slate-800 text-slate-200 placeholder-slate-500 shadow-inner focus:outline-none focus:ring-1 focus:ring-indigo-500/50 focus:border-indigo-500 font-medium transition duration-300">
                    </div>

                    <div class="flex gap-2">
                        <button type="button" id="btnToggleFilter" class="bg-[#1a1d33] hover:bg-[#20243f] border border-slate-700/60 text-slate-300 px-5 rounded-2xl font-bold text-xs transition duration-200 flex items-center gap-2 whitespace-nowrap active:scale-95">
                            ⚙️ Filter Opsi
                            @if(request('filter_location') || request('min_price') || request('max_price'))
                                <span class="w-1.5 h-1.5 rounded-full bg-pink-500 inline-block animate-pulse"></span>
                            @endif
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white px-6 py-4 rounded-2xl text-xs font-bold transition duration-200 shadow-[0_0_15px_rgba(79,70,229,0.3)] active:scale-95">
                            Cari Event
                        </button>
                    </div>
                </div>

                {{-- PANEL FILTER TAMBAHAN --}}
                <div id="panelFilterOpsi" class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out bg-[#111322] border border-transparent rounded-2xl opacity-0 {{ request('filter_location') || request('min_price') || request('max_price') ? '!max-h-[500px] !opacity-100 !border-slate-800 p-5 mt-2' : '' }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        {{-- Lokasi --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-digital">📍 Lokasi Event</label>
                            <select name="filter_location" class="bg-[#0b0c14] border border-slate-800 text-slate-300 rounded-xl p-3 text-xs outline-none focus:border-indigo-500 font-medium transition cursor-pointer">
                                <option value="all">Semua Lokasi</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc }}" {{ request('filter_location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Harga Minimum --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-digital">💵 Harga Minimum (Rp)</label>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Contoh: 50000"
                                   class="bg-[#0b0c14] border border-slate-800 text-slate-200 placeholder-slate-700 rounded-xl p-3 text-xs outline-none focus:border-indigo-500 font-medium transition">
                        </div>

                        {{-- Harga Maksimum --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest font-digital">💸 Harga Maksimum (Rp)</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Contoh: 750000"
                                   class="bg-[#0b0c14] border border-slate-800 text-slate-200 placeholder-slate-700 rounded-xl p-3 text-xs outline-none focus:border-indigo-500 font-medium transition">
                        </div>

                    </div>

                    @if(request('filter_location') || request('min_price') || request('max_price') || request('search'))
                        <div class="flex justify-end mt-4 pt-3 border-t border-slate-800/60">
                            <a href="{{ route('home') }}#popular" class="text-slate-500 hover:text-red-400 text-xs font-bold transition flex items-center gap-1">
                                ❌ Bersihkan Semua Pencarian
                            </a>
                        </div>
                    @endif
                </div>
            </form>

            {{-- SEDANG TREN MINGGU INI --}}
            <div id="popular" class="space-y-4 pt-2">
                <h2 class="font-bold text-lg text-white font-digital flex items-center gap-2">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span class="bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent uppercase tracking-wider text-xs font-black">Sedang Tren Minggu Ini</span>
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($popular as $item)
                        <a href="{{ route('events.detail', $item->event_id) }}" class="group flex flex-col h-full">
                            <div class="bg-[#121424] rounded-[24px] overflow-hidden border border-slate-800/60 shadow-lg hover:shadow-[0_20px_40px_rgba(99,102,241,0.15)] hover:border-indigo-500/50 hover:-translate-y-2 duration-300 flex flex-col h-full relative transition-all">
                                
                                <div class="h-44 overflow-hidden relative bg-slate-950">
                                    @php
                                        $popularImage = $item->primaryImage ?? $item->images->first();
                                    @endphp
                                    <img src="{{ asset($popularImage ? $popularImage->image_path : 'assets/img/events/banner-hero-all.jpg') }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 duration-700 opacity-90 group-hover:opacity-100 transition-transform"
                                         onerror="this.onerror=null;this.src='{{ asset('assets/img/events/banner-hero-all.jpg') }}';">
                                    
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#121424] to-transparent opacity-80"></div>

                                    <div class="absolute top-4 left-4 bg-gradient-to-r from-indigo-600/90 to-purple-600/90 backdrop-blur-md border border-white/10 px-3 py-1 rounded-full text-[9px] font-black tracking-widest text-white font-digital uppercase shadow-md z-10">
                                        {{ $item->category->name ?? 'TRENDING' }}
                                    </div>
                                </div>

                                <div class="p-5 flex flex-col flex-1 justify-between bg-gradient-to-b from-[#121424] to-[#0a0b12] relative z-10 -mt-6 rounded-t-[20px]">
                                    <div class="space-y-2">
                                        <h3 class="font-bold text-sm text-slate-200 line-clamp-1 group-hover:text-indigo-400 duration-200 tracking-wide transition-colors">
                                            {{ $item->title }}
                                        </h3>
                                        
                                        <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed font-normal">
                                            {{ $item->description ?? 'Ikuti event spesial tren minggu ini. Amankan tiket digital Anda sebelum kehabisan kuota sistem.' }}
                                        </p>

                                        <div class="flex flex-col gap-1.5 text-[11px] text-slate-500 font-medium pt-2">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 text-indigo-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2V12a2 2 0 002 2z"></path></svg>
                                                {{ \Carbon\Carbon::parse($item->event_date)->format('d M Y') }}
                                            </span>
                                            <span class="flex items-center gap-2 truncate">
                                                <svg class="w-3.5 h-3.5 text-indigo-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                                <span class="truncate text-slate-500 group-hover:text-slate-400 duration-200 transition-colors">{{ $item->location }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-5 pt-4 border-t border-slate-800/60 flex items-center justify-between">
                                        <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest font-digital">Access Pass</span>
                                        <span class="text-indigo-400 font-black text-base font-digital group-hover:text-pink-400 transition-colors drop-shadow-md">
                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-3 text-center bg-[#121424] border border-slate-800/40 p-10 rounded-2xl text-slate-500 font-medium text-xs shadow-inner">
                            <svg class="w-6 h-6 mx-auto text-slate-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Tidak ada event yang cocok dengan kriteria filter pencarian Anda.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- KATALOG EVENT --}}
            <div id="event-list" class="space-y-6 pt-4">
                <h2 class="font-black text-xl text-white font-digital uppercase tracking-wider">Jelajahi Katalog Event</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($events as $event)
                        <a href="{{ route('events.detail', $event->event_id) }}" class="group">
                            <div class="bg-[#121424] rounded-[24px] overflow-hidden border border-slate-800/60 shadow-lg hover:shadow-[0_10px_30px_rgba(0,0,0,0.5)] hover:border-slate-600 hover:-translate-y-1 duration-300 flex flex-col h-full transition-all">
                                <div class="h-48 overflow-hidden relative bg-slate-950">
                                    @php
                                        $primaryImage = $event->primaryImage ?? $event->images->first();
                                    @endphp
                                    <img src="{{ asset($primaryImage ? $primaryImage->image_path : 'assets/img/events/banner-hero-all.jpg') }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 duration-700 opacity-80 group-hover:opacity-100 transition-transform"
                                         onerror="this.onerror=null;this.src='{{ asset('assets/img/events/banner-hero-all.jpg') }}';">
                                    
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#121424] to-transparent opacity-90"></div>
                                    
                                    <div class="absolute top-4 right-4 bg-slate-950/80 backdrop-blur-md border border-slate-700 px-3 py-1 rounded-full text-[9px] font-bold text-emerald-400 font-digital tracking-wide z-10 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> ONLINE CONFIRMED
                                    </div>
                                </div>

                                <div class="p-5 flex flex-col flex-1 justify-between bg-[#121424] relative z-10 -mt-8 rounded-t-[20px]">
                                    <div class="space-y-2">
                                        <h3 class="font-bold text-sm text-slate-200 line-clamp-2 group-hover:text-indigo-400 duration-200 transition-colors">
                                            {{ $event->title }}
                                        </h3>
                                        <div class="flex flex-col gap-1 text-[11px] text-slate-500 font-medium">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 text-slate-600 group-hover:text-indigo-500/50 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2V12a2 2 0 002 2z"></path></svg>
                                                {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                            </span>
                                            <span class="flex items-center gap-2 truncate">
                                                <svg class="w-3.5 h-3.5 text-slate-600 group-hover:text-indigo-500/50 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                                <span class="truncate">{{ $event->location }}</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-slate-800/60 flex items-center justify-between">
                                        <span class="text-[11px] text-slate-500 font-medium">Digital Pass</span>
                                        <span class="text-indigo-400 font-black text-base font-digital">Rp{{ number_format($event->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-1 md:col-span-2 xl:col-span-3 text-center bg-[#121424] border border-slate-800/40 p-10 rounded-2xl text-slate-500 font-medium text-xs shadow-inner">
                            <svg class="w-6 h-6 mx-auto text-slate-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Belum ada data event yang terdaftar di dalam katalog ini.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- SISI KANAN (STICKY DIGITAL CART) --}}
        <div class="lg:col-span-1 relative z-20">
            <div class="bg-[#121424] rounded-2xl border border-slate-700/60 shadow-[0_0_40px_rgba(0,0,0,0.8)] p-6 sticky top-24 md:top-28 space-y-4 h-auto">
                {{-- Glow accent on cart --}}
                <div class="absolute -top-1 -right-1 w-20 h-20 bg-pink-500/10 rounded-full blur-xl pointer-events-none"></div>

                <h2 class="font-bold text-sm text-white font-digital flex items-center gap-2 border-b border-slate-800 pb-3 tracking-wider uppercase">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span>Digital Cart</span>
                </h2>

                @if(session('cart') && count(session('cart')) > 0)
                    <div class="divide-y divide-slate-800 max-h-72 overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent">
                        @foreach(session('cart') as $cart)
                            <div class="py-3 first:pt-0 last:pb-0 group/cart">
                                <h4 class="font-bold text-slate-300 line-clamp-1 text-xs group-hover/cart:text-indigo-400 transition-colors duration-200">{{ $cart['title'] }}</h4>
                                <div class="flex justify-between items-center mt-1.5">
                                    <span class="text-[9px] font-bold text-slate-400 font-digital uppercase tracking-wider bg-slate-950 px-2 py-0.5 rounded border border-slate-800">
                                        {{ $cart['ticket'] }}
                                    </span>
                                    <span class="text-xs font-black text-indigo-400 font-digital">
                                        Rp{{ number_format($cart['price'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('cart') }}" class="block w-full text-center bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-600 hover:scale-[1.02] transition-transform text-white py-3.5 rounded-xl font-bold text-xs tracking-wider shadow-[0_0_20px_rgba(147,51,234,0.3)] uppercase">
                        Proses Enkripsi Order
                    </a>
                @else
                    <div class="text-center py-8 space-y-3">
                        <div class="w-11 h-11 mx-auto bg-slate-900 rounded-full flex items-center justify-center border border-slate-800/80 text-slate-600 shadow-inner">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <p class="text-[11px] text-slate-500 font-medium">Konsol keranjang kosong.</p>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</div>

{{-- SCRIPT UNTUK ANIMASI CAROUSEL & TOGGLE FILTER --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. LOGIKA CAROUSEL HERO ---
        const carousel = document.getElementById('heroCarousel');
        const dots = document.querySelectorAll('.carousel-dot');
        const totalSlides = carousel.children.length;
        let currentSlide = 0;
        let slideInterval;

        function updateSlider(index) {
            // Geser gambar
            carousel.style.transform = `translateX(-${index * 100}%)`;
            
            // Update UI dot
            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.remove('w-2', 'bg-white/30');
                    dot.classList.add('w-8', 'bg-indigo-500');
                } else {
                    dot.classList.remove('w-8', 'bg-indigo-500');
                    dot.classList.add('w-2', 'bg-white/30');
                }
            });
            currentSlide = index;
        }

        function nextSlide() {
            let nextIndex = (currentSlide + 1) % totalSlides;
            updateSlider(nextIndex);
        }

        // Jalankan auto-slide tiap 5 detik
        if (totalSlides > 1) {
            slideInterval = setInterval(nextSlide, 5000);
            
            // Klik dot untuk navigasi manual
            dots.forEach(dot => {
                dot.addEventListener('click', (e) => {
                    clearInterval(slideInterval); // Hentikan auto-slide sebentar saat di klik
                    const index = parseInt(e.target.getAttribute('data-index'));
                    updateSlider(index);
                    slideInterval = setInterval(nextSlide, 5000); // Mulai lagi
                });
            });
        }

        // --- 2. LOGIKA TOGGLE FILTER PANEL ---
        const btnToggle = document.getElementById('btnToggleFilter');
        const panelFilter = document.getElementById('panelFilterOpsi');

        if(btnToggle && panelFilter) {
            btnToggle.addEventListener('click', () => {
                if(panelFilter.classList.contains('opacity-0')) {
                    panelFilter.classList.remove('max-h-0', 'opacity-0', 'border-transparent');
                    panelFilter.classList.add('max-h-[500px]', 'opacity-100', 'border-slate-800', 'p-5', 'mt-2');
                } else {
                    panelFilter.classList.add('max-h-0', 'opacity-0', 'border-transparent');
                    panelFilter.classList.remove('max-h-[500px]', 'opacity-100', 'border-slate-800', 'p-5', 'mt-2');
                }
            });
        }
    });
</script>

@endsection