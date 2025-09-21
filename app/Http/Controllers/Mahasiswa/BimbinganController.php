<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bimbingan;
use App\Models\KerjaPraktek;

class BimbinganController extends Controller
{
    public function index()
    {
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', auth()->id())->first();
        
        if (!$kerjaPraktek) {
            return redirect()->route('mahasiswa.kerja-praktek.index')
                           ->with('error', 'Anda belum memiliki kerja praktek.');
        }

        $bimbingan = Bimbingan::where('mahasiswa_id', auth()->id())
                              ->with('kerjaPraktek')
                              ->orderBy('tanggal_bimbingan', 'desc')
                              ->paginate(10);

        return view('mahasiswa.kerja-praktek.bimbingan.index', compact('bimbingan', 'kerjaPraktek'));
    }

    public function create()
    {
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', auth()->id())->first();
        
        if (!$kerjaPraktek || $kerjaPraktek->status !== KerjaPraktek::STATUS_SEDANG_KP) {
            return redirect()->route('mahasiswa.bimbingan.index')
                           ->with('error', 'Anda hanya bisa membuat bimbingan saat status KP sedang berlangsung.');
        }

        return view('mahasiswa.kerja-praktek.bimbingan.create', compact('kerjaPraktek'));
    }

    public function store(Request $request)
    {
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', auth()->id())->first();
        
        if (!$kerjaPraktek || $kerjaPraktek->status !== KerjaPraktek::STATUS_SEDANG_KP) {
            return back()->with('error', 'Tidak dapat menambah bimbingan.');
        }

        $request->validate([
            'tanggal_bimbingan' => 'required|date',
            'topik_bimbingan' => 'required|string|max:255',
            'catatan_mahasiswa' => 'required|string',
        ]);

        Bimbingan::create([
            'kerja_praktek_id' => $kerjaPraktek->id,
            'mahasiswa_id' => auth()->id(),
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'topik_bimbingan' => $request->topik_bimbingan,
            'catatan_mahasiswa' => $request->catatan_mahasiswa,
        ]);

        return redirect()->route('mahasiswa.bimbingan.index')
                        ->with('success', 'Bimbingan berhasil ditambahkan.');
    }

    public function show(Bimbingan $bimbingan)
    {
        $this->authorize('view', $bimbingan);
        return view('mahasiswa.kerja-praktek.bimbingan.show', compact('bimbingan'));
    }
}