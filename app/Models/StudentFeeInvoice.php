<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFeeInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'student_profile_id',
        'fee_type_id',
        'title',
        'amount',
        'paid_amount',
        'due_date',
        'status',
        'payment_date',
        'payment_method',
        'transaction_id',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }
}
