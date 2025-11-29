<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-unib-blue-50 to-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            {{-- Header Card --}}
            <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white text-center rounded-t-xl">
                    <div class="flex items-center justify-center mb-2">
                        <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                            <i class="fas fa-key text-xl text-white"></i>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold">Reset Password</h2>
                    <p class="text-blue-100 text-sm mt-1">Buat password baru untuk akun Anda</p>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    required 
                                    autofocus 
                                    autocomplete="username"
                                    value="{{ old('email', $request->email) }}"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 transition duration-200"
                                    placeholder="masukkan email Anda"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                       