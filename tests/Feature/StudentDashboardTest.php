<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the student dashboard page', function () {
    $student = User::factory()->create(['status' => 'active']);
    $firstName = str($student->name)->explode(' ')->first();

    $this->actingAs($student)
        ->get(route('student.dashboard'))
        ->assertOk()
        ->assertSee('English Proficiency Excellence Map')
        ->assertSee("Welcome Back, {$firstName}!")
        ->assertSee($student->name);
});
