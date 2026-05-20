@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2">Manajemen Kontrol Pengguna (User Management)</h1>
    <p class="text-sm text-gray-500">Pusat kendali mutlak admin untuk menyaring peran pengguna, memverifikasi promotor, atau memblokir akun yang melanggar aturan.</p>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border rounded p-4 mb-6 shadow-sm">
    <form action="{{ route('admin.usermanage') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <label for="role_filter" class="text-sm font-medium text-gray-700">Filter Peran:</label>
            <select id="role_filter" name="role" onchange="this.form.submit()" class="border rounded px-3 py-1.5 text-sm bg-gray-50 focus:bg-white focus:outline-none">
                <option value="">-- Semua Pengguna --</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="organizer" {{ request('role') == 'organizer' ? 'selected' : '' }}>Promotor / Organizer</option>
                <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Pembeli / Customer</option>
            </select>
        </div>
        
        @if(request('role'))
            <a href="{{ route('admin.usermanage') }}" class="text-xs text-red-600 hover:underline">❌ Bersihkan Filter</a>
        @endif
    </form>
</div>

<div class="bg-white border rounded p-6 shadow-sm overflow-x-auto">
    <table class="w-full text-sm border-collapse">
        <thead>
            <tr class="text-left text-gray-500 border-b">
                <th class="pb-3">No</th>
                <th class="pb-3">Nama Lengkap</th>
                <th class="pb-3">Alamat Email</th>
                <th class="pb-3">Peran / Role</th>
                <th class="pb-3">Status Akses</th>
                <th class="pb-3 text-center">Tindakan & Otoritas Admin</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse($users as $index => $user)
            <tr class="hover:bg-gray-50">
                <td class="py-4">{{ $index + 1 }}</td>
                <td class="py-4 font-semibold text-gray-900">{{ $user->name }}</td>
                <td class="py-4 text-gray-600">{{ $user->email }}</td>
                <td class="py-4">
                    <form action="{{ route('admin.users.update-role', $user->user_id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <select name="role" onchange="if(confirm('Apakah Anda yakin ingin mengubah peran pengguna ini?')) this.form.submit()" class="border rounded px-2 py-1 text-xs font-medium bg-gray-50 focus:bg-white">
                            <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Pembeli (Customer)</option>
                            <option value="organizer" {{ $user->role == 'organizer' ? 'selected' : '' }}>Promotor (Organizer)</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin Utama</option>
                        </select>
                    </form>
                </td>
                <td class="py-4">
                    @if(isset($user->is_banned) && $user->is_banned)
                        <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-bold tracking-wide">
                            TERBLOKIR (BANNED)
                        </span>
                    @else
                        <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-bold tracking-wide">
                            AKTIF
                        </span>
                    @endif
                </td>
                <td class="py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        @if(isset($user->is_banned) && $user->is_banned)
                            <form action="{{ route('admin.users.toggle-status', $user->user_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membuka blokir akun ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-medium transition-colors">
                                    Pulihkan Akun
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.toggle-status', $user->user_id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Akun yang diblokir tidak akan bisa masuk ke sistem kembali. Lanjutkan pembekuan?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-medium transition-colors">
                                    Suspend / Banned
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-500 italic bg-gray-50">
                    Tidak ditemukan data pengguna yang cocok dengan kriteria filter.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4 text-xs text-gray-500">
        Menampilkan total {{ count($users) }} pengguna sistem Eventix.
    </div>
</div>
@endsection