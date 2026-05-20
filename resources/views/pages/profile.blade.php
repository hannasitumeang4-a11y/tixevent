@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 bg-slate-50 min-h-screen">
    <div class="grid lg:grid-cols-3 gap-8">

        <div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                <div class="flex flex-col items-center border-b border-slate-100 pb-6">
                    <div class="w-24 h-24 rounded-full bg-indigo-600 text-white flex items-center justify-center text-3xl font-bold shadow-md">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </div>

                    <h1 class="mt-4 font-bold text-xl text-slate-800 tracking-wide">
                        {{ auth()->user()->name }}
                    </h1>
                    <p class="text-slate-500 text-sm mt-1 font-medium">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <div class="mt-6 space-y-3">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Role Akun</div>
                        <div class="font-bold text-slate-700 text-sm mt-1 uppercase">{{ auth()->user()->role }}</div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Tiket</div>
                        <div class="font-bold text-indigo-600 text-lg mt-0.5">{{ $orders->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h2 class="font-bold text-xl text-slate-800 mb-6 flex items-center gap-2">
                    <span>🧾</span> Riwayat Transaksi
                </h2>

                @if($orders->count())
                <div class="space-y-4">
                    @foreach($orders as $order)
                    <div class="border border-slate-200 bg-slate-50 rounded-xl p-5 hover:border-slate-300 transition duration-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-bold text-slate-800 text-base">
                                    Order #{{ $order->order_id }}
                                </div>
                                <div class="text-xs text-slate-400 mt-1 font-medium">
                                    {{ $order->created_at->format('d M Y') }}
                                </div>
                            </div>

                            <div>
                                @if(strtoupper($order->order_status) == 'PAID' || strtoupper($order->order_status) == 'SUCCESS')
                                    <span class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold tracking-wider">
                                        PAID
                                    </span>
                                @else
                                    <span class="bg-amber-100 border border-amber-200 text-amber-700 px-3 py-1 rounded-full text-xs font-bold tracking-wider">
                                        {{ $order->order_status }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-dashed border-slate-200 flex justify-between items-center">
                            <span class="text-xs text-slate-500 font-medium">Total Pembayaran</span>
                            <span class="text-indigo-600 font-bold text-base">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-16 space-y-4">
                    <div class="text-5xl opacity-40">🧾</div>
                    <p class="text-slate-400 text-sm font-medium">Belum ada riwayat aktivitas transaksi.</p>
                    <a href="{{route('home')}}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-6 py-3 rounded-xl shadow-md transition">
                        Cari Event Baru
                    </a>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection