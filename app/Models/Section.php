<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['academic_class_id', 'name', 'capacity'];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function students()
    {
        return $this->hasMany(StudentProfile::class);
    }
}
