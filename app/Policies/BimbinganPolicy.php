<?php

namespace App\Policies;

use App\Models\Bimbingan;
use App\Models\User;

class BimbinganPolicy
{
    public function view(User $user, Bimbingan $bimbingan): bool
    {
        return $user->id === $bimbingan->mahasiswa_id || 
               $user->isAdminDosen() || 
               $user->isSuperAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isMahasiswa();
    }

    public function update(User $user, Bimbingan $bimbingan): bool
    {
        return $user->id === $bimbingan->mahasiswa_id || 
               $user->isAdminDosen();
    }

    public function delete(User $user, Bimbingan $bimbingan): bool
    {
        return $user->id === $bimbingan->mahasiswa_id || 
               $user->isSuperAdmin();
    }
}