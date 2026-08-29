<?php

use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function galleryAdmin(): User
{
    return User::factory()->create(['role' => 'admin', 'status' => 'active']);
}

it('halaman galeri admin menampilkan kategori', function () {
    $admin = galleryAdmin();
    GalleryCategory::create(['name' => 'Events', 'slug' => 'events']);

    $this->actingAs($admin)
        ->get(route('admin.gallery'))
        ->assertOk()
        ->assertSee('Events');
});

it('admin dapat menambah kategori gallery', function () {
    $admin = galleryAdmin();

    $this->actingAs($admin)
        ->post(route('admin.gallery-categories.store'), ['name' => 'Competitions'])
        ->assertRedirect();

    expect(GalleryCategory::where('name', 'Competitions')->first())
        ->slug->toBe('competitions');
});

it('nama kategori gallery harus unik', function () {
    $admin = galleryAdmin();
    GalleryCategory::create(['name' => 'Events', 'slug' => 'events']);

    $this->actingAs($admin)
        ->post(route('admin.gallery-categories.store'), ['name' => 'Events'])
        ->assertSessionHasErrors('name');
});

it('admin dapat memperbarui kategori gallery', function () {
    $admin = galleryAdmin();
    $category = GalleryCategory::create(['name' => 'Events', 'slug' => 'events']);

    $this->actingAs($admin)
        ->put(route('admin.gallery-categories.update', $category), ['name' => 'Event & Kegiatan'])
        ->assertRedirect();

    expect($category->fresh())->slug->toBe('event-kegiatan');
});

it('kategori yang masih memiliki foto tidak dapat dihapus', function () {
    $admin = galleryAdmin();
    Storage::fake('public');
    $category = GalleryCategory::create(['name' => 'Events', 'slug' => 'events']);
    $item = GalleryItem::create([
        'gallery_category_id' => $category->id,
        'image_path' => UploadedFile::fake()->image('photo.jpg')->store('gallery', 'public'),
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.gallery-categories.destroy', $category))
        ->assertRedirect()
        ->assertSessionHasErrors('gallery_category');

    expect(GalleryCategory::find($category->id))->not->toBeNull();
});

it('admin dapat menghapus kategori yang tidak memiliki foto', function () {
    $admin = galleryAdmin();
    $category = GalleryCategory::create(['name' => 'Events', 'slug' => 'events']);

    $this->actingAs($admin)
        ->delete(route('admin.gallery-categories.destroy', $category))
        ->assertRedirect();

    expect(GalleryCategory::find($category->id))->toBeNull();
});

it('admin dapat memperbarui foto gallery', function () {
    $admin = galleryAdmin();
    Storage::fake('public');
    $oldCategory = GalleryCategory::create(['name' => 'Events', 'slug' => 'events']);
    $newCategory = GalleryCategory::create(['name' => 'Class', 'slug' => 'class']);
    $item = GalleryItem::create([
        'gallery_category_id' => $oldCategory->id,
        'image_path' => UploadedFile::fake()->image('old.jpg')->store('gallery', 'public'),
    ]);

    $this->actingAs($admin)
        ->put(route('admin.gallery.update', $item), [
            'gallery_category_id' => $newCategory->id,
            'caption' => 'Foto baru',
            'sort_order' => 3,
        ])
        ->assertRedirect();

    expect($item->fresh())
        ->gallery_category_id->toBe($newCategory->id)
        ->caption->toBe('Foto baru')
        ->sort_order->toBe(3);
});

it('hanya admin yang dapat mengelola kategori gallery', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->actingAs($student)
        ->post(route('admin.gallery-categories.store'), ['name' => 'Events'])
        ->assertForbidden();
});
