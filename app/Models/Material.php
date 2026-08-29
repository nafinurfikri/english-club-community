<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['club_session_id', 'title', 'type', 'path', 'url', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function clubSession()
    {
        return $this->belongsTo(ClubSession::class);
    }
}
