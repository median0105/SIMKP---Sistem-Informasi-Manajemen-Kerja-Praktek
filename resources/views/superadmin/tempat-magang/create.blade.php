{{-- resources/views/superadmin/tempat-magang/create.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('superadmin.tempat-magang.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 transform hover:scale-105 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="flex items-center space-x-3">
                    <div>
                        <h2 class="font-bold text-xl leading-tight">
                            Tambah Tempat Magang
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
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
                        Form Tambah Tempat Magang
                    </h3>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    @include('superadmin.tempat-magang._form', [
                        'action' => route('superadmin.tempat-magang.store'),
                        'method' => 'POST',
                        'submitText' => 'Simpan'
                    ])
                </div>
            </div>

        </div>
    </div>
</x-sidebar-layout>