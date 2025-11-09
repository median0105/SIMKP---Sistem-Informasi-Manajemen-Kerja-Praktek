<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('superadmin.kerja-praktek.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Data Kerja Praktek') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('superadmin.kerja-praktek.update', $kerjaPraktek) }}">
                        @csrf
                        @method('PUT')

                        <!-- Mahasiswa Info -->
                        <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Informasi Mahasiswa</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Mahasiswa</label>
                                    <p class="text-base text-gray-900 bg-white px-3 py-2 rounded border">{{ $kerjaPraktek->mahasiswa->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NPM</label>
                                    <p class="text-base text-gray-900 bg-white px-3 py-2 rounded border">{{ $kerjaPraktek->mahasiswa->npm }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Judul KP -->
                        <div class="mb-6">
                            <label for="judul_kp" class="block text-base font-medium text-gray-700 mb-2">Judul Kerja Praktek</label>
                            <input type="text" name="judul_kp" id="judul_kp" value="{{ old('judul_kp', $kerjaPraktek->judul_kp) }}"
                                   class="block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base @error('judul_kp') border-red-500 @enderror"
                                   required>
                            @error('judul_kp')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tempat Magang -->
                        <div class="mb-8">
                            <label for="tempat_magang_id" class="block text-base font-medium text-gray-700 mb-2">Tempat Magang</label>
                            <select name="tempat_magang_id" id="tempat_magang_id"
                                    class="block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base @error('tempat_magang_id') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Tempat Magang</option>
                                @foreach($tempatMagang as $tm)
                                    <option value="{{ $tm->id }}" {{ old('tempat_magang_id', $kerjaPraktek->tempat_magang_id) == $tm->id ? 'selected' : '' }}>
                                        {{ $tm->nama_perusahaan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tempat_magang_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-6 pt-6">
                            <a href="{{ route('superadmin.kerja-praktek.index') }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-6 rounded-lg text-base transition duration-200">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg text-base transition duration-200">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
