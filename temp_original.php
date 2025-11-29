<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kerja Praktek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(!$kerjaPraktek)
                {{-- FORM PENGAJUAN --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Pengajuan Kerja Praktek</h3>

                        {{-- FLASH & VALIDATION --}}
                        @if (session('success'))
                            <div class="mb-4 p-3 rounded bg-green-50 text-green-700">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="mb-4 p-3 rounded bg-red-50 text-red-700">{{ session('error') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="mb-4 p-3 rounded bg-red-50 text-red-700">
                                <div class="font-semibold mb-1">Periksa lagi input Anda:</div>
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('mahasiswa.kerja-praktek.store') }}" class="space-y-6">
                            @csrf

                            {{-- JUDUL KP --}}
                            <div>
                                <label for="judul_kp" class="block text-sm font-medium text-gray-700 mb-2">Judul Kerja Praktek *</label>
                                <input type="text" name="judul_kp" id="judul_kp"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                       value="{{ old('judul_kp') }}" required>
                                @error('judul_kp') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- DATA AKADEMIK --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester Ke *</label>
                                    <input type="number" name="semester_ke" min="1" max="14" required
                                           value="{{ old('semester_ke') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                                    @error('semester_ke') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">IPK Semester Ini *</label>
                                    <input type="number" name="ipk_semester" step="0.01" min="0" max="4" required
                                           value="{{ old('ipk_semester') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                                    @error('ipk_semester') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
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
                                                   @change="toggleCustomInput(false)" onclick="toggleCustomInput(false)">
                                            <label for="radio-prodi" class="font-medium text-gray-900">Pilih Tempat dari Prodi</label>
                                        </div>

                                        {{-- Pencarian --}}
                                        <div class="max-w-md">
                                            <input type="text" x-model="q" placeholder="Cari perusahaan/instansi…"
                                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                                            <p class="mt-1 text-xs text-gray-500">Tips: jika kolom pencarian kosong, hanya 3 tempat pertama yang tampil.</p>
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
                                                               ">
                                                        <div class="ml-3 flex-1">
                                                            <div class="font-medium text-gray-900">{{ $tempat->nama_perusahaan }}</div>
                                                            <div class="text-sm text-gray-600 mt-1">{{ Str::limit($tempat->bidang_usaha, 15) }}</div>
                                                            <div class="text-sm text-gray-500 mt-1">
                                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ Str::limit($tempat->alamat, 30) }}
                                                            </div>
                                                            <div class="text-sm text-gray-500">
                                                                <i class="fas fa-users mr-1"></i>
                                                                Kuota: <strong>{{ $sisa }}</strong> dari {{ $tempat->kuota_mahasiswa }} mahasiswa
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Empty state saat tidak ada hasil pencarian --}}
                                        <div x-cloak class="text-sm text-gray-500 italic"
                                             x-show="
                                                q !== '' &&
                                                !Array.from(document.querySelectorAll('#grid-tempat > div'))
                                                    .some(el => getComputedStyle(el).display !== 'none')
                                             ">
                                            Tidak ada tempat yang cocok dengan pencarian.
                                        </div>
                                    </div>

                                    {{-- Mencari Tempat Sendiri --}}
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <label class="flex items-start cursor-pointer">
                                            <input type="radio" name="pilihan_tempat" value="3" required
                                                   class="mt-1 text-unib-blue-600 focus:ring-unib-blue-500"
                                                   onchange="toggleCustomInput(true)" onclick="toggleCustomInput(true)">
                                            <div class="ml-3 flex-1">
                                                <div class="font-medium text-gray-900">Mencari Tempat Magang Sendiri</div>
                                                <div class="text-sm text-gray-600 mt-1">Anda dapat mencari dan mengajukan tempat magang sendiri</div>

                                                <div id="custom-input" class="mt-4 space-y-4 hidden">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan/Instansi</label>
                                                        <input type="text" name="tempat_magang_sendiri"
                                                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                               value="{{ old('tempat_magang_sendiri') }}">
                                                        @error('tempat_magang_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                                        <textarea name="alamat_tempat_sendiri" rows="3"
                                                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">{{ old('alamat_tempat_sendiri') }}</textarea>
                                                        @error('alamat_tempat_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kontak Person</label>
                                                        <input type="text" name="kontak_tempat_sendiri"
                                                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                               value="{{ old('kontak_tempat_sendiri') }}">
                                                        @error('kontak_tempat_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai KP</label>
                                                        <input type="date" name="tanggal_mulai"
                                                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                               value="{{ old('tanggal_mulai') }}">
                                                        @error('tanggal_mulai') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Hidden id untuk pilihan prodi --}}
                                <input type="hidden" name="tempat_magang_id" id="tempat_magang_id" value="{{ old('tempat_magang_id') }}">

                                @error('pilihan_tempat')  <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                @error('tempat_magang_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- SUBMIT --}}
                            <div class="flex justify-end">
                                <button type="submit"
                                        class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                    <i class="fas fa-paper-plane mr-2"></i> Submit Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                {{-- STATUS PENGAJUAN --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pengajuan Kerja Praktek</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Info Pengajuan --}}
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Judul KP</label>
                                    <p class="text-gray-900 mt-1">{{ $kerjaPraktek->judul_kp }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tempat Magang</label>
                                    <p class="text-gray-900 mt-1">
                                        @if($kerjaPraktek->pilihan_tempat == 3)
                                            {{ $kerjaPraktek->tempat_magang_sendiri }}
                                        @else
                                            {{ $kerjaPraktek->tempatMagang->nama_perusahaan ?? '-' }}
                                        @endif
                                    </p>
                                </div>

                                @if($kerjaPraktek->tanggal_mulai)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Tanggal Mulai</label>
                                        <p class="text-gray-900 mt-1">{{ $kerjaPraktek->tanggal_mulai->format('d F Y') }}</p>
                                    </div>
                                @endif

                                @if($kerjaPraktek->tanggal_selesai)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Tanggal Selesai</label>
                                        <p class="text-gray-900 mt-1">{{ $kerjaPraktek->tanggal_selesai->format('d F Y') }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Status + IPK --}}
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Status</label>
                                    <div class="mt-2">
                                        @php $s = $kerjaPraktek->status; @endphp
                                        @if($s === \App\Models\KerjaPraktek::STATUS_PENGAJUAN)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-2"></i> Menunggu Persetujuan
                                            </span>
                                        @elseif($s === \App\Models\KerjaPraktek::STATUS_DISETUJUI)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-check-circle mr-2"></i> Disetujui
                                            </span>
                                        @elseif($s === \App\Models\KerjaPraktek::STATUS_SEDANG_KP)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-play-circle mr-2"></i> Sedang KP
                                            </span>
                                        @elseif($s === \App\Models\KerjaPraktek::STATUS_SELESAI)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-flag-checkered mr-2"></i> Selesai
                                            </span>
                                        @elseif($s === \App\Models\KerjaPraktek::STATUS_DITOLAK)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-2"></i> Ditolak
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- IPK & Semester --}}
                                @php
                                    $ipk = $kerjaPraktek->ipk_semester;
                                    $semester = $kerjaPraktek->semester_ke;
                                    $badge = 'bg-gray-100 text-gray-800'; $label = '-';
                                    if (!is_null($ipk)) {
                                        if ($ipk >= 3.50) { $badge='bg-green-100 text-green-800'; $label='Sangat Baik'; }
                                        elseif ($ipk >= 3.00) { $badge='bg-blue-100 text-blue-800'; $label='Baik'; }
                                        elseif ($ipk >= 2.50) { $badge='bg-yellow-100 text-yellow-800'; $label='Cukup'; }
                                        else { $badge='bg-red-100 text-red-800'; $label='Perlu Perhatian'; }
                                    }
                                @endphp
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-600">Semester Ke</p>
                                            <p class="font-semibold text-gray-900">{{ $semester ?? '-' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">IPK Semester</p>
                                            <div class="flex items-center gap-2 justify-end">
                                                <p class="font-semibold text-gray-900">{{ is_null($ipk) ? '-' : number_format($ipk, 2) }}</p>
                                                <span class="px-2 py-0.5 rounded-full text-xs {{ $badge }}">{{ $label }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Upload Laporan --}}
                                @if(in_array($kerjaPraktek->status, [\App\Models\KerjaPraktek::STATUS_SEDANG_KP,\App\Models\KerjaPraktek::STATUS_SELESAI], true))
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Laporan KP</label>
                                        <div class="mt-2">
                                            @if($kerjaPraktek->file_laporan)
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-file-pdf text-red-500"></i>
                                                    <a href="{{ Storage::url($kerjaPraktek->file_laporan) }}" target="_blank" class="text-unib-blue-600 hover:text-unib-blue-800">Lihat Laporan</a>
                                                </div>
                                            @else
                                                <form method="POST" action="{{ route('mahasiswa.kerja-praktek.upload-laporan', $kerjaPraktek) }}" enctype="multipart/form-data" class="space-y-3">
                                                    @csrf
                                                    <input type="file" name="file_laporan" accept=".pdf" required
                                                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-unib-blue-50 file:text-unib-blue-700 hover:file:bg-unib-blue-100">
                                                    <button type="submit" class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                                        <i class="fas fa-upload mr-2"></i> Upload Laporan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Upload Kartu Implementasi --}}
                                @if($kerjaPraktek->acc_seminar)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Kartu Implementasi</label>
                                        <div class="mt-2">
                                            @if($kerjaPraktek->file_kartu_implementasi)
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <i class="fas fa-file-pdf text-red-500"></i>
                                                    <a href="{{ Storage::url($kerjaPraktek->file_kartu_implementasi) }}" target="_blank" class="text-unib-blue-600 hover:text-unib-blue-800">Lihat Kartu Implementasi</a>
                                                    @if($kerjaPraktek->acc_pembimbing_lapangan)
                                                        <span class="text-green-600 text-sm"><i class="fas fa-check-circle mr-1"></i>ACC Pembimbing </span>
                                                    @else
                                                        <span class="text-yellow-600 text-sm"><i class="fas fa-clock mr-1"></i>Menunggu ACC</span>
                                                    @endif
                                                </div>
                                            @else
                                                <form method="POST" action="{{ route('mahasiswa.kerja-praktek.upload-kartu', $kerjaPraktek) }}" enctype="multipart/form-data" class="space-y-3">
                                                    @csrf
                                                    <input type="file" name="file_kartu_implementasi" accept=".pdf,.jpg,.jpeg,.png" required
                                                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                                        <i class="fas fa-upload mr-2"></i> Upload Kartu Implementasi
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Kuisioner --}}
                                @if($kerjaPraktek->status === \App\Models\KerjaPraktek::STATUS_SELESAI && $kerjaPraktek->file_laporan)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Evaluasi</label>
                                        <div class="mt-2">
                                            @if($kerjaPraktek->kuisioner)
                                                <span class="inline-flex items-center text-green-600">
                                                    <i class="fas fa-check-circle mr-2"></i> Kuisioner sudah diisi
                                                </span>
                                            @else
                                                <a href="{{ route('mahasiswa.kerja-praktek.kuisioner', $kerjaPraktek) }}"
                                                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                                    <i class="fas fa-poll mr-2"></i> Isi Kuisioner
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Catatan --}}
                        @if($kerjaPraktek->catatan_dosen || $kerjaPraktek->catatan_pengawas)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="font-medium text-gray-900 mb-3">Catatan</h4>
                                @if($kerjaPraktek->catatan_dosen)
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-600">Catatan Dosen:</p>
                                        <p class="text-gray-900 mt-1">{{ $kerjaPraktek->catatan_dosen }}</p>
                                    </div>
                                @endif
                                @if($kerjaPraktek->catatan_pengawas)
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Catatan Pengawas:</p>
                                        <p class="text-gray-900 mt-1">{{ $kerjaPraktek->catatan_pengawas }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleCustomInput(show) {
            const box = document.getElementById('custom-input');
            if (!box) return;
            box.classList.toggle('hidden', !show);

            const names = ['tempat_magang_sendiri','alamat_tempat_sendiri','kontak_tempat_sendiri','tanggal_mulai'];
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

        // Set initial state berdasarkan old input
        document.addEventListener('DOMContentLoaded', () => {
            const oldChoice = '{{ old('pilihan_tempat') }}';
            toggleCustomInput(oldChoice === '3');
        });
    </script>
</x-app-layout>
