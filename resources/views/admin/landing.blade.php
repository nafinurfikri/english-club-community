@extends('layouts.app')

@section('title', 'Edit Landing Page - Admin English Club')

@section('content')

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Landing Page</h1>
            <p class="text-xs sm:text-sm text-gray-500">Kelola konten halaman beranda publik. Perubahan tersimpan sebagai draft sampai Anda publikasikan.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('status') }}</div>
    @endif

    @php
        $sections = [
            'hero' => ['label' => 'Hero Banner', 'fields' => ['title', 'subtitle']],
            'about' => ['label' => 'Tentang EC', 'fields' => ['title', 'body']],
            'cta' => ['label' => 'Ajakan Bergabung (CTA)', 'fields' => ['title', 'body']],
        ];
        $existing = collect($sectionsByKey ?? []);
    @endphp

    @foreach ($sections as $key => $config)
        @php
            $current = $existing->get($key);
            $draft = $current?->draft_content ?? [];
            $published = $current?->published_content ?? [];
        @endphp

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-gray-900">{{ $config['label'] }}</h2>
                    @if ($current)
                        <span class="text-xs text-gray-500">{{ $published ? 'Sudah dipublikasikan' : 'Belum pernah dipublikasikan' }}</span>
                    @else
                        <span class="text-xs text-gray-400">Belum ada data</span>
                    @endif
                </div>
                @if ($published)
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Published</span>
                @else
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Draft</span>
                @endif
            </div>

            <form method="POST" action="{{ route('admin.landing.update', $key) }}" class="space-y-4">
                @csrf
                @method('PUT')

                @foreach ($config['fields'] as $field)
                    @php
                        $fieldLabel = $field === 'subtitle' ? 'Subjudul' : ($field === 'body' ? 'Isi / Deskripsi' : 'Judul');
                        $value = $draft[$field] ?? '';
                    @endphp
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">{{ $fieldLabel }}</label>
                        @if ($field === 'body')
                            <textarea name="content[{{ $field }}]" rows="4" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">{{ $value }}</textarea>
                        @else
                            <input name="content[{{ $field }}]" type="text" value="{{ $value }}" maxlength="255" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        @endif
                    </div>
                @endforeach

                <div class="pt-2 flex flex-wrap items-center justify-between gap-3 border-t border-gray-100">
                    <label class="flex items-center gap-2 text-xs text-gray-600">
                        <input type="checkbox" name="publish" value="1"> Publikasikan sekarang
                    </label>
                    <div class="flex gap-2">
                        <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">
                            <i class="bi bi-eye"></i> Lihat Halaman
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700">
                            <i class="bi bi-save"></i> Simpan Draft
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endforeach

@endsection