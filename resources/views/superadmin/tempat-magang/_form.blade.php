{{-- resources/views/superadmin/tempat-magang/_form.blade.php --}}
@props([
    'action' => '#',
    'method' => 'POST',
    'submitText' => 'Simpan',
    'tempatMagang' => null,
])

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if(in_array($method, ['PUT','PATCH','DELETE']))
        @method($method)
    @endif

    {{-- Flash / Error --}}
    @if (session('success'))
        <div class="p-3 rounded bg-green-50 text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="p-3 rounded bg-red-50 text-red-700">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="p-3 rounded bg-red-50 text-red-700">
            <div class="font-semibold mb-1">Periksa input Anda:</div>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan *</label>
            <input type="text" name="nama_perusahaan"
                   value="{{ old('nama_perusahaan', $tempatMagang->nama_perusahaan ?? '') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Bidang Usaha *</label>
            <input type="text" name="bidang_usaha"
                   value="{{ old('bidang_usaha', $tempatMagang->bidang_usaha ?? '') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kontak Person *</label>
            <input type="text" name="kontak_person"
                   value="{{ old('kontak_person', $tempatMagang->kontak_person ?? '') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Perusahaan *</label>
            <input type="email" name="email_perusahaan"
                   value="{{ old('email_perusahaan', $tempatMagang->email_perusahaan ?? '') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Telepon Perusahaan *</label>
            <input type="text" name="telepon_perusahaan"
                   value="{{ old('telepon_perusahaan', $tempatMagang->telepon_perusahaan ?? '') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kuota Mahasiswa *</label>
            <input type="number" name="kuota_mahasiswa" min="1" max="50"
                   value="{{ old('kuota_mahasiswa', $tempatMagang->kuota_mahasiswa ?? 1) }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500" required>
            <p class="text-xs text-gray-500 mt-1">Batas 1–50 (bisa disesuaikan kebutuhan kebijakan prodi).</p>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat *</label>
        <textarea name="alamat" rows="3"
                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                  required>{{ old('alamat', $tempatMagang->alamat ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <textarea name="deskripsi" rows="4"
                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
        >{{ old('deskripsi', $tempatMagang->deskripsi ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Pengawas Lapangan</label>
        @php
            $currentPengawas = $tempatMagang ? $tempatMagang->pengawas->first() : null;
        @endphp
        @if($currentPengawas)
            <div class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 text-gray-700">
                {{ $currentPengawas->name }} ({{ $currentPengawas->email }})
            </div>
            <input type="hidden" name="pengawas_id" value="{{ $currentPengawas->id }}">
            <p class="text-xs text-gray-500 mt-1">Pengawas lapangan sudah terikat dengan tempat magang ini dan tidak dapat diubah.</p>
        @else
            <select name="pengawas_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                <option value="">-- Pilih Pengawas Lapangan --</option>
                @php
                    $pengawasQuery = \App\Models\User::where('role', 'pengawas_lapangan')->where('is_active', true);
                    if ($tempatMagang) {
                        // Edit: tampilkan yang belum ditugaskan atau yang sudah ditugaskan ke tempat ini
                        $pengawasQuery->where(function($q) use ($tempatMagang) {
                            $q->whereNull('tempat_magang_id')
                              ->orWhere('tempat_magang_id', $tempatMagang->id);
                        });
                    } else {
                        // Create: hanya yang belum ditugaskan
                        $pengawasQuery->whereNull('tempat_magang_id');
                    }
                    $pengawasList = $pengawasQuery->get();
                @endphp
                @foreach($pengawasList as $pengawas)
                    <option value="{{ $pengawas->id }}" {{ old('pengawas_id', $currentPengawas ? $currentPengawas->id : '') == $pengawas->id ? 'selected' : '' }}>
                        {{ $pengawas->name }} ({{ $pengawas->email }})
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Opsional: Pilih pengawas lapangan yang bertanggung jawab untuk tempat magang ini.</p>
        @endif
    </div>

    <div class="flex items-center gap-3">
        <input type="checkbox" id="is_active" name="is_active" value="1"
               {{ old('is_active', $tempatMagang->is_active ?? true) ? 'checked' : '' }}
               class="rounded border-gray-300 text-unib-blue-600 focus:ring-unib-blue-500">
        <label for="is_active" class="text-sm text-gray-700">Aktifkan tempat magang ini</label>
    </div>

    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('superadmin.tempat-magang.index') }}"
           class="px-4 py-2 rounded-md bg-gray-500 hover:bg-gray-600 text-white">Batal</a>
        <button type="submit"
                class="px-6 py-2 rounded-md bg-unib-blue-600 hover:bg-unib-blue-700 text-white font-medium">
            {{ $submitText }}
        </button>
    </div>
</form>
