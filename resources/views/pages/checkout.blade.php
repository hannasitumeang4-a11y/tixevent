@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Checkout</h1>
        <p class="text-gray-500 text-sm">Selesaikan pesananmu untuk mendapatkan tiket.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Detail Pesanan -->
        <div class="border rounded-2xl p-6 bg-white shadow-sm h-fit">
            <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span>🛒</span> Detail Pesanan
            </h2>
            <div class="mb-4 p-4 bg-indigo-50 rounded-xl">
                <div class="font-bold text-indigo-900 text-base">Seminar Digital Marketing 2026</div>
                <div class="text-xs text-indigo-600 mt-1 flex items-center gap-1">
                    <span>📅</span> 20 Mei 2026 | <span>📍</span> Jakarta Convention Center
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm mb-6">
                    <thead>
                        <tr class="text-left text-gray-400 border-b">
                            <th class="pb-2 font-medium">Jenis Tiket</th>
                            <th class="pb-2 font-medium">Harga</th>
                            <th class="pb-2 font-medium text-center">Jumlah</th>
                            <th class="pb-2 font-medium text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <tr class="border-b">
                            <td class="py-4 font-medium">VIP</td>
                            <td class="py-4">Rp500.000</td>
                            <td class="py-4 text-center">1</td>
                            <td class="py-4 text-right">Rp500.000</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-4 font-medium">REGULAR</td>
                            <td class="py-4">Rp200.000</td>
                            <td class="py-4 text-center">2</td>
                            <td class="py-4 text-right">Rp400.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center font-bold text-xl text-gray-800 mt-4">
                <span>Total Bayar</span>
                <span class="text-indigo-600">Rp900.000</span>
            </div>
        </div>

        <!-- Data Pemesan -->
        <div class="border rounded-2xl p-6 bg-white shadow-sm">
            <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span>👤</span> Data Pemesan
            </h2>
            
            {{-- FORM START --}}
            <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Masukkan nama lengkap">
                </div>
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Email Aktif</label>
                    <input type="email" name="email" required class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Masukkan email aktif">
                </div>
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">No. Telepon (WhatsApp)</label>
                    <input type="text" name="phone" required class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Contoh: 08123456789">
                </div>
                <div>
                    <label class="block mb-1.5 text-sm font-semibold text-gray-700">Metode Pembayaran</label>
                    <select name="payment_method" required class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Pilih metode pembayaran</option>
                        <option value="va_bca">Virtual Account BCA</option>
                        <option value="va_mandiri">Virtual Account Mandiri</option>
                        <option value="qris">QRIS (Gopay/OVO/Dana)</option>
                    </select>
                </div>
                
                <div class="pt-4">
                    {{-- UBAH TYPE KE SUBMIT --}}
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl font-bold text-lg shadow-lg transition-all active:scale-[0.98]">
                        Buat Pesanan Sekarang
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-4">
                        Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan yang berlaku.
                    </p>
                </div>
            </form>
            {{-- FORM END --}}
        </div>
    </div>
</div>
@endsection