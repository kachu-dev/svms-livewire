<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationUpdateRequest extends Model
{
    protected $fillable = [
        'violation_id',
        'requested_by',
        'reviewed_by',
        'new_remark',
        'reason',
        'status',
        'denial_reason',
        'reviewed_at',
    ];

    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
