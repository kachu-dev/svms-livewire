<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ViolationType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'classification',
        'deactivated',
    ];

    const CLASSIFICATION_MINOR = 'Minor';

    const CLASSIFICATION_SUSPENSION = 'Major - Suspension';

    const CLASSIFICATION_DISMISSAL = 'Major - Dismissal';

    const CLASSIFICATION_EXPULSION = 'Major - Expulsion';

    public function remarks(): HasMany
    {
        return $this->hasMany(ViolationRemark::class);
    }

    #[Scope]
    protected function search($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%");
        });
    }
}
