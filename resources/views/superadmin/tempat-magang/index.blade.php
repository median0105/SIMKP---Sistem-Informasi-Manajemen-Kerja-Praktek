{{-- resources/views/superadmin/tempat-magang/index.blade.php --}}
<x-sidebar-layout>

    {{-- HEADER dengan UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Manajemen Tempat Magang
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- BACKGROUND utama dengan gradient UNIB --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Tombol Tambah --}}
            <div class="flex justify-end animate-fade-in-up">
                <a href="{{ route('superadmin.tempat-magang.create') }}"
                   class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Tempat Magang
                </a>
            </div>
        
            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- Total Tempat --}}
                <div class="bg-white shadow-xl rounded-xl border border-unib-blue-100 p-6 transition duration-200 animate-fade-in-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Tempat</p>
                            <p class="text-2xl font-bold text-unib-blue-600 mt-2">{{ $stats['total_tempat'] }}</p>
                        </div>
                        <div class="bg-unib-blue-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-building text-unib-blue-600 text-lg"></i>
                        </div>
                    </div>
                </div>

                {{-- Tempat Aktif --}}
                <div class="bg-white shadow-xl rounded-xl border border-unib-blue-100 p-6 transition duration-200 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tempat Aktif</p>
                            <p class="text-2xl font-bold text-green-600 mt-2">{{ $stats['tempat_aktif'] }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-lg"></i>
                        </div>
                    </div>
                </div>

                {{-- Total Kuota --}}
                <div class="bg-white shadow-xl rounded-xl border border-unib-blue-100 p-6 transition duration-200 animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Kuota</p>
                            <p class="text-2xl font-bold text-teknik-orange-600 mt-2">{{ $stats['total_kuota'] }}</p>
                        </div>
                        <div class="bg-teknik-orange-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-users text-teknik-orange-600 text-lg"></i>
                        </div>
                    </div>
                </div>

                {{-- Terpakai --}}
                <div class="bg-white shadow-xl rounded-xl border border-unib-blue-100 p-6 transition duration-200 animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Terpakai</p>
                            <p class="text-2xl font-bold text-purple-600 mt-2">{{ $stats['tempat_terpakai'] }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-user-check text-purple-600 text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            

            {{-- FILTER --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <form method="GET" class="flex flex-wrap gap-4">

                    <div class="flex-1 min-w-64">
         
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama perusahaan / bidang / alamat..."
                               class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-3 focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>

                    <div>
            
                        <select name="status"
                                class="border-gray-300 rounded-lg shadow-sm px-4 py-3 focus:border-unib-blue-500 focus:ring-unib-blue-500">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status')=='active'?'selected':'' }}>Aktif</option>
                            <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div>
                    
                        <select name="bidang_usaha"
                                class="border-gray-300 rounded-lg shadow-sm px-4 py-3 focus:border-unib-blue-500 focus:ring-unib-blue-500">
                            <option value="">Semua Bidang</option>
                            @foreach($bidangUsaha as $bidang)
                                <option value="{{ $bidang }}" {{ request('bidang_usaha')==$bidang?'selected':'' }}>
                                    {{ $bidang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-3">
                        <button class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg shadow-md font-medium transform hover:scale-105 transition duration-200 flex items-center">
                            <i class="fas fa-search mr-2"></i>Cari
                        </button>

                        @if(request()->anyFilled(['search','status','bidang_usaha']))
                            <a href="{{ route('superadmin.tempat-magang.index') }}"
                               class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg shadow-md font-medium transform hover:scale-105 transition duration-200 flex items-center">
                                <i class="fas fa-undo mr-2"></i>Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            
            {{-- TABEL --}}
            <div class="bg-white shadow-xl rounded-xl border border-unib-blue-100 overflow-hidden transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">

                {{-- Header --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Daftar Tempat Magang
                    </h3>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        <i class="fas fa-building mr-2"></i>
                        Total: {{ $tempatMagang->total() }}
                    </span>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="w-5 h-5 rounded border-gray-300 text-unib-blue-600 focus:ring-unib-blue-500">
                            </th>
                            <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Perusahaan
                            </th>
                            <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Bidang
                            </th>
                            <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Kontak
                            </th>
                            <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Kuota
                            </th>
                            <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Mahasiswa
                            </th>
                            <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($tempatMagang as $tempat)
                            <tr class="hover:bg-unib-blue-50 transition duration-150">

                                <td class="px-6 py-4">
                                    <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-unib-blue-600 focus:ring-unib-blue-500">
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">{{ $tempat->nama_perusahaan }}</div>
                                    <div class="text-sm text-gray-500 mt-1 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1 text-xs text-unib-blue-400"></i>
                                        {{ Str::limit($tempat->alamat,50) }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300 shadow-sm">
                                        <i class="fas fa-industry mr-2 text-xs"></i>
                                        {{ $tempat->bidang_usaha }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="font-medium text-gray-900 group-hover:text-unib-blue-700 transition-colors">{{ $tempat->kontak_person }}</div>
                                    <div class="text-gray-500 mt-1 flex items-center">
                                        <i class="fas fa-envelope mr-1 text-xs text-unib-blue-400"></i>
                                        {{ $tempat->email_perusahaan }}
                                    </div>
                                    <div class="text-gray-500 mt-1 flex items-center">
                                        <i class="fas fa-phone mr-1 text-xs text-unib-blue-400"></i>
                                        {{ $tempat->telepon_perusahaan }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">
                                        {{ $tempat->kuota_mahasiswa - $tempat->terpakai_count }}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">dari {{ $tempat->kuota_mahasiswa }}</div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($tempat->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm justify-center">
                                            <i class="fas fa-check-circle mr-2 text-xs"></i>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm justify-center">
                                            <i class="fas fa-times-circle mr-2 text-xs"></i>Tidak Aktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">
                                        {{ $tempat->terpakai_count }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-3">

                                        <a href="{{ route('superadmin.tempat-magang.show',$tempat) }}"
                                           class="text-unib-blue-600 hover:text-unib-blue-800 text-lg transform hover:scale-110 transition duration-200"
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('superadmin.tempat-magang.edit',$tempat) }}"
                                           class="text-green-600 hover:text-green-800 text-lg transform hover:scale-110 transition duration-200"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button onclick="toggleStatus({{ $tempat->id }})"
                                                class="text-teknik-orange-600 hover:text-teknik-orange-800 text-lg transform hover:scale-110 transition duration-200"
                                                title="Toggle Status">
                                            <i class="fas fa-power-off"></i>
                                        </button>

                                        <button onclick="deleteItem({{ $tempat->id }}, '{{ $tempat->nama_perusahaan }}')"
                                                class="text-red-600 hover:text-red-800 text-lg transform hover:scale-110 transition duration-200"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>

                {{-- Pagination dengan style UNIB --}}
                <div class="p-6 border-t bg-unib-blue-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-unib-blue-700">
                            Menampilkan {{ $tempatMagang->firstItem() }} - {{ $tempatMagang->lastItem() }} dari {{ $tempatMagang->total() }} hasil
                        </p>
                        <div class="flex space-x-1">
                            {{ $tempatMagang->withQueryString()->links() }}
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

    <script>
        // Add hover effects to table rows
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.classList.add('table-row-hover');
            });

            // Select all functionality
            const selectAll = document.getElementById('selectAll');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = selectAll.checked;
                    });
                });
            }
        });

        function toggleStatus(id) {
            if (confirm('Apakah Anda yakin ingin mengubah status tempat magang ini?')) {
                // Implement toggle status functionality
                console.log('Toggle status for id:', id);
            }
        }

        function deleteItem(id, name) {
            if (confirm(`Apakah Anda yakin ingin menghapus tempat magang "${name}"?`)) {
                // Implement delete functionality
                console.log('Delete item with id:', id);
            }
        }
    </script>

</x-sidebar-layout>