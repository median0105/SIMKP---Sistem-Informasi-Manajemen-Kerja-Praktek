{{-- resources/views/superadmin/tempat-magang/edit.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('superadmin.tempat-magang.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Tempat Magang — {{ $tempatMagang->nama_perusahaan }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">
                @include('superadmin.tempat-magang._form', [
                    'action' => route('superadmin.tempat-magang.update', $tempatMagang),
                    'method' => 'PUT',
                    'submitText' => 'Update',
                    'tempatMagang' => $tempatMagang
                ])
            </div>
        </div>
    </div>
</x-sidebar-layout>
