<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    protected $fillable = [
        'student_id',
        'student_name',
        'violation_type_id',
        'violation_type_snapshot',
        'violation_remark_id',
        'violation_remark_snapshot',
        'classification',
        'count',
        'original_violation_type_id',
    ];

    #[Scope]
    protected function search($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('student_id', 'like', "%{$search}%")
                ->orWhere('student_name', 'like', "%{$search}%")
                ->orWhere('violation_type_snapshot', 'like', "%{$search}%")
                ->orWhere('violation_remark_snapshot', 'like', "%{$search}%")
                ->orWhere('classification', 'like', "%{$search}%");
        });
    }
}
