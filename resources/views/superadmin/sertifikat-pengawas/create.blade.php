{{-- resources/views/superadmin/sertifikat-pengawas/create.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Sertifikat Pengawas Lapangan
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('superadmin.sertifikat-pengawas.store') }}">
                    @csrf

                    {{-- Nama Pengawas --}}
                    <div class="mb-4">
                        <label for="nama_pengawas" class="block text-sm font-medium text-gray-700">Nama Pengawas</label>
                        <select name="nama_pengawas" id="nama_pengawas"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
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
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($pengawas->count() == 0)
                            <p class="mt-1 text-sm text-orange-600">Semua pengawas lapangan sudah memiliki sertifikat. Jika ingin membuat sertifikat baru, silakan hapus sertifikat yang sudah ada terlebih dahulu.</p>
                        @endif
                    </div>

                    {{-- Nomor Sertifikat --}}
                    <div class="mb-4">
                        <label for="nomor_sertifikat" class="block text-sm font-medium text-gray-700">Nomor Sertifikat</label>
                        <input type="text" name="nomor_sertifikat" id="nomor_sertifikat" value="{{ old('nomor_sertifikat') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                               required>
                        @error('nomor_sertifikat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tahun Ajaran --}}
                    <div class="mb-4">
                        <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ old('tahun_ajaran') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                               required>
                        @error('tahun_ajaran')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Kaprodi --}}
                    <div class="mb-4">
                        <label for="nama_kaprodi" class="block text-sm font-medium text-gray-700">Nama Kaprodi</label>
                        <input type="text" name="nama_kaprodi" id="nama_kaprodi" value="{{ old('nama_kaprodi') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                               required>
                        @error('nama_kaprodi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIP Kaprodi --}}
                    <div class="mb-6">
                        <label for="nip_kaprodi" class="block text-sm font-medium text-gray-700">NIP Kaprodi</label>
                        <input type="text" name="nip_kaprodi" id="nip_kaprodi" value="{{ old('nip_kaprodi') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                               required>
                        @error('nip_kaprodi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('superadmin.sertifikat-pengawas.index') }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-sidebar-layout>
