{{-- resources/views/admin/bimbingan/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Bimbingan
            </h2>
            <a href="{{ route('admin.bimbingan.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Kartu info utama --}}
            <div class="bg-white shadow rounded-lg divide-y">
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Mahasiswa</dt>
                            <dd class="text-gray-900 font-medium">
                                {{ optional($bimbingan->mahasiswa)->name ?? '-' }}
                                @if (optional($bimbingan->mahasiswa)->npm)
                                    <span class="text-gray-500 text-sm"> • {{ $bimbingan->mahasiswa->npm }}</span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-500">Tanggal</dt>
                            <dd class="text-gray-900 font-medium">
                                {{-- tampilkan salah satu field tanggal yang ada --}}
                                {{ $bimbingan->tanggal_bimbingan
                                    ?? $bimbingan->tanggal
                                    ?? ($bimbingan->created_at?->format('d M Y H:i') ?? '-') }}
                            </dd>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <dt class="text-sm text-gray-500">Catatan </dt>
                                    <dd class="text-gray-900 whitespace-pre-line">
                                    {{ $bimbingan->catatan_mahasiswa ?? '-' }}
                                </dd>
                            </div>
                        </div>

                        @if(!empty($bimbingan->file_dokumen))
                            <div class="md:col-span-2">
                                <dt class="text-sm text-gray-500">Lampiran</dt>
                                <dd>
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($bimbingan->file_dokumen) }}"
                                       target="_blank"
                                       class="text-unib-blue-600 hover:text-unib-blue-800">
                                        Unduh lampiran
                                    </a>
                                </dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm text-gray-500">Status Verifikasi</dt>
                            <dd>
                                @if(!empty($bimbingan->is_verified))
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        Menunggu
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Aksi dosen --}}
                <div class="p-6 space-y-4">
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Verifikasi --}}
                        <form method="POST" action="{{ route('admin.bimbingan.verify', $bimbingan) }}">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 rounded-md text-white bg-green-600 hover:bg-green-700">
                                Verifikasi
                            </button>
                        </form>

                        {{-- Form Feedback --}}
                        <form method="POST" action="{{ route('admin.bimbingan.feedback', $bimbingan) }}"
                              class="flex-1 min-w-[280px]">
                            @csrf
                            <div class="flex gap-2">
                                <input type="text" name="feedback" placeholder="Tulis catatan/feedback singkat…"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                       required>
                                <button type="submit"
                                        class="px-4 py-2 rounded-md text-white bg-unib-blue-600 hover:bg-unib-blue-700">
                                    Kirim
                                </button>
                            </div>
                            @error('feedback')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
