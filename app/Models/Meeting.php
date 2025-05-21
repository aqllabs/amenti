<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'start_date',
        'description',
        'address',
        'format',
    ];

    protected $casts = [
        'start_date' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'meeting_attendances')
            ->withPivot('status', 'admin_feedback')
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(MeetingAttendance::class)->with([
            'user' => function ($query) {
                $query->select('id', 'name', 'email');
            },
        ]);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the booking request that created this meeting.
     */
    public function bookingRequest()
    {
        return $this->hasOne(BookingRequest::class);
    }
}
