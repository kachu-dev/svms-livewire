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
        'violated_at',
    ];

    protected $casts = [
        'violated_at' => 'datetime',
    ];
}
