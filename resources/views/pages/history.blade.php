@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Riwayat Transaksi</h1>
<div class="bg-white border rounded p-6">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-500">
                <th>ID Order</th>
                <th>Event</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ORD-20260429001</td>
                <td>Seminar Digital Marketing 2026</td>
                <td>20 Mei 2026</td>
                <td>Rp900.000</td>
                <td><span class="bg-green-100 text-green-700 px-2 py-1 rounded">Sukses</span></td>
                <td><a href="#" class="text-blue-600 hover:underline">Detail</a></td>
            </tr>
            <tr>
                <td>ORD-20260429002</td>
                <td>Web Development Workshop</td>
                <td>18 Mei 2026</td>
                <td>Rp500.000</td>
                <td><span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Pending</span></td>
                <td><a href="#" class="text-blue-600 hover:underline">Detail</a></td>
            </tr>
            <tr>
                <td>ORD-20260429003</td>
                <td>Music Concert 2026</td>
                <td>10 Mei 2026</td>
                <td>Rp1.200.000</td>
                <td><span class="bg-red-100 text-red-700 px-2 py-1 rounded">Dibatalkan</span></td>
                <td><a href="#" class="text-blue-600 hover:underline">Detail</a></td>
            </tr>
        </tbody>
    </table>
    <div class="flex justify-between items-center mt-4">
        <div class="text-xs text-gray-500">Menampilkan 1-3 dari 3 data</div>
        <div class="flex gap-1">
            <button class="px-2 py-1 border rounded">&lt;</button>
            <button class="px-2 py-1 border rounded bg-black text-white">1</button>
            <button class="px-2 py-1 border rounded">2</button>
            <button class="px-2 py-1 border rounded">&gt;</button>
        </div>
    </div>
</div>
@endsection