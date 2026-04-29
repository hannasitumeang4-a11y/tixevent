@extends('layouts.app')

@section('content')
<div>

    <!-- Hero -->
    <div class="grid grid-cols-2 gap-6 border-b pb-6 items-center">
        <div>
            <h1 class="text-4xl font-bold leading-tight text-indigo-900">
                Temukan Event Seru <br> Untukmu
            </h1>
            <p class="mt-4 text-gray-600">
                Berbagai event menarik menantimu.<br>
                Pesan tiket sekarang!
            </p>
            <a href="#" class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Jelajahi Event</a>
        </div>

        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 h-40 rounded-lg flex items-center justify-center">
            <span class="text-white text-lg font-semibold">🎉 Event Image</span>
        </div>
    </div>

    <!-- Kategori -->
    <div class="py-6 border-b">
        <h2 class="font-semibold mb-4 text-lg text-gray-800">Kategori</h2>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('home') }}" 
               class="px-4 py-2 rounded-full text-sm font-medium transition
               {{ !request('category') || request('category') == 'all' 
                  ? 'bg-indigo-600 text-white' 
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('home', ['category' => $cat->category_id]) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition
                   {{ request('category') == $cat->category_id 
                      ? 'bg-indigo-600 text-white' 
                      : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Event Cards -->
    <div class="py-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-lg text-gray-800">Event Terbaru</h2>
            <span class="text-sm text-gray-500">{{ $events->count() }} event tersedia</span>
        </div>

        @if($events->isEmpty())
            <div class="text-center py-12 text-gray-500">
                <p class="text-lg">Belum ada event tersedia</p>
                <p class="text-sm">Coba pilih kategori lain atau cek kembali nanti</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($events as $event)
                <a href="{{ route('events.detail', $event->event_id) }}" class="block group">
                    <div class="border rounded-lg overflow-hidden hover:shadow-lg transition bg-white">
                        <div class="bg-gradient-to-br from-indigo-100 to-purple-100 h-28 flex items-center justify-center">
                            <span class="text-4xl">🎟️</span>
                        </div>
                        <div class="p-3 text-sm">
                            <span class="inline-block bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded mb-2">
                                {{ $event->category->name ?? 'Uncategorized' }}
                            </span>
                            <h3 class="font-semibold text-gray-800 group-hover:text-indigo-600 transition truncate">
                                {{ $event->title }}
                            </h3>
                            <p class="text-gray-500 mt-1 text-xs">
                                📅 {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('d M Y') : 'TBA' }}
                            </p>
                            <p class="text-gray-500 text-xs">📍 {{ $event->location ?? 'TBA' }}</p>
                            <p class="mt-2 font-bold text-indigo-600">
                                {{ $event->price ? 'Rp' . number_format($event->price) : 'Gratis' }}
                            </p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection