<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Periode extends Model
{
    use HasFactory;

    protected $table = 'periodes';

    protected $fillable = [
        'tahun_akademik',
        'semester_ke',
        'semester_type',
        'tanggal_mulai_kp',
        'tanggal_selesai_kp',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai_kp' => 'date',
        'tanggal_selesai_kp' => 'date',
        'status' => 'boolean',
    ];

    /**
     * Scope for active periode.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for current periode based on dates.
     */
    public function scopeCurrent($query)
    {
        return $query->where('tanggal_mulai_kp', '<=', Carbon::today())
                     ->where('tanggal_selesai_kp', '>=', Carbon::today());
    }

    /**
     * Check if this periode is currently active and within dates.
     */
    public function isActiveNow(): bool
    {
        return $this->status &&
               Carbon::today()->between($this->tanggal_mulai_kp, $this->tanggal_selesai_kp);
    }
}
