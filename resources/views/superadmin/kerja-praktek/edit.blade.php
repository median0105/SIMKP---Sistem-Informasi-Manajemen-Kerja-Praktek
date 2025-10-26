<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Kerja Praktek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('superadmin.kerja-praktek.update', $kerjaPraktek) }}">
                        @csrf
                        @method('PUT')

                        <!-- Mahasiswa Info -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Mahasiswa</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Mahasiswa</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $kerjaPraktek->mahasiswa->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">NPM</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $kerjaPraktek->mahasiswa->npm }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Judul KP -->
                        <div class="mb-4">
                            <label for="judul_kp" class="block text-sm font-medium text-gray-700">Judul Kerja Praktek</label>
                            <input type="text" name="judul_kp" id="judul_kp" value="{{ old('judul_kp', $kerjaPraktek->judul_kp) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('judul_kp') border-red-500 @enderror"
                                   required>
                            @error('judul_kp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tempat Magang -->
                        <div class="mb-6">
                            <label for="tempat_magang_id" class="block text-sm font-medium text-gray-700">Tempat Magang</label>
                            <select name="tempat_magang_id" id="tempat_magang_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tempat_magang_id') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Tempat Magang</option>
                                @foreach($tempatMagang as $tm)
                                    <option value="{{ $tm->id }}" {{ old('tempat_magang_id', $kerjaPraktek->tempat_magang_id) == $tm->id ? 'selected' : '' }}>
                                        {{ $tm->nama_perusahaan }} - {{ $tm->alamat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tempat_magang_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('superadmin.kerja-praktek.index') }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
