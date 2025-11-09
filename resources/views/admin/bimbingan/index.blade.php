<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Bimbingan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-2 mb-6">
                <a href="{{ route('admin.bimbingan.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center text-sm">
                    <i class="fas fa-plus mr-2"></i>Tambah Bimbingan
                </a>
            </div>
            {{-- Filter --}}
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari topik atau nama mahasiswa…"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>
                    <div>
                        <select name="status" class="border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                            <option value="all" @selected(request('status')==='all' || !request('status'))>Semua Status</option>
                            <option value="pending"  @selected(request('status')==='pending')>Pending</option>
                            <option value="verified" @selected(request('status')==='verified')>Verified</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                </form>
            </div>

            {{-- Daftar Mahasiswa --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Mahasiswa Bimbingan</h3>
                </div>

                @if($mahasiswa->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat KP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bimbingan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bimbingan Terakhir</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($mahasiswa as $mhs)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $mhs->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $mhs->npm }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                @if($mhs->kerjaPraktek && $mhs->kerjaPraktek->first() && $mhs->kerjaPraktek->first()->pilihan_tempat == 3)
                                                    {{ $mhs->kerjaPraktek->first()->tempat_magang_sendiri }}
                                                @elseif($mhs->kerjaPraktek && $mhs->kerjaPraktek->first() && $mhs->kerjaPraktek->first()->tempatMagang)
                                                    {{ $mhs->kerjaPraktek->first()->tempatMagang->nama_perusahaan }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $mhs->bimbingan->count() }} bimbingan
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($mhs->bimbingan->first())
                                                {{ \Illuminate\Support\Carbon::parse($mhs->bimbingan->first()->tanggal_bimbingan)->locale('id')->translatedFormat('d F Y') }}
                                            @else
                                                Belum ada
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.bimbingan.show', ['mahasiswa' => $mhs->id]) }}"
                                               class="text-unib-blue-600 hover:text-unib-blue-800 inline-flex items-center">
                                                <i class="fas fa-eye mr-1"></i>Detail Bimbingan
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $mahasiswa->withQueryString()->links() }}
                    </div>
                @else
                    <div class="p-10 text-center">
                        <i class="fas fa-users text-5xl text-gray-300 mb-3"></i>
                        <p class="text-gray-600">Belum ada mahasiswa yang dibimbing.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Feedback --}}
    <div id="feedbackModal" class="fixed inset-0 bg-black/40 hidden">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-lg w-full max-w-md overflow-hidden">
                <form id="feedbackForm" method="POST">
                    @csrf
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Beri Feedback</h3>
                        <textarea name="catatan_dosen" rows="4" required
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                  placeholder="Tulis feedback untuk mahasiswa…"></textarea>
                        <div class="mt-4 flex justify-end gap-2">
                            <button type="button" onclick="closeFeedback()"
                                    class="px-4 py-2 rounded-md bg-gray-200 text-gray-700">Batal</button>
                            <button type="submit"
                                    class="px-4 py-2 rounded-md bg-unib-blue-600 text-white">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openFeedback(id) {
            const f = document.getElementById('feedbackForm');
            f.action = `/admin/bimbingan/${id}/feedback`;
            document.getElementById('feedbackModal').classList.remove('hidden');
        }
        function closeFeedback() {
            document.getElementById('feedbackModal').classList.add('hidden');
        }
    </script>
</x-sidebar-layout>
