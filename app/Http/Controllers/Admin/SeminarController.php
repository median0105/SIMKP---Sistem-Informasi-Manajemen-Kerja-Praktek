<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KerjaPraktek;
use App\Models\User;
use App\Models\DosenPenguji;

class SeminarController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        // Ambil mahasiswa yang ditugaskan ke dosen yang sedang login sebagai penguji
        // Termasuk yang sudah selesai seminar dan diuji oleh dosen ini
        $mahasiswa = DosenPenguji::with(['kerjaPraktek.mahasiswa', 'kerjaPraktek.tempatMagang'])
            ->where('dosen_id', auth()->id())
            ->where('is_active', true)
            ->when($search !== '', function ($q) use ($search) {
                $q->whereHas('kerjaPraktek.mahasiswa', function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                    ->orWhere('npm', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->get()
            ->map(function($dosenPenguji) {
                $mahasiswa = $dosenPenguji->kerjaPraktek->mahasiswa;
                $mahasiswa->kpTerbaru = $dosenPenguji->kerjaPraktek;
                return $mahasiswa;
            })
            ->sortBy('name');

        // Convert to pagination manually since we're using map
        $perPage = 15;
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $paginatedMahasiswa = $mahasiswa->slice($offset, $perPage);

        $mahasiswa = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedMahasiswa,
            $mahasiswa->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'pageName' => 'page']
        );
        $mahasiswa->appends($request->query());

        return view('admin.seminar.index', compact('mahasiswa', 'search'));
    }

    public function show(KerjaPraktek $kerjaPraktek)
    {
        // Pastikan dosen yang sedang login adalah penguji untuk KP ini
        $isPenguji = DosenPenguji::where('dosen_id', auth()->id())
            ->where('kerja_praktek_id', $kerjaPraktek->id)
            ->where('is_active', true)
            ->exists();

        if (!$isPenguji) {
            abort(403, 'Anda tidak memiliki akses untuk melihat detail KP ini.');
        }

        $kerjaPraktek->load([
            'mahasiswa', 'tempatMagang', 'bimbingan', 'kegiatan',
            'dosenPembimbing', 'kuisioner'
        ]);

        $isDuplicate = $kerjaPraktek->isDuplicateTitle();
        $needsResponsi = $kerjaPraktek->perluResponsi();

        return view('admin.seminar.show', compact('kerjaPraktek', 'isDuplicate', 'needsResponsi'));
    }

    public function accPendaftaranSeminar(KerjaPraktek $kerjaPraktek, Request $request)
    {
        // Pastikan dosen yang sedang login adalah penguji untuk KP ini
        $isPenguji = DosenPenguji::where('dosen_id', auth()->id())
            ->where('kerja_praktek_id', $kerjaPraktek->id)
            ->where('is_active', true)
            ->exists();

        if (!$isPenguji) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        $request->validate([
            'jadwal_seminar' => 'required|date_format:Y-m-d H:i',
            'ruangan_seminar' => 'required|string|max:255',
            'catatan_seminar' => 'nullable|string'
        ]);

        $kerjaPraktek->update([
            'acc_pendaftaran_seminar' => true,
            'jadwal_seminar' => $request->jadwal_seminar,
            'ruangan_seminar' => $request->ruangan_seminar,
            'catatan_seminar' => $request->catatan_seminar,
        ]);

        // Kirim notifikasi ke mahasiswa
        $kerjaPraktek->mahasiswa->notify(new \App\Notifications\SeminarAccNotification($kerjaPraktek, 'acc_pendaftaran'));

        return redirect()->back()->with('success', 'Pendaftaran seminar berhasil di-ACC.');
    }

    public function tolakPendaftaranSeminar(KerjaPraktek $kerjaPraktek, Request $request)
    {
        // Pastikan dosen yang sedang login adalah penguji untuk KP ini
        $isPenguji = DosenPenguji::where('dosen_id', auth()->id())
            ->where('kerja_praktek_id', $kerjaPraktek->id)
            ->where('is_active', true)
            ->exists();

        if (!$isPenguji) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        $request->validate([
            'catatan_seminar' => 'required|string'
        ]);

        $kerjaPraktek->update([
            'acc_pendaftaran_seminar' => false,
            'catatan_seminar' => $request->catatan_seminar,
            'pendaftaran_seminar' => false, // Reset pendaftaran
        ]);

        // Kirim notifikasi ke mahasiswa
        $kerjaPraktek->mahasiswa->notify(new \App\Notifications\SeminarAccNotification($kerjaPraktek, 'tolak_pendaftaran'));

        return redirect()->back()->with('success', 'Pendaftaran seminar berhasil ditolak.');
    }

    public function accSeminar(KerjaPraktek $kerjaPraktek)
    {
        // Pastikan dosen yang sedang login adalah penguji untuk KP ini
        $isPenguji = DosenPenguji::where('dosen_id', auth()->id())
            ->where('kerja_praktek_id', $kerjaPraktek->id)
            ->where('is_active', true)
            ->exists();

        if (!$isPenguji) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        $kerjaPraktek->update([
            'acc_seminar' => true,
        ]);

        // Kirim notifikasi ke mahasiswa
        $kerjaPraktek->mahasiswa->notify(new \App\Notifications\SeminarAccNotification($kerjaPraktek, 'acc_seminar'));

        return redirect()->back()->with('success', 'Seminar berhasil di-ACC.');
    }

    public function inputNilai(Request $request, KerjaPraktek $kerjaPraktek)
    {
        // Pastikan dosen yang sedang login adalah penguji untuk KP ini
        $isPenguji = DosenPenguji::where('dosen_id', auth()->id())
            ->where('kerja_praktek_id', $kerjaPraktek->id)
            ->where('is_active', true)
            ->exists();

        if (!$isPenguji) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        $request->validate([
            'penilaian_detail' => 'required|array|min:1',
            'penilaian_detail.*.indikator' => 'required|string',
            'penilaian_detail.*.nilai' => 'required|numeric|min:0|max:100',
            'keterangan_penilaian' => 'required|string'
        ]);

        // Hitung rata-rata seminar (indeks 0-2)
        $penilaianSeminar = array_slice($request->penilaian_detail, 0, 3);
        $rataRataSeminar = collect($penilaianSeminar)->avg('nilai');
        $rataRataSeminar = round($rataRataSeminar, 2);

        // Simpan hanya nilai seminar dan rata-rata seminar
        $kerjaPraktek->update([
            'penilaian_detail' => $request->penilaian_detail,
            'rata_rata_seminar' => $rataRataSeminar,
            'keterangan_penilaian' => $request->keterangan_penilaian,
        ]);

        // Kirim notifikasi ke mahasiswa
        $kerjaPraktek->mahasiswa->notify(new \App\Notifications\SeminarAccNotification($kerjaPraktek, 'nilai_seminar', [
            'rata_rata_seminar' => $rataRataSeminar
        ]));

        return redirect()->back()->with('success', 'Penilaian seminar berhasil disimpan.');
    }
}
