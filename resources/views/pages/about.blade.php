@extends('layouts.app')

@section('content')
<div class="relative w-full min-h-[calc(100vh-76px)] bg-[#0b0c10] text-white overflow-hidden flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="absolute top-[-10%] left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="max-w-5xl w-full mx-auto relative z-10 space-y-16">
        
        <div class="group relative w-full rounded-3xl overflow-hidden bg-cover bg-center bg-no-repeat py-16 px-6 md:px-16 text-center transform transition-all duration-500 ease-out shadow-[0_4px_30px_rgba(0,0,0,0.4)] hover:scale-[1.02] hover:shadow-[0_0_50px_rgba(168,85,247,0.2)]"
             style="background-image: linear-gradient(to bottom, rgba(11, 12, 16, 0.65), rgba(11, 12, 16, 0.80)), url('{{ asset('assets/img/events/banner-concert.jpg') }}');">
            
            <div class="absolute inset-0 bg-gradient-to-tr from-purple-500/0 via-transparent to-blue-500/0 group-hover:from-purple-500/5 group-hover:to-blue-500/5 transition-all duration-500 pointer-events-none"></div>

            <div class="relative z-10 max-w-3xl mx-auto">
                <div class="inline-flex items-center justify-center p-3 bg-purple-500/20 backdrop-blur-md rounded-2xl text-purple-300 mb-6 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6">
                    🚀
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight bg-gradient-to-r from-purple-400 via-pink-400 to-blue-400 bg-clip-text text-transparent drop-shadow-md transition-all duration-300 group-hover:tracking-wide">
                    Tentang TIXEVENT
                </h1>
                <p class="mt-5 text-base md:text-lg text-gray-200 leading-relaxed drop-shadow-sm max-w-2xl mx-auto">
                    Platform manajemen dan pemesanan e-tiket digital generasi terbaru yang dirancang untuk mempermudah akses rekreasi, konser musik, seminar edukatif, hingga workshop interaktif Anda.
                </p>
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-bold text-center mb-10 tracking-wide text-gray-200">
                Mengapa Memilih Kami?
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="backdrop-blur-md bg-white/[0.02] border border-white/5 rounded-2xl p-6 hover:border-purple-500/40 hover:bg-white/[0.04] transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-xl text-purple-400 mb-4 group-hover:scale-110 transition-transform">
                        🛡️
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Sistem Barcode Unik</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Perlindungan berlapis membuat tiket masuk Anda aman, terverifikasi, dan bebas dari pemalsuan.
                    </p>
                </div>

                <div class="backdrop-blur-md bg-white/[0.02] border border-white/5 rounded-2xl p-6 hover:border-pink-500/40 hover:bg-white/[0.04] transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-xl bg-pink-500/10 flex items-center justify-center text-xl text-pink-300 mb-4 group-hover:scale-110 transition-transform">
                        ⚡
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Tanpa Antrean Fisik</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Semua transaksi dilakukan penuh secara digital dan instan. Beli tiket hitungan detik langsung masuk akun.
                    </p>
                </div>

                <div class="backdrop-blur-md bg-white/[0.02] border border-white/5 rounded-2xl p-6 hover:border-blue-500/40 hover:bg-white/[0.04] transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-xl text-blue-300 mb-4 group-hover:scale-110 transition-transform">
                        💳
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Metode Bayar Fleksibel</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Dukungan pembayaran lengkap melalui transfer bank otomatis serta berbagai e-wallet populer terintegrasi.
                    </p>
                </div>

            </div>
        </div>

        <div class="text-center border-t border-white/5 pt-6">
            <p class="text-xs text-gray-400 tracking-wider">
                &copy; 2026 TIXEVENT Digital Platform Inc. All Rights Reserved.
            </p>
        </div>

    </div>
</div>
@endsection