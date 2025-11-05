<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisionerQuestion extends Model
{
    protected $fillable = [
        'question_text',
        'type',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // Scope untuk pertanyaan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
