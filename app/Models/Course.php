<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'thumbnail',
        'price',
        'discount_price',
        'duration',
        'passing_score',
        'learning_outcomes',
        'status',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'passing_score' => 'integer',
        'learning_outcomes' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order', 'asc');
    }

    public function publishedLessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->where('is_published', true)->orderBy('order', 'asc');
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function finalExam()
    {
        return $this->hasOne(Exam::class)->where('is_required', true)->latestOfMany();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_enrollments')
            ->withPivot('enrolled_at', 'completed_at', 'status')
            ->withTimestamps();
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->discount_price !== null && $this->discount_price >= 0 && $this->discount_price < $this->price) {
            return (float) $this->discount_price;
        }

        return (float) $this->price;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_price !== null && $this->discount_price < $this->price;
    }
}
