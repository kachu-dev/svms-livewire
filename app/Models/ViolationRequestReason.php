<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;

class ViolationRequestReason extends Model
{
    protected $fillable = ['type', 'label'];

    #[Scope]
    protected function forDelete($q)
    {
        return $q->where('type', 'delete');
    }

    #[Scope]
    protected function forUpdate($q)
    {
        return $q->where('type', 'update');
    }
}
