<?php

namespace App\Http\Controllers;

use App\Models\ClubSession;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $sessions = ClubSession::with(['attendances', 'materials', 'subject'])
            ->latest('scheduled_at')
            ->get();

        return view('student.attendance', [
            'sessions' => $sessions,
            'openSessions' => $sessions->filter(fn (ClubSession $session) => $session->isOpen()),
        ]);
    }

    public function store(Request $request, ClubSession $clubSession, AttendanceService $attendanceService)
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:20']]);
        $attendanceService->checkIn($request->user(), $clubSession, $data['code']);

        return back()->with('status', 'Presensi berhasil dicatat.');
    }
}
