<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }} - {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
            </h2>
            <div class="flex items-center space-x-2">
                <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                    <i class="fas fa-circle text-green-500 mr-1"></i>
                    Online
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
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
</x-app-layout>