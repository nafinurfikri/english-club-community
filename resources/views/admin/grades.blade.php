@extends('layouts.app')

@section('title', 'Data Nilai Siswa - Admin English Club')

@section('content')

    <!-- Header Section -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
         x-data="{ showModal: false }">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Data Nilai Siswa</h1>
            <p class="text-xs sm:text-sm text-gray-500">Input dan kelola penilaian kompetensi siswa berdasarkan divisi masing-masing.</p>
        </div>
        <button @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition shadow-sm flex items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i> Input Nilai Baru
        </button>

        <!-- Modal Input Nilai Baru -->
        <div x-show="showModal" 
             x-transition.opacity
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" 
             style="display: none;">
            <div @click.away="showModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Input Nilai Evaluasi</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                </div>
                
                <form method="POST" action="{{ route('admin.grades.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Siswa</label>
                        <select name="user_id" required class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Nilai</label>
                            <select name="grade_category_id" required class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Score</label>
                            <input name="score" type="number" min="0" max="100" step="0.01" placeholder="0-100" required class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Evaluasi Pembina</label>
                        <textarea name="notes" rows="3" placeholder="Catatan perkembangan siswa..." class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <label class="flex items-center gap-2 text-xs text-gray-600"><input name="published" type="checkbox" value="1"> Publikasikan ke student</label>

                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700">Simpan Nilai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Data Table Nilai -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden"
         x-data="{ grades: @js($grades->values()) }">
        
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <h2 class="text-base font-bold text-gray-900">Rekapitulasi Nilai Evaluasi Sesi</h2>
            <span class="text-xs text-gray-500">Semester Ganjil 2026/2027</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs text-gray-700 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">Nama Siswa & NIS</th>
                        <th class="px-4 py-3">Divisi</th>
                        <th class="px-4 py-3">Speaking</th>
                        <th class="px-4 py-3">Expression</th>
                        <th class="px-4 py-3">Predikat</th>
                        <th class="px-4 py-3">Catatan Pembina</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(g, index) in grades" :key="index">
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900 text-sm" x-text="g.student?.name"></p>
                                <span class="font-mono text-xs text-gray-400" x-text="g.student?.email"></span>
                            </td>
                            <td class="px-4 py-3 text-xs" x-text="g.category?.name"></td>
                            <td class="px-4 py-3 font-semibold text-blue-600" x-text="g.score"></td>
                            <td class="px-4 py-3 font-semibold text-emerald-600" x-text="g.published_at ? 'Published' : 'Draft'"></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-xs font-bold bg-blue-100 text-blue-700 rounded-md" x-text="g.score >= 90 ? 'A' : (g.score >= 80 ? 'B' : 'C')"></span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500 max-w-xs truncate" x-text="g.notes"></td>
                            <td class="px-4 py-3 text-right">
                                <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold"><i class="bi bi-pencil"></i> Edit</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

@endsection
