<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempatMagang;
use App\Models\KerjaPraktek;
use App\Models\PengawasTempatMagang;
use App\Models\User;
use App\Services\NotificationService;

class TempatMagangController extends Controller
{
    public function index(Request $request)
{
    // Hitung "terpakai" hanya dari status disetujui/sedang_kp, tapi exclude yang sudah selesai (nilai_akhir dan file_laporan ada)
    $query = TempatMagang::withCount([
        'kerjaPraktek as terpakai_count' => function ($q) {
            $q->where(function ($qq) {
                $qq->where('status', 'disetujui')
                   ->orWhere(function ($qqq) {
                       $qqq->where('status', 'sedang_kp')
                           ->where(function ($qqqq) {
                               $qqqq->whereNull('nilai_akhir')
                                     ->orWhereNull('file_laporan');
                           });
                   });
            });
        },
        'kerjaPraktek' // total semua KP (untuk informasi tambahan bila perlu)
    ]);

    // Search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nama_perusahaan', 'like', "%{$search}%")
              ->orWhere('bidang_usaha', 'like', "%{$search}%")
              ->orWhere('alamat', 'like', "%{$search}%");
        });
    }

    // Filter status (pakai filled & whitelist agar tidak salah filter saat kosong)
    if (in_array($request->status, ['active','inactive'], true)) {
        $query->where('is_active', $request->status === 'active');
    }

    // Filter bidang usaha
    if ($request->filled('bidang_usaha')) {
        $query->where('bidang_usaha', 'like', "%{$request->bidang_usaha}%");
    }

    $tempatMagang = $query->orderBy('nama_perusahaan')->paginate(15);

    // Statistik (konsisten dengan "terpakai")
    $aktifBase = TempatMagang::active()
        ->withCount(['kerjaPraktek as terpakai_count' => fn($q)=>$q->whereIn('status',['disetujui','sedang_kp'])]);

    $stats = [
        'total_tempat'   => TempatMagang::count(),
        'tempat_aktif'   => TempatMagang::where('is_active', true)->count(),
        'total_kuota'    => TempatMagang::where('is_active', true)->sum('kuota_mahasiswa'),
        'tempat_terpakai'=> $aktifBase->get()->filter(fn($t)=>$t->terpakai_count > 0)->count(),
    ];

    // Filter pilihan bidang usaha
    $bidangUsaha = TempatMagang::distinct()->pluck('bidang_usaha')->filter()->sort();

    return view('superadmin.tempat-magang.index', compact('tempatMagang', 'stats', 'bidangUsaha'));
}


    public function create()
    {
        return view('superadmin.tempat-magang.create');
    }

    public function store(Request $request)
    {
        $request->merge(['pengawas_id' => $request->pengawas_id === '' ? null : $request->pengawas_id]);

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak_person' => 'required|string|max:255',
            'email_perusahaan' => 'required|email|max:255',
            'telepon_perusahaan' => 'required|string|max:20',
            'bidang_usaha' => 'required|string|max:255',
            'kuota_mahasiswa' => 'required|integer|min:1|max:50',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
            'pengawas_id' => 'nullable|exists:users,id',
        ]);

        $tempatMagang = TempatMagang::create($request->except('pengawas_id'));

        // Assign pengawas if provided
        if ($request->pengawas_id) {
            User::where('id', $request->pengawas_id)->update(['tempat_magang_id' => $tempatMagang->id]);
        }

        // Send notification to admin dosen about new tempat magang
        NotificationService::sendToRole(
            'admin_dosen',
            'Tempat Magang Baru Ditambahkan',
            "Tempat magang baru '{$request->nama_perusahaan}' telah ditambahkan ke sistem.",
            'info'
        );

        return redirect()->route('superadmin.tempat-magang.index')
                        ->with('success', 'Tempat magang berhasil ditambahkan.');
    }

    public function show(TempatMagang $tempatMagang)
    {
        $tempatMagang->load(['kerjaPraktek.mahasiswa']);
        
        // Get statistics for this tempat magang
        $stats = [
            'total_mahasiswa' => $tempatMagang->kerjaPraktek->count(),
            'sedang_kp' => $tempatMagang->kerjaPraktek->where('status', 'sedang_kp')->count(),
            'selesai_kp' => $tempatMagang->kerjaPraktek->where('status', 'selesai')->count(),
            'rata_nilai' => $tempatMagang->kerjaPraktek->whereNotNull('nilai_akhir')->avg('nilai_akhir'),
        ];

        return view('superadmin.tempat-magang.show', compact('tempatMagang', 'stats'));
    }

    public function edit(TempatMagang $tempatMagang)
    {
        return view('superadmin.tempat-magang.edit', compact('tempatMagang'));
    }

    public function update(Request $request, TempatMagang $tempatMagang)
    {
        $request->merge(['pengawas_id' => $request->pengawas_id === '' ? null : $request->pengawas_id]);

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak_person' => 'required|string|max:255',
            'email_perusahaan' => 'required|email|max:255',
            'telepon_perusahaan' => 'required|string|max:20',
            'bidang_usaha' => 'required|string|max:255',
            'kuota_mahasiswa' => 'required|integer|min:1|max:50',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
            'pengawas_id' => 'nullable|exists:users,id',
        ]);

        $oldPengawasId = $tempatMagang->pengawas->first()->id ?? null;
        $wasInactive = !$tempatMagang->is_active;
        $tempatMagang->update($request->except('pengawas_id'));

        // Handle pengawas assignment
        if ($oldPengawasId && $oldPengawasId != $request->pengawas_id) {
            User::where('id', $oldPengawasId)->update(['tempat_magang_id' => null]);
        }
        if ($request->pengawas_id) {
            User::where('id', $request->pengawas_id)->update(['tempat_magang_id' => $tempatMagang->id]);
        }

        // Send notification if tempat magang is reactivated
        if ($wasInactive && $request->is_active) {
            NotificationService::sendToRole(
                'mahasiswa',
                'Tempat Magang Tersedia Kembali',
                "Tempat magang '{$tempatMagang->nama_perusahaan}' kini tersedia kembali untuk dipilih.",
                'info'
            );
        }

        return redirect()->route('superadmin.tempat-magang.index')
                        ->with('success', 'Tempat magang berhasil diupdate.');
    }

    public function destroy(TempatMagang $tempatMagang)
    {
        // Check if ada mahasiswa yang sedang KP di tempat ini
        $activeKP = $tempatMagang->kerjaPraktek()->whereIn('status', ['disetujui', 'sedang_kp'])->count();
        
        if ($activeKP > 0) {
            return back()->with('error', 'Tidak dapat menghapus tempat magang yang masih memiliki mahasiswa aktif.');
        }

        $nama = $tempatMagang->nama_perusahaan;
        $tempatMagang->delete();

        return back()->with('success', "Tempat magang '{$nama}' berhasil dihapus.");
    }

    public function toggleStatus(TempatMagang $tempatMagang)
    {
        // Check if ada mahasiswa yang sedang KP di tempat ini sebelum nonaktifkan
        if ($tempatMagang->is_active) {
            $activeKP = $tempatMagang->kerjaPraktek()->whereIn('status', ['disetujui', 'sedang_kp'])->count();
            
            if ($activeKP > 0) {
                return back()->with('error', 'Tidak dapat menonaktifkan tempat magang yang masih memiliki mahasiswa aktif.');
            }
        }

        $tempatMagang->update(['is_active' => !$tempatMagang->is_active]);

        $status = $tempatMagang->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        // Send notification
        if ($tempatMagang->is_active) {
            NotificationService::sendToRole(
                'mahasiswa',
                'Tempat Magang Tersedia',
                "Tempat magang '{$tempatMagang->nama_perusahaan}' kini tersedia untuk dipilih.",
                'info'
            );
        }

        return back()->with('success', "Tempat magang berhasil {$status}.");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:tempat_magang,id'
        ]);

        $tempatMagang = TempatMagang::whereIn('id', $request->selected_items);

        switch ($request->action) {
            case 'activate':
                $tempatMagang->update(['is_active' => true]);
                $message = 'Tempat magang terpilih berhasil diaktifkan.';
                break;

            case 'deactivate':
                // Check for active KP
                $activeCount = KerjaPraktek::whereIn('tempat_magang_id', $request->selected_items)
                                         ->whereIn('status', ['disetujui', 'sedang_kp'])
                                         ->count();
                
                if ($activeCount > 0) {
                    return back()->with('error', 'Beberapa tempat magang masih memiliki mahasiswa aktif dan tidak dapat dinonaktifkan.');
                }

                $tempatMagang->update(['is_active' => false]);
                $message = 'Tempat magang terpilih berhasil dinonaktifkan.';
                break;

            case 'delete':
                // Check for active KP
                $activeCount = KerjaPraktek::whereIn('tempat_magang_id', $request->selected_items)
                                         ->whereIn('status', ['disetujui', 'sedang_kp'])
                                         ->count();
                
                if ($activeCount > 0) {
                    return back()->with('error', 'Beberapa tempat magang masih memiliki mahasiswa aktif dan tidak dapat dihapus.');
                }

                $tempatMagang->delete();
                $message = 'Tempat magang terpilih berhasil dihapus.';
                break;
        }

        return back()->with('success', $message);
    }
}