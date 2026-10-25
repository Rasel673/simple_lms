<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'default_amount', 'description'];

    public function invoices()
    {
        return $this->hasMany(StudentFeeInvoice::class);
    }
}
