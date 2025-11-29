<?php

namespace App\Services;

use App\Models\Periode;
use Carbon\Carbon;

class PeriodeService
{
    /**
     * Get active periode
     */
    public static function getActivePeriode(): ?Periode
    {
        return Periode::where('status', true)->first();
    }

    /**
     * Check if current date is within active periode range
     */
    public static function isWithinPeriodeRange(): bool
    {
        $periode = self::getActivePeriode();
        
        if (!$periode) {
            return false;
        }

        $today = Carbon::today();
        return $today->between($periode->tanggal_mulai_kp, $periode->tanggal_selesai_kp);
    }

    /**
     * Check if user can access KP system based on periode
     */
    public static function canAccessKPSystem($user = null): array
    {
        $user = $user ?? auth()->user();

        // Superadmin always has access
        if ($user && $user->isSuperAdmin()) {
            return [
                'can_access' => true,
                'message' => null,
                'periode' => self::getActivePeriode()
            ];
        }

        $periode = self::getActivePeriode();

        // No active periode
        if (!$periode) {
            return [
                'can_access' => false,
                'message' => 'Belum ada periode Kerja Praktek yang aktif, Silakan hubungi Prodi.',
                'periode' => null
            ];
        }

        $today = Carbon::today();

        // Before start date
        if ($today->lt($periode->tanggal_mulai_kp)) {
            return [
                'can_access' => false,
                'message' => 'Sistem Kerja Praktek belum dibuka, Periode akan dimulai pada ' . 
                             $periode->tanggal_mulai_kp->format('d F Y'),
                'periode' => $periode
            ];
        }

        // After end date
        if ($today->gt($periode->tanggal_selesai_kp)) {
            return [
                'can_access' => false,
                'message' => 'Periode Kerja Praktek telah berakhir pada ' . 
                             $periode->tanggal_selesai_kp->format('d F Y'),
                'periode' => $periode
            ];
        }

        // Within range
        return [
            'can_access' => true,
            'message' => null,
            'periode' => $periode
        ];
    }

    /**
     * Get periode status message for display
     */
    public static function getPeriodeStatusMessage(): ?string
    {
        $result = self::canAccessKPSystem();
        return $result['message'];
    }
}
