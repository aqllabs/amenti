<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quiz extends Model
{
    use HasFactory;

    protected $casts = [
        'structure' => 'array',
    ];

    protected $fillable = [
        'title',
        'description',
        'is_published',
        'lesson_id',
        'structure',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function quizSubmission()
    {
        return $this->hasMany(QuizSubmission::class, 'quiz_id');
    }
}
