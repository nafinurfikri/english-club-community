<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubSession extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'scheduled_at', 'attendance_code_hash', 'opened_at', 'closed_at'];

    protected function casts(): array
    {
        return ['scheduled_at' => 'datetime', 'opened_at' => 'datetime', 'closed_at' => 'datetime'];
    }

    public function isOpen(): bool
    {
        return $this->opened_at !== null && $this->closed_at === null;
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
