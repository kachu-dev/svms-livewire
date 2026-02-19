<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationStageTemplate extends Model
{
    protected $fillable = ['offense_key', 'order', 'name'];
}
