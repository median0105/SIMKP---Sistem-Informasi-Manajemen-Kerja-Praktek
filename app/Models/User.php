<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'npm',
        'nip',
        'role',
        'tempat_magang_id',
        'phone',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Role constants
    const ROLE_MAHASISWA = 'mahasiswa';
    const ROLE_ADMIN_DOSEN = 'admin_dosen';
    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_PENGAWAS_LAPANGAN = 'pengawas_lapangan';

    // Relationships
    public function kerjaPraktek()
    {
        return $this->hasMany(KerjaPraktek::class, 'mahasiswa_id');
    }

    public function bimbingan()
    {
        return $this->hasMany(Bimbingan::class, 'mahasiswa_id');
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'mahasiswa_id');
    }

    // Helper methods
    public function isMahasiswa()
    {
        return $this->role === self::ROLE_MAHASISWA;
    }

    public function isAdminDosen()
    {
        return $this->role === self::ROLE_ADMIN_DOSEN;
    }

    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function isPengawasLapangan()
    {
        return $this->role === self::ROLE_PENGAWAS_LAPANGAN;
    }

    /**
     * Check if user can access KP features based on periode
     */
    public function canAccessKP(): bool
    {
        // SuperAdmin always has access
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Check periode access for regular users
        $access = \App\Services\PeriodeService::canAccessKPSystem($this);
        return $access['can_access'];
    }

    /**
     * Get user's access level for current periode
     */
    public function getKPAccessLevel(): string
    {
        // SuperAdmin always has full access
        if ($this->isSuperAdmin()) {
            return 'full';
        }

        $access = \App\Services\PeriodeService::canAccessKPSystem($this);
        
        if ($access['can_access']) {
            return 'full';
        }

        // If periode exists but not in range, allow read-only
        if ($access['periode']) {
            return 'read-only';
        }

        return 'none';
    }

    // app/Models/User.php

    public function kpTerakhir()
    {
        // ambil KP terbaru per mahasiswa
        return $this->hasOne(\App\Models\KerjaPraktek::class, 'mahasiswa_id')->latestOfMany();
    }
    // app/Models/User.php
    public function kpTerbaru()
    {
        return $this->hasOne(\App\Models\KerjaPraktek::class, 'mahasiswa_id')->latestOfMany();
    }

    // Tambahkan di bagian relationships

    public function pengawasTempatMagang()
    {
        return $this->hasMany(PengawasTempatMagang::class, 'pengawas_id');
    }

    public function tempatMagangYangDiawasi()
    {
        return $this->belongsToMany(TempatMagang::class, 'pengawas_tempat_magang', 'pengawas_id', 'tempat_magang_id')
                    ->withPivot(['is_active', 'jabatan_pengawas', 'deskripsi_tugas'])
                    ->withTimestamps();
    }
    // app/Models/User.php
    public function tempatMagang()
    {
        return $this->belongsTo(TempatMagang::class);
    }


}