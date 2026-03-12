<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    protected $connection = 'arturn_db';

    protected $table = 'profile';

    protected $primaryKey = 'studentid';

    protected $hidden = ['cmdate'];

    public function getProgram(): ?string
    {
        return DB::connection('collegedb')
            ->table('stud_program')
            ->join('prgram', 'stud_program.sp_pr_acronym', '=', 'prgram.pr_code')
            ->where('stud_program.sp_idnum', $this->studentid)
            ->orderBy('stud_program.ts', 'desc')
            ->value('prgram.pr_acronym');
    }

    public function getYear(): ?int
    {
        return DB::connection('collegedb')
            ->table('stud_program')
            ->where('sp_idnum', $this->studentid)
            ->latest('ts')
            ->value('sp_year');
    }

    public function getProgramAttribute(): ?string
    {
        return once(fn () => Cache::remember(
            "student:{$this->studentid}:program",
            now()->addHours(1),
            fn () => $this->getProgram()
        ));
    }

    public function getYearAttribute(): ?int
    {
        return once(fn () => Cache::remember(
            "student:{$this->studentid}:year",
            now()->addHours(1),
            fn () => $this->getYear()
        ));
    }
}
