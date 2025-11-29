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
use App\Http\Controllers\WelcomeController;

Route::get('/', [WelcomeController::class, 'index']);

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
Route::middleware(['auth', 'verified', 'role:mahasiswa', 'periode'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {

        // Kerja Praktek
        Route::prefix('kerja-praktek')->name('kerja-praktek.')->group(function () {
            Route::get('/',  [MahasiswaKerjaPraktekController::class, 'index'])->name('index');
            Route::post('/', [MahasiswaKerjaPraktekController::class, 'store'])->name('store');
            Route::get('/check', [MahasiswaKerjaPraktekController::class, 'checkLatestKP'])->name('check');

            // Edit dan update KP ditolak
            Route::get('{kerjaPraktek}/edit', [MahasiswaKerjaPraktekController::class, 'edit'])->name('edit');
            Route::put('{kerjaPraktek}', [MahasiswaKerjaPraktekController::class, 'update'])->name('update');

            // Upload laporan & kartu implementasi
            Route::post('{kerjaPraktek}/upload-laporan', [MahasiswaKerjaPraktekController::class, 'uploadLaporan'])->name('upload-laporan');
            // Route::post('{kerjaPraktek}/upload-kartu',   [MahasiswaKerjaPraktekController::class, 'uploadKartu'])->name('upload-kartu'); // Removed kartu implementasi upload

            // Upload revisi
            Route::post('{kerjaPraktek}/upload-revisi', [MahasiswaKerjaPraktekController::class, 'uploadRevisi'])->name('upload-revisi');

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
Route::middleware(['auth', 'verified', 'role:admin_dosen','periode'])
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
            Route::get('/create',      [AdminBimbinganController::class, 'create'])->name('create');
            Route::post('/',           [AdminBimbinganController::class, 'store'])->name('store');
            Route::get('/show',        [AdminBimbinganController::class, 'show'])->name('show');
            Route::post('/{bimbingan}/verify',   [AdminBimbinganController::class, 'verify'])->name('verify');
            Route::post('/{bimbingan}/feedback', [AdminBimbinganController::class, 'addFeedback'])->name('feedback');
            Route::post('/verify-all/{mahasiswa}', [AdminBimbinganController::class, 'verifyAll'])->name('verify-all');
        });

        Route::resource('mahasiswa', AdminMahasiswaController::class)->only(['index', 'show']);
        Route::resource('kegiatan', \App\Http\Controllers\Admin\KegiatanController::class)->only(['index','show']);
        Route::get('kegiatan', [AdminKegiatanController::class, 'index'])->name('kegiatan.index');
        Route::prefix('seminar')->name('seminar.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SeminarController::class, 'index'])->name('index');
            Route::get('/{kerjaPraktek}', [App\Http\Controllers\Admin\SeminarController::class, 'show'])->name('show');
            Route::post('/{kerjaPraktek}/acc-pendaftaran-seminar', [App\Http\Controllers\Admin\SeminarController::class, 'accPendaftaranSeminar'])->name('acc-pendaftaran-seminar');
            Route::post('/{kerjaPraktek}/tolak-pendaftaran-seminar', [App\Http\Controllers\Admin\SeminarController::class, 'tolakPendaftaranSeminar'])->name('tolak-pendaftaran-seminar');
            Route::post('/{kerjaPraktek}/acc-seminar', [App\Http\Controllers\Admin\SeminarController::class, 'accSeminar'])->name('acc-seminar');
            Route::post('/{kerjaPraktek}/input-nilai', [App\Http\Controllers\Admin\SeminarController::class, 'inputNilai'])->name('input-nilai');
        });
    });

