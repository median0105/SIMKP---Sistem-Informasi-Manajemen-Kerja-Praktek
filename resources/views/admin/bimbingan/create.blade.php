<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.bimbingan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Bimbingan
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash / errors --}}
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                    <div class="font-semibold mb-1">Periksa input Anda:</div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form tambah bimbingan --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Buat Bimbingan Baru</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.bimbingan.store') }}" class="grid grid-cols-1 gap-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Mahasiswa *</label>
                            <select name="mahasiswa_id" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswa as $mhs)
                                    <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                        {{ $mhs->name }} ({{ $mhs->npm }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Pilih mahasiswa yang akan dibimbing.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu *</label>
                            <input type="datetime-local" name="tanggal_bimbingan" required
                                   value="{{ old('tanggal_bimbingan') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Tentukan waktu bimbingan.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Topik Bimbingan *</label>
                            <input type="text" name="topik_bimbingan" required maxlength="255"
                                   value="{{ old('topik_bimbingan') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan / Ringkasan *</label>
                            <textarea name="catatan_dosen" rows="5" required
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                      placeholder="Jelaskan materi, panduan, atau catatan bimbingan...">{{ old('catatan_dosen') }}</textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="status_verifikasi" id="status_verifikasi" value="1"
                                   {{ old('status_verifikasi') ? 'checked' : '' }}
                                   class="h-4 w-4 text-unib-blue-600 focus:ring-unib-blue-500 border-gray-300 rounded">
                            <label for="status_verifikasi" class="ml-2 block text-sm text-gray-900">
                                Tandai sebagai sudah diverifikasi
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.bimbingan.index') }}"
                               class="px-5 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-5 py-2 rounded-lg bg-unib-blue-600 hover:bg-unib-blue-700 text-white">
                                <i class="fas fa-paper-plane mr-2"></i>Buat Bimbingan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-sidebar-layout>
