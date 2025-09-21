<?php

// app/Models/TempatMagang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TempatMagang extends Model
{
    use HasFactory;

    protected $table = 'tempat_magang';

    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'kontak_person',
        'email_perusahaan',
        'telepon_perusahaan',
        'bidang_usaha',
        'kuota_mahasiswa',
        'is_active',
        'deskripsi',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'kuota_mahasiswa' => 'integer',
    ];

    public function kerjaPraktek()
    {
        return $this->hasMany(KerjaPraktek::class);
    }

    // Scope: hanya yang aktif
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    // Accessor sisa_kuota (butuh withCount alias 'terpakai_count' agar efisien)
    protected function sisaKuota(): Attribute
    {
        return Attribute::make(
            get: function () {
                // fallback jika tidak eager count
                $terpakai = $this->kerjaPraktek()
                    ->whereIn('status', ['disetujui', 'sedang_kp'])
                    ->count();

                return max(0, (int)$this->kuota_mahasiswa - (int)$terpakai);
            }
        );
    }
    public function getDisplayNamaAttribute(): ?string
    {
        return $this->attributes['nama']
            ?? $this->attributes['nama_tempat']
            ?? $this->attributes['nama_instansi']
            ?? $this->attributes['perusahaan']
            ?? null;
    }
    // Tambahkan di bagian relationships

    public function pengawas()
    {
        return $this->belongsToMany(User::class, 'pengawas_tempat_magang', 'tempat_magang_id', 'pengawas_id')
                    ->withPivot(['is_active', 'jabatan_pengawas', 'deskripsi_tugas'])
                    ->withTimestamps();
    }

    public function pengawasAktif()
    {
        return $this->pengawas()->wherePivot('is_active', true);
    }
}
