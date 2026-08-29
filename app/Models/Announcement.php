<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title', 'slug', 'body', 'type', 'event_at', 'published_at'];

    protected function casts(): array
    {
        return ['event_at' => 'datetime', 'published_at' => 'datetime'];
    }
}
