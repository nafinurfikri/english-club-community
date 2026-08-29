<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
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

    public function deleteGallery(GalleryItem $galleryItem)
    {
        Storage::disk('public')->delete($galleryItem->image_path);
        $galleryItem->delete();

        return back()->with('status', 'Foto gallery dihapus.');
    }
}
