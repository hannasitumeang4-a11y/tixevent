@extends('layouts.admin')

@section('content')

<h1 class="text-xl font-bold mb-6 text-gray-800">Dashboard</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Event -->
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-4 text-white">
        <p class="text-indigo-100 text-sm">Total Event</p>
        <h2 class="text-3xl font-bold">{{ $totalEvent }}</h2>
    </div>
    <!-- Total Pesanan -->
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg p-4 text-white">
        <p class="text-emerald-100 text-sm">Total Pesanan</p>
        <h2 class="text-3xl font-bold">{{ $totalOrders }}</h2>
    </div>
    <!-- Total Pendapatan -->
    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg p-4 text-white">
        <p class="text-amber-100 text-sm">Total Pendapatan</p>
        <h2 class="text-3xl font-bold">Rp{{ number_format($totalRevenue) }}</h2>
    </div>
    <!-- Total User -->
    <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-lg p-4 text-white">
        <p class="text-violet-100 text-sm">Total User</p>
        <h2 class="text-3xl font-bold">{{ $totalUsers }}</h2>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <!-- Pesanan Terbaru -->
    <div class="bg-white border rounded-lg p-4">
        <h2 class="font-semibold mb-4 text-gray-800 flex items-center gap-2">
            <span>📦</span> Pesanan Terbaru
        </h2>
        @if(empty($recentOrders))
            <div class="text-center py-8 text-gray-500">
                <p>Belum ada pesanan</p>
            </div>
        @else
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-2">ID</th>
                        <th class="pb-2">Nama</th>
                        <th class="pb-2">Tanggal</th>
                        <th class="pb-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr class="border-b">
                        <td class="py-2">{{ $order->id }}</td>
                        <td class="py-2">{{ $order->user_name }}</td>
                        <td class="py-2">{{ $order->created_at }}</td>
                        <td class="py-2">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                {{ $order->status == 'success' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
    <!-- Event Terbaru -->
    <div class="bg-white border rounded-lg p-4">
        <h2 class="font-semibold mb-4 text-gray-800 flex items-center gap-2">
            <span>🎉</span> Event Terbaru
        </h2>
        @if($recentEvents->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <p>Belum ada event</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($recentEvents as $event)
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition">
                    <div class="bg-gradient-to-br from-indigo-100 to-purple-100 h-12 w-16 rounded flex items-center justify-center text-2xl">
                        🎟️
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-800 truncate">{{ $event->title }}</div>
                        <div class="text-xs text-gray-500">
                            📅 {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('d M Y') : 'TBA' }}
                            &nbsp;|&nbsp; 📍 {{ $event->location ?? 'TBA' }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-indigo-600">
                            {{ $event->price ? 'Rp' . number_format($event->price) : 'Gratis' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection
                    <div class="text-xs text-gray-500">10 Mei 2026</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection