<?php

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function subjectAdmin(): User
{
    return User::factory()->create(['role' => 'admin', 'status' => 'active']);
}

it('halaman mata pelajaran admin menampilkan daftar mata pelajaran', function () {
    $admin = subjectAdmin();
    Subject::factory()->create(['name' => 'Advanced Grammar']);

    $this->actingAs($admin)
        ->get(route('admin.subjects'))
        ->assertOk()
        ->assertSee('Advanced Grammar');
});

it('admin dapat menambah mata pelajaran', function () {
    $admin = subjectAdmin();

    $this->actingAs($admin)
        ->post(route('admin.subjects.store'), [
            'name' => 'Public Speaking',
            'level' => 'Level 4 - Adv',
            'teacher' => 'Prof. Arthur Pendelton',
            'description' => 'Developing rhetorical speaking capacities.',
            'published' => '1',
        ])
        ->assertRedirect();

    expect(Subject::where('name', 'Public Speaking')->first())
        ->is_published->toBeTrue();
});

it('admin dapat memperbarui mata pelajaran', function () {
    $admin = subjectAdmin();
    $subject = Subject::factory()->create(['name' => 'Old Name', 'is_published' => true]);

    $this->actingAs($admin)
        ->put(route('admin.subjects.update', $subject), [
            'name' => 'New Name',
            'level' => 'Level 1 - Beg',
            'teacher' => 'Mrs. Jane Doe',
            'description' => 'Updated description.',
        ])
        ->assertRedirect();

    expect($subject->fresh())->name->toBe('New Name');
});

it('admin dapat menghapus mata pelajaran', function () {
    $admin = subjectAdmin();
    $subject = Subject::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.subjects.destroy', $subject))
        ->assertRedirect();

    expect(Subject::find($subject->id))->toBeNull();
});

it('validasi nama mata pelajaran wajib diisi', function () {
    $admin = subjectAdmin();

    $this->actingAs($admin)
        ->post(route('admin.subjects.store'), [
            'level' => 'Level 1 - Beg',
            'teacher' => 'Mrs. Jane Doe',
            'description' => 'Deskripsi tanpa nama.',
        ])
        ->assertSessionHasErrors('name');
});

it('hanya admin yang dapat mengelola mata pelajaran', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->actingAs($student)
        ->get(route('admin.subjects'))
        ->assertForbidden();
});
