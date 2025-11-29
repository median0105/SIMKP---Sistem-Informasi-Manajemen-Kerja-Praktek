<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bimbingan;
use App\Models\KerjaPraktek;
use App\Services\NotificationService;

class BimbinganController extends Controller
{
    public function index()
    {
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', auth()->id())->first();

        $bimbingan = Bimbingan::where('mahasiswa_id', auth()->id())
                              ->with('kerjaPraktek')
                              ->orderBy('tanggal_bimbingan', 'desc')
                              ->paginate(10);

        return view('mahasiswa.kerja-praktek.bimbingan.index', compact('bimbingan', 'kerjaPraktek'));
    }

    public function create()
    {
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', auth()->id())->first();

        // Allow create bimbingan even if no KP or KP not in status sedang_kp
        return view('mahasiswa.kerja-praktek.bimbingan.create', compact('kerjaPraktek'));
    }

    public function store(Request $request)
    {
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', auth()->id())->first();

        $request->validate([
            'tanggal_bimbingan' => 'required|date',
            'topik_bimbingan' => 'required|string|max:255',
            'catatan_mahasiswa' => 'required|string',
        ]);

        // Cek apakah sudah ada bimbingan di tanggal yang sama
        $existingBimbingan = Bimbingan::where('mahasiswa_id', auth()->id())
            ->where('tanggal_bimbingan', $request->tanggal_bimbingan)
            ->first();

        if ($existingBimbingan) {
            return back()->withInput()->withErrors([
                'tanggal_bimbingan' => 'Anda sudah memiliki bimbingan di tanggal ini.'
            ]);
        }

        $bimbingan = Bimbingan::create([
            'kerja_praktek_id' => $kerjaPraktek ? $kerjaPraktek->id : null,
            'mahasiswa_id' => auth()->id(),
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'topik_bimbingan' => $request->topik_bimbingan,
            'catatan_mahasiswa' => $request->catatan_mahasiswa,
        ]);

        // Kirim notifikasi ke dosen pembimbing yang sudah ditugaskan
        if ($kerjaPraktek) {
            $assignedDosen = \App\Models\DosenPembimbing::whereHas('kerjaPraktek', function($q) use ($kerjaPraktek) {
                $q->where('mahasiswa_id', $kerjaPraktek->mahasiswa_id);
            })->where('jenis_pembimbingan', 'akademik')->pluck('dosen_id');

            if ($assignedDosen->isNotEmpty()) {
                foreach ($assignedDosen as $dosenId) {
                    NotificationService::sendToUser(
                        $dosenId,
                        'Pengajuan Bimbingan Baru',
                        'Mahasiswa ' . auth()->user()->name . ' mengajukan bimbingan untuk topik: ' . $request->topik_bimbingan,
                        'info',
                        $kerjaPraktek->id,
                        route('admin.bimbingan.show', ['mahasiswa' => $bimbingan->mahasiswa_id])
                    );
                }
            }
        }

        return redirect()->route('mahasiswa.bimbingan.index')
                        ->with('success', 'Bimbingan berhasil ditambahkan.');
    }

    public function show(Bimbingan $bimbingan)
    {
        $this->authorize('view', $bimbingan);
        return view('mahasiswa.kerja-praktek.bimbingan.show', compact('bimbingan'));
    }
}