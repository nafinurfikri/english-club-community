<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the student attendance page', function () {
    $this->actingAs(User::factory()->create(['status' => 'active']))
        ->get(route('student.attendance'))
        ->assertOk()
        ->assertSee('Presensi Kehadiran')
        ->assertSee('Daily Attendance')
        ->assertSee('Verify Attendance');
});
