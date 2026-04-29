@extends('layouts.app')

@section('content')
<div>

    <!-- Hero -->
    <div class="grid grid-cols-2 gap-6 border-b pb-6">
        <div>
            <h1 class="text-4xl font-bold leading-tight">
                Temukan Event Seru <br> Untukmu
            </h1>
            <p class="mt-4 text-gray-600">
                Berbagai event menarik menantimu.<br>
                Pesan tiket sekarang!
            </p>
        </div>

        <div class="bg-gray-200 h-40 rounded"></div>
    </div>

    <!-- Kategori -->
    <div class="py-6 border-b">
        <h2 class="font-semibold mb-4">Kategori</h2>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-black text-white rounded">Semua</button>
            <button class="px-4 py-2 border rounded">Seminar</button>
            <button class="px-4 py-2 border rounded">Workshop</button>
            <button class="px-4 py-2 border rounded">Konser</button>
            <button class="px-4 py-2 border rounded">Festival</button>
        </div>
    </div>

    <!-- Event Cards -->
    <div class="py-6">
        <h2 class="font-semibold mb-4">Event Terbaru</h2>

        <div class="grid grid-cols-4 gap-4">
            @for($i = 0; $i < 4; $i++)
            <div class="border rounded overflow-hidden">
                <div class="bg-gray-200 h-28"></div>
                <div class="p-3 text-sm">
                    <h3 class="font-semibold">Seminar Digital Marketing 2026</h3>
                    <p class="text-gray-500 mt-1">20 Mei 2026</p>
                    <p class="text-gray-500">Jakarta</p>
                    <p class="mt-2 font-medium">Mulai dari Rp150.000</p>
                </div>
            </div>
            @endfor
        </div>
    </div>

</div>
@endsection