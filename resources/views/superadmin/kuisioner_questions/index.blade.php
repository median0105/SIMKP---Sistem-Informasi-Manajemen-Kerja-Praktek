<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Pertanyaan Kuisioner
            </h2>
            <a href="{{ route('superadmin.kuisioner_questions.create') }}" class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-md">
                Tambah Pertanyaan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-50 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Urutan</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pertanyaan</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aktif</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($questions as $question)
                            <tr>
                                <td class="px-4 py-2">{{ $question->order }}</td>
                                <td class="px-4 py-2">{{ $question->question_text }}</td>
                                <td class="px-4 py-2">{{ ucfirst($question->type) }}</td>
                                <td class="px-4 py-2">{{ $question->is_active ? 'Ya' : 'Tidak' }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('superadmin.kuisioner_questions.edit', $question) }}" class="text-unib-blue-600 hover:text-unib-blue-800 mr-2">Edit</a>
                                    <form action="{{ route('superadmin.kuisioner_questions.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pertanyaan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">Belum ada pertanyaan kuisioner.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
