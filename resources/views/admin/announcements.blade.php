@extends('layouts.app')

@section('title', 'Kelola Pengumuman - Admin English Club')

@section('content')

    <!-- Header Section -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
         x-data="{ showModal: false }">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Pengumuman (Announcement)</h1>
            <p class="text-xs sm:text-sm text-gray-500">Buat dan publikasikan pengumuman baru yang akan tampil di halaman Guest dan Siswa.</p>
        </div>
        <button @click="showModal = true" class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition shadow-sm flex items-center gap-2">
            <i class="bi bi-megaphone-fill"></i> Tambah Pengumuman Baru
        </button>

        <!-- Modal Tambah Pengumuman -->
        <div x-show="showModal" 
             x-transition.opacity
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" 
             style="display: none;">
            <div @click.away="showModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Tambah Pengumuman Baru</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                </div>
                
                <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Pengumuman</label>
                        <input name="title" type="text" required placeholder="Contoh: Pendaftaran Lomba Speech 2026" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori / Badge</label>
                            <select name="type" required class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                                <option value="agenda">Agenda</option>
                                <option value="jadwal">Jadwal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Status Publish</label>
                            <select name="published" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                                <option value="1">Published</option>
                                <option value="0">Draft</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Isi Pengumuman</label>
                        <textarea name="body" rows="4" required placeholder="Tuliskan detail pengumuman lengkap..." class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-amber-500"></textarea>
                    </div>

                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-amber-500 text-white text-xs font-semibold rounded-xl hover:bg-amber-600">Publish Pengumuman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Announcement Cards List -->
    <div class="space-y-4"
         x-data="{
             announcements: @js($announcements->values())
         }">
        
        <template x-for="(item, index) in announcements" :key="item.id">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:border-amber-300 transition">
                <div class="space-y-1.5 max-w-3xl">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 text-xs font-semibold bg-amber-100 text-amber-800 rounded-full" x-text="item.type"></span>
                        <span class="text-xs text-gray-400" x-text="item.event_at || item.created_at"></span>
                        <span :class="item.published_at ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600'"
                            class="px-2 py-0.5 text-[10px] font-bold rounded-full" x-text="item.published_at ? 'Published' : 'Draft'"></span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900" x-text="item.title"></h3>
                    <p class="text-xs text-gray-600 leading-relaxed" x-text="item.body"></p>
                </div>

                <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
                    <button class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <form method="POST" :action="'{{ url('/admin/announcements') }}/' + item.id" onsubmit="return confirm('Hapus pengumuman ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold transition"><i class="bi bi-trash"></i> Hapus</button>
                    </form>
                </div>
            </div>
        </template>
    </div>

@endsection
