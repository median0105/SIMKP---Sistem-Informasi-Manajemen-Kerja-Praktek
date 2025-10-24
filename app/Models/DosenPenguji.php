<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenPenguji extends Model
{
    protected $table = 'dosen_penguji';

    protected $fillable = [
        'kerja_praktek_id',
        'dosen_id',
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
