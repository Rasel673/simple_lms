<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'video_url',
        'duration',
        'order',
        'is_preview',
        'is_published',
        'description',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_preview' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function isCompletedBy($userId): bool
    {
        if (!$userId) {
            return false;
        }

        return $this->progress()->where('user_id', $userId)->where('is_completed', true)->exists();
    }

    public function getEmbedUrlAttribute(): ?string
    {
        if (empty($this->video_url)) {
            return null;
        }

        $url = trim($this->video_url);

        // YouTube matches (standard, short, embed)
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $match)) {
            return 'https://www.youtube-nocookie.com/embed/' . $match[1] . '?rel=0&modestbranding=1';
        }

        // Vimeo matches
        if (preg_match('/(?:vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+))/i', $url, $match)) {
            $vimeoId = end($match);
            return 'https://player.vimeo.com/video/' . $vimeoId;
        }

        // Direct video link (e.g., mp4, webm) or already an embed url
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return null;
    }
}
