@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold mb-2">Pusat Laporan Eksekutif (Financial & Performance Report)</h1>
        <p class="text-sm text-gray-500">Analisis metrik pendapatan platform, performa penjualan konser terlaris, serta data statistik promotor teraktif untuk kebutuhan evaluasi/demo aplikasi.</p>
    </div>
    
    <a href="{{ route('admin.report.export-excel') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded shadow transition-colors flex items-center gap-2">
        📊 Ekspor laporan ke Excel (.xlsx)
    </a>
</div>

<div class="print-wide space-y-8">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 text-white p-6 rounded border shadow-sm">
            <div class="text-xs font-bold uppercase tracking-wider opacity-80">Total Uang Masuk (Gross Revenue)</div>
            <div class="text-3xl font-extrabold mt-2">Rp{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
            <div class="text-xs mt-4 italic opacity-90">*Akumulasi dari seluruh transaksi checkout tiket berstatus sukses.</div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white p-6 rounded border shadow-sm">
            <div class="text-xs font-bold uppercase tracking-wider opacity-80">Total Tiket Terjual (Volume Transaksi)</div>
            <div class="text-3xl font-extrabold mt-2">{{ $totalTicketsSold ?? 0 }} Tiket</div>
            <div class="text-xs mt-4 italic opacity-90">*Menunjukkan tingkat perputaran kuota tiket dalam platform.</div>
        </div>
    </div>

    <div class="space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="bg-white border rounded p-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                    🔥 Top 5 Event Terlaris (Sumbangsih Tertinggi)
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="text-gray-500 border-b text-xs uppercase">
                                <th class="pb-2">Nama Event</th>
                                <th class="pb-2 text-center">Tiket Terjual</th>
                                <th class="pb-2 text-right">Total Omset</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($topEvents as $topEvent)
                            <tr>
                                <td class="py-3 font-semibold text-gray-800">{{ $topEvent->event->title ?? 'Event Dihapus' }}</td>
                                <td class="py-3 text-center text-gray-600 font-medium">{{ $topEvent->total_sales }}x Transaksi</td>
                                <td class="py-3 text-right text-emerald-600 font-bold">Rp{{ number_format($topEvent->revenue, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-400 italic text-xs">Belum ada data penjualan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white border rounded p-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                    🏢 Top 5 Promotor / Organizer Teraktif
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="text-gray-500 border-b text-xs uppercase">
                                <th class="pb-2">Nama Instansi / Akun</th>
                                <th class="pb-2">Email Kontak</th>
                                <th class="pb-2 text-center">Event Dibuat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($topPromoters as $promoter)
                            <tr>
                                <td class="py-3 font-semibold text-gray-800">{{ $promoter->organizer->name ?? 'Promotor Default' }}</td>
                                <td class="py-3 text-gray-500 text-xs">{{ $promoter->organizer->email ?? 'N/A' }}</td>
                                <td class="py-3 text-center"><span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded text-xs font-bold">{{ $promoter->total_events }} Event</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-400 italic text-xs">Belum ada promotor terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="bg-white border rounded p-6 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 mb-4">📜 Journal Log Transaksi Real-time (Audit Trail)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="text-gray-500 border-b text-xs uppercase bg-gray-50">
                            <th class="p-3">Kode Order</th>
                            <th class="p-3">Pembeli</th>
                            <th class="p-3">Konser & Tiket</th>
                            <th class="p-3">Metode</th>
                            <th class="p-3 text-right">Uang Masuk</th>
                            <th class="p-3 text-center">Waktu Berhasil</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($allOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-mono text-xs text-gray-700 font-bold">{{ $order->order_code }}</td>
                            <td class="p-3">
                                <div class="font-semibold text-gray-900">{{ $order->user->name ?? 'Anonymous' }}</div>
                                <div class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</div>
                            </td>
                            <td class="p-3">
                                <div class="font-medium text-gray-800">{{ $order->event->title ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">Jenis: {{ $order->ticket->ticket_name ?? 'Regular' }}</div>
                            </td>
                            <td class="p-3">
                                <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded text-xs font-medium uppercase">
                                    {{ $order->payment_method }}
                                </span>
                            </td>
                            <td class="p-3 text-right text-gray-900 font-semibold">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-center text-xs text-gray-500">
                                {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-400 italic">Belum ada dana penjualan tiket masuk ke sistem database Eventix.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection