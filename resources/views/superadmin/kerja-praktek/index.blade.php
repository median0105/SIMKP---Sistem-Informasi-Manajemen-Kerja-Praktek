<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Kerja Praktek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <!-- Search and Filter -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <form method="GET" class="flex-1">
                            <div class="flex gap-2">
                                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama mahasiswa, NPM, atau judul KP..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Status</option>
                                    <option value="pengajuan" {{ $status === 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                                    <option value="disetujui" {{ $status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="berjalan" {{ $status === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul KP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat Magang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($kerjaPrakteks as $kp)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $kp->mahasiswa->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $kp->mahasiswa->npm }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $kp->judul_kp }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $kp->tempatMagang->nama_perusahaan ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($kp->status === 'pengajuan') bg-yellow-100 text-yellow-800
                                            @elseif($kp->status === 'disetujui') bg-green-100 text-green-800
                                            @elseif($kp->status === 'ditolak') bg-red-100 text-red-800
                                            @elseif($kp->status === 'berjalan') bg-blue-100 text-blue-800
                                            @elseif($kp->status === 'selesai') bg-gray-100 text-gray-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($kp->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $kp->created_at->locale('id')->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($kp->status === 'ditolak')
                                        <form method="POST" action="{{ route('superadmin.users.destroy-kp', [$kp->mahasiswa, $kp]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data KP yang ditolak ini? Mahasiswa dapat mengajukan ulang.')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                    <i class="fas fa-trash"></i>Hapus</button>
                                        </form>
                                        @else
                                        <a href="{{ route('superadmin.users.show', $kp->mahasiswa) }}" class="text-unib-blue-600 hover:text-unib-blue-900" title="Detail">
                                        <i class="fas fa-eye"></i> Detail</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data KP ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $kerjaPrakteks->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
