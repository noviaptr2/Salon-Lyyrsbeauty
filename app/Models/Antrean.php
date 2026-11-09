<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Antrean extends Model
{
    protected $fillable = [
        'user_id',
        'nomor_antrean',
        'nama_pelanggan',
        'no_hp',
        'layanan_id',
        'tanggal_antrean',
        'status',
        'payment_status',
        'total_pembayaran',
        'payment_proof',
        'payment_verified_at',
    ];

    protected $casts = [
        'tanggal_antrean' => 'date',
        'payment_verified_at' => 'datetime',
    ];

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
