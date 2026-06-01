@extends('layouts.admin') {{-- Sesuaikan dengan nama template admin/master kamu --}}

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="mb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-500 hover:text-indigo-600 font-medium">← Kembali ke Dashboard</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        {{-- Header Detail --}}
        <div class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Detail Validasi Pembayaran</h2>
                <p class="text-xs text-slate-500">ID Pesanan: <span class="font-mono font-bold">#{{ $order->order_id }}</span></p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->order_status == 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                STATUS: {{ strtoupper($order->order_status) }}
            </span>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Informasi Sisi Kiri: Data Transaksi --}}
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Informasi Pemesan & Tiket</h3>
                <div class="bg-slate-50 rounded-xl p-4 space-y-3 text-sm border border-slate-100">
                    <p class="text-slate-600"><strong>Nama Customer:</strong> <span class="text-slate-800 block mt-0.5">{{ $order->user_name }}</span></p>
                    <p class="text-slate-600"><strong>Email:</strong> <span class="text-slate-800 block mt-0.5 font-mono text-xs">{{ $order->email }}</span></p>
                    <p class="text-slate-600"><strong>Nama Event:</strong> <span class="text-slate-800 block mt-0.5 font-medium">{{ $order->event_title }}</span></p>
                    <p class="text-slate-600"><strong>Jenis Tiket:</strong> <span class="text-slate-800 block mt-0.5"><span class="bg-indigo-100 text-indigo-800 text-[10px] font-bold px-2 py-0.5 rounded">{{ strtoupper($order->ticket_type) }}</span></span></p>
                    <p class="text-slate-600"><strong>Jumlah Beli:</strong> <span class="text-slate-800 block mt-0.5">{{ $order->quantity }} Tiket</span></p>
                    <div class="pt-2 border-t border-slate-200">
                        <p class="text-slate-600"><strong>Total Tagihan Wajib Masuk:</strong></p>
                        <p class="text-xl font-black text-indigo-600 mt-0.5">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Informasi Sisi Kanan: Bukti Fisik Struk Pembayaran --}}
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Bukti Transfer Unggahan User</h3>
                <div class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl p-4 bg-slate-50 min-h-[250px]">
                    @if($order->payment_proof)
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-center group">
                            <img src="{{ asset('storage/' . $order->payment_proof) }}" class="max-w-[200px] h-auto rounded-lg shadow-md border border-slate-300 group-hover:scale-105 transition duration-200">
                            <span class="text-[11px] text-slate-400 block mt-2 group-hover:text-indigo-600 font-medium">Klik gambar untuk memperbesar penuh ↗</span>
                        </a>
                    @else
                        <div class="text-center p-4">
                            <span class="text-3xl">⚠️</span>
                            <p class="text-xs text-amber-600 font-semibold mt-2">Tidak Ada Bukti Transfer Terupload</p>
                            <p class="text-[10px] text-slate-400 mt-1">Transaksi mungkin menggunakan metode gratis atau belum menyelesaikan checkout.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Panel Aksi Penentu (Hanya Muncul Jika Status Belum Paid) --}}
        @if($order->order_status != 'paid')
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
            {{-- AKSI TOLAK (HAPUS PERMANEN DARI RIWAYAT) --}}
            <form action="{{ route('admin.orders.reject', $order->order_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin BUKTI INI PALSU? Pesanan akan dihapus permanen dan hilang dari riwayat user.')">
                @csrf
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition">
                    ❌ Tolak & Hapus Transaksi Curang
                </button>
            </form>

            {{-- AKSI SETUJU (SAH) --}}
            <form action="{{ route('admin.orders.approve', $order->order_id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition">
                    ✓ Sah, Terbitkan E-Tiket
                </button>
            </form>
        </div>
        @else
        <div class="p-4 bg-emerald-50 text-center border-t border-emerald-100 text-xs font-medium text-emerald-800">
            🔒 Transaksi ini telah sah diverifikasi dan tidak dapat diubah kembali.
        </div>
        @endif
    </div>
</div>
@endsection