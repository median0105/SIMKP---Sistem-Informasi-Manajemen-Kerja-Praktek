<nav x-data="{ open: false }" class="bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 border-b border-unib-blue-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img class="h-24 w-auto" src="{{ asset('storage/logo-unib.png') }}" alt="UNIB Logo">
                        <div class="ml-3">
                            <div class="text-white font-bold text-lg">SIMKP</div>
                            <div class="text-unib-blue-200 text-xs">Universitas Bengkulu</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-unib-blue-200">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->isMahasiswa())
                        <x-nav-link :href="route('mahasiswa.kerja-praktek.index')" :active="request()->routeIs('mahasiswa.kerja-praktek.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Kerja Praktek') }}
                        </x-nav-link>
                        <x-nav-link :href="route('mahasiswa.bimbingan.index')" :active="request()->routeIs('mahasiswa.bimbingan.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Bimbingan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('mahasiswa.kegiatan.index')" :active="request()->routeIs('mahasiswa.kegiatan.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Kegiatan') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isAdminDosen())
                        <x-nav-link :href="route('admin.mahasiswa.index')" :active="request()->routeIs('admin.mahasiswa.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Mahasiswa') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.kerja-praktek.index')" :active="request()->routeIs('admin.kerja-praktek.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Verifikasi KP') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.bimbingan.index')" :active="request()->routeIs('admin.bimbingan.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Bimbingan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.kegiatan.index')" :active="request()->routeIs('admin.kegiatan.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Kegiatan Mahasiswa') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.seminar.index')" :active="request()->routeIs('admin.seminar.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Seminar') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isSuperAdmin())
                        <x-nav-link :href="route('superadmin.users.index')" :active="request()->routeIs('superadmin.users.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Kelola User') }}
                        </x-nav-link>
                        <x-nav-link :href="route('superadmin.tempat-magang.index')" :active="request()->routeIs('superadmin.tempat-magang.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Tempat Magang') }}
                        </x-nav-link>
                        <x-nav-link :href="route('superadmin.laporan.index')" :active="request()->routeIs('superadmin.laporan.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Laporan') }}
                        </x-nav-link>
                        <!-- Kuisioner Dropdown -->
                        <div x-data="{ open:false }" class="relative inline-block text-left h-14" x-cloak>
                            <button
                                @click="open = !open"
                                @keydown.escape.window="open=false"
                                class="inline-flex h-full items-center px-4 text-sm font-semibold text-white hover:text-unib-blue-200
                                    {{ request()->routeIs('superadmin.kuisioner.*') || request()->routeIs('superadmin.kuisioner_questions.*') ? 'text-unib-blue-200' : '' }}">
                                Kuisioner
                                <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="{{ route('superadmin.kuisioner.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Lihat Kuisioner</a>
                                    <a href="{{ route('superadmin.kuisioner_questions.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kelola Pertanyaan</a>
                                </div>
                            </div>
                        </div>

                        <x-nav-link :href="route('superadmin.kegiatan.index')" :active="request()->routeIs('superadmin.kegiatan.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Kegiatan Mahasiswa') }}
                        </x-nav-link>
                        <x-nav-link :href="route('superadmin.kerja-praktek.index')" :active="request()->routeIs('superadmin.kerja-praktek.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Data KP') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isPengawasLapangan())
                        <x-nav-link :href="route('pengawas.mahasiswa.index')" :active="request()->routeIs('pengawas.mahasiswa.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Mahasiswa KP') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pengawas.kegiatan.index')" :active="request()->routeIs('pengawas.kegiatan.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Kegiatan Mahasiswa') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pengawas.kuisioner.index')" :active="request()->routeIs('pengawas.kuisioner.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Kuisioner') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>
            <!-- Notification + User Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:space-x-4">

            <!-- Notification Bell -->
            <div class="relative">
                @php
                    $unreadCount = App\Services\NotificationService::getUnreadCount(auth()->id());
                @endphp
                <a href="{{ route('notifications.index') }}"
                class="p-2 text-white hover:text-unib-blue-200 relative">
                    <i class="fas fa-bell text-lg"></i>
                    @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                           {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>
            </div>

    <!-- Settings Dropdown -->
            <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-unib-blue-600 hover:bg-unib-blue-500 focus:outline-none transition ease-in-out duration-150">

                    {{-- Avatar dengan bingkai bulat --}}
                    @if(Auth::user()->avatar)
                        <div class="relative">
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                alt="Avatar"
                                class="w-8 h-8 rounded-full border-2 border-white shadow-sm mr-2 object-cover">
                        </div>
                    @else
                        {{-- Jika belum ada avatar, tampilkan huruf pertama nama --}}
                        <div
                            class="w-8 h-8 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-gray-700 font-semibold mr-2 shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif

                    {{-- Nama optional (bisa diaktifkan jika mau tampilkan nama) --}}
                    {{-- <div>{{ Auth::user()->name }}</div> --}}

                    <div class="ml-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <!-- Additional Info -->
                {{-- <div class="px-4 py-2 text-sm text-gray-500 border-t border-gray-200">
                    @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_dosen')
                        <div class="mb-1"><strong>NIP:</strong> {{ auth()->user()->nip ?? '-' }}</div>
                        <div><strong>No. HP:</strong> {{ auth()->user()->phone ?? '-' }}</div>
                    @elseif(auth()->user()->role === 'pengawas_lapangan')
                        <div><strong>No. HP:</strong> {{ auth()->user()->phone ?? '-' }}</div>
                    @elseif(auth()->user()->role === 'mahasiswa')
                        <div class="mb-1"><strong>NPM:</strong> {{ auth()->user()->npm ?? '-' }}</div>
                        <div><strong>No. HP:</strong> {{ auth()->user()->phone ?? '-' }}</div>
                    @endif
                </div> --}}

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>


            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-unib-blue-200 hover:bg-unib-blue-700 focus:outline-none focus:bg-unib-blue-700 focus:text-unib-blue-200 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-unib-blue-200">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-unib-blue-700">
            <div class="px-4 flex items-center">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full mr-3">
                @else
                    <div class="w-10 h-10 rounded-full bg-gray-300 mr-3 flex items-center justify-center">
                        <i class="fas fa-user text-gray-600 text-sm"></i>
                    </div>
                @endif
                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-unib-blue-200">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:text-unib-blue-200">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-white hover:text-unib-blue-200">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>