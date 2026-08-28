@extends('layouts.app')

@section('title', 'Data Siswa - Admin English Club')

@section('content')

    <!-- Header Section -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
         x-data="{ showModal: false }">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Data Siswa</h1>
            <p class="text-xs sm:text-sm text-gray-500">Kelola akun, NIS, divisi, dan status keanggotaan siswa English Club.</p>
        </div>
        <button @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition shadow-sm flex items-center gap-2">
            <i class="bi bi-person-plus-fill"></i> Tambah Siswa Baru
        </button>

        <!-- Modal Tambah Siswa Baru (Alpine.js) -->
        <div x-show="showModal" 
             x-transition.opacity
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" 
             style="display: none;">
            <div @click.away="showModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Tambah Siswa Baru</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                </div>
                
                <form @submit.prevent="showModal = false" class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">NIS (Nomor Induk Siswa)</label>
                        <input type="text" placeholder="202410099" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" placeholder="Masukkan nama siswa" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Pilihan Divisi</label>
                        <select class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            <option>Speech</option>
                            <option>Story Telling</option>
                            <option>Debate</option>
                            <option>News Anchor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email Siswa</label>
                        <input type="email" placeholder="siswa@dwiguna.sch.id" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700">Simpan Siswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Data Table Siswa -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden"
         x-data="{
             students: [
                 { nis: '202410012', name: 'Kelvin Muaezin Lubis', divisi: 'Speech', email: 'kelvin@dwiguna.sch.id', status: 'Aktif' },
                 { nis: '202410015', name: 'Riekosta Febrianto', divisi: 'Debate', email: 'riekosta@dwiguna.sch.id', status: 'Aktif' },
                 { nis: '202410018', name: 'Nafi Nur Fikri', divisi: 'Story Telling', email: 'nafi@dwiguna.sch.id', status: 'Aktif' },
                 { nis: '202410022', name: 'Anindya Eka Pratiwi', divisi: 'News Anchor', email: 'anindya@dwiguna.sch.id', status: 'Aktif' }
             ]
         }">
        
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-gray-900">Daftar Anggota EC</span>
                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold" x-text="students.length + ' Siswa'"></span>
            </div>
            <input type="text" placeholder="Cari siswa..." class="w-full sm:w-64 px-3 py-1.5 bg-gray-50 border rounded-lg text-xs focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs text-gray-700 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">NIS</th>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Divisi</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(s, index) in students" :key="index">
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-4 py-3 font-mono text-xs text-gray-500" x-text="s.nis"></td>
                            <td class="px-4 py-3 font-semibold text-gray-900" x-text="s.name"></td>
                            <td class="px-4 py-3 text-xs text-gray-500" x-text="s.email"></td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-0.5 text-xs bg-blue-50 text-blue-600 rounded-md font-medium" x-text="s.divisi"></span>
                            </td>
                            <td class="px-4 py-3">
                                <span :class="s.status === 'Aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600'" 
                                      class="px-2.5 py-1 text-xs font-semibold rounded-full" x-text="s.status"></span>
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold"><i class="bi bi-pencil-square"></i> Edit</button>
                                <button @click="students.splice(index, 1)" class="text-rose-600 hover:text-rose-800 text-xs font-semibold"><i class="bi bi-trash"></i> Hapus</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

@endsection
