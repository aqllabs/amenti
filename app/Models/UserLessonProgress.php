<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserLessonProgress extends Pivot
{
    public $incrementing = true;
    public $timestamps = true;
    protected $table = 'user_lesson_progress';
    protected $fillable = [
        'user_id',
        'lesson_id',
        'course_id',
        'completed_at',
        'completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
