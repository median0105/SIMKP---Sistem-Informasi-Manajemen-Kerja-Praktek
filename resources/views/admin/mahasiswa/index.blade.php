
{{-- resources/views/admin/mahasiswa/index.blade.php --}}
<x-sidebar-layout>
    {{-- Header section with UNIB blue gradient --}}
 <x-slot name="header">
    <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
        <div class="flex items-center space-x-3">
            <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                <!-- Ikon dihapus -->
            </div>
            <div>
                <h2 class="font-bold text-xl leading-tight">
                    Daftar Mahasiswa Bimbingan
                </h2>
            </div>
        </div>
    </div>
</x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- === Filter & Search === --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-base font-medium text-gray-700 mb-2">
                         
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari berdasarkan nama, NPM, atau email..."
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                    </div>
                    
                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">
                        
                        </label>
                        <select name="status_kp"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            <option value="">Semua Status KP</option>
                            <option value="pengajuan"  {{ request('status_kp')==='pengajuan'  ? 'selected' : '' }}>Pengajuan</option>
                            <option value="disetujui"  {{ request('status_kp')==='disetujui'  ? 'selected' : '' }}>Disetujui</option>
                            <option value="sedang_kp"  {{ request('status_kp')==='sedang_kp'  ? 'selected' : '' }}>Sedang KP</option>
                            <option value="selesai"    {{ request('status_kp')==='selesai'    ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak"    {{ request('status_kp')==='ditolak'    ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end gap-3">
                        <button type="submit" 
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Cari
                        </button>
                        @if(request()->filled('search') || request()->filled('status_kp'))
                            <a href="{{ route('admin.mahasiswa.index') }}"
                               class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- =========================================================
                 A) LIST MAHASISWA (default, sesuai nama file ini)
               ========================================================= --}}
            <div class="h-4"></div>
            @if(isset($mahasiswa))
                <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    {{-- Table header with UNIB gradient --}}
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                        <h3 class="text-xl font-bold">
                            Tabel Mahasiswa Bimbingan
                        </h3>
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                            Total: {{ $mahasiswa->total() }}
                        </div>
                    </div>

                    @if($mahasiswa->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                            NPM
                                        </th>
                                        <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                            Nama
                                        </th>
                                        <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                            Judul KP
                                        </th>
                                        <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                            Status KP
                                        </th>
                                        <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                            Tempat
                                        </th>
                                        <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach($mahasiswa as $mhs)
                                        @php
                                            $kp = $mhs->kpTerakhir;
                                            $status = $kp->status ?? null;
                                            $displayStatus = $status;
                                            if ($status === 'sedang_kp' && $kp->nilai_akhir && $kp->file_laporan) {
                                                $displayStatus = 'selesai';
                                            }
                                            
                                            // Status mapping dengan warna UNIB
                                            $statusMap = [
                                                'pengajuan' => ['bg-yellow-100 text-yellow-800 border-yellow-300', 'Pengajuan'],
                                                'disetujui' => ['bg-unib-blue-100 text-unib-blue-800 border-unib-blue-300', 'Disetujui'],
                                                'sedang_kp' => ['bg-teknik-orange-100 text-teknik-orange-800 border-teknik-orange-300', 'Sedang KP'],
                                                'selesai'   => ['bg-green-100 text-green-800 border-green-300', 'Selesai'],
                                                'ditolak'   => ['bg-red-100 text-red-800 border-red-300', 'Ditolak'],
                                            ];
                                            [$statusClass, $statusLabel] = $statusMap[$displayStatus] ?? ['bg-gray-100 text-gray-800 border-gray-300', 'Tidak Diketahui'];
                                        @endphp

                                        <tr class="hover:bg-unib-blue-50 transition">
                                            <td class="px-6 py-4">
                                                <div class="font-semibold text-gray-900 text-base">
                                                    {{ $mhs->npm ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-semibold text-gray-900 text-base">
                                                    {{ $mhs->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 mt-1">
                                                    {{ $mhs->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-gray-900 text-base">
                                                    {{ $kp->judul_kp ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($status)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }} border shadow-sm">
                                                        {{ $statusLabel }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-500">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-gray-900 text-base">
                                                    {{ $kp?->tempatMagang?->nama_perusahaan ?? ($kp->tempat_magang_sendiri ?? '-') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center gap-2 justify-center">
                                                    <a href="{{ route('admin.mahasiswa.show', $mhs->id) }}"
                                                       class="text-unib-blue-600 hover:text-unib-blue-800 text-base font-medium flex items-center px-3 py-2 rounded-lg hover:bg-unib-blue-100 transition duration-200">
                                                        Detail
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination dengan style UNIB --}}
                        <div class="p-6 border-t bg-unib-blue-50">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-unib-blue-700">
                                    Menampilkan {{ $mahasiswa->firstItem() }} - {{ $mahasiswa->lastItem() }} dari {{ $mahasiswa->total() }} hasil
                                </p>
                                <div class="flex space-x-1">
                                    {{ $mahasiswa->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Empty state dengan animasi Lottie --}}
                        <div class="text-center py-16 text-gray-500">
                            <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
                            <dotlottie-wc src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" style="width: 300px;height: 300px; margin: 0 auto;" autoplay loop></dotlottie-wc>
                            <div class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Mahasiswa</div>
                            <p class="text-base text-gray-600 max-w-md mx-auto">
                                Data mahasiswa tidak ditemukan untuk filter saat ini. 
                                Coba ubah kriteria pencarian atau reset filter.
                            </p>
                        </div>
                    @endif
                </div>

            {{-- =========================================================
                 B) FALLBACK: LIST BIMBINGAN
               ========================================================= --}}
            @elseif(isset($bimbingan))
                <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    {{-- Table header dengan gradient teknik-orange --}}
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-teknik-orange-500 to-teknik-orange-600 text-white flex items-center justify-between min-h-[70px]">
                        <h3 class="text-xl font-bold">
                            Tabel Bimbingan Mahasiswa
                        </h3>
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                            Total: {{ $bimbingan->total() }}
                        </div>
                    </div>

                    @if($bimbingan->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($bimbingan as $item)
                                <div class="flex items-start justify-between p-6 hover:bg-unib-blue-50 transition">
                                    <div class="flex-1">
                                        <div class="mb-4">
                                            @if($item->status_verifikasi)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                                    Verified
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-300 shadow-sm">
                                                    Pending
                                                </span>
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-600">
                                                    Mahasiswa:
                                                </p>
                                                <p class="font-semibold text-gray-900 text-lg">
                                                    {{ $item->mahasiswa->name }} ({{ $item->mahasiswa->npm }})
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600">
                                                    Tanggal Bimbingan:
                                                </p>
                                                <p class="font-semibold text-gray-900 text-lg">
                                                    {{ optional($item->tanggal_bimbingan)->format('d F Y') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <p class="text-sm font-medium text-gray-600 mb-2">
                                                Catatan Mahasiswa:
                                            </p>
                                            <p class="text-gray-900 text-base bg-gray-50 p-4 rounded-lg border border-gray-200">
                                                {{ \Illuminate\Support\Str::limit($item->catatan_mahasiswa, 200) }}
                                            </p>
                                        </div>

                                        @if($item->catatan_dosen)
                                            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                                                <p class="text-sm font-medium text-green-800 mb-2">
                                                    Feedback Dosen:
                                                </p>
                                                <p class="text-green-900 text-base">{{ $item->catatan_dosen }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ml-6 flex flex-col space-y-3">
                                        <a href="{{ route('admin.bimbingan.show', ['mahasiswa' => $item->mahasiswa_id]) }}"
                                           class="text-unib-blue-600 hover:text-unib-blue-800 text-base font-medium flex items-center px-4 py-2 rounded-lg hover:bg-unib-blue-100 transition duration-200">
                                            Detail
                                        </a>

                                        @if(!$item->status_verifikasi)
                                            <button onclick="verifyBimbingan({{ $item->id }})"
                                                    class="text-green-600 hover:text-green-800 text-base font-medium flex items-center px-4 py-2 rounded-lg hover:bg-green-100 transition duration-200">
                                                Verify
                                            </button>
                                            <button onclick="addFeedback({{ $item->id }})"
                                                    class="text-teknik-orange-600 hover:text-teknik-orange-800 text-base font-medium flex items-center px-4 py-2 rounded-lg hover:bg-orange-100 transition duration-200">
                                                Feedback
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="p-6 border-t bg-unib-blue-50">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-unib-blue-700">
                                    Menampilkan {{ $bimbingan->firstItem() }} - {{ $bimbingan->lastItem() }} dari {{ $bimbingan->total() }} hasil
                                </p>
                                <div class="flex space-x-1">
                                    {{ $bimbingan->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Empty state dengan animasi Lottie --}}
                        <div class="text-center py-16 text-gray-500">
                            <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
                            <dotlottie-wc src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" style="width: 300px;height: 300px; margin: 0 auto;" autoplay loop></dotlottie-wc>
                            <div class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Bimbingan</div>
                            <p class="text-base text-gray-600 max-w-md mx-auto">
                                Belum ada bimbingan dari mahasiswa yang perlu diverifikasi.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Modal Feedback dengan style UNIB --}}
                <div id="feedbackModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
                    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 border border-unib-blue-200">
                        <form id="feedbackForm" method="POST">
                            @csrf
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div class="bg-unib-blue-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-comment-dots text-unib-blue-600 text-xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Berikan Feedback</h3>
                                </div>
                                <div class="mb-6">
                                    <label class="block text-base font-medium text-gray-700 mb-3">Feedback untuk Mahasiswa</label>
                                    <textarea name="catatan_dosen" rows="4" required
                                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                              placeholder="Berikan feedback yang konstruktif untuk mahasiswa..."></textarea>
                                </div>
                                <div class="flex space-x-4">
                                    <button type="submit" 
                                            class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                                        Simpan Feedback
                                    </button>
                                    <button type="button" 
                                            onclick="closeModal('feedbackModal')" 
                                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </form>
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
                        setTimeout(() => {
                            document.querySelector('#feedbackModal > div').classList.add('scale-100');
                        }, 10);
                    }

                    function closeModal(modalId) {
                        document.querySelector(`#${modalId} > div`).classList.remove('scale-100');
                        setTimeout(() => {
                            document.getElementById(modalId).classList.add('hidden');
                        }, 200);
                    }
                </script>

            {{-- =========================================================
                 C) Jika controller tidak mengirim keduanya
               ========================================================= --}}
            @else
                <div class="bg-white shadow-xl rounded-xl p-12 text-center text-gray-600 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg">
                    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
                    <dotlottie-wc src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" style="width: 300px;height: 300px; margin: 0 auto;" autoplay loop></dotlottie-wc>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tidak Ada Data</h3>
                    <p class="text-base text-gray-600">Tidak ada data untuk ditampilkan.</p>
                </div>
            @endif

        </div>
    </div>

    {{-- CSS untuk animasi kustom --}}
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</x-sidebar-layout>