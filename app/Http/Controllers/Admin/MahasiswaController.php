<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KerjaPraktek;
use App\Models\DosenPembimbing;

class MahasiswaController extends Controller
{
    /**
     * List mahasiswa + filter/search.
     * Menyediakan relasi semu "kpTerakhir" agar view tidak perlu
     * mengutak-atik Collection dari hasMany kerjaPraktek.
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->where('role', User::ROLE_MAHASISWA)
            ->where('is_active', true)
            ->with([
                // Urutkan KP biar cepat saat ambil latest
                'kerjaPraktek' => fn ($q) => $q->orderByDesc('created_at'),
                'kerjaPraktek.tempatMagang',
            ]);

        // Search: nama / NPM / email
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('npm', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status KP (match ke salah satu KP miliknya)
        if ($request->filled('status_kp')) {
            $status = $request->status_kp;
            $query->whereHas('kerjaPraktek', fn ($q) => $q->where('status', $status));
        }

        // Hanya mahasiswa yang dibimbing oleh dosen (akademik) ini
        if ($request->filled('my_students') || (auth()->check() && auth()->user()->role === User::ROLE_ADMIN_DOSEN)) {
            $query->whereHas('kerjaPraktek.dosenAkademik', fn ($q) => $q->where('dosen_id', auth()->id()));
        }

        $mahasiswa = $query->orderBy('name')->paginate(15)->appends($request->query());

        // Suntik relasi semu "kpTerakhir" (single model) dari koleksi kerjaPraktek
        $mahasiswa->setCollection(
            $mahasiswa->getCollection()->transform(function (User $m) {
                $latest = optional($m->getRelation('kerjaPraktek'))->first(); // karena sudah di-order desc
                $m->setRelation('kpTerakhir', $latest); // agar di view bisa: $m->kpTerakhir
                return $m;
            })
        );

        // Statistik ringkas untuk cards
        $stats = [
            'total_mahasiswa'    => User::where('role', User::ROLE_MAHASISWA)->count(),
            'mahasiswa_bimbingan'=> DosenPembimbing::where('dosen_id', auth()->id())
                                    ->where('jenis_pembimbingan', 'akademik')->count(),
            'sedang_kp'          => KerjaPraktek::whereHas('dosenAkademik', fn ($q) => $q->where('dosen_id', auth()->id()))
                                    ->where('status', KerjaPraktek::STATUS_SEDANG_KP)->count(),
            'selesai_kp'         => KerjaPraktek::whereHas('dosenAkademik', fn ($q) => $q->where('dosen_id', auth()->id()))
                                    ->where('status', KerjaPraktek::STATUS_SELESAI)->count(),
        ];

        return view('admin.mahasiswa.index', compact('mahasiswa', 'stats'));
    }

    /**
     * Detail satu mahasiswa + aktivitas ringkas.
     * Juga menyuntik "kpTerakhir" untuk dipakai di view detail.
     */
    public function show(User $mahasiswa)
    {
        if ($mahasiswa->role !== User::ROLE_MAHASISWA) {
            abort(404);
        }

        $mahasiswa->load([
            'kerjaPraktek'                    => fn ($q) => $q->orderByDesc('created_at'),
            'kerjaPraktek.tempatMagang',
            'kerjaPraktek.dosenPembimbing.dosen',
            'bimbingan'                       => fn ($q) => $q->latest()->limit(10),
            'kegiatan'                        => fn ($q) => $q->latest()->limit(15),
        ]);

        // relasi semu kpTerakhir untuk view
        $mahasiswa->setRelation('kpTerakhir', optional($mahasiswa->kerjaPraktek)->first());

        // Statistik kecil untuk panel
        $stats = [
            'total_bimbingan'     => $mahasiswa->bimbingan()->count(),
            'bimbingan_verified'  => $mahasiswa->bimbingan()->where('status_verifikasi', true)->count(),
            'total_kegiatan'      => $mahasiswa->kegiatan()->count(),
            'total_jam_kegiatan'  => (int) $mahasiswa->kegiatan()->sum('durasi_jam'),
        ];

        return view('admin.mahasiswa.show', compact('mahasiswa', 'stats'));
    }

    /**
     * Assign / update dosen pembimbing (akademik / lapangan) untuk KP terbaru mahasiswa.
     */
    public function assignDosen(Request $request, User $mahasiswa)
    {
        $request->validate([
            'dosen_id'            => ['required', 'exists:users,id'],
            'jenis_pembimbingan'  => ['required', 'in:akademik,lapangan'],
        ]);

        // KP terbaru (bukan sembarang first)
        $kerjaPraktek = $mahasiswa->kerjaPraktek()->latest('created_at')->first();

        if (!$kerjaPraktek) {
            return back()->with('error', 'Mahasiswa belum memiliki data KP.');
        }

        DosenPembimbing::updateOrCreate(
            [
                'kerja_praktek_id'   => $kerjaPraktek->id,
                'jenis_pembimbingan' => $request->jenis_pembimbingan,
            ],
            [
                'dosen_id'  => $request->dosen_id,
                'is_active' => true,
            ]
        );

        return back()->with('success', 'Dosen pembimbing berhasil ditugaskan.');
    }
    
}
