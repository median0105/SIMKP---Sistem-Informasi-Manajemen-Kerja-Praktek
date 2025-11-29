<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <!-- Ikon dihapus -->
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Notifikasi
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Mark All Read Button --}}
            @if($notifications->count() > 0)
                <div class="flex justify-end animate-fade-in-up">
                    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                        @csrf
                        <button type="submit" class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center">
                            <i class="fas fa-check-double mr-2"></i>
                            Tandai Semua Sudah Dibaca
                        </button>
                    </form>
                </div>
            @endif

            {{-- Notifications Card --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                @if($notifications->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($notifications as $notification)
                            <div class="p-6 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }} transition duration-150">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        @switch($notification->type)
                                            @case('success')
                                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center border border-green-200">
                                                    <i class="fas fa-check text-green-600"></i>
                                                </div>
                                                @break
                                            @case('error')
                                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center border border-red-200">
                                                    <i class="fas fa-times text-red-600"></i>
                                                </div>
                                                @break
                                            @case('warning')
                                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center border border-yellow-200">
                                                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                                </div>
                                                @break
                                            @default
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center border border-blue-200">
                                                    <i class="fas fa-info text-blue-600"></i>
                                                </div>
                                        @endswitch
                                    </div>

                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-base font-medium text-gray-900 {{ $notification->is_read ? '' : 'font-bold' }}">
                                                {{ $notification->title }}
                                            </h3>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs text-gray-500">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                                @if(!$notification->is_read)
                                                    <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="mt-2 text-sm text-gray-600">
                                            {{ $notification->message }}
                                        </p>

                                        <div class="mt-4 flex items-center space-x-4">
                                            {{-- Link ke halaman tujuan --}}
                                            @if($notification->action_url)
                                                <a href="{{ $notification->action_url }}"
                                                   data-mark-url="{{ route('notifications.mark-read', $notification) }}"
                                                   class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium flex items-center js-mark-then-go">
                                                    <i class="fas fa-external-link-alt mr-2"></i>
                                                    Lihat Detail
                                                </a>
                                            @endif

                                            {{-- Tombol tandai sudah dibaca --}}
                                            @if(!$notification->is_read)
                                                <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm font-medium flex items-center">
                                                        <i class="fas fa-check mr-2"></i>
                                                        Tandai Sudah Dibaca
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="px-6 py-4 border-t border-unib-blue-200 bg-unib-blue-50">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-unib-blue-700">
                                Menampilkan {{ $notifications->firstItem() }} - {{ $notifications->lastItem() }} dari {{ $notifications->total() }} notifikasi
                            </p>
                            <div class="flex space-x-1">
                                {{ $notifications->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Empty State dengan Animasi DotLottie --}}
                    <div class="text-center py-16 text-gray-500 animate-fade-in-up">
                        <div class="flex flex-col items-center justify-center">
                            <!-- DotLottie Animation -->
                            <div class="mb-6 lottie-container">
                                <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
                                <dotlottie-wc 
                                    src="https://lottie.host/afbe699e-d2e0-417e-81ab-d568e3d08e1b/3a5CZ9DW7H.lottie" 
                                    style="width: 300px; height: 300px;" 
                                    autoplay 
                                    loop>
                                </dotlottie-wc>
                            </div>
                            <div class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Notifikasi</div>
                            <p class="text-base text-gray-600 max-w-md mx-auto">
                                Anda belum memiliki notifikasi apapun.
                            </p>
                        </div>
                    </div>
                @endif
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
        
        /* Style untuk dotlottie container */
        .lottie-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }
        
        /* Smooth transitions */
        .transition-colors {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
    </style>

    {{-- JS: klik "Lihat Detail" → kirim POST mark-read dulu, baru redirect ke action_url --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-mark-then-go').forEach(link => {
                link.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const markUrl = link.dataset.markUrl;
                    try {
                        await fetch(markUrl, {
                            method: 'POST',
                            headers: { 
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        });
                    } catch(e) {
                        // gagal menandai dibaca bukan masalah fatal, tetap redirect
                        console.error('Failed to mark notification as read:', e);
                    }
                    window.location.href = link.getAttribute('href');
                });
            });
            
            // Ensure dotlottie-wc loads properly
            if (typeof customElements !== 'undefined') {
                customElements.whenDefined('dotlottie-wc').then(() => {
                    console.log('DotLottie Web Component loaded successfully');
                });
            }
        });
    </script>

    <!-- DotLottie Web Component Script -->
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
</x-sidebar-layout>