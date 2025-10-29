{{-- resources/views/admin/seminar/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Mahasiswa Seminar
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('dashboard') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Filter --}}
            <div class="bg-white shadow rounded-lg p-6">
                <form method="GET" class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NPM, atau email mahasiswa…"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Cari</button>
                        <a href="{{ route('admin.seminar.index') }}" class="ml-3 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="bg-white shadow rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">NPM</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tempat Magang</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status KP</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($mahasiswa as $mhs)
                            <tr>
                                <td class="px-4 py-2">
                                    <div class="font-medium text-gray-900">{{ $mhs->name }}</div>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-gray-900">{{ $mhs->npm }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-gray-900">{{ $mhs->email }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-gray-900">{{ $mhs->kpTerbaru?->tempatMagang?->nama_perusahaan ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    @if($mhs->kpTerbaru)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($mhs->kpTerbaru->status === 'disetujui') bg-green-100 text-green-700
                                            @elseif($mhs->kpTerbaru->status === 'proses') bg-yellow-100 text-yellow-700
                                            @elseif($mhs->kpTerbaru->status === 'ditolak') bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-700 @endif">
                                            {{ ucfirst($mhs->kpTerbaru->status) }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-1 text-right text-sm font-medium">
                                    <a href="{{ route('admin.seminar.show', $mhs->kpTerbaru) }}"
                                        class="text-unib-blue-600 hover:text-unib-blue-900">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">
                                    Tidak ada mahasiswa yang siap untuk diuji.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t">
                    {{ $mahasiswa->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
