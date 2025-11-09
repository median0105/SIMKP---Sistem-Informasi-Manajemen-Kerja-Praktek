<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifikasi') }}
            </h2>
        </div>  
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($notifications->count() > 0)
                <div class="mb-6">
                    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                        @csrf
                        <button type="submit" class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                            <i class="fas fa-check-double mr-1"></i>
                            Tandai Semua Sudah Dibaca
                        </button>
                    </form>
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($notifications->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($notifications as $notification)
                            <div class="p-6 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }} hover:bg-gray-50 transition duration-150">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        @switch($notification->type)
                                            @case('success')
                                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-check text-green-600"></i>
                                                </div>
                                                @break
                                            @case('error')
                                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-times text-red-600"></i>
                                                </div>
                                                @break
                                            @case('warning')
                                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                                </div>
                                                @break
                                            @default
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-info text-blue-600"></i>
                                                </div>
                                        @endswitch
                                    </div>

                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium text-gray-900 {{ $notification->is_read ? '' : 'font-bold' }}">
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

                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ $notification->message }}
                                        </p>

                                        <div class="mt-3 flex items-center space-x-3">
                                            {{-- LINK ke halaman tujuan: klik → POST dulu ke mark-read, baru redirect --}}
                                            @if($notification->action_url)
                                                <a href="{{ $notification->action_url }}"
                                                   data-mark-url="{{ route('notifications.mark-read', $notification) }}"
                                                   class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium js-mark-then-go">
                                                    <i class="fas fa-external-link-alt mr-1"></i>
                                                    Lihat Detail
                                                </a>
                                            @endif

                                            {{-- Tombol tandai sudah dibaca (POST murni) --}}
                                            @if(!$notification->is_read)
                                                <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm">
                                                        <i class="fas fa-check mr-1"></i>
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
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="p-6 text-center">
                        <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Notifikasi</h3>
                        <p class="text-gray-600">Anda belum memiliki notifikasi apapun.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- JS: klik "Lihat Detail" → kirim POST mark-read dulu, baru redirect ke action_url --}}
    <script>
      document.querySelectorAll('.js-mark-then-go').forEach(link => {
        link.addEventListener('click', async (e) => {
          e.preventDefault();
          const markUrl = link.dataset.markUrl;
          try {
            await fetch(markUrl, {
              method: 'POST',
              headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
          } catch(e) {
            // gagal menandai dibaca bukan masalah fatal, tetap redirect
          }
          window.location.href = link.getAttribute('href');
        });
      });
    </script>
</x-sidebar-layout>
