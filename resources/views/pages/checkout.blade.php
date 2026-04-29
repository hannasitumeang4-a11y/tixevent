@extends('layouts.app')

@section('content')
<div class="grid grid-cols-2 gap-8">
    <!-- Detail Pesanan -->
    <div class="border rounded p-6 bg-white">
        <h2 class="font-bold mb-4">Detail Pesanan</h2>
        <div class="mb-2 font-semibold">Seminar Digital Marketing 2026</div>
        <div class="text-xs text-gray-500 mb-2">20 Mei 2026 | Jakarta Convention Center</div>
        <table class="w-full text-xs mb-4">
            <thead>
                <tr class="text-left text-gray-500">
                    <th>Jenis Tiket</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>VIP</td>
                    <td>Rp500.000</td>
                    <td>1</td>
                    <td>Rp500.000</td>
                </tr>
                <tr>
                    <td>REGULAR</td>
                    <td>Rp200.000</td>
                    <td>2</td>
                    <td>Rp400.000</td>
                </tr>
            </tbody>
        </table>
        <div class="flex justify-between font-bold text-base">
            <span>Total</span>
            <span>Rp900.000</span>
        </div>
    </div>
    <!-- Data Pemesan -->
    <div class="border rounded p-6 bg-white">
        <h2 class="font-bold mb-4">Data Pemesan</h2>
        <form class="space-y-4">
            <div>
                <label class="block mb-1 text-sm">Nama Lengkap</label>
                <input type="text" class="w-full border rounded px-4 py-2" placeholder="Masukkan nama lengkap">
            </div>
            <div>
                <label class="block mb-1 text-sm">Email</label>
                <input type="email" class="w-full border rounded px-4 py-2" placeholder="Masukkan email">
            </div>
            <div>
                <label class="block mb-1 text-sm">No. Telepon</label>
                <input type="text" class="w-full border rounded px-4 py-2" placeholder="Masukkan no. telepon">
            </div>
            <div>
                <label class="block mb-1 text-sm">Metode Pembayaran</label>
                <select class="w-full border rounded px-4 py-2">
                    <option>Pilih metode pembayaran</option>
                </select>
            </div>
            <button class="w-full bg-black text-white py-2 rounded font-semibold">Buat Pesanan</button>
        </form>
    </div>
</div>
@endsection