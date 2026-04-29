@extends('layouts.app')

@section('content')
<nav class="text-xs text-gray-500 mb-4">
	<a href="#" class="hover:underline">Home</a> /
	<a href="#" class="hover:underline">Event</a> /
	<span>Seminar Digital Marketing 2026</span>
</nav>

<div class="grid grid-cols-2 gap-8">
	<!-- Gambar utama dan gallery -->
	<div>
		<div class="bg-gray-200 h-56 rounded mb-4"></div>
		<div class="flex gap-2">
			<div class="bg-gray-200 h-16 w-24 rounded"></div>
			<div class="bg-gray-200 h-16 w-24 rounded"></div>
			<div class="bg-gray-200 h-16 w-24 rounded"></div>
			<div class="bg-gray-200 h-16 w-24 rounded"></div>
		</div>
	</div>
	<!-- Detail event -->
	<div>
		<span class="inline-block bg-gray-100 text-xs px-2 py-1 rounded mb-2">Seminar</span>
		<h1 class="text-2xl font-bold mb-2">Seminar Digital Marketing 2026</h1>
		<div class="flex items-center gap-4 text-gray-500 mb-2">
			<span>20 Mei 2026</span>
			<span>09.00 - 16.00 WIB</span>
		</div>
		<div class="mb-2 text-gray-500">Jakarta Convention Center</div>
		<p class="mb-4 text-gray-700">Seminar ini membahas strategi digital marketing terbaru untuk meningkatkan bisnis Anda di era digital. Dapatkan insight langsung dari ahli dan praktisi di bidangnya.</p>

		<div class="mb-4">
			<h2 class="font-semibold mb-2">Pilih Jenis Tiket</h2>
			<div class="space-y-2">
				<div class="flex items-center justify-between border rounded px-4 py-2">
					<div>
						<div class="font-semibold">VIP</div>
						<div class="text-xs text-gray-500">Fasilitas: Kursi VIP, Lunch, Goodie Bag, Sertifikat</div>
						<div class="text-xs text-gray-400">Stok: 40 tiket tersedia</div>
					</div>
					<div class="text-right">
						<div class="font-bold">Rp500.000</div>
						<button class="ml-4 px-4 py-2 bg-black text-white rounded">Pilih</button>
					</div>
				</div>
				<div class="flex items-center justify-between border rounded px-4 py-2">
					<div>
						<div class="font-semibold">REGULAR</div>
						<div class="text-xs text-gray-500">Fasilitas: Kursi Reguler, Sertifikat</div>
						<div class="text-xs text-gray-400">Stok: 100 tiket tersedia</div>
					</div>
					<div class="text-right">
						<div class="font-bold">Rp200.000</div>
						<button class="ml-4 px-4 py-2 bg-black text-white rounded">Pilih</button>
					</div>
				</div>
				<div class="flex items-center justify-between border rounded px-4 py-2">
					<div>
						<div class="font-semibold">PRESALE</div>
						<div class="text-xs text-gray-500">Fasilitas: Kursi Reguler, Sertifikat</div>
						<div class="text-xs text-gray-400">Stok: 50 tiket tersedia</div>
					</div>
					<div class="text-right">
						<div class="font-bold">Rp150.000</div>
						<button class="ml-4 px-4 py-2 bg-black text-white rounded">Pilih</button>
					</div>
				</div>
			</div>
		</div>

		<div class="flex gap-2">
			<a href="#" class="px-4 py-2 border rounded">Kembali</a>
			<a href="#" class="px-4 py-2 bg-black text-white rounded">Beli Tiket</a>
		</div>
	</div>
</div>
@endsection
