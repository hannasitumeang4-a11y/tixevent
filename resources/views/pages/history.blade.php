@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 bg-slate-50 min-h-screen">
    <h1 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2.5">
        <span>🎟</span> Tiket Saya
    </h1>

    <div class="grid gap-8 max-w-4xl">
        @forelse($orders as $order)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-lg overflow-hidden flex flex-col md:flex-row min-h-[220px]">
            
            {{-- BAGIAN KIRI TIKET: INFORMASI EVENT (WARNA KREM REKREASI ELASTRASI / COKELAT MODERN) --}}
            <div class="p-6 text-slate-800 flex-1 flex flex-col justify-between border-b-2 border-dashed md:border-b-0 md:border-r-2 border-slate-200 relative" style="background-color: #f4ebd0;">
                
                {{-- Efek Bulatan Potongan Karcis Pinggir Tiket Digital --}}
                <div class="hidden md:block absolute -right-3 -top-3 w-6 h-6 rounded-full bg-slate-50 border border-slate-200"></div>
                <div class="hidden md:block absolute -right-3 -bottom-3 w-6 h-6 rounded-full bg-slate-50 border border-slate-200"></div>

                <div>
                    <div class="flex justify-between items-start mb-2">
                        <span class="bg-amber-800 text-white px-2.5 py-0.5 rounded text-[10px] font-bold tracking-widest uppercase">
                            {{ $order->event->category->name ?? 'EVENT PASS' }}
                        </span>
                        <span class="text-xs font-mono font-bold text-amber-900">#{{ $order->order_code }}</span>
                    </div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight line-clamp-2">
                        {{ $order->event->title ?? 'Judul Event' }}
                    </h2>
                    <p class="text-xs text-amber-900/80 font-bold mt-1 flex items-center gap-1">
                        📍 {{ $order->event->location ?? 'Lokasi Acara' }}
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-amber-900/20 text-xs">
                    <div>
                        <span class="text-[10px] text-amber-900/60 block font-bold uppercase">Nama Pemesan</span>
                        <span class="font-bold text-slate-900 truncate block">{{ auth()->user()->name }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-amber-900/60 block font-bold uppercase">Tipe Tiket</span>
                        <span class="font-bold text-indigo-700 block">{{ $order->ticket->ticket_type ?? 'Regular' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-amber-900/60 block font-bold uppercase">Tanggal Pelaksanaan</span>
                        <span class="font-semibold text-slate-800 block">
                            {{ $order->event ? \Carbon\Carbon::parse($order->event->event_date)->format('d M Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- BAGIAN KANAN TIKET: LAYOUT BARCODE UNIK VALIDATION --}}
            <div class="p-6 bg-amber-50/50 md:w-64 flex flex-col justify-center items-center text-center border-t border-slate-100 md:border-t-0">
                <div class="mb-3">
                    @if(strtoupper($order->order_status) == 'PAID' || strtoupper($order->order_status) == 'SUCCESS')
                        <span class="bg-emerald-600 text-white px-4 py-1 rounded-full text-[10px] font-extrabold tracking-widest">
                            VERIFIED PASS
                        </span>
                    @else
                        <span class="bg-amber-500 text-slate-900 px-4 py-1 rounded-full text-[10px] font-extrabold tracking-widest">
                            {{ strtoupper($order->order_status) }}
                        </span>
                    @endif
                </div>

                {{-- Simulasi Komponen Grafik Barcode CSS --}}
                <div class="bg-white border border-slate-300 p-3 rounded-lg shadow-inner inline-block w-full">
                    <div class="w-full h-12 bg-slate-900 flex justify-around items-center overflow-hidden rounded px-2 opacity-90 tracking-tighter" style="letter-spacing: -2px;">
                        @for ($i = 0; $i < 24; $i++)
                            <div class="bg-black h-full" style="width: {{ rand(1, 4) }}px; opacity: {{ rand(7, 10)/10 }};"></div>
                        @endfor
                    </div>
                    <p class="text-[10px] font-mono mt-1 text-slate-600 font-bold tracking-widest">
                        *{{ substr($order->order_code, 4) }}*
                    </p>
                </div>
                
                <p class="text-[10px] text-slate-400 font-medium mt-2">Tunjukkan barcode di atas kepada panitia pintu gerbang masuk.</p>
            </div>

        </div>
        @empty
        <div class="bg-white border border-slate-200 p-12 rounded-2xl text-center space-y-4 max-w-md mx-auto mt-8 shadow-sm">
            <div class="text-6xl opacity-40">🛒</div>
            <h2 class="font-bold text-lg text-slate-800">Belum Ada Tiket yang Dibeli</h2>
            <p class="text-slate-500 text-xs leading-relaxed">
                Kamu belum mengamankan tiket manapun. Yuk, cari event seru di halaman katalog utama!
            </p>
            <a href="{{ route('home') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-6 py-3 rounded-xl shadow-md transition">
                Jelajahi Konser & Event
            </a>
        </div>
        @endforelse
    </div>
    
    {{-- Pagination links link bawaan --}}
    <div class="mt-6 max-w-4xl">
        {{ $orders->links() }}
    </div>
</div>
@endsection