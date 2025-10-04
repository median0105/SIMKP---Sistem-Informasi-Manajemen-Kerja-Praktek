<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KerjaPraktek;
use App\Models\TempatMagang;
use App\Models\Bimbingan;
use App\Models\Kegiatan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        switch ($user->role) {
            case User::ROLE_MAHASISWA:
                $data = $this->getMahasiswaDashboardData();
                break;
            case User::ROLE_ADMIN_DOSEN:
                $data = $this->getAdminDosenDashboardData();
                break;
            case User::ROLE_SUPERADMIN:
                $data = $this->getSuperAdminDashboardData();
                break;
            case User::ROLE_PENGAWAS_LAPANGAN:
                $data = $this->getPengawasDashboardData();
                break;
        }

        return view('dashboard', compact('data'));
    }

    private function getMahasiswaDashboardData()
    {
        $userId = auth()->id();

        $kerjaPraktek = KerjaPraktek::with(['dosenAkademik.dosen'])
                                   ->where('mahasiswa_id', $userId)
                                   ->first();

        return [
            'kerjaPraktek' => $kerjaPraktek,
            'totalBimbingan' => Bimbingan::where('mahasiswa_id', $userId)->count(),
            'totalKegiatan' => Kegiatan::where('mahasiswa_id', $userId)->count(),
            'bimbinganTerbaru' => Bimbingan::where('mahasiswa_id', $userId)
                                          ->latest()
                                          ->take(3)
                                          ->get(),
            'kegiatanTerbaru' => Kegiatan::where('mahasiswa_id', $userId)
                                         ->latest()
                                         ->take(5)
                                         ->get(),
        ];
    }

    private function getAdminDosenDashboardData()
    {
        $dosenId = auth()->id();

        // Hitung jumlah mahasiswa yang sudah mendaftar seminar tapi belum di-ACC
        $seminarPendingCount = KerjaPraktek::where('pendaftaran_seminar', true)
            ->where('acc_pendaftaran_seminar', false)
            ->whereHas('dosenPembimbing', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                  ->where('jenis_pembimbingan', 'akademik');
            })
            ->count();

        // Get recent seminar registrations for this dosen
        $seminarRegistrations = KerjaPraktek::where('pendaftaran_seminar', true)
            ->whereHas('dosenPembimbing', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                  ->where('jenis_pembimbingan', 'akademik');
            })
            ->with('mahasiswa')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return [
            'totalMahasiswa' => User::where('role', User::ROLE_MAHASISWA)->count(),
            'pengajuanBaru' => KerjaPraktek::where('status', KerjaPraktek::STATUS_PENGAJUAN)->count(),
            'sedangKP' => KerjaPraktek::where('status', KerjaPraktek::STATUS_SEDANG_KP)
                ->where(function($q) {
                    $q->whereNull('nilai_akhir')
                      ->orWhereNull('file_laporan');
                })->count(),
            'selesaiKP' => KerjaPraktek::where('status', KerjaPraktek::STATUS_SELESAI)
                ->orWhere(function($q) {
                    $q->where('status', KerjaPraktek::STATUS_SEDANG_KP)
                      ->whereNotNull('nilai_akhir')
                      ->whereNotNull('file_laporan');
                })->count(),
            'pengajuanTerbaru' => KerjaPraktek::where('status', KerjaPraktek::STATUS_PENGAJUAN)
                                              ->with('mahasiswa')
                                              ->latest()
                                              ->take(5)
                                              ->get(),
            'mahasiswaBimbinganAcc' => KerjaPraktek::where('status', KerjaPraktek::STATUS_DISETUJUI)
                                                  ->whereHas('dosenPembimbing', function($q) use ($dosenId) {
                                                      $q->where('dosen_id', $dosenId)
                                                        ->where('jenis_pembimbingan', 'akademik');
                                                  })
                                                  ->with(['mahasiswa', 'tempatMagang'])
                                                  ->latest()
                                                  ->take(10)
                                                  ->get(),
            'seminarPendingCount' => $seminarPendingCount,
            'seminarRegistrations' => $seminarRegistrations,
        ];
    }

    private function getSuperAdminDashboardData()
{
    // daftar status yang mau ditampilkan
    $statuses = [
        'pengajuan'  => KerjaPraktek::STATUS_PENGAJUAN,
        'disetujui'  => KerjaPraktek::STATUS_DISETUJUI,
        'sedang_kp'  => KerjaPraktek::STATUS_SEDANG_KP,
        'selesai'    => KerjaPraktek::STATUS_SELESAI,
        'ditolak'    => KerjaPraktek::STATUS_DITOLAK,
    ];

    $statistikStatus = [];
    foreach ($statuses as $label => $status) {
        $statistikStatus[$label] = KerjaPraktek::where('status', $status)->count();
    }

    return [
        'totalMahasiswa'   => User::where('role', User::ROLE_MAHASISWA)->count(),
        'totalDosen'       => User::where('role', User::ROLE_ADMIN_DOSEN)->count(),
        'totalPengawas'    => User::where('role', User::ROLE_PENGAWAS_LAPANGAN)->count(),
        'totalTempatMagang'=> TempatMagang::where('is_active', true)->count(),
        'totalKerjaPraktek'=> array_sum($statistikStatus),
        'statistikStatus'  => $statistikStatus,
    ];
}

    private function getPengawasDashboardData()
    {
        return [
            'mahasiswaKP' => KerjaPraktek::where('status', KerjaPraktek::STATUS_SEDANG_KP)
                                        ->with('mahasiswa')
                                        ->count(),
            'laporanPending' => KerjaPraktek::whereNull('file_laporan')
                                           ->where('status', KerjaPraktek::STATUS_SEDANG_KP)
                                           ->count(),
        ];
    }
}