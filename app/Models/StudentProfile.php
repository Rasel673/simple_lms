<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admission_number',
        'name',
        'academic_class_id',
        'section_id',
        'roll_number',
        'group',
        'session_year',
        'dob',
        'gender',
        'blood_group',
        'guardian_name',
        'guardian_phone',
        'address',
        'avatar',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function marks()
    {
        return $this->hasMany(StudentMark::class);
    }

    public function feeInvoices()
    {
        return $this->hasMany(StudentFeeInvoice::class);
    }

    public function attendances()
    {
        return $this->hasMany(AttendanceRecord::class, 'student_profile_id');
    }
}
