<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasNoPersonalTeam;
use Exception;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Use this for Stripe
use Illuminate\Support\Collection;
use Laravel\Cashier\Billable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use Billable;
    use HasApiTokens;
    use HasFactory;
    use HasNoPersonalTeam, HasTeams {
        HasNoPersonalTeam::ownsTeam insteadof HasTeams;
        HasNoPersonalTeam::isCurrentTeam insteadof HasTeams;
    }
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
        'username',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relations
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Check if user can access panel,
        //        return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();

        if ($panel->getId() === 'admin') {
            return $this->email === 'mentorshiphk@gmail.com' || $this->user_type === 'admin';
        }

        return true;
    }

    public function trialIsUsed()
    {
        return $this->trial_is_used;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->belongsToTeam($tenant);
    }

    public function getTenants(Panel $panel): array|Collection
    {
        return $this->allTeams();
    }

    public function team()
    {
        // Get current tenant safely with null check
        $tenant = Filament::getTenant();

        // Return the relationship for the current team only
        return $this->belongsToMany(Team::class, 'team_user')
            ->where('team_id', $tenant?->id)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_attendances')
            ->withPivot('created_at', 'status', 'feedback', 'admin_feedback', 'rating')
            ->withTimestamps();
    }

    public function activityAttendances()
    {
        return $this->hasMany(ActivityAttendance::class);
    }

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'meeting_attendances')
            ->withPivot('status', 'admin_feedback')
            ->withTimestamps();
    }

    // user has many activity attendances
    // user has many meeting attendances
    // user hase many course enrollments

    public function mentors()
    {
        return $this->belongsToMany(User::class, 'mentorships', 'mentee_id', 'mentor_id')
            ->withTimestamps();
    }

    public function mentees()
    {
        return $this->belongsToMany(User::class, 'mentorships', 'mentor_id', 'mentee_id')
            ->withTimestamps();
    }

    public function submittedForms()
    {
        return $this->belongsToMany(
            MyForm::class,
            'form_submissions',
            'user_id',
            'form_id'
        )->withPivot('created_at')->withTimestamps();
    }

    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function quizSubmissions()
    {
        return $this->hasMany(QuizSubmission::class);
    }

    public function submittedQuizzes()
    {
        return $this->belongsToMany(
            Quiz::class,
            'quiz_submissions',
            'user_id',
            'quiz_id'
        )->withPivot('responses')->withTimestamps();
    }

    public function courseProgress()
    {
        return $this->belongsToMany(Lesson::class, 'user_lesson_progress', 'user_id', 'lesson_id')->withTimestamps();
    }

    public function enrollInCourse(int $courseId): bool
    {
        // Check if the course exists
        $course = Course::find($courseId);
        if (! $course) {
            return false;
        }

        // Check if the user is already enrolled
        if ($this->userEnrollments()->contains($courseId)) {
            return false;
        }

        // Enroll the user
        try {
            $this->userEnrollments()->attach($courseId, ['enrolled_at' => now()]);

            return true;
        } catch (Exception $e) {
            // Handle any errors (e.g., database errors)
            report($e);

            return false;
        }
    }

    public function userEnrollments()
    {
        return $this->belongsToMany(Course::class, 'user_course')
            ->withTimestamps();
    }

    public function finishLesson(Lesson $lesson): bool
    {
        // Check if the user is enrolled in the course
        if (! $this->userEnrollments()->where('course_id', $lesson->course_id)->exists()) {
            return false;
        }

        // Attach the lesson to the user's course progress
        $this->courseProgress()->syncWithoutDetaching([$lesson->id => ['completed_at' => now(), 'course_id' => $lesson->course_id]]);

        return true;
    }

    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'user_lesson_progress')
            ->using(UserLessonProgress::class)
            ->withPivot('course_id')->withTimestamps();
    }

    public function calculateCourseProgress(int $courseId): float
    {
        $totalLessons = Lesson::where('course_id', $courseId)->count();

        if ($totalLessons === 0) {
            return 0;
        }
        $completedLessons = $this->getCourseProgress($courseId);

        return ($completedLessons / $totalLessons) * 100;
    }

    public function getCourseProgress($courseId)
    {
        return $this->completedLessons()
            ->wherePivot('course_id', $courseId)
            ->count();
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    /**
     * Get booking requests sent by this user.
     */
    public function sentBookingRequests(): HasMany
    {
        return $this->hasMany(BookingRequest::class, 'requester_id');
    }

    /**
     * Get booking requests received by this user.
     */
    public function receivedBookingRequests(): HasMany
    {
        return $this->hasMany(BookingRequest::class, 'recipient_id');
    }
}
