<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_number',
        'staff_profile_id',
        'month',
        'year',
        'basic_salary',
        'bonus',
        'deductions',
        'net_salary',
        'payment_date',
        'payment_method',
        'status',
        'notes',
    ];

    public function staff()
    {
        return $this->belongsTo(StaffProfile::class, 'staff_profile_id');
    }
}
