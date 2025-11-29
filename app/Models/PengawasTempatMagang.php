<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengawasTempatMagang extends Model
{
    use HasFactory;

    protected $table = 'pengawas_tempat_magang';

    protected $fillable = [
        'pengawas_id',
        'tempat_magang_id',
        'is_active',
        'jabatan_pengawas',
        'deskripsi_tugas',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function pengawas()
    {
        return $this->belongsTo(User::class, 'pengawas_id');
    }

    public function tempatMagang()
    {
        return $this->belongsTo(TempatMagang::class);
    }
}