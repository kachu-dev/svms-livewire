<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    protected $fillable = [
        'student_id',
        'student_name',
        'type',
        'remarks',
        'violation_type_id',
        'violation_type_snapshot',
        'violation_remark_id',
        'violation_remark_snapshot',
        'classification',
        'count',
        'original_violation_type_id',
    ];
}
