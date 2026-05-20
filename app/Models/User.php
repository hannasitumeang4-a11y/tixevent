<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ==========================================
    // LOGIC HELPER UNTUK PENGECEKAN TIGA ROLE
    // ==========================================
    
    /**
     * Cek apakah user adalah Admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah Customer (menggantikan 'user' lama)
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    /**
     * Cek apakah user adalah Organizer (Role Baru)
     */
    public function isOrganizer()
    {
        return $this->role === 'organizer';
    }

    // ==========================================
    // RELASI ANTAR TABEL DATABASE
    // ==========================================

    public function events()
    {
        return $this->hasMany(Event::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
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