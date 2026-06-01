@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 bg-slate-50 min-h-screen">
    <div class="grid lg:grid-cols-3 gap-8">

        {{-- Kiri: Identitas & Riwayat Singkat --}}
        <div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                <div class="flex flex-col items-center border-b border-slate-100 pb-6">
                    <div class="w-24 h-24 rounded-full bg-indigo-600 text-white flex items-center justify-center text-3xl font-bold shadow-md">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <h1 class="mt-4 font-bold text-xl text-slate-800 tracking-wide">
                        {{ auth()->user()->name }}
                    </h1>
                    <p class="text-slate-500 text-sm mt-1 font-medium">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <div class="mt-6 space-y-3">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex justify-between items-center">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Role Akun</div>
                        <div class="font-bold text-slate-700 text-sm uppercase">{{ auth()->user()->role }}</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex justify-between items-center">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Tiket</div>
                        <div class="font-bold text-indigo-600 text-lg">{{ $orders->count() ?? 0 }}</div>
                    </div>
                    
                    {{-- Tombol Logout --}}
                    <form action="{{ route('logout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full text-center bg-red-50 hover:bg-red-100 text-red-600 font-bold text-sm py-3 rounded-xl border border-red-100 transition-colors">
                            Logout / Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kanan: Form Pengaturan Akun & Riwayat --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Form Edit Profile --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h2 class="font-bold text-xl text-slate-800 mb-2 flex items-center gap-2">
                    <span>⚙️</span> Pengaturan Akun
                </h2>
                <p class="text-sm text-slate-500 mb-6 border-b border-slate-100 pb-4">Ubah informasi dasar dan kata sandi akun Anda di sini.</p>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-sm font-medium">
                        ✅ {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-200 rounded-xl text-sm">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition">
                        </div>
                    </div>

                    <div class="pt-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Ganti Kata Sandi (Opsional)</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="password" name="password" placeholder="Password Baru" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 focus:border-indigo-500 focus:ring-1 outline-none transition">
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 focus:border-indigo-500 focus:ring-1 outline-none transition">
                        </div>
                        <p class="text-xs text-slate-400 mt-2">* Kosongkan kedua kolom di atas jika tidak ingin mengubah kata sandi.</p>
                    </div>

                    <div class="flex justify-end pt-4 mt-2 border-t border-slate-100">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Riwayat Transaksi --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h2 class="font-bold text-xl text-slate-800 mb-6 flex items-center gap-2">
                    <span>🧾</span> Riwayat Transaksi Anda
                </h2>

                @if(isset($orders) && $orders->count())
                <div class="space-y-4">
                    @foreach($orders as $order)
                    <div class="border border-slate-200 bg-white rounded-2xl p-5 hover:shadow-lg transition duration-300">
                        
                        {{-- BAGIAN ATAS: Nomor Invoice & Status --}}
                        <div class="flex justify-between items-start border-b border-slate-100 pb-4 mb-4">
                            <div>
                                {{-- Menggunakan order_code asli jika ada, jika tidak pakai format acak dari order_id --}}
                                <div class="font-extrabold text-slate-800 text-sm tracking-wide">
                                    {{ $order->order_code ?? 'INV/' . ($order->created_at ? $order->created_at->format('Ymd') : date('Ymd')) . '/TIX/' . str_pad($order->order_id ?? 0, 4, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="text-xs text-slate-400 mt-1 font-medium">
                                    Dibeli pada: {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }} WIB
                                </div>
                            </div>
                            <div>
                                @if(strtoupper($order->order_status ?? '') == 'PAID' || strtoupper($order->order_status ?? '') == 'SUCCESS')
                                    <span class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-3 py-1.5 rounded-md text-[10px] font-bold tracking-wider uppercase flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tiket Diterbitkan
                                    </span>
                                @else
                                    <span class="bg-amber-100 border border-amber-200 text-amber-700 px-3 py-1.5 rounded-md text-[10px] font-bold tracking-wider uppercase">
                                        {{ $order->order_status ?? 'PENDING' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- BAGIAN TENGAH: Detail Konser --}}
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 rounded-lg bg-indigo-50 flex items-center justify-center text-xl">
                                🎸
                            </div>
                            <div>
                                {{-- Menggunakan Nullsafe Operator PHP 8 (?->) untuk mencegah error crash --}}
                                <h3 class="font-bold text-slate-800 text-lg leading-tight">
                                    {{ $order->event?->title ?? 'Acara Tidak Ditemukan' }}
                                </h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    Kategori: <span class="font-semibold text-slate-700">{{ $order->ticket?->ticket_type ?? 'N/A' }}</span> 
                                    <span class="mx-2 text-slate-300">|</span> 
                                    Jumlah: <span class="font-semibold text-slate-700">{{ $order->quantity ?? 1 }} Tiket</span>
                                </p>
                            </div>
                        </div>

                        {{-- BAGIAN BAWAH: Total Harga & Tombol E-Tiket --}}
                        <div class="mt-5 pt-4 border-t border-dashed border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="w-full sm:w-auto text-left">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Pembayaran</span>
                                <span class="text-indigo-600 font-extrabold text-xl">
                                    Rp{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            <a href="{{ route('invoice', $order->order_id ?? $order->id) }}" 
                               class="w-full sm:w-auto bg-gradient-to-r from-slate-800 to-slate-900 hover:from-indigo-600 hover:to-indigo-700 text-white text-sm font-bold py-2.5 px-6 rounded-xl shadow-md transition-all transform hover:-translate-y-1 text-center flex justify-center items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                                Lihat E-Tiket
                            </a>
                        </div>

                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 space-y-4 border border-dashed border-slate-200 rounded-xl">
                    <div class="text-5xl opacity-40">🧾</div>
                    <p class="text-slate-400 text-sm font-medium">Belum ada riwayat aktivitas transaksi.</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection