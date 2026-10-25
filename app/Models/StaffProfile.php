<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'name',
        'designation_id',
        'phone',
        'email',
        'qualification',
        'joining_date',
        'monthly_salary',
        'status',
        'avatar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    public function attendances()
    {
        return $this->hasMany(AttendanceRecord::class, 'staff_profile_id');
    }

    public function salaryPayments()
    {
        return $this->hasMany(StaffSalaryPayment::class);
    }
}
