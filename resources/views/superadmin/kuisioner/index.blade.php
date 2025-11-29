{{-- resources/views/superadmin/kuisioner/index.blade.php --}}
<x-sidebar-layout>

    {{-- ===================== HEADER ===================== --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm"></div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Rekap Kuisioner Kerja Praktek
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- ===================== BODY ===================== --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ================= FLASH MESSAGE ================= --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ======================= FILTER ======================= --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform hover:shadow-lg transition duration-300 animate-fade-in-up">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">

                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">Dari</label>
                        <input type="text" name="start_date" value="{{ request('start_date') }}"
                            class="datepicker w-full border-gray-300 rounded-lg shadow-sm focus:ring-unib-blue-500 focus:border-unib-blue-500 px-3 py-3 text-base">
                    </div>

                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">Sampai</label>
                        <input type="text" name="end_date" value="{{ request('end_date') }}"
                            class="datepicker w-full border-gray-300 rounded-lg shadow-sm focus:ring-unib-blue-500 focus:border-unib-blue-500 px-3 py-3 text-base">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-base font-medium text-gray-700 mb-2">Cari</label>
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Nama/NPM, judul KP, atau perusahaan…"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-unib-blue-500 focus:border-unib-blue-500 px-4 py-3 text-base">
                    </div>

                    <div class="flex gap-3">
                        <button class="flex-1 bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition">
                            Cari
                        </button>
                        <a href="{{ route('superadmin.kuisioner.index') }}"
                            class="flex-1 bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition">
                            Reset
                        </a>
                    </div>

                </form>
            </div>

            {{-- ======================= TABLE MAHASISWA ======================= --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">

                {{-- HEADER TABLE --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">Jawaban Kuisioner Mahasiswa</h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $items->total() }}
                    </div>
                </div>

                @if($items->count() == 0)
                    <div class="flex flex-col items-center justify-center py-10">
                        <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
                        <dotlottie-wc src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" style="width: 260px;height: 260px" autoplay loop></dotlottie-wc>
                        <p class="text-gray-500 text-lg mt-4">Belum ada kuisioner.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                <tr>
                                    <th class="th-col">Mahasiswa</th>
                                    <th class="th-col">Judul KP</th>
                                    <th class="th-col">Tempat</th>
                                    <th class="th-col">Rating</th>
                                    <th class="th-col">Rekomendasi</th>
                                    <th class="th-col">Tanggal Isi</th>
                                    <th class="th-col">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($items as $it)
                                    @php
                                        $kp  = $it->kerjaPraktek;
                                        $mhs = $kp?->mahasiswa;
                                        $tm  = $kp?->tempatMagang;
                                    @endphp

                                    <tr class="hover:bg-unib-blue-50 transition duration-150 ease-in-out group">
                                        <td class="td-col">
                                            <div class="font-semibold text-gray-900">{{ $mhs?->name ?? '-' }}</div>
                                            @if($mhs?->npm)
                                                <div class="text-xs text-gray-500">NPM: {{ $mhs->npm }}</div>
                                            @endif
                                        </td>

                                        <td class="td-col">{{ $kp?->judul_kp ?? '-' }}</td>

                                        <td class="td-col">
                                            {{ $kp?->pilihan_tempat == 3 ? ($kp->tempat_magang_sendiri ?? '-') : ($tm?->nama_perusahaan ?? '-') }}
                                        </td>

                                        <td class="td-col">
                                            <span class="
                                                px-3 py-1 rounded-lg text-xs font-semibold
                                                @class([
                                                    'bg-green-100 text-green-700'   => $it->rating_keseluruhan === 'Sangat Baik',
                                                    'bg-blue-100 text-blue-700'     => $it->rating_keseluruhan === 'Baik',
                                                    'bg-yellow-100 text-yellow-700' => $it->rating_keseluruhan === 'Cukup Baik',
                                                    'bg-red-100 text-red-700'       => $it->rating_keseluruhan === 'Kurang Baik',
                                                    'bg-gray-100 text-gray-700'     => !$it->rating_keseluruhan,
                                                ])
                                            ">
                                                {{ $it->rating_keseluruhan ?? 'N/A' }}
                                            </span>
                                        </td>

                                        <td class="td-col">
                                            @if($it->rekomendasi_tempat)
                                                <span class="badge-green">Ya</span>
                                            @else
                                                <span class="badge-red">Tidak</span>
                                            @endif
                                        </td>

                                        <td class="td-col">{{ $it->created_at->locale('id')->translatedFormat('d F Y') }}</td>

                                        <td class="td-col">
                                            <a href="{{ route('superadmin.kuisioner.show', $it) }}"
                                                class="text-unib-blue-600 hover:text-unib-blue-900 font-semibold">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>

            {{-- ======================= TABLE PENGAWAS ======================= --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 mt-6 transform transition hover:shadow-lg animate-fade-in-up">

                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">Jawaban Kuisioner Pengawas Lapangan</h3>
                </div>

                @php
                    $kuisionerPengawas = \App\Models\KuisionerPengawas::with(['pengawas','question'])
                        ->join('kuisioner_pengawas_questions','kuisioner_pengawas.kuisioner_pengawas_question_id','=','kuisioner_pengawas_questions.id')
                        ->where('kuisioner_pengawas_questions.is_active', true)
                        ->orderBy('kuisioner_pengawas_questions.order')
                        ->orderBy('kuisioner_pengawas.submitted_at', 'desc')
                        ->select('kuisioner_pengawas.*')
                        ->get();
                @endphp

                @if($kuisionerPengawas->count() == 0)
                    <div class="flex flex-col items-center justify-center py-10">
                        <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
                        <dotlottie-wc src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" style="width: 260px;height: 260px" autoplay loop></dotlottie-wc>
                        <p class="text-gray-500 text-lg mt-4">Belum ada jawaban pengawas.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                <tr>
                                    <th class="th-col">Pengawas</th>
                                    <th class="th-col">Pertanyaan</th>
                                    <th class="th-col">Jawaban</th>
                                    <th class="th-col">Tanggal</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($kuisionerPengawas as $r)
                                    <tr class="hover:bg-unib-blue-50 transition duration-150">
                                        <td class="td-col">
                                            <div class="font-semibold">{{ $r->pengawas->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $r->pengawas->email ?? '-' }}</div>
                                        </td>

                                        <td class="td-col">{{ $r->question->question_text ?? '-' }}</td>

                                        <td class="td-col">
                                            @if($r->question->type === 'rating')
                                                <span class="badge-blue">{{ $r->rating }}/5</span>
                                            @elseif($r->question->type === 'yes_no')
                                                @if($r->yes_no)
                                                    <span class="badge-green">Ya</span>
                                                @else
                                                    <span class="badge-red">Tidak</span>
                                                @endif
                                            @elseif($r->question->type === 'qualitative_rating')
                                                <span class="badge-purple">{{ $r->answer }}</span>
                                            @else
                                                {{ $r->answer ?? '-' }}
                                            @endif
                                        </td>

                                        <td class="td-col">
                                            {{ ($r->submitted_at ?? $r->created_at)->locale('id')->translatedFormat('d F Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ======================= SCRIPT ======================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('.datepicker', {
                dateFormat: 'Y-m-d',
                locale: 'id'
            });
        });
    </script>

    {{-- ======================= STYLING SHORTCUT ======================= --}}
    <style>
        .th-col { @apply px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap; }
        .td-col { @apply px-6 py-4 whitespace-nowrap text-base text-gray-700; }
        .badge-green { @apply px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700; }
        .badge-red { @apply px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700; }
        .badge-blue { @apply px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700; }
        .badge-purple { @apply px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700; }
    </style>

</x-sidebar-layout>
