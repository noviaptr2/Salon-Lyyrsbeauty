<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga',
        'durasi',
        'foto',
    ];

    public function antreans(): HasMany
    {
        return $this->hasMany(Antrean::class);
    }
}
