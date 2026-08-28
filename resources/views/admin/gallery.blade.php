@extends('layouts.app')

@section('title', 'Kelola Galeri - Admin English Club')

@section('content')

    <!-- Header Section -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
         x-data="{ showModal: false }">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Galeri Foto</h1>
            <p class="text-xs sm:text-sm text-gray-500">Unggah dan kelola dokumentasi foto kegiatan klub untuk ditampilkan ke pengunjung web.</p>
        </div>
        <button @click="showModal = true" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition shadow-sm flex items-center gap-2">
            <i class="bi bi-cloud-upload-fill"></i> Upload Foto Baru
        </button>

        <!-- Modal Upload Foto Galeri -->
        <div x-show="showModal" 
             x-transition.opacity
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" 
             style="display: none;">
            <div @click.away="showModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Upload Foto Galeri</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                </div>
                
                <form @submit.prevent="showModal = false" class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Judul / Keterangan Foto</label>
                        <input type="text" placeholder="Contoh: Juara 1 Story Telling Regional" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Album</label>
                        <select class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                            <option>Kegiatan Rutin</option>
                            <option>Kompetisi & Lomba</option>
                            <option>Workshop & Event</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih File Foto</label>
                        <input type="file" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>

                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700">Simpan Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"
         x-data="{
             photos: [
                 { id: 1, title: 'Latihan Rutin Speech', category: 'Kegiatan Rutin', date: '28 Aug 2026', img: '{{ asset('images/dokumentasi-1.jpg') }}' },
                 { id: 2, title: 'Debate Sparring Match', category: 'Kegiatan Rutin', date: '21 Aug 2026', img: '{{ asset('images/dokumentasi-2.jpg') }}' },
                 { id: 3, title: 'Juara Speech Contest', category: 'Kompetisi & Lomba', date: '14 Aug 2026', img: '{{ asset('images/dokumentasi-3.jpg') }}' },
                 { id: 4, title: 'Workshop Native Speaker', category: 'Workshop & Event', date: '07 Aug 2026', img: '{{ asset('images/dokumentasi-4.jpg') }}' }
             ]
         }">
        
        <template x-for="(p, index) in photos" :key="p.id">
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div class="relative group">
                    <img :src="p.img" :alt="p.title" class="w-full h-40 object-cover" onerror="this.src='https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=500&auto=format&fit=crop&q=60'">
                    <span class="absolute top-2 left-2 px-2 py-0.5 text-[10px] font-semibold bg-black/60 text-white rounded-md backdrop-blur-sm" x-text="p.category"></span>
                </div>
                <div class="p-3">
                    <h4 class="font-bold text-gray-900 text-sm truncate" x-text="p.title"></h4>
                    <p class="text-xs text-gray-400 mt-0.5" x-text="p.date"></p>
                    
                    <div class="mt-3 pt-2 border-t border-gray-100 flex justify-end gap-2">
                        <button @click="photos.splice(index, 1)" class="text-rose-600 hover:text-rose-800 text-xs font-semibold flex items-center gap-1">
                            <i class="bi bi-trash"></i> Hapus Foto
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

@endsection
