<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'statut',
        // 'reserved_at',
        'cancelled_at',
    ];

    public function user() { return $this->belongsToMany(User::class); }
    public function book() { return $this->belongsToMany(Book::class); }
}