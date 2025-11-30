<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('mahasiswa.kerja-praktek.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Edit Kerja Praktek Ditolak
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Error messages --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md animate-fade-in-up">
                    <div class="font-semibold mb-1 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Periksa lagi input Anda:
                    </div>
                    <ul class="list-disc list-inside text-sm mt-2">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Main Form Card --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Edit Pengajuan Kerja Praktek
                    </h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('mahasiswa.kerja-praktek.update', $kerjaPraktek) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- JUDUL KP --}}
                        <div>
                            <label for="judul_kp" class="block text-sm font-medium text-gray-700 mb-2">Judul Kerja Praktek *</label>
                            <input type="text" name="judul_kp" id="judul_kp"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                   value="{{ old('judul_kp', $kerjaPraktek->judul_kp) }}" required>
                            @error('judul_kp') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- UPLOAD PROPOSAL --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Proposal Kerja Praktek *</label>
                            <input type="file" name="file_proposal" accept=".pdf" required
                                   class="w-full text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 transition duration-200">
                            <p class="text-xs text-gray-500 mt-2 flex items-center">
                                <i class="fas fa-info-circle mr-1 text-unib-blue-400"></i>
                                Format: PDF, Maksimal 10MB
                            </p>
                            @if($kerjaPraktek->file_proposal)
                                <p class="text-sm text-gray-600 mt-2">File saat ini: <a href="{{ Storage::url($kerjaPraktek->file_proposal) }}" target="_blank" class="text-unib-blue-600 hover:underline">Lihat Proposal</a></p>
                            @endif
                            @error('file_proposal') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- PILIHAN TEMPAT --}}
                        <div x-data="{ q: '' }">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Pilihan Tempat Kerja Praktek *</label>

                            <div class="space-y-4">
                                {{-- Tempat dari Prodi --}}
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" id="radio-prodi" name="pilihan_tempat" value="1" required
                                               class="text-unib-blue-600 focus:ring-unib-blue-500"
                                               @change="toggleCustomInput(false)" onclick="toggleCustomInput(false)" {{ $kerjaPraktek->pilihan_tempat == 1 ? 'checked' : '' }}>
                                        <label for="radio-prodi" class="font-medium text-gray-900">Pilih Tempat dari Prodi</label>
                                    </div>

                                    {{-- Pencarian --}}
                                    <div class="max-w-md">
                                        <input type="text" x-model="q" placeholder="Cari perusahaan/instansi…"
                                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                                            <i class="fas fa-lightbulb mr-1 text-unib-blue-400"></i>
                                            Tips: jika kolom pencarian kosong, hanya 3 tempat pertama yang tampil.
                                        </p>
                                    </div>

                                    {{-- Grid Tempat (yang masih ada kuota) --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="grid-tempat">
                                        @foreach($tempatMagang as $tempat)
                                            @php
                                                $sisa = $tempat->sisaKuota ?? max(0, (int) $tempat->kuota_mahasiswa - (int) ($tempat->terpakai_count ?? 0));
                                            @endphp
                                            @if($sisa <= 0) @continue @endif

                                            <div x-cloak class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition"
                                                 x-show="
                                                    q === ''
                                                        ? ({{ $loop->index }} < 3)
                                                        : ('{{ Str::lower($tempat->nama_perusahaan) }} {{ Str::lower($tempat->bidang_usaha) }} {{ Str::lower($tempat->alamat) }}')
                                                            .includes(q.toLowerCase())
                                                 ">
                                                <label class="flex items-start cursor-pointer">
                                                    <input type="radio" name="tempat_prodi_picker"
                                                           class="mt-1 text-unib-blue-600 focus:ring-unib-blue-500"
                                                           onchange="
                                                               document.getElementById('tempat_magang_id').value = '{{ $tempat->id }}';
                                                               const r = document.getElementById('radio-prodi'); if (r) r.checked = true;
                                                               toggleCustomInput(false);
                                                           " {{ $kerjaPraktek->tempat_magang_id == $tempat->id ? 'checked' : '' }}>
                                                    <div class="ml-3 flex-1">
                                                        <div class="font-medium text-gray-900">{{ $tempat->nama_perusahaan }}</div>
                                                        <div class="text-sm text-gray-600 mt-1">{{ Str::limit($tempat->bidang_usaha, 15) }}</div>
                                                        <div class="text-sm text-gray-500 mt-1 flex items-center">
                                                            <i class="fas fa-map-marker-alt mr-1 text-xs text-unib-blue-400"></i>
                                                            {{ Str::limit($tempat->alamat, 30) }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 flex items-center">
                                                            <i class="fas fa-users mr-1 text-xs text-unib-blue-400"></i>
                                                            Kuota: <strong>{{ $sisa }}</strong> dari {{ $tempat->kuota_mahasiswa }} Mahasiswa
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Empty state saat tidak ada hasil pencarian --}}
                                    <div x-cloak class="text-sm text-gray-500 italic flex items-center"
                                         x-show="
                                            q !== '' &&
                                            !Array.from(document.querySelectorAll('#grid-tempat > div'))
                                                .some(el => getComputedStyle(el).display !== 'none')
                                         ">
                                        <i class="fas fa-search mr-2"></i>
                                        Tidak ada tempat yang cocok dengan pencarian.
                                    </div>
                                </div>

                                {{-- Mencari Tempat Sendiri --}}
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <label class="flex items-start cursor-pointer">
                                        <input type="radio" name="pilihan_tempat" value="3" required
                                               class="mt-1 text-unib-blue-600 focus:ring-unib-blue-500"
                                               onchange="toggleCustomInput(true)" onclick="toggleCustomInput(true)" {{ $kerjaPraktek->pilihan_tempat == 3 ? 'checked' : '' }}>
                                        <div class="ml-3 flex-1">
                                            <div class="font-medium text-gray-900">Mencari Tempat Magang Sendiri</div>
                                            <div class="text-sm text-gray-600 mt-1">Anda dapat mencari dan mengajukan tempat magang sendiri</div>

                                            <div id="custom-input" class="mt-4 space-y-4 {{ $kerjaPraktek->pilihan_tempat == 3 ? '' : 'hidden' }}">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan/Instansi *</label>
                                                    <input type="text" name="tempat_magang_sendiri"
                                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                                           value="{{ old('tempat_magang_sendiri', $kerjaPraktek->tempat_magang_sendiri) }}" required>
                                                    @error('tempat_magang_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bidang Usaha *</label>
                                                    <input type="text" name="bidang_usaha_sendiri"
                                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                                           value="{{ old('bidang_usaha_sendiri', $kerjaPraktek->bidang_usaha_sendiri) }}" required>
                                                    @error('bidang_usaha_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                                                    <textarea name="alamat_tempat_sendiri" rows="3"
                                                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200" required>{{ old('alamat_tempat_sendiri', $kerjaPraktek->alamat_tempat_sendiri) }}</textarea>
                                                    @error('alamat_tempat_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Perusahaan</label>
                                                    <input type="email" name="email_perusahaan_sendiri"
                                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                                           value="{{ old('email_perusahaan_sendiri', $kerjaPraktek->email_perusahaan_sendiri) }}">
                                                    @error('email_perusahaan_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Telepon Perusahaan</label>
                                                    <input type="text" name="telepon_perusahaan_sendiri"
                                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                                           value="{{ old('telepon_perusahaan_sendiri', $kerjaPraktek->telepon_perusahaan_sendiri) }}">
                                                    @error('telepon_perusahaan_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kontak Person *</label>
                                                    <input type="text" name="kontak_tempat_sendiri"
                                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                                           value="{{ old('kontak_tempat_sendiri', $kerjaPraktek->kontak_tempat_sendiri) }}" required>
                                                    @error('kontak_tempat_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kuota Mahasiswa *</label>
                                                    <input type="number" name="kuota_mahasiswa_sendiri" min="1"
                                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                                           value="{{ old('kuota_mahasiswa_sendiri', $kerjaPraktek->kuota_mahasiswa_sendiri) }}" required>
                                                    @error('kuota_mahasiswa_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Perusahaan</label>
                                                    <textarea name="deskripsi_perusahaan_sendiri" rows="3"
                                                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">{{ old('deskripsi_perusahaan_sendiri', $kerjaPraktek->deskripsi_perusahaan_sendiri) }}</textarea>
                                                    @error('deskripsi_perusahaan_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai KP *</label>
                                                    <input type="date" name="tanggal_mulai"
                                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                                           value="{{ old('tanggal_mulai', $kerjaPraktek->tanggal_mulai ? $kerjaPraktek->tanggal_mulai->format('Y-m-d') : '') }}" required>
                                                    @error('tanggal_mulai') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Hidden id untuk pilihan prodi --}}
                            <input type="hidden" name="tempat_magang_id" id="tempat_magang_id" value="{{ old('tempat_magang_id', $kerjaPraktek->tempat_magang_id) }}">

                            @error('pilihan_tempat')  <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            @error('tempat_magang_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end gap-3 pt-6">
                            <a href="{{ route('mahasiswa.kerja-praktek.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-base font-medium transition duration-200 flex items-center">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit"
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium transition duration-200 flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i>Perbarui Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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

    <script>
        function toggleCustomInput(show) {
            const box = document.getElementById('custom-input');
            if (!box) return;
            box.classList.toggle('hidden', !show);

            const names = ['tempat_magang_sendiri','bidang_usaha_sendiri','alamat_tempat_sendiri','email_perusahaan_sendiri','telepon_perusahaan_sendiri','kontak_tempat_sendiri','kuota_mahasiswa_sendiri','deskripsi_perusahaan_sendiri','tanggal_mulai'];
            names.forEach(n => {
                const el = document.querySelector(`[name="${n}"]`);
                if (el) {
                    el.disabled = !show;
                    if (!show) el.value = '';
                }
            });

            const hidden = document.getElementById('tempat_magang_id');
            if (show) {
                if (hidden) hidden.value = ''; // penting: kosongkan saat custom
                const r3 = document.querySelector('input[name="pilihan_tempat"][value="3"]');
                if (r3) r3.checked = true;
            }
        }

        // Set initial state berdasarkan pilihan_tempat
        document.addEventListener('DOMContentLoaded', () => {
            const pilihanTempat = '{{ $kerjaPraktek->pilihan_tempat }}';
            toggleCustomInput(pilihanTempat == '3');
        });
    </script>
</x-sidebar-layout>