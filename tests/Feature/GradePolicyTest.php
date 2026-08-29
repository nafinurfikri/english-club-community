<?php

use App\Models\Grade;
use App\Models\GradeCategory;
use App\Models\User;
use App\Policies\GradePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function gradeAdmin(): User
{
    return User::factory()->create(['role' => 'admin', 'status' => 'active']);
}

function gradeStudent(): User
{
    return User::factory()->create(['role' => 'siswa', 'status' => 'active']);
}

function makeGrade(User $student, bool $published): Grade
{
    $category = GradeCategory::create(['name' => 'Speaking']);

    return Grade::create([
        'user_id' => $student->id,
        'grade_category_id' => $category->id,
        'score' => 85,
        'published_at' => $published ? now() : null,
    ]);
}

it('admin dapat melihat semua nilai', function () {
    $policy = new GradePolicy;
    $student = gradeStudent();
    $grade = makeGrade($student, true);

    expect($policy->view(gradeAdmin(), $grade))->toBeTrue();
});

it('siswa dapat melihat nilai miliknya yang sudah dipublikasikan', function () {
    $policy = new GradePolicy;
    $student = gradeStudent();
    $grade = makeGrade($student, true);

    expect($policy->view($student, $grade))->toBeTrue();
});

it('siswa tidak dapat melihat nilai miliknya yang belum dipublikasikan', function () {
    $policy = new GradePolicy;
    $student = gradeStudent();
    $grade = makeGrade($student, false);

    expect($policy->view($student, $grade))->toBeFalse();
});

it('siswa tidak dapat melihat nilai milik siswa lain', function () {
    $policy = new GradePolicy;
    $other = gradeStudent();
    $grade = makeGrade(gradeStudent(), true);

    expect($policy->view($other, $grade))->toBeFalse();
});
