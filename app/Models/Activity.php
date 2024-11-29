<?php

namespace App\Models;

use App\Observers\ActivityObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'updated_at',
        'start_date',
        'end_date',
        'description',
        'address',
        'status',
        'activity_name',
        'image_urls',
        'duration',
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(ActivityObserver::class);
    }

    //not sure this is the best way to do this

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'activity_attendances')
            ->withPivot(['status', 'admin_feedback', 'created_at'])
            ->withTimestamps();
    }

    //attendances also contains foreign userid whose fields i need to access
    //hasmay with the users columns
    public function attendances()
    {
        return $this->hasMany(ActivityAttendance::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
