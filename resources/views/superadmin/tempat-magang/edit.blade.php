{{-- resources/views/superadmin/tempat-magang/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Tempat Magang — {{ $tempatMagang->nama_perusahaan }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('superadmin.tempat-magang.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                   Kembali
                </a>
                {{-- Tombol Hapus cepat (opsional, ada juga di index) --}}
                <form method="POST" action="{{ route('superadmin.tempat-magang.destroy', $tempatMagang) }}"
                      onsubmit="return confirm('Yakin ingin menghapus tempat magang ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                        Hapus
                    </button>
                </form>
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
