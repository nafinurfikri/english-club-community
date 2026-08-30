@extends('layouts.app')

@section('title', 'Presensi - Student English Club')

@section('header')
    <header class="bg-white shadow-sm border-b border-gray-100 px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 leading-tight">Presensi Kehadiran</h1>
            <p class="text-xs sm:text-sm text-gray-500">Daily attendance verification via One-Time Password (OTP) authentication</p>
        </div>

        <div class="flex items-center gap-3 sm:gap-4">
            <button class="relative text-gray-500 hover:text-gray-700 transition">
                <i class="bi bi-bell text-xl"></i>
                <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
            </button>

            <span class="hidden sm:block w-px h-6 bg-gray-200"></span>

            <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full whitespace-nowrap">Active Academic</span>
        </div>
    </header>
@endsection

@section('content')

    @php($openSessions = $openSessions ?? collect())

    @foreach ($openSessions as $openSession)
        <form id="attendance-form-{{ $openSession->id }}" method="POST" action="{{ route('student.attendance.store', $openSession) }}" class="hidden">
            @csrf
            <input id="attendance-code-{{ $openSession->id }}" type="hidden" name="code">
        </form>
    @endforeach

    <div class="relative min-h-[65vh] flex flex-col items-center" x-data="attendance()" @paste="onPaste($event)">

        <!-- Background Gradient Blobs -->
        <div class="absolute top-0 right-0 w-[28rem] h-[28rem] bg-blue-200/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[26rem] h-[26rem] bg-blue-100/50 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 left-1/4 w-64 h-64 bg-indigo-100/40 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Page Title -->
        <div class="relative z-10 text-center mt-8 sm:mt-12 mb-10 px-4">
            <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 tracking-tight">Daily Attendance</h2>
            <p class="text-gray-500 mt-4 max-w-lg mx-auto text-sm sm:text-base">Enter the 6-digit code provided by your instructor to confirm your presence for today's session.</p>
        </div>

        <!-- Session Picker Cards -->
        @if ($openSessions->count() > 1)
            <div class="relative z-10 w-full max-w-md mb-6">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 text-center">Pilih Sesi Presensi</p>
                <div class="space-y-2.5">
                    <template x-for="(s, i) in sessions" :key="s.id">
                        <button type="button"
                                class="w-full text-left rounded-2xl border-2 bg-white/80 backdrop-blur px-4 py-3 transition"
                                :class="selectedId === s.id ? 'border-blue-600 ring-2 ring-blue-100' : 'border-gray-200 hover:border-blue-300'"
                                @click="selectedId = s.id">
                            <span class="flex items-center justify-between gap-2">
                                <span class="min-w-0">
                                    <span class="block text-sm font-bold text-gray-900 truncate" x-text="s.title"></span>
                                    <span class="block text-xs text-gray-500 mt-0.5" x-text="(s.subject ? s.subject + ' · ' : '') + s.time"></span>
                                </span>
                                <span class="shrink-0">
                                    <i class="bi bi-check-circle-fill text-blue-600 text-lg" x-show="selectedId === s.id"></i>
                                    <i class="bi bi-circle text-gray-300 text-lg" x-show="selectedId !== s.id"></i>
                                </span>
                            </span>
                        </button>
                    </template>
                </div>
            </div>
        @endif

        <!-- OTP Card -->
        <div class="relative z-10 bg-white/80 backdrop-blur rounded-2xl shadow-sm border border-gray-100 w-full max-w-md p-8 mb-12">
            <div class="flex justify-center mb-7">
                <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-2xl">
                    <i class="bi bi-fingerprint"></i>
                </div>
            </div>

            <!-- OTP Inputs -->
            <div class="flex justify-center gap-2 sm:gap-2.5 mb-7">
                <template x-for="(d, i) in digits" :key="i">
                    <input type="text" inputmode="numeric" maxlength="1"
                           :id="'otp-' + i"
                           x-model="digits[i]"
                           @input="onInput(i, $event)"
                           @keydown.backspace="onBackspace(i)"
                           class="w-11 sm:w-12 h-14 rounded-xl border-2 text-center text-xl font-bold text-gray-800 bg-gray-50 focus:bg-white focus:outline-none transition-colors duration-200"
                           :class="digits[i] !== '' ? 'border-blue-600' : 'border-gray-200/80 focus:border-blue-600'">
                </template>
            </div>

            <!-- Success Message -->
            <div x-show="verified" x-transition.opacity class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3 flex items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                <span>Attendance verified! Kehadiranmu hari ini tercatat.</span>
            </div>

            <!-- Error Message -->
            <p x-show="error" x-transition.opacity x-text="error" class="mb-4 text-xs text-rose-600 text-center"></p>

            <!-- Verify Button -->
            <button @click="verify"
                    :disabled="verified"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 disabled:cursor-not-allowed text-white font-semibold py-3.5 rounded-full text-sm transition-colors duration-200">
                Verify Attendance
            </button>

            <!-- Time Remaining -->
            <div class="mt-7 pt-5 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Time Remaining</span>
                <span class="text-2xl font-extrabold text-blue-600" :class="seconds <= 30 && '!text-rose-600'" x-text="timerText"></span>
            </div>
        </div>

        @foreach ($openSessions as $openSession)
            @php($studentAttendance = $openSession->attendances->first(fn ($a) => $a->user_id === auth()->id()))
            @if ($openSession->materials->isNotEmpty())
                <div class="relative z-10 mb-8 w-full max-w-md rounded-2xl border border-gray-200 bg-white p-5 shadow-sm" x-show="selectedId === {{ $openSession->id }}">
                    <h3 class="font-bold text-gray-900">Materi {{ $openSession->title }}</h3>
                    <div class="mt-3 space-y-2">
                        @foreach ($openSession->materials as $material)
                            @if ($material->is_published && $studentAttendance && $studentAttendance->status === \App\Models\Attendance::STATUS_HADIR)
                                <a href="{{ route('student.materials.show', $material) }}" class="flex items-center justify-between gap-3 rounded-xl bg-gray-50 px-3 py-2 text-sm text-blue-600 hover:bg-blue-50">
                                    <span class="truncate">{{ $material->title }}</span>
                                    <i class="bi bi-box-arrow-up-right shrink-0"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <script>
        function attendance() {
            return {
                digits: Array(6).fill(''),
                sessions: @js($openSessions->map(fn ($s) => [
                    'id' => $s->id,
                    'title' => $s->title,
                    'subject' => $s->subject?->name,
                    'time' => $s->scheduled_at?->format('d M Y · H:i') ?? 'Belum dijadwalkan',
                ])->values()),
                selectedId: null,
                error: '',
                verified: false,
                seconds: 298,
                timer: null,

                init() {
                    if (this.sessions.length > 0) this.selectedId = this.sessions[0].id;
                    this.timer = setInterval(() => {
                        if (this.seconds > 0) this.seconds--;
                        else clearInterval(this.timer);
                    }, 1000);
                    this.$nextTick(() => this.focusBox(0));
                },

                get timerText() {
                    const m = String(Math.floor(this.seconds / 60)).padStart(2, '0');
                    const s = String(this.seconds % 60).padStart(2, '0');
                    return m + ':' + s;
                },

                get code() {
                    return this.digits.join('');
                },

                focusBox(i) {
                    const el = document.getElementById('otp-' + i);
                    if (el) el.focus();
                },

                onInput(i, e) {
                    const val = e.target.value.replace(/[^0-9]/g, '').slice(-1);
                    e.target.value = val;
                    this.digits[i] = val;
                    this.error = '';
                    if (val && i < 5) this.focusBox(i + 1);
                },

                onBackspace(i) {
                    if (this.digits[i] === '' && i > 0) this.focusBox(i - 1);
                },

                onPaste(e) {
                    const text = (e.clipboardData.getData('text') || '').replace(/[^0-9]/g, '').slice(0, 6);
                    if (!text) return;
                    text.split('').forEach((ch, idx) => { this.digits[idx] = ch; });
                    this.focusBox(Math.min(text.length, 5));
                },

                verify() {
                    if (this.verified) return;
                    if (this.seconds <= 0) {
                        this.error = 'Waktu habis. Minta kode OTP baru kepada instruktor.';
                        return;
                    }
                    if (this.code.length < 6) {
                        this.error = 'Masukkan lengkap 6 digit kode OTP.';
                        return;
                    }
                    const session = this.sessions.find(s => s.id === this.selectedId);
                    if (!session) {
                        this.error = 'Belum ada sesi presensi yang sedang dibuka.';
                        return;
                    }
                    this.error = '';
                    const form = document.getElementById('attendance-form-' + session.id);
                    document.getElementById('attendance-code-' + session.id).value = this.code;
                    form.submit();
                }
            }
        }
    </script>

@endsection