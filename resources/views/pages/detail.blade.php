@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">

<nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">

    <a href="{{ route('home') }}"
    class="hover:text-indigo-600">

        Home

    </a>

    <span>/</span>

    <a href="#"
    class="hover:text-indigo-600">

        {{ $event->category->name ?? 'Event' }}

    </a>

    <span>/</span>

    <span class="text-gray-700">

        {{ $event->title }}

    </span>

</nav>



<div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

    <!-- GAMBAR EVENT -->
    <div>

        @php

            $primaryImage =
            $event->primaryImage
            ?? $event->images->first();

        @endphp


        <div class="rounded-3xl overflow-hidden shadow-xl border">

            @if($primaryImage)

                <img
                src="{{ asset($primaryImage->image_path) }}"
                alt="{{ $event->title }}"
                class="w-full h-[500px] object-cover hover:scale-105 duration-500">

            @else

                <div class="h-[500px] bg-gradient-to-r from-indigo-100 to-purple-100 flex flex-col justify-center items-center">

                    <div class="text-7xl">

                        🎫

                    </div>

                    <div class="mt-3 text-gray-500">

                        Gambar tidak tersedia

                    </div>

                </div>

            @endif

        </div>

    </div>




    <!-- DETAIL EVENT -->
    <div>

        <span class="bg-indigo-100 text-indigo-600 px-4 py-2 rounded-full text-xs font-bold">

            {{ $event->category->name ?? 'Event' }}

        </span>


        <h1 class="text-4xl font-black mt-4 mb-3">

            {{ $event->title }}

        </h1>


        <div class="flex flex-wrap gap-4 text-gray-500 mb-4">

            <div class="bg-gray-100 px-4 py-2 rounded-xl">

                📅

                {{ $event->event_date
                ? \Carbon\Carbon::parse(
                $event->event_date
                )->format('d M Y')
                :'TBA' }}

            </div>


            <div class="bg-gray-100 px-4 py-2 rounded-xl">

                🕐

                {{ $event->start_time }}

                -

                {{ $event->end_time }}

            </div>

        </div>


        <div class="bg-gray-100 p-4 rounded-xl mb-5">

            📍

            {{ $event->location }}

        </div>



        <div class="leading-relaxed text-gray-700 mb-8">

            {{ $event->description }}

        </div>




        <!-- HARGA -->

        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-3xl p-6 mb-8 shadow-lg">

            <div class="text-sm opacity-80">

                Harga mulai dari

            </div>


            <div class="text-4xl font-black mt-1">

                @if($event->tickets->count())

                    Rp{{ number_format(

                    $event->tickets->min('price'),

                    0,

                    ',',

                    '.'

                    ) }}

                @else

                    Gratis

                @endif

            </div>

        </div>




        <!-- TIKET -->

        <div class="mb-8">

            <h2 class="font-bold text-xl mb-4">

                Pilih Jenis Tiket

            </h2>



            <div class="space-y-4">

                @forelse($event->tickets as $ticket)

                <div class="border rounded-2xl p-5 hover:shadow-lg transition">

                    <div class="flex justify-between items-center">


                        <div>

                            <div class="font-bold text-xl">

                                {{ $ticket->ticket_type }}

                            </div>


                            <div class="text-sm text-gray-500 mt-2">

                                Stock:
                                {{ $ticket->stock }}

                            </div>


                            <div class="text-sm text-gray-400">

                                Maks beli:
                                {{ $ticket->max_buy_per_order }}

                            </div>


                            @if($ticket->status=="available")

                            <span class="inline-block mt-2 text-xs px-3 py-1 bg-green-100 text-green-700 rounded-full">

                                Tersedia

                            </span>

                            @else

                            <span class="inline-block mt-2 text-xs px-3 py-1 bg-red-100 text-red-600 rounded-full">

                                Sold Out

                            </span>

                            @endif


                        </div>




                        <div class="text-right">

                            <div class="font-black text-2xl text-indigo-600">

                                Rp{{ number_format(

                                $ticket->price,

                                0,

                                ',',

                                '.'

                                ) }}

                            </div>


                            @if($ticket->status=="available")

                            <div class="flex gap-2 mt-3">

                                <form
                                action="{{route('cart.add')}}"
                                method="POST">

                                    @csrf

                                    <input
                                    type="hidden"
                                    name="event_id"
                                    value="{{$event->event_id}}">

                                    <input
                                    type="hidden"
                                    name="ticket_id"
                                    value="{{$ticket->event_ticket_id}}">

                                    <button
                                    class="bg-gray-100 px-4 py-3 rounded-xl">

                                        🛒

                                    </button>

                                </form>


                                <a
                                href="{{route(
                                'checkout',
                                [
                                'event'=>$event->event_id,
                                'ticket'=>$ticket->event_ticket_id
                                ]
                                )}}"

                                class="bg-indigo-600
                                hover:bg-indigo-700
                                px-6
                                py-3
                                rounded-xl
                                text-white">

                                Beli

                                </a>

                            </div>

                            @else

                            <button
                            disabled
                            class="mt-3 px-6 py-3 rounded-xl bg-gray-300 text-gray-500">

                                Sold Out

                            </button>

                            @endif

                        </div>

                    </div>

                </div>

                @empty

                <div class="bg-red-50 border border-red-200 p-6 rounded-2xl text-center">

                    <div class="text-5xl">

                        😔

                    </div>

                    <div class="font-bold mt-3 text-red-600">

                        Tiket belum tersedia

                    </div>

                </div>

                @endforelse

            </div>

        </div>



        <!-- BUTTON -->

        <div class="flex gap-4">

            <a
            href="{{ route('home') }}"
            class="px-6 py-3 border rounded-xl">

                ← Kembali

            </a>


            <a
            href="#"
            class="flex-1 bg-gray-100 text-gray-500 rounded-xl flex justify-center items-center">

                Pilih tiket di atas

            </a>

        </div>

    </div>

</div>

</div>

<hr class="my-16">

<h2
class="font-bold
text-2xl
mb-6">

Event Serupa 🎉

</h2>


@php

$relatedEvents = \App\Models\Event::where(
    'category_id',
    $event->category_id
)
->where(
    'event_id',
    '!=',
    $event->event_id
)
->take(3)
->get();

@endphp


<div
class="grid
md:grid-cols-3
gap-5">

@foreach($relatedEvents as $related)

<a
href="{{route(
'events.detail',
$related->event_id
)}}"

class="bg-white
rounded-3xl
shadow
overflow-hidden">

@php
$image =
$related
->images
->first();
@endphp


@if($image)

<img
src="{{asset(
$image->image_path
)}}"

class="h-48
w-full
object-cover">

@endif


<div class="p-5">

<div class="font-bold">

{{$related->title}}

</div>


<div
class="text-sm
text-gray-500
mt-2">

📍

{{$related->location}}

</div>

</div>

</a>

@endforeach

</div>

@endsection