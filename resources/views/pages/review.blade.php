@extends('layouts.app')

@section('content')
<div class="relative w-full min-h-[calc(100vh-76px)] bg-[#0b0c10] text-white py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="max-w-5xl w-full mx-auto space-y-12">
        
        <div class="text-center">
            <div class="inline-flex items-center justify-center p-3 bg-yellow-500/10 rounded-2xl text-yellow-400 mb-4">
                ⭐
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">
                Ulasan Pengguna
            </h1>
            <p class="mt-2 text-sm text-gray-400">
                Apa kata mereka tentang pengalaman memesan tiket di TIXEVENT.
            </p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-500/20 border border-green-500/30 rounded-xl text-green-400 text-sm max-w-2xl mx-auto text-center">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-500/20 border border-red-500/30 rounded-xl text-red-400 text-sm max-w-2xl mx-auto text-center">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-w-2xl mx-auto">
            @if($hasOrdered)
                <div class="bg-white/[0.02] border border-white/10 rounded-3xl p-6 md:p-8 backdrop-blur-md shadow-xl">
                    <h3 class="text-lg font-semibold text-white mb-4">Bagikan Pengalaman Anda</h3>
                    
                    <form action="{{ route('review.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Rating Anda</label>
                            <select name="rating" required class="w-full bg-[#16171e] border border-white/10 rounded-xl px-4 py-3 text-yellow-400 focus:outline-none focus:border-purple-500">
                                <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Puas)</option>
                                <option value="4">⭐⭐⭐⭐ (4 - Puas)</option>
                                <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                <option value="2">⭐⭐ (2 - Kurang Puas)</option>
                                <option value="1">⭐ (1 - Kecewa)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Pesan Ulasan</label>
                            <textarea name="comment" rows="3" required placeholder="Tulis ulasan pengalaman Anda di sini agar bisa dilihat user lain..." 
                                      class="w-full bg-[#16171e] border border-white/10 rounded-xl p-4 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold rounded-xl text-sm transition-all duration-300 shadow-lg shadow-purple-500/20">
                            Kirim Ulasan Sekarang
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white/[0.01] border border-white/5 rounded-2xl p-6 text-center text-sm text-gray-400">
                    🔒 Fitur ulasan terkunci. Hanya pengguna yang telah menyelesaikan transaksi pembelian tiket dan belum pernah memberikan ulasan yang dapat mengisi form.
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">
            @forelse($reviews as $item)
                <div class="bg-white/[0.02] border border-white/5 rounded-2xl p-6 flex flex-col justify-between shadow-lg">
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-300 italic leading-relaxed">
                            "{{ $item->comment ?? 'Ulasan tanpa teks komentar.' }}"
                        </p>
                    </div>
                    
                    <div class="mt-6 flex items-center space-x-3 pt-4 border-t border-white/5">
                        <div class="w-10 h-10 rounded-full bg-purple-600/30 border border-purple-500/30 flex items-center justify-center text-sm font-bold text-purple-300 uppercase">
                            {{ substr($item->user->name ?? 'US', 0, 2) }}
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-white">
                                {{ $item->user->name ?? 'Customer Tixevent' }}
                            </h4>
                            
                            <div class="text-xs text-yellow-400 mt-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $item->rating ? '★' : '☆' }}
                                @endfor
                                <span class="text-gray-500 text-[10px] ml-2">Verified Buyer</span>
                            </div>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full text-center text-sm text-gray-500 py-8">
                    Belum ada ulasan yang diterbitkan.
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection