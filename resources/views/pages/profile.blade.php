@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">

<div class="grid lg:grid-cols-3 gap-8">


<!-- kiri -->
<div>

<div class="bg-white rounded-3xl shadow border p-8">

<div class="flex flex-col items-center">

<div
class="w-24 h-24 rounded-full
bg-indigo-600 text-white
flex items-center justify-center
text-3xl font-bold">

{{ strtoupper(substr(auth()->user()->name,0,1)) }}

</div>


<h1 class="mt-4 font-bold text-xl">

{{ auth()->user()->name }}

</h1>

<p class="text-gray-500">

{{ auth()->user()->email }}

</p>

</div>


<div class="mt-8 space-y-3">

<div class="bg-gray-50 p-4 rounded-xl">

<div class="text-xs text-gray-400">

Role

</div>

<div class="font-bold">

{{ auth()->user()->role }}

</div>

</div>


<div class="bg-gray-50 p-4 rounded-xl">

<div class="text-xs text-gray-400">

Total Tiket

</div>

<div class="font-bold">

{{ $orders->count() }}

</div>

</div>

</div>

</div>

</div>


<!-- kanan -->

<div class="lg:col-span-2">

<div class="bg-white rounded-3xl shadow border p-6">

<h2 class="font-bold text-xl mb-6">

Riwayat Transaksi

</h2>


@if($orders->count())

<div class="space-y-4">

@foreach($orders as $order)

<div class="border rounded-2xl p-5">

<div
class="flex
justify-between
items-center">

<div>

<div class="font-bold">

Order #{{ $order->order_id }}

</div>

<div class="text-sm text-gray-500">

{{ $order->created_at->format('d M Y') }}

</div>

</div>


<div>

<span
class="bg-green-100
text-green-700
px-3 py-1 rounded-full text-sm">

{{ $order->status }}

</span>

</div>

</div>


<div class="mt-4 font-bold text-indigo-600">

Rp{{ number_format($order->total,0,',','.') }}

</div>

</div>

@endforeach

</div>

@else


<div class="text-center py-12">

🧾

<p class="mt-4 text-gray-500">

Belum ada transaksi

</p>

<a
href="{{route('home')}}"
class="mt-4 inline-block bg-indigo-600 text-white px-5 py-3 rounded-xl">

Cari Event

</a>

</div>

@endif

</div>

</div>

</div>

</div>

@endsection