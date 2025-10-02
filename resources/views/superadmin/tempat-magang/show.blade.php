{{-- resources/views/superadmin/tempat-magang/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Tempat — {{ $tempatMagang->nama_perusahaan }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('superadmin.tempat-magang.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Info Perusahaan --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-2">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $tempatMagang->nama_perusahaan }}</h3>
                        <p class="text-sm text-gray-600">{{ $tempatMagang->bidang_usaha }}</p>
                        <p class="text-sm text-gray-600">{{ $tempatMagang->alamat }}</p>
                        <div class="text-sm text-gray-600">
                            <div>Kontak: {{ $tempatMagang->kontak_person }}</div>
                            <div>Email: {{ $tempatMagang->email_perusahaan }}</div>
                            <div>Telepon: {{ $tempatMagang->telepon_perusahaan }}</div>
                        </div>
                        @if($tempatMagang->deskripsi)
                            <div class="pt-2 text-gray-700">{{ $tempatMagang->deskripsi }}</div>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <div>
                            @php
                                $terpakai = $tempatMagang->kerjaPraktek->whereIn('status',['disetujui','sedang_kp'])->count();
                                $sisa = max(0, $tempatMagang->kuota_mahasiswa - $terpakai);
                            @endphp
                            <div class="text-sm text-gray-600">Kuota</div>
                            <div class="text-xl font-semibold">{{ $sisa }} / {{ $tempatMagang->kuota_mahasiswa }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Status</div>
                            @if($tempatMagang->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Tidak Aktif
                                </span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">
                            Total KP: {{ $tempatMagang->kerjaPraktek->count() }}<br>
                            Sedang KP: {{ $tempatMagang->kerjaPraktek->where('status','sedang_kp')->count() }}<br>
                            Selesai : {{ $tempatMagang->kerjaPraktek->where('status','selesai')->count() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Mahasiswa (jika ada) --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Mahasiswa</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul KP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tempatMagang->kerjaPraktek as $kp)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $kp->mahasiswa->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $kp->judul_kp }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst(str_replace('_',' ',$kp->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-6 text-center text-gray-500">Belum ada mahasiswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
