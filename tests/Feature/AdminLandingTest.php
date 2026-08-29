<?php

use App\Models\LandingSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function landingAdmin(): User
{
    return User::factory()->create(['role' => 'admin', 'status' => 'active']);
}

it('halaman editor landing admin menampilkan section', function () {
    $admin = landingAdmin();
    LandingSection::create(['key' => 'hero', 'draft_content' => ['title' => 'Judul Hero']]);

    $this->actingAs($admin)
        ->get(route('admin.landing'))
        ->assertOk()
        ->assertSee('Hero Banner')
        ->assertSee('Judul Hero');
});

it('admin dapat menyimpan draft section landing', function () {
    $admin = landingAdmin();

    $this->actingAs($admin)
        ->put(route('admin.landing.update', 'hero'), [
            'content' => ['title' => 'Judul Baru', 'subtitle' => 'Subjudul baru'],
        ])
        ->assertRedirect();

    $section = LandingSection::where('key', 'hero')->first();
    expect($section)->not->toBeNull()
        ->and($section->draft_content)->toBe(['title' => 'Judul Baru', 'subtitle' => 'Subjudul baru'])
        ->and($section->published_content)->toBeNull();
});

it('admin dapat mempublikasikan section landing', function () {
    $admin = landingAdmin();

    $this->actingAs($admin)
        ->put(route('admin.landing.update', 'hero'), [
            'content' => ['title' => 'Judul Publik', 'subtitle' => 'Subjudul publik'],
            'publish' => '1',
        ])
        ->assertRedirect();

    expect(LandingSection::where('key', 'hero')->first()->published_content)
        ->toBe(['title' => 'Judul Publik', 'subtitle' => 'Subjudul publik']);
});

it('halaman beranda menampilkan konten section yang dipublikasikan', function () {
    LandingSection::create([
        'key' => 'hero',
        'draft_content' => ['title' => 'Judul Draft', 'subtitle' => 'Tidak tampil'],
        'published_content' => ['title' => 'Judul Tampil', 'subtitle' => 'Subjudul tampil'],
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Judul Tampil')
        ->assertDontSee('Judul Draft');
});

it('admin dapat memperbarui section yang sudah ada', function () {
    $admin = landingAdmin();
    LandingSection::create(['key' => 'about', 'draft_content' => ['title' => 'Lama', 'body' => 'Konten lama'], 'published_content' => ['title' => 'Lama', 'body' => 'Konten lama']]);

    $this->actingAs($admin)
        ->put(route('admin.landing.update', 'about'), [
            'content' => ['title' => 'Baru', 'body' => 'Konten baru'],
            'publish' => '1',
        ])
        ->assertRedirect();

    expect(LandingSection::where('key', 'about')->first()->published_content)
        ->toBe(['title' => 'Baru', 'body' => 'Konten baru']);
});

it('hanya admin yang dapat mengedit landing page', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->actingAs($student)
        ->get(route('admin.landing'))
        ->assertForbidden();
});
