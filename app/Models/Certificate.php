<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'certificate_number',
        'user_id',
        'course_id',
        'issue_date',
        'pdf_path',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getVerificationUrlAttribute(): string
    {
        return route('certificate.verify', ['number' => $this->certificate_number]);
    }
}
