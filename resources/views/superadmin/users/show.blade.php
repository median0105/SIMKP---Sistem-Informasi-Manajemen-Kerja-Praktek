<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('superadmin.kerja-praktek.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 transform hover:scale-105 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Detail Mahasiswa - {{ $user->name }}
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Flash messages for success and error --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up mb-6">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up mb-6">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- User Information Card --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 mb-8 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                {{-- Card header with UNIB blue gradient --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white">
                    <h3 class="text-xl font-bold flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>Informasi User
                    </h3>
                </div>
                
                {{-- Card content --}}
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col space-y-4">
                            <div class="flex items-center">
                                <i class="fas fa-user text-unib-blue-500 mr-3 w-5 text-center"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Nama</p>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-unib-blue-500 mr-3 w-5 text-center"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-medium text-gray-900">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user-tag text-unib-blue-500 mr-3 w-5 text-center"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Role</p>
                                    <p class="font-medium text-gray-900">{{ ucfirst($user->role) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-4">
                            @if($user->npm)
                            <div class="flex items-center">
                                <i class="fas fa-id-card text-unib-blue-500 mr-3 w-5 text-center"></i>
                                <div>
                                    <p class="text-sm text-gray-500">NPM</p>
                                    <p class="font-medium text-gray-900">{{ $user->npm }}</p>
                                </div>
                            </div>
                            @endif
                            <div class="flex items-center">
                                <i class="fas fa-circle text-unib-blue-500 mr-3 w-5 text-center"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->is_active ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' }} shadow-sm">
                                        <i class="{{ $user->is_active ? 'fas fa-check-circle' : 'fas fa-times-circle' }} mr-2 text-xs"></i>
                                        {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-unib-blue-500 mr-3 w-5 text-center"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Dibuat</p>
                                    <p class="font-medium text-gray-900">{{ $user->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kerja Praktek Data Card --}}
            @if($user->role === 'mahasiswa')
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 mb-8 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                {{-- Card header with UNIB blue gradient --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white">
                    <h3 class="text-xl font-bold flex items-center">
                        <i class="fas fa-briefcase mr-3"></i>Data Kerja Praktek
                    </h3>
                </div>
                
                {{-- Card content --}}
                <div class="p-6">
                    @if($user->kerjaPraktek->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($user->kerjaPraktek as $kp)
                            <div class="border rounded-lg p-4 transition-all duration-200 hover:shadow-md {{ $kp->status === 'ditolak' ? 'border-red-300 bg-red-50' : 'border-gray-300 bg-gray-50 hover:bg-white' }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-lg text-gray-900 mb-2">{{ $kp->judul_kp }}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-circle mr-2 text-xs {{ $kp->status === 'ditolak' ? 'text-red-500' : 'text-unib-blue-500' }}"></i>
                                                <span class="text-sm text-gray-600">Status: 
                                                    <span class="font-medium {{ $kp->status === 'ditolak' ? 'text-red-600' : 'text-gray-800' }}">
                                                        {{ ucfirst($kp->status) }}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar mr-2 text-xs text-unib-blue-500"></i>
                                                <span class="text-sm text-gray-600">
                                                    Tanggal Pengajuan: {{ $kp->created_at->locale('id')->translatedFormat('d F Y') }}
                                                </span>
                                            </div>
                                            @if($kp->tempatMagang)
                                            <div class="flex items-center md:col-span-2">
                                                <i class="fas fa-building mr-2 text-xs text-unib-blue-500"></i>
                                                <span class="text-sm text-gray-600">
                                                    Tempat Magang: {{ $kp->tempatMagang->nama_perusahaan }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if($kp->status === 'ditolak')
                                    <div class="ml-4 flex-shrink-0">
                                        <form method="POST" action="{{ route('superadmin.users.destroyKP', [$user, $kp]) }}" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data KP yang ditolak ini? Mahasiswa dapat mengajukan ulang setelah penghapusan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg text-sm shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                                                <i class="fas fa-trash mr-2"></i>Hapus KP
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                            <div class="text-base font-medium text-gray-900 mb-2">Belum Ada Data Kerja Praktek</div>
                            <p class="text-sm text-gray-600">Mahasiswa ini belum mengajukan kerja praktek.</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="flex justify-end space-x-4 animate-fade-in-up">
                <a href="{{ route('superadmin.users.edit', $user) }}" 
                   class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
            </div>
        </div>
    </div>

    {{-- CSS untuk animasi kustom --}}
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</x-sidebar-layout>