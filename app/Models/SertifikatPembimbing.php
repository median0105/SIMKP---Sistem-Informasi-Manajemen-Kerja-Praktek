<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikatPembimbing extends Model
{
    use HasFactory;

    protected $table = 'sertifikat_pembimbing';

    protected $fillable = [
        'pembimbing_lapangan_id',
        'kerja_praktek_id',
        'nomor_sertifikat',
        'tanggal_terbit',
        'file_sertifikat',
        'is_sent',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'is_sent' => 'boolean',
    ];

    // Relationships
    public function pembimbingLapangan()
    {
        return $this->belongsTo(User::class, 'pembimbing_lapangan_id');
    }

    public function kerjaPraktek()
    {
        return $this->belongsTo(KerjaPraktek::class);
    }
}