/*
|--------------------------------------------------------------------------
| Super Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        // Users
        Route::resource('users', SAUserController::class);
        Route::post('users/{user}/toggle-status', [SAUserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('users/{user}/kerja-praktek/{kerjaPraktek}', [SAUserController::class, 'destroyKP'])->name('users.destroy-kp');

        // Dosen Pembimbing
        Route::get('dosen-pembimbing', [SAUserController::class, 'indexDosenPembimbing'])->name('dosen-pembimbing.index');
        Route::get('dosen-pembimbing/{user}', [SAUserController::class, 'showDosenPembimbing'])->name('dosen-pembimbing.show');
        Route::post('dosen-pembimbing/{user}/assign-mahasiswa', [SAUserController::class, 'assignMahasiswaToDosen'])->name('dosen-pembimbing.assign-mahasiswa');

        // Dosen Penguji
        Route::get('dosen-penguji', [SAUserController::class, 'indexDosenPenguji'])->name('dosen-penguji.index');
        Route::get('dosen-penguji/{user}', [SAUserController::class, 'showDosenPenguji'])->name('dosen-penguji.show');
        Route::post('dosen-penguji/{user}/assign-mahasiswa', [SAUserController::class, 'assignMahasiswaToDosenPenguji'])->name('dosen-penguji.assign-mahasiswa');

        // Kerja Praktek
        Route::get('kerja-praktek', [SAUserController::class, 'indexKP'])->name('kerja-praktek.index');
        Route::get('kerja-praktek/{kerjaPraktek}/edit', [SAUserController::class, 'editKP'])->name('kerja-praktek.edit');
        Route::put('kerja-praktek/{kerjaPraktek}', [SAUserController::class, 'updateKP'])->name('kerja-praktek.update');
        Route::post('kerja-praktek/{kerjaPraktek}/assign-dosen-pembimbing', [SAUserController::class, 'assignDosenPembimbing'])->name('kerja-praktek.assign-dosen-pembimbing');
        Route::post('kerja-praktek/{kerjaPraktek}/assign-dosen-penguji', [SAUserController::class, 'assignDosenPenguji'])->name('kerja-praktek.assign-dosen-penguji');

        // Verifikasi Instansi Mandiri
        Route::get('verifikasi-instansi', [SAUserController::class, 'indexVerifikasiInstansi'])->name('verifikasi-instansi.index');
        Route::patch('verifikasi-instansi/{kerjaPraktek}/approve', [SAUserController::class, 'approveInstansi'])->name('verifikasi-instansi.approve');
        Route::patch('verifikasi-instansi/{kerjaPraktek}/reject', [SAUserController::class, 'rejectInstansi'])->name('verifikasi-instansi.reject');

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
        Route::resource('kuisioner_pengawas_questions', \App\Http\Controllers\SuperAdmin\KuisionerPengawasQuestionController::class);

        // Periode
        Route::resource('periodes', \App\Http\Controllers\SuperAdmin\PeriodeController::class);

        // Sertifikat Pengawas
        Route::resource('sertifikat-pengawas', \App\Http\Controllers\SuperAdmin\SertifikatPengawasController::class);
        Route::post('sertifikat-pengawas/{sertifikatPengawa}/generate', [\App\Http\Controllers\SuperAdmin\SertifikatPengawasController::class, 'generate'])->name('sertifikat-pengawas.generate');
        Route::post('sertifikat-pengawas/generate-all', [\App\Http\Controllers\SuperAdmin\SertifikatPengawasController::class, 'generateAll'])->name('sertifikat-pengawas.generate-all');
        Route::get('sertifikat-pengawas/{sertifikatPengawa}/download', [\App\Http\Controllers\SuperAdmin\SertifikatPengawasController::class, 'download'])->name('sertifikat-pengawas.download');
    });

/*
|--------------------------------------------------------------------------
| Pengawas Lapangan
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:pengawas_lapangan','periode'])
    ->prefix('pengawas')
    ->name('pengawas.')
    ->group(function () {
        Route::resource('mahasiswa', PengawasMahasiswaController::class)->only(['index', 'show']);
        Route::post('mahasiswa/{kerjaPraktek}/acc-kartu', [PengawasMahasiswaController::class, 'accKartuImplementasi'])->name('mahasiswa.acc-kartu');
        Route::post('mahasiswa/{kerjaPraktek}/feedback',  [PengawasMahasiswaController::class, 'addFeedback'])->name('mahasiswa.feedback');
        Route::post('mahasiswa/{kerjaPraktek}/penilaian-pengawas', [PengawasMahasiswaController::class, 'inputPenilaianPengawas'])->name('mahasiswa.penilaian-pengawas');

        // Kegiatan
        Route::resource('kegiatan', \App\Http\Controllers\PengawasLapangan\KegiatanController::class)->only(['index']);

        // Sertifikat
        Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PengawasLapangan\SertifikatController::class, 'index'])->name('index');
            Route::get('/{sertifikat}/download', [\App\Http\Controllers\PengawasLapangan\SertifikatController::class, 'download'])->name('download');
        });

        // Kuisioner
        Route::prefix('kuisioner')->name('kuisioner.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PengawasLapangan\KuisionerController::class, 'index'])->name('index');
            Route::get('/{kerjaPraktek}', [\App\Http\Controllers\PengawasLapangan\KuisionerController::class, 'show'])->name('show');
            Route::get('/{kerjaPraktek}/feedback', [\App\Http\Controllers\PengawasLapangan\KuisionerController::class, 'createFeedback'])->name('feedback');
            Route::post('/{kerjaPraktek}/feedback', [\App\Http\Controllers\PengawasLapangan\KuisionerController::class, 'storeFeedback'])->name('store-feedback');
            Route::get('/analytics', [\App\Http\Controllers\PengawasLapangan\KuisionerController::class, 'analytics'])->name('analytics');
        });

        // Kuisioner Pengawas
        Route::resource('kuisioner-pengawas', \App\Http\Controllers\PengawasLapangan\KuisionerPengawasController::class)->only(['index', 'store']);
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
