<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <i class="fas fa-user-circle text-white"></i>
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        {{ __('Profile Management') }}
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">
                        Kelola informasi profil dan keamanan akun Anda
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Profile Information Card -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Informasi Profil
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        <i class="fas fa-user mr-2"></i>Profile Data
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="h-8"></div>

            <!-- Profile Overview Table -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Ringkasan Profil
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: 6 Informasi
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Field
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Value
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <!-- Profile Photo -->
                            <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900 text-base flex items-center">
                                        <i class="fas fa-camera mr-3 text-unib-blue-600"></i>
                                        Foto Profil
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" 
                                                 alt="Profile Photo" 
                                                 class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-unib-blue-400 to-unib-blue-600 flex items-center justify-center">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                        <span class="text-gray-900 text-base font-medium">
                                            {{ $user->avatar ? 'Foto tersedia' : 'Default avatar' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->avatar ? 'bg-green-100 text-green-800 border-green-300' : 'bg-gray-100 text-gray-800 border-gray-300' }} border shadow-sm">
                                        {{ $user->avatar ? 'Tersedia' : 'Default' }}
                                    </span>
                                </td>
                            </tr>

                            <!-- Role Information -->
                            <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900 text-base flex items-center">
                                        <i class="fas fa-user-tag mr-3 text-unib-blue-600"></i>
                                        Peran
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 text-base font-medium">
                                        @php
                                            $roleLabels = [
                                                'superadmin' => 'Super Admin',
                                                'admin_dosen' => 'Admin Dosen',
                                                'pengawas_lapangan' => 'Pengawas Lapangan',
                                                'mahasiswa' => 'Mahasiswa',
                                            ];
                                        @endphp
                                        {{ $roleLabels[$user->role] ?? 'User' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleBadges = [
                                            'superadmin' => ['bg-purple-100 text-purple-800 border-purple-300', 'Super Admin'],
                                            'admin_dosen' => ['bg-blue-100 text-blue-800 border-blue-300', 'Admin Dosen'],
                                            'pengawas_lapangan' => ['bg-green-100 text-green-800 border-green-300', 'Pengawas'],
                                            'mahasiswa' => ['bg-orange-100 text-orange-800 border-orange-300', 'Mahasiswa'],
                                        ];
                                        [$roleClass, $roleLabel] = $roleBadges[$user->role] ?? ['bg-gray-100 text-gray-800 border-gray-300', 'User'];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $roleClass }} border shadow-sm">
                                        {{ $roleLabel }}
                                    </span>
                                </td>
                            </tr>

                            <!-- Email Verification -->
                            <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900 text-base flex items-center">
                                        <i class="fas fa-envelope mr-3 text-unib-blue-600"></i>
                                        Verifikasi Email
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 text-base font-medium">
                                        {{ $user->email }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->hasVerifiedEmail() ? 'bg-green-100 text-green-800 border-green-300' : 'bg-yellow-100 text-yellow-800 border-yellow-300' }} border shadow-sm">
                                        <i class="fas {{ $user->hasVerifiedEmail() ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                        {{ $user->hasVerifiedEmail() ? 'Terverifikasi' : 'Menunggu' }}
                                    </span>
                                </td>
                            </tr>

                            <!-- Role Specific Information -->
                            @if($user->role === 'superadmin' || $user->role === 'admin_dosen')
                                <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900 text-base flex items-center">
                                            <i class="fas fa-id-badge mr-3 text-unib-blue-600"></i>
                                            NIP
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-gray-900 text-base font-medium">
                                            {{ $user->nip ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->nip ? 'bg-green-100 text-green-800 border-green-300' : 'bg-gray-100 text-gray-800 border-gray-300' }} border shadow-sm">
                                            {{ $user->nip ? 'Tersedia' : 'Kosong' }}
                                        </span>
                                    </td>
                                </tr>
                            @elseif($user->role === 'mahasiswa')
                                <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900 text-base flex items-center">
                                            <i class="fas fa-graduation-cap mr-3 text-unib-blue-600"></i>
                                            NPM
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-gray-900 text-base font-medium">
                                            {{ $user->npm ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->npm ? 'bg-green-100 text-green-800 border-green-300' : 'bg-gray-100 text-gray-800 border-gray-300' }} border shadow-sm">
                                            {{ $user->npm ? 'Tersedia' : 'Kosong' }}
                                        </span>
                                    </td>
                                </tr>
                            @endif

                            <!-- Phone Number -->
                            <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900 text-base flex items-center">
                                        <i class="fas fa-phone mr-3 text-unib-blue-600"></i>
                                        No. HP
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 text-base font-medium">
                                        {{ $user->phone ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->phone ? 'bg-green-100 text-green-800 border-green-300' : 'bg-gray-100 text-gray-800 border-gray-300' }} border shadow-sm">
                                        {{ $user->phone ? 'Tersedia' : 'Kosong' }}
                                    </span>
                                </td>
                            </tr>

                            <!-- Account Status -->
                            <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900 text-base flex items-center">
                                        <i class="fas fa-shield-alt mr-3 text-unib-blue-600"></i>
                                        Status Akun
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 text-base font-medium">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->is_active ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300' }} border shadow-sm">
                                        <i class="fas {{ $user->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="h-8"></div>

            <!-- Update Password Card -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Keamanan Akun
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        <i class="fas fa-lock mr-2"></i>Update Password
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="h-8"></div>

            <!-- Delete Account Card -->
           <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Hapus Akun
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

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

        .transition-colors {
            transition: all 0.2s ease;
        }

        .hover\:bg-unib-blue-50:hover {
            background-color: rgb(239 246 255);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
                        submitBtn.disabled = true;
                    }
                });
            });
        });
    </script>
</x-sidebar-layout>