<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClubSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminSessionController extends Controller
{
    public function index()
    {
        $sessions = ClubSession::withCount('attendances')
            ->with(['materials', 'attendances.user'])
            ->latest('scheduled_at')
            ->get();
        $activeSession = $sessions->first(fn (ClubSession $session) => $session->isOpen());

        $students = User::where('role', 'siswa')->where('status', 'active')
            ->orderBy('name')->get();

        $activeAttendances = $activeSession
            ? $activeSession->attendances->keyBy('user_id')
            : collect();

        $hadirCount = $activeSession?->attendances->where('status', Attendance::STATUS_HADIR)->count() ?? 0;
        $izinCount = $activeSession?->attendances->where('status', Attendance::STATUS_IZIN)->count() ?? 0;
        $alphaCount = max(0, $students->count() - $hadirCount - $izinCount);

        $labelMap = [
            Attendance::STATUS_HADIR => 'Hadir',
            Attendance::STATUS_IZIN => 'Izin',
            Attendance::STATUS_ALPHA => 'Alpha',
        ];

        return view('admin.attendance', [
            'sessions' => $sessions,
            'activeSession' => $activeSession,
            'totalStudents' => $students->count(),
            'hadirCount' => $hadirCount,
            'izinCount' => $izinCount,
            'alphaCount' => $alphaCount,
            'attendanceRows' => $students->map(fn (User $student) => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'status' => isset($activeAttendances[$student->id])
                    ? $labelMap[$activeAttendances[$student->id]->status] ?? null
                    : null,
                'time' => $activeAttendances[$student->id]?->attended_at?->format('H:i').' WIB',
            ])->values(),
        ]);
    }

    public function updateStatus(Request $request)
    {
        $data = $request->validate([
            'club_session_id' => ['required', 'exists:club_sessions,id'],
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['required', Rule::in(Attendance::STATUSES)],
        ]);

        $attendance = Attendance::updateOrCreate(
            ['club_session_id' => $data['club_session_id'], 'user_id' => $data['user_id']],
            [
                'status' => $data['status'],
                'attended_at' => $data['status'] === Attendance::STATUS_HADIR
                    ? (Attendance::where('club_session_id', $data['club_session_id'])->where('user_id', $data['user_id'])->value('attended_at') ?? now())
                    : null,
            ]
        );

        return back()->with('status', "Status presensi {$attendance->user?->name} diperbarui.");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'scheduled_at' => ['nullable', 'date'],
        ]);
        $code = (string) random_int(100000, 999999);
        $session = ClubSession::create([...$data, 'attendance_code_hash' => Hash::make($code)]);

        return back()->with('status', "Sesi berhasil dibuat. Kode presensi: {$code}")
            ->with('attendance_code', $code)
            ->with('created_session_id', $session->id);
    }

    public function open(ClubSession $clubSession)
    {
        $clubSession->update(['opened_at' => now(), 'closed_at' => null]);

        return back()->with('status', 'Presensi sesi dibuka.');
    }

    public function close(ClubSession $clubSession)
    {
        $clubSession->update(['closed_at' => now()]);

        return back()->with('status', 'Presensi sesi ditutup.');
    }

    public function regenerateCode(ClubSession $clubSession)
    {
        $code = (string) random_int(100000, 999999);
        $clubSession->update(['attendance_code_hash' => Hash::make($code)]);

        return back()->with('status', "Kode presensi baru: {$code}")
            ->with('attendance_code', $code);
    }

    public function storeMaterial(Request $request, ClubSession $clubSession)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:file,url'],
            'file' => ['required_if:type,file', 'nullable', 'file', 'max:20480'],
            'url' => ['required_if:type,url', 'nullable', 'url', 'max:2048'],
            'published' => ['sometimes', 'boolean'],
        ]);

        $clubSession->materials()->create([
            'title' => $data['title'],
            'type' => $data['type'],
            'path' => $data['type'] === 'file' ? $request->file('file')->store('materials') : null,
            'url' => $data['type'] === 'url' ? $data['url'] : null,
            'is_published' => ! empty($data['published']),
        ]);

        return back()->with('status', 'Materi berhasil disimpan.');
    }
}
