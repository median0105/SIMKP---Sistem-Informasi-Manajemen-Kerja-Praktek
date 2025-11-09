<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Kerja Praktek') }}
       </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

                    <!-- Alert judul duplikat -->
                    @if(isset($duplicateTitles) && $duplicateTitles->count() > 0)
                        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fas fa-exclamation-triangle text-yellow-400 mt-1"></i>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Peringatan: Judul Duplikat Ditemukan</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>{{ $duplicateTitles->count() }} judul KP terdeteksi duplikat dari total {{ $kerjaPrakteks->total() }} data KP ({{ $duplicatePercentage }}% duplikat).</p>
                                        <p>Judul KP berikut terdeteksi duplikat:</p>
                                        <ul class="list-disc list-inside mt-1">
                                            @foreach($duplicateTitles as $title)
                                                <li>{{ $title }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Search and Filter -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <form method="GET" class="flex-1">
                            <div class="flex gap-2">
                                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama mahasiswa, NPM, judul KP, atau tempat magang..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <select name="tempat_magang_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Tempat Magang</option>
                                    @foreach($tempatMagang as $tm)
                                    <option value="{{ $tm->id }}" {{ $tempat_magang_id == $tm->id ? 'selected' : '' }}>{{ $tm->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Pembimbing</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Penguji</th>
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
                                        {{-- @if($kp->duplicate_info && count($kp->duplicate_info) > 0)
                                            <div class="mt-2">
                                                <button type="button" onclick="toggleDuplicates({{ $kp->id }})"
                                                        class="text-xs text-blue-600 hover:text-blue-800 underline">
                                                    Lihat kemiripan ({{ count($kp->duplicate_info) }})
                                                </button>
                                                <div id="duplicates-{{ $kp->id }}" class="hidden mt-2 space-y-1">
                                                    @foreach($kp->duplicate_info as $duplicate)
                                                        <div class="text-xs bg-gray-50 p-2 rounded border">
                                                            <div class="font-medium">{{ $duplicate['judul_kp'] }}</div>
                                                            <div class="text-gray-600">
                                                                {{ $duplicate['mahasiswa'] }} ({{ $duplicate['npm'] }}) -
                                                                {{ $duplicate['tempat_magang'] }}
                                                            </div>
                                                            <div class="text-green-600 font-medium">
                                                                Kemiripan: {{ $duplicate['similarity'] }}%
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif --}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if($kp->pilihan_tempat == 3)
                                                {{ $kp->tempat_magang_sendiri ?? '-' }}
                                            @else
                                                {{ $kp->tempatMagang->nama_perusahaan ?? '-' }}
                                            @endif
                                        </div>
                                    </td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap">
                                        @if($kp->duplicate_info && count($kp->duplicate_info) > 0)
                                            <div class="space-y-1">
                                                @foreach($kp->duplicate_info as $duplicate)
                                                    <div class="text-xs bg-red-50 text-red-700 px-2 py-1 rounded">
                                                        {{ $duplicate['similarity'] }}% - {{ $duplicate['mahasiswa'] }} ({{ $duplicate['npm'] }})
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td> --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $dosenPembimbingAkademik = $kp->dosenPembimbing->where('jenis_pembimbingan', 'akademik')->where('is_active', true)->first();
                                            $dosenPengujiAktif = $kp->dosenPenguji->where('is_active', true)->first();
                                            $excludedDosenIds = [];
                                            if ($dosenPengujiAktif) {
                                                $excludedDosenIds[] = $dosenPengujiAktif->dosen_id;
                                            }
                                        @endphp
                                        @if($dosenPembimbingAkademik && $dosenPembimbingAkademik->dosen)
                                            <div class="text-sm text-gray-900">{{ $dosenPembimbingAkademik->dosen->name }}</div>
                                        @else
                                            <form method="POST" action="{{ route('superadmin.kerja-praktek.assign-dosen-pembimbing', $kp) }}" class="inline">
                                                @csrf
                                                <select name="dosen_id" class="text-xs border-gray-300 rounded px-2 py-1" required>
                                                    <option value="">Pilih Dosen</option>
                                                    @foreach($dosen as $d)
                                                        @if(!in_array($d->id, $excludedDosenIds))
                                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="text-blue-600 hover:text-blue-900 text-xs ml-1" title="Assign">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $dosenPembimbingAkademik = $kp->dosenPembimbing->where('jenis_pembimbingan', 'akademik')->where('is_active', true)->first();
                                            $dosenPengujiAktif = $kp->dosenPenguji->where('is_active', true)->first();
                                            $excludedDosenIds = [];
                                            if ($dosenPembimbingAkademik) {
                                                $excludedDosenIds[] = $dosenPembimbingAkademik->dosen_id;
                                            }
                                        @endphp
                                        @if($dosenPengujiAktif && $dosenPengujiAktif->dosen)
                                            <div class="text-sm text-gray-900">{{ $dosenPengujiAktif->dosen->name }}</div>
                                        @else
                                            <form method="POST" action="{{ route('superadmin.kerja-praktek.assign-dosen-penguji', $kp) }}" class="inline">
                                                @csrf
                                                <select name="dosen_id" class="text-xs border-gray-300 rounded px-2 py-1" required>
                                                    <option value="">Pilih Dosen</option>
                                                    @foreach($dosen as $d)
                                                        @if(!in_array($d->id, $excludedDosenIds))
                                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="text-blue-600 hover:text-blue-900 text-xs ml-1" title="Assign">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </form>
                                        @endif
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
                                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">
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

        function toggleDuplicates(kpId) {
            const element = document.getElementById(`duplicates-${kpId}`);
            element.classList.toggle('hidden');
        }
    </script>
</x-sidebar-layout>
