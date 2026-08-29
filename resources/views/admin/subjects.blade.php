@extends('layouts.app')

@section('title', 'Data Mata Pelajaran - Admin English Club')

@section('content')

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
         x-data="{ showCreate: false, showEdit: false, editing: {} }">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Mata Pelajaran</h1>
            <p class="text-xs sm:text-sm text-gray-500">Kelola kurikulum, level, dan pengajar program English Club.</p>
        </div>
        <button @click="showCreate = true" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition shadow-sm flex items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i> Tambah Mata Pelajaran
        </button>

        @if (session('status'))
            <div class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('status') }}</div>
        @endif

        <!-- Modal Tambah Mata Pelajaran -->
        <div x-show="showCreate"
             x-transition.opacity
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             style="display: none;">
            <div @click.away="showCreate = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Tambah Mata Pelajaran</h3>
                    <button @click="showCreate = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                </div>

                <form method="POST" action="{{ route('admin.subjects.store') }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Mata Pelajaran</label>
                        <input name="name" type="text" required placeholder="Contoh: Advanced Grammar" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Level</label>
                            <select name="level" required class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="Level 1 - Beg">Level 1 - Beg</option>
                                <option value="Level 2 - Basic">Level 2 - Basic</option>
                                <option value="Level 3 - Int">Level 3 - Int</option>
                                <option value="Level 4 - Adv">Level 4 - Adv</option>
                                <option value="Professional Class">Professional Class</option>
                                <option value="Special Prep">Special Prep</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Pengajar</label>
                            <input name="teacher" type="text" required placeholder="Nama pengajar" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" required placeholder="Deskripsi modul pembelajaran..." class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Gambar (opsional)</label>
                        <input name="image" type="file" accept="image/*" class="w-full px-3 py-2 border rounded-xl text-sm">
                    </div>
                    <label class="flex items-center gap-2 text-xs text-gray-600"><input name="published" type="checkbox" value="1" checked> Publikasikan ke student</label>

                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="showCreate = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Mata Pelajaran -->
        <div x-show="showEdit"
             x-transition.opacity
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             style="display: none;">
            <div @click.away="showEdit = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Edit Mata Pelajaran</h3>
                    <button @click="showEdit = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                </div>

                <form :action="'{{ url('/admin/subjects') }}/' + editing.id" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Mata Pelajaran</label>
                        <input name="name" type="text" required x-model="editing.name" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Level</label>
                            <select name="level" required x-model="editing.level" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="Level 1 - Beg">Level 1 - Beg</option>
                                <option value="Level 2 - Basic">Level 2 - Basic</option>
                                <option value="Level 3 - Int">Level 3 - Int</option>
                                <option value="Level 4 - Adv">Level 4 - Adv</option>
                                <option value="Professional Class">Professional Class</option>
                                <option value="Special Prep">Special Prep</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Pengajar</label>
                            <input name="teacher" type="text" required x-model="editing.teacher" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" required x-model="editing.description" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Gambar (opsional)</label>
                        <input name="image" type="file" accept="image/*" class="w-full px-3 py-2 border rounded-xl text-sm">
                    </div>
                    <label class="flex items-center gap-2 text-xs text-gray-600"><input name="published" type="checkbox" value="1" :checked="editing.is_published"> Publikasikan ke student</label>

                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="showEdit = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Data Table Mata Pelajaran -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden"
         x-data="{ subjects: @js($subjects->values()) }">

        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-gray-900">Daftar Mata Pelajaran EC</span>
                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold" x-text="subjects.length + ' Mata Pelajaran'"></span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs text-gray-700 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">Mata Pelajaran</th>
                        <th class="px-4 py-3">Level</th>
                        <th class="px-4 py-3">Pengajar</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="s in subjects" :key="s.id">
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900 text-sm" x-text="s.name"></p>
                                <span class="text-xs text-gray-400 max-w-xs truncate block" x-text="s.description"></span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-0.5 text-xs bg-blue-50 text-blue-600 rounded-md font-medium" x-text="s.level"></span>
                            </td>
                            <td class="px-4 py-3 text-xs" x-text="s.teacher"></td>
                            <td class="px-4 py-3">
                                <span :class="s.is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600'"
                                      class="px-2.5 py-1 text-xs font-semibold rounded-full" x-text="s.is_published ? 'Published' : 'Draft'"></span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="editing = s; showEdit = true"
                                            class="text-blue-600 hover:text-blue-800 text-xs font-semibold"><i class="bi bi-pencil"></i> Edit</button>
                                    <form method="POST" :action="'{{ url('/admin/subjects') }}/' + s.id" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-rose-600 hover:text-rose-800 text-xs font-semibold"><i class="bi bi-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

@endsection