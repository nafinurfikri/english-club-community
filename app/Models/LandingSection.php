<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSection extends Model
{
    protected $fillable = ['key', 'draft_content', 'published_content'];

    protected function casts(): array
    {
        return ['draft_content' => 'array', 'published_content' => 'array'];
    }
}
