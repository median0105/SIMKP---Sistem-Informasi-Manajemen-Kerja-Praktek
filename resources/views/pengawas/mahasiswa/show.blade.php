{{-- resources/views/pengawas/mahasiswa/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Detail Mahasiswa</h2>
            <a href="{{ route('pengawas.mahasiswa.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $kp->mahasiswa->name }} ({{ $kp->mahasiswa->npm }})</h3>
                    <p class="text-sm text-gray-600">{{ $kp->judul_kp }}</p>
                    <div class="mt-2">
                        @include('components.kp-status-badge',['status'=>$kp->status])
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    <div>Mulai: {{ optional($kp->tanggal_mulai)->format('d M Y') ?: '-' }}</div>
                    <div>Selesai: {{ optional($kp->tanggal_selesai)->format('d M Y') ?: '-' }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="text-center border rounded-lg p-4">
                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $kp->file_laporan ? 'bg-green-100 text-green-700':'bg-gray-100 text-gray-500' }}">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="mt-2 font-medium">Laporan</div>
                    <div class="text-sm text-gray-600">{{ $kp->file_laporan ? 'Sudah upload' : 'Belum upload' }}</div>
                </div>

                <div class="text-center border rounded-lg p-4">
                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $kp->file_kartu_implementasi ? 'bg-yellow-100 text-yellow-700':'bg-gray-100 text-gray-500' }}">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="mt-2 font-medium">Kartu Implementasi</div>
                    <div class="text-sm text-gray-600">
                        @if($kp->acc_pembimbing_lapangan)
                            ACC Pembimbing Lapangan
                        @elseif($kp->file_kartu_implementasi)
                            Menunggu ACC
                        @else
                            Belum upload
                        @endif
                    </div>
                    @if($kp->file_kartu_implementasi && !$kp->acc_pembimbing_lapangan)
                        <form class="mt-3" method="POST" action="{{ route('pengawas.mahasiswa.acc-kartu', $kp) }}">
                            @csrf
                            <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-md text-sm" onclick="return confirm('ACC kartu implementasi?')">
                                ACC Kartu
                            </button>
                        </form>
                    @endif
                </div>

                <div class="text-center border rounded-lg p-4">
                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $kp->nilai_akhir ? 'bg-blue-100 text-blue-700':'bg-gray-100 text-gray-500' }}">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="mt-2 font-medium">Ujian</div>
                    <div class="text-sm text-gray-600">
                        @if($kp->nilai_akhir)
                            Nilai: {{ $kp->nilai_akhir }} — {{ $kp->lulus_ujian ? 'Lulus' : 'Tidak lulus' }}
                        @else
                            Belum ujian
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h4 class="font-semibold text-gray-900 mb-2">Feedback Pembimbing Lapangan</h4>
                <form method="POST" action="{{ route('pengawas.mahasiswa.feedback', $kp) }}" class="space-y-3">
                    @csrf
                    <textarea name="catatan_pengawas" rows="4" class="w-full border-gray-300 rounded-md" placeholder="Tulis catatan/masukan...">{{ old('catatan_pengawas', $kp->catatan_pengawas) }}</textarea>
                    <button class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-md">
                        Simpan Feedback
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
