@extends('layouts.app')

@section('content')

<div class="container mx-auto">

<h1
class="text-3xl font-bold mb-6">

Keranjang Saya

</h1>


@if(count($cart)>0)

<div class="space-y-4">

@foreach($cart as $item)

<div
class="bg-white
rounded-2xl
shadow
p-5
flex
justify-between">

<div>

<div
class="font-bold">

{{$item['title']}}

</div>

<div
class="text-gray-500">

{{$item['ticket']}}

</div>

<div
class="text-indigo-600
font-bold
mt-2">

Rp{{number_format(
$item['price'],
0,
',',
'.'
)}}

</div>

</div>


<div
class="flex gap-3">

<a
href="{{route(
'checkout',
[
'event'=>$item['event_id'],
'ticket'=>$item['ticket_id']
]
)}}"

class="bg-indigo-600
text-white
px-5
py-2
rounded-xl">

Checkout

</a>


<a
href="{{route(
'cart.remove',
$item['ticket_id']
)}}"

class="bg-red-500
text-white
px-5
py-2
rounded-xl">

Hapus

</a>

</div>

</div>

@endforeach

</div>


@else

<div
class="bg-white
rounded-3xl
text-center
p-10">

🛒

<p class="mt-4">

Keranjang kosong

</p>

</div>

@endif

</div>

@endsection