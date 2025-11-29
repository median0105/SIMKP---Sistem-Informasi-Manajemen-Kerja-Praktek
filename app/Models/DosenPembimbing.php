<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenPembimbing extends Model
{
    use HasFactory;

    protected $table = 'dosen_pembimbing';

    protected $fillable = [
        'kerja_praktek_id',
        'dosen_id',
        'jenis_pembimbingan', // 'akademik' atau 'lapangan'
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function kerjaPraktek()
    {
        return $this->belongsTo(KerjaPraktek::class);
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}