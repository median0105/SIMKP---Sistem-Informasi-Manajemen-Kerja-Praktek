<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisionerPengawas extends Model
{
    protected $fillable = [
        'pengawas_id',
        'kuisioner_pengawas_question_id',
        'answer',
        'rating',
        'yes_no',
        'submitted_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'yes_no' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    // Relationships
    public function pengawas()
    {
        return $this->belongsTo(User::class, 'pengawas_id');
    }

    public function question()
    {
        return $this->belongsTo(KuisionerPengawasQuestion::class, 'kuisioner_pengawas_question_id');
    }
}
