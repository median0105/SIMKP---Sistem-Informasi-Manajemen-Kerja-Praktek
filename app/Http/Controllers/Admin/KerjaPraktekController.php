<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KerjaPraktek;
use App\Models\DosenPembimbing;
use App\Models\Notifikasi;

class KerjaPraktekController extends Controller
{
    public function index(Request $request)
    {
        $query = KerjaPraktek::with(['mahasiswa', 'tempatMagang', 'dosenAkademik', 'dosenPembimbing.dosen'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Jika "Semua Status", tampilkan hanya KP yang dibimbing oleh dosen yang sedang login
            $query->whereHas('dosenPembimbing', function($q) {
                $q->where('dosen_id', auth()->id())
                ->where('jenis_pembimbingan', 'akademik');
            });
        }

        // Filter untuk hanya menampilkan KP yang instansi_verified = true atau pilihan_tempat != 3
        $query->where(function($q) {
            $q->where('instansi_verified', true)
              ->orWhere('pilihan_tempat', '!=', 3);
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_kp', 'like', "%{$search}%")
                ->orWhereHas('mahasiswa', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('npm', 'like', "%{$search}%");
                });
            });
        }

        $kerjaPraktek = $query->paginate(15);

        $duplicateTitles = KerjaPraktek::select('judul_kp')
            ->groupBy('judul_kp')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('judul_kp');

        return view('admin.kerja-praktek.index', compact('kerjaPraktek', 'duplicateTitles'));
    }

    public function show(KerjaPraktek $kerjaPraktek)
    {
        $kerjaPraktek->load([
            'mahasiswa', 'tempatMagang', 'bimbingan', 'kegiatan',
            'dosenPembimbing', 'kuisioner'
        ]);

        $isDuplicate = $kerjaPraktek->isDuplicateTitle();
        $needsResponsi = $kerjaPraktek->perluResponsi();

        return view('admin.kerja-praktek.show', compact('kerjaPraktek', 'isDuplicate', 'needsResponsi'));
    }

    public function approve(KerjaPraktek $kerjaPraktek)
    {
        if ($kerjaPraktek->isDuplicateTitle()) {
            return back()->with('error', 'Judul KP sudah pernah digunakan sebelumnya. Mohon gunakan judul yang berbeda.');
        }

        $kerjaPraktek->update([
            'status' => KerjaPraktek::STATUS_SEDANG_KP,
        ]);

        // tetapkan pembimbing akademik = dosen yang approve
        DosenPembimbing::updateOrCreate(
            ['kerja_praktek_id' => $kerjaPraktek->id, 'jenis_pembimbingan' => 'akademik'],
            ['dosen_id' => auth()->id()]
        );

        Notifikasi::create([
            'user_id' => $kerjaPraktek->mahasiswa_id,
            'title' => 'Pengajuan KP Disetujui',
            'message' => "Pengajuan KP Anda dengan judul '{$kerjaPraktek->judul_kp}' telah disetujui.",
            'type' => 'success',
            'kerja_praktek_id' => $kerjaPraktek->id,
            'action_url' => route('mahasiswa.kerja-praktek.index')
        ]);

        return back()->with('success', 'Pengajuan KP berhasil disetujui.');
    }

    public function reject(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $request->validate(['catatan_dosen' => 'required|string']);

        // Ubah status menjadi ditolak
        $kerjaPraktek->update([
            'status' => KerjaPraktek::STATUS_DITOLAK,
            'catatan_dosen' => $request->catatan_dosen
        ]);

        // Notifikasi penolakan standar
        Notifikasi::create([
            'user_id' => $kerjaPraktek->mahasiswa_id,
            'title' => 'Pengajuan KP Ditolak',
            'message' => "Pengajuan KP Anda ditolak. Alasan: {$request->catatan_dosen}",
            'type' => 'error',
            'kerja_praktek_id' => $kerjaPraktek->id,
            'action_url' => route('mahasiswa.kerja-praktek.index')
        ]);

        // Kirim notifikasi ke superadmin
        \App\Services\NotificationService::sendToRole('superadmin', 'Pengajuan KP Ditolak', "Pengajuan KP mahasiswa {$kerjaPraktek->mahasiswa->name} ({$kerjaPraktek->mahasiswa->npm}) dengan judul '{$kerjaPraktek->judul_kp}' telah ditolak oleh dosen pembimbing.", 'warning', $kerjaPraktek->getKey(), route('superadmin.kerja-praktek.index'));

        // ✅ Jika ini penolakan ke-2, kirim notifikasi khusus untuk menemui dosen pembimbing
        $rejectedCount = KerjaPraktek::where('mahasiswa_id', $kerjaPraktek->mahasiswa_id)
            ->where('status', KerjaPraktek::STATUS_DITOLAK)
            ->count();

        if ($rejectedCount >= 2) {
            Notifikasi::create([
                'user_id' => $kerjaPraktek->mahasiswa_id,
                'title' => 'Mohon Temui Dosen Pembimbing',
                'message' => 'Pengajuan KP Anda telah ditolak sebanyak 2 kali. Silakan segera menemui dosen pembimbing untuk konsultasi sebelum pengajuan berikutnya.',
                'type' => 'warning',
                'kerja_praktek_id' => $kerjaPraktek->id,
                'action_url' => route('mahasiswa.kerja-praktek.index'),
            ]);
        }

        return back()->with('success', 'Pengajuan KP ditolak dengan alasan yang diberikan.');
    }

    public function accSeminar(KerjaPraktek $kerjaPraktek)
    {
        $kerjaPraktek->update([
            'acc_seminar' => true,
            'tanggal_seminar' => now()->addDays(7)
        ]);

        Notifikasi::create([
            'user_id' => $kerjaPraktek->mahasiswa_id,
            'title' => 'Seminar KP Disetujui',
            'message' => "Anda sudah dapat mengikuti seminar KP. Tanggal seminar: ".$kerjaPraktek->tanggal_seminar->format('d F Y'),
            'type' => 'success',
            'kerja_praktek_id' => $kerjaPraktek->id
        ]);

        return back()->with('success', 'Mahasiswa berhasil di-ACC untuk seminar KP.');
    }

    public function startKP(KerjaPraktek $kerjaPraktek)
    {
        $kerjaPraktek->update([
            'status' => KerjaPraktek::STATUS_SEDANG_KP,
            'tanggal_mulai' => now()
        ]);

        if ($kerjaPraktek->perluResponsi()) {
            $kerjaPraktek->update(['perlu_responsi' => true]);
        }

        Notifikasi::create([
            'user_id' => $kerjaPraktek->mahasiswa_id,
            'title' => 'Kerja Praktek Dimulai',
            'message' => 'Status KP Anda diubah menjadi "Sedang KP". Mulai dokumentasikan kegiatan dan bimbingan Anda.',
            'type' => 'info',
            'kerja_praktek_id' => $kerjaPraktek->id
        ]);

        // Kirim notifikasi ke pengawas lapangan tempat magang tersebut
        if ($kerjaPraktek->tempatMagang && $kerjaPraktek->tempatMagang->pengawasAktif) {
            foreach ($kerjaPraktek->tempatMagang->pengawasAktif as $pengawas) {
                Notifikasi::create([
                    'user_id' => $pengawas->id,
                    'title' => 'Mahasiswa Mulai Kerja Praktek',
                    'message' => "Mahasiswa {$kerjaPraktek->mahasiswa->name} (NPM: {$kerjaPraktek->mahasiswa->npm}) dengan judul KP '{$kerjaPraktek->judul_kp}' telah mulai kerja praktek di tempat magang Anda.",
                    'type' => 'info',
                    'kerja_praktek_id' => $kerjaPraktek->id,
                    'action_url' => route('pengawas.mahasiswa.show', $kerjaPraktek)
                ]);
            }
        }

        return back()->with('success', 'Status KP diubah menjadi "Sedang KP".');
    }

    public function inputNilai(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $request->validate([
            'penilaian_detail' => 'required|array|min:1',
            'penilaian_detail.*.indikator' => 'required|string',
            'penilaian_detail.*.nilai' => 'required|numeric|min:0|max:100',
            'keterangan_penilaian' => 'required|string'
        ]);

        // Hitung rata-rata dosen pembimbing (indeks 0-2)
        $penilaianDosen = array_slice($request->penilaian_detail, 0, 3);
        $rataRataDosen = collect($penilaianDosen)->avg('nilai');
        $rataRataDosen = round($rataRataDosen, 2);

        // Pastikan nilai seminar sudah ada dari dosen penguji
        if (!$kerjaPraktek->rata_rata_seminar) {
            return back()->with('error', 'Nilai seminar belum diinput oleh dosen penguji.');
        }

        // Hitung nilai akhir: rata-rata dari pengawas, seminar, dan pembimbing
        $rataRataPengawas = $kerjaPraktek->rata_rata_pengawas ?? 0;
        $rataRataSeminar = $kerjaPraktek->rata_rata_seminar;
        $rataRataGabungan = ($rataRataSeminar + $rataRataDosen) / 2;
        $nilaiAkhir = $rataRataPengawas > 0 ? ($rataRataPengawas + $rataRataGabungan) / 2 : $rataRataGabungan;
        $nilaiAkhir = round($nilaiAkhir, 2);

        $lulus = $nilaiAkhir >= 70;

        $kerjaPraktek->update([
            'penilaian_detail' => $request->penilaian_detail,
            'penilaian_dosen' => $request->penilaian_detail,
            'rata_rata_dosen' => $rataRataDosen,
            'nilai_akhir' => $nilaiAkhir,
            'keterangan_penilaian' => $request->keterangan_penilaian,
            'lulus_ujian' => $lulus,
            'status' => KerjaPraktek::STATUS_SELESAI
        ]);

        // Kirim notifikasi ke mahasiswa
        Notifikasi::create([
            'user_id' => $kerjaPraktek->mahasiswa_id,
            'title' => 'Penilaian Kerja Praktek Selesai',
            'message' => "Penilaian kerja praktek Anda telah selesai dengan nilai akhir: {$nilaiAkhir}. Status: " . ($lulus ? 'LULUS' : 'TIDAK LULUS') . ". Silakan cek detail penilaian di halaman kerja praktek Anda.",
            'type' => $lulus ? 'success' : 'warning',
            'kerja_praktek_id' => $kerjaPraktek->id,
            'action_url' => route('mahasiswa.kerja-praktek.index')
        ]);

        return back()->with('success', 'Penilaian berhasil disimpan.');
    }

    public function sendReminder(KerjaPraktek $kerjaPraktek)
    {
        $daysSinceLastBimbingan = 0;
        $lastBimbingan = $kerjaPraktek->bimbingan()->latest()->first();

        if ($lastBimbingan) {
            $daysSinceLastBimbingan = $lastBimbingan->created_at->diffInDays(now());
        }

        $message = $daysSinceLastBimbingan > 30
            ? "Sudah {$daysSinceLastBimbingan} hari tidak ada bimbingan. Mohon segera lakukan bimbingan."
            : "Jangan lupa melakukan bimbingan rutin untuk KP Anda.";

        Notifikasi::create([
            'user_id' => $kerjaPraktek->mahasiswa_id,
            'title' => 'Reminder Bimbingan KP',
            'message' => $message,
            'type' => 'warning',
            'kerja_praktek_id' => $kerjaPraktek->id,
            'action_url' => route('mahasiswa.bimbingan.create')
        ]);

        return back()->with('success', 'Reminder berhasil dikirim ke mahasiswa.');
    }

    public function setIpk(Request $request, KerjaPraktek $kerjaPraktek)
{
    // Otorisasi sesuai kebutuhanmu (policy / role middleware sudah ada di route group)
    $validated = $request->validate([
        'semester_ke'  => ['nullable','integer','min:1','max:14'],
        // pakai regex agar 2 desimal & range 0.00 - 4.00
        'ipk_semester' => ['required','regex:/^\d(\.\d{1,2})?$/'], // 0 - 9.99 format
    ], [
        'ipk_semester.regex' => 'IPK harus angka desimal 2 digit (contoh: 3.75).',
    ]);

    // batas atas IPK = 4.00
    $ipk = (float) $validated['ipk_semester'];
    if ($ipk < 0 || $ipk > 4) {
        return back()->with('error', 'IPK harus di antara 0.00 sampai 4.00.');
    }

    $kerjaPraktek->update([
        'semester_ke'  => $validated['semester_ke'] ?? $kerjaPraktek->semester_ke,
        'ipk_semester' => number_format($ipk, 2, '.', ''),
    ]);

    return back()->with('success', 'IPK semester berhasil disimpan.');
}
    // public function accKartu(KerjaPraktek $kerjaPraktek) - REMOVED kartu implementasi ACC
    // {
    //     // (opsional) $this->authorize('update', $kerjaPraktek);

    //     if (!$kerjaPraktek->file_kartu_implementasi) {
    //         return back()->with('error', 'Mahasiswa belum mengunggah kartu implementasi.');
    //     }

    //     $kerjaPraktek->update([
    //         'acc_pembimbing_lapangan' => true,
    //     ]);

    //     Notifikasi::create([
    //         'user_id'          => $kerjaPraktek->mahasiswa_id,
    //         'title'            => 'Kartu Implementasi Di-ACC',
    //         'message'          => "Kartu implementasi untuk KP '{$kerjaPraktek->judul_kp}' telah di-ACC. Silakan bersiap untuk ujian.",
    //         'type'             => 'success',
    //         'kerja_praktek_id' => $kerjaPraktek->id,
    //         'action_url'       => route('mahasiswa.kerja-praktek.index'),
    //     ]);

    //     return back()->with('success', 'Kartu implementasi di-ACC dan notifikasi dikirim ke mahasiswa.');
    // }

    public function accLaporan(KerjaPraktek $kerjaPraktek)
    {
        // (opsional) $this->authorize('update', $kerjaPraktek);

        if (!$kerjaPraktek->file_laporan) {
            return back()->with('error', 'Mahasiswa belum mengunggah laporan.');
        }

        $kerjaPraktek->update([
            'acc_pembimbing_laporan' => true,
        ]);

        Notifikasi::create([
            'user_id'          => $kerjaPraktek->mahasiswa_id,
            'title'            => 'Laporan KP Di-ACC',
            'message'          => "Laporan KP '{$kerjaPraktek->judul_kp}' telah di-ACC oleh pembimbing. Silakan daftar seminar untuk melanjutkan proses.",
            'type'             => 'success',
            'kerja_praktek_id' => $kerjaPraktek->id,
            'action_url'       => route('mahasiswa.kerja-praktek.index'),
        ]);

        return back()->with('success', 'Laporan di-ACC dan notifikasi dikirim ke mahasiswa.');
    }

    public function accPendaftaranSeminar(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $request->validate([
            'jadwal_seminar' => 'required|date|after:now',
            'ruangan_seminar' => 'required|string|max:255',
            'catatan_seminar' => 'nullable|string'
        ]);

        if (!$kerjaPraktek->pendaftaran_seminar) {
            return back()->with('error', 'Mahasiswa belum mendaftar seminar.');
        }

        $kerjaPraktek->update([
            'acc_pendaftaran_seminar' => true,
            'jadwal_seminar' => $request->jadwal_seminar,
            'ruangan_seminar' => $request->ruangan_seminar,
            'catatan_seminar' => $request->catatan_seminar,
        ]);

        Notifikasi::create([
            'user_id' => $kerjaPraktek->mahasiswa_id,
            'title' => 'Jadwal Seminar KP Ditetapkan',
            'message' => "Jadwal seminar KP Anda telah ditetapkan: " . \Carbon\Carbon::parse($request->jadwal_seminar)->format('d F Y H:i') . " di ruangan {$request->ruangan_seminar}",
            'type' => 'success',
            'kerja_praktek_id' => $kerjaPraktek->id,
            'action_url' => route('mahasiswa.kerja-praktek.index'),
        ]);

        return back()->with('success', 'Pendaftaran seminar di-ACC dan jadwal telah ditetapkan.');
    }

    public function tolakPendaftaranSeminar(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $request->validate(['catatan_seminar' => 'required|string']);

        $kerjaPraktek->update([
            'pendaftaran_seminar' => false,
            'tanggal_daftar_seminar' => null,
            'catatan_seminar' => $request->catatan_seminar,
        ]);

        Notifikasi::create([
            'user_id' => $kerjaPraktek->mahasiswa_id,
            'title' => 'Pendaftaran Seminar Ditolak',
            'message' => "Pendaftaran seminar KP Anda ditolak. Alasan: {$request->catatan_seminar}",
            'type' => 'error',
            'kerja_praktek_id' => $kerjaPraktek->id,
            'action_url' => route('mahasiswa.kerja-praktek.index'),
        ]);

        return back()->with('success', 'Pendaftaran seminar ditolak.');
    }

    public function accProposal(KerjaPraktek $kerjaPraktek)
    {
        if (!$kerjaPraktek->file_proposal) {
            return back()->with('error', 'Mahasiswa belum mengunggah proposal.');
        }

        $kerjaPraktek->update([
            'status' => KerjaPraktek::STATUS_SEDANG_KP,
            'tanggal_mulai' => now()
        ]);

        // tetapkan pembimbing akademik = dosen yang approve
        DosenPembimbing::updateOrCreate(
            ['kerja_praktek_id' => $kerjaPraktek->id, 'jenis_pembimbingan' => 'akademik'],
            ['dosen_id' => auth()->id()]
        );

        Notifikasi::create([
            'user_id'          => $kerjaPraktek->mahasiswa_id,
            'title'            => 'Proposal KP Di-ACC - Kerja Praktek Dimulai',
            'message'          => "Proposal KP '{$kerjaPraktek->judul_kp}' telah di-ACC oleh pembimbing. Status KP Anda langsung berubah menjadi 'Sedang KP'. Mulai dokumentasikan kegiatan dan bimbingan Anda.",
            'type'             => 'success',
            'kerja_praktek_id' => $kerjaPraktek->id,
            'action_url'       => route('mahasiswa.kerja-praktek.index'),
        ]);

        // Kirim notifikasi ke pengawas lapangan tempat magang tersebut
        if ($kerjaPraktek->tempatMagang && $kerjaPraktek->tempatMagang->pengawasAktif) {
            foreach ($kerjaPraktek->tempatMagang->pengawasAktif as $pengawas) {
                Notifikasi::create([
                    'user_id' => $pengawas->id,
                    'title' => 'Mahasiswa Mulai Kerja Praktek',
                    'message' => "Mahasiswa {$kerjaPraktek->mahasiswa->name} (NPM: {$kerjaPraktek->mahasiswa->npm}) dengan judul KP '{$kerjaPraktek->judul_kp}' telah mulai kerja praktek di tempat magang Anda.",
                    'type' => 'info',
                    'kerja_praktek_id' => $kerjaPraktek->id,
                    'action_url' => route('pengawas.mahasiswa.show', $kerjaPraktek)
                ]);
            }
        }

        return back()->with('success', 'Proposal di-ACC, status KP langsung berubah menjadi "Sedang KP", dan notifikasi dikirim ke mahasiswa.');
    }

    public function rejectProposal(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $request->validate(['catatan_dosen' => 'required|string']);

        // Ubah status menjadi ditolak
        $kerjaPraktek->update([
            'status' => KerjaPraktek::STATUS_DITOLAK,
            'catatan_dosen' => $request->catatan_dosen
        ]);

        // Notifikasi penolakan proposal
        Notifikasi::create([
            'user_id' => $kerjaPraktek->mahasiswa_id,
            'title' => 'Proposal KP Ditolak',
            'message' => "Proposal KP Anda ditolak. Alasan: {$request->catatan_dosen}",
            'type' => 'error',
            'kerja_praktek_id' => $kerjaPraktek->id,
            'action_url' => route('mahasiswa.kerja-praktek.index')
        ]);

        // Kirim notifikasi ke superadmin
        \App\Services\NotificationService::sendToRole('superadmin', 'Proposal KP Ditolak', "Proposal KP mahasiswa {$kerjaPraktek->mahasiswa->name} ({$kerjaPraktek->mahasiswa->npm}) dengan judul '{$kerjaPraktek->judul_kp}' telah ditolak oleh dosen pembimbing.", 'warning', $kerjaPraktek->id, route('superadmin.kerja-praktek.index'));

        // Jika ini penolakan ke-2, kirim notifikasi khusus untuk menemui dosen pembimbing
        $rejectedCount = KerjaPraktek::where('mahasiswa_id', $kerjaPraktek->mahasiswa_id)
            ->where('status', KerjaPraktek::STATUS_DITOLAK)
            ->count();

        if ($rejectedCount >= 2) {
            Notifikasi::create([
                'user_id' => $kerjaPraktek->mahasiswa_id,
                'title' => 'Mohon Temui Dosen Pembimbing',
                'message' => 'Proposal KP Anda telah ditolak sebanyak 2 kali. Silakan segera menemui dosen pembimbing untuk konsultasi sebelum pengajuan berikutnya.',
                'type' => 'warning',
                'kerja_praktek_id' => $kerjaPraktek->id,
                'action_url' => route('mahasiswa.kerja-praktek.index'),
            ]);
        }

        return back()->with('success', 'Proposal KP ditolak dengan alasan yang diberikan.');
    }

}
