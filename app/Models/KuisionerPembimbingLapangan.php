<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuisionerPembimbingLapangan extends Model
{
    use HasFactory;

    protected $table = 'kuisioner_pembimbing_lapangan';

    protected $fillable = [
        'kerja_praktek_id',
        'pembimbing_lapangan_id',
        'rating_mahasiswa',
        'komentar_kinerja',
        'saran_mahasiswa',
        'rekomendasi_mahasiswa',
        'kelebihan_mahasiswa',
        'kekurangan_mahasiswa',
        'tanggal_feedback',
    ];

    protected $casts = [
        'rekomendasi_mahasiswa' => 'boolean',
        'tanggal_feedback' => 'datetime',
        'rating_mahasiswa' => 'integer',
    ];

    // Relationships
    public function kerjaPraktek()
    {
        return $this->belongsTo(KerjaPraktek::class);
    }

    public function pembimbingLapangan()
    {
        return $this->belongsTo(User::class, 'pembimbing_lapangan_id');
    }
}