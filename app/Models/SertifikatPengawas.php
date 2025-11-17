<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikatPengawas extends Model
{
    protected $fillable = [
        'nama_pengawas',
        'nomor_sertifikat',
        'tahun_ajaran',
        'nama_kaprodi',
        'nip_kaprodi',
        'file_path',
        'is_generated',
    ];

    protected $casts = [
        'is_generated' => 'boolean',
    ];
}
