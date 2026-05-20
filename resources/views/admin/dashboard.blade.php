@extends('layouts.admin')

@section('content')

<h1 class="text-xl font-bold mb-6 text-gray-800">Pusat Kendali Promotor</h1>

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

<div class="mb-6 bg-white p-6 border rounded-lg shadow-sm">
    <h2 class="font-bold text-gray-800 text-lg mb-4">🔮 Katalog Konser Aktif</h2>
    
    @if($myEvents->isEmpty())
        <div class="text-center py-12 text-gray-400">
            <p class="mb-3">Belum ada event yang terdaftar di sistem.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($myEvents as $event)
            <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden p-4 text-white flex flex-col justify-between">
                <div>
                    <div class="w-full h-48 bg-slate-800 rounded-lg mb-3 overflow-hidden flex items-center justify-center text-3xl">
                        @if($event->images && $event->images->first())
                            <img src="{{ asset($event->images->first()->image_path) }}" class="w-full h-full object-cover">
                        @else
                            🎟️
                        @endif
                    </div>
                    
                    <span class="bg-blue-600/20 text-blue-400 text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded">Rp{{ number_format($event->price, 0, ',', '.') }}</span>
                    <h3 class="font-bold text-base mt-1 line-clamp-1 text-gray-100">{{ $event->title }}</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ strip_tags($event->description) }}</p>
                    
                    <div class="text-[11px] text-gray-400 mt-3 space-y-1 bg-slate-800/40 p-2 rounded-lg">
                        <div>📅 {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</div>
                        <div class="truncate">📍 {{ $event->location }}</div>
                    </div>
                </div>

                <div class="mt-4 flex gap-2 w-full">
                    <a href="{{ route('organizer.events.show', $event->event_id) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs text-center font-semibold py-2 px-3 rounded-lg shadow transition duration-200">
                        👥 Manifes
                    </a>
                    <a href="{{ route('organizer.events.edit', $event->event_id) }}" class="flex-1 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs text-center font-bold py-2 px-3 rounded-lg shadow transition duration-200">
                        ⚙️ Atur Tiket
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @endif
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

@endsection