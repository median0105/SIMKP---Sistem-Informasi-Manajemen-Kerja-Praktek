<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kerja Praktek') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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

                        <form method="POST" action="{{ route('mahasiswa.kerja-praktek.store') }}" enctype="multipart/form-data" class="space-y-6">
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

                            {{-- UPLOAD KRS --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Kartu Rencana Studi (KRS) *</label>
                                <input type="file" name="file_krs" accept=".pdf" required
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-unib-blue-50 file:text-unib-blue-700 hover:file:bg-unib-blue-100">
                                <p class="text-xs text-gray-500 mt-1">Format: PDF, Maksimal 5MB</p>
                                @error('file_krs') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- UPLOAD PROPOSAL --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Proposal Kerja Praktek *</label>
                                <input type="file" name="file_proposal" accept=".pdf" required
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                <p class="text-xs text-gray-500 mt-1">Format: PDF, Maksimal 10MB</p>
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
                                                   onchange="toggleCustomInput(false); validateForm();">
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
                                                                   validateForm();
                                                               ">
                                                        <div class="ml-3 flex-1">
                                                            <div class="font-medium text-gray-900">{{ $tempat->nama_perusahaan }}</div>
                                                            <div class="text-sm text-gray-600 mt-1">{{ Str::limit($tempat->bidang_usaha, 15) }}</div>
                                                            <div class="text-sm text-gray-500 mt-1">
                                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ Str::limit($tempat->alamat, 30) }}
                                                            </div>
                                                            <div class="text-sm text-gray-500">
                                                                <i class="fas fa-users mr-1"></i>
                                                                Kuota: <strong>{{ $sisa }}</strong> dari {{ $tempat->kuota_mahasiswa }} Mahasiswa
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
                                                   onchange="toggleCustomInput(true); validateForm();">
                                            <div class="ml-3 flex-1">
                                                <div class="font-medium text-gray-900">Mencari Tempat Magang Sendiri</div>
                                                <div class="text-sm text-gray-600 mt-1">Anda dapat mencari dan mengajukan tempat magang sendiri</div>

                                                <div id="custom-input" class="mt-4 space-y-4 hidden">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan/Instansi *</label>
                                                            <input type="text" name="tempat_magang_sendiri"
                                                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                                   value="{{ old('tempat_magang_sendiri') }}" required>
                                                            @error('tempat_magang_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Bidang Usaha *</label>
                                                            <input type="text" name="bidang_usaha_sendiri"
                                                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                                   value="{{ old('bidang_usaha_sendiri') }}" required>
                                                            @error('bidang_usaha_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                                                        <textarea name="alamat_tempat_sendiri" rows="3"
                                                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500" required>{{ old('alamat_tempat_sendiri') }}</textarea>
                                                        @error('alamat_tempat_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Perusahaan *</label>
                                                            <input type="email" name="email_perusahaan_sendiri"
                                                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                                   value="{{ old('email_perusahaan_sendiri') }}" required>
                                                            @error('email_perusahaan_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon Perusahaan *</label>
                                                            <input type="text" name="telepon_perusahaan_sendiri"
                                                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                                   value="{{ old('telepon_perusahaan_sendiri') }}" required>
                                                            @error('telepon_perusahaan_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Kontak Person *</label>
                                                            <input type="text" name="kontak_tempat_sendiri"
                                                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                                   value="{{ old('kontak_tempat_sendiri') }}" required>
                                                            @error('kontak_tempat_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Kuota Mahasiswa *</label>
                                                            <input type="number" name="kuota_mahasiswa_sendiri" min="1" max="50"
                                                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                                   value="{{ old('kuota_mahasiswa_sendiri', 1) }}" required>
                                                            @error('kuota_mahasiswa_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Perusahaan</label>
                                                        <textarea name="deskripsi_perusahaan_sendiri" rows="3"
                                                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">{{ old('deskripsi_perusahaan_sendiri') }}</textarea>
                                                        @error('deskripsi_perusahaan_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai KP *</label>
                                                        <input type="date" name="tanggal_mulai"
                                                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                               value="{{ old('tanggal_mulai') }}" required>
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
                                 <button type="submit" id="submit-btn"
                                        class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Pengajuan
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
                                        <p class="text-gray-900 mt-1">{{ $kerjaPraktek->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}</p>
                                    </div>
                                @endif

                                @if($kerjaPraktek->tanggal_selesai)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Tanggal Selesai</label>
                                        <p class="text-gray-900 mt-1">{{ $kerjaPraktek->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Status + IPK --}}
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Status</label>
                                    <div class="mt-2">
                                        @php
                                            $s = $kerjaPraktek->status;
                                            $displayStatus = $s;
                                            if ($s === 'sedang_kp' && $kerjaPraktek->nilai_akhir && $kerjaPraktek->file_laporan) {
                                                // If KP is ongoing but student has uploaded laporan and has final grade, show 'selesai' status
                                                $displayStatus = 'selesai';
                                            }
                                        @endphp
                                        @if($s === \App\Models\KerjaPraktek::STATUS_PENGAJUAN)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-2"></i> Menunggu Persetujuan
                                            </span>
                                        @elseif($s === \App\Models\KerjaPraktek::STATUS_DISETUJUI)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-check-circle mr-2"></i> Disetujui
                                            </span>
                                        @elseif($s === \App\Models\KerjaPraktek::STATUS_SEDANG_KP && $displayStatus === 'sedang_kp')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-play-circle mr-2"></i> Sedang KP
                                            </span>
                                        @elseif($displayStatus === 'selesai')
                                            <div class="flex flex-col space-y-2">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-flag-checkered mr-2"></i> Selesai
                                                </span>
                                                @if($kerjaPraktek->nilai_akhir && !$kerjaPraktek->lulus_ujian)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-times-circle mr-2"></i> TIDAK LULUS
                                                    </span>
                                                @endif
                                            </div>
                                        @elseif($s === \App\Models\KerjaPraktek::STATUS_DITOLAK)
                                            <div class="flex flex-col space-y-2">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-2"></i> Ditolak
                                                </span>
                                                <a href="{{ route('mahasiswa.kerja-praktek.edit', $kerjaPraktek) }}"
                                                   class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition">
                                                    <i class="fas fa-edit mr-2"></i> Edit Pengajuan
                                                </a>
                                            </div>
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
                                                    @if($kerjaPraktek->acc_pembimbing_laporan)
                                                        <span class="text-green-600 text-sm"><i class="fas fa-check-circle mr-1"></i>ACC Pembimbing</span>
                                                    @else
                                                        <span class="text-yellow-600 text-sm"><i class="fas fa-clock mr-1"></i>Menunggu ACC</span>
                                                    @endif
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

                                {{-- Seminar Registration --}}
                                @if($kerjaPraktek->acc_pembimbing_laporan && $kerjaPraktek->file_laporan)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Pendaftaran Seminar</label>
                                        <div class="mt-2">
                                            @if($kerjaPraktek->pendaftaran_seminar)
                                                @if($kerjaPraktek->acc_pendaftaran_seminar)
                                                    <div class="space-y-2">
                                                        <span class="inline-flex items-center text-green-600">
                                                            <i class="fas fa-check-circle mr-2"></i> Pendaftaran Seminar Disetujui
                                                        </span>
                                                        @if($kerjaPraktek->jadwal_seminar)
                                                            <div class="text-sm text-gray-600">
                                                                <i class="fas fa-calendar mr-1"></i> Jadwal: {{ $kerjaPraktek->jadwal_seminar->locale('id')->translatedFormat('d F Y \p\u\k\u\l H:i') }} WIB
                                                                @if($kerjaPraktek->ruangan_seminar)
                                                                    <br><i class="fas fa-map-marker-alt mr-1"></i> Ruangan: {{ $kerjaPraktek->ruangan_seminar }}
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="inline-flex items-center text-yellow-600">
                                                        <i class="fas fa-clock mr-2"></i> Menunggu Jadwal Seminar
                                                    </span>
                                                    @if($kerjaPraktek->tanggal_daftar_seminar)
                                                        <div class="text-sm text-gray-500 mt-1">
                                                            Didaftarkan pada: {{ $kerjaPraktek->tanggal_daftar_seminar->locale('id')->translatedFormat('d F Y') }}
                                                        </div>
                                                    @endif
                                                @endif
                                            @else
                                                <form method="POST" action="{{ route('mahasiswa.kerja-praktek.daftar-seminar', $kerjaPraktek) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                                        <i class="fas fa-calendar-plus mr-2"></i> Daftar Seminar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Upload Revisi --}}
                                @if($kerjaPraktek->acc_seminar && $kerjaPraktek->rata_rata_seminar)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Upload Revisi Laporan</label>
                                        <div class="mt-2">
                                            @if($kerjaPraktek->file_revisi)
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-file-pdf text-red-500"></i>
                                                    <a href="{{ Storage::url($kerjaPraktek->file_revisi) }}" target="_blank" class="text-unib-blue-600 hover:text-unib-blue-800">Lihat Revisi</a>
                                                </div>
                                            @else
                                                <form method="POST" action="{{ route('mahasiswa.kerja-praktek.upload-revisi', $kerjaPraktek) }}" enctype="multipart/form-data" class="space-y-3">
                                                    @csrf
                                                    <input type="file" name="file_revisi" accept=".pdf" required
                                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                                        <i class="fas fa-upload mr-2"></i> Upload Revisi
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

    {{-- <script>
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

            // Function to validate form before submission
        function validateForm() {
            const submitBtn = document.getElementById('submit-btn');
            const form = document.querySelector('form');
            let isValid = true;
            let errors = [];

            // Check if pilihan_tempat is selected
            const pilihanTempat = form.querySelector('input[name="pilihan_tempat"]:checked');
            if (!pilihanTempat) {
                isValid = false;
                errors.push('Pilihan tempat required');
            } else {
                if (pilihanTempat.value === '1') {
                    // For 'Pilih Tempat dari Prodi', require specific tempat selection
                    const tempatMagangId = document.getElementById('tempat_magang_id');
                    if (!tempatMagangId || !tempatMagangId.value) {
                        isValid = false;
                        errors.push('Tempat magang dari prodi required');
                    }
                } else if (pilihanTempat.value === '3') {
                    // For 'Mencari Tempat Magang Sendiri', enable button immediately
                    isValid = true;
                }
            }

            // Check basic required fields (but don't disable button for these)
            const judulKp = form.querySelector('input[name="judul_kp"]');
            if (!judulKp || !judulKp.value.trim()) {
                errors.push('Judul KP required');
            }

            const semesterKe = form.querySelector('input[name="semester_ke"]');
            if (!semesterKe || !semesterKe.value.trim()) {
                errors.push('Semester ke required');
            }

            const ipkSemester = form.querySelector('input[name="ipk_semester"]');
            if (!ipkSemester || !ipkSemester.value.trim()) {
                errors.push('IPK semester required');
            }

            // Check custom fields when choosing custom place
            if (pilihanTempat && pilihanTempat.value === '3') {
                const customFields = ['tempat_magang_sendiri', 'bidang_usaha_sendiri', 'alamat_tempat_sendiri', 'email_perusahaan_sendiri', 'telepon_perusahaan_sendiri', 'kontak_tempat_sendiri', 'kuota_mahasiswa_sendiri', 'tanggal_mulai'];
                customFields.forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    if (field && !field.value.trim()) {
                        errors.push(`${fieldName} required`);
                    }
                });
            }

            // Check file uploads (but don't disable button for these)
            const fileKrs = form.querySelector('input[name="file_krs"]');
            const fileProposal = form.querySelector('input[name="file_proposal"]');
            if (fileKrs && !fileKrs.files[0]) {
                errors.push('File KRS required');
            }
            if (fileProposal && !fileProposal.files[0]) {
                errors.push('File proposal required');
            }

            // Debug logging
            console.log('Form validation:', isValid, errors);

            // Button is always enabled - validation handled server-side
            submitBtn.disabled = false;
            return isValid;

        // Set initial state berdasarkan old input
        document.addEventListener('DOMContentLoaded', () => {
            const oldChoice = '{{ old('pilihan_tempat') }}';
            toggleCustomInput(oldChoice === '3');

            // Add event listeners for validation
            const form = document.querySelector('form');
            if (form) {
                const inputs = form.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    input.addEventListener('input', validateForm);
                    input.addEventListener('change', validateForm);
                });

                // Specifically add listeners for pilihan_tempat radio buttons
                const pilihanTempatRadios = form.querySelectorAll('input[name="pilihan_tempat"]');
                pilihanTempatRadios.forEach(radio => {
                    radio.addEventListener('change', validateForm);
                });

                // Initial validation - enable button if form is already valid
                setTimeout(validateForm, 100);
            }
        });

        @if($kerjaPraktek && $kerjaPraktek->status === \App\Models\KerjaPraktek::STATUS_DITOLAK)
        // Polling untuk refresh otomatis jika KP ditolak dihapus
        setInterval(function() {
            fetch('{{ route('mahasiswa.kerja-praktek.check') }}')
                .then(response => response.json())
                .then(data => {
                    if (!data.has_kp) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Polling error:', error));
        }, 30000); // 30 detik
        @endif
    </script> --}}

    <script>
function toggleCustomInput(show) {
    const box = document.getElementById('custom-input');
    if (!box) return;
    box.classList.toggle('hidden', !show);

    // Enable/disable input fields untuk custom tempat
    const customFields = [
        'tempat_magang_sendiri', 'bidang_usaha_sendiri', 'alamat_tempat_sendiri',
        'email_perusahaan_sendiri', 'telepon_perusahaan_sendiri', 
        'kontak_tempat_sendiri', 'kuota_mahasiswa_sendiri', 'tanggal_mulai'
    ];
    
    customFields.forEach(fieldName => {
        const el = document.querySelector(`[name="${fieldName}"]`);
        if (el) {
            el.disabled = !show;
            if (!show) el.value = '';
        }
    });

    // Reset hidden field ketika pilih custom
    const hidden = document.getElementById('tempat_magang_id');
    if (show && hidden) {
        hidden.value = '';
    }
}

function validateForm() {
    const submitBtn = document.getElementById('submit-btn');
    if (!submitBtn) return true;

    // SELALU enable tombol submit - validasi dilakukan di server
    submitBtn.disabled = false;
    return true;
}

// Initialize form state
document.addEventListener('DOMContentLoaded', function() {
    // Set initial state berdasarkan old input
    const oldChoice = '{{ old('pilihan_tempat') }}';
    if (oldChoice === '3') {
        toggleCustomInput(true);
    } else {
        toggleCustomInput(false);
        
        // Set default tempat jika ada dari old input
        const oldTempatId = '{{ old('tempat_magang_id') }}';
        if (oldTempatId) {
            document.getElementById('tempat_magang_id').value = oldTempatId;
        }
    }

    // Enable tombol submit dari awal
    const submitBtn = document.getElementById('submit-btn');
    if (submitBtn) {
        submitBtn.disabled = false;
    }

    // Event listeners untuk radio buttons pilihan tempat
    const pilihanTempatRadios = document.querySelectorAll('input[name="pilihan_tempat"]');
    pilihanTempatRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === '3') {
                toggleCustomInput(true);
            } else {
                toggleCustomInput(false);
            }
            validateForm();
        });
    });

    // Event listener untuk pilihan tempat dari prodi
    const tempatProdiRadios = document.querySelectorAll('input[name="tempat_prodi_picker"]');
    tempatProdiRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Otomatis pilih radio "Pilih Tempat dari Prodi"
            const prodiRadio = document.getElementById('radio-prodi');
            if (prodiRadio) {
                prodiRadio.checked = true;
                toggleCustomInput(false);
            }
            validateForm();
        });
    });

    // Event listeners untuk input changes
    const form = document.querySelector('form');
    if (form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('input', validateForm);
            input.addEventListener('change', validateForm);
        });
    }
});

// Polling untuk refresh otomatis jika KP ditolak dihapus
@if($kerjaPraktek && $kerjaPraktek->status === \App\Models\KerjaPraktek::STATUS_DITOLAK)
setInterval(function() {
    fetch('{{ route('mahasiswa.kerja-praktek.check') }}')
        .then(response => response.json())
        .then(data => {
            if (!data.has_kp) {
                location.reload();
            }
        })
        .catch(error => console.error('Polling error:', error));
}, 30000); // 30 detik
@endif
</script>
</x-sidebar-layout>
