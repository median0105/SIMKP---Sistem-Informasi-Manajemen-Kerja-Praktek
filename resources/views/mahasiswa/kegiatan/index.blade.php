<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800">Kegiatan Saya</h2>
  </x-slot>

  <div class="py-8 max-w-5xl mx-auto space-y-6">
    @if($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    @if(session('error'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
      </div>
    @endif
    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif
    {{-- Form tambah kegiatan --}}
    <div class="bg-white rounded-lg shadow p-6">
      <form method="POST" action="{{ route('mahasiswa.kegiatan.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="text-sm text-gray-600">Tanggal</label>
            <input type="date" name="tanggal_kegiatan" required class="w-full border-gray-300 rounded-md">
          </div>
          <div>
            <label class="text-sm text-gray-600">Durasi (jam)</label>
            <input type="number" name="durasi_jam" min="1" required class="w-full border-gray-300 rounded-md">
          </div>
          <div>
            <label class="text-sm text-gray-600">Bukti</label>
            <input type="file" name="file_dokumentasi" required class="w-full border-gray-300 rounded-md">
          </div>
          <div class="md:col-span-3">
            <label class="text-sm text-gray-600">Deskripsi</label>
            <textarea name="deskripsi_kegiatan" rows="3" required class="w-full border-gray-300 rounded-md"></textarea>
          </div>
        </div>
        <div class="mt-4">
          <button class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">Simpan</button>
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
              <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
              <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
              <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bukti</th>
              <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @forelse($kegiatan as $k)
              <tr>
                <td class="px-3 py-2">{{ $k->tanggal_kegiatan->format('d M Y') }}</td>
                <td class="px-3 py-2">{{ $k->durasi_jam }} jam</td>
                <td class="px-3 py-2">
                  @if($k->file_dokumentasi)
                    <a href="{{ \Illuminate\Support\Facades\Storage::url($k->file_dokumentasi) }}" target="_blank">
                      <img src="{{ \Illuminate\Support\Facades\Storage::url($k->file_dokumentasi) }}" alt="Bukti" class="w-20 h-20 object-cover rounded cursor-pointer hover:opacity-75">
                    </a>
                  @else
                    Tidak ada bukti
                  @endif
                </td>
                <td class="px-3 py-2">{{ $k->deskripsi_kegiatan }}</td>
              </tr>
            @empty
              <tr><td colspan="4" class="px-3 py-6 text-center text-gray-500">Belum ada kegiatan.</td></tr>
            @endforelse
          </tbody>
        </table>
        <div class="mt-4">{{ $kegiatan->links() }}</div>
      </div>
    </div>
  </div>
</x-app-layout>
