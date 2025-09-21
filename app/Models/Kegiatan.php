<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'kerja_praktek_id',
        'mahasiswa_id',
        'tanggal_kegiatan',
        'deskripsi_kegiatan',
        'file_dokumentasi',
        'durasi_jam',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'durasi_jam' => 'integer',
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