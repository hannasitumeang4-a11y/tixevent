@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Tambah Event</h1>
<form class="grid grid-cols-2 gap-8">
    <div class="space-y-4">
        <div>
            <label class="block mb-1 text-sm">Judul Event</label>
            <input type="text" class="w-full border rounded px-4 py-2" placeholder="Masukkan judul event">
        </div>
        <div>
            <label class="block mb-1 text-sm">Kategori</label>
            <select class="w-full border rounded px-4 py-2">
                <option>Pilih kategori</option>
            </select>
        </div>
        <div>
            <label class="block mb-1 text-sm">Tanggal Event</label>
            <input type="date" class="w-full border rounded px-4 py-2">
        </div>
        <div>
            <label class="block mb-1 text-sm">Waktu Mulai</label>
            <input type="time" class="w-full border rounded px-4 py-2">
        </div>
        <div>
            <label class="block mb-1 text-sm">Lokasi</label>
            <input type="text" class="w-full border rounded px-4 py-2" placeholder="Masukkan lokasi">
        </div>
    </div>
    <div class="space-y-4">
        <div>
            <label class="block mb-1 text-sm">Deskripsi</label>
            <textarea class="w-full border rounded px-4 py-2" rows="5" placeholder="Masukkan deskripsi event"></textarea>
        </div>
        <div>
            <label class="block mb-1 text-sm">Akun</label>
            <input type="text" class="w-full border rounded px-4 py-2" placeholder="Masukkan akun pemilik event">
        </div>
        <div class="flex gap-4">
            <div class="flex-1">
                <label class="block mb-1 text-sm">Status</label>
                <select class="w-full border rounded px-4 py-2">
                    <option>Pilih status</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block mb-1 text-sm">Featured</label>
                <select class="w-full border rounded px-4 py-2">
                    <option>Pilih</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block mb-1 text-sm">Gambar Event</label>
            <div class="border-dashed border-2 border-gray-300 rounded flex items-center justify-center h-24 cursor-pointer">
                <span class="text-gray-400">Klik untuk upload gambar</span>
            </div>
        </div>
        <div class="flex gap-2 justify-end">
            <button class="px-4 py-2 border rounded">Batal</button>
            <button class="px-4 py-2 bg-black text-white rounded">Simpan</button>
        </div>
    </div>
</form>
@endsection