<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bimbingan;
use App\Models\KerjaPraktek;
use App\Models\Notifikasi;

class BimbinganController extends Controller
{
    /**
     * List bimbingan milik mahasiswa yang dibimbing dosen (user) ini.
     */
    public function index(Request $request)
    {
        // Ambil semua KP yang dosen-akademiknya adalah user saat ini
        $kerjaPraktekIds = KerjaPraktek::whereHas('dosenAkademik', function ($q) {
            $q->where('dosen_id', auth()->id());
        })->pluck('id');

        $bimbingan = Bimbingan::query()
            ->with([
                'mahasiswa:id,name,npm,email',
                'kerjaPraktek.tempatMagang',
            ])
            ->whereIn('kerja_praktek_id', $kerjaPraktekIds)
            // filter status
            ->when($request->filled('status'), function ($q) use ($request) {
                if ($request->status === 'verified') {
                    $q->where('status_verifikasi', true);
                } elseif ($request->status === 'pending') {
                    $q->where('status_verifikasi', false);
                }
            })
            // search
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim($request->search);
                $q->where('topik_bimbingan', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%")
                          ->orWhere('npm', 'like', "%{$search}%");
                  });
            })
            ->orderByDesc('tanggal_bimbingan') // utamakan tanggal terbaru
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString(); // supaya filter & search tetap menempel saat paging

        return view('admin.bimbingan.index', compact('bimbingan'));
    }

    /**
     * Detail satu bimbingan.
     */
    public function show(Bimbingan $bimbingan)
    {
        $this->authorize('view', $bimbingan);

        $bimbingan->load([
            'mahasiswa:id,name,npm,email',
            'kerjaPraktek.tempatMagang',
        ]);

        return view('admin.bimbingan.show', compact('bimbingan'));
    }

    /**
     * Verifikasi satu bimbingan.
     */
    public function verify(Bimbingan $bimbingan)
    {
        $this->authorize('update', $bimbingan);

        if ($bimbingan->status_verifikasi) {
            return back()->with('info', 'Bimbingan ini sudah diverifikasi.');
        }

        $bimbingan->forceFill([
            'status_verifikasi' => true,
        ])->save();

        // Notifikasi ke mahasiswa (opsional, jika model ada)
        if (class_exists(Notifikasi::class)) {
            Notifikasi::create([
                'user_id'          => $bimbingan->mahasiswa_id,
                'title'            => 'Bimbingan diverifikasi',
                'message'          => "Bimbingan '{$bimbingan->topik_bimbingan}' telah diverifikasi oleh dosen pembimbing.",
                'type'             => 'success',
                'kerja_praktek_id' => $bimbingan->kerja_praktek_id,
                'action_url'       => route('mahasiswa.bimbingan.show', $bimbingan),
            ]);
        }

        return back()->with('success', 'Bimbingan berhasil diverifikasi.');
    }

    /**
     * Tambah/ubah feedback dosen (opsional langsung verifikasi).
     */
    public function addFeedback(Request $request, Bimbingan $bimbingan)
    {
        $this->authorize('update', $bimbingan);

        $data = $request->validate([
            'catatan_dosen' => ['required','string','max:2000'],
            'verify'        => ['nullable','boolean'], // centang jika ingin sekalian verifikasi
        ]);

        $bimbingan->forceFill([
            'catatan_dosen'     => $data['catatan_dosen'],
            'status_verifikasi' => $request->boolean('verify', true), // default: true
        ])->save();

        if (class_exists(Notifikasi::class)) {
            Notifikasi::create([
                'user_id'          => $bimbingan->mahasiswa_id,
                'title'            => 'Feedback bimbingan',
                'message'          => "Dosen memberikan feedback untuk bimbingan '{$bimbingan->topik_bimbingan}'.",
                'type'             => 'info',
                'kerja_praktek_id' => $bimbingan->kerja_praktek_id,
                'action_url'       => route('mahasiswa.bimbingan.show', $bimbingan),
            ]);
        }

        return back()->with('success', 'Feedback berhasil disimpan.');
    }
}
