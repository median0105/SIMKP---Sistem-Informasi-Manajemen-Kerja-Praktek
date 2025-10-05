<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800">Kegiatan Mahasiswa</h2>
  </x-slot>

  <div class="py-8 max-w-7xl mx-auto space-y-6">
    {{-- Filter --}}
    <div class="bg-white rounded-lg shadow p-6">
      <form method="GET" action="{{ route('pengawas.kegiatan.index') }}" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-64">
          <label class="text-sm text-gray-600">Cari Mahasiswa</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama mahasiswa..." class="w-full border-gray-300 rounded-md">
        </div>
        {{-- <div class="flex-1 min-w-64">
          <label class="text-sm text-gray-600">Tanggal Mulai</label>
          <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full border-gray-300 rounded-md">
        </div>
        <div class="flex-1 min-w-64">
          <label class="text-sm text-gray-600">Tanggal Akhir</label>
          <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="w-full border-gray-300 rounded-md">
        </div> --}}
        <div class="flex items-end">
          <button class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Cari</button>
          <a href="{{ route('pengawas.kegiatan.index') }}" class="ml-3 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">Reset</a>
        </div>
      </form>
    </div>

    {{-- Tabel kegiatan --}}
    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 border-b">
        <h3 class="font-semibold">Daftar Kegiatan</h3>
      </div>
      <div class="p-6 overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mahasiswa</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dokumentasi</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @forelse($kegiatan as $k)
              <tr>
                <td class="px-4 py-2 text-sm text-gray-900">
                  {{ $k->mahasiswa->name }}<br>
                  <span class="text-xs text-gray-500">{{ $k->mahasiswa->npm }}</span>
                </td>
                <td class="px-4 py-2 text-sm text-gray-900">
                  {{ \Illuminate\Support\Carbon::parse($k->tanggal_kegiatan)->locale('id')->translatedFormat('d F Y') }}
                </td>
                <td class="px-4 py-2 text-sm text-gray-900">{{ $k->durasi_jam }} jam</td>
                <td class="px-4 py-2 text-sm text-gray-900">
                  {{ \Illuminate\Support\Str::limit($k->deskripsi_kegiatan, 50) }}
                </td>
                <td class="px-4 py-2 text-sm text-gray-900">
                  @if($k->file_dokumentasi)
                    <a href="{{ \Illuminate\Support\Facades\Storage::url($k->file_dokumentasi) }}" target="_blank" >
                      <img src="{{ \Illuminate\Support\Facades\Storage::url($k->file_dokumentasi) }}" alt="Bukti" class="w-16 h-16 object-cover rounded cursor-pointer hover:opacity-75">
                    </a>
                  @else
                    Tidak ada
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada kegiatan tercatat.</td></tr>
            @endforelse
          </tbody>
        </table>
        <div class="mt-4">{{ $kegiatan->links() }}</div>
      </div>
    </div>
  </div>
</x-app-layout>
