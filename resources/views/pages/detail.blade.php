@extends('layouts.app')

@section('content')
<nav class="text-xs text-gray-500 mb-4">
	<a href="{{ route('home') }}" class="hover:underline text-indigo-600">Home</a> /
	<a href="#" class="hover:underline text-indigo-600">{{ $event->category->name ?? 'Event' }}</a> /
	<span>{{ $event->title }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
	<!-- Gambar utama dan gallery -->
	<div>
		<div class="bg-gradient-to-br from-indigo-100 to-purple-100 h-56 rounded-lg mb-4 flex items-center justify-center">
			<span class="text-6xl">🎟️</span>
		</div>
		<div class="flex gap-2">
			<div class="bg-gray-100 h-16 w-24 rounded flex items-center justify-center text-2xl">🖼️</div>
			<div class="bg-gray-100 h-16 w-24 rounded flex items-center justify-center text-2xl">🖼️</div>
			<div class="bg-gray-100 h-16 w-24 rounded flex items-center justify-center text-2xl">🖼️</div>
			<div class="bg-gray-100 h-16 w-24 rounded flex items-center justify-center text-2xl">🖼️</div>
		</div>
	</div>
	
	<!-- Detail event -->
	<div>
		<span class="inline-block bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded-full mb-2 font-medium">
			{{ $event->category->name ?? 'Uncategorized' }}
		</span>
		<h1 class="text-2xl font-bold mb-2 text-gray-800">{{ $event->title }}</h1>
		<div class="flex flex-wrap items-center gap-4 text-gray-500 mb-3 text-sm">
			<span class="flex items-center gap-1">📅 {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('d M Y') : 'TBA' }}</span>
			<span class="flex items-center gap-1">🕐 {{ $event->start_time ?? '09.00' }} - {{ $event->end_time ?? '16.00' }} WIB</span>
		</div>
		<div class="mb-3 text-gray-500 flex items-center gap-1 text-sm">
			<span>📍</span> {{ $event->location ?? 'Lokasi belum ditentukan' }}
		</div>
		<p class="mb-4 text-gray-700 leading-relaxed">{{ $event->description ?? 'Deskripsi event belum tersedia.' }}</p>

		<!-- Harga Utama -->
		<div class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-4">
			<div class="text-sm text-gray-500">Harga mulai dari</div>
			<div class="text-3xl font-bold text-indigo-600">
				{{ $event->price ? 'Rp' . number_format($event->price) : 'Gratis' }}
			</div>
		</div>

		<div class="mb-4">
			<h2 class="font-semibold mb-3 text-gray-800">Pilih Jenis Tiket</h2>
			<div class="space-y-3">
				<!-- VIP Ticket -->
				<div class="flex items-center justify-between border-2 border-indigo-100 rounded-lg px-4 py-3 hover:border-indigo-300 transition bg-white">
					<div>
						<div class="font-bold text-gray-800">VIP</div>
						<div class="text-xs text-gray-500">Fasilitas: Kursi VIP, Lunch, Goodie Bag, Sertifikat</div>
						<div class="text-xs text-green-600 mt-1">✓ Stok tersedia</div>
					</div>
					<div class="text-right">
						<div class="font-bold text-lg text-indigo-600">Rp500.000</div>
						<a href="{{ route('checkout') }}" class="ml-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">Pilih</a>
					</div>
				</div>
				<!-- Regular Ticket -->
				<div class="flex items-center justify-between border rounded-lg px-4 py-3 hover:border-gray-300 transition bg-white">
					<div>
						<div class="font-bold text-gray-800">REGULAR</div>
						<div class="text-xs text-gray-500">Fasilitas: Kursi Reguler, Sertifikat</div>
						<div class="text-xs text-green-600 mt-1">✓ Stok tersedia</div>
					</div>
					<div class="text-right">
						<div class="font-bold text-lg text-gray-700">{{ $event->price ? 'Rp' . number_format($event->price) : 'Gratis' }}</div>
						<a href="{{ route('checkout') }}" class="ml-4 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition text-sm font-medium">Pilih</a>
					</div>
				</div>
			</div>
		</div>

		<div class="flex gap-3">
			<a href="{{ route('home') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-700 font-medium">← Kembali</a>
			<a href="{{ route('checkout') }}" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition font-medium shadow-md">Beli Tiket Sekarang</a>
		</div>
	</div>
</div>
@endsection
