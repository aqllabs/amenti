<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    use HasFactory;

    protected $casts = [
        'responses' => 'array',
    ];

    protected $guarded = [];

    protected $table = 'form_submissions';

    public function form()
    {
        return $this->belongsTo(MyForm::class, 'form_id');
    }
}
