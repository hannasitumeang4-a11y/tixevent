@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto py-10">

<div class="bg-white rounded-2xl shadow p-8">

<div class="text-center">

<div class="text-6xl">
✅
</div>

<h1 class="text-3xl font-bold mt-3">

Pembayaran Berhasil

</h1>

<p class="text-gray-500">

Pesanan berhasil dibuat

</p>

</div>


<div class="mt-8 border rounded-xl p-5">

<div class="mb-3">

<b>Kode Order:</b>

{{ $order->order_code }}

</div>


<div class="mb-3">

<b>Event:</b>

{{ $order->event->title }}

</div>


<div class="mb-3">

<b>Tiket:</b>

{{ $order->ticket->ticket_type }}

</div>


<div class="mb-3">

<b>Total:</b>

Rp{{ number_format(
$order->total_amount,
0,
',',
'.'
) }}

</div>

<div>

<b>Status:</b>

<span class="text-green-600">

{{ $order->order_status }}

</span>

</div>

</div>


<div class="mt-6 flex gap-4">

<a
href="{{ route('history') }}"
class="flex-1 text-center py-3 rounded-xl bg-indigo-600 text-white">

Riwayat

</a>


<button
onclick="window.print()"
class="flex-1 py-3 rounded-xl border">

Unduh Tiket

</button>

</div>

</div>

</div>

@endsection