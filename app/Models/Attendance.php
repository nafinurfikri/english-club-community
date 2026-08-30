<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public const STATUS_HADIR = 'hadir';

    public const STATUS_IZIN = 'izin';

    public const STATUS_ALPHA = 'alpha';

    public const STATUSES = [self::STATUS_HADIR, self::STATUS_IZIN, self::STATUS_ALPHA];

    protected $fillable = ['club_session_id', 'user_id', 'attended_at', 'status'];

    protected function casts(): array
    {
        return ['attended_at' => 'datetime'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clubSession()
    {
        return $this->belongsTo(ClubSession::class);
    }
}
