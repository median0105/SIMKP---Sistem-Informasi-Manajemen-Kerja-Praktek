<?php

namespace App\Policies;

use App\Models\Kegiatan;
use App\Models\User;

class KegiatanPolicy
{
    public function view(User $user, Kegiatan $kegiatan): bool
    {
        return $user->id === $kegiatan->mahasiswa_id || 
               $user->isAdminDosen() || 
               $user->isSuperAdmin() ||
               $user->isPengawasLapangan();
    }

    public function create(User $user): bool
    {
        return $user->isMahasiswa();
    }

    public function update(User $user, Kegiatan $kegiatan): bool
    {
        return $user->id === $kegiatan->mahasiswa_id;
    }

    public function delete(User $user, Kegiatan $kegiatan): bool
    {
        return $user->id === $kegiatan->mahasiswa_id || 
               $user->isSuperAdmin();
    }
}