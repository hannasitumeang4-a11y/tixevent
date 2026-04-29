@extends('layouts.admin')

@section('content')

<h1 class="text-xl font-bold mb-6">Dashboard</h1>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="border rounded p-4">
        <p class="text-gray-500">Total Event</p>
        <h2 class="text-2xl font-bold">24</h2>
    </div>
    <div class="border rounded p-4">
        <p class="text-gray-500">Total Pesanan</p>
        <h2 class="text-2xl font-bold">136</h2>
    </div>
    <div class="border rounded p-4">
        <p class="text-gray-500">Total Pendapatan</p>
        <h2 class="text-2xl font-bold">Rp45.250.000</h2>
    </div>
    <div class="border rounded p-4">
        <p class="text-gray-500">Total User</p>
        <h2 class="text-2xl font-bold">128</h2>
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div class="border rounded p-4 h-64">
        <h2 class="font-semibold mb-2">Pesanan Terbaru</h2>
        <table class="w-full text-xs">
            <thead>
                <tr class="text-left text-gray-500">
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ORD-20260429001</td>
                    <td>Budi Santoso</td>
                    <td>29 Apr 2026</td>
                    <td><span class="bg-green-100 text-green-700 px-2 py-1 rounded">Sukses</span></td>
                </tr>
                <tr>
                    <td>ORD-20260429002</td>
                    <td>Siti Aisyah</td>
                    <td>29 Apr 2026</td>
                    <td><span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Pending</span></td>
                </tr>
                <tr>
                    <td>ORD-20260429003</td>
                    <td>Andi Pratama</td>
                    <td>28 Apr 2026</td>
                    <td><span class="bg-red-100 text-red-700 px-2 py-1 rounded">Dibatalkan</span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="border rounded p-4 h-64">
        <h2 class="font-semibold mb-2">Event Terbaru</h2>
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <div class="bg-gray-200 h-10 w-16 rounded"></div>
                <div>
                    <div class="font-semibold">Seminar Digital Marketing 2026</div>
                    <div class="text-xs text-gray-500">20 Mei 2026</div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-gray-200 h-10 w-16 rounded"></div>
                <div>
                    <div class="font-semibold">Web Development Workshop</div>
                    <div class="text-xs text-gray-500">18 Mei 2026</div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-gray-200 h-10 w-16 rounded"></div>
                <div>
                    <div class="font-semibold">Music Concert 2026</div>
                    <div class="text-xs text-gray-500">10 Mei 2026</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection