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
        $query = KerjaPraktek::with(['mahasiswa', 'tempatMagang', 'dosenAkademik'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // default fokus ke pengajuan
            $query->where('status', KerjaPraktek::STATUS_PENGAJUAN);
        }

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
            'status' => KerjaPraktek::STATUS_DISETUJUI,
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

        return back()->with('success', 'Status KP diubah menjadi "Sedang KP".');
    }

    public function inputNilai(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $request->validate([
            'penilaian_detail' => 'required|array',
            'penilaian_detail.*.indikator' => 'required|string',
            'penilaian_detail.*.nilai' => 'required|numeric|min:0|max:100',
            'nilai_akhir' => 'required|numeric|min:0|max:100',
            'keterangan_penilaian' => 'required|string'
        ]);

        $lulus = $request->nilai_akhir >= 70;

        $kerjaPraktek->update([
            'penilaian_detail' => $request->penilaian_detail,
            'nilai_akhir' => $request->nilai_akhir,
            'keterangan_penilaian' => $request->keterangan_penilaian,
            'lulus_ujian' => $lulus,
            'status' => $lulus ? KerjaPraktek::STATUS_SELESAI : KerjaPraktek::STATUS_SEDANG_KP
        ]);

        Notifikasi::create([
            'user_id' => $kerjaPraktek->mahasiswa_id,
            'title' => 'Hasil Ujian KP',
            'message' => "Hasil ujian KP Anda: ".($lulus ? 'LULUS' : 'TIDAK LULUS')." dengan nilai {$request->nilai_akhir}",
            'type' => $lulus ? 'success' : 'warning',
            'kerja_praktek_id' => $kerjaPraktek->id
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
public function accKartu(KerjaPraktek $kerjaPraktek)
{
    // (opsional) $this->authorize('update', $kerjaPraktek);

    if (!$kerjaPraktek->file_kartu_implementasi) {
        return back()->with('error', 'Mahasiswa belum mengunggah kartu implementasi.');
    }

    $kerjaPraktek->update([
        'acc_pembimbing_lapangan' => true,
    ]);

    Notifikasi::create([
        'user_id'          => $kerjaPraktek->mahasiswa_id,
        'title'            => 'Kartu Implementasi Di-ACC',
        'message'          => "Kartu implementasi untuk KP '{$kerjaPraktek->judul_kp}' telah di-ACC. Silakan bersiap untuk ujian.",
        'type'             => 'success',
        'kerja_praktek_id' => $kerjaPraktek->id,
        'action_url'       => route('mahasiswa.kerja-praktek.index'),
    ]);

    return back()->with('success', 'Kartu implementasi di-ACC dan notifikasi dikirim ke mahasiswa.');
}

}
