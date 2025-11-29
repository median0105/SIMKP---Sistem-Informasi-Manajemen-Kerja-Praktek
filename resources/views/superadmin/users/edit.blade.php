<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                   
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Edit Pengguna
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Flash Messages --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center mb-6">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    <div>
                        <p class="font-medium">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside mt-1 text-sm">
                            @foreach ($errors->all() as $e) 
                                <li>{{ $e }}</li> 
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Card Form --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-unib-blue-100">
                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Form Edit Pengguna
                    </h3>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    <form method="POST" action="{{ route('superadmin.users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div>
                            <label class="block text-base font-semibold text-gray-700 mb-3">
                                Nama <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-base font-semibold text-gray-700 mb-3">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                        </div>

                        {{-- Role dan Identitas --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-base font-semibold text-gray-700 mb-3">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select name="role" id="roleSelect" required 
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                                    <option value="superadmin"        @selected(old('role',$user->role)==='superadmin')>Super Admin</option>
                                    <option value="admin_dosen"       @selected(old('role',$user->role)==='admin_dosen')>Admin Dosen</option>
                                    <option value="pengawas_lapangan" @selected(old('role',$user->role)==='pengawas_lapangan')>Pengawas Lapangan</option>
                                    <option value="mahasiswa"         @selected(old('role',$user->role)==='mahasiswa')>Mahasiswa</option>
                                </select>
                            </div>
                            
                            {{-- NPM Group --}}
                            <div id="npmGroup" class="{{ old('role',$user->role)==='mahasiswa' ? '' : 'hidden' }}">
                                <label class="block text-base font-semibold text-gray-700 mb-3">
                                    NPM <span class="text-sm font-normal text-gray-500">(khusus Mahasiswa)</span>
                                </label>
                                <input type="text" name="npm" value="{{ old('npm', $user->npm) }}"
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            </div>
                            
                            {{-- NIP Group --}}
                            <div id="nipGroup" class="{{ in_array(old('role',$user->role), ['superadmin', 'admin_dosen']) ? '' : 'hidden' }}">
                                <label class="block text-base font-semibold text-gray-700 mb-3">
                                    NIP <span class="text-sm font-normal text-gray-500">(khusus Super Admin & Dosen)</span>
                                </label>
                                <input type="text" name="nip" value="{{ old('nip', $user->nip) }}"
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            </div>
                        </div>

                        {{-- No. HP --}}
                        <div>
                            <label class="block text-base font-semibold text-gray-700 mb-3">
                                No. HP
                            </label>
                            <input type="text" name="phone" value="{{ old('phone',$user->phone) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                        </div>

                        {{-- Password --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-base font-semibold text-gray-700 mb-3">
                                    Password <span class="text-sm font-normal text-gray-500">(opsional)</span>
                                </label>
                                <input type="password" name="password"
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200" 
                                       placeholder="Kosongkan jika tidak diubah">
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-gray-700 mb-3">
                                    Konfirmasi Password <span class="text-sm font-normal text-gray-500">(opsional)</span>
                                </label>
                                <input type="password" name="password_confirmation"
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200" 
                                       placeholder="Ulangi password">
                            </div>
                        </div>

                        {{-- Status Aktif --}}
                        <div class="mb-8">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                       class="text-unib-blue-600 focus:ring-unib-blue-500">
                                <span class="ml-2 text-base font-medium text-gray-700">Akun aktif</span>
                            </label>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('superadmin.users.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit"
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-8 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center">
                                <i class="fas fa-save mr-2"></i>Update Pengguna
                            </button>
                        </div>
                    </form>
                </div>
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