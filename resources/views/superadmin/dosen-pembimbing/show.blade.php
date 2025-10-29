{{-- resources/views/superadmin/dosen-pembimbing/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Dosen Pembimbing: {{ $user->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('superadmin.dosen-pembimbing.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
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

            {{-- Info Dosen --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dosen</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIP</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->nip ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No HP</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    Nonaktif
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Mahasiswa yang Dibimbing --}}
            <div class="bg-white shadow rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Mahasiswa yang Dibimbing ({{ $mahasiswa->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">NPM</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tempat Magang</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status KP</th>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">
                                    Belum ada mahasiswa yang dibimbing.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tambah Mahasiswa --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Mahasiswa</h3>
                <form method="POST" action="{{ route('superadmin.dosen-pembimbing.assign-mahasiswa', $user) }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="mahasiswa_id" class="block text-sm font-medium text-gray-700">Pilih Mahasiswa</label>
                            <select name="mahasiswa_id" id="mahasiswa_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500" required>
                                <option value="">Pilih Mahasiswa</option>
                                @foreach($mahasiswaAvailable as $mhs)
                                    <option value="{{ $mhs->id }}">{{ $mhs->name }} ({{ $mhs->npm }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                <i class="fas fa-plus mr-2"></i>Tambah Mahasiswa
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
