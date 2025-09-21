<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Bimbingan') }}
            </h2>
            <div class="flex space-x-2">
                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                    Pending: {{ $bimbingan->where('status_verifikasi', false)->count() }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                            <option value="">Semua Status</option>
                            <option value="pending"  @selected(request('status')==='pending')>Pending</option>
                            <option value="verified" @selected(request('status')==='verified')>Verified</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-2 rounded-md font-medium">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </form>
            </div>

            {{-- Daftar Bimbingan --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Bimbingan Mahasiswa</h3>
                </div>

                @if($bimbingan->count())
                    <div class="divide-y divide-gray-200">
                        @foreach($bimbingan as $item)
                            <div class="p-6 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-1">
                                            <h4 class="text-base md:text-lg font-medium text-gray-900">
                                                {{ $item->topik_bimbingan }}
                                            </h4>
                                            @if($item->status_verifikasi)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>Verified
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-sm text-gray-600">
                                            Mahasiswa:
                                            <span class="font-medium text-gray-900">
                                                {{ optional($item->mahasiswa)->name }}
                                                @if(optional($item->mahasiswa)->npm)
                                                    ({{ $item->mahasiswa->npm }})
                                                @endif
                                            </span>
                                        </p>

                                        <p class="text-sm text-gray-600">
                                            Tanggal:
                                            <span class="font-medium text-gray-900">
                                                @if($item->tanggal_bimbingan)
                                                    {{ \Illuminate\Support\Carbon::parse($item->tanggal_bimbingan)->format('d M Y H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </p>

                                        @if($item->catatan_mahasiswa)
                                            <p class="text-sm text-gray-700 mt-2">
                                                {{ \Illuminate\Support\Str::limit($item->catatan_mahasiswa, 180) }}
                                            </p>
                                        @endif

                                        @if($item->catatan_dosen)
                                            <div class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3">
                                                <p class="text-xs font-medium text-green-800 mb-1">Feedback Dosen</p>
                                                <p class="text-sm text-green-900">{{ $item->catatan_dosen }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ml-6 flex flex-col space-y-2">
                                        <a href="{{ route('admin.bimbingan.show', $item) }}"
                                           class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>

                                        @if(!$item->status_verifikasi)
                                            <form method="POST" action="{{ route('admin.bimbingan.verify', $item) }}"
                                                  onsubmit="return confirm('Verifikasi bimbingan ini?')">
                                                @csrf
                                                <button class="text-green-600 hover:text-green-800 text-left text-sm font-medium">
                                                    <i class="fas fa-check mr-1"></i>Verify
                                                </button>
                                            </form>

                                            <button type="button"
                                                    onclick="openFeedback({{ $item->id }})"
                                                    class="text-blue-600 hover:text-blue-800 text-left text-sm font-medium">
                                                <i class="fas fa-comment mr-1"></i>Feedback
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $bimbingan->withQueryString()->links() }}
                    </div>
                @else
                    <div class="p-10 text-center">
                        <i class="fas fa-comments text-5xl text-gray-300 mb-3"></i>
                        <p class="text-gray-600">Belum ada bimbingan.</p>
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
</x-app-layout>
