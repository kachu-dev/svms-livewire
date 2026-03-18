<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationStages extends Model
{
    protected $fillable = [
        'violation_id',
        'order',
        'name',
        'is_complete',
        'remark',
        'file_path',
        'completed_at',
        'status',
    ];

    protected $casts = [
        'is_complete' => 'boolean',
        'completed_at' => 'datetime',
    ];

}
