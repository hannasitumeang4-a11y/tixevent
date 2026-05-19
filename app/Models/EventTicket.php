<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    protected $primaryKey='event_ticket_id';

    protected $fillable=[

        'event_id',
        'ticket_type',
        'price',
        'stock',
        'max_buy_per_order',
        'status'

    ];


    public function event()
    {
        return $this->belongsTo(
            Event::class,
            'event_id',
            'event_id'
        );
    }
}