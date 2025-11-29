<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\KerjaPraktek;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    public function index()
    {
        // KP aktif (apapun statusnya) milik mahasiswa login
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', auth()->id())->latest()->first();

        if (!$kerjaPraktek) {
            return redirect()
                ->route('mahasiswa.kerja-praktek.index')
                ->with('error', 'Anda belum memiliki kerja praktek.');
        }

        $kegiatan = Kegiatan::where('mahasiswa_id', auth()->id())
            ->orderByDesc('tanggal_kegiatan')
            ->paginate(15);

        return view('mahasiswa.kegiatan.index', compact('kegiatan', 'kerjaPraktek'));
    }

    public function create()
    {
        $kerjaPraktek = KerjaPraktek::where('mahasiswa_id', auth()->id())->latest()->first();

        if (!$kerjaPraktek || $kerjaPraktek->status !== KerjaPraktek::STATUS_SEDANG_KP) {
            return redirect()
                ->route('mahasiswa.kegiatan.index')
                ->with('error', 'Anda hanya bisa menambah kegiatan saat status KP sedang berlangsung.');
        }

        return view('mahasiswa.kegiatan.create', compact('kerjaPraktek'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal_kegiatan'   => ['required', 'date'],
            'durasi_jam'         => ['required', 'integer', 'min:1'],
            'deskripsi_kegiatan' => ['required', 'string', 'max:3000'],
            'file_dokumentasi'   => ['required', 'image', 'max:5120'],
        ]);

        // Pastikan mahasiswa punya KP berjalan
        $kp = $request->user()
            ->kerjaPraktek()            // relasi User->hasMany(KerjaPraktek::class)
            ->latest()
            ->first();

        if (!$kp) {
            return back()->with('error', 'Anda belum memiliki kerja praktek.');
        }

        if ($kp->status !== KerjaPraktek::STATUS_SEDANG_KP) {
            return back()->with('error', 'Status KP belum/sudah tidak sedang berlangsung.');
        }

        // SIMPAN KEGIATAN & simpan hasil create ke $kegiatan (PENTING)
        $kegiatan = $kp->kegiatan()->create([
            'mahasiswa_id'       => $request->user()->id,      // wajib diisi
            // kerja_praktek_id akan otomatis terisi oleh relasi $kp->kegiatan()
            'tanggal_kegiatan'   => $data['tanggal_kegiatan'],
            'durasi_jam'         => $data['durasi_jam'],
            'deskripsi_kegiatan' => $data['deskripsi_kegiatan'],
            'file_dokumentasi'   => $request->file('file_dokumentasi')->store('kegiatan-dokumentasi', 'public'),
        ]);

        // Kirim notifikasi ke dosen pembimbing akademik (jika ada)
        $dosenIds = $kp->dosenPembimbing()
            ->where('jenis_pembimbingan', 'akademik')
            ->pluck('dosen_id');

        if ($dosenIds->isNotEmpty()) {
            $tgl = $kegiatan->tanggal_kegiatan instanceof \Illuminate\Support\Carbon
                ? $kegiatan->tanggal_kegiatan->format('d M Y')
                : Carbon::parse($kegiatan->tanggal_kegiatan)->format('d M Y');

            foreach ($dosenIds as $dosenId) {
                Notifikasi::create([
                    'user_id'          => $dosenId,
                    'title'            => 'Kegiatan baru mahasiswa bimbingan',
                    'message'          => "{$kp->mahasiswa->name} menambahkan kegiatan pada {$tgl}.",
                    'type'             => 'info',
                    'kerja_praktek_id' => $kp->id,
                    // 'action_url'     => route('admin.mahasiswa.show', $kp->mahasiswa_id) // opsional
                ]);
            }
        }

        // Kirim notifikasi ke superadmin
        $tgl = $kegiatan->tanggal_kegiatan instanceof \Illuminate\Support\Carbon
            ? $kegiatan->tanggal_kegiatan->format('d M Y')
            : Carbon::parse($kegiatan->tanggal_kegiatan)->format('d M Y');

        \App\Services\NotificationService::sendToRole('superadmin', 'Kegiatan Baru Mahasiswa', "Mahasiswa {$kp->mahasiswa->name} ({$kp->mahasiswa->npm}) menambahkan kegiatan pada {$tgl} dengan deskripsi: {$kegiatan->deskripsi_kegiatan}", 'info', $kp->id, route('superadmin.kegiatan.index'));

        // Kirim notifikasi ke dosen pembimbing yang sudah ditugaskan
        if ($dosenIds->isNotEmpty()) {
            foreach ($dosenIds as $dosenId) {
                \App\Services\NotificationService::sendToUser(
                    $dosenId,
                    'Kegiatan Baru Mahasiswa Bimbingan',
                    "Mahasiswa {$kp->mahasiswa->name} menambahkan kegiatan pada {$tgl} dengan deskripsi: {$kegiatan->deskripsi_kegiatan}",
                    'info',
                    $kp->id,
                    route('admin.mahasiswa.show', $kp->mahasiswa_id)
                );
            }
        }

        return redirect()
            ->route('mahasiswa.kegiatan.index')
            ->with('success', 'Kegiatan berhasil disimpan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $this->authorize('view', $kegiatan);
        return view('mahasiswa.kegiatan.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        $this->authorize('update', $kegiatan);
        return view('mahasiswa.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $this->authorize('update', $kegiatan);

        $request->validate([
            'tanggal_kegiatan'   => ['required', 'date'],
            'deskripsi_kegiatan' => ['required', 'string', 'max:3000'],
            'durasi_jam'         => ['required', 'integer', 'min:1', 'max:12'],
            'file_dokumentasi'   => ['nullable', 'image', 'max:5120'], // 5MB
        ]);

        $data = $request->only(['tanggal_kegiatan', 'deskripsi_kegiatan', 'durasi_jam']);

        if ($request->hasFile('file_dokumentasi')) {
            // Hapus file lama
            if ($kegiatan->file_dokumentasi) {
                Storage::disk('public')->delete($kegiatan->file_dokumentasi);
            }
            $data['file_dokumentasi'] = $request->file('file_dokumentasi')->store('kegiatan-dokumentasi', 'public');
        }

        $kegiatan->update($data);

        return redirect()
            ->route('mahasiswa.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diupdate.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $this->authorize('delete', $kegiatan);

        if ($kegiatan->file_dokumentasi) {
            Storage::disk('public')->delete($kegiatan->file_dokumentasi);
        }

        $kegiatan->delete();

        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}
