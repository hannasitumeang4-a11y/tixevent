<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role' // ✅ FIX: dari role_id jadi role
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ❌ HAPUS relasi role karena tidak pakai tabel roles
    // public function role() { ... }

    public function events()
    {
        return $this->hasMany(Event::class, 'user_id', 'user_id');
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