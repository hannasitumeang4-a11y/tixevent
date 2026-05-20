<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // Mengunci Primary Key bawaan tabel phpMyAdmin kamu
    protected $primaryKey = 'event_id';

    // PENTING: Mendaftarkan semua field inputan controller agar lolos Mass Assignment
    protected $fillable = [
        'title',
        'slug',
        'description', 
        'category_id',
        'organizer_id', // <-- DI-UPGRADE: Dipastikan aman dari proteksi mass assignment!
        'location',
        'event_date',   
        'start_time',
        'end_time',
        'price',
        'status'
    ];

    // ==========================================
    // ELOQUENT RELATIONSHIPS (SINKRONISASI TOTAL)
    // ==========================================

    /**
     * Relasi ke Tabel Users (Promotor / Organizer)
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id', 'user_id');
    }

    /**
     * Relasi ke Tabel Categories
     */
    public function category()
    {
        return $this->belongsTo(
            Category::class,
            'category_id',
            'category_id'
        );
    }

    /**
     * Relasi ke Tabel Event Images (Banyak Foto Poster)
     */
    public function images()
    {
        return $this->hasMany(
            EventImage::class,
            'event_id',
            'event_id'
        );
    }

    /**
     * Relasi ke Tabel Event Tickets (VIP, REGULAR, PRESALE)
     */
    public function tickets()
    {
        return $this->hasMany(
            EventTicket::class,
            'event_id',
            'event_id'
        );
    }

    /**
     * Relasi Khusus: Mengambil satu poster utama
     */
    public function primaryImage()
    {
        return $this->hasOne(
            EventImage::class,
            'event_id',
            'event_id'
        )->where('is_primary', 1);
    }

    // ==========================================
    // UTILITY ACCESSORS & HELPERS (ANTI TIKET GRATIS)
    // ==========================================

    /**
     * Helper: Cek apakah event ini sudah punya tiket aktif di database atau belum
     */
    public function hasTickets()
    {
        return $this->tickets()->count() > 0;
    }

    /**
     * Helper: Mengambil kisaran harga tiket terendah untuk ditampilkan di halaman katalog depan
     */
    public function getMinPriceAttribute()
    {
        // Cek dulu apakah manifes tiket di database benar-benar ada dan tidak kosong
        if ($this->tickets()->count() > 0) {
            return $this->tickets()->min('price');
        }
        return $this->price; // fallback ke harga dasar jika tiket belum di-generate
    }
}