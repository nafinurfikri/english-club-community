<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\LandingSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminContentController extends Controller
{
    public function announcements()
    {
        return view('admin.announcements', ['announcements' => Announcement::latest()->get()]);
    }

    public function gallery()
    {
        return view('admin.gallery', [
            'items' => GalleryItem::with('category')->orderBy('sort_order')->latest()->get(),
            'categories' => GalleryCategory::orderBy('name')->get(),
        ]);
    }

    public function announcement(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'type' => ['required', 'in:jadwal,agenda'],
            'event_at' => ['nullable', 'date'],
            'published' => ['sometimes', 'boolean'],
        ]);

        Announcement::create([
            ...$data,
            'slug' => Str::slug($data['title']).'-'.Str::lower(Str::random(5)),
            'published_at' => ! empty($data['published']) ? now() : null,
        ]);

        return back()->with('status', 'Pengumuman berhasil disimpan.');
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'type' => ['required', 'in:jadwal,agenda'],
            'event_at' => ['nullable', 'date'],
            'published' => ['sometimes', 'boolean'],
        ]);

        $announcement->update([
            ...$data,
            'published_at' => ! empty($data['published']) ? ($announcement->published_at ?? now()) : null,
        ]);

        return back()->with('status', 'Pengumuman berhasil diperbarui.');
    }

    public function deleteAnnouncement(Announcement $announcement)
    {
        $announcement->delete();

        return back()->with('status', 'Pengumuman berhasil dihapus.');
    }

    public function landingIndex()
    {
        return view('admin.landing', [
            'sectionsByKey' => LandingSection::orderBy('key')->get()->keyBy('key'),
        ]);
    }

    public function landing(Request $request, string $key)
    {
        $data = $request->validate([
            'content' => ['required', 'array'],
            'content.*' => ['nullable', 'string'],
            'publish' => ['sometimes', 'boolean'],
        ]);

        $section = LandingSection::firstOrCreate(['key' => $key]);
        $section->update([
            'draft_content' => $data['content'],
            'published_content' => ! empty($data['publish']) ? $data['content'] : $section->published_content,
        ]);

        return back()->with('status', 'Section landing berhasil disimpan.');
    }

    public function galleryItem(Request $request)
    {
        $data = $request->validate([
            'gallery_category_id' => ['required', 'exists:gallery_categories,id'],
            'caption' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:5120'],
            'published' => ['sometimes', 'boolean'],
        ]);

        GalleryItem::create([
            'gallery_category_id' => $data['gallery_category_id'],
            'caption' => $data['caption'] ?? null,
            'image_path' => $request->file('image')->store('gallery', 'public'),
            'published_at' => ! empty($data['published']) ? now() : null,
        ]);

        return back()->with('status', 'Foto gallery berhasil disimpan.');
    }

    public function updateGallery(Request $request, GalleryItem $galleryItem)
    {
        $data = $request->validate([
            'gallery_category_id' => ['required', 'exists:gallery_categories,id'],
            'caption' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:5120'],
            'published' => ['sometimes', 'boolean'],
        ]);

        $path = $galleryItem->image_path;

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($path);
            $path = $request->file('image')->store('gallery', 'public');
        }

        $galleryItem->update([
            ...$data,
            'image_path' => $path,
            'published_at' => ! empty($data['published']) ? ($galleryItem->published_at ?? now()) : null,
        ]);

        return back()->with('status', 'Foto gallery berhasil diperbarui.');
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:gallery_categories,name']]);
        GalleryCategory::create([...$data, 'slug' => Str::slug($data['name'])]);

        return back()->with('status', 'Kategori gallery berhasil dibuat.');
    }

    public function updateCategory(Request $request, GalleryCategory $galleryCategory)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:gallery_categories,name,'.$galleryCategory->id]]);
        $galleryCategory->update([...$data, 'slug' => Str::slug($data['name'])]);

        return back()->with('status', 'Kategori gallery berhasil diperbarui.');
    }

    public function deleteCategory(GalleryCategory $galleryCategory)
    {
        if ($galleryCategory->items()->exists()) {
            return back()->withErrors(['gallery_category' => 'Kategori yang masih memiliki foto tidak dapat dihapus.']);
        }

        $galleryCategory->delete();

        return back()->with('status', 'Kategori gallery berhasil dihapus.');
    }

    public function deleteGallery(GalleryItem $galleryItem)
    {
        Storage::disk('public')->delete($galleryItem->image_path);
        $galleryItem->delete();

        return back()->with('status', 'Foto gallery dihapus.');
    }
}
