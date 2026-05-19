@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Checkout
        </h1>

        <p class="text-gray-500 text-sm">
            Selesaikan pesananmu untuk mendapatkan tiket.
        </p>
    </div>

    @php
        $qty=1;
        $subtotal=$ticket->price*$qty;
    @endphp


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- DETAIL PESANAN -->
        <div class="border rounded-2xl p-6 bg-white shadow-sm h-fit">

            <h2 class="font-bold text-lg mb-4 flex items-center gap-2">

                <span>🛒</span>

                Detail Pesanan

            </h2>


            <!-- EVENT -->

            <div class="mb-4 p-4 bg-indigo-50 rounded-xl">

                <div class="font-bold text-indigo-900 text-base">

                    {{ $event->title }}

                </div>


                <div class="text-xs text-indigo-600 mt-1 flex items-center gap-1">

                    <span>📅</span>

                    {{ \Carbon\Carbon::parse(
                    $event->event_date
                    )->format('d M Y') }}

                    |

                    <span>📍</span>

                    {{ $event->location }}

                </div>

            </div>



            <div class="overflow-x-auto">

                <table class="w-full text-sm mb-6">

                    <thead>

                    <tr class="text-left text-gray-400 border-b">

                        <th class="pb-2">
                            Jenis Tiket
                        </th>

                        <th class="pb-2">
                            Harga
                        </th>

                        <th class="pb-2 text-center">
                            Jumlah
                        </th>

                        <th class="pb-2 text-right">
                            Subtotal
                        </th>

                    </tr>

                    </thead>


                    <tbody>

                    <tr class="border-b">

                        <td class="py-4 font-medium">

                            {{ $ticket->ticket_type }}

                        </td>

                        <td class="py-4">

                            Rp{{ number_format(
                            $ticket->price,
                            0,
                            ',',
                            '.'
                            ) }}

                        </td>

                        <td class="py-4 text-center">

                            {{ $qty }}

                        </td>

                        <td class="py-4 text-right">

                            Rp{{ number_format(
                            $subtotal,
                            0,
                            ',',
                            '.'
                            ) }}

                        </td>

                    </tr>

                    </tbody>

                </table>

            </div>



            <div class="flex justify-between items-center font-bold text-xl">

                <span>

                    Total Bayar

                </span>

                <span class="text-indigo-600">

                    Rp{{ number_format(
                    $subtotal,
                    0,
                    ',',
                    '.'
                    ) }}

                </span>

            </div>

        </div>




        <!-- FORM -->

        <div class="border rounded-2xl p-6 bg-white shadow-sm">

            <h2 class="font-bold text-lg mb-4">

                👤 Data Pemesan

            </h2>


            <form
            action="{{ route('checkout.process') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-4">

            @csrf


            <input
            type="hidden"
            name="event_id"
            value="{{ $event->event_id }}">


            <input
            type="hidden"
            name="ticket_id"
            value="{{ $ticket->event_ticket_id }}">


            <input
            type="hidden"
            name="total"
            value="{{ $subtotal }}">



            <div>

                <label class="block mb-2 font-semibold">

                    Nama Lengkap

                </label>

                <input
                type="text"
                name="name"
                required
                value="{{ auth()->user()->name ?? '' }}"
                class="w-full border rounded-xl p-3">

            </div>




            <div>

                <label class="block mb-2 font-semibold">

                    Email

                </label>

                <input
                type="email"
                name="email"
                required
                value="{{ auth()->user()->email ?? '' }}"
                class="w-full border rounded-xl p-3">

            </div>




            <div>

                <label class="block mb-2 font-semibold">

                    Nomor HP

                </label>

                <input
                type="text"
                name="phone"
                required
                class="w-full border rounded-xl p-3"
                placeholder="08123456789">

            </div>




            <div>

                <label class="block mb-2 font-semibold">

                    Metode Pembayaran

                </label>

                <select
                id="payment_method"
                name="payment_method"
                required
                class="w-full border rounded-xl p-3">

                    <option value="">

                        Pilih metode pembayaran

                    </option>

                    <option value="va_bca">

                        Virtual Account BCA

                    </option>

                    <option value="va_mandiri">

                        Virtual Account Mandiri

                    </option>

                    <option value="qris">

                        QRIS (OVO/Gopay/Dana)

                    </option>

                </select>

            </div>




            <!-- BAGIAN DINAMIS -->

            <div
            id="paymentInfo"
            class="hidden">


                <!-- QR -->

                <div
                id="qrisBox"
                class="hidden mt-4 border rounded-xl p-4 bg-gray-50">

                    <p class="font-bold mb-3">

                        Scan QRIS:

                    </p>

                    <img
                    src="{{ asset('assets/payment/qris.png') }}"
                    class="w-64 rounded shadow">


                    <p class="text-xs text-gray-500 mt-3">

                        Scan QR lalu upload bukti pembayaran

                    </p>

                </div>




                <!-- REKENING -->

                <div
                id="transferBox"
                class="hidden mt-4 border rounded-xl p-4 bg-gray-50">

                    <p class="font-bold">

                        Transfer ke rekening:

                    </p>


                    <div class="mt-3">

                        BCA :
                        1234567890

                    </div>

                    <div>

                        a.n Hanna Situmeang

                    </div>

                </div>





                <div class="mt-4">

                    <label class="block mb-2 font-semibold">

                        Upload Bukti Pembayaran

                    </label>

                    <input
                    type="file"
                    name="payment_proof"
                    accept="image/*"
                    class="w-full border rounded-xl p-3">

                </div>


            </div>




            <button
            type="submit"
            class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700">

                Bayar Rp{{ number_format(
                $subtotal,
                0,
                ',',
                '.'
                ) }}

            </button>


            </form>

        </div>

    </div>

</div>




<script>

const select=
document.getElementById(
'payment_method'
);

const paymentInfo=
document.getElementById(
'paymentInfo'
);

const qris=
document.getElementById(
'qrisBox'
);

const transfer=
document.getElementById(
'transferBox'
);



select.addEventListener(
'change',
function(){


paymentInfo.classList.remove(
'hidden'
);


qris.classList.add(
'hidden'
);


transfer.classList.add(
'hidden'
);



if(
this.value=="qris"
){

qris.classList.remove(
'hidden'
);

}



if(
this.value=="va_bca"
||
this.value=="va_mandiri"
){

transfer.classList.remove(
'hidden'
);

}

});

</script>

@endsection