<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_id',
        'status',
        'admin_feedback',
    ];

    const STATUS_ATTENDED = 'ATTENDED';
    const STATUS_ACCEPTED = 'ACCEPTED';
    const STATUS_NOSHOW = 'NOSHOW';
    const STATUS_REJECTED = 'REJECTED';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_ATTENDED,
            self::STATUS_ACCEPTED,
            self::STATUS_NOSHOW,
            self::STATUS_REJECTED,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
