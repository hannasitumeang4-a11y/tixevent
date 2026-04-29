@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-6">Tambah Event</h1>

<form class="grid grid-cols-2 gap-8">
    <div class="space-y-4">
        <div>
            <label class="block mb-1 text-sm">Judul Event</label>
            <input type="text" class="w-full border rounded px-4 py-2">
        </div>

        <div>
            <label class="block mb-1 text-sm">Tanggal Event</label>
            <input type="date" class="w-full border rounded px-4 py-2">
        </div>

        <div>
            <label class="block mb-1 text-sm">Lokasi</label>
            <input type="text" class="w-full border rounded px-4 py-2">
        </div>
    </div>

    <div>
        <button class="px-4 py-2 bg-black text-white rounded">Simpan</button>
    </div>
</form>

<hr class="my-6">

<h2 class="text-xl font-bold mb-4">Daftar Event</h2>

<div class="bg-white border rounded p-4">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-500">
                <th>No</th>
                <th>Judul</th>
                <th>Lokasi</th>
                <th>Tanggal</th>
                <th>Harga</th>
            </tr>
        </thead>

        <tbody>
            @foreach($events as $index => $event)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $event->title }}</td>
                <td>{{ $event->location }}</td>
                <td>{{ $event->event_date }}</td>
                <td>Rp{{ number_format($event->price) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection