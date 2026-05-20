@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2">Moderasi Ulasan & Testimoni (Review Management)</h1>
    <p class="text-sm text-gray-500">Pusat kendali moderator admin untuk menyaring ulasan pembeli. Hapus ulasan yang mengandung spam, kata kasar, atau ujaran kebencian demi menjaga reputasi platform.</p>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border rounded p-6 shadow-sm overflow-x-auto">
    <table class="w-full text-sm border-collapse">
        <thead>
            <tr class="text-left text-gray-500 border-b">
                <th class="pb-3 w-12">No</th>
                <th class="pb-3 w-40">Pengguna</th>
                <th class="pb-3 w-48">Nama Event / Konser</th>
                <th class="pb-3 w-28">Rating</th>
                <th class="pb-3">Isi Komentar / Ulasan</th>
                <th class="pb-3 text-center w-32">Aksi Moderator</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse($reviews as $index => $review)
            <tr class="hover:bg-gray-50">
                <td class="py-4 font-medium text-gray-600">{{ $index + 1 }}</td>
                <td class="py-4">
                    <div class="font-semibold text-gray-900">{{ $review->user->name ?? 'Anonymous' }}</div>
                    <div class="text-xs text-gray-400">{{ $review->user->email ?? '' }}</div>
                </td>
                <td class="py-4 text-gray-700 font-medium">
                    {{ $review->event->title ?? 'Event Tidak Ditemukan' }}
                </td>
                <td class="py-4">
                    <div class="flex items-center text-amber-500">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= ($review->rating ?? 0))
                                <span>★</span>
                            @else
                                <span class="text-gray-300">★</span>
                            @endif
                        @endfor
                        <span class="ml-1 text-xs text-gray-500 font-bold">({{ $review->rating ?? 0 }}/5)</span>
                    </div>
                </td>
                <td class="py-4 text-gray-600 max-w-md break-words">
                    <p class="italic text-gray-800">"{{ $review->comment ?? $review->review ?? '-' }}"</p>
                    <span class="text-xs text-gray-400 block mt-1">Ditulis pada: {{ $review->created_at ? $review->created_at->format('d M Y, H:i') : '-' }}</span>
                </td>
                <td class="py-4 text-center">
                    <form action="{{ route('admin.reviews.destroy', $review->review_id ?? $review->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini secara permanen dari sistem?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-medium transition-colors inline-flex items-center gap-1 shadow-sm">
                            🗑️ Hapus Spam
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-500 italic bg-gray-50">
                    Belum ada ulasan atau testimoni konser yang masuk ke database sistem.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4 text-xs text-gray-500">
        Total database ulasan: {{ count($reviews) }} data testimoni terdaftar.
    </div>
</div>
@endsection