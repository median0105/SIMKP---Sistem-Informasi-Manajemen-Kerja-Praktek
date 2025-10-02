{{-- resources/views/superadmin/tempat-magang/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Tempat Magang — {{ $tempatMagang->nama_perusahaan }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('superadmin.tempat-magang.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
</x-app-layout>
