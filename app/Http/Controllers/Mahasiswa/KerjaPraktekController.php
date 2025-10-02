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
            'alamat_tempat_sendiri' => ['nullable','required_if:pilihan_tempat,3','string','max:5000'],
            'kontak_tempat_sendiri' => ['nullable','required_if:pilihan_tempat,3','string','max:255'],
            'tanggal_mulai'         => ['nullable','required_if:pilihan_tempat,3','date'],

            // Wajib saat daftar KP
            'ipk_semester' => ['required','numeric','between:0,4.00'],
            'semester_ke'  => ['required','integer','min:1','max:14'],
            'file_krs'     => ['required','file','mimes:pdf','max:5120'],
        ];

        $messages = [
            'tempat_magang_id.exists'           => 'Tempat magang yang dipilih tidak valid.',
            'tempat_magang_sendiri.required_if' => 'Nama perusahaan wajib diisi jika mencari sendiri.',
            'alamat_tempat_sendiri.required_if' => 'Alamat wajib diisi jika mencari sendiri.',
            'kontak_tempat_sendiri.required_if' => 'Kontak wajib diisi jika mencari sendiri.',
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

        $payload = [
            'mahasiswa_id'   => $mahasiswaId,
            'judul_kp'       => $dataValid['judul_kp'],
            'pilihan_tempat' => $choice,
            'status'         => KerjaPraktek::STATUS_PENGAJUAN,
            'ipk_semester'   => (float) $dataValid['ipk_semester'],
            'semester_ke'    => (int) $dataValid['semester_ke'],
            'file_krs'       => $fileKrsPath,
        ];

        if ($choice === 1) {
            // dari prodi
            $payload['tempat_magang_id']       = (int) $dataValid['tempat_magang_id'];
            $payload['tempat_magang_sendiri']  = null;
            $payload['alamat_tempat_sendiri']  = null;
            $payload['kontak_tempat_sendiri']  = null;
            $payload['tanggal_mulai']          = null;
        } else {
            // cari sendiri
            $payload['tempat_magang_id']       = null;
            $payload['tempat_magang_sendiri']  = $dataValid['tempat_magang_sendiri'];
            $payload['alamat_tempat_sendiri']  = $dataValid['alamat_tempat_sendiri'];
            $payload['kontak_tempat_sendiri']  = $dataValid['kontak_tempat_sendiri'];
            $payload['tanggal_mulai']          = $dataValid['tanggal_mulai'];
        }

        $kerjaPraktek = KerjaPraktek::create($payload);

        // Kirim notifikasi ke dosen
        NotificationService::sendToRole(
            'admin_dosen',
            'Pengajuan Kerja Praktek Baru',
            'Mahasiswa ' . auth()->user()->name . ' mengajukan kerja praktek dengan judul: ' . $payload['judul_kp'],
            'info',
            $kerjaPraktek->id,
            route('admin.kerja-praktek.show', $kerjaPraktek->id)
        );

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

        return back()->with('success', 'Laporan berhasil diupload.');
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

        return back()->with('success', 'Kuisioner berhasil disimpan.');
    }

    /** Upload kartu implementasi (setelah ACC seminar) */
    public function uploadKartu(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $this->authorize('update', $kerjaPraktek);

        if (!$kerjaPraktek->acc_seminar) {
            return back()->with('error', 'Anda hanya dapat upload kartu implementasi setelah mendapat ACC seminar.');
        }

        $request->validate([
            'file_kartu_implementasi' => ['required','file','mimes:pdf,jpg,jpeg,png','max:5120'], // 5MB
        ]);

        if ($kerjaPraktek->file_kartu_implementasi) {
            Storage::disk('public')->delete($kerjaPraktek->file_kartu_implementasi);
        }

        $path = $request->file('file_kartu_implementasi')->store('kartu-implementasi', 'public');

        $kerjaPraktek->update([
            'file_kartu_implementasi' => $path,
            'acc_pembimbing_lapangan' => false, // reset ACC lapangan
        ]);

        return back()->with('success', 'Kartu implementasi berhasil diupload. Menunggu ACC dari pembimbing lapangan.');
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
