<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'course_id',
        'lesson_id',
        'title',
        'description',
        'passing_percentage',
        'duration_minutes',
        'max_attempts',
        'is_required',
        'is_published',
    ];

    protected $casts = [
        'passing_percentage' => 'integer',
        'duration_minutes' => 'integer',
        'max_attempts' => 'integer',
        'is_required' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order', 'asc');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function attemptsForUser($userId): HasMany
    {
        return $this->attempts()->where('user_id', $userId);
    }

    public function hasPassed($userId): bool
    {
        return $this->attemptsForUser($userId)->where('is_passed', true)->exists();
    }

    public function canAttempt($userId): bool
    {
        if ($this->max_attempts <= 0) {
            return true; // unlimited
        }

        $count = $this->attemptsForUser($userId)->count();
        return $count < $this->max_attempts;
    }
}
