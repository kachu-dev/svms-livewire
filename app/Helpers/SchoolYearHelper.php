<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class SchoolYearHelper
{
    public static function current(): string
    {
        return cache()->remember('setting_school_year', 60, function () {
            return DB::table('settings')
                ->where('key', 'school_year')
                ->value('value') ?? self::fromDate(); // fallback to date logic
        });
    }

    public static function fromDate(): string
    {
        $now = now();
        $startMonth = 6;
        $startYear = $now->month >= $startMonth ? $now->year : $now->year - 1;

        return $startYear.'-'.($startYear + 1);
    }
}
