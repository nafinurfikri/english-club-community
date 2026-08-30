<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClubSession;
use App\Models\Material;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminSessionController extends Controller
{
    public function index(Request $request)
    {
        $sessions = ClubSession::with(['materials', 'attendances.user', 'subject'])
            ->latest('scheduled_at')
            ->get();
        $selectedId = $request->query('session');
        $activeSession = $sessions
            ->first(fn (ClubSession $session) => $selectedId !== null && $session->id === (int) $selectedId && $session->isOpen())
            ?? $sessions->first(fn (ClubSession $session) => $session->isOpen());

        $students = User::where('role', 'siswa')->where('status', 'active')
            ->orderBy('name')->get();

        $hadirCount = $activeSession?->attendances->where('status', Attendance::STATUS_HADIR)->count() ?? 0;
        $izinCount = $activeSession?->attendances->where('status', Attendance::STATUS_IZIN)->count() ?? 0;
        $alphaCount = max(0, $students->count() - $hadirCount - $izinCount);

        $otpCode = $activeSession?->attendance_code
            ? Crypt::decryptString($activeSession->attendance_code)
            : null;

        return view('admin.attendance', [
            'sessions' => $sessions,
            'activeSession' => $activeSession,
            'openSessions' => $sessions->filter(fn (ClubSession $session) => $session->isOpen()),
            'subjects' => Subject::orderBy('name')->get(),
            'totalStudents' => $students->count(),
            'hadirCount' => $hadirCount,
            'izinCount' => $izinCount,
            'alphaCount' => $alphaCount,
            'otpCode' => $otpCode,
            'attendanceRows' => $this->buildAttendanceRows($activeSession, $students),
        ]);
    }

    public function history(Request $request)
    {
        $week = $request->query('week');
        if ($week !== null && ! preg_match('/^\d{4}-W\d{1,2}$/', (string) $week)) {
            $week = null;
        }

        $start = $week ? Carbon::parse($week.'-1')->startOfWeek() : now()->startOfWeek();
        $end = $start->copy()->endOfWeek();

        $sessions = ClubSession::query()
            ->with('subject')
            ->withCount([
                'attendances as hadir_count' => fn ($query) => $query->where('status', Attendance::STATUS_HADIR),
                'attendances as izin_count' => fn ($query) => $query->where('status', Attendance::STATUS_IZIN),
            ])
            ->whereBetween('scheduled_at', [$start, $end])
            ->orderBy('scheduled_at')
            ->get();

        $availableWeeks = ClubSession::query()
            ->whereNotNull('scheduled_at')
            ->get(['scheduled_at'])
            ->pluck('scheduled_at')
            ->map(fn (Carbon $monday) => $monday->copy()->startOfWeek())
            ->push(now()->startOfWeek())
            ->unique(fn (Carbon $monday) => $monday->format('o-\WW'))
            ->sortByDesc(fn (Carbon $monday) => $monday->getTimestamp())
            ->values()
            ->map(fn (Carbon $monday) => [
                'key' => $monday->format('o-\WW'),
                'label' => $monday->isoFormat('D MMM YYYY').' - '.$monday->copy()->endOfWeek()->isoFormat('D MMM YYYY'),
                'current' => $monday->isSameDay(now()->startOfWeek()),
            ]);

        return view('admin.attendance-history', [
            'sessions' => $sessions,
            'totalStudents' => User::where('role', 'siswa')->where('status', 'active')->count(),
            'weekLabel' => $start->isoFormat('D MMM YYYY').' - '.$end->isoFormat('D MMM YYYY'),
            'availableWeeks' => $availableWeeks,
            'currentWeek' => $start->format('o-\WW'),
        ]);
    }

    public function historySession(ClubSession $clubSession)
    {
        $clubSession->load(['subject', 'attendances.user']);

        $students = User::where('role', 'siswa')->where('status', 'active')
            ->orderBy('name')->get();

        $attendances = $clubSession->attendances;
        $hadirCount = $attendances->where('status', Attendance::STATUS_HADIR)->count();
        $izinCount = $attendances->where('status', Attendance::STATUS_IZIN)->count();

        return view('admin.attendance-history-session', [
            'session' => $clubSession,
            'rows' => $this->buildAttendanceRows($clubSession, $students),
            'totalStudents' => $students->count(),
            'hadirCount' => $hadirCount,
            'izinCount' => $izinCount,
            'alphaCount' => max(0, $students->count() - $hadirCount - $izinCount),
        ]);
    }

    /**
     * Build attendance rows for a session across all active students.
     *
     * @param  Collection<int, User>  $students
     * @return Collection<int, array{id: int, name: string, email: string, status: string|null, time: string}>
     */
    private function buildAttendanceRows(?ClubSession $clubSession, $students)
    {
        $attendances = $clubSession?->attendances?->keyBy('user_id') ?? collect();

        $labelMap = [
            Attendance::STATUS_HADIR => 'Hadir',
            Attendance::STATUS_IZIN => 'Izin',
            Attendance::STATUS_ALPHA => 'Alpha',
        ];

        return $students
            ->map(function (User $student) use ($attendances, $labelMap) {
                $attendance = $attendances->get($student->id);

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'status' => $attendance
                        ? $labelMap[$attendance->status] ?? null
                        : $labelMap[Attendance::STATUS_ALPHA],
                    'time' => $attendance?->attended_at ? $attendance->attended_at->format('H:i').' WIB' : '-',
                ];
            })
            ->values();
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
            'subject_id' => ['required', 'exists:subjects,id'],
            'description' => ['nullable', 'string'],
            'scheduled_at' => ['nullable', 'date'],
        ]);
        $code = (string) random_int(100000, 999999);
        $session = ClubSession::create([
            ...$data,
            'attendance_code' => Crypt::encryptString($code),
            'attendance_code_hash' => Hash::make($code),
            'attendance_code_expires_at' => now()->addMinutes(ClubSession::OTP_LIFETIME_MINUTES),
            'opened_at' => now(),
        ]);

        return back()->with('status', "Sesi berhasil dibuat dan presensi dibuka. Kode presensi: {$code}")
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
        $expiresAt = now()->addMinutes(ClubSession::OTP_LIFETIME_MINUTES);
        $clubSession->update([
            'attendance_code' => Crypt::encryptString($code),
            'attendance_code_hash' => Hash::make($code),
            'attendance_code_expires_at' => $expiresAt,
        ]);

        return back()->with('status', "Kode presensi baru: {$code}")
            ->with('attendance_code', $code);
    }

    public function revokeCode(ClubSession $clubSession)
    {
        $clubSession->update([
            'attendance_code' => null,
            'attendance_code_hash' => null,
            'attendance_code_expires_at' => null,
        ]);

        return back()->with('status', 'Kode OTP dibatalkan. Siswa tidak dapat presensi sampai kode baru dibuat.');
    }

    public function otp(ClubSession $clubSession): JsonResponse
    {
        if (! $clubSession->isOpen()) {
            return response()->json(['code' => null, 'expires_at' => null, 'is_open' => false]);
        }

        if ($clubSession->attendance_code_hash === null) {
            return response()->json(['code' => null, 'expires_at' => null, 'is_open' => true]);
        }

        if ($clubSession->isAttendanceCodeExpired() || $clubSession->attendance_code === null) {
            return $this->rotateCode($clubSession);
        }

        return response()->json([
            'code' => Crypt::decryptString($clubSession->attendance_code),
            'expires_at' => $clubSession->attendance_code_expires_at?->toIso8601String(),
            'is_open' => true,
        ]);
    }

    private function rotateCode(ClubSession $clubSession): JsonResponse
    {
        $code = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(ClubSession::OTP_LIFETIME_MINUTES);
        $clubSession->update([
            'attendance_code' => Crypt::encryptString($code),
            'attendance_code_hash' => Hash::make($code),
            'attendance_code_expires_at' => $expiresAt,
        ]);

        return response()->json([
            'code' => $code,
            'expires_at' => $expiresAt->toIso8601String(),
            'is_open' => true,
        ]);
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

    public function publishSessionMaterial(Material $material)
    {
        abort_if($material->club_session_id === null, 404);

        $material->update(['is_published' => true]);

        return back()->with('status', 'Materi dipublikasikan.');
    }

    public function destroySessionMaterial(Material $material)
    {
        abort_if($material->club_session_id === null, 404);

        $material->delete();

        return back()->with('status', 'Materi dihapus.');
    }
}
