<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <i class="fas fa-calendar-alt text-lg"></i>
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Tambah Periode Baru
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
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
                        Form Tambah Periode
                    </h3>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    <form method="POST" action="{{ route('superadmin.periodes.store') }}" class="space-y-6" id="periodeForm">
                        @csrf

                        {{-- Tahun Akademik --}}
                        <div>
                            <label class="block text-base font-semibold text-gray-700 mb-3">
                                Tahun Akademik <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tahun_akademik" value="{{ old('tahun_akademik') }}" 
                                   placeholder="Contoh: 2024/2025" required
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                        </div>

                        {{-- Semester Ke dan Jenis Semester --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-base font-semibold text-gray-700 mb-3">
                                    Semester Ke <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="semester_ke" id="semester_ke" value="{{ old('semester_ke', 1) }}" 
                                       min="1" max="14" required
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            </div>

                            <div>
                                <label class="block text-base font-semibold text-gray-700 mb-3">
                                    Jenis Semester <span class="text-red-500">*</span>
                                </label>
                                <select name="semester_type" id="semester_type" required 
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                                    <option value="ganjil" @selected(old('semester_type') === 'ganjil')>Ganjil</option>
                                    <option value="genap" @selected(old('semester_type') === 'genap')>Genap</option>
                                </select>
                                <p class="text-sm text-gray-500 mt-2 flex items-center" id="semester_info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <!-- Info akan muncul di sini -->
                                </p>
                            </div>
                        </div>

                        {{-- Tanggal Mulai dan Selesai KP --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-base font-semibold text-gray-700 mb-3">
                                    Tanggal Mulai KP <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_mulai_kp" value="{{ old('tanggal_mulai_kp') }}" required
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            </div>

                            <div>
                                <label class="block text-base font-semibold text-gray-700 mb-3">
                                    Tanggal Selesai KP <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_selesai_kp" value="{{ old('tanggal_selesai_kp') }}" required
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            </div>
                        </div>

                        {{-- Status Aktif --}}
                        <div class="mb-8">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="status" value="1" 
                                       {{ old('status') ? 'checked' : '' }}
                                       class="text-unib-blue-600 focus:ring-unib-blue-500">
                                <span class="ml-2 text-base font-medium text-gray-700">Aktifkan periode ini</span>
                            </label>
                            <p class="text-sm text-gray-500 mt-2 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Jika dicentang, periode lain akan otomatis dinonaktifkan
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('superadmin.periodes.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit"
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-8 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center">
                                <i class="fas fa-save mr-2"></i>Simpan Periode
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const semesterKeInput = document.getElementById('semester_ke');
            const semesterTypeSelect = document.getElementById('semester_type');
            const semesterInfo = document.getElementById('semester_info');

            // Function to determine semester type based on semester number
            function updateSemesterType() {
                const semesterKe = parseInt(semesterKeInput.value);
                
                if (isNaN(semesterKe)) {
                    semesterInfo.innerHTML = '<i class="fas fa-info-circle mr-2"></i>';
                    return;
                }

                // Odd semester = Ganjil, Even semester = Genap
                const isGanjil = semesterKe % 2 === 1;
                
                if (isGanjil) {
                    semesterTypeSelect.value = 'ganjil';
                    semesterInfo.innerHTML = `<i class="fas fa-info-circle mr-2"></i>Semester ${semesterKe} adalah semester Ganjil`;
                    semesterInfo.className = 'text-sm text-blue-600 mt-2 flex items-center font-medium';
                } else {
                    semesterTypeSelect.value = 'genap';
                    semesterInfo.innerHTML = `<i class="fas fa-info-circle mr-2"></i>Semester ${semesterKe} adalah semester Genap`;
                    semesterInfo.className = 'text-sm text-green-600 mt-2 flex items-center font-medium';
                }

                // Add common semester info
                const commonInfo = getSemesterCommonInfo(semesterKe);
                if (commonInfo) {
                    semesterInfo.innerHTML += ` | ${commonInfo}`;
                }
            }

            // Function to provide additional info about common semesters
            function getSemesterCommonInfo(semester) {
                switch(semester) {
                    case 1: return 'Tingkat 1';
                    case 2: return 'Tingkat 1';
                    case 3: return 'Tingkat 2';
                    case 4: return 'Tingkat 2';
                    case 5: return 'Tingkat 3';
                    case 6: return 'Tingkat 3 (Biasanya KP)';
                    case 7: return 'Tingkat 4';
                    case 8: return 'Tingkat 4';
                    default: return '';
                }
            }

            // Event listener for semester input change
            semesterKeInput.addEventListener('input', updateSemesterType);
            semesterKeInput.addEventListener('change', updateSemesterType);

            // Also update when page loads (for edit scenarios)
            updateSemesterType();

            // Optional: Add validation for semester range
            semesterKeInput.addEventListener('blur', function() {
                const value = parseInt(this.value);
                if (value < 1) {
                    this.value = 1;
                } else if (value > 14) {
                    this.value = 14;
                }
                updateSemesterType();
            });
        });
    </script>
</x-sidebar-layout>