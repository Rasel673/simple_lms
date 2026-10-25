<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'role_type', 'description'];

    public function staff()
    {
        return $this->hasMany(StaffProfile::class);
    }
}
