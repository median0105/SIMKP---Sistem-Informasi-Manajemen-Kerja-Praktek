<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KerjaPraktek;
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
            'phone'    => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'tempat_magang_id' => 'nullable|exists:tempat_magang,id',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'npm'      => $request->npm,
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
            'phone'     => 'nullable|string',
            'is_active' => 'boolean',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $request->validate($rules);

        $data = $request->only(['name', 'email', 'role', 'npm', 'phone', 'is_active']);

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

        return view('superadmin.kerja-praktek.index', compact('kerjaPrakteks', 'search', 'status'));
    }
}
