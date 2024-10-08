<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyForm extends Model
{
    use HasFactory;

    protected $casts = [
        'structure' => 'array',
    ];

    protected $guarded = [];

    protected $table = 'forms';



    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class, 'form_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
