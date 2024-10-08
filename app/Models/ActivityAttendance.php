<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'admin_feedback',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
