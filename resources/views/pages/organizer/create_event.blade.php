@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen bg-[#0b0c10] py-12 px-4 flex justify-center items-center">
    <div class="w-full max-w-lg bg-[#16171e] border border-white/10 rounded-2xl p-8 shadow-2xl text-white">
        <form method="POST" action="{{ route('organizer.events.store') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="status" value="published">

            <h1 class="text-2xl font-bold mb-2 text-center text-purple-400">Buat Event Baru 🎤</h1>
            <p class="text-xs text-gray-400 text-center mb-6">Isi data di bawah ini untuk menerbitkan tiket acara Anda</p>

            @if($errors->any())
                <div class="bg-red-500/20 border border-red-500/30 text-red-400 text-sm p-4 rounded-xl mb-4">
                    @foreach($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="mb-4">
                <label class="block mb-1 text-sm text-gray-300">Nama Event / Konser</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500" placeholder="Contoh: Webinar Nasional Ketahanan Pangan" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-sm text-gray-300">Kategori Event</label>
                <select name="category_id" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500 cursor-pointer" required>
                    <option value="" disabled selected>-- Pilih Kategori Event --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-sm text-gray-300">Deskripsi Event</label>
                <textarea name="description" rows="3" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500 resize-none" placeholder="Tulis deskripsi atau line-up artis di sini..." required>{{ old('description') }}</textarea>
            </div>

            <div class="bg-[#0b0c10]/50 border border-white/5 rounded-xl p-4 mb-4">
                <p class="text-xs font-semibold text-purple-400 uppercase tracking-wider mb-3">Pengaturan Harga Kategori Tiket</p>
                
                <div class="mb-3">
                    <label class="block mb-1 text-xs text-gray-400">Harga Tiket VIP (Rupiah)</label>
                    <input type="number" name="price_vip" value="{{ old('price_vip') }}" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2 text-white focus:outline-none focus:border-purple-500 text-sm" placeholder="Contoh: 750000" min="0" required>
                </div>

                <div class="mb-3">
                    <label class="block mb-1 text-xs text-gray-400">Harga Tiket REGULAR (Rupiah)</label>
                    <input type="number" name="price_regular" value="{{ old('price_regular') }}" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2 text-white focus:outline-none focus:border-purple-500 text-sm" placeholder="Contoh: 500000" min="0" required>
                </div>

                <div>
                    <label class="block mb-1 text-xs text-gray-400">Harga Tiket PRESALE (Rupiah)</label>
                    <input type="number" name="price_presale" value="{{ old('price_presale') }}" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2 text-white focus:outline-none focus:border-purple-500 text-sm" placeholder="Contoh: 350000" min="0" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-1 text-sm text-gray-300">Tanggal Pelaksanaan</label>
                    <input type="date" name="event_date" value="{{ old('event_date') }}" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm text-gray-300">Waktu / Jam</label>
                    <div class="flex items-center space-x-1">
                        <input type="time" name="start_time" value="{{ old('start_time', '19:00') }}" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl p-2 text-xs text-white focus:outline-none focus:border-purple-500" required>
                        <span class="text-xs text-gray-500">s/d</span>
                        <input type="time" name="end_time" value="{{ old('end_time', '22:00') }}" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl p-2 text-xs text-white focus:outline-none focus:border-purple-500" required>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-sm text-gray-300">Lokasi / Venue</label>
                <input type="text" name="location" value="{{ old('location') }}" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-purple-500" placeholder="Contoh: Stadion Utama Gelora Bung Karno" required>
            </div>

            <div class="mb-6">
                <label class="block mb-1 text-sm text-gray-300">Poster Event</label>
                <input type="file" name="image" accept="image/*" class="w-full bg-[#0b0c10] border border-white/10 rounded-xl px-4 py-2 text-white focus:outline-none focus:border-purple-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-purple-500/20 file:text-purple-300 hover:file:bg-purple-500/30 cursor-pointer" required>
                <p class="text-[10px] text-gray-500 mt-1">*Format gambar: JPG, JPEG, PNG, atau WEBP (Maksimal 2MB)</p>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('organizer.dashboard') }}" class="w-1/3 bg-white/10 hover:bg-white/20 text-center py-3 rounded-xl font-semibold text-sm transition-all flex items-center justify-center">Batal</a>
                <button type="submit" class="w-2/3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white py-3 rounded-xl font-semibold text-sm shadow-lg shadow-purple-500/20 transition-all">Terbitkan Event</button>
            </div>
        </form>
    </div>
</div>
@endsection