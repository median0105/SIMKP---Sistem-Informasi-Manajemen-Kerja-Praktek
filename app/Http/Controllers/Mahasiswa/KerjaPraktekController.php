<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\KerjaPraktek;
use App\Models\TempatMagang;
use App\Models\Kuisioner;
use App\Services\NotificationService;
use App\Models\PengawasTempatMagang;
use App\Models\User;

class KerjaPraktekController extends Controller
{
    /**
     * Halaman KP Mahasiswa:
     * - Panel status KP terakhir (jika ada)
     * - Form pengajuan + daftar tempat (aktif & masih ada sisa kuota)
     */
    public function index()
    {
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', auth()->id())
            ->with('tempatMagang')
            ->latest('created_at')
            ->first();

        // tampilkan hanya tempat aktif + hitung terpakai (untuk sisa kuota)
        $tempatMagang = TempatMagang::active()
            ->withCount([
                'kerjaPraktek as terpakai_count' => fn ($q) =>
                    $q->whereIn('status', [
                        KerjaPraktek::STATUS_DISETUJUI,
                        KerjaPraktek::STATUS_SEDANG_KP,
                    ]),
            ])
            ->orderBy('nama_perusahaan')
            ->get();

        return view('mahasiswa.kerja-praktek.index', compact('kerjaPraktek', 'tempatMagang'));
    }

