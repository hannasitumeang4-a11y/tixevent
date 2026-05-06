@extends('layouts.admin')

@section('content')

<h1 class="text-xl font-bold mb-6 text-gray-800">Dashboard</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-4 text-white">
        <p class="text-indigo-100 text-sm">Total Event</p>
        <h2 class="text-3xl font-bold">{{ $totalEvent }}</h2>
    </div>

    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg p-4 text-white">
        <p class="text-emerald-100 text-sm">Total Pesanan</p>
        <h2 class="text-3xl font-bold">{{ $totalOrders }}</h2>
    </div>

    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg p-4 text-white">
        <p class="text-amber-100 text-sm">Total Pendapatan</p>
        <h2 class="text-3xl font-bold">Rp{{ number_format($totalRevenue) }}</h2>
    </div>

    <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-lg p-4 text-white">
        <p class="text-violet-100 text-sm">Total User</p>
        <h2 class="text-3xl font-bold">{{ $totalUsers }}</h2>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    <!-- Pesanan -->
    <div class="bg-white border rounded-lg p-4">
        <h2 class="font-semibold mb-4 text-gray-800">📦 Pesanan Terbaru</h2>

        @if(empty($recentOrders))
            <div class="text-center py-8 text-gray-500">
                <p>Belum ada pesanan</p>
            </div>
        @else
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr class="border-b">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user_name }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Event -->
    <div class="bg-white border rounded-lg p-4">
        <h2 class="font-semibold mb-4 text-gray-800">🎉 Event Terbaru</h2>

        @if($recentEvents->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <p>Belum ada event</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($recentEvents as $event)
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg">
                    <div class="h-12 w-16 bg-gray-200 flex items-center justify-center">
                        🎟️
                    </div>

                    <div class="flex-1">
                        <div class="font-semibold">{{ $event->title }}</div>
                        <div class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }} |
                            {{ $event->location }}
                        </div>
                    </div>

                    <div class="text-sm font-bold">
                        Rp{{ number_format($event->price) }}
                    </div>
                </div>
                @endforeach
            </div>
        @endif


                    <div class="text-xs text-gray-500">10 Mei 2026</div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection