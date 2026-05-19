<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey='order_id';

    protected $fillable=[

        'order_code',
        'user_id',
        'event_id',
        'ticket_id',
        'quantity',
        'total_amount',
        'payment_method',
        'payment_proof',
        'order_status'
    ];


    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'user_id'
        );
    }


    public function event()
    {
        return $this->belongsTo(
            Event::class,
            'event_id',
            'event_id'
        );
    }


    public function ticket()
    {
        return $this->belongsTo(
            EventTicket::class,
            'ticket_id',
            'event_ticket_id'
        );
    }
}