<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Mahasiswa - {{ $user->name }}
            </h2>
            <a href="{{ route('superadmin.kerja-praktek.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Informasi User</h3>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <strong>Nama:</strong> {{ $user->name }}
                            </div>
                            <div>
                                <strong>Email:</strong> {{ $user->email }}
                            </div>
                            <div>
                                <strong>Role:</strong> {{ ucfirst($user->role) }}
                            </div>
                            @if($user->npm)
                            <div>
                                <strong>NPM:</strong> {{ $user->npm }}
                            </div>
                            @endif
                            <div>
                                <strong>Status:</strong> {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </div>
                            <div>
                                <strong>Dibuat:</strong> {{ $user->created_at->locale('id')->translatedFormat('d F Y') }}
                            </div>
                        </div>
                    </div>

                    @if($user->role === 'mahasiswa')
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Data Kerja Praktek</h3>
                        @if($user->kerjaPraktek->isNotEmpty())
                            <div class="mt-4 space-y-4">
                                @foreach($user->kerjaPraktek as $kp)
                                <div class="border rounded-lg p-4 {{ $kp->status === 'ditolak' ? 'border-red-300 bg-red-50' : 'border-gray-300' }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium">{{ $kp->judul_kp }}</h4>
                                            <p class="text-sm text-gray-600">Status: <span class="font-medium {{ $kp->status === 'ditolak' ? 'text-red-600' : 'text-gray-800' }}">{{ ucfirst($kp->status) }}</span></p>
                                            <p class="text-sm text-gray-600">Tanggal Pengajuan: {{ $kp->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                                            @if($kp->tempatMagang)
                                            <p class="text-sm text-gray-600">Tempat Magang: {{ $kp->tempatMagang->nama_perusahaan }}</p>
                                            @endif
                                        </div>
                                        @if($kp->status === 'ditolak')
                                        <form method="POST" action="{{ route('superadmin.users.destroyKP', [$user, $kp]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data KP yang ditolak ini? Mahasiswa dapat mengajukan ulang setelah penghapusan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                Hapus KP
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-4 text-gray-600">Belum ada data Kerja Praktek.</p>
                        @endif
                    </div>
                    @endif

                    <div class="flex space-x-4">
                        {{-- <a href="{{ route('superadmin.kerja-praktek.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a> --}}
                        {{-- <a href="{{ route('superadmin.users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit User
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
