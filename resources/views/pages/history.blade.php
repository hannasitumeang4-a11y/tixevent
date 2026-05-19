@extends('layouts.app')

@section('content')

<h1 class="text-3xl font-bold mb-6">
🎟 Tiket Saya
</h1>

<div class="grid gap-5">

@forelse(auth()->user()->orders as $order)

<div class="bg-white rounded-3xl shadow-lg overflow-hidden">

<div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-white">

<div class="flex justify-between">

<div>

<h2 class="font-bold text-xl">

E-Ticket Eventix

</h2>

<p>

Order #{{ $order->order_id }}

</p>

</div>

<div>

<span class="bg-white text-indigo-600 px-4 py-1 rounded-full">

{{ $order->status }}

</span>

</div>

</div>

</div>

<div class="p-6">

<div class="grid md:grid-cols-2 gap-6">

<div>

<p class="text-gray-500">

Nama Pemesan

</p>

<h3 class="font-bold">

{{auth()->user()->name}}

</h3>

</div>

<div>

<p class="text-gray-500">

Email

</p>

<h3>

{{auth()->user()->email}}

</h3>

</div>

<div>

<p class="text-gray-500">

Total Pembayaran

</p>

<h3 class="font-bold text-indigo-600">

Rp{{number_format($order->total)}}

</h3>

</div>

<div>

<p class="text-gray-500">

Tanggal

</p>

<h3>

{{ $order->created_at->format('d M Y') }}

</h3>

</div>

</div>

<div class="mt-8 border-t pt-6">

<div class="bg-gray-100 rounded-2xl p-5 text-center">

<div class="text-6xl">

🎫

</div>

<div class="font-bold">

Tunjukkan tiket ini saat masuk event

</div>

</div>

</div>

</div>

</div>

@empty

<div class="bg-white p-10 rounded-3xl text-center">

<div class="text-7xl">

🛒

</div>

<h2 class="font-bold text-xl mt-4">

Belum ada tiket dibeli

</h2>

<p class="text-gray-500">

Beli event dulu ya princess

</p>

</div>

@endforelse

</div>

@endsection