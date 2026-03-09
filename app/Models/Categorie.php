<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = [
        'name'
    ];
    public function livres()
    {
        return $this->hasMany(Livre::class);
    }
}
