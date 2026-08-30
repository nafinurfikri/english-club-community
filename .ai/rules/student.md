---
paths:
  - 'resources/views/student/**'
---

# Student

## OTP countdown must come from server expires_at, not hardcode
Kode OTP absensi berlaku ClubSession::OTP_LIFETIME_MINUTES (1 menit). Jangan pernah menampilkan countdown hardcode di view. Kirimkan attendance_code_expires_at (ISO) tiap sesi dari controller (AttendanceController::index pass $otpExpiresAt map id=>iso|nul) dan hitung sisa waktu real-time dari expires_at. Tampilkan 'Tidak ada kode aktif' bila null/expired.
