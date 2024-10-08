<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSubmission extends Model
{
    use HasFactory;

    protected $casts = [
        'responses' => 'array'
    ];

    protected  $guarded = [];

    public function quiz()
    {

        return $this->belongsTo(Quiz::class, 'quiz_id');

    }

}
