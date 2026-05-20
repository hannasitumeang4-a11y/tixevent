@extends('layouts.admin')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2">Manajemen & Kurasi Event (Pusat Kendali Admin)</h1>
    <p class="text-sm text-gray-500">Gunakan halaman ini untuk menyetujui event baru dari Promotor atau menurunkan paksa event yang bermasalah.</p>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

<h2 class="text-xl font-bold mb-4">Daftar Pengawasan Event</h2>

<div class="bg-white border rounded p-4 overflow-x-auto">
    <table class="w-full text-sm border-collapse">
        <thead>
            <tr class="text-left text-gray-500 border-b">
                <th class="pb-3 pt-2">No</th>
                <th class="pb-3 pt-2">Judul</th>
                <th class="pb-3 pt-2">Lokasi</th>
                <th class="pb-3 pt-2">Tanggal</th>
                <th class="pb-3 pt-2">Status Sistem</th>
                <th class="pb-3 pt-2 text-center">Aksi / Tindakan Admin</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse($events as $index => $event)
            <tr class="hover:bg-gray-50">
                <td class="py-4 font-medium">{{ $index + 1 }}</td>
                <td class="py-4 font-bold text-gray-900">{{ $event->title }}</td>
                <td class="py-4">{{ $event->location }}</td>
                <td class="py-4">{{ $event->event_date }}</td>
                <td class="py-4">
                    @if($event->status == 'published')
                        <span class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded">Tayang (Published)</span>
                    @elseif($event->status == 'draft' || $event->status == 'pending')
                        <span class="px-2.5 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded">Menunggu Persetujuan (Pending)</span>
                    @elseif($event->status == 'rejected')
                        <span class="px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">Ditolak (Rejected)</span>
                    @else
                        <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded">{{ ucfirst($event->status) }}</span>
                    @endif
                </td>
                <td class="py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        
                        @if($event->status != 'published')
                            <form action="{{ route('admin.events.publish', $event->event_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui dan menerbitkan event ini ke halaman utama?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-medium transition-colors">
                                    Publish
                                </button>
                            </form>
                            
                            @if($event->status != 'rejected')
                            <form action="{{ route('admin.events.reject', $event->event_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK event ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded text-xs font-medium transition-colors">
                                    Banned/Tolak
                                </button>
                            </form>
                            @endif

                        @else
                            <form action="{{ route('admin.events.takedown', $event->event_id) }}" method="POST" onsubmit="return confirm('PERINGATAN SAKTI: Anda akan menurunkan paksa event ini secara total dari halaman pembeli. Lanjutkan?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-medium transition-colors shadow">
                                    Take Down
                                </button>
                            </form>
                        @endif

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-500 italic bg-gray-50">
                    Belum ada data event promotor yang masuk ke database tixevent3.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection