<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        $periodes = Periode::latest()->paginate(10);
        $activePeriode = Periode::active()->first();

        return view('superadmin.periodes.index', compact('periodes', 'activePeriode'));
    }

    public function create()
    {
        return view('superadmin.periodes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required|string|max:20',
            'semester_ke' => 'required|integer|min:1',
            'semester_type' => 'required|in:ganjil,genap',
            'tanggal_mulai_kp' => 'required|date',
            'tanggal_selesai_kp' => 'required|date|after:tanggal_mulai_kp',
            'status' => 'boolean',
        ]);

        if ($request->status) {
            Periode::query()->update(['status' => false]);
        }

        Periode::create($request->all());

        return redirect()->route('superadmin.periodes.index')->with('success', 'Periode berhasil dibuat.');
    }

    public function show(Periode $periode)
    {
        return view('superadmin.periodes.show', compact('periode'));
    }

    public function edit(Periode $periode)
    {
        return view('superadmin.periodes.edit', compact('periode'));
    }

    public function update(Request $request, Periode $periode)
    {
        $request->validate([
            'tahun_akademik' => 'required|string|max:20',
            'semester_ke' => 'required|integer|min:1',
            'semester_type' => 'required|in:ganjil,genap',
            'tanggal_mulai_kp' => 'required|date',
            'tanggal_selesai_kp' => 'required|date|after:tanggal_mulai_kp',
            'status' => 'boolean',
        ]);

        if ($request->status) {
            Periode::where('id', '!=', $periode->id)->update(['status' => false]);
        }

        $periode->update($request->all());

        return redirect()->route('superadmin.periodes.index')->with('success', 'Periode berhasil diupdate.');
    }

    public function destroy(Periode $periode)
    {
        if ($periode->status) {
            return back()->with('error', 'Tidak bisa hapus periode aktif.');
        }

        $periode->delete();

        return redirect()->route('superadmin.periodes.index')->with('success', 'Periode berhasil dihapus.');
    }
}
