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
                
                <form @submit.prevent="showModal = false" class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Siswa</label>
                        <select class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            <option>Kelvin Muaezin Lubis (Speech)</option>
                            <option>Riekosta Febrianto (Debate)</option>
                            <option>Nafi Nur Fikri (Story Telling)</option>
                            <option>Anindya Eka Pratiwi (News Anchor)</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nilai Speaking / Speech</label>
                            <input type="number" placeholder="0-100" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nilai Expression</label>
                            <input type="number" placeholder="0-100" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Evaluasi Pembina</label>
                        <textarea rows="3" placeholder="Catatan perkembangan siswa..." class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

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
         x-data="{
             grades: [
                 { nis: '202410012', name: 'Kelvin Muaezin Lubis', divisi: 'Speech', speaking: 90, expression: 92, grade: 'A', note: 'Penguasaan materi sangat baik dan intonasi jelas' },
                 { nis: '202410015', name: 'Riekosta Febrianto', divisi: 'Debate', speaking: 85, expression: 88, grade: 'A-', note: 'Argumen logis, penyampaian sangat persuasif' },
                 { nis: '202410018', name: 'Nafi Nur Fikri', divisi: 'Story Telling', speaking: 95, expression: 96, grade: 'A+', note: 'Sangat ekspresif dan menguasai panggung' },
                 { nis: '202410022', name: 'Anindya Eka Pratiwi', divisi: 'News Anchor', speaking: 88, expression: 90, grade: 'A', note: 'Artikulasi jernih dan gaya pembawaan profesional' }
             ]
         }">
        
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
                                <p class="font-semibold text-gray-900 text-sm" x-text="g.name"></p>
                                <span class="font-mono text-xs text-gray-400" x-text="g.nis"></span>
                            </td>
                            <td class="px-4 py-3 text-xs" x-text="g.divisi"></td>
                            <td class="px-4 py-3 font-semibold text-blue-600" x-text="g.speaking"></td>
                            <td class="px-4 py-3 font-semibold text-emerald-600" x-text="g.expression"></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-xs font-bold bg-blue-100 text-blue-700 rounded-md" x-text="g.grade"></span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500 max-w-xs truncate" x-text="g.note"></td>
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
