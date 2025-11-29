<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KerjaPraktek;
use App\Models\DosenPenguji;
use App\Models\TempatMagang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $role   = $request->get('role'); // null / '' = semua role

        // Statistik per-role (opsional untuk cards ringkasan di view)
        $roleStats = [
            'mahasiswa'         => User::where('role', User::ROLE_MAHASISWA)->count(),
            'admin_dosen'       => User::where('role', User::ROLE_ADMIN_DOSEN)->count(),
            'superadmin'        => User::where('role', User::ROLE_SUPERADMIN)->count(),
            'pengawas_lapangan' => User::where('role', User::ROLE_PENGAWAS_LAPANGAN)->count(),
        ];

        // Query dasar + pencarian
        $base = User::query()
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%")
                       ->orWhere('npm', 'like', "%{$search}%");
                });
            })
            ->orderBy('name');

        // Jika memilih role tertentu → single table
        if (!empty($role)) {
            $users = (clone $base)
                ->where('role', $role)
                ->paginate(15)
                ->withQueryString();

            return view('superadmin.users.index', [
                'role'      => $role,
                'search'    => $search,
                'users'     => $users,
                'roleStats' => $roleStats,
            ]);
        }

        // Jika "Semua Role" → empat tabel terpisah, masing-masing punya page param sendiri
        $usersByRole = [
            'superadmin'        => (clone $base)->where('role', User::ROLE_SUPERADMIN)
                                        ->paginate(10, ['*'], 'su_page')->withQueryString(),
            'admin_dosen'       => (clone $base)->where('role', User::ROLE_ADMIN_DOSEN)
                                        ->paginate(10, ['*'], 'ad_page')->withQueryString(),
            'pengawas_lapangan' => (clone $base)->where('role', User::ROLE_PENGAWAS_LAPANGAN)
                                        ->paginate(10, ['*'], 'pl_page')->withQueryString(),
            'mahasiswa'         => (clone $base)->where('role', User::ROLE_MAHASISWA)
                                        ->paginate(10, ['*'], 'mhs_page')->withQueryString(),
        ];

        return view('superadmin.users.index', [
            'role'        => null,
            'search'      => $search,
            'usersByRole' => $usersByRole,
            'roleStats'   => $roleStats,
        ]);
    }

    public function create()
    {
        return view('superadmin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:mahasiswa,admin_dosen,superadmin,pengawas_lapangan',
            'npm'      => 'required_if:role,mahasiswa|nullable|string|unique:users,npm',
            'nip'      => 'required_if:role,admin_dosen,superadmin|nullable|string|unique:users,nip',
            'phone'    => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'tempat_magang_id' => 'nullable|exists:tempat_magang,id',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'npm'      => $request->npm,
            'nip'      => $request->nip,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Jika role pengawas_lapangan dan ada tempat magang_id, buat relasi otomatis
        if ($request->role === 'pengawas_lapangan' && $request->filled('tempat_magang_id')) {
            \App\Models\PengawasTempatMagang::create([
                'pengawas_id' => $user->id,
                'tempat_magang_id' => $request->tempat_magang_id,
                'is_active' => true,
                'jabatan_pengawas' => 'Pengawas Lapangan',
                'deskripsi_tugas' => 'Pengawas lapangan untuk tempat magang ini',
            ]);
        }

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load(['kerjaPraktek.tempatMagang', 'bimbingan', 'kegiatan']);
        return view('superadmin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'role'      => 'required|in:mahasiswa,admin_dosen,superadmin,pengawas_lapangan',
            'npm'       => 'required_if:role,mahasiswa|nullable|string|unique:users,npm,' . $user->id,
            'nip'       => 'required_if:role,admin_dosen,superadmin|nullable|string|unique:users,nip,' . $user->id,
            'phone'     => 'nullable|string',
            'is_active' => 'boolean',
        ];

        // Add tempat_magang_id validation for pengawas_lapangan
        if ($request->role === 'pengawas_lapangan') {
            $rules['tempat_magang_id'] = 'nullable|exists:tempat_magang,id';
        }

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $request->validate($rules);

        $data = $request->only(['name', 'email', 'role', 'npm', 'nip', 'phone', 'is_active']);

        // Include tempat_magang_id if provided
        if ($request->has('tempat_magang_id')) {
            $data['tempat_magang_id'] = $request->tempat_magang_id;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Handle PengawasTempatMagang relationship
        if ($request->role === 'pengawas_lapangan' && $request->filled('tempat_magang_id')) {
            // Update or create relationship
            \App\Models\PengawasTempatMagang::updateOrCreate(
                ['pengawas_id' => $user->id],
                [
                    'tempat_magang_id' => $request->tempat_magang_id,
                    'is_active' => true,
                    'jabatan_pengawas' => 'Pengawas Lapangan',
                    'deskripsi_tugas' => 'Pengawas lapangan untuk tempat magang ini',
                ]
            );
        } elseif ($request->role !== 'pengawas_lapangan') {
            // Remove relationship if role changed from pengawas_lapangan
            \App\Models\PengawasTempatMagang::where('pengawas_id', $user->id)->delete();
        }

        return back()->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        // Cek jika superadmin mencoba menonaktifkan akun sendiri
        if (auth()->user()->role === User::ROLE_SUPERADMIN && $user->id === auth()->id()) {
            return back()->with('error', 'Akun anda tidak bisa di nonaktifkan karena anda adalah superadmin.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User berhasil {$status}.");
    }

    /**
     * Hapus data KP yang ditolak untuk mahasiswa tertentu.
     * Hanya untuk status 'ditolak'.
     */
    public function destroyKP(User $user, KerjaPraktek $kerjaPraktek)
    {
        $this->authorize('delete', $kerjaPraktek);

        // Pastikan KP milik user ini
        if ($kerjaPraktek->mahasiswa_id !== $user->id) {
            return back()->with('error', 'KP tidak ditemukan untuk user ini.');
        }

        // Hanya boleh hapus jika status ditolak
        if ($kerjaPraktek->status !== KerjaPraktek::STATUS_DITOLAK) {
            return back()->with('error', 'Hanya KP yang ditolak yang dapat dihapus.');
        }

        // Hapus file terkait jika ada
        if ($kerjaPraktek->file_krs) {
            Storage::disk('public')->delete($kerjaPraktek->file_krs);
        }
        if ($kerjaPraktek->file_laporan) {
            Storage::disk('public')->delete($kerjaPraktek->file_laporan);
        }
        if ($kerjaPraktek->file_kartu_implementasi) {
            Storage::disk('public')->delete($kerjaPraktek->file_kartu_implementasi);
        }

        // Hapus catatan terkait (notifikasi, dll.)
        $kerjaPraktek->notifikasi()->delete();
        // Jika ada bimbingan/kegiatan kosong, hapus juga (opsional)
        // $kerjaPraktek->bimbingan()->delete(); // Jika diperlukan

        // Hapus KP
        $kerjaPraktek->delete();

        return back()->with('success', 'Data KP yang ditolak berhasil dihapus. Mahasiswa dapat mengajukan ulang.');
    }

    /**
     * Index Data Dosen Pembimbing.
     */
    public function indexDosenPembimbing(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $dosen = User::where('role', User::ROLE_ADMIN_DOSEN)
            ->when($search !== '', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('superadmin.dosen-pembimbing.index', compact('dosen', 'search'));
    }

    /**
     * Show detail dosen pembimbing dengan mahasiswa yang dibimbingnya.
     */
    public function showDosenPembimbing(User $user)
    {
        // Pastikan user adalah dosen
        if ($user->role !== User::ROLE_ADMIN_DOSEN) {
            abort(404);
        }

        // Ambil mahasiswa yang dibimbing oleh dosen ini
        $mahasiswa = KerjaPraktek::with(['mahasiswa', 'tempatMagang'])
            ->whereHas('dosenPembimbing', function($q) use ($user) {
                $q->where('dosen_id', $user->id)
                  ->where('jenis_pembimbingan', 'akademik');
            })
            ->get()
            ->groupBy('mahasiswa_id')
            ->map(function($kps) {
                $mahasiswa = $kps->first()->mahasiswa;
                // Set kpTerbaru ke KP yang memiliki dosen pembimbing (terbaru jika multiple)
                $mahasiswa->kpTerbaru = $kps->sortByDesc('created_at')->first();
                return $mahasiswa;
            })
            ->sortBy('name');

        // Ambil semua mahasiswa yang belum memiliki dosen pembimbing akademik
        $mahasiswaAvailable = User::where('role', User::ROLE_MAHASISWA)
            ->whereDoesntHave('kerjaPraktek.dosenPembimbing', function($q) {
                $q->where('jenis_pembimbingan', 'akademik');
            })
            ->with('kpTerbaru')
            ->orderBy('name')
            ->get();

        return view('superadmin.dosen-pembimbing.show', compact('user', 'mahasiswa', 'mahasiswaAvailable'));
    }

    /**
     * Assign mahasiswa ke dosen pembimbing.
     */
    public function assignMahasiswaToDosen(Request $request, User $user)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
        ]);

        $mahasiswa = User::findOrFail($request->mahasiswa_id);

        // Pastikan mahasiswa memiliki KP
        $kp = $mahasiswa->kerjaPraktek()->latest()->first();
        if (!$kp) {
            return back()->with('error', 'Mahasiswa belum memiliki data KP.');
        }

        // Cek apakah sudah ada dosen pembimbing akademik
        $existing = $kp->dosenPembimbing()->where('jenis_pembimbingan', 'akademik')->first();
        if ($existing) {
            return back()->with('error', 'Mahasiswa sudah memiliki dosen pembimbing akademik.');
        }

        // Assign dosen pembimbing
        \App\Models\DosenPembimbing::create([
            'kerja_praktek_id' => $kp->id,
            'dosen_id' => $user->id,
            'jenis_pembimbingan' => 'akademik',
            'is_active' => true,
        ]);

        // Kirim notifikasi ke dosen pembimbing
        \App\Services\NotificationService::sendToUser(
            $user->id,
            'Mahasiswa Baru Ditugaskan',
            "Mahasiswa {$mahasiswa->name} ({$mahasiswa->npm}) telah ditugaskan sebagai mahasiswa bimbingan Anda.",
            'info',
            $kp->id,
            route('admin.kerja-praktek.show', $kp)
        );

        return back()->with('success', 'Mahasiswa berhasil ditugaskan ke dosen pembimbing.');
    }

    /**
     * Index Data Dosen Penguji.
     */
    public function indexDosenPenguji(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $dosen = User::where('role', User::ROLE_ADMIN_DOSEN)
            ->when($search !== '', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('superadmin.dosen-penguji.index', compact('dosen', 'search'));
    }

    /**
     * Show detail dosen penguji dengan mahasiswa yang dinilainya.
     */
    public function showDosenPenguji(User $user)
    {
        // Pastikan user adalah dosen
        if ($user->role !== User::ROLE_ADMIN_DOSEN) {
            abort(404);
        }

        // Ambil mahasiswa yang ditugaskan ke dosen ini sebagai penguji
        $mahasiswa = DosenPenguji::with(['kerjaPraktek.mahasiswa', 'kerjaPraktek.tempatMagang'])
            ->where('dosen_id', $user->id)
            ->where('is_active', true)
            ->get()
            ->map(function($dosenPenguji) {
                $mahasiswa = $dosenPenguji->kerjaPraktek->mahasiswa;
                $mahasiswa->kpTerbaru = $dosenPenguji->kerjaPraktek;
                return $mahasiswa;
            })
            ->sortBy('name');

        // Ambil mahasiswa yang sudah mengajukan KP (ada data KP) dan belum ditugaskan ke dosen penguji ini
        // Tapi jangan tampilkan mahasiswa yang sudah dibimbing oleh dosen ini sebagai dosen pembimbing
        // Dan jangan tampilkan mahasiswa yang sudah memiliki dosen penguji lainnya
        $mahasiswaAvailable = User::where('role', User::ROLE_MAHASISWA)
            ->whereHas('kerjaPraktek') // Pastikan sudah ada data KP
            ->whereDoesntHave('kerjaPraktek.dosenPenguji', function($q) {
                $q->where('is_active', true);
            })
            ->whereDoesntHave('kerjaPraktek.dosenPembimbing', function($q) use ($user) {
                $q->where('dosen_id', $user->id)
                  ->where('jenis_pembimbingan', 'akademik')
                  ->where('is_active', true);
            })
            ->with(['kpTerbaru.tempatMagang'])
            ->orderBy('name')
            ->get();

        return view('superadmin.dosen-penguji.show', compact('user', 'mahasiswa', 'mahasiswaAvailable'));
    }

    /**
     * Assign mahasiswa ke dosen penguji.
     */
    public function assignMahasiswaToDosenPenguji(Request $request, User $user)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
        ]);

        $mahasiswa = User::findOrFail($request->mahasiswa_id);

        // Pastikan mahasiswa memiliki KP
        $kp = $mahasiswa->kerjaPraktek()->latest()->first();
        if (!$kp) {
            return back()->with('error', 'Mahasiswa belum memiliki data KP.');
        }

        // Cek apakah mahasiswa sudah ditugaskan ke dosen penguji ini
        $existing = DosenPenguji::where('kerja_praktek_id', $kp->id)
            ->where('dosen_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($existing) {
            return back()->with('error', 'Mahasiswa ini sudah ditugaskan ke dosen penguji ini.');
        }

        // Buat relasi dosen penguji
        DosenPenguji::create([
            'kerja_praktek_id' => $kp->id,
            'dosen_id' => $user->id,
            'is_active' => true,
        ]);

        // Kirim notifikasi ke dosen penguji
        \App\Services\NotificationService::sendToUser(
            $user->id,
            'Mahasiswa Baru untuk Dinilai',
            "Mahasiswa {$mahasiswa->name} ({$mahasiswa->npm}) telah ditugaskan untuk dinilai oleh Anda.",
            'info',
            $kp->id,
            route('admin.kerja-praktek.show', $kp)
        );

        return back()->with('success', 'Mahasiswa berhasil ditugaskan ke dosen penguji.');
    }

    /**
     * List semua data KP untuk superadmin.
     */
    public function indexKP(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status'); // filter status
        $tempat_magang_id = $request->get('tempat_magang_id'); // filter tempat magang

        // Map "berjalan" to "sedang_kp" for filtering
        $statusMap = [
            'berjalan' => 'sedang_kp',
        ];
        $filterStatus = $statusMap[$status] ?? $status;

        $kerjaPrakteks = KerjaPraktek::with(['mahasiswa', 'tempatMagang', 'dosenPembimbing.dosen', 'dosenPenguji.dosen'])
            ->when($search !== '', function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                    ->orWhere('npm', 'like', "%{$search}%");
                })
                ->orWhere('judul_kp', 'like', "%{$search}%")
                ->orWhereHas('tempatMagang', function ($qq) use ($search) {
                    $qq->where('nama_perusahaan', 'like', "%{$search}%");
                });
            })
            ->when($filterStatus, function ($q) use ($filterStatus) {
                $q->where('status', $filterStatus);
            })
            ->when($tempat_magang_id, function ($q) use ($tempat_magang_id) {
                $q->where('tempat_magang_id', $tempat_magang_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Get all dosen for dropdowns
        $dosen = User::where('role', User::ROLE_ADMIN_DOSEN)->orderBy('name')->get();

        // Get all active tempat magang for dropdown
        $tempatMagang = \App\Models\TempatMagang::active()->orderBy('nama_perusahaan')->get();

        // Fetch unread notifications for superadmin related to rejected proposals
        $notifications = \App\Models\Notifikasi::where('type', 'warning')
            ->where('is_read', false)
            ->where('title', 'Proposal KP Ditolak')
            ->orderByDesc('created_at')
            ->get();

        // Calculate duplicate statistics
        $totalKPs = KerjaPraktek::count();
        $duplicateTitles = KerjaPraktek::select('judul_kp')
            ->groupBy('judul_kp')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('judul_kp');

        $duplicateKPs = 0;
        foreach ($duplicateTitles as $title) {
            $duplicateKPs += KerjaPraktek::where('judul_kp', $title)->count() - 1; // count duplicates, not originals
        }
        $duplicatePercentage = $totalKPs > 0 ? round(($duplicateKPs / $totalKPs) * 100, 2) : 0;

        // Add duplicate info to each KP
        $kerjaPrakteks->each(function($kp) {
            $kp->duplicate_info = $kp->getDuplicateInfo();
        });

        return view('superadmin.kerja-praktek.index', compact('kerjaPrakteks', 'search', 'status', 'tempat_magang_id', 'dosen', 'tempatMagang', 'notifications', 'duplicateTitles', 'duplicatePercentage'));
    }

    /**
     * Show form edit KP (hanya untuk status pengajuan).
     */
    public function editKP(KerjaPraktek $kerjaPraktek)
    {
        // Pastikan hanya bisa edit jika status pengajuan
        if ($kerjaPraktek->status !== KerjaPraktek::STATUS_PENGAJUAN) {
            return back()->with('error', 'Data KP hanya dapat diedit jika status masih pengajuan.');
        }

        $tempatMagang = \App\Models\TempatMagang::where('is_active', true)->orderBy('nama_perusahaan')->get();

        return view('superadmin.kerja-praktek.edit', compact('kerjaPraktek', 'tempatMagang'));
    }

    /**
     * Update data KP (hanya untuk status pengajuan).
     */
    public function updateKP(Request $request, KerjaPraktek $kerjaPraktek)
    {
        // Pastikan hanya bisa update jika status pengajuan
        if ($kerjaPraktek->status !== KerjaPraktek::STATUS_PENGAJUAN) {
            return back()->with('error', 'Data KP hanya dapat diedit jika status masih pengajuan.');
        }

        $request->validate([
            'judul_kp' => 'required|string|max:255',
            'tempat_magang_id' => 'required|exists:tempat_magang,id',
        ]);

        $kerjaPraktek->update([
            'judul_kp' => $request->judul_kp,
            'tempat_magang_id' => $request->tempat_magang_id,
        ]);

        return redirect()->route('superadmin.kerja-praktek.index')
            ->with('success', 'Data KP berhasil diupdate.');
    }

    /**
     * Assign dosen pembimbing ke KP.
     */
    public function assignDosenPembimbing(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
        ]);

        $dosen = User::findOrFail($request->dosen_id);

        // Pastikan dosen adalah admin_dosen
        if ($dosen->role !== User::ROLE_ADMIN_DOSEN) {
            return back()->with('error', 'User yang dipilih bukan dosen.');
        }

        // Cek apakah sudah ada dosen pembimbing akademik
        $existing = $kerjaPraktek->dosenPembimbing()->where('jenis_pembimbingan', 'akademik')->first();
        if ($existing) {
            return back()->with('error', 'KP ini sudah memiliki dosen pembimbing akademik.');
        }

        // Assign dosen pembimbing
        \App\Models\DosenPembimbing::create([
            'kerja_praktek_id' => $kerjaPraktek->id,
            'dosen_id' => $dosen->id,
            'jenis_pembimbingan' => 'akademik',
            'is_active' => true,
        ]);

        // Kirim notifikasi ke dosen pembimbing
        \App\Services\NotificationService::sendToUser(
            $dosen->id,
            'Mahasiswa Baru Ditugaskan',
            "Mahasiswa {$kerjaPraktek->mahasiswa->name} ({$kerjaPraktek->mahasiswa->npm}) telah ditugaskan sebagai mahasiswa bimbingan Anda.",
            'info',
            $kerjaPraktek->id,
            route('admin.kerja-praktek.show', $kerjaPraktek)
        );

        return back()->with('success', 'Dosen pembimbing berhasil ditugaskan.');
    }

    /**
     * Assign dosen penguji ke KP.
     */
    public function assignDosenPenguji(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
        ]);

        $dosen = User::findOrFail($request->dosen_id);

        // Pastikan dosen adalah admin_dosen
        if ($dosen->role !== User::ROLE_ADMIN_DOSEN) {
            return back()->with('error', 'User yang dipilih bukan dosen.');
        }

        // Cek apakah sudah ada dosen penguji
        $existing = DosenPenguji::where('kerja_praktek_id', $kerjaPraktek->id)
            ->where('is_active', true)
            ->first();

        if ($existing) {
            return back()->with('error', 'KP ini sudah memiliki dosen penguji.');
        }

        // Assign dosen penguji
        DosenPenguji::create([
            'kerja_praktek_id' => $kerjaPraktek->id,
            'dosen_id' => $dosen->id,
            'is_active' => true,
        ]);

        // Kirim notifikasi ke dosen penguji
        \App\Services\NotificationService::sendToUser(
            $dosen->id,
            'Mahasiswa Baru untuk Dinilai',
            "Mahasiswa {$kerjaPraktek->mahasiswa->name} ({$kerjaPraktek->mahasiswa->npm}) telah ditugaskan untuk dinilai oleh Anda.",
            'info',
            $kerjaPraktek->id,
            route('admin.kerja-praktek.show', $kerjaPraktek)
        );

        return back()->with('success', 'Dosen penguji berhasil ditugaskan.');
    }

    /**
     * Index verifikasi instansi mandiri.
     */
    public function indexVerifikasiInstansi(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status');

        $query = KerjaPraktek::with('mahasiswa')
            ->where('pilihan_tempat', 3) // Hanya yang mandiri
            ->whereHas('mahasiswa'); // Pastikan mahasiswa masih ada

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('tempat_magang_sendiri', 'like', "%{$search}%")
                  ->orWhere('bidang_usaha_sendiri', 'like', "%{$search}%")
                  ->orWhere('alamat_tempat_sendiri', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('npm', 'like', "%{$search}%");
                  });
            });
        }

        // Filter status: default hanya yang belum diverifikasi jika tidak ada filter
        if ($status) {
            $query->where('status', $status);
        } else {
            $query->where('instansi_verified', false);
        }

        // Filter berdasarkan instansi_verified jika ada
        if ($request->filled('instansi_verified')) {
            $query->where('instansi_verified', $request->boolean('instansi_verified'));
        }

        $kerjaPrakteks = $query->orderByDesc('created_at')->paginate(15);

        return view('superadmin.verifikasi-instansi.index', compact('kerjaPrakteks', 'search', 'status'));
    }

    /**
     * Approve instansi mandiri.
     */
    public function approveInstansi(KerjaPraktek $kerjaPraktek)
    {
        // Pastikan hanya untuk pilihan_tempat = 3
        if ($kerjaPraktek->pilihan_tempat !== 3) {
            return back()->with('error', 'Data ini bukan instansi mandiri.');
        }

        // Pastikan status masih pengajuan
        if ($kerjaPraktek->status !== KerjaPraktek::STATUS_PENGAJUAN) {
            return back()->with('error', 'Instansi ini sudah diproses.');
        }

        // Cek apakah tempat magang dengan nama yang sama sudah ada
        $existingTempatMagang = TempatMagang::where('nama_perusahaan', $kerjaPraktek->tempat_magang_sendiri)->first();

        if ($existingTempatMagang) {
            // Jika sudah ada, gunakan yang existing dan update kuota jika perlu
            $tempatMagangId = $existingTempatMagang->id;
            // Update kuota jika kuota existing lebih kecil
            if ($existingTempatMagang->kuota_mahasiswa < $kerjaPraktek->kuota_mahasiswa_sendiri) {
                $existingTempatMagang->update(['kuota_mahasiswa' => $kerjaPraktek->kuota_mahasiswa_sendiri]);
            }
        } else {
            // Jika belum ada, buat tempat magang baru
            $tempatMagang = TempatMagang::create([
                'nama_perusahaan' => $kerjaPraktek->tempat_magang_sendiri,
                'bidang_usaha' => $kerjaPraktek->bidang_usaha_sendiri,
                'alamat' => $kerjaPraktek->alamat_tempat_sendiri,
                'email_perusahaan' => $kerjaPraktek->email_perusahaan_sendiri,
                'telepon_perusahaan' => $kerjaPraktek->telepon_perusahaan_sendiri,
                'kontak_person' => $kerjaPraktek->kontak_tempat_sendiri,
                'kuota_mahasiswa' => $kerjaPraktek->kuota_mahasiswa_sendiri,
                'deskripsi' => $kerjaPraktek->deskripsi_perusahaan_sendiri,
                'is_active' => true,
                'tanggal_mulai' => $kerjaPraktek->tanggal_mulai,
            ]);
            $tempatMagangId = $tempatMagang->id;
        }

        // Update kerja praktek: hanya set instansi_verified, jangan ubah status KP
        $kerjaPraktek->update([
            'instansi_verified' => true,
            'pilihan_tempat' => 1, // Ubah ke pilihan dari prodi
            'tempat_magang_id' => $tempatMagangId,
            // Kosongkan field mandiri karena sudah menjadi tempat magang resmi
            'tempat_magang_sendiri' => null,
            'bidang_usaha_sendiri' => null,
            'alamat_tempat_sendiri' => null,
            'email_perusahaan_sendiri' => null,
            'telepon_perusahaan_sendiri' => null,
            'kontak_tempat_sendiri' => null,
            'kuota_mahasiswa_sendiri' => null,
            'deskripsi_perusahaan_sendiri' => null,
            'tanggal_mulai' => null,
        ]);

        // Kirim notifikasi ke mahasiswa
        \App\Services\NotificationService::sendToUser(
            $kerjaPraktek->mahasiswa_id,
            'Instansi Mandiri Diverifikasi',
            "Instansi magang mandiri Anda '{$kerjaPraktek->tempat_magang_sendiri}' telah diverifikasi oleh superadmin dan terdaftar sebagai tempat magang resmi. Anda dapat melanjutkan proses pengajuan KP.",
            'success',
            $kerjaPraktek->id,
            route('mahasiswa.kerja-praktek.index')
        );

        return back()->with('success', 'Instansi mandiri berhasil diverifikasi dan terdaftar sebagai tempat magang.');
    }

    /**
     * Reject instansi mandiri.
     */
    public function rejectInstansi(KerjaPraktek $kerjaPraktek)
    {
        // Pastikan hanya untuk pilihan_tempat = 3
        if ($kerjaPraktek->pilihan_tempat !== 3) {
            return back()->with('error', 'Data ini bukan instansi mandiri.');
        }

        // Pastikan status masih pengajuan
        if ($kerjaPraktek->status !== KerjaPraktek::STATUS_PENGAJUAN) {
            return back()->with('error', 'Instansi ini sudah diproses.');
        }

        $kerjaPraktek->update(['status' => KerjaPraktek::STATUS_DITOLAK]);

        // Kirim notifikasi ke mahasiswa
        \App\Services\NotificationService::sendToUser(
            $kerjaPraktek->mahasiswa_id,
            'Instansi Mandiri Ditolak',
            "Instansi magang mandiri Anda '{$kerjaPraktek->tempat_magang_sendiri}' ditolak. Silakan ajukan ulang atau pilih dari daftar instansi yang tersedia.",
            'warning',
            $kerjaPraktek->id,
            route('mahasiswa.kerja-praktek.index')
        );

        return back()->with('success', 'Instansi mandiri berhasil ditolak.');
    }
}
