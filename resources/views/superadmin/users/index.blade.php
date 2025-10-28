{{-- resources/views/superadmin/users/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen User
            </h2>
            <a href="{{ route('superadmin.users.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>Tambah User
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Filter --}}
            <div class="bg-white shadow rounded-lg p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email / NPM…"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>
                    <div>
                        <select name="role" class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                            <option value="">Semua Role</option>
                            <option value="superadmin"        @selected(request('role') === 'superadmin')>Super Admin</option>
                            <option value="admin_dosen"       @selected(request('role') === 'admin_dosen')>Admin Dosen</option>
                            <option value="pengawas_lapangan" @selected(request('role') === 'pengawas_lapangan')>Pengawas Lapangan</option>
                            <option value="mahasiswa"         @selected(request('role') === 'mahasiswa')>Mahasiswa</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Cari</button>
                        <a href="{{ route('superadmin.users.index') }}" class="ml-3 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            @php
                $roleMap = [
                    'superadmin'        => ['Super Admin',        'bg-purple-100 text-purple-700'],
                    'admin_dosen'       => ['Admin Dosen',       'bg-blue-100 text-blue-700'],
                    'pengawas_lapangan' => ['Pengawas Lapangan', 'bg-amber-100 text-amber-700'],
                    'mahasiswa'         => ['Mahasiswa',         'bg-green-100 text-green-700'],
                ];
            @endphp

            {{-- =========================
                 MODE: SEMUA ROLE → multi tabel
            ========================== --}}
            @isset($usersByRole)
                @foreach (['superadmin','admin_dosen','pengawas_lapangan','mahasiswa'] as $key)
                    @php
                        /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $collection */
                        $collection = $usersByRole[$key];
                        [$label, $cls] = $roleMap[$key];
                    @endphp

                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Tabel: {{ $label }}
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cls }}">
                                Total: {{ $collection->total() }}
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($collection as $user)
                                    <tr>
                                        <td class="px-4 py-2">
                                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                            @if($user->npm)
                                                <div class="text-xs text-gray-500">NPM: {{ $user->npm }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">
                                            <span class="text-gray-900">{{ $user->email }}</span>
                                        </td>
                                        <td class="px-4 py-2">
                                            @php
                                                $r = $user->role;
                                                [$rlbl,$rcls] = $roleMap[$r] ?? [$r,'bg-gray-100 text-gray-700'];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $rcls }}">
                                                {{ $rlbl }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            @if($user->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">
                                            <div class="flex items-center gap-2">
                                                {{-- Toggle aktif/nonaktif --}}
                                                @if(!(auth()->user()->role === 'superadmin' && $user->id === auth()->id()))
                                                    <form method="POST" action="{{ route('superadmin.users.toggle-status', $user) }}">
                                                        @csrf
                                                        <button type="submit"
                                                                class="text-xs px-3 py-1 rounded border
                                                                       {{ $user->is_active ? 'border-red-300 text-red-700 hover:bg-red-50' : 'border-green-300 text-green-700 hover:bg-green-50' }}">
                                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs px-3 py-1 rounded border border-gray-300 text-gray-500">
                                                        Tidak bisa dinonaktifkan
                                                    </span>
                                                @endif

                                                {{-- Edit --}}
                                                <a href="{{ route('superadmin.users.edit', $user) }}"
                                                   class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </a>

                                                {{-- Delete --}}
                                                <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}"
                                                      onsubmit="return confirm('Hapus pengguna ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                        <i class="fas fa-trash mr-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-gray-500">
                                            Tidak ada data pengguna.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="p-4 border-t">
                            {{-- Paginasi per tabel sudah punya page name unik dari controller --}}
                            {{ $collection->withQueryString()->links() }}
                        </div>
                    </div>
                @endforeach
            @endisset

            {{-- =========================
                 MODE: ROLE TERTENTU → single tabel
            ========================== --}}
            @isset($users)
                <div class="bg-white shadow rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($users as $user)
                                <tr>
                                    <td class="px-4 py-2">
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        @if($user->npm)
                                            <div class="text-xs text-gray-500">NPM: {{ $user->npm }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="text-gray-900">{{ $user->email }}</span>
                                    </td>
                                    <td class="px-4 py-2">
                                        @php
                                            $r = $user->role;
                                            [$rlbl,$rcls] = $roleMap[$r] ?? [$r,'bg-gray-100 text-gray-700'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $rcls }}">
                                            {{ $rlbl }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                        <td class="px-4 py-2">
                                            <div class="flex items-center gap-2">
                                                {{-- Toggle aktif/nonaktif --}}
                                                @if(!(auth()->user()->role === 'superadmin' && $user->id === auth()->id()))
                                                    <form method="POST" action="{{ route('superadmin.users.toggle-status', $user) }}">
                                                        @csrf
                                                        <button type="submit"
                                                                class="text-xs px-3 py-1 rounded border
                                                                       {{ $user->is_active ? 'border-red-300 text-red-700 hover:bg-red-50' : 'border-green-300 text-green-700 hover:bg-green-50' }}">
                                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs px-3 py-1 rounded border border-gray-300 text-gray-500">
                                                        Tidak bisa dinonaktifkan
                                                    </span>
                                                @endif

                                                {{-- Edit --}}
                                                <a href="{{ route('superadmin.users.edit', $user) }}"
                                                   class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </a>

                                                {{-- Delete --}}
                                                <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}"
                                                      onsubmit="return confirm('Hapus pengguna ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                        <i class="fas fa-trash mr-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-500">
                                        Tidak ada data pengguna.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t">
                        {{ $users->withQueryString()->links() }}
                    </div>
                </div>
            @endisset

        </div>
    </div>
</x-app-layout>
