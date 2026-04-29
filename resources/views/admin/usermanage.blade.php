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
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded">
                        AKTIF
                    </span>
                </td>
                <td>
                    <button class="text-blue-600 hover:underline mr-2">✏️</button>
                    <button class="text-red-600 hover:underline">🗑️</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 text-xs text-gray-500">
        Total {{ count($users) }} user
    </div>
</div>
@endsection