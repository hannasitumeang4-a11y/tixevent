@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto p-8">

<div class="bg-white rounded-2xl shadow p-8">

<div class="text-center">

<h1 class="text-3xl font-bold text-green-600">
✅ Pembayaran Berhasil
</h1>

<p class="text-gray-500 mt-2">
Terima kasih sudah melakukan pembelian tiket
</p>

</div>

<div class="mt-8 space-y-4">

<div class="flex justify-between">
<span>Kode Pesanan</span>
<b>{{ $order->order_code }}</b>
</div>

<div class="flex justify-between">
<span>Nama</span>
<b>{{ auth()->user()->name }}</b>
</div>

<div class="flex justify-between">
<span>Email</span>
<b>{{ auth()->user()->email }}</b>
</div>

<div class="flex justify-between">
<span>Pembayaran</span>
<b>{{ strtoupper($order->payment_method) }}</b>
</div>

<div class="flex justify-between">
<span>Status</span>

<span class="bg-green-100 text-green-600 px-3 py-1 rounded-full">
{{ $order->order_status }}
</span>

</div>

<div class="border-t pt-4 flex justify-between text-xl font-bold">

<span>Total</span>

<span>
Rp{{ number_format(
$order->total_amount,
0,
',',
'.'
) }}
</span>

</div>

</div>

<div class="grid grid-cols-2 gap-3 mt-8">

<button onclick="window.print()"
class="bg-indigo-600 text-white py-3 rounded-xl">

Unduh Nota

</button>

<a
href="{{ route('history') }}"
class="border text-center py-3 rounded-xl">

Riwayat Transaksi

</a>

</div>

</div>

</div>

@endsection