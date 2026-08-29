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
                
                <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Judul / Keterangan Foto</label>
                        <input name="caption" type="text" placeholder="Contoh: Juara 1 Story Telling Regional" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Album</label>
                        <select name="gallery_category_id" required class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih File Foto</label>
                        <input name="image" type="file" required accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>
                    <label class="flex items-center gap-2 text-xs text-gray-600"><input name="published" type="checkbox" value="1" checked> Publikasikan ke pengunjung</label>

                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700">Simpan Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Manajemen Kategori Album -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4 mb-6"
         x-data="{ showCatCreate: false, showCatEdit: false, editCat: {} }">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-3">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Kategori Album</h2>
                <p class="text-xs text-gray-500">Kelola kategori untuk mengelompokkan foto.</p>
            </div>
            <button @click="showCatCreate = true" class="bg-purple-50 hover:bg-purple-100 text-purple-700 font-semibold px-3 py-2 rounded-xl text-xs transition flex items-center gap-2">
                <i class="bi bi-plus-circle-fill"></i> Tambah Kategori
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach ($categories as $category)
                <div class="flex items-center justify-between gap-2 border border-gray-200 rounded-xl px-3 py-2">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $category->name }}</p>
                        <span class="text-xs text-gray-400">{{ $category->items()->count() }} foto</span>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        <button @click="editCat = { id: {{ $category->id }}, name: '{{ $category->name }}' }; showCatEdit = true"
                                class="text-blue-600 hover:text-blue-800 text-xs font-semibold"><i class="bi bi-pencil"></i></button>
                        <form method="POST" action="{{ route('admin.gallery-categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-rose-600 hover:text-rose-800 text-xs font-semibold"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal Tambah Kategori -->
        <div x-show="showCatCreate"
             x-transition.opacity
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             style="display: none;">
            <div @click.away="showCatCreate = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Tambah Kategori Album</h3>
                    <button @click="showCatCreate = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                </div>
                <form method="POST" action="{{ route('admin.gallery-categories.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori</label>
                        <input name="name" type="text" required placeholder="Contoh: Events" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="showCatCreate = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Kategori -->
        <div x-show="showCatEdit"
             x-transition.opacity
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             style="display: none;">
            <div @click.away="showCatEdit = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Edit Kategori Album</h3>
                    <button @click="showCatEdit = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                </div>
                <form :action="'{{ url('/admin/gallery-categories') }}/' + editCat.id" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori</label>
                        <input name="name" type="text" required x-model="editCat.name" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="showCatEdit = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"
         x-data="{
             photos: @js($items->values()),
             showEdit: false,
             editing: {}
         }">
        
        <template x-for="(p, index) in photos" :key="p.id">
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div class="relative group">
                    <img :src="'{{ asset('storage') }}/' + p.image_path" :alt="p.caption || 'Gallery image'" class="w-full h-40 object-cover">
                    <span class="absolute top-2 left-2 px-2 py-0.5 text-[10px] font-semibold bg-black/60 text-white rounded-md backdrop-blur-sm" x-text="p.category?.name"></span>
                </div>
                <div class="p-3">
                    <h4 class="font-bold text-gray-900 text-sm truncate" x-text="p.caption || 'Dokumentasi kegiatan'"></h4>
                    <p class="text-xs text-gray-400 mt-0.5" x-text="p.published_at || p.created_at"></p>
                    
                    <div class="mt-3 pt-2 border-t border-gray-100 flex justify-end gap-2">
                        <button @click="editing = { ...p, sort_order: p.sort_order ?? 0 }; showEdit = true"
                                class="text-blue-600 hover:text-blue-800 text-xs font-semibold flex items-center gap-1"><i class="bi bi-pencil"></i> Edit</button>
                        <form method="POST" :action="'{{ url('/admin/gallery') }}/' + p.id" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-rose-600 hover:text-rose-800 text-xs font-semibold flex items-center gap-1"><i class="bi bi-trash"></i> Hapus Foto</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <!-- Modal Edit Foto Galeri -->
        <div x-show="showEdit"
             x-transition.opacity
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             style="display: none;">
            <div @click.away="showEdit = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Edit Foto Galeri</h3>
                    <button @click="showEdit = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                </div>

                <form :action="'{{ url('/admin/gallery') }}/' + editing.id" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Judul / Keterangan Foto</label>
                        <input name="caption" type="text" x-model="editing.caption" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Album</label>
                        <select name="gallery_category_id" required x-model="editing.gallery_category_id" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Urutan Tampil</label>
                        <input name="sort_order" type="number" min="0" x-model="editing.sort_order" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Ganti File Foto (opsional)</label>
                        <input name="image" type="file" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>
                    <label class="flex items-center gap-2 text-xs text-gray-600"><input name="published" type="checkbox" value="1" :checked="editing.published_at"> Publikasikan ke pengunjung</label>

                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="showEdit = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
