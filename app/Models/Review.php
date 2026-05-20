<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // Nama tabel di database
    protected $table = 'reviews';

    // Kolom yang diizinkan untuk diisi massal
    protected $fillable = [
        'user_id',
        'event_id',
        'rating',
        'comment', // Harus 'comment' agar sesuai dengan database hasil migration Anda
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}