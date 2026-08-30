<?php

use App\Models\Attendance;
use App\Models\ClubSession;
use App\Models\Material;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function subjectMaterialAdmin(): User
{
    return User::factory()->create(['role' => 'admin', 'status' => 'active']);
}

it('mewajibkan memilih mata pelajaran saat membuat sesi', function () {
    $admin = subjectMaterialAdmin();
    $subject = Subject::create(['name' => 'Advanced Grammar', 'level' => 'Level 3 - Int', 'teacher' => 'Miss Windy', 'description' => 'Deskripsi']);

    $this->actingAs($admin)
        ->post(route('admin.sessions.store'), ['title' => 'Sesi 1'])
        ->assertSessionHasErrors('subject_id');

    $this->actingAs($admin)
        ->post(route('admin.sessions.store'), ['title' => 'Sesi 1', 'subject_id' => $subject->id])
        ->assertRedirect();

    expect(ClubSession::where('title', 'Sesi 1')->where('subject_id', $subject->id)->exists())->toBeTrue();
});

it('admin dapat mengupload materi langsung ke mata pelajaran', function () {
    Storage::fake('local');
    $admin = subjectMaterialAdmin();
    $subject = Subject::create(['name' => 'Advanced Grammar', 'level' => 'Level 3 - Int', 'teacher' => 'Miss Windy', 'description' => 'Deskripsi']);

    $this->actingAs($admin)
        ->post(route('admin.subjects.materials.store', $subject), [
            'title' => 'Modul Grammar',
            'type' => 'file',
            'file' => UploadedFile::fake()->create('grammar.pdf', 10, 'application/pdf'),
            'published' => '1',
        ])
        ->assertRedirect();

    $material = Material::where('title', 'Modul Grammar')->first();
    expect($material)->not->toBeNull()
        ->and($material->subject_id)->toBe($subject->id)
        ->and($material->club_session_id)->toBeNull()
        ->and($material->is_published)->toBeTrue();
});

it('siswa yang sudah hadir di sesi subject dapat mengakses materi subject', function () {
    $admin = subjectMaterialAdmin();
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $subject = Subject::create(['name' => 'Advanced Grammar', 'level' => 'Level 3 - Int', 'teacher' => 'Miss Windy', 'description' => 'Deskripsi']);
    $session = ClubSession::create(['title' => 'Sesi 1', 'subject_id' => $subject->id]);
    $material = Material::create([
        'subject_id' => $subject->id,
        'title' => 'Materi Subject',
        'type' => 'url',
        'url' => 'https://example.com/subject',
        'is_published' => true,
    ]);

    $this->actingAs($student)->get(route('student.materials.show', $material))->assertForbidden();

    Attendance::create(['club_session_id' => $session->id, 'user_id' => $student->id, 'attended_at' => now(), 'status' => 'hadir']);
    $this->actingAs($student)->get(route('student.materials.show', $material))->assertRedirect();
});

it('siswa yang hanya hadir di subject lain tidak dapat mengakses materi subject ini', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $subjectA = Subject::create(['name' => 'Grammar', 'level' => 'Level 3 - Int', 'teacher' => 'A', 'description' => 'D']);
    $subjectB = Subject::create(['name' => 'Speaking', 'level' => 'Level 3 - Int', 'teacher' => 'B', 'description' => 'D']);
    $sessionA = ClubSession::create(['title' => 'Sesi Grammar', 'subject_id' => $subjectA->id]);
    $materialB = Material::create([
        'subject_id' => $subjectB->id,
        'title' => 'Materi Speaking',
        'type' => 'url',
        'url' => 'https://example.com/speaking',
        'is_published' => true,
    ]);

    Attendance::create(['club_session_id' => $sessionA->id, 'user_id' => $student->id, 'attended_at' => now(), 'status' => 'hadir']);

    $this->actingAs($student)->get(route('student.materials.show', $materialB))->assertForbidden();
});

it('halaman detail subject menampilkan materi dan terkunci jika belum hadir', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $subject = Subject::create(['name' => 'Advanced Grammar', 'level' => 'Level 3 - Int', 'teacher' => 'Miss Windy', 'description' => 'Deskripsi materi']);
    $material = Material::create([
        'subject_id' => $subject->id,
        'title' => 'Materi Tertutup',
        'type' => 'url',
        'url' => 'https://example.com/subject',
        'is_published' => true,
    ]);

    $this->actingAs($student)
        ->get(route('student.subjects.show', $subject))
        ->assertSuccessful()
        ->assertSee('Materi terkunci')
        ->assertSee('Materi Tertutup')
        ->assertSee('Terkunci');
});

it('halaman detail subject menampilkan materi sesi dan mandiri bagi siswa yang hadir', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $subject = Subject::create(['name' => 'Advanced Grammar', 'level' => 'Level 3 - Int', 'teacher' => 'Miss Windy', 'description' => 'Deskripsi materi']);
    $session = ClubSession::create(['title' => 'Sesi Grammar', 'subject_id' => $subject->id]);

    Material::create(['subject_id' => $subject->id, 'title' => 'Materi Mandiri', 'type' => 'url', 'url' => 'https://example.com/a', 'is_published' => true]);
    Material::create(['club_session_id' => $session->id, 'title' => 'Materi Sesi', 'type' => 'url', 'url' => 'https://example.com/b', 'is_published' => true]);

    Attendance::create(['club_session_id' => $session->id, 'user_id' => $student->id, 'attended_at' => now(), 'status' => 'hadir']);

    $this->actingAs($student)
        ->get(route('student.subjects.show', $subject))
        ->assertSuccessful()
        ->assertSee('Materi Mandiri')
        ->assertSee('Materi Sesi')
        ->assertDontSee('Materi terkunci');
});
