@props(['active' => ''])

<div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:block" :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }" x-show="sidebarOpen || window.innerWidth >= 1024" x-transition:enter="transition-transform ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <img class="h-16 w-auto" src="{{ asset('storage/logo-unib.png') }}" alt="UNIB Logo">
                <div class="ml-2">
                    <div class="text-white font-bold text-sm">SIMKP</div>
                    <div class="text-unib-blue-200 text-xs">Universitas Bengkulu</div>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>
                Dashboard
            </a>

            @if(auth()->user()->isMahasiswa())
                <!-- Kerja Praktek -->
                <a href="{{ route('mahasiswa.kerja-praktek.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('mahasiswa.kerja-praktek.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-briefcase mr-3 w-5 text-center"></i>
                    Kerja Praktek
                </a>

                <!-- Bimbingan -->
                <a href="{{ route('mahasiswa.bimbingan.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('mahasiswa.bimbingan.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-chalkboard-teacher mr-3 w-5 text-center"></i>
                    Bimbingan
                </a>

                <!-- Kegiatan -->
                <a href="{{ route('mahasiswa.kegiatan.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('mahasiswa.kegiatan.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-tasks mr-3 w-5 text-center"></i>
                    Kegiatan
                </a>
            @endif

            @if(auth()->user()->isAdminDosen())
                <!-- Mahasiswa -->
                <a href="{{ route('admin.mahasiswa.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.mahasiswa.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-user-graduate mr-3 w-5 text-center"></i>
                    Mahasiswa
                </a>

                <!-- Verifikasi KP -->
                <a href="{{ route('admin.kerja-praktek.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.kerja-praktek.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-check-circle mr-3 w-5 text-center"></i>
                    Verifikasi KP
                </a>

                <!-- Bimbingan -->
                <a href="{{ route('admin.bimbingan.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.bimbingan.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-chalkboard-teacher mr-3 w-5 text-center"></i>
                    Bimbingan
                </a>

                <!-- Kegiatan Mahasiswa -->
                <a href="{{ route('admin.kegiatan.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.kegiatan.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-tasks mr-3 w-5 text-center"></i>
                    Kegiatan Mahasiswa
                </a>

                <!-- Seminar -->
                <a href="{{ route('admin.seminar.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.seminar.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-graduation-cap mr-3 w-5 text-center"></i>
                    Seminar
                </a>
            @endif

            @if(auth()->user()->isSuperAdmin())
                <!-- Kelola User -->
                <a href="{{ route('superadmin.users.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('superadmin.users.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-users-cog mr-3 w-5 text-center"></i>
                    Kelola User
                </a>

                <!-- Tempat Magang -->
                <a href="{{ route('superadmin.tempat-magang.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('superadmin.tempat-magang.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-building mr-3 w-5 text-center"></i>
                    Tempat Magang
                </a>

                <!-- Laporan -->
                <a href="{{ route('superadmin.laporan.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('superadmin.laporan.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
                    Laporan
                </a>

                <!-- Data Master Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('superadmin.kerja-praktek.*') || request()->routeIs('superadmin.dosen-pembimbing.*') || request()->routeIs('superadmin.dosen-penguji.*') || request()->routeIs('superadmin.verifikasi-instansi.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center w-full px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('superadmin.kerja-praktek.*') || request()->routeIs('superadmin.dosen-pembimbing.*') || request()->routeIs('superadmin.dosen-penguji.*') || request()->routeIs('superadmin.verifikasi-instansi.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                        <i class="fas fa-database mr-3 w-5 text-center"></i>
                        Data Master
                        <svg class="ml-auto h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 010-1.06z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition class="mt-1 ml-4 space-y-1">
                        <a href="{{ route('superadmin.kerja-praktek.index') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('superadmin.kerja-praktek.index') ? 'bg-unib-blue-50 text-unib-blue-700' : '' }}">
                            Data KP
                        </a>
                        <a href="{{ route('superadmin.verifikasi-instansi.index') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('superadmin.verifikasi-instansi.index') ? 'bg-unib-blue-50 text-unib-blue-700' : '' }}">
                            Verifikasi Instansi
                        </a>
                        <a href="{{ route('superadmin.dosen-pembimbing.index') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('superadmin.dosen-pembimbing.index') ? 'bg-unib-blue-50 text-unib-blue-700' : '' }}">
                            Data Dosen Pembimbing
                        </a>
                        <a href="{{ route('superadmin.dosen-penguji.index') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('superadmin.dosen-penguji.index') ? 'bg-unib-blue-50 text-unib-blue-700' : '' }}">
                            Data Dosen Penguji
                        </a>
                    </div>
                </div>

                <!-- Kuisioner Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('superadmin.kuisioner.*') || request()->routeIs('superadmin.kuisioner_questions.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center w-full px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('superadmin.kuisioner.*') || request()->routeIs('superadmin.kuisioner_questions.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                        <i class="fas fa-clipboard-list mr-3 w-5 text-center"></i>
                        Kuisioner
                        <svg class="ml-auto h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 010-1.06z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition class="mt-1 ml-4 space-y-1">
                        <a href="{{ route('superadmin.kuisioner.index') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('superadmin.kuisioner.index') ? 'bg-unib-blue-50 text-unib-blue-700' : '' }}">
                            Lihat Kuisioner
                        </a>
                        <a href="{{ route('superadmin.kuisioner_questions.index') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('superadmin.kuisioner_questions.index') ? 'bg-unib-blue-50 text-unib-blue-700' : '' }}">
                            Kelola Pertanyaan
                        </a>
                    </div>
                </div>

                <!-- Kegiatan Mahasiswa -->
                <a href="{{ route('superadmin.kegiatan.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('superadmin.kegiatan.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-tasks mr-3 w-5 text-center"></i>
                    Kegiatan Mahasiswa
                </a>
            @endif

            @if(auth()->user()->isPengawasLapangan())
                <!-- Mahasiswa KP -->
                <a href="{{ route('pengawas.mahasiswa.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('pengawas.mahasiswa.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-user-graduate mr-3 w-5 text-center"></i>
                    Mahasiswa KP
                </a>

                <!-- Kegiatan Mahasiswa -->
                <a href="{{ route('pengawas.kegiatan.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('pengawas.kegiatan.*') ? 'bg-unib-blue-50 text-unib-blue-700 border-r-4 border-unib-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-unib-blue-600' }}">
                    <i class="fas fa-tasks mr-3 w-5 text-center"></i>
                    Kegiatan Mahasiswa
                </a>
            @endif
        </nav>

        <!-- User Info & Logout -->
        {{-- <div class="p-4 border-t border-gray-200">
            <div class="flex items-center mb-4">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                         alt="Avatar"
                         class="w-10 h-10 rounded-full border-2 border-gray-300 mr-3 object-cover">
                @else
                    <div class="w-10 h-10 rounded-full bg-gray-300 mr-3 flex items-center justify-center">
                        <i class="fas fa-user text-gray-600 text-sm"></i>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <div class="space-y-2">
                <!-- Notification Bell -->
                <div class="relative">
                    @php
                        $unreadCount = App\Services\NotificationService::getUnreadCount(auth()->id());
                    @endphp
                    <a href="{{ route('notifications.index') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <i class="fas fa-bell mr-3 w-5 text-center"></i>
                        Notifikasi
                        @if($unreadCount > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                               {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        @endif
                    </a>
                </div>

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <i class="fas fa-user-cog mr-3 w-5 text-center"></i>
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div> --}}
    </div>
</div>
