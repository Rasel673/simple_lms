<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'session_year',
        'start_date',
        'end_date',
        'is_published',
    ];

    public function marks()
    {
        return $this->hasMany(StudentMark::class);
    }
}
