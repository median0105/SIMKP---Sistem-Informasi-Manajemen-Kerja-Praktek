<?php

namespace App\Policies;

use App\Models\KerjaPraktek;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KerjaPraktekPolicy
{
    use HandlesAuthorization;

    /**
     * Superadmin boleh segalanya.
     */
    public function before(User $user, string $ability): ?bool
    {
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return true;
        }
        return null;
    }

    /**
     * Siapa saja yang boleh melihat data KP tertentu:
     * - Pemilik (mahasiswa pemilik KP)
     * - Admin/Dosen
     * - Pengawas lapangan
     * - (Superadmin di-handle by before)
     */
    public function view(User $user, KerjaPraktek $kerjaPraktek): bool
    {
        return $user->id === $kerjaPraktek->mahasiswa_id
            || (method_exists($user, 'isAdminDosen') && $user->isAdminDosen())
            || (method_exists($user, 'isPengawasLapangan') && $user->isPengawasLapangan());
    }

    /**
     * Buat pengajuan KP: hanya mahasiswa.
     */
    public function create(User $user): bool
    {
        return method_exists($user, 'isMahasiswa') && $user->isMahasiswa();
    }

    /**
     * Update data KP dari sisi mahasiswa (upload laporan/kartu, isi kuisioner, dll):
     * hanya pemilik.
     */
    public function update(User $user, KerjaPraktek $kerjaPraktek): bool
    {
        return $user->id === $kerjaPraktek->mahasiswa_id;
    }

    /**
     * Hapus data KP: hanya superadmin (sudah auto-allow di before()).
     * Diset juga di sini untuk dokumentasi eksplisit.
     */
    public function delete(User $user, KerjaPraktek $kerjaPraktek): bool
    {
        return method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin();
    }

    /**
     * Ability sisi Admin/Dosen (opsional; pakai jika controller admin memanggil authorize()).
     * Sekarang controller admin kamu belum pakai policy, tapi biar siap kalau mau ditambahkan.
     */
    public function approve(User $user, KerjaPraktek $kerjaPraktek): bool
    {
        return method_exists($user, 'isAdminDosen') && $user->isAdminDosen();
    }

    public function reject(User $user, KerjaPraktek $kerjaPraktek): bool
    {
        return method_exists($user, 'isAdminDosen') && $user->isAdminDosen();
    }

    public function accSeminar(User $user, KerjaPraktek $kerjaPraktek): bool
    {
        return method_exists($user, 'isAdminDosen') && $user->isAdminDosen();
    }

    public function start(User $user, KerjaPraktek $kerjaPraktek): bool
    {
        return method_exists($user, 'isAdminDosen') && $user->isAdminDosen();
    }

    public function inputNilai(User $user, KerjaPraktek $kerjaPraktek): bool
    {
        return method_exists($user, 'isAdminDosen') && $user->isAdminDosen();
    }

    public function sendReminder(User $user, KerjaPraktek $kerjaPraktek): bool
    {
        return method_exists($user, 'isAdminDosen') && $user->isAdminDosen();
    }
}
