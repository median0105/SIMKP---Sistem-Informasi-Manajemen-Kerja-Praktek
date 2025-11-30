{{-- resources/views/superadmin/sertifikat-pengawas/create.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('superadmin.sertifikat-pengawas.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 transform hover:scale-105 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="flex items-center space-x-3">
                    <div>
                        <h2 class="font-bold text-xl leading-tight">
                            Tambah Sertifikat Pengawas Lapangan
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Flash Messages --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center mb-6">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    <div>
                        <p class="font-medium">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside mt-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Card Form --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-unib-blue-100">
                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Form Tambah Sertifikat
                    </h3>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    <form method="POST" action="{{ route('superadmin.sertifikat-pengawas.store') }}" class="space-y-6">
                        @csrf

                        {{-- Nama Pengawas --}}
                        <div>
                            <label for="nama_pengawas" class="block text-base font-semibold text-gray-700 mb-3">
                                Nama Pengawas <span class="text-red-500">*</span>
                            </label>
                            <select name="nama_pengawas" id="nama_pengawas"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                    required>
                                <option value="">Pilih Pengawas Lapangan</option>
                                @if($pengawas->count() > 0)
                                    @foreach($pengawas as $p)
                                        <option value="{{ $p->name }}" {{ old('nama_pengawas') == $p->name ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Semua pengawas sudah memiliki sertifikat</option>
                                @endif
                            </select>
                            @error('nama_pengawas')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                            @if($pengawas->count() == 0)
                                <p class="text-sm text-orange-600 mt-2 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Semua pengawas lapangan sudah memiliki sertifikat. Jika ingin membuat sertifikat baru, silakan hapus sertifikat yang sudah ada terlebih dahulu.
                                </p>
                            @endif
                        </div>

                        {{-- Nomor Sertifikat --}}
                        <div>
                            <label for="nomor_sertifikat" class="block text-base font-semibold text-gray-700 mb-3">
                                Nomor Sertifikat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nomor_sertifikat" id="nomor_sertifikat" value="{{ old('nomor_sertifikat') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                   required>
                            @error('nomor_sertifikat')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Tahun Ajaran --}}
                        <div>
                            <label for="tahun_ajaran" class="block text-base font-semibold text-gray-700 mb-3">
                                Tahun Ajaran <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ old('tahun_ajaran') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                   required>
                            @error('tahun_ajaran')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Nama Kaprodi --}}
                        <div>
                            <label for="nama_kaprodi" class="block text-base font-semibold text-gray-700 mb-3">
                                Nama Kaprodi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_kaprodi" id="nama_kaprodi" value="{{ old('nama_kaprodi') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                   required>
                            @error('nama_kaprodi')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- NIP Kaprodi --}}
                        <div>
                            <label for="nip_kaprodi" class="block text-base font-semibold text-gray-700 mb-3">
                                NIP Kaprodi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nip_kaprodi" id="nip_kaprodi" value="{{ old('nip_kaprodi') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                   required>
                            @error('nip_kaprodi')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <button type="submit"
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-8 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center">
                                <i class="fas fa-save mr-2"></i>Simpan Sertifikat
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-sidebar-layout>