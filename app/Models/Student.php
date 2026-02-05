<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $connection = 'school_db';

    protected $table = 'students';
}
