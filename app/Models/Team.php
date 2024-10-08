<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }

    //courses
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    //mentorships
    public function mentorships()
    {
        return $this->hasMany(Mentorship::class);
    }

    //myforms
    public function myForms()
    {
        return $this->hasMany(MyForm::class);
    }

    //activities
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
}
