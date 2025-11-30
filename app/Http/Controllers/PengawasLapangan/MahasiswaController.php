<?php
namespace App\Http\Controllers\PengawasLapangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KerjaPraktek;
use App\Services\NotificationService;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $pengawas = $request->user();

        if (!$pengawas->tempat_magang_id) {
            return back()->with('error', 'Akun pengawas belum dihubungkan ke Tempat Magang.');
        }

        $query = KerjaPraktek::with(['mahasiswa', 'tempatMagang'])
            ->where('tempat_magang_id', $pengawas->tempat_magang_id)
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(function ($qq) use ($s) {
                    $qq->where('judul_kp', 'like', "%{$s}%")
                       ->orWhereHas('mahasiswa', fn($qm) => $qm->where('name','like',"%{$s}%")->orWhere('npm','like',"%{$s}%"));
                });
            })
            ->orderByDesc('created_at');

        $kp = $query->paginate(15)->withQueryString();
        
        

        $stats = [
            'total'     => KerjaPraktek::where('tempat_magang_id', $pengawas->tempat_magang_id)->count(),
            'pengajuan' => KerjaPraktek::where('tempat_magang_id', $pengawas->tempat_magang_id)->where('status', KerjaPraktek::STATUS_PENGAJUAN)->count(),
            'disetujui' => KerjaPraktek::where('tempat_magang_id', $pengawas->tempat_magang_id)->where('status', KerjaPraktek::STATUS_DISETUJUI)->count(),
            'sedang'    => KerjaPraktek::where('tempat_magang_id', $pengawas->tempat_magang_id)->where('status', KerjaPraktek::STATUS_SEDANG_KP)->where(function ($q) {
                $q->whereNull('nilai_akhir')->orWhereNull('file_laporan');
            })->count(),
            'selesai'   => KerjaPraktek::where('tempat_magang_id', $pengawas->tempat_magang_id)->where(function ($q) {
                $q->where('status', KerjaPraktek::STATUS_SELESAI)->orWhere(function ($qq) {
                    $qq->where('status', KerjaPraktek::STATUS_SEDANG_KP)->whereNotNull('nilai_akhir')->whereNotNull('file_laporan');
                });
            })->count(),
        ];

        return view('pengawas.mahasiswa.index', [
            'kp'     => $kp,
            'stats'  => $stats,
            'place'  => $pengawas->tempatMagang,
        ]);
    }

    public function show(Request $request, KerjaPraktek $mahasiswa)
    {
        $this->abortIfNotMine($request->user(), $mahasiswa);

        $mahasiswa->load(['mahasiswa','tempatMagang','bimbingan','kegiatan']);
        return view('pengawas.mahasiswa.show', ['kp' => $mahasiswa]);
    }

    public function accKartuImplementasi(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $this->abortIfNotMine($request->user(), $kerjaPraktek);

        if (!$kerjaPraktek->file_kartu_implementasi) {
            return back()->with('error', 'Mahasiswa belum mengunggah kartu implementasi.');
        }

        $kerjaPraktek->update([
            'acc_pembimbing_lapangan' => true,
        ]);

        return back()->with('success', 'Kartu implementasi di-ACC.');
    }

    public function addFeedback(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $this->abortIfNotMine($request->user(), $kerjaPraktek);

        $data = $request->validate([
            'catatan_pengawas' => 'required|string|max:3000',
        ]);

        $kerjaPraktek->update($data);

        return back()->with('success', 'Feedback pembimbing lapangan tersimpan.');
    }

    public function inputPenilaianPengawas(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $this->abortIfNotMine($request->user(), $kerjaPraktek);

        // Validasi: hanya bisa input jika sudah acc pendaftaran seminar dan sedang seminar
        if (!$kerjaPraktek->acc_pendaftaran_seminar || (!$kerjaPraktek->acc_seminar && $kerjaPraktek->status !== 'sedang_kp')) {
            return back()->with('error', 'Mahasiswa belum memenuhi syarat untuk penilaian kerja.');
        }

        $request->validate([
            'penilaian_pengawas' => 'required|array|min:1',
            'penilaian_pengawas.*.aspek' => 'required|string',
            'penilaian_pengawas.*.nilai' => 'required|numeric|min:0|max:100',
        ]);

        // Hitung rata-rata
        $nilaiAkhir = collect($request->penilaian_pengawas)->avg('nilai');
        $rataRata = round($nilaiAkhir, 2);

        $kerjaPraktek->update([
            'penilaian_pengawas' => $request->penilaian_pengawas,
            'rata_rata_pengawas' => $rataRata,
        ]);

        // Kirim notifikasi ke dosen pembimbing
        if ($kerjaPraktek->dosenPembimbing && $kerjaPraktek->dosenPembimbing->count() > 0) {
            foreach ($kerjaPraktek->dosenPembimbing as $dosenPembimbing) {
                NotificationService::sendToUser(
                    $dosenPembimbing->dosen_id,
                    'Penilaian Kerja Mahasiswa',
                    "Pengawas lapangan telah mengisi penilaian kerja untuk mahasiswa {$kerjaPraktek->mahasiswa->name} (NPM: {$kerjaPraktek->mahasiswa->npm}). Silakan lengkapi penilaian dosen.",
                    'info',
                    $kerjaPraktek->id,
                    route('admin.kerja-praktek.show', $kerjaPraktek)
                );
            }
        }

        return back()->with('success', 'Penilaian kerja berhasil disimpan.');
    }

    private function abortIfNotMine($user, KerjaPraktek $kp): void
    {
        abort_if(
            $user->tempat_magang_id !== $kp->tempat_magang_id,
            403,
            'Anda tidak berwenang mengakses data ini.'
        );
    }
}