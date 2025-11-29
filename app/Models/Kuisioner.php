<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuisioner extends Model
{
    use HasFactory;

    protected $table = 'kuisioner';

    protected $fillable = [
        'kerja_praktek_id',
        'rating_tempat_magang',
        'rating_bimbingan',
        'rating_sistem',
        'saran_perbaikan',
        'kesan_pesan',
        'rekomendasi_tempat',
        'dynamic_answers',
    ];

    protected $casts = [
        'rating_tempat_magang' => 'integer',
        'rating_bimbingan' => 'integer',
        'rating_sistem' => 'integer',
        'rekomendasi_tempat' => 'boolean',
        'dynamic_answers' => 'array',
    ];

    // Relationships
    public function kerjaPraktek()
    {
        return $this->belongsTo(KerjaPraktek::class);
    }

    // Accessor untuk rating keseluruhan
    public function getRatingKeseluruhanAttribute()
    {
        // Kumpulkan semua rating
        $ratings = [
            $this->rating_tempat_magang,
            $this->rating_bimbingan,
            $this->rating_sistem,
        ];

        // Tambahkan rating dari dynamic_answers
        if ($this->dynamic_answers) {
            foreach ($this->dynamic_answers as $answer) {
                if (isset($answer['rating']) && is_numeric($answer['rating'])) {
                    $ratings[] = (int) $answer['rating'];
                }
            }
        }

        // Jika tidak ada rating, return null
        if (empty($ratings)) {
            return null;
        }

        // Cari rating terendah
        $minRating = min($ratings);

        // Tentukan kategori berdasarkan rating terendah
        if ($minRating == 5) {
            return 'Sangat Baik';
        } elseif ($minRating == 4) {
            return 'Baik';
        } elseif ($minRating == 3) {
            return 'Cukup Baik';
        } else {
            return 'Kurang Baik';
        }
    }
}
