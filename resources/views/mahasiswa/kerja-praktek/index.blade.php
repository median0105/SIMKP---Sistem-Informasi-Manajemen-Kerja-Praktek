<x-sidebar-layout>
    {{-- Header section with UNIB blue gradient --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        {{ __('Kerja Praktek') }}
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if(!$kerjaPraktek)
                {{-- FORM PENGAJUAN --}}
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-unib-blue-100 animate-fade-in-up">
                    <div class="p-8 bg-white">
                        <div class="flex items-center mb-8">
                            <div class="bg-gradient-to-r from-unib-blue-500 to-unib-blue-600 rounded-xl mr-4 w-14 h-14 flex items-center justify-center shadow-sm">
                                 <i class="fas fa-file-alt text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Pengajuan Kerja Praktek</h3>
                                <p class="text-gray-600 mt-1">Isi formulir berikut untuk mengajukan kerja praktek</p>
                            </div>
                        </div>

                        {{-- FLASH & VALIDATION --}}
                        @if (session('success'))
                            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center animate-fade-in-up">
                                <i class="fas fa-check-circle text-green-600 text-lg mr-3"></i>
                                <div class="text-green-700 font-medium">{{ session('success') }}</div>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-center animate-fade-in-up">
                                <i class="fas fa-exclamation-circle text-red-600 text-lg mr-3"></i>
                                <div class="text-red-700 font-medium">{{ session('error') }}</div>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 animate-fade-in-up">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    <div class="font-semibold text-red-700">Periksa lagi input Anda:</div>
                                </div>
                                <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('mahasiswa.kerja-praktek.store') }}" enctype="multipart/form-data" class="space-y-8">
                            @csrf

                            {{-- JUDUL KP --}}
                            <div class="bg-gradient-to-r from-unib-blue-50 to-blue-50 rounded-xl p-6 border border-unib-blue-200 transition-all duration-300">
                                <label for="judul_kp" class="block text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-graduation-cap text-unib-blue-600 mr-3"></i>
                                    Judul Kerja Praktek *
                                </label>
                                <input type="text" name="judul_kp" id="judul_kp"
                                       class="w-full px-4 py-3 border-2 border-unib-blue-300 rounded-xl shadow-sm focus:border-unib-blue-500 focus:ring-2 focus:ring-unib-blue-500 focus:ring-opacity-20 transition duration-200"
                                       value="{{ old('judul_kp') }}" required
                                       placeholder="Masukkan judul kerja praktek Anda">
                                @error('judul_kp') <p class="text-red-500 text-sm mt-2 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>

                            {{-- DATA AKADEMIK --}}
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200 transition-all duration-300">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-user-graduate text-green-600 mr-3"></i>
                                    Data Akademik
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Semester Ke *</label>
                                        <input type="number" name="semester_ke" min="1" max="14" required
                                               value="{{ old('semester_ke') }}"
                                               class="w-full px-4 py-3 border-2 border-green-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 transition duration-200"
                                               placeholder="Contoh: 6">
                                        @error('semester_ke') <p class="text-red-500 text-sm mt-2 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">IPK Semester Ini *</label>
                                        <input type="number" name="ipk_semester" step="0.01" min="0" max="4" required
                                               value="{{ old('ipk_semester') }}"
                                               class="w-full px-4 py-3 border-2 border-green-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 transition duration-200"
                                               placeholder="Contoh: 3.50">
                                        @error('ipk_semester') <p class="text-red-500 text-sm mt-2 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- UPLOAD BERKAS --}}
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200 transition-all duration-300">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-file-upload text-purple-600 mr-3"></i>
                                    Upload Berkas
                                </h4>
                                
                                {{-- UPLOAD KRS --}}
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Kartu Rencana Studi (KRS) *</label>
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-1">
                                            <input type="file" name="file_krs" accept=".pdf" required
                                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-unib-blue-500 file:to-unib-blue-600 file:text-white hover:file:from-unib-blue-700 hover:file:to-unib-blue-800 transition duration-200">
                                        </div>
                                        <div class="bg-unib-blue-100 p-3 rounded-xl">
                                            <i class="fas fa-file-pdf text-unib-blue-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2 flex items-center"><i class="fas fa-info-circle mr-1"></i> Format: PDF, Maksimal 5MB</p>
                                    @error('file_krs') <p class="text-red-500 text-sm mt-2 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                                </div>

                                {{-- UPLOAD PROPOSAL --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Proposal Kerja Praktek *</label>
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-1">
                                            <input type="file" name="file_proposal" accept=".pdf" required
                                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-purple-500 file:to-purple-600 file:text-white hover:file:from-purple-600 hover:file:to-purple-700 transition duration-200">
                                        </div>
                                        <div class="bg-purple-100 p-3 rounded-xl">
                                            <i class="fas fa-file-contract text-purple-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2 flex items-center"><i class="fas fa-info-circle mr-1"></i> Format: PDF, Maksimal 10MB</p>
                                    @error('file_proposal') <p class="text-red-500 text-sm mt-2 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- PILIHAN TEMPAT --}}
                            <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl p-6 border border-orange-200 transition-all duration-300" x-data="{ q: '' }">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-building text-orange-600 mr-3"></i>
                                    Pilihan Tempat Kerja Praktek
                                </h4>

                                <div class="space-y-6">
                                    {{-- Tempat dari Prodi --}}
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-4 p-4 bg-white rounded-xl border-2 border-unib-blue-300 hover:border-unib-blue-500 transition duration-200">
                                            <input type="radio" id="radio-prodi" name="pilihan_tempat" value="1" required
                                                   class="text-unib-blue-500 focus:ring-unib-blue-500 w-5 h-5"
                                                   onchange="toggleCustomInput(false); validateForm();">
                                            <label for="radio-prodi" class="flex items-center cursor-pointer">
                                                <div class="bg-unib-blue-100 p-2 rounded-lg mr-3">
                                                    <i class="fas fa-university text-unib-blue-600"></i>
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900">Pilih Tempat dari Prodi</div>
                                                    <div class="text-sm text-gray-600">Pilih dari daftar tempat yang telah disediakan</div>
                                                </div>
                                            </label>
                                        </div>

                                        {{-- Pencarian --}}
                                        <div class="max-w-md">
                                            <div class="relative">
                                                <input type="text" x-model="q" placeholder="Cari perusahaan/instansi…"
                                                       class="w-full pl-10 pr-4 py-3 border-2 border-orange-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-20 transition duration-200">
                                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                            </div>
                                            <p class="mt-2 text-xs text-gray-500 flex items-center"><i class="fas fa-lightbulb mr-1"></i> Tips: jika kolom pencarian kosong, hanya 3 tempat pertama yang tampil.</p>
                                        </div>

                                        {{-- Grid Tempat (yang masih ada kuota) --}}
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="grid-tempat">
                                            @foreach($tempatMagang as $tempat)
                                                @php
                                                    $sisa = $tempat->sisaKuota ?? max(0, (int) $tempat->kuota_mahasiswa - (int) ($tempat->terpakai_count ?? 0));
                                                @endphp
                                                @if($sisa <= 0) @continue @endif

                                                <div x-cloak class="bg-white border-2 border-unib-blue-200 rounded-xl p-4 hover:border-unib-blue-500 transition duration-200"
                                                     x-show="
                                                        q === ''
                                                            ? ({{ $loop->index }} < 3)
                                                            : ('{{ Str::lower($tempat->nama_perusahaan) }} {{ Str::lower($tempat->bidang_usaha) }} {{ Str::lower($tempat->alamat) }}')
                                                                .includes(q.toLowerCase())
                                                     ">
                                                    <label class="flex items-start cursor-pointer">
                                                        <input type="radio" name="tempat_prodi_picker"
                                                               class="mt-1 text-unib-blue-500 focus:ring-unib-blue-500 w-4 h-4"
                                                               onchange="
                                                                   document.getElementById('tempat_magang_id').value = '{{ $tempat->id }}';
                                                                   const r = document.getElementById('radio-prodi'); if (r) r.checked = true;
                                                                   toggleCustomInput(false);
                                                                   validateForm();
                                                               ">
                                                        <div class="ml-3 flex-1">
                                                            <div class="font-semibold text-gray-900">{{ $tempat->nama_perusahaan }}</div>
                                                            <div class="text-sm text-gray-600 mt-2 flex items-center">
                                                                <i class="fas fa-briefcase mr-2 text-unib-blue-500"></i>
                                                                {{ Str::limit($tempat->bidang_usaha, 20) }}
                                                            </div>
                                                            <div class="text-sm text-gray-500 mt-2 flex items-center">
                                                                <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>
                                                                {{ Str::limit($tempat->alamat, 25) }}
                                                            </div>
                                                            <div class="text-sm text-gray-500 mt-2 flex items-center">
                                                                <i class="fas fa-users mr-2 text-orange-500"></i>
                                                                Kuota: <strong class="ml-1 {{ $sisa < 3 ? 'text-red-600' : 'text-green-600' }}">{{ $sisa }}</strong> dari {{ $tempat->kuota_mahasiswa }}
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Empty state saat tidak ada hasil pencarian --}}
                                        <div x-cloak class="text-center py-8 bg-gray-50 rounded-xl"
                                             x-show="
                                                q !== '' &&
                                                !Array.from(document.querySelectorAll('#grid-tempat > div'))
                                                    .some(el => getComputedStyle(el).display !== 'none')
                                             ">
                                            <dotlottie-player 
                                                src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                                background="transparent" 
                                                speed="1" 
                                                style="width: 150px; height: 150px; margin: 0 auto;" 
                                                loop 
                                                autoplay>
                                            </dotlottie-player>
                                            <p class="text-gray-500 mt-4">Tidak ada tempat yang cocok dengan pencarian.</p>
                                        </div>
                                    </div>

                                    {{-- Mencari Tempat Sendiri --}}
                                    <div class="p-4 bg-white rounded-xl border-2 border-green-300 hover:border-green-500 transition duration-200">
                                        <label class="flex items-start cursor-pointer">
                                            <input type="radio" name="pilihan_tempat" value="3" required
                                                   class="mt-1 text-green-500 focus:ring-green-500 w-5 h-5"
                                                   onchange="toggleCustomInput(true); validateForm();">
                                            <div class="ml-3 flex-1">
                                                <div class="flex items-center">
                                                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                                                        <i class="fas fa-search-plus text-green-600"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-gray-900">Mencari Tempat Magang Sendiri</div>
                                                        <div class="text-sm text-gray-600">Anda dapat mencari dan mengajukan tempat magang sendiri</div>
                                                    </div>
                                                </div>

                                                <div id="custom-input" class="mt-6 space-y-6 hidden">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan/Instansi *</label>
                                                            <input type="text" name="tempat_magang_sendiri"
                                                                   class="w-full px-4 py-3 border-2 border-green-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 transition duration-200"
                                                                   value="{{ old('tempat_magang_sendiri') }}" required
                                                                   placeholder="Nama perusahaan">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-2">Bidang Usaha *</label>
                                                            <input type="text" name="bidang_usaha_sendiri"
                                                                   class="w-full px-4 py-3 border-2 border-green-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 transition duration-200"
                                                                   value="{{ old('bidang_usaha_sendiri') }}" required
                                                                   placeholder="Bidang usaha">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                                                        <textarea name="alamat_tempat_sendiri" rows="3"
                                                                  class="w-full px-4 py-3 border-2 border-green-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 transition duration-200" required
                                                                  placeholder="Alamat lengkap perusahaan">{{ old('alamat_tempat_sendiri') }}</textarea>
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Perusahaan *</label>
                                                            <input type="email" name="email_perusahaan_sendiri"
                                                                   class="w-full px-4 py-3 border-2 border-green-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 transition duration-200"
                                                                   value="{{ old('email_perusahaan_sendiri') }}" required
                                                                   placeholder="email@perusahaan.com">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon Perusahaan *</label>
                                                            <input type="text" name="telepon_perusahaan_sendiri"
                                                                   class="w-full px-4 py-3 border-2 border-green-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 transition duration-200"
                                                                   value="{{ old('telepon_perusahaan_sendiri') }}" required
                                                                   placeholder="Nomor telepon">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-2">Kontak Person *</label>
                                                            <input type="text" name="kontak_tempat_sendiri"
                                                                   class="w-full px-4 py-3 border-2 border-green-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 transition duration-200"
                                                                   value="{{ old('kontak_tempat_sendiri') }}" required
                                                                   placeholder="Nama kontak person">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-2">Kuota Mahasiswa *</label>
                                                            <input type="number" name="kuota_mahasiswa_sendiri" min="1" max="50"
                                                                   class="w-full px-4 py-3 border-2 border-green-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 transition duration-200"
                                                                   value="{{ old('kuota_mahasiswa_sendiri', 1) }}" required>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Perusahaan</label>
                                                        <textarea name="deskripsi_perusahaan_sendiri" rows="3"
                                                                  class="w-full px-4 py-3 border-2 border-green-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 transition duration-200"
                                                                  placeholder="Deskripsi singkat perusahaan">{{ old('deskripsi_perusahaan_sendiri') }}</textarea>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai KP *</label>
                                                        <input type="date" name="tanggal_mulai"
                                                               class="w-full px-4 py-3 border-2 border-green-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-20 transition duration-200"
                                                               value="{{ old('tanggal_mulai') }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Hidden id untuk pilihan prodi --}}
                                <input type="hidden" name="tempat_magang_id" id="tempat_magang_id" value="{{ old('tempat_magang_id') }}">

                                @error('pilihan_tempat')  <p class="text-red-500 text-sm mt-2 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                                @error('tempat_magang_id') <p class="text-red-500 text-sm mt-2 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>

                            {{-- SUBMIT --}}
                            <div class="flex justify-end pt-6">
                                <button type="submit" id="submit-btn"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-lg transition duration-200 flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-paper-plane mr-3"></i> Kirim Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                {{-- STATUS PENGAJUAN --}}
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-unib-blue-100 mb-8 animate-fade-in-up">
                    <div class="p-8 bg-white">
                        <div class="flex items-center mb-8">
                            <div class="bg-gradient-to-r from-unib-blue-500 to-unib-blue-600 rounded-xl mr-4 w-14 h-14 flex items-center justify-center shadow-sm">
                                <i class="fas fa-clipboard-check text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Status Pengajuan Kerja Praktek</h3>
                                <p class="text-gray-600 mt-1">Informasi lengkap mengenai pengajuan kerja praktek Anda</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {{-- Info Pengajuan --}}
                            <div class="space-y-6">
                                <div class="bg-gradient-to-r from-unib-blue-50 to-blue-50 rounded-xl p-6 border border-unib-blue-200 transition-all duration-300">
                                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-info-circle text-unib-blue-600 mr-2"></i>
                                        Informasi Pengajuan
                                    </h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Judul KP</label>
                                            <p class="text-gray-900 mt-1 font-medium">{{ $kerjaPraktek->judul_kp }}</p>
                                        </div>

                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Tempat Magang</label>
                                            <p class="text-gray-900 mt-1 font-medium">
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
                                                <p class="text-gray-900 mt-1 font-medium">{{ $kerjaPraktek->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}</p>
                                            </div>
                                        @endif

                                        @if($kerjaPraktek->tanggal_selesai)
                                            <div>
                                                <label class="text-sm font-medium text-gray-600">Tanggal Selesai</label>
                                                <p class="text-gray-900 mt-1 font-medium">{{ $kerjaPraktek->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Status + Progress --}}
                            <div class="space-y-6">
                                {{-- Status --}}
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border-2 border-gray-200 transition-all duration-300">
                                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-tasks text-unib-blue-600 mr-2"></i>
                                        Status Pengajuan
                                    </h4>
                                    <div class="mt-2">
                                        @php
                                            $s = $kerjaPraktek->status;
                                            $displayStatus = $s;
                                            if ($s === 'sedang_kp' && $kerjaPraktek->nilai_akhir && $kerjaPraktek->file_laporan) {
                                                $displayStatus = 'selesai';
                                            }
                                        @endphp
                                        @if($s === \App\Models\KerjaPraktek::STATUS_PENGAJUAN)
                                            <div class="flex items-center p-4 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border border-yellow-200">
                                                <i class="fas fa-clock text-yellow-600 text-2xl mr-4"></i>
                                                <div>
                                                    <div class="font-semibold text-yellow-800">Menunggu Persetujuan</div>
                                                    <div class="text-sm text-yellow-600 mt-1">Pengajuan Anda sedang dalam proses review</div>
                                                </div>
                                            </div>
                                        @elseif($s === \App\Models\KerjaPraktek::STATUS_DISETUJUI)
                                            <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                                <i class="fas fa-check-circle text-green-600 text-2xl mr-4"></i>
                                                <div>
                                                    <div class="font-semibold text-green-800">Disetujui</div>
                                                    <div class="text-sm text-green-600 mt-1">Pengajuan Anda telah disetujui</div>
                                                </div>
                                            </div>
                                        @elseif($s === \App\Models\KerjaPraktek::STATUS_SEDANG_KP && $displayStatus === 'sedang_kp')
                                            <div class="flex items-center p-4 bg-gradient-to-r from-unib-blue-50 to-blue-50 rounded-xl border border-unib-blue-200">
                                                <i class="fas fa-play-circle text-unib-blue-600 text-2xl mr-4"></i>
                                                <div>
                                                    <div class="font-semibold text-unib-blue-800">Sedang KP</div>
                                                    <div class="text-sm text-unib-blue-600 mt-1">Anda sedang melaksanakan kerja praktek</div>
                                                </div>
                                            </div>
                                        @elseif($displayStatus === 'selesai')
                                            <div class="space-y-3">
                                                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                                                    <i class="fas fa-flag-checkered text-gray-600 text-2xl mr-4"></i>
                                                    <div>
                                                        <div class="font-semibold text-gray-800">Selesai</div>
                                                        <div class="text-sm text-gray-600 mt-1">Kerja praktek telah selesai</div>
                                                    </div>
                                                </div>
                                                @if($kerjaPraktek->nilai_akhir && !$kerjaPraktek->lulus_ujian)
                                                    <div class="flex items-center p-4 bg-gradient-to-r from-red-50 to-pink-50 rounded-xl border border-red-200">
                                                        <i class="fas fa-times-circle text-red-600 text-2xl mr-4"></i>
                                                        <div>
                                                            <div class="font-semibold text-red-800">TIDAK LULUS</div>
                                                            <div class="text-sm text-red-600 mt-1">Anda perlu mengulang kerja praktek</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($s === \App\Models\KerjaPraktek::STATUS_DITOLAK)
                                            <div class="space-y-3">
                                                <div class="flex items-center p-4 bg-gradient-to-r from-red-50 to-pink-50 rounded-xl border border-red-200">
                                                    <i class="fas fa-times-circle text-red-600 text-2xl mr-4"></i>
                                                    <div>
                                                        <div class="font-semibold text-red-800">Ditolak</div>
                                                        <div class="text-sm text-red-600 mt-1">Pengajuan Anda ditolak</div>
                                                    </div>
                                                </div>
                                                <a href="{{ route('mahasiswa.kerja-praktek.edit', $kerjaPraktek) }}"
                                                   class="flex items-center justify-center p-3 bg-gradient-to-r from-unib-blue-50 to-blue-50 hover:from-unib-blue-100 hover:to-blue-100 rounded-xl border border-unib-blue-200 text-unib-blue-700 font-medium transition duration-200">
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
                                        elseif ($ipk >= 3.00) { $badge='bg-unib-blue-100 text-unib-blue-800'; $label='Baik'; }
                                        elseif ($ipk >= 2.50) { $badge='bg-yellow-100 text-yellow-800'; $label='Cukup'; }
                                        else { $badge='bg-red-100 text-red-800'; $label='Perlu Perhatian'; }
                                    }
                                @endphp
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200 transition-all duration-300">
                                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-chart-line text-unib-blue-600 mr-2"></i>
                                        Data Akademik
                                    </h4>
                                    <div class="flex items-center justify-between">
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600">Semester Ke</p>
                                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $semester ?? '-' }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600">IPK Semester</p>
                                            <div class="flex items-center gap-2 justify-center mt-1">
                                                <p class="text-2xl font-bold text-gray-900">{{ is_null($ipk) ? '-' : number_format($ipk, 2) }}</p>
                                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $badge }} border">{{ $label }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Upload Laporan --}}
                                @if(in_array($kerjaPraktek->status, [\App\Models\KerjaPraktek::STATUS_SEDANG_KP,\App\Models\KerjaPraktek::STATUS_SELESAI], true))
                                    <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6 border-2 border-red-200 transition-all duration-300">
                                        <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                            Laporan KP
                                        </h4>
                                        <div class="mt-2">
                                            @if($kerjaPraktek->file_laporan)
                                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-file-pdf text-red-500 text-xl mr-3"></i>
                                                        <div>
                                                            <a href="{{ Storage::url($kerjaPraktek->file_laporan) }}" target="_blank" 
                                                               class="text-green-700 hover:text-green-800 font-medium">Lihat Laporan</a>
                                                            @if($kerjaPraktek->acc_pembimbing_laporan)
                                                                <div class="text-sm text-green-600 mt-1 flex items-center">
                                                                    <i class="fas fa-check-circle mr-1"></i> ACC Pembimbing
                                                                </div>
                                                            @else
                                                                <div class="text-sm text-yellow-600 mt-1 flex items-center">
                                                                    <i class="fas fa-clock mr-1"></i> Menunggu ACC
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form method="POST" action="{{ route('mahasiswa.kerja-praktek.upload-laporan', $kerjaPraktek) }}" enctype="multipart/form-data" class="space-y-4">
                                                    @csrf
                                                    <div class="flex items-center space-x-4">
                                                        <div class="flex-1">
                                                            <input type="file" name="file_laporan" accept=".pdf" required
                                                                class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-unib-blue-500 file:to-unib-blue-600 file:text-white hover:file:from-unib-blue-700 hover:file:to-unib-blue-800 transition duration-200">
                                                        </div>
                                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition duration-200 shadow-lg">
                                                            <i class="fas fa-upload mr-2"></i> Upload
                                                        </button>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Seminar Registration --}}
                                @if($kerjaPraktek->acc_pembimbing_laporan && $kerjaPraktek->file_laporan)
                                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl p-6 border-2 border-orange-200 transition-all duration-300">
                                        <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                            <i class="fas fa-microphone text-orange-500 mr-2"></i>
                                            Pendaftaran Seminar
                                        </h4>
                                        <div class="mt-2">
                                            @if($kerjaPraktek->pendaftaran_seminar)
                                                @if($kerjaPraktek->acc_pendaftaran_seminar)
                                                    <div class="space-y-3 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                                        <div class="flex items-center text-green-700">
                                                            <i class="fas fa-check-circle mr-2"></i>
                                                            <span class="font-medium">Pendaftaran Seminar Disetujui</span>
                                                        </div>
                                                        @if($kerjaPraktek->jadwal_seminar)
                                                            <div class="text-sm text-green-600 space-y-1">
                                                                <div class="flex items-center">
                                                                    <i class="fas fa-calendar mr-2"></i>
                                                                    Jadwal: {{ $kerjaPraktek->jadwal_seminar->locale('id')->translatedFormat('d F Y \p\u\k\u\l H:i') }} WIB
                                                                </div>
                                                                @if($kerjaPraktek->ruangan_seminar)
                                                                    <div class="flex items-center">
                                                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                                                        Ruangan: {{ $kerjaPraktek->ruangan_seminar }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="flex items-center p-4 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border border-yellow-200">
                                                        <i class="fas fa-clock text-yellow-600 mr-3"></i>
                                                        <div>
                                                            <div class="text-yellow-700 font-medium">Menunggu Jadwal Seminar</div>
                                                            @if($kerjaPraktek->tanggal_daftar_seminar)
                                                                <div class="text-sm text-yellow-600 mt-1">
                                                                    Didaftarkan pada: {{ $kerjaPraktek->tanggal_daftar_seminar->locale('id')->translatedFormat('d F Y') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                @if($kerjaPraktek->canRegisterSeminar())
                                                    <form method="POST" action="{{ route('mahasiswa.kerja-praktek.daftar-seminar', $kerjaPraktek) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl font-medium shadow-lg transition duration-200 flex items-center">
                                                            <i class="fas fa-calendar-plus mr-2"></i> Daftar Seminar
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="text-center p-4 bg-gray-50 rounded-xl border border-gray-200">
                                                        <i class="fas fa-info-circle text-gray-400 text-xl mb-2"></i>
                                                        <p class="text-gray-500 text-sm">Belum memenuhi syarat untuk mendaftar seminar</p>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Upload Revisi --}}
                                @if($kerjaPraktek->acc_seminar && $kerjaPraktek->rata_rata_seminar)
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border-2 border-green-200 transition-all duration-300">
                                        <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                            <i class="fas fa-edit text-green-500 mr-2"></i>
                                            Upload Revisi Laporan
                                        </h4>
                                        <div class="mt-2">
                                            @if($kerjaPraktek->file_revisi)
                                                <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                                    <i class="fas fa-file-pdf text-red-500 text-xl mr-3"></i>
                                                    <a href="{{ Storage::url($kerjaPraktek->file_revisi) }}" target="_blank" class="text-green-700 hover:text-green-800 font-medium">Lihat Revisi</a>
                                                </div>
                                            @else
                                                <form method="POST" action="{{ route('mahasiswa.kerja-praktek.upload-revisi', $kerjaPraktek) }}" enctype="multipart/form-data" class="space-y-4">
                                                    @csrf
                                                    <div class="flex items-center space-x-4">
                                                        <div class="flex-1">
                                                            <input type="file" name="file_revisi" accept=".pdf" required
                                                                class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-green-500 file:to-green-600 file:text-white hover:file:from-green-600 hover:file:to-green-700 transition duration-200">
                                                        </div>
                                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-medium transition duration-200 shadow-lg">
                                                            <i class="fas fa-upload mr-2"></i> Upload
                                                        </button>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Kuisioner --}}
                                @if($kerjaPraktek->status === \App\Models\KerjaPraktek::STATUS_SELESAI && $kerjaPraktek->file_laporan)
                                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border-2 border-purple-200 transition-all duration-300">
                                        <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                            <i class="fas fa-poll text-purple-500 mr-2"></i>
                                            Evaluasi
                                        </h4>
                                        <div class="mt-2">
                                            @if($kerjaPraktek->kuisioner)
                                                <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                                    <span class="text-green-700 font-medium">Kuisioner sudah diisi</span>
                                                </div>
                                            @else
                                                <a href="{{ route('mahasiswa.kerja-praktek.kuisioner', $kerjaPraktek) }}"
                                                   class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-xl font-medium transition duration-200 shadow-lg flex items-center justify-center">
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
                            <div class="mt-8 pt-8 border-t border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-sticky-note text-unib-blue-600 mr-2"></i>
                                    Catatan
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @if($kerjaPraktek->catatan_dosen)
                                        <div class="bg-gradient-to-r from-unib-blue-50 to-blue-50 rounded-xl p-4 border border-unib-blue-200 transition-all duration-300">
                                            <div class="flex items-center mb-2">
                                                <i class="fas fa-chalkboard-teacher text-unib-blue-600 mr-2"></i>
                                                <p class="text-sm font-medium text-unib-blue-800">Catatan Dosen:</p>
                                            </div>
                                            <p class="text-gray-900">{{ $kerjaPraktek->catatan_dosen }}</p>
                                        </div>
                                    @endif
                                    @if($kerjaPraktek->catatan_pengawas)
                                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200 transition-all duration-300">
                                            <div class="flex items-center mb-2">
                                                <i class="fas fa-user-tie text-green-600 mr-2"></i>
                                                <p class="text-sm font-medium text-green-800">Catatan Pengawas:</p>
                                            </div>
                                            <p class="text-gray-900">{{ $kerjaPraktek->catatan_pengawas }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Lottie Player Script --}}
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

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
        
        /* Smooth transitions without movement */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
        
        /* Hover effects without transform */
        .hover\:shadow-lg:hover {
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .hover\:shadow-xl:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>

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