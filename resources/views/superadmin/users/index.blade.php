{{-- resources/views/superadmin/users/index.blade.php --}}
<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                  
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Manajemen User
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>
    
    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            {{-- Button to add new user --}}
            <div class="flex justify-end animate-fade-in-up">
                <a href="{{ route('superadmin.users.create') }}"
                   class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Tambah User
                </a>
            </div>

            {{-- Flash messages for success and error --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Filter form for searching users --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-base font-medium text-gray-700 mb-2">
                         
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email / NPM…"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                    </div>
                    
                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">
                       
                        </label>
                        <select name="role" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            <option value="">Semua Role</option>
                            <option value="superadmin"        @selected(request('role') === 'superadmin')>Super Admin</option>
                            <option value="admin_dosen"       @selected(request('role') === 'admin_dosen')>Admin Dosen</option>
                            <option value="pengawas_lapangan" @selected(request('role') === 'pengawas_lapangan')>Pengawas Lapangan</option>
                            <option value="mahasiswa"         @selected(request('role') === 'mahasiswa')>Mahasiswa</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transition duration-200 flex items-center justify-center flex-1">
                            <i class="fas fa-search mr-2"></i>Cari
                        </button>
                        <a href="{{ route('superadmin.users.index') }}" class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transition duration-200 flex items-center justify-center flex-1">
                            <i class="fas fa-undo mr-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Role mapping for labels and colors --}}
            @php
                $roleMap = [
                    'superadmin'        => ['Super Admin',        'bg-purple-100 text-purple-800 border-purple-300', 'fas fa-user-shield'],
                    'admin_dosen'       => ['Admin Dosen',       'bg-unib-blue-100 text-unib-blue-800 border-unib-blue-300', 'fas fa-chalkboard-teacher'],
                    'pengawas_lapangan' => ['Pengawas Lapangan', 'bg-teknik-orange-100 text-teknik-orange-800 border-teknik-orange-300', 'fas fa-clipboard-check'],
                    'mahasiswa'         => ['Mahasiswa',         'bg-green-100 text-green-800 border-green-300', 'fas fa-user-graduate'],
                ];
            @endphp

            {{-- =========================
                 MODE: SEMUA ROLE → multi tabel (displaying separate tables for each role)
            ========================== --}}
            @isset($usersByRole)
                @foreach (['superadmin','admin_dosen','pengawas_lapangan','mahasiswa'] as $key)
                    @php
                        /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $collection */
                        $collection = $usersByRole[$key];
                        [$label, $cls, $icon] = $roleMap[$key];
                    @endphp

                    {{-- Table container with UNIB blue color scheme --}}
                    <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 mb-8 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                        {{-- Table header with UNIB blue gradient - DIPERBAIKI --}}
                        <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                            <h3 class="text-xl font-bold">
                                Tabel: {{ $label }}
                            </h3>
                            <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                                <i class="fas fa-users mr-2"></i>
                                Total: {{ $collection->total() }}
                            </div>
                        </div>

                        {{-- Scrollable table body --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                {{-- Table header --}}
                                <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                                </thead>
                                {{-- Table body --}}
                                <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($collection as $user)
                                    <tr class="hover:bg-unib-blue-50 transition duration-150">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900 text-base">{{ $user->name }}</div>
                                            @if($user->npm && $key === 'mahasiswa')
                                                <div class="text-sm text-gray-500 mt-1 flex items-center">
                                                    <i class="fas fa-id-card mr-1 text-xs text-unib-blue-400"></i>
                                                    NPM: {{ $user->npm }}
                                                </div>
                                            @endif
                                            @if($user->nip && in_array($key, ['superadmin', 'admin_dosen']))
                                                <div class="text-sm text-gray-500 mt-1 flex items-center">
                                                    <i class="fas fa-id-card mr-1 text-xs text-unib-blue-400"></i>
                                                    NIP: {{ $user->nip }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 text-base">{{ $user->email }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $r = $user->role;
                                                [$rlbl,$rcls,$ricon] = $roleMap[$r] ?? [$r,'bg-gray-100 text-gray-800 border-gray-300', 'fas fa-user'];
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $rcls }} border shadow-sm">
                                                <i class="{{ $ricon }} mr-2 text-xs"></i>
                                                {{ $rlbl }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($user->is_active)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                                    <i class="fas fa-check-circle mr-2 text-xs"></i>Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm">
                                                    <i class="fas fa-times-circle mr-2 text-xs"></i>Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                {{-- Toggle active/inactive status --}}
                                                @if(!(auth()->user()->role === 'superadmin' && $user->id === auth()->id()))
                                                    <form method="POST" action="{{ route('superadmin.users.toggle-status', $user) }}" class="flex">
                                                        @csrf
                                                        <button type="submit"
                                                                class="text-xs px-3 py-2 rounded border font-medium shadow-sm transition duration-200
                                                                       {{ $user->is_active ? 'border-red-300 text-red-700 hover:bg-red-50' : 'border-green-300 text-green-700 hover:bg-green-50' }}">
                                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs px-3 py-2 rounded border border-gray-300 text-gray-500 bg-gray-100">
                                                        Tidak bisa dinonaktifkan
                                                    </span>
                                                @endif

                                                {{-- Edit user link --}}
                                                <a href="{{ route('superadmin.users.edit', $user) }}"
                                                   class="text-unib-blue-600 hover:text-unib-blue-800 text-xs font-medium flex items-center px-3 py-2 rounded hover:bg-unib-blue-100 transition duration-200"
                                                   title="Edit User">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </a>

                                                {{-- Delete user form --}}
                                                <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}"
                                                      onsubmit="return confirm('Hapus pengguna ini?');" class="flex">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium flex items-center px-3 py-2 rounded hover:bg-red-50 transition duration-200"
                                                            title="Hapus User">
                                                        <i class="fas fa-trash mr-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Empty state for table --}}
                                    <tr>
                                        <td colspan="5" class="text-center py-12 text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                                <div class="text-base font-medium text-gray-900 mb-2">Tidak Ada Data</div>
                                                <p class="text-sm text-gray-600">Tidak ada data pengguna untuk role ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination for each table --}}
                        <div class="p-6 border-t bg-unib-blue-50">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-unib-blue-700">
                                    Menampilkan {{ $collection->firstItem() }} - {{ $collection->lastItem() }} dari {{ $collection->total() }} hasil
                                </p>
                                <div class="flex space-x-1">
                                    {{ $collection->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endisset

            {{-- =========================
                 MODE: ROLE TERTENTU → single tabel (displaying a single table for a specific role)
            ========================== --}}
            @isset($users)
                {{-- Single table container --}}
                <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    {{-- Table header with UNIB gradient - DIPERBAIKI --}}
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                        <h3 class="text-xl font-bold">
                            Tabel Pengguna
                        </h3>
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                            <i class="fas fa-users mr-2"></i>
                            Total: {{ $users->total() }}
                        </div>
                    </div>

                    {{-- Scrollable table body --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            {{-- Table header --}}
                            <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                    Nama
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                    ID
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                    Role
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                            </thead>
                            {{-- Table body --}}
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($users as $user)
                                <tr class="hover:bg-unib-blue-50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900 text-base">{{ $user->name }}</div>
                                        @if($user->npm)
                                            <div class="text-sm text-gray-500 mt-1 flex items-center">
                                                <i class="fas fa-id-card mr-1 text-xs text-unib-blue-400"></i>
                                                NPM: {{ $user->npm }}
                                            </div>
                                        @endif
                                        @if($user->nip)
                                            <div class="text-sm text-gray-500 mt-1 flex items-center">
                                                <i class="fas fa-id-card mr-1 text-xs text-unib-blue-400"></i>
                                                NIP: {{ $user->nip }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900 text-base">{{ $user->email }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->npm)
                                            <span class="text-gray-900 text-base">{{ $user->npm }}</span>
                                        @elseif($user->nip)
                                            <span class="text-gray-900 text-base">{{ $user->nip }}</span>
                                        @else
                                            <span class="text-gray-500 text-base">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $r = $user->role;
                                            [$rlbl,$rcls,$ricon] = $roleMap[$r] ?? [$r,'bg-gray-100 text-gray-800 border-gray-300', 'fas fa-user'];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $rcls }} border shadow-sm">
                                            <i class="{{ $ricon }} mr-2 text-xs"></i>
                                            {{ $rlbl }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                                <i class="fas fa-check-circle mr-2 text-xs"></i>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm">
                                                <i class="fas fa-times-circle mr-2 text-xs"></i>Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- Toggle active/inactive status --}}
                                            @if(!(auth()->user()->role === 'superadmin' && $user->id === auth()->id()))
                                                <form method="POST" action="{{ route('superadmin.users.toggle-status', $user) }}" class="flex">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-xs px-3 py-2 rounded border font-medium shadow-sm transition duration-200
                                                                   {{ $user->is_active ? 'border-red-300 text-red-700 hover:bg-red-50' : 'border-green-300 text-green-700 hover:bg-green-50' }}">
                                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs px-3 py-2 rounded border border-gray-300 text-gray-500 bg-gray-100">
                                                    Tidak bisa dinonaktifkan
                                                </span>
                                            @endif

                                            {{-- Edit user link --}}
                                            <a href="{{ route('superadmin.users.edit', $user) }}"
                                               class="text-unib-blue-600 hover:text-unib-blue-800 text-xs font-medium flex items-center px-3 py-2 rounded hover:bg-unib-blue-100 transition duration-200"
                                               title="Edit User">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>

                                            {{-- Delete user form --}}
                                            <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}"
                                                  onsubmit="return confirm('Hapus pengguna ini?');" class="flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium flex items-center px-3 py-2 rounded hover:bg-red-50 transition duration-200"
                                                        title="Hapus User">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                           @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12 text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                            <div class="text-base font-medium text-gray-900 mb-2">Tidak Ada Data</div>
                                            <p class="text-sm text-gray-600">Tidak ada data pengguna yang ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination dengan style UNIB --}}
                    <div class="p-6 border-t bg-unib-blue-50">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-unib-blue-700">
                                Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} hasil
                            </p>
                            <div class="flex space-x-1">
                                {{ $users->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endisset

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