<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViolationRemark extends Model
{
    protected $fillable = [
        'violation_type_id',
        'label',
    ];

    public function violationType(): BelongsTo
    {
        return $this->belongsTo(ViolationType::class);
    }
}
