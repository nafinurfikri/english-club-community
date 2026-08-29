<?php

namespace App\Http\Controllers;

use App\Models\ClubSession;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('student.attendance', [
            'sessions' => ClubSession::with('attendances')->latest('scheduled_at')->get(),
        ]);
    }

    public function store(Request $request, ClubSession $clubSession, AttendanceService $attendanceService)
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:20']]);
        $attendanceService->checkIn($request->user(), $clubSession, $data['code']);

        return back()->with('status', 'Presensi berhasil dicatat.');
    }
}
