<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KerjaPraktek;
use App\Models\DosenPenguji;
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

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $request->validate($rules);

        $data = $request->only(['name', 'email', 'role', 'npm', 'nip', 'phone', 'is_active']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

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

        $kerjaPrakteks = KerjaPraktek::with(['mahasiswa', 'tempatMagang'])
            ->when($search !== '', function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                    ->orWhere('npm', 'like', "%{$search}%");
                })
                ->orWhere('judul_kp', 'like', "%{$search}%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Fetch unread notifications for superadmin related to rejected proposals
        $notifications = \App\Models\Notifikasi::where('type', 'warning')
            ->where('is_read', false)
            ->where('title', 'Proposal KP Ditolak')
            ->orderByDesc('created_at')
            ->get();

        return view('superadmin.kerja-praktek.index', compact('kerjaPrakteks', 'search', 'status', 'notifications'));
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
}
