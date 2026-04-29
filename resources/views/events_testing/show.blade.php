@extends('layouts.app')

@section('content')

<div style="max-width:900px; margin:40px auto; padding:20px;">

    <!-- CARD DETAIL -->
    <div style="
        border:1px solid #ddd;
        border-radius:12px;
        padding:25px;
        box-shadow:0 4px 10px rgba(0,0,0,0.08);
        background:white;
    ">

        <!-- JUDUL -->
        <h2 style="margin-bottom:15px;">
            {{ $event->name }}
        </h2>

        <!-- KATEGORI -->
        <p style="margin-bottom:10px;">
            <b>Kategori:</b> {{ $event->category->name ?? '-' }}
        </p>

        <!-- LOKASI -->
        <p style="margin-bottom:10px;">
            <b>Lokasi:</b> {{ $event->location ?? '-' }}
        </p>

        <!-- TANGGAL -->
        <p style="margin-bottom:10px;">
            <b>Tanggal:</b>
            {{ $event->event_date 
                ? \Carbon\Carbon::parse($event->event_date)->format('d M Y') 
                : '-' 
            }}
        </p>

        <!-- HARGA -->
        <p style="margin-bottom:20px;">
            <b>Harga:</b>
            {{ $event->price ? 'Rp' . number_format($event->price) : '-' }}
        </p>

        <!-- DESKRIPSI (OPSIONAL) -->
        @if($event->description)
            <div style="margin-bottom:20px;">
                <b>Deskripsi:</b>
                <p style="margin-top:5px; line-height:1.6;">
                    {{ $event->description }}
                </p>
            </div>
        @endif

        <!-- BUTTON -->
        <div style="display:flex; gap:10px;">

            <!-- PESAN TIKET -->
            <a href="/checkout" style="
                padding:10px 18px;
                background:black;
                color:white;
                border-radius:6px;
                text-decoration:none;
            ">
                Pesan Tiket
            </a>

            <!-- KEMBALI -->
            <a href="/" style="
                padding:10px 18px;
                background:#eee;
                color:#333;
                border-radius:6px;
                text-decoration:none;
            ">
                Kembali
            </a>

        </div>

    </div>

</div>

@endsection