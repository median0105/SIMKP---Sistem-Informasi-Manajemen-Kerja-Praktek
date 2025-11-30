
<x-sidebar-layout>
    {{-- HEADER --}}
<x-slot name="header">
    <div class="flex items-center justify-between bg-unib-blue-600 text-white p-4 rounded-lg shadow-lg">
        <div class="flex items-center space-x-3">
            <div>
                <h2 class="font-bold text-xl leading-tight">
                    Kegiatan Saya
                </h2>
                <!-- Subtitle bisa ditambahkan di sini -->
            </div>
        </div>
    </div>
</x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Flash Messages --}}
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    <div>
                        <strong class="font-bold">Terjadi kesalahan:</strong>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form Tambah Kegiatan --}}
            <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tambah Kegiatan Baru</h3>
                <form method="POST" action="{{ route('mahasiswa.kegiatan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                            <input type="date" name="tanggal_kegiatan" required 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (jam)</label>
                            <input type="number" name="durasi_jam" min="1" required 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Dokumentasi</label>
                            <input type="file" name="file_dokumentasi" required 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-unib-blue-50 file:text-unib-blue-700 hover:file:bg-unib-blue-100">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kegiatan</label>
                            <textarea name="deskripsi_kegiatan" rows="3" required 
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                      placeholder="Deskripsikan kegiatan yang dilakukan..."></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center">
                            <i class="fas fa-save mr-2"></i>Simpan Kegiatan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tabel Kegiatan --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Daftar Kegiatan
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $kegiatan->total() }}
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                    Durasi
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                    Dokumentasi
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                    Deskripsi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($kegiatan as $k)
                                <tr class="hover:bg-unib-blue-50 transition duration-150 group">
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                        {{ $k->tanggal_kegiatan->locale('id')->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300 shadow-sm">
                                            {{ $k->durasi_jam }} jam
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($k->file_dokumentasi)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::url($k->file_dokumentasi) }}" target="_blank" class="transform hover:scale-105 transition duration-200">
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($k->file_dokumentasi) }}" alt="Bukti" class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-75 border border-unib-blue-200">
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-sm">Tidak ada bukti</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $k->deskripsi_kegiatan }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12">
                                        <div class="flex flex-col items-center justify-center">
                                            <dotlottie-player 
                                                src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                                background="transparent" 
                                                speed="1" 
                                                style="width: 200px; height: 200px; margin: 0 auto;" 
                                                loop 
                                                autoplay>
                                            </dotlottie-player>
                                            <div class="text-base font-medium text-gray-900 mb-2">Belum ada kegiatan</div>
                                            <p class="text-sm text-gray-600">Mulai tambahkan kegiatan magang Anda</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-6 border-t bg-unib-blue-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-unib-blue-700">
                            Menampilkan {{ $kegiatan->firstItem() }} - {{ $kegiatan->lastItem() }} dari {{ $kegiatan->total() }} hasil
                        </p>
                        <div class="flex space-x-1">
                            {{ $kegiatan->links() }}
                        </div>
                    </div>
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