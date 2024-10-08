<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'content', 'order', 'duration', 'is_published', 'course_id', 'video_url',
    ];

    protected $appends = ['has_quiz'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lesson) {
            $lesson->setNextOrder();
        });

        static::deleted(function ($lesson) {
            static::reorderAfterDelete($lesson->course_id, $lesson->order);
        });
    }

    public function setNextOrder()
    {
        $maxOrder = $this->getMaxOrderForCourse();
        $this->order = $maxOrder + 1;
    }

    protected function getMaxOrderForCourse()
    {
        return static::where('course_id', $this->course_id)->max('order') ?? 0;
    }

    public static function reorderAfterDelete($courseId, $deletedOrder)
    {
        static::where('course_id', $courseId)
            ->where('order', '>', $deletedOrder)
            ->decrement('order');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    //add hasQuiz attribute that returns true if the lesson has a quiz

    public function getHasQuizAttribute()
    {
        return $this->quiz()->exists();
    }

    // hasQuiz attribute that returns true if the lesson has a quiz

    public function quiz(): HasOne
    {
//        can be null
        return $this->hasOne(Quiz::class)->withDefault();
    }
}
