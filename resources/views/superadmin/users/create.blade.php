<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('superadmin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Penggunaa</h2>
            </div>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('superadmin.users.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" id="roleSelect" required class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="superadmin"        @selected(old('role')==='superadmin')>Super Admin</option>
                                <option value="admin_dosen"       @selected(old('role')==='admin_dosen')>Admin Dosen</option>
                                <option value="pengawas_lapangan" @selected(old('role')==='pengawas_lapangan')>Pengawas Lapangan</option>
                                <option value="mahasiswa"         @selected(old('role')==='mahasiswa')>Mahasiswa</option>
                            </select>
                        </div>
                        <div id="npmGroup" class="{{ old('role')==='mahasiswa' ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">NPM (khusus Mahasiswa)</label>
                            <input type="text" name="npm" value="{{ old('npm') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div id="nipGroup" class="{{ in_array(old('role'), ['superadmin', 'admin_dosen']) ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIP (khusus Super Admin & Dosen)</label>
                            <input type="text" name="nip" value="{{ old('nip') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" required
                                   class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="rounded"
                               {{ old('is_active',1) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm text-gray-700">Aktifkan akun</label>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('superadmin.users.index') }}" class="px-4 py-2 rounded-md bg-gray-200 text-gray-700">Batal</a>
                        <button type="submit" class="px-5 py-2 rounded-md bg-unib-blue-600 text-white hover:bg-unib-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const roleSelect = document.getElementById('roleSelect');
        const npmGroup = document.getElementById('npmGroup');
        const nipGroup = document.getElementById('nipGroup');
        roleSelect?.addEventListener('change', () => {
            npmGroup.classList.toggle('hidden', roleSelect.value !== 'mahasiswa');
            nipGroup.classList.toggle('hidden', !['superadmin', 'admin_dosen'].includes(roleSelect.value));
        });
    </script>
</x-sidebar-layout>
