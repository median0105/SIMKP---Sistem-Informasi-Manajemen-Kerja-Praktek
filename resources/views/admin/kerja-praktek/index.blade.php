<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <!-- Ikon dihapus sesuai pattern -->
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Verifikasi Kerja Praktek
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
                <form method="GET" action="{{ route('admin.kerja-praktek.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-base font-medium text-gray-700 mb-2">
                          
                        </label>
                        <input type="text" name="search"
                               placeholder="Cari berdasarkan nama, NPM, atau judul KP..."
                               value="{{ request('search') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                    </div>
                    
                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">
                         
                        </label>
                        <select name="status" onchange="this.form.submit()"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            <option value="">Semua Status</option>
                            <option value="pengajuan"  @selected(request('status')=='pengajuan')>Pengajuan</option>
                            <option value="disetujui"  @selected(request('status')=='disetujui')>Disetujui</option>
                            <option value="sedang_kp"  @selected(request('status')=='sedang_kp')>Sedang KP</option>
                            <option value="selesai"    @selected(request('status')=='selesai')>Selesai</option>
                            <option value="ditolak"    @selected(request('status')=='ditolak')>Ditolak</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 w-full flex items-center justify-center">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- Alert judul duplikat --}}
            @if($duplicateTitles->count() > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg shadow-md p-6 mb-6 transform transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mt-1 mr-4"></i>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Peringatan: Judul Duplikat Ditemukan</h3>
                            <div class="text-base text-yellow-700">
                                <p class="mb-3">Judul KP berikut terdeteksi duplikat dan memerlukan perhatian khusus:</p>
                                <ul class="list-disc list-inside space-y-1 bg-yellow-100 p-4 rounded-lg">
                                    @foreach($duplicateTitles as $title)
                                        <li class="font-medium">{{ $title }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            {{-- Daftar KP --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                
                {{-- Table header with UNIB gradient --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Daftar Pengajuan Kerja Praktek
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $kerjaPraktek->total() }}
                    </div>
                </div>

                @if($kerjaPraktek->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Mahasiswa
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Judul KP
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Tempat
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Dosen Pembimbing
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($kerjaPraktek as $kp)
                                    <tr class="hover:bg-unib-blue-50 transition-colors duration-200 {{ $kp->isDuplicateTitle() ? 'bg-yellow-50 hover:bg-yellow-100' : '' }}">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900 text-base">{{ $kp->mahasiswa->name ?? '-' }}</div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ $kp->mahasiswa->npm ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-start">
                                                <span class="text-gray-900 text-base flex-1">
                                                    {{ \Illuminate\Support\Str::limit($kp->judul_kp, 50) }}
                                                </span>
                                                @if($kp->isDuplicateTitle())
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-300 shadow-sm ml-3">
                                                        Duplikat
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 text-base">
                                                @if($kp->pilihan_tempat == 3)
                                                    {{ \Illuminate\Support\Str::limit($kp->tempat_magang_sendiri, 30) }}
                                                @else
                                                    {{ $kp->tempatMagang->nama_perusahaan ?? '-' }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 text-base">
                                                @if($kp->dosenPembimbing && $kp->dosenPembimbing->where('jenis_pembimbingan', 'akademik')->first())
                                                    {{ $kp->dosenPembimbing->where('jenis_pembimbingan', 'akademik')->first()->dosen->name ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                // Status mapping with UNIB colors
                                                $statusMap = [
                                                    'pengajuan' => ['bg-yellow-100 text-yellow-800 border-yellow-300', 'Pengajuan'],
                                                    'disetujui' => ['bg-unib-blue-100 text-unib-blue-800 border-unib-blue-300', 'Disetujui'],
                                                    'sedang_kp' => ['bg-teknik-orange-100 text-teknik-orange-800 border-teknik-orange-300', 'Sedang KP'],
                                                    'selesai'   => ['bg-green-100 text-green-800 border-green-300', 'Selesai'],
                                                    'ditolak'   => ['bg-red-100 text-red-800 border-red-300', 'Ditolak'],
                                                ];
                                                [$statusClass, $statusLabel] = $statusMap[$kp->status] ?? ['bg-gray-100 text-gray-800 border-gray-300', 'Tidak Diketahui'];
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }} border shadow-sm">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 text-base">
                                                {{ optional($kp->created_at)->locale('id')->translatedFormat('d F Y') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center gap-2 justify-center">
                                                {{-- Detail button --}}
                                                <a href="{{ route('admin.kerja-praktek.show', $kp) }}"
                                                   class="text-unib-blue-600 hover:text-unib-blue-800 text-base font-medium flex items-center px-3 py-2 rounded-lg hover:bg-unib-blue-100 transition duration-200"
                                                   title="Detail KP">
                                                    Detail
                                                </a>

                                                {{-- Action buttons based on status --}}
                                                @if($kp->status === 'pengajuan')
                                                    {{-- Approve button --}}
                                                    <button type="button" onclick="approveKP({{ $kp->id }})"
                                                            class="text-green-600 hover:text-green-800 text-base font-medium flex items-center px-3 py-2 rounded-lg hover:bg-green-100 transition duration-200 {{ $kp->isDuplicateTitle() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                            {{ $kp->isDuplicateTitle() ? 'disabled' : '' }}
                                                            title="Setujui KP">
                                                        Setujui
                                                    </button>

                                                    {{-- Reject button --}}
                                                    <button type="button" onclick="rejectKP({{ $kp->id }})"
                                                            class="text-red-600 hover:text-red-800 text-base font-medium flex items-center px-3 py-2 rounded-lg hover:bg-red-100 transition duration-200"
                                                            title="Tolak KP">
                                                        Tolak
                                                    </button>
                                                @endif

                                                @if($kp->status === 'sedang_kp')
                                                    {{-- Send reminder button --}}
                                                    <button type="button" onclick="sendReminder({{ $kp->id }})"
                                                            class="text-teknik-orange-600 hover:text-teknik-orange-800 text-base font-medium flex items-center px-3 py-2 rounded-lg hover:bg-orange-100 transition duration-200"
                                                            title="Kirim Reminder">
                                                        Reminder
                                                    </button>
                                                @endif
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
                                Menampilkan {{ $kerjaPraktek->firstItem() }} - {{ $kerjaPraktek->lastItem() }} dari {{ $kerjaPraktek->total() }} hasil
                            </p>
                            <div class="flex space-x-1">
                                {{ $kerjaPraktek->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Empty state dengan animasi Lottie --}}
                    <div class="text-center py-16 text-gray-500 animate-fade-in-up">
                        <div class="flex flex-col items-center justify-center">
                            <!-- Lottie Animation -->
                            <div class="mb-6">
                                <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
                                <dotlottie-wc 
                                    src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                    style="width: 300px; height: 300px;" 
                                    autoplay 
                                    loop>
                                </dotlottie-wc>
                            </div>
                            <div class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Data</div>
                            <p class="text-base text-gray-600 max-w-md mx-auto">
                                Belum ada pengajuan kerja praktek yang sesuai dengan kriteria pencarian Anda.
                            </p>
                            @if(request('search') || request('status'))
                                <button onclick="window.location.href='{{ route('admin.kerja-praktek.index') }}'" 
                                        class="mt-6 bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center">
                                    Reset Filter
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Approve --}}
    <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 border border-unib-blue-200">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Setujui Pengajuan KP</h3>
                </div>
                <p class="text-gray-600 mb-6 text-base">Yakin ingin menyetujui pengajuan kerja praktek ini?</p>
                <div class="flex space-x-4">
                    <button onclick="confirmApprove()" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex-1 flex items-center justify-center">
                        Ya, Setujui
                    </button>
                    <button onclick="closeModal('approveModal')" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex-1 flex items-center justify-center">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Reject --}}
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 border border-unib-blue-200">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-100 rounded-full p-3 mr-4">
                            <i class="fas fa-times text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Tolak Pengajuan KP</h3>
                    </div>
                    <div class="mb-6">
                        <label class="block text-base font-medium text-gray-700 mb-3">Alasan Penolakan</label>
                        <textarea name="catatan_dosen" rows="4" required
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                  placeholder="Berikan alasan penolakan yang jelas dan konstruktif..."></textarea>
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex-1 flex items-center justify-center">
                            Tolak
                        </button>
                        <button type="button" 
                                onclick="closeModal('rejectModal')" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex-1 flex items-center justify-center">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
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
        
        /* Smooth transitions for modals */
        .modal-transition {
            transition: all 0.3s ease-out;
        }
        
        /* Style untuk dotlottie container */
        .lottie-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }
    </style>

    <!-- DotLottie Web Component Script -->
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>

    <script>
        let currentKpId = null;

        function approveKP(kpId) {
            currentKpId = kpId;
            document.getElementById('approveModal').classList.remove('hidden');
            setTimeout(() => {
                document.querySelector('#approveModal > div').classList.add('scale-100');
            }, 10);
        }

        function rejectKP(kpId) {
            currentKpId = kpId;
            document.getElementById('rejectForm').action = `/admin/kerja-praktek/${kpId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
            setTimeout(() => {
                document.querySelector('#rejectModal > div').classList.add('scale-100');
            }, 10);
        }

        function confirmApprove() {
            if (currentKpId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/kerja-praktek/${currentKpId}/approve`;
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        }

        function sendReminder(kpId) {
            if (confirm('Kirim reminder kepada mahasiswa?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/kerja-praktek/${kpId}/send-reminder`;
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        }

        function closeModal(modalId) {
            document.querySelector(`#${modalId} > div`).classList.remove('scale-100');
            setTimeout(() => {
                document.getElementById(modalId).classList.add('hidden');
            }, 200);
        }
        
        // Pastikan dotlottie-wc terload dengan baik
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof customElements !== 'undefined') {
                customElements.whenDefined('dotlottie-wc').then(() => {
                    console.log('DotLottie Web Component loaded successfully');
                });
            }
        });
    </script>
</x-sidebar-layout>