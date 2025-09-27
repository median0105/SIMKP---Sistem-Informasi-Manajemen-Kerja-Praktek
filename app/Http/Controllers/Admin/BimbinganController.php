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
            // search (DIKELOMPOKKAN agar OR tidak "bocor")
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim($request->search);
                $q->where(function ($qq) use ($search) {
                    $qq->where('topik_bimbingan', 'like', "%{$search}%")
                       ->orWhereHas('mahasiswa', function ($sub) use ($search) {
                           $sub->where('name', 'like', "%{$search}%")
                               ->orWhere('npm', 'like', "%{$search}%");
                       });
                });
            })
            ->orderByDesc('tanggal_bimbingan') // utamakan tanggal terbaru
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

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
            // jika ada relasi feedbacks, bisa diaktifkan:
            // 'feedbacks.user:id,name',
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

        // Notifikasi ke mahasiswa (opsional)
        if (class_exists(Notifikasi::class)) {
            Notifikasi::create([
                'user_id'          => $bimbingan->mahasiswa_id,
                'title'            => 'Bimbingan diverifikasi',
                'message'          => "Bimbingan '{$bimbingan->topik_bimbingan}' telah diverifikasi oleh dosen pembimbing.",
                'type'             => 'success',
                'kerja_praktek_id' => $bimbingan->kerja_praktek_id,
                // Pastikan route ini ada. Kalau tidak, bisa kosongkan `action_url`.
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

        // Terima dua kemungkinan nama input:
        //  - "catatan_dosen" (nama kolom)
        //  - "feedback" (nama field di form)
        $request->validate([
            'catatan_dosen' => 'required_without:feedback|string|max:2000',
            'feedback'      => 'required_without:catatan_dosen|string|max:2000',
            'verify'        => 'nullable|boolean',
        ]);

        $feedbackText = $request->input('catatan_dosen', $request->input('feedback'));

        $bimbingan->forceFill([
            'catatan_dosen'     => $feedbackText,
            // Jika checkbox verify dicentang maka true, jika tidak biarkan nilai sebelumnya
            'status_verifikasi' => $request->has('verify')
                ? $request->boolean('verify')
                : $bimbingan->status_verifikasi,
        ])->save();

        if (class_exists(Notifikasi::class)) {
            Notifikasi::create([
                'user_id'          => $bimbingan->mahasiswa_id,
                'title'            => 'Feedback bimbingan',
                'message'          => "Dosen memberikan feedback untuk bimbingan '{$bimbingan->catatan_dosen}'.",
                'type'             => 'info',
                'kerja_praktek_id' => $bimbingan->kerja_praktek_id,
                // Pastikan route ini ada. Kalau tidak, bisa kosongkan `action_url`.
                'action_url'       => route('mahasiswa.bimbingan.show', $bimbingan),
            ]);
        }

        return back()->with('success', 'Feedback berhasil disimpan.');
    }
}
