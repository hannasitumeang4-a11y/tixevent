<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $primaryKey='event_id';

    protected $fillable=[
        'title',
        'slug',
        'description',
        'category_id',
        'organizer_id',
        'location',
        'event_date',
        'start_time',
        'end_time',
        'price',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(
            Category::class,
            'category_id',
            'category_id'
        );
    }

    public function images()
    {
        return $this->hasMany(
            EventImage::class,
            'event_id',
            'event_id'
        );
    }

    public function tickets()
    {
        return $this->hasMany(
            EventTicket::class,
            'event_id',
            'event_id'
        );
    }

    public function primaryImage()
    {
        return $this->hasOne(
            EventImage::class,
            'event_id',
            'event_id'
        )->where('is_primary',1);
    }
}