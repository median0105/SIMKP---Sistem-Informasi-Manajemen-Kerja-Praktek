<nav x-data="{ open: false }" class="bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 border-b border-unib-blue-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img class="h-8 w-auto" src="{{ asset('storage/unib.png') }}" alt="UNIB Logo">
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
                        {{-- <x-nav-link :href="route('admin.kegiatan.index')" :active="request()->routeIs('admin.kegiatan.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Kegiatan Mahasiswa') }}
                        </x-nav-link> --}}
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
                        <x-nav-link :href="route('superadmin.kuisioner.index')" :active="request()->routeIs('superadmin.kuisioner.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Kuisioner') }}
                        </x-nav-link>
                        <x-nav-link :href="route('superadmin.kegiatan.index')" :active="request()->routeIs('superadmin.kegiatan.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Kegiatan Mahasiswa') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isPengawasLapangan())
                        <x-nav-link :href="route('pengawas.mahasiswa.index')" :active="request()->routeIs('pengawas.mahasiswa.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Mahasiswa KP') }}
                        </x-nav-link>
                        {{-- <x-nav-link :href="route('pengawas.kuisioner.index')" :active="request()->routeIs('pengawas.kuisioner.*')" class="text-white hover:text-unib-blue-200">
                            {{ __('Kuisioner') }}
                        </x-nav-link> --}}
                    @endif
                </div>
            </div>
            <!-- Notification + User Dropdown -->
<div class="hidden sm:flex sm:items-center sm:space-x-4">
    <!-- Notification Bell -->
    <div class="relative">
        <a href="{{ route('notifications.index') }}" 
        class="p-2 text-white hover:text-unib-blue-200 relative">
            <i class="fas fa-bell text-lg"></i>
            @php
                $unreadCount = App\Services\NotificationService::getUnreadCount(auth()->id());
            @endphp
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
            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-unib-blue-600 hover:bg-unib-blue-500 focus:outline-none transition ease-in-out duration-150">
                <div>{{ Auth::user()->name }}</div>
                <div class="ml-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>
        
        <x-slot name="content">
            <x-dropdown-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-dropdown-link>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown>
</div>

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
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-unib-blue-200">{{ Auth::user()->email }}</div>
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
