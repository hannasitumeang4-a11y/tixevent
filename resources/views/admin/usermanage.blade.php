@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Manajemen User</h1>
<div class="bg-white border rounded p-6">
    <div class="flex justify-between mb-4">
        <div></div>
        <button class="bg-black text-white px-4 py-2 rounded">+ Tambah User</button>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-500">
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Admin Utama</td>
                <td>admin@eventix.com</td>
                <td>superadmin</td>
                <td><span class="bg-green-100 text-green-700 px-2 py-1 rounded">AKTIF</span></td>
                <td>
                    <button class="text-blue-600 hover:underline mr-2">✏️</button>
                    <button class="text-red-600 hover:underline">🗑️</button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Budi Santoso</td>
                <td>budi@gmail.com</td>
                <td>seller</td>
                <td><span class="bg-green-100 text-green-700 px-2 py-1 rounded">AKTIF</span></td>
                <td>
                    <button class="text-blue-600 hover:underline mr-2">✏️</button>
                    <button class="text-red-600 hover:underline">🗑️</button>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Siti Aisyah</td>
                <td>siti@gmail.com</td>
                <td>seller</td>
                <td><span class="bg-green-100 text-green-700 px-2 py-1 rounded">AKTIF</span></td>
                <td>
                    <button class="text-blue-600 hover:underline mr-2">✏️</button>
                    <button class="text-red-600 hover:underline">🗑️</button>
                </td>
            </tr>
            <tr>
                <td>4</td>
                <td>Andi Pratama</td>
                <td>andi@gmail.com</td>
                <td>user</td>
                <td><span class="bg-red-100 text-red-700 px-2 py-1 rounded">Nonaktif</span></td>
                <td>
                    <button class="text-blue-600 hover:underline mr-2">✏️</button>
                    <button class="text-red-600 hover:underline">🗑️</button>
                </td>
            </tr>
            <tr>
                <td>5</td>
                <td>Dewi Lestari</td>
                <td>dewi@gmail.com</td>
                <td>user</td>
                <td><span class="bg-green-100 text-green-700 px-2 py-1 rounded">AKTIF</span></td>
                <td>
                    <button class="text-blue-600 hover:underline mr-2">✏️</button>
                    <button class="text-red-600 hover:underline">🗑️</button>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="flex justify-between items-center mt-4">
        <div class="text-xs text-gray-500">Menampilkan 1-5 dari 5 data</div>
        <div class="flex gap-1">
            <button class="px-2 py-1 border rounded">&lt;</button>
            <button class="px-2 py-1 border rounded bg-black text-white">1</button>
            <button class="px-2 py-1 border rounded">2</button>
            <button class="px-2 py-1 border rounded">&gt;</button>
        </div>
    </div>
</div>
@endsection