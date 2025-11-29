<x-sidebar-layout>
    {{-- Header section dengan UNIB blue gradient --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.bimbingan.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center backdrop-blur-sm transition duration-200 border border-white/30 text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <!-- Ikon dihapus sesuai pattern -->
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Tambah Bimbingan
                    </h2>
                   
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area dengan gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Flash / errors dengan style yang konsisten --}}
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-red-800 transform transition-all duration-300 animate-fade-in-up">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 transform transition-all duration-300 animate-fade-in-up">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-400 mt-1 mr-3"></i>
                        <div>
                            <div class="font-semibold text-red-800 mb-2">Periksa input Anda:</div>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form tambah bimbingan dengan style kartu yang konsisten --}}
            <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white">
                    <h3 class="text-lg font-semibold">Buat Bimbingan Baru</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.bimbingan.store') }}" class="grid grid-cols-1 gap-6">
                        @csrf

                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">Pilih Mahasiswa *</label>
                            <select name="mahasiswa_id" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswa as $mhs)
                                    <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                        {{ $mhs->name }} ({{ $mhs->npm }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 mt-2">Pilih mahasiswa yang akan dibimbing.</p>
                        </div>

                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">Tanggal & Waktu *</label>
                            <input type="datetime-local" name="tanggal_bimbingan" required
                                   value="{{ old('tanggal_bimbingan') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            <p class="text-sm text-gray-500 mt-2">Tentukan waktu bimbingan.</p>
                        </div>

                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">Topik Bimbingan *</label>
                            <input type="text" name="topik_bimbingan" required maxlength="255"
                                   value="{{ old('topik_bimbingan') }}"
                                   placeholder="Masukkan topik bimbingan..."
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                        </div>

                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">Catatan / Ringkasan *</label>
                            <textarea name="catatan_dosen" rows="5" required
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                      placeholder="Jelaskan materi, panduan, atau catatan bimbingan...">{{ old('catatan_dosen') }}</textarea>
                        </div>

                        <div class="flex items-center p-4 bg-unib-blue-50 rounded-lg border border-unib-blue-100">
                            <input type="checkbox" name="status_verifikasi" id="status_verifikasi" value="1"
                                   {{ old('status_verifikasi') ? 'checked' : '' }}
                                   class="h-5 w-5 text-unib-blue-600 focus:ring-unib-blue-500 border-gray-300 rounded">
                            <label for="status_verifikasi" class="ml-3 block text-base font-medium text-gray-900">
                                Tandai sebagai sudah diverifikasi
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.bimbingan.index') }}"
                               class="px-6 py-3 rounded-lg bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium shadow-md transform hover:scale-105 transition duration-200">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-6 py-3 rounded-lg bg-unib-blue-600 hover:bg-unib-blue-700 text-white font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                               Buat Bimbingan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

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
    </style>
</x-sidebar-layout>