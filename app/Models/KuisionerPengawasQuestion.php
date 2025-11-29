<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisionerPengawasQuestion extends Model
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

    // Relationship dengan responses
    public function responses()
    {
        return $this->hasMany(KuisionerPengawas::class, 'kuisioner_pengawas_question_id');
    }
}
