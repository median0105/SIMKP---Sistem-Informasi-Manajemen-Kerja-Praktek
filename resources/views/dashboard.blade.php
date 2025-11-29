<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Beranda') }} - {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->isMahasiswa())
                @include('dashboard.mahasiswa')
            @elseif(auth()->user()->isAdminDosen())
                @include('dashboard.admin-dosen')
            @elseif(auth()->user()->isSuperAdmin())
                @include('dashboard.superadmin')
            @elseif(auth()->user()->isPengawasLapangan())
                @include('dashboard.pengawas')
            @endif
        </div>
    </div>
</x-sidebar-layout>
