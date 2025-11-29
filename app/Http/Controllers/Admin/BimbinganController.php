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
     * List mahasiswa yang dibimbing dosen (user) ini.
     */
    public function index(Request $request)
    {
        // Ambil semua KP yang dosen-akademiknya adalah user saat ini
        $kerjaPraktekIds = KerjaPraktek::whereHas('dosenAkademik', function ($q) {
            $q->where('dosen_id', auth()->id());
        })->pluck('id');

        $mahasiswa = \App\Models\User::query()
            ->with([
                'kerjaPraktek' => function ($q) {
                    $q->with('tempatMagang');
                },
                'bimbingan' => function ($q) {
                    $q->orderByDesc('tanggal_bimbingan');
                }
            ])
            ->whereHas('kerjaPraktek', function ($q) use ($kerjaPraktekIds) {
                $q->whereIn('id', $kerjaPraktekIds);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim($request->search);
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                       ->orWhere('npm', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bimbingan.index', compact('mahasiswa'));
    }

    /**
     * Detail semua bimbingan mahasiswa.
     */
    public function show(Request $request)
    {
        $mahasiswaId = $request->query('mahasiswa');

        if (!$mahasiswaId) {
            abort(404, 'Mahasiswa tidak ditemukan');
        }

        $mahasiswa = \App\Models\User::with([
            'kerjaPraktek.tempatMagang',
            'bimbingan' => function ($q) {
                $q->orderByDesc('tanggal_bimbingan');
            }
        ])->findOrFail($mahasiswaId);

        // Pastikan dosen ini memang membimbing mahasiswa ini
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', $mahasiswaId)
            ->whereHas('dosenAkademik', function ($q) {
                $q->where('dosen_id', auth()->id());
            })->first();

        if (!$kerjaPraktek) {
            abort(403, 'Anda tidak memiliki akses untuk melihat bimbingan mahasiswa ini.');
        }

        return view('admin.bimbingan.show', compact('mahasiswa', 'kerjaPraktek'));
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
     * Form untuk membuat bimbingan baru.
     */
    public function create()
    {
        // Ambil mahasiswa yang sedang KP dan dibimbing oleh dosen ini
        $kerjaPraktekIds = KerjaPraktek::whereHas('dosenAkademik', function ($q) {
            $q->where('dosen_id', auth()->id());
        })->pluck('id');

        $mahasiswa = \App\Models\User::whereHas('kerjaPraktek', function ($q) use ($kerjaPraktekIds) {
            $q->whereIn('id', $kerjaPraktekIds);
        })->select('id', 'name', 'npm')->orderBy('name')->get();

        return view('admin.bimbingan.create', compact('mahasiswa'));
    }

    /**
     * Simpan bimbingan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id'       => 'required|exists:users,id',
            'tanggal_bimbingan'  => 'required|date',
            'topik_bimbingan'    => 'required|string|max:255',
            'catatan_dosen'      => 'required|string|max:2000',
            'status_verifikasi'  => 'nullable|boolean',
        ]);

        // Pastikan mahasiswa ini memang dibimbing oleh dosen ini
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', $request->mahasiswa_id)
            ->whereHas('dosenAkademik', function ($q) {
                $q->where('dosen_id', auth()->id());
            })->first();

        if (!$kerjaPraktek) {
            return back()->with('error', 'Anda tidak memiliki akses untuk membuat bimbingan untuk mahasiswa ini.');
        }

        $bimbingan = Bimbingan::create([
            'kerja_praktek_id'    => $kerjaPraktek->id,
            'mahasiswa_id'        => $request->mahasiswa_id,
            'tanggal_bimbingan'   => $request->tanggal_bimbingan,
            'topik_bimbingan'     => $request->topik_bimbingan,
            'catatan_dosen'       => $request->catatan_dosen,
            'catatan_mahasiswa'   => '',
            'status_verifikasi'   => $request->boolean('status_verifikasi'),
        ]);

        // Notifikasi ke mahasiswa
        if (class_exists(Notifikasi::class)) {
            Notifikasi::create([
                'user_id'          => $bimbingan->mahasiswa_id,
                'title'            => 'Bimbingan baru',
                'message'          => "Dosen memberikan bimbingan baru: '{$bimbingan->topik_bimbingan}'.",
                'type'             => 'info',
                'kerja_praktek_id' => $bimbingan->kerja_praktek_id,
                'action_url'       => route('mahasiswa.bimbingan.show', $bimbingan),
            ]);
        }

        return redirect()->route('admin.bimbingan.index')->with('success', 'Bimbingan berhasil dibuat.');
    }

    /**
     * Verifikasi semua bimbingan mahasiswa yang belum diverifikasi.
     */
    public function verifyAll(\App\Models\User $mahasiswa)
    {
        // Pastikan dosen ini memang membimbing mahasiswa ini
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('dosenAkademik', function ($q) {
                $q->where('dosen_id', auth()->id());
            })->first();

        if (!$kerjaPraktek) {
            return back()->with('error', 'Anda tidak memiliki akses untuk memverifikasi bimbingan mahasiswa ini.');
        }

        // Verifikasi semua bimbingan yang belum diverifikasi
        $updatedCount = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->where('status_verifikasi', false)
            ->update(['status_verifikasi' => true]);

        if ($updatedCount > 0) {
            // Notifikasi ke mahasiswa
            if (class_exists(Notifikasi::class)) {
                Notifikasi::create([
                    'user_id'          => $mahasiswa->id,
                    'title'            => 'Bimbingan diverifikasi',
                    'message'          => "Semua bimbingan Anda telah diverifikasi oleh dosen pembimbing.",
                    'type'             => 'success',
                    'kerja_praktek_id' => $kerjaPraktek->id,
                    'action_url'       => route('mahasiswa.bimbingan.index'),
                ]);
            }

            return back()->with('success', "Berhasil memverifikasi {$updatedCount} bimbingan.");
        }

        return back()->with('info', 'Tidak ada bimbingan yang perlu diverifikasi.');
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
