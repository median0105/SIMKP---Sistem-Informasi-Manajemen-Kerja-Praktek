<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    use HasFactory;

    protected $table = 'bimbingan';

    protected $fillable = [
        'kerja_praktek_id',
        'mahasiswa_id',
        'tanggal_bimbingan',
        'topik_bimbingan',
        'catatan_mahasiswa',
        'catatan_dosen',
        'status_verifikasi',
    ];

    protected $casts = [
        'tanggal_bimbingan' => 'date',
        'status_verifikasi' => 'boolean',
    ];

    // Relationships
    public function kerjaPraktek()
    {
        return $this->belongsTo(KerjaPraktek::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}