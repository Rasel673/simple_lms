<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_name',
        'min_mark',
        'max_mark',
        'grade_point',
        'remarks',
    ];

    public static function getGradeForMark(float $mark): array
    {
        $rule = static::where('min_mark', '<=', $mark)
            ->where('max_mark', '>=', $mark)
            ->first();

        if ($rule) {
            return [
                'grade' => $rule->grade_name,
                'point' => (float)$rule->grade_point,
                'remarks' => $rule->remarks,
            ];
        }

        return ['grade' => 'F', 'point' => 0.00, 'remarks' => 'Failed'];
    }
}
