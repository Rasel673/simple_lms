<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_exam_id',
        'student_profile_id',
        'subject_id',
        'marks_obtained',
        'grade',
        'grade_point',
        'remarks',
    ];

    public function schoolExam()
    {
        return $this->belongsTo(SchoolExam::class);
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
