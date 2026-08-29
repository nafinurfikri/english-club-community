<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\ClubSession;
use App\Models\GalleryItem;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalSessions = ClubSession::count();
        $attendanceCount = Attendance::where('user_id', $request->user()->id)->count();
        $sessions = ClubSession::whereNotNull('scheduled_at')->where('scheduled_at', '>=', now())->orderBy('scheduled_at')->take(3)->get();
        $grades = Grade::where('user_id', $request->user()->id)->whereNotNull('published_at');

        return $request->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : view('student.dashboard', [
                'attendanceCount' => $attendanceCount,
                'attendancePercentage' => $totalSessions > 0 ? round($attendanceCount / $totalSessions * 100, 1) : 0,
                'gradeCount' => $grades->count(),
                'gradeAverage' => round((float) ($grades->avg('score') ?? 0), 2),
                'sessions' => $sessions,
            ]);
    }

    public function admin()
    {
        return view('admin.dashboard', [
            'pendingStudents' => User::where('role', 'siswa')->where('status', 'pending')->get(),
            'totalStudents' => User::where('role', 'siswa')->where('status', 'active')->count(),
            'totalAnnouncements' => Announcement::whereNotNull('published_at')->count(),
            'totalGalleryItems' => GalleryItem::whereNotNull('published_at')->count(),
            'totalSessions' => ClubSession::count(),
        ]);
    }

    public function approveStudent(User $user)
    {
        abort_unless($user->role === 'siswa', 404);
        $user->update(['status' => 'active']);

        return back()->with('status', 'Akun siswa telah disetujui.');
    }
}
