<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventTicket;

class CartController extends Controller
{

    public function index()
    {
        $cart=session()->get(
            'cart',
            []
        );

        return view(
            'pages.cart',
            compact(
                'cart'
            )
        );
    }



    public function add(
        Request $request
    )
    {

        $ticket=
        EventTicket::findOrFail(
            $request->ticket_id
        );


        $event=
        Event::findOrFail(
            $request->event_id
        );


        $cart=
        session()->get(
            'cart',
            []
        );


        $id=
        $ticket->event_ticket_id;


        $cart[$id]=[

            'event_id'=>
            $event->event_id,

            'ticket_id'=>
            $ticket->event_ticket_id,

            'title'=>
            $event->title,

            'ticket'=>
            $ticket->ticket_type,

            'price'=>
            $ticket->price,

            'image'=>
            optional(
                $event->images->first()
            )->image_path

        ];


        session()->put(
            'cart',
            $cart
        );


        return back()
        ->with(
            'success',
            'Berhasil masuk keranjang'
        );

    }



    public function remove($id)
    {

        $cart=
        session()->get(
            'cart'
        );


        unset(
            $cart[$id]
        );


        session()->put(
            'cart',
            $cart
        );


        return back();

    }

}