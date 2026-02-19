<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationStages extends Model
{
    protected $fillable = [
        'violation_id',
        'order',
        'name',
        'isComplete',
        'remark',
        'file_path',
        'completed_at',
    ];

    protected $casts = [
        'isComplete'   => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function isCompleteStep(): bool
    {
        return $this->name === 'Complete';
    }
}
