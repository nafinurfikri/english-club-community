<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the student dashboard page', function () {
    $this->actingAs(User::factory()->create(['status' => 'active']))
        ->get(route('student.dashboard'))
        ->assertOk()
        ->assertSee('English Proficiency Excellence Map')
        ->assertSee('Welcome Back, Budi!')
        ->assertSee('Budi Santoso');
});
