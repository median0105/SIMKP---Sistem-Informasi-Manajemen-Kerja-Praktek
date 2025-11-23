<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Kerja Praktek') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Filter & Search --}}
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <form method="GET" action="{{ route('admin.kerja-praktek.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search"
                               placeholder="Cari berdasarkan nama, NPM, atau judul KP..."
                               value="{{ request('search') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>
                    <div>
                        <select name="status" onchange="this.form.submit()"
                                class="border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                            <option value="">Semua Status</option>
                            <option value="pengajuan"  @selected(request('status')=='pengajuan')>Pengajuan</option>
                            <option value="disetujui"  @selected(request('status')=='disetujui')>Disetujui</option>
                            <option value="sedang_kp"  @selected(request('status')=='sedang_kp')>Sedang KP</option>
                            <option value="selesai"    @selected(request('status')=='selesai')>Selesai</option>
                            <option value="ditolak"    @selected(request('status')=='ditolak')>Ditolak</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- Alert judul duplikat --}}
            @if($duplicateTitles->count() > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-yellow-400 mt-1"></i>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Peringatan: Judul Duplikat Ditemukan</h3>
                            <div class="mt-2 text-sm text-yellow-700">
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

            {{-- Daftar KP --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Pengajuan Kerja Praktek</h3>
                </div>

                @if($kerjaPraktek->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul KP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Pembimbing</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($kerjaPraktek as $kp)
                                    <tr class="{{ $kp->isDuplicateTitle() ? 'bg-yellow-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $kp->mahasiswa->name ?? '-' }}</div>
                                                <div class="text-sm text-gray-500">{{ $kp->mahasiswa->npm ?? '-' }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ \Illuminate\Support\Str::limit($kp->judul_kp, 50) }}
                                                @if($kp->isDuplicateTitle())
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i> Duplikat
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            @if($kp->pilihan_tempat == 3)
                                                {{ \Illuminate\Support\Str::limit($kp->tempat_magang_sendiri, 30) }}
                                            @else
                                                {{ $kp->tempatMagang->nama_perusahaan ?? '-' }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            @if($kp->dosenPembimbing && $kp->dosenPembimbing->where('jenis_pembimbingan', 'akademik')->first())
                                                {{ $kp->dosenPembimbing->where('jenis_pembimbingan', 'akademik')->first()->dosen->name ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @switch($kp->status)
                                                @case('pengajuan')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pengajuan</span>
                                                    @break
                                                @case('disetujui')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Disetujui</span>
                                                    @break
                                                @case('sedang_kp')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Sedang KP</span>
                                                    @break
                                                @case('selesai')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Selesai</span>
                                                    @break
                                                @case('ditolak')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ optional($kp->created_at)->locale('id')->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.kerja-praktek.show', $kp) }}"
                                                   class="text-unib-blue-600 hover:text-unib-blue-900" title="Detail KP">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if($kp->status === 'pengajuan')
                                                    <button type="button" onclick="approveKP({{ $kp->id }})"
                                                            class="text-green-600 hover:text-green-900 {{ $kp->isDuplicateTitle() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                            {{ $kp->isDuplicateTitle() ? 'disabled' : '' }}
                                                            title="Mulai KP">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" onclick="rejectKP({{ $kp->id }})"
                                                            class="text-red-600 hover:text-red-900" title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif

                                                @if($kp->status === 'sedang_kp')
                                                    <button type="button" onclick="sendReminder({{ $kp->id }})"
                                                            class="text-yellow-600 hover:text-yellow-900" title="Kirim Reminder">
                                                        <i class="fas fa-bell"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $kerjaPraktek->withQueryString()->links() }}
                    </div>
                @else
                    <div class="p-6 text-center">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Data</h3>
                        <p class="text-gray-600">Belum ada pengajuan kerja praktek.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Approve (diubah untuk langsung ke status sedang_kp) --}}
    <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Mulai Kerja Praktek</h3>
                    <p class="text-gray-600 mb-6">Yakin ingin menyetujui dan memulai kerja praktek ini? Status akan berubah menjadi "Sedang KP".</p>
                    <div class="flex space-x-3">
                        <button onclick="confirmApprove()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Ya, Mulai KP</button>
                        <button onclick="closeModal('approveModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Reject --}}
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full">
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Pengajuan KP</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                            <textarea name="catatan_dosen" rows="4" required
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                      placeholder="Berikan alasan penolakan..."></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Tolak</button>
                            <button type="button" onclick="closeModal('rejectModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentKpId = null;

        function approveKP(kpId) {
            currentKpId = kpId;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function rejectKP(kpId) {
            currentKpId = kpId;
            document.getElementById('rejectForm').action = `/admin/kerja-praktek/${kpId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function confirmApprove() {
            if (currentKpId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/kerja-praktek/${currentKpId}/approve`; // Pastikan route ini mengarah ke controller yang mengupdate status ke 'sedang_kp'
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        }

        function sendReminder(kpId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/kerja-praktek/${kpId}/send-reminder`;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</x-sidebar-layout>