<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kerja Praktek Ditolak') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Edit Pengajuan Kerja Praktek</h3>

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

                    <form method="POST" action="{{ route('mahasiswa.kerja-praktek.update', $kerjaPraktek) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- JUDUL KP --}}
                        <div>
                            <label for="judul_kp" class="block text-sm font-medium text-gray-700 mb-2">Judul Kerja Praktek *</label>
                            <input type="text" name="judul_kp" id="judul_kp"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                   value="{{ old('judul_kp', $kerjaPraktek->judul_kp) }}" required>
                            @error('judul_kp') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- UPLOAD PROPOSAL --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Proposal Kerja Praktek *</label>
                            <input type="file" name="file_proposal" accept=".pdf" required
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                            <p class="text-xs text-gray-500 mt-1">Format: PDF, Maksimal 10MB</p>
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
                                                           " {{ $kerjaPraktek->tempat_magang_id == $tempat->id ? 'checked' : '' }}>
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
                                               onchange="toggleCustomInput(true)" onclick="toggleCustomInput(true)" {{ $kerjaPraktek->pilihan_tempat == 3 ? 'checked' : '' }}>
                                        <div class="ml-3 flex-1">
                                            <div class="font-medium text-gray-900">Mencari Tempat Magang Sendiri</div>
                                            <div class="text-sm text-gray-600 mt-1">Anda dapat mencari dan mengajukan tempat magang sendiri</div>

                                            <div id="custom-input" class="mt-4 space-y-4 {{ $kerjaPraktek->pilihan_tempat == 3 ? '' : 'hidden' }}">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan/Instansi</label>
                                                    <input type="text" name="tempat_magang_sendiri"
                                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                           value="{{ old('tempat_magang_sendiri', $kerjaPraktek->tempat_magang_sendiri) }}">
                                                    @error('tempat_magang_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                                    <textarea name="alamat_tempat_sendiri" rows="3"
                                                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">{{ old('alamat_tempat_sendiri', $kerjaPraktek->alamat_tempat_sendiri) }}</textarea>
                                                    @error('alamat_tempat_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kontak Person</label>
                                                    <input type="text" name="kontak_tempat_sendiri"
                                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                           value="{{ old('kontak_tempat_sendiri', $kerjaPraktek->kontak_tempat_sendiri) }}">
                                                    @error('kontak_tempat_sendiri') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai KP</label>
                                                    <input type="date" name="tanggal_mulai"
                                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                                           value="{{ old('tanggal_mulai', $kerjaPraktek->tanggal_mulai ? $kerjaPraktek->tanggal_mulai->format('Y-m-d') : '') }}">
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

                        {{-- SUBMIT --}}
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('mahasiswa.kerja-praktek.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-medium transition duration-200">
                                Batal
                            </a>

                            <button type="submit"
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                <i class="fas fa-paper-plane mr-2"></i> Perbarui Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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

        // Set initial state berdasarkan pilihan_tempat
        document.addEventListener('DOMContentLoaded', () => {
            const pilihanTempat = '{{ $kerjaPraktek->pilihan_tempat }}';
            toggleCustomInput(pilihanTempat == '3');
        });
    </script>
</x-app-layout>
