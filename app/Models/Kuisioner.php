<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuisioner extends Model
{
    use HasFactory;

    protected $table = 'kuisioner';

    protected $fillable = [
        'kerja_praktek_id',
        'rating_tempat_magang',
        'rating_bimbingan',
        'rating_sistem',
        'saran_perbaikan',
        'kesan_pesan',
        'rekomendasi_tempat',
    ];

    protected $casts = [
        'rating_tempat_magang' => 'integer',
        'rating_bimbingan' => 'integer',
        'rating_sistem' => 'integer',
        'rekomendasi_tempat' => 'boolean',
    ];

    // Relationships
    public function kerjaPraktek()
    {
        return $this->belongsTo(KerjaPraktek::class);
    }

    
}