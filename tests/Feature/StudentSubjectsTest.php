<?php

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the student subjects page with published subjects', function () {
    Subject::factory()->create(['name' => 'Advanced Grammar']);
    Subject::factory()->create(['name' => 'TOEFL Preparation']);

    $this->actingAs(User::factory()->create(['status' => 'active']))
        ->get(route('student.subjects'))
        ->assertOk()
        ->assertSee('Kurikulum & Mata Pelajaran')
        ->assertSee('Advanced Grammar')
        ->assertSee('TOEFL Preparation');
});

it('does not show unpublished subjects to students', function () {
    Subject::factory()->create(['name' => 'Advanced Grammar']);
    Subject::factory()->create(['name' => 'Draft Subject', 'is_published' => false]);

    $this->actingAs(User::factory()->create(['status' => 'active']))
        ->get(route('student.subjects'))
        ->assertOk()
        ->assertSee('Advanced Grammar')
        ->assertDontSee('Draft Subject');
});
