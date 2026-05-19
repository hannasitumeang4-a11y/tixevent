<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey='user_id';

    protected $fillable=[
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden=[
        'password',
        'remember_token',
    ];

    public function events()
    {
        return $this->hasMany(Event::class,'user_id','user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'user_id','user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}