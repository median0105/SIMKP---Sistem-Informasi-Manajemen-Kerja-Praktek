@props(['active' => ''])

<div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:block" :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }" x-show="sidebarOpen || window.innerWidth >= 1024" x-transition:enter="transition-transform ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-center h-20 px-4 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 shadow-lg">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <img class="h-16 w-auto" src="{{ asset('images/logo Unib.png') }}" alt="UNIB Logo">
                <div class="ml-3">
                    <div class="text-white font-bold text-lg">SIMKP</div>
                    <div class="text-white font-bold text-sm">Universitas Bengkulu</div>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto bg-gradient-to-b from-unib-blue-50 to-gray-50">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('dashboard') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                    <i class="fas fa-home text-center text-sm text-unib-blue-700"></i>
                </div>
                <span class="flex-1">Beranda</span>
            </a>

            @if(auth()->user()->isSuperAdmin())
                <!-- Data Master Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('superadmin.kerja-praktek.*') || request()->routeIs('superadmin.dosen-pembimbing.*') || request()->routeIs('superadmin.dosen-penguji.*') || request()->routeIs('superadmin.verifikasi-instansi.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center w-full px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('superadmin.kerja-praktek.*') || request()->routeIs('superadmin.dosen-pembimbing.*') || request()->routeIs('superadmin.dosen-penguji.*') || request()->routeIs('superadmin.verifikasi-instansi.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                        <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                            <i class="fas fa-layer-group text-center text-sm text-unib-blue-700"></i>
                        </div>
                        <span class="flex-1 text-left">Data Master</span>
                        <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 010-1.06z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition class="mt-1 ml-5 space-y-1 border-l-2 border-unib-blue-300 pl-2">
                        <a href="{{ route('superadmin.kerja-praktek.index') }}"
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 border {{ request()->routeIs('superadmin.kerja-praktek.index') ? 'bg-unib-blue-100 text-unib-blue-700 border-unib-blue-300' : 'text-gray-700 hover:bg-unib-blue-100 hover:text-unib-blue-600 border-unib-blue-200' }}">
                            <span class="ml-2">Data Kerja Praktek</span>
                        </a>
                        <a href="{{ route('superadmin.verifikasi-instansi.index') }}"
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 border {{ request()->routeIs('superadmin.verifikasi-instansi.index') ? 'bg-unib-blue-100 text-unib-blue-700 border-unib-blue-300' : 'text-gray-700 hover:bg-unib-blue-100 hover:text-unib-blue-600 border-unib-blue-200' }}">
                            <span class="ml-2">Verifikasi Instansi</span>
                        </a>
                        <a href="{{ route('superadmin.dosen-pembimbing.index') }}"
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 border {{ request()->routeIs('superadmin.dosen-pembimbing.index') ? 'bg-unib-blue-100 text-unib-blue-700 border-unib-blue-300' : 'text-gray-700 hover:bg-unib-blue-100 hover:text-unib-blue-600 border-unib-blue-200' }}">
                            <span class="ml-2">Data Dosen Pembimbing</span>
                        </a>
                        <a href="{{ route('superadmin.dosen-penguji.index') }}"
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 border {{ request()->routeIs('superadmin.dosen-penguji.index') ? 'bg-unib-blue-100 text-unib-blue-700 border-unib-blue-300' : 'text-gray-700 hover:bg-unib-blue-100 hover:text-unib-blue-600 border-unib-blue-200' }}">
                            <span class="ml-2">Data Dosen Penguji</span>
                        </a>
                    </div>
                </div>

                <!-- Kuisioner Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('superadmin.kuisioner.*') || request()->routeIs('superadmin.kuisioner_questions.*') || request()->routeIs('superadmin.kuisioner_pengawas_questions.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center w-full px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('superadmin.kuisioner.*') || request()->routeIs('superadmin.kuisioner_questions.*') || request()->routeIs('superadmin.kuisioner_pengawas_questions.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                        <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-teknik-orange-100">
                            <i class="fas fa-clipboard-list text-center text-sm text-teknik-orange-600"></i>
                        </div>
                        <span class="flex-1 text-left">Kuisioner</span>
                        <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 010-1.06z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition class="mt-1 ml-5 space-y-1 border-l-2 border-unib-blue-300 pl-2">
                        <a href="{{ route('superadmin.kuisioner.index') }}"
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 border {{ request()->routeIs('superadmin.kuisioner.index') ? 'bg-unib-blue-100 text-unib-blue-700 border-unib-blue-300' : 'text-gray-700 hover:bg-unib-blue-100 hover:text-unib-blue-600 border-unib-blue-200' }}">
                            <span class="ml-2">Lihat Data Kuisioner</span>
                        </a>
                        <a href="{{ route('superadmin.kuisioner_questions.index') }}"
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 border {{ request()->routeIs('superadmin.kuisioner_questions.index') ? 'bg-unib-blue-100 text-unib-blue-700 border-unib-blue-300' : 'text-gray-700 hover:bg-unib-blue-100 hover:text-unib-blue-600 border-unib-blue-200' }}">
                            <span class="ml-2">Kelola Pertanyaan Mahasiswa</span>
                        </a>
                        <a href="{{ route('superadmin.kuisioner_pengawas_questions.index') }}"
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 border {{ request()->routeIs('superadmin.kuisioner_pengawas_questions.index') ? 'bg-unib-blue-100 text-unib-blue-700 border-unib-blue-300' : 'text-gray-700 hover:bg-unib-blue-100 hover:text-unib-blue-600 border-unib-blue-200' }}">
                            <span class="ml-2">Kelola Pertanyaan Pengawas</span>
                        </a>
                    </div>
                </div>
            @endif

            @if(auth()->user()->isMahasiswa())
                <!-- Kerja Praktek -->
                <a href="{{ route('mahasiswa.kerja-praktek.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('mahasiswa.kerja-praktek.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                        <i class="fas fa-laptop-code text-center text-sm text-unib-blue-700"></i>
                    </div>
                    <span class="flex-1">Pengajuan Kerja Praktek</span>
                </a>

                <!-- Bimbingan -->
                <a href="{{ route('mahasiswa.bimbingan.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('mahasiswa.bimbingan.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                        <i class="fas fa-hands-helping text-center text-sm text-unib-blue-700"></i>
                    </div>
                    <span class="flex-1">Bimbingan Kerja Praktek</span>
                </a>

                <!-- Kegiatan -->
                <a href="{{ route('mahasiswa.kegiatan.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('mahasiswa.kegiatan.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-teknik-orange-100">
                        <i class="fas fa-calendar-check text-center text-sm text-teknik-orange-600"></i>
                    </div>
                    <span class="flex-1">Kegiatan</span>
                </a>
            @endif

            @if(auth()->user()->isAdminDosen())
                <!-- Mahasiswa -->
                <a href="{{ route('admin.mahasiswa.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('admin.mahasiswa.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                        <i class="fas fa-user-graduate text-center text-sm text-unib-blue-700"></i>
                    </div>
                    <span class="flex-1">Daftar Mahasiswa</span>
                </a>

                <!-- Verifikasi KP -->
                <a href="{{ route('admin.kerja-praktek.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('admin.kerja-praktek.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-teknik-orange-100">
                        <i class="fas fa-check-double text-center text-sm text-teknik-orange-600"></i>
                    </div>
                    <span class="flex-1">Pengajuan Kerja Praktek</span>
                </a>

                <!-- Bimbingan -->
                <a href="{{ route('admin.bimbingan.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('admin.bimbingan.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                        <i class="fas fa-comments text-center text-sm text-unib-blue-700"></i>
                    </div>
                    <span class="flex-1">Bimbingan Mahasiswa</span>
                </a>

                <!-- Kegiatan Mahasiswa -->
                <a href="{{ route('admin.kegiatan.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('admin.kegiatan.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-teknik-orange-100">
                        <i class="fas fa-tasks text-center text-sm text-teknik-orange-600"></i>
                    </div>
                    <span class="flex-1">Kegiatan Mahasiswa</span>
                </a>

                <!-- Seminar -->
                <a href="{{ route('admin.seminar.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('admin.seminar.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                        <i class="fas fa-microphone-alt text-center text-sm text-unib-blue-700"></i>
                    </div>
                    <span class="flex-1">Penguji Mahasiswa</span>
                </a>
            @endif

            @if(auth()->user()->isSuperAdmin())
                <!-- Kelola User -->
                <a href="{{ route('superadmin.users.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('superadmin.users.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                        <i class="fas fa-users-cog text-center text-sm text-unib-blue-700"></i>
                    </div>
                    <span class="flex-1">Kelola Akun</span>
                </a>

                <!-- Periode -->
                <a href="{{ route('superadmin.periodes.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('superadmin.periodes.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                        <i class="fas fa-calendar-alt text-center text-sm text-unib-blue-700"></i>
                    </div>
                    <span class="flex-1">Periode Kerja Praktek</span>
                </a>

                <!-- Tempat Magang -->
                <a href="{{ route('superadmin.tempat-magang.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('superadmin.tempat-magang.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                        <i class="fas fa-map-marked-alt text-center text-sm text-unib-blue-700"></i>
                    </div>
                    <span class="flex-1">Tempat Magang</span>
                </a>

                <!-- Laporan -->
                <a href="{{ route('superadmin.laporan.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('superadmin.laporan.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-teknik-orange-100">
                        <i class="fas fa-chart-line text-center text-sm text-teknik-orange-600"></i>
                    </div>
                    <span class="flex-1">Laporan Kerja Praktek</span>
                </a>

                <!-- Sertifikat Pengawas -->
                <a href="{{ route('superadmin.sertifikat-pengawas.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('superadmin.sertifikat-pengawas.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-teknik-orange-100">
                        <i class="fas fa-certificate text-center text-sm text-teknik-orange-600"></i>
                    </div>
                    <span class="flex-1">Sertifikat Pengawas</span>
                </a>

                <!-- Kegiatan Mahasiswa -->
                <a href="{{ route('superadmin.kegiatan.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('superadmin.kegiatan.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                        <i class="fas fa-clipboard-check text-center text-sm text-unib-blue-700"></i>
                    </div>
                    <span class="flex-1">Kegiatan Mahasiswa</span>
                </a>
            @endif

            @if(auth()->user()->isPengawasLapangan())
                <!-- Mahasiswa KP -->
                <a href="{{ route('pengawas.mahasiswa.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('pengawas.mahasiswa.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                        <i class="fas fa-user-friends text-center text-sm text-unib-blue-700"></i>
                    </div>
                    <span class="flex-1">Daftar Mahasiswa</span>
                </a>

                <!-- Kegiatan Mahasiswa -->
                <a href="{{ route('pengawas.kegiatan.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('pengawas.kegiatan.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-teknik-orange-100">
                        <i class="fas fa-list-ol text-center text-sm text-teknik-orange-600"></i>
                    </div>
                    <span class="flex-1">Kegiatan Mahasiswa</span>
                </a>

                <!-- Kuisioner -->
                <a href="{{ route('pengawas.kuisioner-pengawas.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('pengawas.kuisioner-pengawas.index') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-unib-blue-100">
                        <i class="fas fa-poll-h text-center text-sm text-unib-blue-700"></i>
                    </div>
                    <span class="flex-1">Kuisioner</span>
                </a>

                <!-- Sertifikat -->
                <a href="{{ route('pengawas.sertifikat.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 border {{ request()->routeIs('pengawas.sertifikat.*') ? 'bg-unib-blue-600 text-white shadow-lg border-unib-blue-700' : 'text-gray-700 hover:bg-unib-blue-500 hover:text-white border-unib-blue-200' }}">
                    <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-teknik-orange-100">
                        <i class="fas fa-id-card-alt text-center text-sm text-teknik-orange-600"></i>
                    </div>
                    <span class="flex-1">Sertifikat</span>
                </a>
            @endif
        </nav>
    </div>
</div>