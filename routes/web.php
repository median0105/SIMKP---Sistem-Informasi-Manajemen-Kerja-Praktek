<?php

use Illuminate\Support\Facades\Route;

// ===== Public Controllers =====
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// ===== Mahasiswa Controllers =====
use App\Http\Controllers\Mahasiswa\KerjaPraktekController as MahasiswaKerjaPraktekController;
use App\Http\Controllers\Mahasiswa\BimbinganController as MahasiswaBimbinganController;
use App\Http\Controllers\Mahasiswa\KegiatanController as MahasiswaKegiatanController;

// ===== Admin/Dosen Controllers =====
use App\Http\Controllers\Admin\KerjaPraktekController as AdminKerjaPraktekController;
use App\Http\Controllers\Admin\BimbinganController as AdminBimbinganController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;

// ===== Super Admin Controllers =====
use App\Http\Controllers\SuperAdmin\UserController as SAUserController;
use App\Http\Controllers\SuperAdmin\TempatMagangController as SATempatMagangController;
use App\Http\Controllers\SuperAdmin\LaporanController as SALaporanController;
use App\Http\Controllers\SuperAdmin\KuisionerController as SAKuisionerController;
use App\Http\Controllers\SuperAdmin\KegiatanController as SAKegiatanController;

// ===== Pengawas Lapangan Controllers =====
use App\Http\Controllers\PengawasLapangan\MahasiswaController as PengawasMahasiswaController;

// ===== Notifications (semua role) =====
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

