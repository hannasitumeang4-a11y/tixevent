@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4">

<div class="flex gap-6">

<!-- SIDEBAR -->
<aside class="w-72 hidden lg:block">

<div class="bg-white rounded-3xl shadow-md border p-5 sticky top-24">

<h2 class="font-bold text-xl mb-5">
Kategori Event
</h2>

<div class="space-y-2">

<a href="{{ route('home',['category'=>'all']) }}"
class="flex items-center gap-3 px-4 py-3 rounded-xl
{{ !request('category')||request('category')=='all'
?'bg-indigo-600 text-white'
:'hover:bg-gray-100' }}">

🎟️ Semua Event

</a>

@foreach($categories as $cat)

<a
href="{{ route('home',['category'=>$cat->category_id]) }}"
class="flex items-center gap-3 px-4 py-3 rounded-xl
{{ request('category')==$cat->category_id
?'bg-indigo-600 text-white'
:'hover:bg-gray-100' }}">

🎫 {{ $cat->name }}

</a>

@endforeach

</div>

</div>



<!-- KERANJANG -->
<div class="mt-5 bg-white rounded-3xl shadow border p-6">

<h2 class="font-bold mb-4">
🛒 Keranjang
</h2>

@if(session('cart') && count(session('cart'))>0)

@foreach(session('cart') as $cart)

<div class="border-b py-3">

<div class="font-bold">

{{ $cart['title'] }}

</div>

<div class="text-sm text-gray-500">

{{ $cart['ticket'] }}

</div>

<div class="text-indigo-600 font-bold">

Rp{{number_format($cart['price'],0,',','.')}}

</div>

</div>

@endforeach

<a
href="{{route('cart')}}"
class="block mt-4 text-center bg-indigo-600 text-white px-4 py-2 rounded-xl">

Lihat Keranjang

</a>

@else

<div class="text-center py-5">

<div class="text-5xl">
🛒
</div>

<p class="text-sm text-gray-500 mt-3">
Belum ada tiket dipilih
</p>

<a
href="#event-list"
class="inline-block mt-4 bg-indigo-600 text-white px-4 py-2 rounded-xl">

Jelajahi Event

</a>

</div>

@endif

</div>

</aside>




<main class="flex-1">


<!-- HERO -->

<div class="rounded-[30px] overflow-hidden mb-8 relative shadow-lg">

<div
id="heroCarousel"
class="flex transition-all duration-700">

<img
src="{{asset('assets/img/events/banner-hero-all.jpg')}}"
class="w-full flex-shrink-0">

<img
src="{{asset('assets/img/events/banner-concert.jpg')}}"
class="w-full flex-shrink-0">

<img
src="{{asset('assets/img/events/banner-seminar.jpg')}}"
class="w-full flex-shrink-0">

<img
src="{{asset('assets/img/events/banner-workshop.jpg')}}"
class="w-full flex-shrink-0">

</div>


<div class="absolute inset-0 bg-black/50">

<div class="p-10 text-white">

<h1 class="text-5xl font-black">

Temukan Event Favoritmu 🎉

</h1>

<p class="mt-3 text-lg">

Konser • Workshop • Seminar • Festival

</p>


<div class="flex gap-3 mt-6">

<a
href="#event-list"
class="bg-indigo-600 px-6 py-3 rounded-xl">

Jelajahi Event

</a>


<a
href="#popular"
class="bg-white text-black px-6 py-3 rounded-xl">

Event Populer

</a>

</div>

</div>

</div>

</div>




<!-- STATS -->

<div class="grid grid-cols-3 gap-4 mb-10">

<div class="bg-white p-6 rounded-3xl shadow">

<div class="text-3xl font-black">

{{ $events->total() }}+

</div>

<div class="text-gray-500">

Event

</div>

</div>


<div class="bg-white p-6 rounded-3xl shadow">

<div class="text-3xl font-black">

{{ $categories->count() }}+

</div>

<div class="text-gray-500">

Kategori

</div>

</div>


<div class="bg-white p-6 rounded-3xl shadow">

<div class="text-3xl font-black">

1000+

</div>

<div class="text-gray-500">

Pengguna

</div>

</div>

</div>




<!-- SEARCH -->

<form
action="{{ route('home') }}"
class="mb-10">

<input
type="text"
name="search"
value="{{ request('search') }}"
placeholder="Cari konser, workshop, seminar..."

class="w-full px-6 py-4 rounded-2xl border shadow">

</form>




<!-- POPULAR -->

<div
id="popular"
class="mb-10">

<h2 class="font-black text-2xl mb-5">

🔥 Event Populer Minggu Ini

</h2>


<div class="grid md:grid-cols-3 gap-5">

@forelse($popular as $item)

<a
href="{{route('events.detail',$item->event_id)}}">

<div class="bg-white rounded-3xl p-5 shadow hover:shadow-xl">

<div class="font-bold">

{{ $item->title }}

</div>

<div class="text-sm text-gray-500 mt-2">

📍 {{ $item->location }}

</div>

<div class="text-indigo-600 mt-3 font-bold">

Rp{{number_format($item->price,0,',','.')}}

</div>

</div>

</a>

@empty

<div class="col-span-3 text-center bg-white p-8 rounded-3xl">

Belum ada event populer

</div>

@endforelse

</div>

</div>





<div id="event-list">

<h1 class="font-black text-3xl mb-6">

Jelajahi Event 🎉

</h1>


<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

@foreach($events as $event)

<a
href="{{ route('events.detail',$event->event_id) }}">

<div class="bg-white rounded-3xl overflow-hidden shadow hover:shadow-xl duration-300">

<div class="h-56 overflow-hidden">

@php
$primaryImage=
$event->primaryImage
??$event->images->first();
@endphp


@if($primaryImage)

<img
src="{{ asset($primaryImage->image_path) }}"
class="w-full h-full object-cover hover:scale-110 duration-500">

@else

<div class="w-full h-full bg-gray-200 flex items-center justify-center">

🎟️

</div>

@endif

</div>


<div class="p-5">

<h3 class="font-bold line-clamp-2">

{{ $event->title }}

</h3>


<div class="text-sm mt-2">

📅
{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}

</div>


<div class="text-sm text-gray-500">

📍 {{ $event->location }}

</div>


<div class="mt-3 text-indigo-600 font-black">

Rp{{number_format($event->price,0,',','.')}}

</div>

</div>

</div>

</a>

@endforeach

</div>

</div>


<div class="mt-10">

{{ $events->links() }}

</div>

</main>

</div>

</div>


<script>

document.addEventListener("DOMContentLoaded",()=>{

const slider=
document.getElementById("heroCarousel");

let current=0;

setInterval(()=>{

current++;

if(current>3){

current=0;

}

slider.style.transform=
`translateX(-${current*100}%)`;

},3000)

})

</script>

@endsection