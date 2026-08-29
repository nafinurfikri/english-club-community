<?php

use App\Models\Grade;
use App\Models\GradeCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function gradesPageAdmin(): User
{
    return User::factory()->create(['role' => 'admin', 'status' => 'active']);
}

it('admin dapat membuka halaman data nilai', function () {
    $student = User::factory()->create(['name' => 'Ahmad Nilai', 'role' => 'siswa', 'status' => 'active']);
    $category = GradeCategory::create(['name' => 'Speaking']);
    Grade::create([
        'user_id' => $student->id,
        'grade_category_id' => $category->id,
        'score' => 88.5,
        'published_at' => now(),
    ]);

    $this->actingAs(gradesPageAdmin())
        ->get(route('admin.grades'))
        ->assertOk()
        ->assertSee('Ahmad Nilai')
        ->assertSee('88.5');
});

it('admin dapat menyimpan nilai siswa', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $category = GradeCategory::create(['name' => 'Debate']);

    $this->actingAs(gradesPageAdmin())
        ->post(route('admin.grades.store'), [
            'user_id' => $student->id,
            'grade_category_id' => $category->id,
            'score' => 92,
            'notes' => 'Sangat baik',
            'published' => '1',
        ])
        ->assertRedirect();

    expect((float) Grade::where('user_id', $student->id)->first()->score)
        ->toBe(92.0)
        ->and(Grade::where('user_id', $student->id)->first()->published_at)
        ->not->toBeNull();
});

it('nilai wajib diisi score yang valid', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $category = GradeCategory::create(['name' => 'Speech']);

    $this->actingAs(gradesPageAdmin())
        ->post(route('admin.grades.store'), [
            'user_id' => $student->id,
            'grade_category_id' => $category->id,
            'score' => 150,
        ])
        ->assertSessionHasErrors('score');
});

it('hanya admin yang dapat membuka halaman data nilai', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->actingAs($student)
        ->get(route('admin.grades'))
        ->assertForbidden();
});
