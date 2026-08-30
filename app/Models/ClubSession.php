<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubSession extends Model
{
    use HasFactory;

    public const OTP_LIFETIME_MINUTES = 1;

    protected $fillable = ['title', 'description', 'subject_id', 'scheduled_at', 'attendance_code_hash', 'attendance_code', 'attendance_code_expires_at', 'opened_at', 'closed_at'];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'attendance_code_expires_at' => 'datetime',
        ];
    }

    public function isOpen(): bool
    {
        return $this->opened_at !== null && $this->closed_at === null;
    }

    public function isAttendanceCodeExpired(): bool
    {
        return $this->attendance_code_expires_at !== null && $this->attendance_code_expires_at->isPast();
    }

    public function hasActiveAttendanceCode(): bool
    {
        return $this->attendance_code_hash !== null
            && $this->attendance_code !== null
            && ! $this->isAttendanceCodeExpired();
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
