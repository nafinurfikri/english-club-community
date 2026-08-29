<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\LandingSection;
use Illuminate\Http\Request;

class PublicContentController extends Controller
{
    public function home()
    {
        return view('guest.home', [
            'sections' => LandingSection::query()->whereNotNull('published_content')->get()->keyBy('key'),
        ]);
    }

    public function announcements()
    {
        return view('guest.announcement', [
            'announcements' => Announcement::whereNotNull('published_at')->latest('event_at')->get(),
        ]);
    }

    public function gallery(Request $request)
    {
        $items = GalleryItem::with('category')
            ->whereNotNull('published_at')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        if ($request->filled('category')) {
            $items = $items->filter(fn (GalleryItem $item) => $item->category?->slug === $request->string('category')->toString());
        }

        return view('guest.gallery', [
            'items' => $items,
            'categories' => GalleryCategory::orderBy('name')->get(),
        ]);
    }
}