/*
|--------------------------------------------------------------------------
| Dashboard & Profile
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Mahasiswa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {

        // Kerja Praktek
        Route::prefix('kerja-praktek')->name('kerja-praktek.')->group(function () {
            Route::get('/',  [MahasiswaKerjaPraktekController::class, 'index'])->name('index');
            Route::post('/', [MahasiswaKerjaPraktekController::class, 'store'])->name('store');
            Route::get('/check', [MahasiswaKerjaPraktekController::class, 'checkLatestKP'])->name('check');

            // Upload laporan & kartu implementasi
            Route::post('{kerjaPraktek}/upload-laporan', [MahasiswaKerjaPraktekController::class, 'uploadLaporan'])->name('upload-laporan');
            // Route::post('{kerjaPraktek}/upload-kartu',   [MahasiswaKerjaPraktekController::class, 'uploadKartu'])->name('upload-kartu'); // Removed kartu implementasi upload

            // Seminar registration
            Route::post('{kerjaPraktek}/daftar-seminar', [MahasiswaKerjaPraktekController::class, 'daftarSeminar'])->name('daftar-seminar');

            // Kuisioner
            Route::get('{kerjaPraktek}/kuisioner',  [MahasiswaKerjaPraktekController::class, 'kuisioner'])->name('kuisioner');
            Route::post('{kerjaPraktek}/kuisioner', [MahasiswaKerjaPraktekController::class, 'storeKuisioner'])->name('store-kuisioner');
        });

        // Bimbingan & Kegiatan
        Route::resource('bimbingan', MahasiswaBimbinganController::class)->except(['edit', 'update', 'destroy']);
        Route::resource('kegiatan',  MahasiswaKegiatanController::class);
    });

/*
|--------------------------------------------------------------------------
| Admin / Dosen
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin_dosen'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Kerja Praktek Management
        Route::prefix('kerja-praktek')->name('kerja-praktek.')->group(function () {
            Route::get('/',                [AdminKerjaPraktekController::class, 'index'])->name('index');
            Route::get('/{kerjaPraktek}',  [AdminKerjaPraktekController::class, 'show'])->name('show');

            Route::post('/{kerjaPraktek}/approve',      [AdminKerjaPraktekController::class, 'approve'])->name('approve');
            Route::post('/{kerjaPraktek}/reject',       [AdminKerjaPraktekController::class, 'reject'])->name('reject');
            Route::post('/{kerjaPraktek}/acc-seminar',  [AdminKerjaPraktekController::class, 'accSeminar'])->name('acc-seminar');
            Route::post('/{kerjaPraktek}/start',        [AdminKerjaPraktekController::class, 'startKP'])->name('start');
            Route::post('/{kerjaPraktek}/input-nilai',  [AdminKerjaPraktekController::class, 'inputNilai'])->name('input-nilai');
            Route::post('/{kerjaPraktek}/send-reminder',[AdminKerjaPraktekController::class, 'sendReminder'])->name('send-reminder');

            // IPK dan ACC kartu implementasi
            Route::post('/{kerjaPraktek}/set-ipk',   [AdminKerjaPraktekController::class, 'setIpk'])->name('set-ipk');
            // Route::post('/{kerjaPraktek}/acc-kartu', [AdminKerjaPraktekController::class, 'accKartu'])->name('acc-kartu'); // Removed kartu implementasi ACC
            Route::post('/{kerjaPraktek}/acc-laporan', [AdminKerjaPraktekController::class, 'accLaporan'])->name('acc-laporan');

            // Seminar registration
            Route::post('/{kerjaPraktek}/acc-pendaftaran-seminar', [AdminKerjaPraktekController::class, 'accPendaftaranSeminar'])->name('acc-pendaftaran-seminar');
            Route::post('/{kerjaPraktek}/tolak-pendaftaran-seminar', [AdminKerjaPraktekController::class, 'tolakPendaftaranSeminar'])->name('tolak-pendaftaran-seminar');
            Route::post('/{kerjaPraktek}/acc-proposal', [AdminKerjaPraktekController::class, 'accProposal'])->name('acc-proposal');
            Route::post('/{kerjaPraktek}/reject-proposal', [AdminKerjaPraktekController::class, 'rejectProposal'])->name('reject-proposal');
        });

        // Bimbingan Management
        Route::prefix('bimbingan')->name('bimbingan.')->group(function () {
            Route::get('/',            [AdminBimbinganController::class, 'index'])->name('index');
            Route::get('/{bimbingan}', [AdminBimbinganController::class, 'show'])->name('show');
            Route::post('/{bimbingan}/verify',   [AdminBimbinganController::class, 'verify'])->name('verify');
            Route::post('/{bimbingan}/feedback', [AdminBimbinganController::class, 'addFeedback'])->name('feedback');
        });

        Route::resource('mahasiswa', AdminMahasiswaController::class)->only(['index', 'show']);
        Route::resource('kegiatan', \App\Http\Controllers\Admin\KegiatanController::class)->only(['index','show']);
        Route::get('kegiatan', [AdminKegiatanController::class, 'index'])->name('kegiatan.index');
    });

/*
|--------------------------------------------------------------------------
| Super Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        // Users
        Route::resource('users', SAUserController::class);
        Route::post('users/{user}/toggle-status', [SAUserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('users/{user}/kerja-praktek/{kerjaPraktek}', [SAUserController::class, 'destroyKP'])->name('users.destroy-kp');

        // Kerja Praktek
        Route::get('kerja-praktek', [SAUserController::class, 'indexKP'])->name('kerja-praktek.index');

        // Tempat Magang
        Route::resource('tempat-magang', SATempatMagangController::class);
        Route::post('tempat-magang/bulk-action',                  [SATempatMagangController::class, 'bulkAction'])->name('tempat-magang.bulk-action');
        Route::post('tempat-magang/{tempatMagang}/toggle-status', [SATempatMagangController::class, 'toggleStatus'])->name('tempat-magang.toggle-status');

        // Laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/',                 [SALaporanController::class, 'index'])->name('index');
            Route::get('/export-kp',        [SALaporanController::class, 'exportKP'])->name('export-kp');
            Route::get('/export-mahasiswa', [SALaporanController::class, 'exportMahasiswa'])->name('export-mahasiswa');
            Route::get('/detail-kp',        [SALaporanController::class, 'detailKP'])->name('detail-kp');
        });

        Route::resource('kegiatan', \App\Http\Controllers\SuperAdmin\KegiatanController::class)->only(['index','show','destroy']);
        Route::get('kegiatan', [SAKegiatanController::class, 'index'])->name('kegiatan.index');
        Route::delete('kegiatan/{kegiatan}', [SAKegiatanController::class, 'destroy'])->name('kegiatan.destroy');
        // Kuisioner (➡️ DIPISAH dari laporan)
        Route::prefix('kuisioner')->name('kuisioner.')->group(function () {
            Route::get('/',           [SAKuisionerController::class, 'index'])->name('index');
            Route::get('/{kuisioner}',[SAKuisionerController::class, 'show'])->name('show');
        });

        Route::resource('kuisioner_questions', \App\Http\Controllers\SuperAdmin\KuisionerQuestionController::class);
    });

/*
|--------------------------------------------------------------------------
| Pengawas Lapangan
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:pengawas_lapangan'])
    ->prefix('pengawas')
    ->name('pengawas.')
    ->group(function () {
        Route::resource('mahasiswa', PengawasMahasiswaController::class)->only(['index', 'show']);
        Route::post('mahasiswa/{kerjaPraktek}/acc-kartu', [PengawasMahasiswaController::class, 'accKartuImplementasi'])->name('mahasiswa.acc-kartu');
        Route::post('mahasiswa/{kerjaPraktek}/feedback',  [PengawasMahasiswaController::class, 'addFeedback'])->name('mahasiswa.feedback');
        Route::post('mahasiswa/{kerjaPraktek}/penilaian-pengawas', [PengawasMahasiswaController::class, 'inputPenilaianPengawas'])->name('mahasiswa.penilaian-pengawas');

        // Kegiatan
        Route::resource('kegiatan', \App\Http\Controllers\PengawasLapangan\KegiatanController::class)->only(['index']);

        // Alihkan menu kuisioner pengawas ke daftar mahasiswa
        Route::get('kuisioner', fn () => redirect()->route('pengawas.mahasiswa.index'))->name('kuisioner.index');
    });

/*
|--------------------------------------------------------------------------
| Notifications (semua role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])
    ->prefix('notifications')
    ->name('notifications.')
    ->group(function () {
        Route::get('/',                        [NotificationController::class, 'index'])->name('index');
        Route::post('/mark-read/{notification}', [NotificationController::class, 'markRead'])->name('mark-read');
        Route::post('/mark-all-read',            [NotificationController::class, 'markAllRead'])->name('mark-all-read');
    });

require __DIR__.'/auth.php';
