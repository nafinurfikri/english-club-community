<?php

it('renders the student attendance page', function () {
    $this->get(route('student.attendance'))
        ->assertOk()
        ->assertSee('Presensi Kehadiran')
        ->assertSee('Daily Attendance')
        ->assertSee('Verify Attendance');
});
