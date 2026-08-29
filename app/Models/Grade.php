<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['user_id', 'grade_category_id', 'score', 'notes', 'published_at'];

    protected function casts(): array
    {
        return ['score' => 'decimal:2', 'published_at' => 'datetime'];
    }

    public function category()
    {
        return $this->belongsTo(GradeCategory::class, 'grade_category_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
