{{-- resources/views/superadmin/dosen-penguji/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Data Dosen Penguji
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
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email dosen…"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Cari</button>
                        <a href="{{ route('superadmin.dosen-penguji.index') }}" class="ml-3 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">
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
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">NIP</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No HP</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dibuat</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($dosen as $d)
                            <tr>
                                <td class="px-4 py-2">
                                    <div class="font-medium text-gray-900">{{ $d->name }}</div>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-gray-900">{{ $d->email }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-gray-900">{{ $d->nip ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-gray-900">{{ $d->phone ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    @if($d->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-gray-900">{{ $d->created_at->locale('id')->translatedFormat('d F Y') }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('superadmin.dosen-penguji.show', $d) }}"
                                       class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                                        <i class="fas fa-eye mr-1"></i>Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">
                                    Tidak ada data dosen penguji.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t">
                    {{ $dosen->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
