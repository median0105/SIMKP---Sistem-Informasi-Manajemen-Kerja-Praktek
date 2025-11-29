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

        $kerjaPraktek = KerjaPraktek::with(['dosenAkademik.dosen', 'dosenPenguji.dosen'])
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

        // Get unread notifications for this dosen
        $notifications = \App\Models\Notifikasi::where('user_id', $dosenId)
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

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

        // Get today's activities for this dosen
        $today = now()->toDateString();

        $todayActivities = collect();

        // Pengajuan baru hari ini
        $pengajuanHariIni = KerjaPraktek::where('status', KerjaPraktek::STATUS_PENGAJUAN)
            ->whereDate('created_at', $today)
            ->whereHas('dosenPembimbing', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                  ->where('jenis_pembimbingan', 'akademik');
            })
            ->count();

        if ($pengajuanHariIni > 0) {
            $todayActivities->push([
                'type' => 'pengajuan',
                'message' => "{$pengajuanHariIni} Pengajuan Baru",
                'time' => 'Hari ini',
                'color' => 'blue'
            ]);
        }

        // Aktivitas bimbingan hari ini (updated today)
        $bimbinganToday = Bimbingan::whereDate('updated_at', $today)
            ->whereHas('mahasiswa.kerjaPraktek.dosenPembimbing', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                  ->where('jenis_pembimbingan', 'akademik');
            })
            ->count();

        if ($bimbinganToday > 0) {
            $todayActivities->push([
                'type' => 'bimbingan',
                'message' => "{$bimbinganToday} aktivitas bimbingan",
                'time' => 'Hari ini',
                'color' => 'green'
            ]);
        }

        // KP yang diperbarui hari ini
        $kpUpdatedToday = KerjaPraktek::whereDate('updated_at', $today)
            ->whereHas('dosenPembimbing', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                  ->where('jenis_pembimbingan', 'akademik');
            })
            ->count();

        if ($kpUpdatedToday > 0) {
            $todayActivities->push([
                'type' => 'kp_updated',
                'message' => "{$kpUpdatedToday} KP diperbarui",
                'time' => 'Hari ini',
                'color' => 'blue'
            ]);
        }

        // Notifikasi diterima hari ini
        $notificationsToday = \App\Models\Notifikasi::where('user_id', $dosenId)
            ->whereDate('created_at', $today)
            ->count();

        if ($notificationsToday > 0) {
            $todayActivities->push([
                'type' => 'notification',
                'message' => "{$notificationsToday} notifikasi diterima",
                'time' => 'Hari ini',
                'color' => 'purple'
            ]);
        }

        // KP yang disetujui hari ini
        $kpApprovedToday = KerjaPraktek::where('status', KerjaPraktek::STATUS_DISETUJUI)
            ->whereDate('updated_at', $today)
            ->whereHas('dosenPembimbing', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                  ->where('jenis_pembimbingan', 'akademik');
            })
            ->count();

        if ($kpApprovedToday > 0) {
            $todayActivities->push([
                'type' => 'approved',
                'message' => "{$kpApprovedToday} KP disetujui",
                'time' => 'Hari ini',
                'color' => 'green'
            ]);
        }

        // KP yang ditolak hari ini
        $kpRejectedToday = KerjaPraktek::where('status', KerjaPraktek::STATUS_DITOLAK)
            ->whereDate('updated_at', $today)
            ->whereHas('dosenPembimbing', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                  ->where('jenis_pembimbingan', 'akademik');
            })
            ->count();

        if ($kpRejectedToday > 0) {
            $todayActivities->push([
                'type' => 'rejected',
                'message' => "{$kpRejectedToday} KP ditolak",
                'time' => 'Hari ini',
                'color' => 'red'
            ]);
        }

        return [
            'notifications' => $notifications,
            'totalMahasiswaBimbingan' => KerjaPraktek::whereHas('dosenPembimbing', function($q) use ($dosenId) {
                                              $q->where('dosen_id', $dosenId)
                                                ->where('jenis_pembimbingan', 'akademik');
                                          })->count(),
            'pengajuanBaru' => KerjaPraktek::where('status', KerjaPraktek::STATUS_PENGAJUAN)
                                              ->whereHas('dosenPembimbing', function($q) use ($dosenId) {
                                                  $q->where('dosen_id', $dosenId)
                                                    ->where('jenis_pembimbingan', 'akademik');
                                              })->count(),
            'sedangKP' => KerjaPraktek::where('status', KerjaPraktek::STATUS_SEDANG_KP)
                ->whereHas('dosenPembimbing', function($q) use ($dosenId) {
                    $q->where('dosen_id', $dosenId)
                      ->where('jenis_pembimbingan', 'akademik');
                })
                ->where(function($q) {
                    $q->whereNull('nilai_akhir')
                      ->orWhereNull('file_laporan');
                })->count(),
            'selesaiKP' => KerjaPraktek::whereHas('dosenPembimbing', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                  ->where('jenis_pembimbingan', 'akademik');
            })
            ->where(function($q) {
                $q->where('status', KerjaPraktek::STATUS_SELESAI)
                  ->orWhere(function($subQ) {
                      $subQ->where('status', KerjaPraktek::STATUS_SEDANG_KP)
                           ->whereNotNull('nilai_akhir')
                           ->whereNotNull('file_laporan');
                  });
            })->count(),
            'pengajuanTerbaru' => KerjaPraktek::where('status', KerjaPraktek::STATUS_PENGAJUAN)
                                              ->whereHas('dosenPembimbing', function($q) use ($dosenId) {
                                                  $q->where('dosen_id', $dosenId)
                                                    ->where('jenis_pembimbingan', 'akademik');
                                              })
                                              ->with('mahasiswa')
                                              ->latest()
                                              ->take(5)
                                              ->get(),
            'mahasiswaBimbingan' => KerjaPraktek::whereHas('dosenPembimbing', function($q) use ($dosenId) {
                                                $q->where('dosen_id', $dosenId)
                                                  ->where('jenis_pembimbingan', 'akademik');
                                            })
                                            ->with(['mahasiswa', 'tempatMagang'])
                                            ->latest()
                                            ->take(10)
                                            ->get(),
            'seminarPendingCount' => $seminarPendingCount,
            'seminarRegistrations' => $seminarRegistrations,
            'todayActivities' => $todayActivities,
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

    // Get unread notifications for superadmin
    $notifications = \App\Models\Notifikasi::where('user_id', auth()->id())
        ->where('is_read', false)
        ->latest()
        ->take(5)
        ->get();

    // Ambil KP yang ditolak terbaru untuk notifikasi
    $rejectedKPs = KerjaPraktek::where('status', KerjaPraktek::STATUS_DITOLAK)
        ->with('mahasiswa')
        ->latest('updated_at')
        ->take(5)
        ->get();

    // Data untuk tempat magang terpopuler
    $popularTempatMagang = KerjaPraktek::with('tempatMagang')
                                      ->whereNotNull('tempat_magang_id')
                                      ->selectRaw('tempat_magang_id, COUNT(*) as total')
                                      ->groupBy('tempat_magang_id')
                                      ->orderByDesc('total')
                                      ->limit(10)
                                      ->get()
                                      ->map(function($item) {
                                          return [
                                              'nama' => $item->tempatMagang->nama_perusahaan ?? 'Unknown',
                                              'total' => $item->total,
                                              'bidang' => $item->tempatMagang->bidang_usaha ?? ''
                                          ];
                                      });

    return [
        'totalMahasiswa'   => User::where('role', User::ROLE_MAHASISWA)->count(),
        'totalDosen'       => User::where('role', User::ROLE_ADMIN_DOSEN)->count(),
        'totalDosenPenguji' => User::where('role', User::ROLE_ADMIN_DOSEN)->count(), // Dosen penguji sama dengan dosen pembimbing
        'totalPengawas'    => User::where('role', User::ROLE_PENGAWAS_LAPANGAN)->count(),
        'totalTempatMagang'=> TempatMagang::where('is_active', true)->count(),
        'totalKerjaPraktek'=> array_sum($statistikStatus),
        'totalInstansiPending' => KerjaPraktek::where('pilihan_tempat', 3)
            ->where('status', KerjaPraktek::STATUS_PENGAJUAN)
            ->count(),
        'statistikStatus'  => $statistikStatus,
        'notifications'    => $notifications,
        'rejectedKPs'      => $rejectedKPs,
        'popularTempatMagang' => $popularTempatMagang,
    ];
}

    private function getPengawasDashboardData()
    {
        $pengawasId = auth()->id();

        // Mahasiswa yang sedang KP di tempat magang pengawas
        $mahasiswaKP = KerjaPraktek::where('status', KerjaPraktek::STATUS_SEDANG_KP)
            ->whereHas('tempatMagang.pengawasAktif', function($q) use ($pengawasId) {
                $q->where('pengawas_id', $pengawasId);
            })
            ->with('mahasiswa')
            ->count();

        // Laporan pending di tempat magang pengawas
        $laporanPending = KerjaPraktek::whereNull('file_laporan')
            ->where('status', KerjaPraktek::STATUS_SEDANG_KP)
            ->whereHas('tempatMagang.pengawasAktif', function($q) use ($pengawasId) {
                $q->where('pengawas_id', $pengawasId);
            })
            ->count();

        // Notifikasi terbaru untuk pengawas
        $recentNotifications = \App\Models\Notifikasi::where('user_id', $pengawasId)
            ->where('is_read', false)
            ->with('kerjaPraktek.mahasiswa')
            ->latest()
            ->take(5)
            ->get();

        return [
            'mahasiswaKP' => $mahasiswaKP,
            'laporanPending' => $laporanPending,
            'recentNotifications' => $recentNotifications,
        ];
    }
}