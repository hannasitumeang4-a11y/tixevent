@extends('layouts.app')

@section('content')

<!-- HERO -->
<div style="text-align:center; margin:20px;">
    <h1>Temukan Event Seru Untukmu</h1>
    <p>Berbagai event menarik menantimu. Pesan tiket sekarang!</p>
</div>

<!-- KATEGORI -->
<div style="text-align:center; margin-bottom:30px;">
    
    <h3 style="margin-bottom:15px;">Kategori Event</h3>

    <div style="display:flex; justify-content:center; gap:10px; flex-wrap:wrap;">
        
        <!-- SEMUA -->
        <a href="/" style="
            padding:8px 16px;
            border:none;
            border-radius:20px;
            text-decoration:none;
            display:inline-block;
            background: {{ request('category') ? '#fff' : '#333' }};
            color: {{ request('category') ? '#333' : '#fff' }};
        ">
            Semua
        </a>

        <!-- DINAMIS -->
        @foreach($categories as $cat)
            <a href="/?category={{ $cat->category_id }}" style="
                padding:8px 16px;
                border-radius:20px;
                border:1px solid #333;
                text-decoration:none;
                display:inline-block;
                background: {{ request('category') == $cat->category_id ? '#333' : '#fff' }};
                color: {{ request('category') == $cat->category_id ? '#fff' : '#333' }};
            ">
                {{ $cat->name }}
            </a>
        @endforeach

    </div>
</div>

<!-- EVENT -->
<div class="container">
    <h3 style="text-align:center;">Event Terbaru</h3>

    @if($events->isEmpty())
        <p style="text-align:center;">Belum ada event tersedia</p>
    @endif

    <div style="display:flex; flex-wrap:wrap; gap:20px; justify-content:center;">
        @foreach($events as $event)
            <div style="
                border:1px solid #ddd;
                padding:15px;
                width:260px;
                border-radius:10px;
                box-shadow:0 2px 5px rgba(0,0,0,0.1);
            ">
                
                <!-- 🔥 FIX DI SINI -->
                <h4>{{ $event->title }}</h4>

                <p><b>Kategori:</b> {{ $event->category->name ?? '-' }}</p>

                <p><b>Lokasi:</b> {{ $event->location ?? '-' }}</p>

                <p>
                    <b>Tanggal:</b>
                    {{ $event->event_date 
                        ? \Carbon\Carbon::parse($event->event_date)->format('d M Y') 
                        : '-' 
                    }}
                </p>

                <p>
                    <b>Harga:</b> 
                    {{ $event->price ? 'Rp' . number_format($event->price) : '-' }}
                </p>

                <a href="/events/{{ $event->slug ?? $event->event_id }}" style="
                    display:inline-block;
                    margin-top:10px;
                    text-decoration:none;
                    color:#007bff;
                ">
                    Lihat Detail
                </a>

            </div>
        @endforeach
    </div>

    <!-- PAGINATION -->
    <div style="margin-top:30px; text-align:center;">
        {{ $events->withQueryString()->links() }}
    </div>

</div>

@endsection