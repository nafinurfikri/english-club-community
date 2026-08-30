<?php

use App\Models\Attendance;
use App\Models\ClubSession;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('allows an admin to upload a published material', function () {
    Storage::fake('local');
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    $session = ClubSession::create(['title' => 'Session Material']);

    $this->actingAs($admin)->post(route('admin.materials.store', $session), [
        'title' => 'Grammar Handout',
        'type' => 'file',
        'file' => UploadedFile::fake()->create('grammar.pdf', 10, 'application/pdf'),
        'published' => '1',
    ])->assertRedirect();

    expect(Material::where('title', 'Grammar Handout')->where('is_published', true)->exists())->toBeTrue();
});

it('allows an admin to add a material as a url link', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    $session = ClubSession::create(['title' => 'Session Material']);

    $this->actingAs($admin)->post(route('admin.materials.store', $session), [
        'title' => 'Video Belajar',
        'type' => 'url',
        'url' => 'https://example.com/video',
        'published' => '1',
    ])->assertRedirect();

    $material = Material::where('title', 'Video Belajar')->first();
    expect($material)->not->toBeNull()
        ->and($material->type)->toBe('url')
        ->and($material->url)->toBe('https://example.com/video')
        ->and($material->path)->toBeNull()
        ->and($material->is_published)->toBeTrue();
});

it('only allows students who attended (status hadir) to access published materials', function () {
    Storage::fake('local');
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $otherStudent = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $session = ClubSession::create(['title' => 'Session Material']);
    $path = UploadedFile::fake()->create('grammar.pdf', 10, 'application/pdf')->store('materials');
    $material = Material::create([
        'club_session_id' => $session->id,
        'title' => 'Grammar Handout',
        'type' => 'file',
        'path' => $path,
        'is_published' => true,
    ]);

    $this->actingAs($otherStudent)->get(route('student.materials.show', $material))->assertForbidden();

    Attendance::create(['club_session_id' => $session->id, 'user_id' => $student->id, 'attended_at' => now()]);
    $this->actingAs($student)->get(route('student.materials.show', $material))->assertOk();
});

it('halaman presensi admin memuat tombol materi per sesi tanpa form inline', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    $first = ClubSession::factory()->create(['title' => 'Sesi A', 'opened_at' => now(), 'closed_at' => null]);
    $second = ClubSession::factory()->create(['title' => 'Sesi B', 'opened_at' => null, 'closed_at' => null]);

    $html = $this->actingAs($admin)
        ->get(route('admin.attendance'))
        ->assertOk()
        ->getContent();

    expect($html)
        ->toContain('bi-folder2-open')
        ->toContain('Materi Sesi:')
        ->not->toContain('Simpan Materi')
        ->not->toContain('Publikasikan untuk student');
});

it('admin dapat mempublikasikan materi sesi', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    $session = ClubSession::factory()->create(['title' => 'Sesi']);
    $material = Material::create([
        'club_session_id' => $session->id,
        'title' => 'Materi Draft',
        'type' => 'url',
        'is_published' => false,
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.session-materials.publish', $material))
        ->assertRedirect();

    expect($material->fresh()->is_published)->toBeTrue();
});

it('admin dapat menghapus materi sesi', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    $session = ClubSession::factory()->create(['title' => 'Sesi']);
    $material = Material::create([
        'club_session_id' => $session->id,
        'title' => 'Materi Lama',
        'type' => 'url',
        'is_published' => true,
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.session-materials.destroy', $material))
        ->assertRedirect();

    expect(Material::find($material->id))->toBeNull();
});

it('materi mata pelajaran tidak dapat dikelola lewat endpoint materi sesi', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    $material = Material::create(['title' => 'Materi Mapel', 'type' => 'url', 'is_published' => true]);

    $this->actingAs($admin)
        ->patch(route('admin.session-materials.publish', $material))
        ->assertNotFound();

    $this->actingAs($admin)
        ->delete(route('admin.session-materials.destroy', $material))
        ->assertNotFound();
});

it('does not allow students with status izin or alpha to access session materials', function () {
    Storage::fake('local');
    $izinStudent = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $alphaStudent = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $session = ClubSession::create(['title' => 'Session Material']);
    $path = UploadedFile::fake()->create('grammar.pdf', 10, 'application/pdf')->store('materials');
    $material = Material::create([
        'club_session_id' => $session->id,
        'title' => 'Grammar Handout',
        'type' => 'file',
        'path' => $path,
        'is_published' => true,
    ]);

    Attendance::create(['club_session_id' => $session->id, 'user_id' => $izinStudent->id, 'attended_at' => now(), 'status' => 'izin']);
    Attendance::create(['club_session_id' => $session->id, 'user_id' => $alphaStudent->id, 'attended_at' => null, 'status' => 'alpha']);

    $this->actingAs($izinStudent)->get(route('student.materials.show', $material))->assertForbidden();
    $this->actingAs($alphaStudent)->get(route('student.materials.show', $material))->assertForbidden();
});
