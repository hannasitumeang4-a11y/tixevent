@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 bg-slate-50 min-h-screen">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">
            Checkout Order Pass
        </h1>
        <p class="text-slate-500 text-xs mt-1 font-medium">
            Selesaikan pesanan digitalmu untuk mengamankan baris e-ticket.
        </p>
    </div>

    {{-- Alert Error jika validasi controller gagal --}}
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium">
        ⚠️ {{ session('error') }}
    </div>
    @endif

    @php
        $isFreeTicket = ($ticket->price == 0);
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <div class="border border-slate-200 rounded-2xl p-6 bg-white shadow-sm h-fit">
            <h2 class="font-bold text-base text-slate-800 mb-4 flex items-center gap-2">
                <span>🛒</span> Detail Pesanan
            </h2>

            <div class="mb-5 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                <div class="font-bold text-indigo-600 text-base">
                    {{ $event->title }}
                </div>
                <div class="text-[11px] text-slate-500 mt-1.5 flex flex-wrap items-center gap-2 font-semibold">
                    <span class="flex items-center gap-1">📅 {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                    <span class="text-slate-300">|</span>
                    <span class="flex items-center gap-1">📍 {{ $event->location }}</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs mb-6">
                    <thead>
                        <tr class="text-left text-slate-400 border-b border-slate-200 pb-2 uppercase tracking-wider font-bold">
                            <th class="pb-3">Jenis Tiket</th>
                            <th class="pb-3">Harga</th>
                            <th class="pb-3 text-center">Jumlah</th>
                            <th class="pb-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        <tr>
                            <td class="py-4 font-bold text-slate-800">
                                {{ $ticket->ticket_type }}
                                <div class="text-[10px] text-amber-600 font-medium mt-0.5">Sisa Stok: {{ $ticket->stock }} tiket</div>
                            </td>
                            <td class="py-4">
                                {{ $isFreeTicket ? 'Gratis' : 'Rp' . number_format($ticket->price, 0, ',', '.') }}
                            </td>
                            <td class="py-4 text-center font-mono font-bold">
                                {{-- Dropdown Jumlah Tiket (Maksimal disesuaikan jadi 5 sesuai ketentuan event) --}}
                                <select id="quantity_select" class="bg-slate-100 border border-slate-300 text-slate-800 rounded-lg p-1.5 text-xs font-bold outline-none focus:border-indigo-500">
                                    @for ($i = 1; $i <= min($ticket->stock, 5); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </td>
                            <td class="py-4 text-right text-indigo-600 font-bold">
                                <span id="table_subtotal">{{ $isFreeTicket ? 'Rp0' : 'Rp' . number_format($ticket->price, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center font-bold text-lg pt-4 border-t border-slate-200">
                <span class="text-slate-500 text-sm font-medium">Total Bayar</span>
                <span class="text-indigo-600 font-extrabold text-xl">
                    <span id="total_bayar_text">Rp{{ number_format($ticket->price, 0, ',', '.') }}</span>
                </span>
            </div>
        </div>

        <div class="border border-slate-200 rounded-2xl p-6 bg-white shadow-sm">
            <h2 class="font-bold text-base text-slate-800 mb-5 flex items-center gap-2">
                <span>👤</span> Data Pemesan @if(!$isFreeTicket) & Pembayaran @endif
            </h2>

            <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <input type="hidden" name="event_id" value="{{ $event->event_id }}">
                <input type="hidden" name="ticket_id" value="{{ $ticket->event_ticket_id }}">
                
                {{-- Input Hidden untuk mengirim data kuantitas asli ke controller kamu --}}
                <input type="hidden" id="hidden_quantity" name="quantity" value="1">

                <div>
                    <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="name" required value="{{ auth()->user()->name ?? '' }}"
                           class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl p-3 text-sm outline-none focus:border-indigo-500 focus:bg-white transition font-medium">
                </div>

                <div>
                    <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Email</label>
                    <input type="email" name="email" required value="{{ auth()->user()->email ?? '' }}"
                           class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl p-3 text-sm outline-none focus:border-indigo-500 focus:bg-white transition font-medium">
                </div>

                <div>
                    <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Nomor HP</label>
                    <input type="text" name="phone" required placeholder="08123456789"
                           class="w-full bg-slate-50 border border-slate-300 text-slate-800 placeholder-slate-400 rounded-xl p-3 text-sm outline-none focus:border-indigo-500 focus:bg-white transition font-medium">
                </div>

                @if(!$isFreeTicket)
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Metode Pembayaran</label>
                    <select id="payment_method" name="payment_method" required
                            class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl p-3 text-sm outline-none focus:border-indigo-500 focus:bg-white transition font-semibold">
                        <option value="">Pilih metode pembayaran</option>
                        <option value="va_bca">Virtual Account BCA</option>
                        <option value="va_mandiri">Virtual Account Mandiri</option>
                        <option value="qris">QRIS (OVO/Gopay/Dana)</option>
                    </select>
                </div>

                <div id="paymentInfo" class="hidden">
                    <div id="qrisBox" class="hidden mt-3 border border-slate-200 rounded-xl p-4 bg-slate-50 text-center">
                        <p class="font-bold text-xs text-slate-700 mb-3 tracking-wide">SCAN QRIS DIGITAL ACCESSIBLE:</p>
                        <img src="{{ asset('assets/payment/qris.png') }}" class="w-48 mx-auto rounded-xl shadow-sm border border-slate-300">
                        <p class="text-[10px] text-slate-400 mt-3 font-medium">Scan QR di atas lalu upload screenshot bukti struk pembayaran</p>
                    </div>

                    <div id="transferBox" class="hidden mt-3 border border-slate-200 rounded-xl p-4 bg-slate-50 text-sm text-slate-700 space-y-1">
                        <p class="font-bold text-xs text-indigo-600 uppercase tracking-wide mb-1">Instruksi Transfer Manual:</p>
                        <div class="font-bold text-slate-800">Bank Penerima : BCA / Mandiri</div>
                        <div class="font-mono text-indigo-600 font-bold text-base bg-indigo-5 px-3 py-1 rounded border border-indigo-100 inline-block mt-1">1234567890</div>
                        <div class="text-xs text-slate-400 mt-1 font-medium">a.n Hanna Situmeang</div>
                    </div>

                    <div class="mt-4">
                        <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Upload Bukti Pembayaran</label>
                        {{-- Ditambahkan tag 'required' bersyarat via JavaScript agar aman --}}
                        <input type="file" id="payment_proof_input" name="payment_proof" accept="image/*"
                               class="w-full bg-slate-50 border border-slate-300 text-slate-600 rounded-xl p-2 text-xs outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-100 file:text-indigo-600 hover:file:bg-indigo-200 file:cursor-pointer">
                    </div>
                </div>
                @else
                    <input type="hidden" name="payment_method" value="free_pass">
                    <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium flex items-center gap-2">
                        <span>✨</span> Event ini tidak dipungut biaya. Tiket instan langsung aktif setelah data diverifikasi otomatis.
                    </div>
                @endif

                <button type="submit" class="w-full {{ $isFreeTicket ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white py-3.5 rounded-xl font-bold text-sm shadow-md transition mt-4">
                    <span id="button_text">{{ $isFreeTicket ? 'Ambil Tiket Bebas Biaya' : 'Bayar Rp' . number_format($ticket->price, 0, ',', '.') }}</span>
                </button>
            </form>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // --- INTEGRASI KALKULASI HARGA DINAMIS & STOK ---
    const qtySelect = document.getElementById('quantity_select');
    const hiddenQty = document.getElementById('hidden_quantity');
    const tableSubtotal = document.getElementById('table_subtotal');
    const totalBayarText = document.getElementById('total_bayar_text');
    const buttonText = document.getElementById('button_text');
    const proofInput = document.getElementById('payment_proof_input');
    
    const ticketPrice = {{ (int) $ticket->price }};
    const isFree = {{ $isFreeTicket ? 'true' : 'false' }};

    if (qtySelect) {
        qtySelect.addEventListener('change', function() {
            const currentQty = parseInt(this.value);
            
            // Set value input hidden agar terkirim ke Controller PHP
            hiddenQty.value = currentQty;

            // Hitung nominal baru
            const newTotal = ticketPrice * currentQty;
            
            // Format Rupiah untuk tampilan teks website
            const formattedTotal = 'Rp' + newTotal.toLocaleString('id-ID');

            // Ganti teks element secara realtime tanpa reload halaman
            if (isFree) {
                tableSubtotal.innerText = 'Rp0';
                totalBayarText.innerText = 'Rp0';
                buttonText.innerText = 'Ambil Tiket Bebas Biaya';
            } else {
                tableSubtotal.innerText = formattedTotal;
                totalBayarText.innerText = formattedTotal;
                buttonText.innerText = 'Bayar ' + formattedTotal;
            }
        });
    }

    // --- LOGIKA MENAMPILKAN METODE PEMBAYARAN & VALIDASI WAJIB UPLOAD ---
    const select = document.getElementById('payment_method');
    const paymentInfo = document.getElementById('paymentInfo');
    const qris = document.getElementById('qrisBox');
    const transfer = document.getElementById('transferBox');

    if (select) {
        select.addEventListener('change', function() {
            if (this.value === "") {
                paymentInfo.classList.add('hidden');
                if(proofInput) proofInput.required = false;
                return;
            }

            paymentInfo.classList.remove('hidden');
            if(proofInput) proofInput.required = true; // Kunci formulir wajib upload struk gambar!
            qris.classList.add('hidden');
            transfer.classList.add('hidden');

            if (this.value === "qris") {
                qris.classList.remove('hidden');
            }

            if (this.value === "va_bca" || this.value === "va_mandiri") {
                transfer.classList.remove('hidden');
            }
        });
    }
});
</script>
@endsection