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

it('only allows students who attended to access published materials', function () {
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
