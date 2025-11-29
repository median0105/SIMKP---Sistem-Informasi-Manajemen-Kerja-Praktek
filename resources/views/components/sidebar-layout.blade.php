<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIMKP - Sistem Informasi Manajemen Kerja Praktek</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/unib.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>[x-cloak]{display:none!important}</style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <!-- Mobile menu overlay -->
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main content -->
        <div class="flex-1 lg:pl-64">
            <!-- Top bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 lg:hidden">
                <div class="flex items-center justify-between px-4 py-3">
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
                        <i class="fas fa-bars text-lg"></i>
                    </button>

                    <!-- Notification Bell (Mobile) -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            @php
                                $unreadCount = App\Services\NotificationService::getUnreadCount(auth()->id());
                            @endphp
                            <a href="{{ route('notifications.index') }}"
                               class="p-2 text-gray-500 hover:text-unib-blue-600 relative">
                                <i class="fas fa-bell text-lg"></i>
                                @if($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                       {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                    </span>
                                @endif
                            </a>
                        </div>

                        <!-- User dropdown (Mobile) -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 focus:outline-none transition ease-in-out duration-150">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                             alt="Avatar"
                                             class="w-8 h-8 rounded-full border-2 border-gray-300 shadow-sm mr-2 object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-200 border-2 border-gray-300 flex items-center justify-center text-gray-700 font-semibold mr-2 shadow-sm">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>

                <!-- Page Heading (Mobile) -->
                @if (isset($header))
                    <div class="px-4 py-3 border-t border-gray-200">
                        {{ $header }}
                    </div>
                @endif
            </header>

            <!-- Desktop Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow-sm border-b border-gray-200 hidden lg:block">
                    <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                        <div>{{ $header }}</div>

                        <!-- User Dropdown (Desktop) -->
                        <div class="flex items-center space-x-4">
                            <!-- Notification Bell (Desktop) -->
                            <div class="relative">
                                @php
                                    $unreadCount = App\Services\NotificationService::getUnreadCount(auth()->id());
                                @endphp
                                <a href="{{ route('notifications.index') }}"
                                   class="p-2 text-gray-500 hover:text-unib-blue-600 relative">
                                    <i class="fas fa-bell text-lg"></i>
                                    @if($unreadCount > 0)
                                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                           {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                        </span>
                                    @endif
                                </a>
                            </div>

                            <!-- User Dropdown -->
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 focus:outline-none transition ease-in-out duration-150">
                                        @if(Auth::user()->avatar)
                                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                                 alt="Avatar"
                                                 class="w-10 h-10 rounded-full border-2 border-gray-300 shadow-sm mr-2 object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-200 border-2 border-gray-300 flex items-center justify-center text-gray-700 font-semibold mr-2 shadow-sm">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>

        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"></div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <script>
        // Auto hide messages
        setTimeout(() => {
            const successMsg = document.getElementById('success-message');
            const errorMsg = document.getElementById('error-message');

            if (successMsg) {
                successMsg.classList.remove('translate-x-full');
                setTimeout(() => {
                    successMsg.classList.add('translate-x-full');
                }, 3000);
            }

            if (errorMsg) {
                errorMsg.classList.remove('translate-x-full');
                setTimeout(() => {
                    errorMsg.classList.add('translate-x-full');
                }, 3000);
            }
        }, 100);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>
</html>
