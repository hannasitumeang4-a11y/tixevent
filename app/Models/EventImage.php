<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    protected $table = 'event_images';
    protected $primaryKey = 'event_image_id';
    
    protected $fillable = ['event_id', 'image_path', 'is_primary'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }
}