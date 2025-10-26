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
                    <!-- Notifications -->
                    {{-- @if($notifications->count() > 0)
                    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Notifikasi Proposal KP Ditolak
                                </h3>
                                 <div class="mt-2 text-sm text-yellow-700">
                                     <ul role="list" class="list-disc pl-5 space-y-1">
                                         @foreach($notifications as $notification)
                                         <li>
                                             {{ $notification->message }}
                                             <a href="{{ $notification->action_url }}" class="text-yellow-800 underline ml-2" onclick="markAsRead({{ $notification->id }})">Lihat</a>
                                         </li>
                                         @endforeach
                                     </ul>
                                 </div>
                             </div>
                        </div>
                    </div>
                    @endif --}}

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
                                            @if($kp->display_status === 'pengajuan') bg-yellow-100 text-yellow-800
                                            @elseif($kp->display_status === 'disetujui') bg-gray-100 text-gray-800
                                            @elseif($kp->display_status === 'ditolak') bg-red-100 text-red-800
                                            @elseif($kp->display_status === 'berjalan') bg-blue-100 text-blue-800
                                            @elseif($kp->display_status === 'selesai') bg-green-100 text-green-800
                                            @elseif($kp->display_status === 'tidak_lulus') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            @if($kp->display_status === 'tidak_lulus') Tidak Lulus @else {{ ucfirst($kp->display_status) }} @endif
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
                                                    <i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                        @elseif($kp->status === 'pengajuan')
                                        <a href="{{ route('superadmin.kerja-praktek.edit', $kp) }}" class="text-blue-600 hover:text-blue-900 mr-2" title="Edit">
                                            <i class="fas fa-edit"></i> Edit</a>
                                        <a href="{{ route('superadmin.users.show', $kp->mahasiswa) }}" class="text-unib-blue-600 hover:text-unib-blue-900" title="Detail">
                                            <i class="fas fa-eye"></i> Detail</a>
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

    <script>
        function markAsRead(notificationId) {
            fetch(`/notifications/mark-read/${notificationId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    </script>
</x-app-layout>
