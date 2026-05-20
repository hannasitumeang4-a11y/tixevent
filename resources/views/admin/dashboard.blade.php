@extends('layouts.admin')

@section('content')

<h1 class="text-xl font-bold mb-6 text-gray-800">Pusat Kendali Promotor</h1>

@if(($reportedEventsCount ?? 0) > 0 || ($stuckTransactionsCount ?? 0) > 0)
<div class="mb-6 space-y-2">
    @if(($reportedEventsCount ?? 0) > 0)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm flex items-center justify-between animate-pulse">
            <div class="flex items-center space-x-3">
                <span class="text-red-500 text-xl">⚠️</span>
                <div>
                    <h3 class="text-red-800 font-bold text-sm">Laporan Pelanggaran Event</h3>
                    <p class="text-red-700 text-xs">Ada {{ $reportedEventsCount }} event yang dilaporkan oleh pengguna dan membutuhkan moderasi segera.</p>
                </div>
            </div>
            <a href="{{ route('admin.events') }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-1.5 rounded transition">Periksa</a>
        </div>
    @endif

    @if(($stuckTransactionsCount ?? 0) > 0)
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="text-amber-500 text-xl">⏳</span>
                <div>
                    <h3 class="text-amber-800 font-bold text-sm">Transaksi Menggantung (Stuck)</h3>
                    <p class="text-amber-700 text-xs">Terdapat {{ $stuckTransactionsCount }} transaksi berstatus PENDING yang melewati batas waktu pembayaran.</p>
                </div>
            </div>
            <a href="#tabel-verifikasi" class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-3 py-1.5 rounded transition">Lihat Log</a>
        </div>
    @endif
</div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-4 text-white shadow">
        <p class="text-indigo-100 text-sm">Total Event</p>
        <h2 class="text-3xl font-bold">{{ $totalEvent }} Terbit</h2>
    </div>

    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg p-4 text-white shadow">
        <p class="text-emerald-100 text-sm">Total Pesanan</p>
        <h2 class="text-3xl font-bold">{{ $totalOrders }} Order</h2>
    </div>

    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg p-4 text-white shadow">
        <p class="text-amber-100 text-sm">Total Pendapatan</p>
        <h2 class="text-3xl font-bold">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h2>
    </div>

    <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-lg p-4 text-white shadow">
        <p class="text-violet-100 text-sm">Total User (Pembeli)</p>
        <h2 class="text-3xl font-bold">{{ $totalUsers }} Pembeli</h2>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    
    <div id="tabel-verifikasi" class="lg:col-span-2 bg-white p-6 border rounded-lg shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800 text-lg flex items-center gap-2">⚡ Verifikasi Pembayaran Real-time</h2>
                <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2 py-0.5 rounded-full">Butuh Tindakan</span>
            </div>

            @if(empty($pendingPayments) || count($pendingPayments) == 0)
                <div class="text-center py-16 text-gray-400">
                    <p class="text-2xl mb-2">✅</p>
                    <p class="text-sm">Semua bukti transfer bersih! Tidak ada antrean pembayaran saat ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 border-b bg-gray-50">
                                <th class="p-3">ID Order</th>
                                <th class="p-3">Pembeli & Event</th>
                                <th class="p-3">Bukti Transfer</th>
                                <th class="p-3 text-center">Aksi / Otoritas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pendingPayments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-semibold text-gray-700">#{{ $payment->order_id }}</td>
                                <td class="p-3">
                                    <div class="font-medium text-gray-800">{{ $payment->user_name }}</div>
                                    <div class="text-gray-400 text-[10px] truncate max-w-[180px]">{{ $payment->event_title }}</div>
                                </td>
                                <td class="p-3">
                                    @if($payment->payment_proof)
                                        <a href="{{ asset($payment->payment_proof) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-medium inline-flex items-center gap-1 bg-indigo-50 px-2 py-1 rounded border border-indigo-100 transition">
                                            🔍 Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada bukti</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.orders.approve', $payment->order_id) }}" method="POST" onsubmit="return confirm('Setujui pembayaran ini? Tiket otomatis dikirim ke pembeli.')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1.5 px-3 rounded text-[11px] shadow transition">
                                                Setujui
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.orders.reject', $payment->order_id) }}" method="POST" onsubmit="return confirm('Tolak pembayaran ini jika bukti palsu/tidak valid?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-1.5 px-3 rounded text-[11px] border border-red-200 transition">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white p-6 border rounded-lg shadow-sm flex flex-col justify-between">
        <div>
            <h2 class="font-bold text-gray-800 text-lg mb-1">📈 Grafik Omset Platform</h2>
            <p class="text-gray-400 text-xs mb-4">Tren visual pendapatan penjualan tiket</p>
            <div class="w-full relative" style="height: 220px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="bg-white border rounded-lg p-4 shadow-sm">
        <h2 class="font-semibold mb-4 text-gray-800 flex items-center gap-1">📦 Pesanan Terbaru</h2>

        @if(empty($recentOrders) || count($recentOrders) == 0)
            <div class="text-center py-12 text-gray-400 text-xs">
                <p>Belum ada log pesanan tiket masuk.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-gray-500 border-b bg-gray-50">
                            <th class="p-2">ID</th>
                            <th class="p-2">Nama Penonton</th>
                            <th class="p-2">Tanggal Order</th>
                            <th class="p-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-2 font-medium">#{{ $order->order_id }}</td>
                            <td class="p-2">{{ $order->user_name }}</td>
                            <td class="p-2 text-gray-500">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="p-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $order->order_status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ strtoupper($order->order_status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="bg-white border rounded-lg p-4 shadow-sm">
        <h2 class="font-semibold mb-4 text-gray-800 flex items-center gap-1">🎉 Ringkasan Event Terkini</h2>

        @if($recentEvents->isEmpty())
            <div class="text-center py-12 text-gray-400 text-xs">
                <p>Belum ada histori event baru.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($recentEvents as $rEvent)
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg border border-transparent hover:border-gray-100 transition">
                    <div class="h-10 w-12 bg-indigo-50 border border-indigo-100 rounded flex items-center justify-center text-lg">
                        🎟️
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-xs truncate text-gray-800">{{ $rEvent->title }}</div>
                        <div class="text-[10px] text-gray-400 truncate">
                            {{ \Carbon\Carbon::parse($rEvent->event_date)->format('d M Y') }} | {{ $rEvent->location }}
                        </div>
                    </div>

                    <div class="text-xs font-bold text-gray-700 bg-gray-100 px-2 py-1 rounded">
                        Rp{{ number_format($rEvent->price, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Data dummy atau data dari Laravel backend ($chartLabels & $chartData)
        const labels = {!! json_encode($chartLabels ?? ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4']) !!};
        const dataRevenue = {!! json_encode($chartData ?? [1200000, 4500000, 3100000, 9250000]) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omset (Rp)',
                    data: dataRevenue,
                    borderColor: '#f59e0b', /* Warna amber menyesuaikan tema dashboard */
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#d97706'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            },
                            font: { size: 9 }
                        },
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>

@endsection