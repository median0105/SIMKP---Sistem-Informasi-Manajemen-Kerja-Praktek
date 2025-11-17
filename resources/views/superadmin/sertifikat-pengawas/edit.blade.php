{{-- resources/views/superadmin/sertifikat-pengawas/edit.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Sertifikat Pengawas Lapangan
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('superadmin.sertifikat-pengawas.update', $sertifikatPengawa) }}">
                    @csrf
                    @method('PUT')

                    {{-- Nama Pengawas --}}
                    <div class="mb-4">
                        <label for="nama_pengawas" class="block text-sm font-medium text-gray-700">Nama Pengawas</label>
                        <input type="text" name="nama_pengawas" id="nama_pengawas" value="{{ old('nama_pengawas', $sertifikatPengawa->nama_pengawas) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                               required>
                        @error('nama_pengawas')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor Sertifikat --}}
                    <div class="mb-4">
                        <label for="nomor_sertifikat" class="block text-sm font-medium text-gray-700">Nomor Sertifikat</label>
                        <input type="text" name="nomor_sertifikat" id="nomor_sertifikat" value="{{ old('nomor_sertifikat', $sertifikatPengawa->nomor_sertifikat) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                               required>
                        @error('nomor_sertifikat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tahun Ajaran --}}
                    <div class="mb-4">
                        <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ old('tahun_ajaran', $sertifikatPengawa->tahun_ajaran) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                               required>
                        @error('tahun_ajaran')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Kaprodi --}}
                    <div class="mb-4">
                        <label for="nama_kaprodi" class="block text-sm font-medium text-gray-700">Nama Kaprodi</label>
                        <input type="text" name="nama_kaprodi" id="nama_kaprodi" value="{{ old('nama_kaprodi', $sertifikatPengawa->nama_kaprodi) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                               required>
                        @error('nama_kaprodi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIP Kaprodi --}}
                    <div class="mb-6">
                        <label for="nip_kaprodi" class="block text-sm font-medium text-gray-700">NIP Kaprodi</label>
                        <input type="text" name="nip_kaprodi" id="nip_kaprodi" value="{{ old('nip_kaprodi', $sertifikatPengawa->nip_kaprodi) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                               required>
                        @error('nip_kaprodi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Warning if already generated --}}
                    @if($sertifikatPengawa->is_generated)
                        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        Sertifikat sudah digenerate
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Perubahan data tidak akan mempengaruhi file PDF yang sudah digenerate. Jika perlu mengubah data, hapus sertifikat dan buat ulang.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Buttons --}}
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('superadmin.sertifikat-pengawas.show', $sertifikatPengawa) }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-sidebar-layout>
