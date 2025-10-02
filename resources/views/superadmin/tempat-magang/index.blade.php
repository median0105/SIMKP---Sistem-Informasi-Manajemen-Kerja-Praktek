<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Tempat Magang') }}
            </h2>
            <a href="{{ route('superadmin.tempat-magang.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                <i class="fas fa-plus mr-2"></i>Tambah Tempat Magang
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Tempat</p>
                            <p class="text-2xl font-semibold text-blue-600 mt-2">{{ $stats['total_tempat'] }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <i class="fas fa-building text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tempat Aktif</p>
                            <p class="text-2xl font-semibold text-green-600 mt-2">{{ $stats['tempat_aktif'] }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Kuota</p>
                            <p class="text-2xl font-semibold text-purple-600 mt-2">{{ $stats['total_kuota'] }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3">
                            <i class="fas fa-users text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Terpakai</p>
                            <p class="text-2xl font-semibold text-orange-600 mt-2">{{ $stats['tempat_terpakai'] }}</p>
                        </div>
                        <div class="bg-orange-100 rounded-full p-3">
                            <i class="fas fa-handshake text-orange-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama perusahaan, bidang usaha, atau alamat..." 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>
                    <div>
                        <select name="status" class="border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div>
                        <select name="bidang_usaha" class="border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                            <option value="">Semua Bidang</option>
                            @foreach($bidangUsaha as $bidang)
                                <option value="{{ $bidang }}" {{ request('bidang_usaha') === $bidang ? 'selected' : '' }}>
                                    {{ $bidang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'bidang_usaha']))
                        <a href="{{ route('superadmin.tempat-magang.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md font-medium">
                            <i class="fas fa-times mr-2"></i>Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Bulk Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Tempat Magang</h3>
                        <div class="flex space-x-2">
                            <button onclick="toggleBulkActions()" id="bulkActionToggle" class="hidden bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">
                                <i class="fas fa-check-square mr-2"></i>Aksi Massal
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bulk Action Bar -->
                <div id="bulkActionBar" class="hidden px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <form method="POST" action="{{ route('superadmin.tempat-magang.bulk-action') }}" onsubmit="return confirmBulkAction()">
                        @csrf
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">
                                <span id="selectedCount">0</span> item dipilih
                            </span>
                            <select name="action" class="border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Pilih Aksi</option>
                                <option value="activate">Aktifkan</option>
                                <option value="deactivate">Nonaktifkan</option>
                                <option value="delete">Hapus</option>
                            </select>
                            <button type="submit" class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-md text-sm">
                                Jalankan
                            </button>
                            <button type="button" onclick="cancelBulkAction()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>

                @if($tempatMagang->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">
                                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-unib-blue-600" onchange="toggleSelectAll()">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perusahaan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bidang Usaha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tempatMagang as $tempat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="selected_items[]" value="{{ $tempat->id }}" 
                                                   class="item-checkbox rounded border-gray-300 text-unib-blue-600" 
                                                   onchange="updateBulkActions()">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $tempat->nama_perusahaan }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($tempat->alamat, 50) }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $tempat->bidang_usaha }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $tempat->kontak_person }}</div>
                                            <div class="text-sm text-gray-500">{{ $tempat->email_perusahaan }}</div>
                                            <div class="text-sm text-gray-500">{{ $tempat->telepon_perusahaan }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ $tempat->kuota_mahasiswa }}</div>
                                            <div class="text-xs text-gray-500">Mahasiswa</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($tempat->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ $tempat->terpakai_count }}</div>
                                            <div class="text-xs text-gray-500">Total KP</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('superadmin.tempat-magang.show', $tempat) }}" 
                                                   class="text-unib-blue-600 hover:text-unib-blue-900" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('superadmin.tempat-magang.edit', $tempat) }}" 
                                                   class="text-green-600 hover:text-green-900" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="toggleStatus({{ $tempat->id }})" 
                                                        class="text-yellow-600 hover:text-yellow-900" 
                                                        title="{{ $tempat->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas fa-{{ $tempat->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                                <button onclick="deleteItem({{ $tempat->id }}, '{{ $tempat->nama_perusahaan }}')" 
                                                        class="text-red-600 hover:text-red-900" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $tempatMagang->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Tempat Magang</h3>
                        <p class="text-gray-600 mb-6">Belum ada tempat magang yang terdaftar dalam sistem.</p>
                        <a href="{{ route('superadmin.tempat-magang.create') }}" 
                           class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                            <i class="fas fa-plus mr-2"></i>Tambah Tempat Magang Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            updateBulkActions();
        }

        function updateBulkActions() {
            const checkedItems = document.querySelectorAll('.item-checkbox:checked');
            const bulkActionToggle = document.getElementById('bulkActionToggle');
            const selectedCount = document.getElementById('selectedCount');
            
            if (checkedItems.length > 0) {
                bulkActionToggle.classList.remove('hidden');
                if (selectedCount) selectedCount.textContent = checkedItems.length;
            } else {
                bulkActionToggle.classList.add('hidden');
                cancelBulkAction();
            }
        }

        function toggleBulkActions() {
            const bulkActionBar = document.getElementById('bulkActionBar');
            bulkActionBar.classList.toggle('hidden');
        }

        function cancelBulkAction() {
            const bulkActionBar = document.getElementById('bulkActionBar');
            bulkActionBar.classList.add('hidden');
        }

        function confirmBulkAction() {
            const action = document.querySelector('select[name="action"]').value;
            const checkedItems = document.querySelectorAll('.item-checkbox:checked');
            
            if (!action) {
                alert('Pilih aksi yang akan dilakukan');
                return false;
            }
            
            const actionText = {
                'activate': 'mengaktifkan',
                'deactivate': 'menonaktifkan', 
                'delete': 'menghapus'
            };
            
            return confirm(`Yakin ingin ${actionText[action]} ${checkedItems.length} tempat magang terpilih?`);
        }

        function toggleStatus(id) {
    if (confirm('Yakin ingin mengubah status tempat magang ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('superadmin.tempat-magang.toggle-status', ':id') }}".replace(':id', id);
        form.innerHTML = `@csrf`;
        document.body.appendChild(form);
        form.submit();
    }
}

        function deleteItem(id, nama) {
            if (confirm(`PERINGATAN! Tindakan ini akan menghapus tempat magang "${nama}" secara permanen. Yakin ingin melanjutkan?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/superadmin/tempat-magang/${id}`;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>