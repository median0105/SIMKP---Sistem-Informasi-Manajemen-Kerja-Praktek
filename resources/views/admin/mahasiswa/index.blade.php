<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- === Statistik === --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Mahasiswa</p>
                            <p class="text-2xl font-semibold text-blue-600 mt-2">{{ $stats['total_mahasiswa'] ?? 0 }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Mahasiswa Bimbingan</p>
                            <p class="text-2xl font-semibold text-green-600 mt-2">{{ $stats['mahasiswa_bimbingan'] ?? 0 }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <i class="fas fa-chalkboard-teacher text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Sedang KP</p>
                            <p class="text-2xl font-semibold text-yellow-600 mt-2">{{ $stats['sedang_kp'] ?? 0 }}</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-3">
                            <i class="fas fa-play-circle text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Selesai KP</p>
                            <p class="text-2xl font-semibold text-gray-600 mt-2">{{ $stats['selesai_kp'] ?? 0 }}</p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <i class="fas fa-flag-checkered text-gray-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === Filter & Search === --}}
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari berdasarkan nama, NPM, atau email..."
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>

                    <div>
                        <select name="status_kp"
                                class="border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                            <option value="">Semua Status KP</option>
                            <option value="pengajuan"  {{ request('status_kp')==='pengajuan'  ? 'selected' : '' }}>Pengajuan</option>
                            <option value="disetujui"  {{ request('status_kp')==='disetujui'  ? 'selected' : '' }}>Disetujui</option>
                            <option value="sedang_kp"  {{ request('status_kp')==='sedang_kp'  ? 'selected' : '' }}>Sedang KP</option>
                            <option value="selesai"    {{ request('status_kp')==='selesai'    ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak"    {{ request('status_kp')==='ditolak'    ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-2 rounded-md font-medium">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        @if(request()->filled('search') || request()->filled('status_kp'))
                            <a href="{{ route('admin.mahasiswa.index') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md font-medium">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- =========================================================
                 A) LIST MAHASISWA (default, sesuai nama file ini)
               ========================================================= --}}
            @if(isset($mahasiswa))
                <div class="bg-white rounded-lg shadow">
                    @if($mahasiswa->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPM</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul KP</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status KP</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tempat</th>
                                        <th class="px-6 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($mahasiswa as $mhs)
                                        @php
                                            $kp = $mhs->kpTerakhir; // BUKAN $mhs->kerjaPraktek
                                            $status = $kp->status ?? null;
                                            $map = [
                                                'pengajuan' => 'bg-yellow-100 text-yellow-800',
                                                'disetujui' => 'bg-blue-100 text-blue-800',
                                                'sedang_kp' => 'bg-green-100 text-green-800',
                                                'selesai'   => 'bg-gray-100 text-gray-800',
                                                'ditolak'   => 'bg-red-100 text-red-800',
                                            ];
                                            $cls = $map[$status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp

                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $mhs->npm ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $mhs->name }}
                                                <div class="text-xs text-gray-500">{{ $mhs->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $kp->judul_kp ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm">
                                                @if($status)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cls }}">
                                                        {{ ucfirst(str_replace('_',' ',$status)) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-500">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $kp?->tempatMagang?->nama_perusahaan ?? ($kp->tempat_magang_sendiri ?? '-') }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-medium">
                                                <a href="{{ route('admin.mahasiswa.show', $mhs->id) }}"
                                                   class="text-unib-blue-600 hover:text-unib-blue-900">
                                                    <i class="fas fa-eye mr-1"></i>Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $mahasiswa->links() }}
                        </div>
                    @else
                        <div class="p-6 text-center">
                            <i class="fas fa-user-graduate text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Mahasiswa</h3>
                            <p class="text-gray-600">Data mahasiswa tidak ditemukan untuk filter saat ini.</p>
                        </div>
                    @endif
                </div>

            {{-- =========================================================
                 B) FALLBACK: LIST BIMBINGAN
               ========================================================= --}}
            @elseif(isset($bimbingan))
                <div class="bg-white rounded-lg shadow">
                    @if($bimbingan->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($bimbingan as $item)
                                <div class="flex items-start justify-between p-6 hover:bg-gray-50">
                                    <div class="flex-1">
                                        <div class="mb-2">
                                            @if($item->status_verifikasi)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>Verified
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                </span>
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <p class="text-sm text-gray-600">Mahasiswa:</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $item->mahasiswa->name }} ({{ $item->mahasiswa->npm }})
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">Tanggal Bimbingan:</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ optional($item->tanggal_bimbingan)->format('d F Y') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <p class="text-sm text-gray-600 mb-1">Catatan Mahasiswa:</p>
                                            <p class="text-gray-900">{{ \Illuminate\Support\Str::limit($item->catatan_mahasiswa, 200) }}</p>
                                        </div>

                                        @if($item->catatan_dosen)
                                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                                <p class="text-sm font-medium text-green-800 mb-1">Feedback Dosen:</p>
                                                <p class="text-green-900">{{ $item->catatan_dosen }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ml-6 flex flex-col space-y-2">
                                        <a href="{{ route('admin.bimbingan.show', $item) }}"
                                           class="text-unib-blue-600 hover:text-unib-blue-800 font-medium text-sm">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>

                                        @if(!$item->status_verifikasi)
                                            <button onclick="verifyBimbingan({{ $item->id }})"
                                                    class="text-green-600 hover:text-green-800 font-medium text-sm">
                                                <i class="fas fa-check mr-1"></i>Verify
                                            </button>
                                            <button onclick="addFeedback({{ $item->id }})"
                                                    class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                <i class="fas fa-comment mr-1"></i>Feedback
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $bimbingan->links() }}
                        </div>
                    @else
                        <div class="p-6 text-center">
                            <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Bimbingan</h3>
                            <p class="text-gray-600">Belum ada bimbingan dari mahasiswa yang perlu diverifikasi.</p>
                        </div>
                    @endif
                </div>

                {{-- Modal Feedback (khusus bimbingan) --}}
                <div id="feedbackModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="bg-white rounded-lg max-w-md w-full">
                            <form id="feedbackForm" method="POST">
                                @csrf
                                <div class="p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Berikan Feedback</h3>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Feedback untuk Mahasiswa</label>
                                        <textarea name="catatan_dosen" rows="4" required
                                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                  placeholder="Berikan feedback yang konstruktif untuk mahasiswa..."></textarea>
                                    </div>
                                    <div class="flex space-x-3">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                            Simpan Feedback
                                        </button>
                                        <button type="button" onclick="closeModal('feedbackModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Script (khusus bimbingan) --}}
                <script>
                    let currentBimbinganId = null;

                    function verifyBimbingan(bimbinganId) {
                        if (confirm('Yakin ingin memverifikasi bimbingan ini?')) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/admin/bimbingan/${bimbinganId}/verify`;
                            form.innerHTML = '@csrf';
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }

                    function addFeedback(bimbinganId) {
                        currentBimbinganId = bimbinganId;
                        document.getElementById('feedbackForm').action = `/admin/bimbingan/${bimbinganId}/feedback`;
                        document.getElementById('feedbackModal').classList.remove('hidden');
                    }

                    function closeModal(modalId) {
                        document.getElementById(modalId).classList.add('hidden');
                    }
                </script>

            {{-- =========================================================
                 C) Jika controller tidak mengirim keduanya
               ========================================================= --}}
            @else
                <div class="bg-white rounded-lg p-6 text-center text-gray-600">
                    Tidak ada data untuk ditampilkan.
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
