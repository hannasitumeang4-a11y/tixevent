@extends('layouts.app')

@section('content')
<div class="container py-5" style="background-color: #0f172a; color: #f8fafc; min-height: 100vh;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-white">{{ $event->title }}</h1>
            <p class="text-muted">Manajemen Transaksi & Manifes Penjualan Tiket</p>
        </div>
        <a href="{{ route('organizer.dashboard') }}" class="btn text-white px-4" style="background-color: #334155; border-radius: 8px;">
            Kembali ke Dashboard
        </a>
    </div>

    <div class="row mb-5">
        <div class="col-md-6 mb-3">
            <div class="p-4" style="background-color: #1e293b; border-radius: 12px; border-left: 5px solid #a855f7;">
                <p class="text-muted text-uppercase font-weight-bold small mb-1">Total Tiket Terjual</p>
                <h3 class="text-white font-weight-bold mb-0">{{ $totalTerjual }} Tiket</h3>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="p-4" style="background-color: #1e293b; border-radius: 12px; border-left: 5px solid #22c55e;">
                <p class="text-muted text-uppercase font-weight-bold small mb-1">Total Pendapatan Terkumpul</p>
                <h3 class="text-white font-weight-bold mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <div class="p-4" style="background-color: #1e293b; border-radius: 12px;">
        <h3 class="h5 text-white font-weight-bold mb-4">Daftar Manifes Pembeli Tiket</h3>
        
        <div class="table-responsive">
            <table class="table text-white" style="background-color: #1e293b;">
                <thead>
                    <tr style="border-bottom: 2px solid #334155; color: #94a3b8;">
                        <th>Nama Pembeli</th>
                        <th>Email</th>
                        <th>Tanggal Beli</th>
                        <th>Jumlah Tiket</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr style="border-bottom: 1px solid #334155;">
                            <td class="font-weight-bold" style="color: #cbd5e1;">{{ $order->nama_pembeli }}</td>
                            <td>{{ $order->email_pembeli }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->tanggal_beli)->translatedFormat('d F Y H:i') }} WIB</td>
                            <td>{{ $order->quantity }} Pcs</td>
                            <td class="text-success font-weight-bold">
                                Rp {{ number_format($order->quantity * $order->harga_satuan, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <span class="d-block mb-2" style="font-size: 24px;">🎟️</span>
                                Belum ada transaksi pembelian tiket untuk event ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection