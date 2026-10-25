<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['academic_class_id', 'name', 'code', 'total_marks', 'pass_marks'];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function studentMarks()
    {
        return $this->hasMany(StudentMark::class);
    }
}
