@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen bg-[#0b0c10] py-12 px-4 flex justify-center items-center text-white">
    <div class="w-full max-w-2xl bg-[#16171e] border border-white/10 rounded-2xl p-8 shadow-2xl">
        
        <div class="mb-6">
            <a href="{{ route('organizer.dashboard') }}" class="text-sm text-gray-400 hover:text-purple-400 transition-colors flex items-center gap-2">
                🎤 Kembali ke Dashboard Promotor
            </a>
        </div>

        <form action="{{ route('organizer.events.update', $event->event_id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="border-b border-white/5 pb-3">
                <h2 class="text-xl font-bold text-purple-400">1. Data Informasi Dasar Event 📝</h2>
                <p class="text-xs text-gray-400">Modifikasi data utama serta penyesuaian parameter pelaksanaan.</p>
            </div>

            <div>
                <label class="block mb-1 text-sm text-gray-300">Nama Event / Konser</label>
                <input type="text" name="title" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500" value="{{ old('title', $event->title) }}" required>
            </div>

            <div>
                <label class="block mb-1 text-sm text-gray-300">Kategori Event</label>
                <select name="category_id" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500 cursor-pointer" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ old('category_id', $event->category_id) == $category->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm text-gray-300">Deskripsi Event</label>
                <textarea name="description" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500 resize-none" rows="4" required>{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm text-gray-300">Harga Tiket REGULAR (Acuan IDR)</label>
                    <input type="number" name="price_regular" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500" value="{{ old('price_regular', $event->price) }}" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm text-gray-300">Tanggal Pelaksanaan</label>
                    <input type="date" name="event_date" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500" value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm text-gray-300">Jam Mulai</label>
                    <input type="time" name="start_time" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500" value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('H:i')) }}" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm text-gray-300">Jam Selesai</label>
                    <input type="time" name="end_time" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500" value="{{ old('end_time', \Carbon\Carbon::parse($event->end_time)->format('H:i')) }}" required>
                </div>
            </div>

            <div>
                <label class="block mb-1 text-sm text-gray-300">Lokasi / Venue</label>
                <input type="text" name="location" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500" value="{{ old('location', $event->location) }}" required>
            </div>

            <div class="bg-[#0b0c10]/50 border border-white/5 rounded-xl p-4 space-y-3">
                <p class="text-xs font-semibold text-purple-400 uppercase tracking-wider">Pengaturan Harga Kategori Tiket Baru</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block mb-1 text-xs text-gray-400">Harga Tiket VIP</label>
                        <input type="number" name="price_vip" value="{{ old('price_vip', 750000) }}" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-purple-500" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs text-gray-400">Harga Tiket PRESALE</label>
                        <input type="number" name="price_presale" value="{{ old('price_presale', 350000) }}" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-purple-500" required>
                    </div>
                </div>
            </div>

            <div>
                <label class="block mb-1 text-sm text-gray-300">Ganti Poster Event (Kosongkan jika tidak diubah)</label>
                <input type="file" name="image" accept="image/*" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2 text-xs text-white file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-purple-500/20 file:text-purple-300 cursor-pointer">
            </div>

            <div class="bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 p-4 rounded-xl text-xs space-y-1">
                <strong>⚠️ Konfirmasi Perubahan:</strong>
                <p>Saat menekan simpan, manifes kategori harga tiket lama akan disinkronkan ulang secara instan berdasarkan data form di atas.</p>
            </div>

            <div class="flex justify-end space-x-3 pt-2">
                <a href="{{ route('organizer.dashboard') }}" class="bg-white/10 hover:bg-white/20 px-5 py-2.5 rounded-xl font-semibold text-sm transition-all flex items-center justify-center">Batal</a>
                <button type="submit" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 px-6 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-purple-500/20 transition-all flex items-center gap-2">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection