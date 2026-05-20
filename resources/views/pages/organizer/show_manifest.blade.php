@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="manifest-container py-4">
    <div class="container-fluid px-md-5">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 pb-3 border-bottom border-secondary gap-3">
            <div>
                <a href="{{ route('organizer.dashboard') }}" class="text-muted text-decoration-none small mb-2 d-inline-flex align-items-center back-link">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Dashboard
                </a>
                <h1 class="text-white font-weight-bold mb-1" style="font-size: 24px; letter-spacing: -0.5px;">Manifes & Laporan Penjualan</h1>
                <p class="text-muted small mb-0">
                    Data transaksi tiket untuk event: <span class="text-info font-weight-bold">{{ $event->title }}</span>
                </p>
            </div>
            <div>
                <a href="{{ route('organizer.events.export', $event->event_id ?? $event->id) }}" class="btn btn-success btn-sm px-4 py-2 rounded-pill font-weight-bold shadow-sm d-inline-flex align-items-center">
                    <i class="fa-solid fa-file-excel mr-2"></i> Export Data Excel
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="stat-card p-3 rounded-lg border border-secondary" style="background: rgba(16, 185, 129, 0.1);">
                    <div class="text-success small font-weight-bold text-uppercase mb-1"><i class="fa-solid fa-sack-dollar mr-1"></i> Total Pendapatan</div>
                    <h3 class="text-white font-weight-bold mb-0">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="stat-card p-3 rounded-lg border border-secondary" style="background: rgba(56, 189, 248, 0.1);">
                    <div class="text-info small font-weight-bold text-uppercase mb-1"><i class="fa-solid fa-ticket mr-1"></i> Tiket Terjual</div>
                    <h3 class="text-white font-weight-bold mb-0">{{ $stats['total_tickets'] }} <span style="font-size: 14px; font-weight: normal;" class="text-muted">Lembar</span></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card p-3 rounded-lg border border-secondary" style="background: rgba(168, 85, 247, 0.1);">
                    <div class="text-purple small font-weight-bold text-uppercase mb-1" style="color: #c084fc;"><i class="fa-solid fa-users mr-1"></i> Total Transaksi</div>
                    <h3 class="text-white font-weight-bold mb-0">{{ $stats['total_buyers'] }} <span style="font-size: 14px; font-weight: normal;" class="text-muted">Pembeli</span></h3>
                </div>
            </div>
        </div>

        <div class="card table-wrapper" style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; overflow: hidden;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless text-white mb-0 custom-table">
                        <thead style="background: rgba(15, 23, 42, 0.6); border-bottom: 1px solid #334155;">
                            <tr>
                                <th class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 1px;">ID Order</th>
                                <th class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 1px;">Nama Penonton</th>
                                <th class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 1px;">Kategori</th>
                                <th class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 1px;">Qty</th>
                                <th class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 1px;">Subtotal</th>
                                <th class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: 1px;">Tanggal Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($manifests as $item)
                                <tr>
                                    <td class="font-weight-bold text-info">#{{ $item->order_id }}</td>
                                    <td class="font-weight-bold">{{ $item->user_name }}</td>
                                    <td>
                                        <span class="badge {{ $item->ticket_type == 'VIP' ? 'badge-warning' : ($item->ticket_type == 'PRESALE' ? 'badge-success' : 'badge-primary') }} px-2 py-1">
                                            {{ $item->ticket_type }}
                                        </span>
                                    </td>
                                    <td>{{ $item->quantity }}x</td>
                                    <td class="font-weight-bold text-success">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    <td class="text-muted small">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fa-solid fa-ticket text-muted mb-3 d-block" style="font-size: 32px; opacity: 0.5;"></i>
                                        <h6 class="text-white font-weight-bold mb-1">Belum Ada Transaksi</h6>
                                        <p class="text-muted small mb-0">Saat ini belum ada penonton yang membeli tiket untuk event ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .manifest-container { font-family: 'Plus Jakarta Sans', sans-serif; }
    .custom-table th, .custom-table td { padding: 14px 20px; vertical-align: middle; }
    .custom-table tbody tr { border-bottom: 1px solid #334155; }
    .custom-table tbody tr:last-child { border-bottom: none; }
    .custom-table tbody tr:hover { background: rgba(255, 255, 255, 0.02); }
    .back-link:hover { color: #fff !important; transform: translateX(-2px); transition: all 0.2s; }
</style>
@endsection