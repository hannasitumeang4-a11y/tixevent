@extends('layouts.app')

@section('content')
{{-- STYLE KHUSUS UNTUK PROSES CETAK NOTA --}}
<style>
    @media print {
        /* 1. Sembunyikan seluruh elemen global dari layouts.app (Navbar, Footer, Sidebar, dll) */
        nav, header, footer, sidebar, aside, .navbar, [class*="nav"], [id*="nav"] {
            display: none !important;
        }

        /* 2. Bersihkan background abu-abu bawaan wrapper luar */
        .min-h-screen, .bg-slate-50 {
            background-color: transparent !important;
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
            min-height: auto !important;
        }

        /* 3. Atur kotak nota agar bersih, rapi, dan tidak berbayang kotor di kertas */
        #invoice-card {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important; /* border tipis agar tetap rapi saat dicetak */
            margin: 0 auto !important;
            padding: 2rem !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        /* 4. Paksa browser untuk mencetak warna & background asli komponen barcode */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-scheme: light !important;
        }
    }
</style>

<div class="max-w-3xl mx-auto p-8 bg-slate-50 min-h-screen">

    {{-- Ditambahkan id="invoice-card" untuk target styling print --}}
    <div id="invoice-card" class="bg-white rounded-2xl shadow-md border border-slate-200 p-8">

        <div class="text-center border-b border-slate-100 pb-6">
            <h1 class="text-3xl font-extrabold text-emerald-600 flex items-center justify-center gap-2">
                <span>✅</span> Pendaftaran Berhasil
            </h1>
            <p class="text-slate-500 mt-2 text-sm font-medium">
                Terima kasih sudah melakukan pemesanan pass lewat platform kami.
            </p>
        </div>

        <div class="mt-8 space-y-4 text-sm text-slate-600">

            <div class="flex justify-between items-center py-1">
                <span class="font-medium text-slate-500">Kode Pesanan</span>
                <b class="text-slate-800 font-mono">{{ $order->order_code }}</b>
            </div>

            <div class="flex justify-between items-center py-1">
                <span class="font-medium text-slate-500">Nama</span>
                <b class="text-slate-800">{{ auth()->user()->name }}</b>
            </div>

            <div class="flex justify-between items-center py-1">
                <span class="font-medium text-slate-500">Email</span>
                <b class="text-slate-800">{{ auth()->user()->email }}</b>
            </div>

            <div class="flex justify-between items-center py-1">
                <span class="font-medium text-slate-500">Metode Akses</span>
                <b class="text-indigo-600 uppercase">
                    {{ $order->payment_method == 'free_pass' ? 'FREE ACCESS (GRATIS)' : strtoupper($order->payment_method) }}
                </b>
            </div>

            <div class="flex justify-between items-center py-1">
                <span class="font-medium text-slate-500">Status Akses</span>
                <span class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold uppercase">
                    {{ $order->order_status }}
                </span>
            </div>

            <div class="border-t border-slate-200 pt-5 mt-2 flex justify-between items-center text-xl font-bold">
                <span class="text-slate-700 text-base font-semibold">Total Tagihan</span>
                <span class="text-indigo-600 font-extrabold text-2xl">
                    {{ $order->total_amount == 0 ? 'Rp0 (Free Pass)' : 'Rp' . number_format($order->total_amount, 0, ',', '.') }}
                </span>
            </div>

        </div>

        {{-- SIMULASI BARCODE NOTA UNTUK MEMUDAHKAN SCREENSHOT TIKET CEPAT --}}
        <div class="mt-8 p-4 bg-slate-50 border border-slate-200 rounded-xl text-center">
            <div class="w-48 h-10 bg-slate-800 mx-auto flex justify-around items-center overflow-hidden px-2 rounded opacity-75" style="letter-spacing: -1px;">
                @for ($i = 0; $i < 18; $i++)
                    <div class="bg-black h-full" style="width: {{ rand(1, 3) }}px;"></div>
                @endfor
            </div>
            <p class="text-[9px] font-mono text-slate-400 mt-1 uppercase tracking-widest">Digital Order Node Security Code</p>
        </div>

        {{-- Tombol otomatis hilang saat cetak berkat class print:hidden --}}
        <div class="grid grid-cols-2 gap-4 mt-8 print:hidden">

            <button onclick="window.print()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl transition duration-200 shadow-sm text-center text-sm">
                Unduh Nota
            </button>

            <a href="{{ route('history') }}"
               class="border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold py-3.5 rounded-xl text-center transition duration-200 text-sm">
                Lihat E-Ticket Saya
            </a>

        </div>

    </div>
</div>
@endsection