    public function store(Request $request)
    {
        $rules = [
            'judul_kp'       => ['required','string','max:255'],
            'pilihan_tempat' => ['required','integer','in:1,3'], // 1=prodi, 3=mandiri

            // PRODI → hanya valid kalau pilihan_tempat=1
            'tempat_magang_id' => [
                'nullable',
                'exclude_unless:pilihan_tempat,1',
                Rule::exists('tempat_magang', 'id'),
            ],

            // MANDIRI → hanya wajib kalau pilihan_tempat=3
            'tempat_magang_sendiri' => ['nullable','required_if:pilihan_tempat,3','string','max:255'],
            'bidang_usaha_sendiri' => ['nullable','required_if:pilihan_tempat,3','string','max:255'],
            'alamat_tempat_sendiri' => ['nullable','required_if:pilihan_tempat,3','string','max:5000'],
            'email_perusahaan_sendiri' => ['nullable','required_if:pilihan_tempat,3','email','max:255'],
            'telepon_perusahaan_sendiri' => ['nullable','required_if:pilihan_tempat,3','string','max:20'],
            'kontak_tempat_sendiri' => ['nullable','required_if:pilihan_tempat,3','string','max:255'],
            'kuota_mahasiswa_sendiri' => ['nullable','required_if:pilihan_tempat,3','integer','min:1','max:50'],
            'deskripsi_perusahaan_sendiri' => ['nullable','string','max:5000'],
            'tanggal_mulai'         => ['nullable','required_if:pilihan_tempat,3','date'],

            // Wajib saat daftar KP
            'ipk_semester' => ['required','numeric','between:0,4.00'],
            'semester_ke'  => ['required','integer','min:1','max:14'],
            'file_krs'     => ['required','file','mimes:pdf','max:5120'],
            'file_proposal' => ['required','file','mimes:pdf','max:10240'], // 10MB
        ];

        $messages = [
            'tempat_magang_id.exists'           => 'Tempat magang yang dipilih tidak valid.',
            'tempat_magang_sendiri.required_if' => 'Nama perusahaan wajib diisi jika mencari sendiri.',
            'bidang_usaha_sendiri.required_if' => 'Bidang usaha wajib diisi jika mencari sendiri.',
            'alamat_tempat_sendiri.required_if' => 'Alamat wajib diisi jika mencari sendiri.',
            'email_perusahaan_sendiri.required_if' => 'Email perusahaan wajib diisi jika mencari sendiri.',
            'telepon_perusahaan_sendiri.required_if' => 'No. telepon perusahaan wajib diisi jika mencari sendiri.',
            'kontak_tempat_sendiri.required_if' => 'Kontak person wajib diisi jika mencari sendiri.',
            'kuota_mahasiswa_sendiri.required_if' => 'Kuota mahasiswa wajib diisi jika mencari sendiri.',
            'tanggal_mulai.required_if'         => 'Tanggal mulai wajib diisi jika mencari sendiri.',
            'ipk_semester.required'             => 'IPK semester wajib diisi.',
            'semester_ke.required'              => 'Semester ke- wajib diisi.',
        ];

        $dataValid = $request->validate($rules, $messages);

        $mahasiswaId = auth()->id();

        // Batasi pengajuan jika sudah 2x ditolak
        $rejectedCount = KerjaPraktek::where('mahasiswa_id', $mahasiswaId)
            ->where('status', KerjaPraktek::STATUS_DITOLAK)
            ->count();
        if ($rejectedCount >= 2) {
            return back()->withInput()->with('error',
                'Pengajuan KP Anda sudah ditolak 2 kali. Silakan temui dosen pembimbing sebelum mengajukan kembali.');
        }

        // Cegah jika ada KP aktif/pengajuan
        $existing = KerjaPraktek::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', [
                KerjaPraktek::STATUS_PENGAJUAN,
                KerjaPraktek::STATUS_DISETUJUI,
                KerjaPraktek::STATUS_SEDANG_KP,
            ])->first();
        if ($existing) {
            return back()->withInput()->with('error',
                'Anda masih memiliki pengajuan/KP aktif. Selesaikan dulu atau hubungi admin untuk menutupnya.');
        }

        // Check for duplicate title regardless of internship location
        $tempKp = new KerjaPraktek(['judul_kp' => $dataValid['judul_kp']]);
        if ($tempKp->isDuplicateTitle()) {
            $duplicateInfo = $tempKp->getDuplicateInfo();
            $duplicatePercentage = count($duplicateInfo) > 0 ? round((count($duplicateInfo) / KerjaPraktek::count()) * 100, 2) : 0;
            return back()->withInput()->with('error',
                'Judul KP yang Anda ajukan sudah ada atau mirip dengan judul KP yang sudah ada di sistem (' . $duplicatePercentage . '% duplikat dari total judul KP). Silakan gunakan judul yang berbeda.');
        }

        $choice = (int) $dataValid['pilihan_tempat'];

        // Cek sisa kuota saat pilih prodi
        if ($choice === 1) {
            $tm = TempatMagang::active()
                ->withCount([
                    'kerjaPraktek as terpakai_count' => fn ($q) =>
                        $q->whereIn('status', [
                            KerjaPraktek::STATUS_DISETUJUI,
                            KerjaPraktek::STATUS_SEDANG_KP,
                        ]),
                ])
                ->findOrFail($dataValid['tempat_magang_id']);

            $sisa = max(0, (int) $tm->kuota_mahasiswa - (int) $tm->terpakai_count);
            if ($sisa < 1) {
                return back()->withInput()->with('error', 'Kuota tempat magang ini sudah penuh.');
            }
        }

        // Build payload tersanitasi
        $fileKrsPath = $request->file('file_krs')->store('krs', 'public');
        $fileProposalPath = $request->file('file_proposal')->store('proposal-kp', 'public');

        $payload = [
            'mahasiswa_id'   => $mahasiswaId,
            'judul_kp'       => $dataValid['judul_kp'],
            'pilihan_tempat' => $choice,
            'status'         => KerjaPraktek::STATUS_PENGAJUAN,
            'ipk_semester'   => (float) $dataValid['ipk_semester'],
            'semester_ke'    => (int) $dataValid['semester_ke'],
            'file_krs'       => $fileKrsPath,
            'file_proposal'  => $fileProposalPath,
        ];

        if ($choice === 1) {
            // dari prodi
            $payload['tempat_magang_id']       = (int) $dataValid['tempat_magang_id'];
            $payload['tempat_magang_sendiri']  = null;
            $payload['bidang_usaha_sendiri']   = null;
            $payload['alamat_tempat_sendiri']  = null;
            $payload['email_perusahaan_sendiri'] = null;
            $payload['telepon_perusahaan_sendiri'] = null;
            $payload['kontak_tempat_sendiri']  = null;
            $payload['kuota_mahasiswa_sendiri'] = null;
            $payload['deskripsi_perusahaan_sendiri'] = null;
            $payload['tanggal_mulai']          = null;
        } else {
            // cari sendiri
            $payload['tempat_magang_id']       = null;
            $payload['tempat_magang_sendiri']  = $dataValid['tempat_magang_sendiri'];
            $payload['bidang_usaha_sendiri']   = $dataValid['bidang_usaha_sendiri'];
            $payload['alamat_tempat_sendiri']  = $dataValid['alamat_tempat_sendiri'];
            $payload['email_perusahaan_sendiri'] = $dataValid['email_perusahaan_sendiri'];
            $payload['telepon_perusahaan_sendiri'] = $dataValid['telepon_perusahaan_sendiri'];
            $payload['kontak_tempat_sendiri']  = $dataValid['kontak_tempat_sendiri'];
            $payload['kuota_mahasiswa_sendiri'] = $dataValid['kuota_mahasiswa_sendiri'];
            $payload['deskripsi_perusahaan_sendiri'] = $dataValid['deskripsi_perusahaan_sendiri'] ?? null;
            $payload['tanggal_mulai']          = $dataValid['tanggal_mulai'];
        }

        $kerjaPraktek = KerjaPraktek::create($payload);

        // Kirim notifikasi ke superadmin untuk penugasan dosen pembimbing
        $tempatInfo = '';
        if ($choice === 1) {
            $tempatMagang = TempatMagang::find($dataValid['tempat_magang_id']);
            $tempatInfo = 'di ' . $tempatMagang->nama_perusahaan;
        } else {
            $tempatInfo = 'di ' . $dataValid['tempat_magang_sendiri'];
        }

        // Jika pilihan mandiri (pilihan_tempat = 3), kirim notifikasi ke halaman verifikasi instansi
        if ($choice === 3) {
            NotificationService::sendToRole(
                'superadmin',
                'Pengajuan Instansi Mandiri Baru',
                'Mahasiswa ' . auth()->user()->name . ' mengajukan kerja praktek dengan instansi mandiri "' . $dataValid['tempat_magang_sendiri'] . '" dengan judul "' . $payload['judul_kp'] . '". Silakan verifikasi instansi ini.',
                'warning',
                $kerjaPraktek->id,
                route('superadmin.verifikasi-instansi.index')
            );
        } else {
            // Jika pilihan dari prodi, kirim notifikasi ke halaman data KP
            NotificationService::sendToRole(
                'superadmin',
                'Pengajuan Kerja Praktek Baru',
                'Mahasiswa ' . auth()->user()->name . ' mengajukan kerja praktek dengan judul "' . $payload['judul_kp'] . '" ' . $tempatInfo . '. Silakan pilihkan dosen pembimbing untuk mahasiswa ini.',
                'warning',
                $kerjaPraktek->id,
                route('superadmin.kerja-praktek.index')
            );
        }

        // Kirim notifikasi ke dosen pembimbing yang sudah ditugaskan (jika ada)
        $assignedDosen = \App\Models\DosenPembimbing::whereHas('kerjaPraktek', function($q) use ($mahasiswaId) {
            $q->where('mahasiswa_id', $mahasiswaId);
        })->where('jenis_pembimbingan', 'akademik')->pluck('dosen_id');

        if ($assignedDosen->isNotEmpty()) {
            foreach ($assignedDosen as $dosenId) {
                NotificationService::sendToUser(
                    $dosenId,
                    'Pengajuan Kerja Praktek Baru',
                    'Mahasiswa ' . auth()->user()->name . ' mengajukan kerja praktek dengan judul: ' . $payload['judul_kp'],
                    'info',
                    $kerjaPraktek->id,
                    route('admin.kerja-praktek.show', $kerjaPraktek->id)
                );
            }
        }

        return redirect()->route('mahasiswa.kerja-praktek.index')
            ->with('success', 'Pengajuan kerja praktek berhasil disubmit.');
    }

    /** Upload laporan KP (PDF) */
    public function uploadLaporan(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $this->authorize('update', $kerjaPraktek);

        $request->validate([
            'file_laporan' => ['required','file','mimes:pdf','max:10240'], // 10MB
        ]);

        if ($kerjaPraktek->file_laporan) {
            Storage::disk('public')->delete($kerjaPraktek->file_laporan);
        }

        $path = $request->file('file_laporan')->store('laporan-kp', 'public');

        $kerjaPraktek->update([
            'file_laporan' => $path,
        ]);

        // Kirim notifikasi ke dosen pembimbing yang sudah ditugaskan
        $assignedDosen = \App\Models\DosenPembimbing::whereHas('kerjaPraktek', function($q) use ($kerjaPraktek) {
            $q->where('mahasiswa_id', $kerjaPraktek->mahasiswa_id);
        })->where('jenis_pembimbingan', 'akademik')->pluck('dosen_id');

        if ($assignedDosen->isNotEmpty()) {
            foreach ($assignedDosen as $dosenId) {
                NotificationService::sendToUser(
                    $dosenId,
                    'Laporan KP Diunggah',
                    'Mahasiswa ' . auth()->user()->name . ' telah mengunggah laporan kerja praktek dengan judul: ' . $kerjaPraktek->judul_kp,
                    'info',
                    $kerjaPraktek->id,
                    route('admin.kerja-praktek.show', $kerjaPraktek->id)
                );
            }
        }

        return back()->with('success', 'Laporan berhasil diupload.');
    }

    /** Daftar Seminar */
    public function daftarSeminar(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $this->authorize('update', $kerjaPraktek);

        if (!$kerjaPraktek->acc_pembimbing_laporan) {
            return back()->with('error', 'Laporan KP Anda belum di-ACC oleh pembimbing.');
        }

        if ($kerjaPraktek->pendaftaran_seminar) {
            return back()->with('error', 'Anda sudah terdaftar untuk seminar.');
        }

        $kerjaPraktek->update([
            'pendaftaran_seminar' => true,
            'tanggal_daftar_seminar' => now(),
        ]);

        // Kirim notifikasi ke dosen pembimbing yang sudah ditugaskan
        $assignedDosen = \App\Models\DosenPembimbing::whereHas('kerjaPraktek', function($q) use ($kerjaPraktek) {
            $q->where('mahasiswa_id', $kerjaPraktek->mahasiswa_id);
        })->where('jenis_pembimbingan', 'akademik')->pluck('dosen_id');

        if ($assignedDosen->isNotEmpty()) {
            foreach ($assignedDosen as $dosenId) {
                NotificationService::sendToUser(
                    $dosenId,
                    'Pendaftaran Seminar KP',
                    'Mahasiswa ' . auth()->user()->name . ' telah mendaftar seminar kerja praktek dengan judul: ' . $kerjaPraktek->judul_kp,
                    'info',
                    $kerjaPraktek->id,
                    route('admin.kerja-praktek.show', $kerjaPraktek->id)
                );
            }
        }

        // Kirim notifikasi ke dosen penguji yang sudah ditugaskan
        $assignedDosenPenguji = \App\Models\DosenPenguji::whereHas('kerjaPraktek', function($q) use ($kerjaPraktek) {
            $q->where('mahasiswa_id', $kerjaPraktek->mahasiswa_id);
        })->where('is_active', true)->pluck('dosen_id');

        if ($assignedDosenPenguji->isNotEmpty()) {
            foreach ($assignedDosenPenguji as $dosenId) {
                NotificationService::sendToUser(
                    $dosenId,
                    'Pendaftaran Seminar KP',
                    'Mahasiswa dengan NPM ' . auth()->user()->npm . ' (' . auth()->user()->name . ') telah mendaftar seminar dengan judul: "' . $kerjaPraktek->judul_kp . '". Silakan ACC pendaftarannya.',
                    'warning',
                    $kerjaPraktek->id,
                    route('admin.seminar.index')
                );
            }
        }

        return back()->with('success', 'Pendaftaran seminar berhasil. Menunggu jadwal dari dosen Penguji.');
    }

    /** Halaman kuisioner */
    public function kuisioner(KerjaPraktek $kerjaPraktek)
    {
        $this->authorize('view', $kerjaPraktek);

        // Pastikan KP sudah selesai
        if ($kerjaPraktek->status !== 'selesai') {
            return back()->with('error', 'Kuisioner hanya dapat diisi setelah KP selesai.');
        }

        $kuisioner = $kerjaPraktek->kuisioner; // hasOne

        // Ambil pertanyaan aktif
        $questions = \App\Models\KuisionerQuestion::where('is_active', true)->orderBy('order')->get();

        return view('mahasiswa.kerja-praktek.kuisioner', compact('kerjaPraktek', 'kuisioner', 'questions'));
    }

    /** Simpan kuisioner */
    public function storeKuisioner(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $this->authorize('update', $kerjaPraktek);

        $rules = [
            'rating_tempat_magang' => ['required','integer','min:1','max:5'],
            'rating_bimbingan'     => ['required','integer','min:1','max:5'],
            'rating_sistem'        => ['required','integer','min:1','max:5'],
            'saran_perbaikan'      => ['nullable','string'],
            'kesan_pesan'          => ['nullable','string'],
            'rekomendasi_tempat'   => ['required','boolean'],
        ];

        // Validasi jawaban dinamis
        $questions = \App\Models\KuisionerQuestion::where('is_active', true)->get();
        foreach ($questions as $question) {
            $key = 'dynamic_answers.' . $question->id;
            if ($question->type === 'rating') {
                $rules[$key] = ['required','integer','min:1','max:5'];
            } elseif ($question->type === 'text') {
                $rules[$key] = ['nullable','string'];
            } elseif ($question->type === 'yes_no') {
                $rules[$key] = ['required','boolean'];
            }
        }

        $validated = $request->validate($rules);

        $data = $request->only([
            'rating_tempat_magang',
            'rating_bimbingan',
            'rating_sistem',
            'saran_perbaikan',
            'kesan_pesan',
            'rekomendasi_tempat',
        ]);

        $data['dynamic_answers'] = $validated['dynamic_answers'] ?? [];

        Kuisioner::updateOrCreate(
            ['kerja_praktek_id' => $kerjaPraktek->id],
            $data
        );

        // Kirim notifikasi ke superadmin bahwa mahasiswa telah mengisi kuisioner
        NotificationService::sendToRole(
            'superadmin',
            'Kuisioner KP Baru',
            'Mahasiswa ' . auth()->user()->name . ' (' . auth()->user()->npm . ') telah mengisi kuisioner untuk KP: ' . $kerjaPraktek->judul_kp,
            'info',
            $kerjaPraktek->id,
            route('superadmin.kuisioner.show', $kerjaPraktek->kuisioner->id ?? null)
        );

        return back()->with('success', 'Kuisioner berhasil disimpan.');
    }
    public function edit(KerjaPraktek $kerjaPraktek)
    {
        $this->authorize('update', $kerjaPraktek);

        // Pastikan hanya KP yang ditolak yang bisa diedit
        if ($kerjaPraktek->status !== KerjaPraktek::STATUS_DITOLAK) {
            return back()->with('error', 'Hanya KP yang ditolak yang dapat diedit.');
        }

        // tampilkan hanya tempat aktif + hitung terpakai (untuk sisa kuota)
        $tempatMagang = TempatMagang::active()
            ->withCount([
                'kerjaPraktek as terpakai_count' => fn ($q) =>
                    $q->whereIn('status', [
                        KerjaPraktek::STATUS_DISETUJUI,
                        KerjaPraktek::STATUS_SEDANG_KP,
                    ]),
            ])
            ->orderBy('nama_perusahaan')
            ->get();

        return view('mahasiswa.kerja-praktek.edit', compact('kerjaPraktek', 'tempatMagang'));
    }

    /** Update KP yang ditolak */
    public function update(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $this->authorize('update', $kerjaPraktek);

        // Pastikan hanya KP yang ditolak yang bisa diupdate
        if ($kerjaPraktek->status !== KerjaPraktek::STATUS_DITOLAK) {
            return back()->with('error', 'Hanya KP yang ditolak yang dapat diupdate.');
        }

        $rules = [
            'judul_kp'       => ['required','string','max:255'],
            'pilihan_tempat' => ['required','integer','in:1,3'], // 1=prodi, 3=mandiri

            // PRODI → hanya valid kalau pilihan_tempat=1
            'tempat_magang_id' => [
                'nullable',
                'exclude_unless:pilihan_tempat,1',
                Rule::exists('tempat_magang', 'id'),
            ],

            // MANDIRI → hanya wajib kalau pilihan_tempat=3
            'tempat_magang_sendiri' => ['nullable','required_if:pilihan_tempat,3','string','max:255'],
            'bidang_usaha_sendiri' => ['nullable','required_if:pilihan_tempat,3','string','max:255'],
            'alamat_tempat_sendiri' => ['nullable','required_if:pilihan_tempat,3','string','max:5000'],
            'email_perusahaan_sendiri' => ['nullable','email','max:255'],
            'telepon_perusahaan_sendiri' => ['nullable','string','max:20'],
            'kontak_tempat_sendiri' => ['nullable','required_if:pilihan_tempat,3','string','max:255'],
            'kuota_mahasiswa_sendiri' => ['nullable','required_if:pilihan_tempat,3','integer','min:1','max:50'],
            'deskripsi_perusahaan_sendiri' => ['nullable','string','max:5000'],
            'tanggal_mulai'         => ['nullable','required_if:pilihan_tempat,3','date'],

            // Wajib saat update
            'file_proposal' => ['required','file','mimes:pdf','max:10240'], // 10MB
        ];

        $messages = [
            'tempat_magang_id.exists'           => 'Tempat magang yang dipilih tidak valid.',
            'tempat_magang_sendiri.required_if' => 'Nama perusahaan wajib diisi jika mencari sendiri.',
            'bidang_usaha_sendiri.required_if' => 'Bidang usaha wajib diisi jika mencari sendiri.',
            'alamat_tempat_sendiri.required_if' => 'Alamat wajib diisi jika mencari sendiri.',
            'email_perusahaan_sendiri.required_if' => 'Email perusahaan wajib diisi jika mencari sendiri.',
            'telepon_perusahaan_sendiri.required_if' => 'No. telepon perusahaan wajib diisi jika mencari sendiri.',
            'kontak_tempat_sendiri.required_if' => 'Kontak person wajib diisi jika mencari sendiri.',
            'kuota_mahasiswa_sendiri.required_if' => 'Kuota mahasiswa wajib diisi jika mencari sendiri.',
            'tanggal_mulai.required_if'         => 'Tanggal mulai wajib diisi jika mencari sendiri.',
            'file_proposal.required'            => 'File proposal wajib diupload.',
        ];

        $dataValid = $request->validate($rules, $messages);

        // Check for duplicate title regardless of internship location
        $tempKp = new KerjaPraktek(['judul_kp' => $dataValid['judul_kp']]);
        if ($tempKp->isDuplicateTitle()) {
            $duplicateInfo = $tempKp->getDuplicateInfo();
            $duplicatePercentage = count($duplicateInfo) > 0 ? round((count($duplicateInfo) / KerjaPraktek::count()) * 100, 2) : 0;
            return back()->withInput()->with('error',
                'Judul KP yang Anda ajukan sudah ada atau mirip dengan judul KP yang sudah ada di sistem (' . $duplicatePercentage . '% duplikat dari total judul KP). Silakan gunakan judul yang berbeda.');
        }

        $choice = (int) $dataValid['pilihan_tempat'];

        // Cek sisa kuota saat pilih prodi
        if ($choice === 1) {
            $tm = TempatMagang::active()
                ->withCount([
                    'kerjaPraktek as terpakai_count' => fn ($q) =>
                        $q->whereIn('status', [
                            KerjaPraktek::STATUS_DISETUJUI,
                            KerjaPraktek::STATUS_SEDANG_KP,
                        ]),
                ])
                ->findOrFail($dataValid['tempat_magang_id']);

            $sisa = max(0, (int) $tm->kuota_mahasiswa - (int) $tm->terpakai_count);
            if ($sisa < 1) {
                return back()->withInput()->with('error', 'Kuota tempat magang ini sudah penuh.');
            }
        }

        // Hapus file proposal lama jika ada
        if ($kerjaPraktek->file_proposal) {
            Storage::disk('public')->delete($kerjaPraktek->file_proposal);
        }

        // Upload file proposal baru
        $fileProposalPath = $request->file('file_proposal')->store('proposal-kp', 'public');

        // Build payload update
        $payload = [
            'judul_kp'       => $dataValid['judul_kp'],
            'pilihan_tempat' => $choice,
            'status'         => KerjaPraktek::STATUS_PENGAJUAN, // Reset status ke pengajuan
            'file_proposal'  => $fileProposalPath,
        ];

        if ($choice === 1) {
            // dari prodi
            $payload['tempat_magang_id']       = (int) $dataValid['tempat_magang_id'];
            $payload['tempat_magang_sendiri']  = null;
            $payload['bidang_usaha_sendiri']   = null;
            $payload['alamat_tempat_sendiri']  = null;
            $payload['email_perusahaan_sendiri'] = null;
            $payload['telepon_perusahaan_sendiri'] = null;
            $payload['kontak_tempat_sendiri']  = null;
            $payload['kuota_mahasiswa_sendiri'] = null;
            $payload['deskripsi_perusahaan_sendiri'] = null;
            $payload['tanggal_mulai']          = null;
        } else {
            // cari sendiri
            $payload['tempat_magang_id']       = null;
            $payload['tempat_magang_sendiri']  = $dataValid['tempat_magang_sendiri'];
            $payload['bidang_usaha_sendiri']   = $dataValid['bidang_usaha_sendiri'];
            $payload['alamat_tempat_sendiri']  = $dataValid['alamat_tempat_sendiri'];
            $payload['email_perusahaan_sendiri'] = $dataValid['email_perusahaan_sendiri'];
            $payload['telepon_perusahaan_sendiri'] = $dataValid['telepon_perusahaan_sendiri'];
            $payload['kontak_tempat_sendiri']  = $dataValid['kontak_tempat_sendiri'];
            $payload['kuota_mahasiswa_sendiri'] = $dataValid['kuota_mahasiswa_sendiri'];
            $payload['deskripsi_perusahaan_sendiri'] = $dataValid['deskripsi_perusahaan_sendiri'] ?? null;
            $payload['tanggal_mulai']          = $dataValid['tanggal_mulai'];
        }

        $kerjaPraktek->update($payload);

        // Kirim notifikasi ke superadmin untuk penugasan dosen pembimbing
        $tempatInfo = '';
        if ($choice === 1) {
            $tempatMagang = TempatMagang::find($dataValid['tempat_magang_id']);
            $tempatInfo = 'di ' . $tempatMagang->nama_perusahaan;
        } else {
            $tempatInfo = 'di ' . $dataValid['tempat_magang_sendiri'];
        }

        // Jika pilihan mandiri (pilihan_tempat = 3), kirim notifikasi ke halaman verifikasi instansi
        if ($choice === 3) {
            NotificationService::sendToRole(
                'superadmin',
                'Pengajuan Instansi Mandiri Diperbarui',
                'Mahasiswa ' . auth()->user()->name . ' telah memperbarui pengajuan kerja praktek dengan instansi mandiri "' . $dataValid['tempat_magang_sendiri'] . '" dengan judul "' . $payload['judul_kp'] . '". Silakan verifikasi instansi ini.',
                'warning',
                $kerjaPraktek->id,
                route('superadmin.verifikasi-instansi.index')
            );
        } else {
            // Jika pilihan dari prodi, kirim notifikasi ke halaman data KP
            NotificationService::sendToRole(
                'superadmin',
                'Pengajuan Kerja Praktek Diperbarui',
                'Mahasiswa ' . auth()->user()->name . ' telah memperbarui pengajuan kerja praktek dengan judul "' . $payload['judul_kp'] . '" ' . $tempatInfo . '',
                'warning',
                $kerjaPraktek->id,
                route('superadmin.kerja-praktek.index')
            );
        }

        // Kirim notifikasi ke dosen pembimbing yang sudah ditugaskan (jika ada)
        $assignedDosen = \App\Models\DosenPembimbing::whereHas('kerjaPraktek', function($q) use ($kerjaPraktek) {
            $q->where('mahasiswa_id', $kerjaPraktek->mahasiswa_id);
        })->where('jenis_pembimbingan', 'akademik')->pluck('dosen_id');

        if ($assignedDosen->isNotEmpty()) {
            foreach ($assignedDosen as $dosenId) {
                NotificationService::sendToUser(
                    $dosenId,
                    'Pengajuan Kerja Praktek Diperbarui',
                    'Mahasiswa ' . auth()->user()->name . ' telah memperbarui pengajuan kerja praktek dengan judul: ' . $payload['judul_kp'],
                    'info',
                    $kerjaPraktek->id,
                    route('admin.kerja-praktek.show', $kerjaPraktek->id)
                );
            }
        }

        return redirect()->route('mahasiswa.kerja-praktek.index')
            ->with('success', 'Pengajuan kerja praktek berhasil diperbarui.');
    }

    /** Upload Revisi Laporan */
    public function uploadRevisi(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $this->authorize('update', $kerjaPraktek);

        if (!$kerjaPraktek->acc_seminar || !$kerjaPraktek->rata_rata_seminar) {
            return back()->with('error', 'Anda hanya dapat upload revisi setelah seminar di-ACC dan nilai seminar sudah diinput.');
        }

        $request->validate([
            'file_revisi' => ['required','file','mimes:pdf','max:10240'], // 10MB
        ]);

        if ($kerjaPraktek->file_revisi) {
            Storage::disk('public')->delete($kerjaPraktek->file_revisi);
        }

        $path = $request->file('file_revisi')->store('revisi-laporan', 'public');

        $kerjaPraktek->update([
            'file_revisi' => $path,
        ]);

        // Kirim notifikasi ke dosen pembimbing yang sudah ditugaskan
        $assignedDosen = \App\Models\DosenPembimbing::whereHas('kerjaPraktek', function($q) use ($kerjaPraktek) {
            $q->where('mahasiswa_id', $kerjaPraktek->mahasiswa_id);
        })->where('jenis_pembimbingan', 'akademik')->pluck('dosen_id');

        if ($assignedDosen->isNotEmpty()) {
            foreach ($assignedDosen as $dosenId) {
                NotificationService::sendToUser(
                    $dosenId,
                    'Revisi Laporan KP Diunggah',
                    'Mahasiswa ' . auth()->user()->name . ' telah mengunggah revisi laporan kerja praktek dengan judul: ' . $kerjaPraktek->judul_kp,
                    'info',
                    $kerjaPraktek->id,
                    route('admin.kerja-praktek.show', $kerjaPraktek->id)
                );
            }
        }

        // Kirim notifikasi ke dosen penguji yang sudah ditugaskan
        $assignedPenguji = \App\Models\DosenPenguji::whereHas('kerjaPraktek', function($q) use ($kerjaPraktek) {
            $q->where('mahasiswa_id', $kerjaPraktek->mahasiswa_id);
        })->pluck('dosen_id');

        if ($assignedPenguji->isNotEmpty()) {
            foreach ($assignedPenguji as $dosenId) {
                NotificationService::sendToUser(
                    $dosenId,
                    'Revisi Laporan KP Diunggah',
                    'Mahasiswa ' . auth()->user()->name . ' telah mengunggah revisi laporan kerja praktek dengan judul: ' . $kerjaPraktek->judul_kp,
                    'info',
                    $kerjaPraktek->id,
                    route('admin.seminar.show', $kerjaPraktek->id)
                );
            }
        }

        return back()->with('success', 'Revisi laporan berhasil diupload.');
    }

    /** Endpoint untuk cek KP terbaru (untuk polling auto-refresh) */
    public function checkLatestKP()
    {
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', auth()->id())
            ->latest('created_at')
            ->first();

        return response()->json([
            'has_kp' => $kerjaPraktek ? true : false,
            'status' => $kerjaPraktek ? $kerjaPraktek->status : null,
        ]);
    }

}
