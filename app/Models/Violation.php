<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Violation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'student_name',
        'violation_type_id',
        'violation_type_snapshot',
        'violation_remark_id',
        'violation_remark_snapshot',
        'classification',
        'status',
        'original_violation_type_id',
        'is_complete',
        'completed_at',
        'remark',
        'file_path',
    ];

    #[Scope]
    protected function search($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('student_id', 'like', "%{$search}%")
                ->orWhere('student_name', 'like', "%{$search}%")
                ->orWhere('violation_type_snapshot', 'like', "%{$search}%")
                ->orWhere('violation_remark_snapshot', 'like', "%{$search}%")
                ->orWhere('classification', 'like', "%{$search}%");
        });
    }

    public function minorOffenseNumber(): ?int
    {
        if ($this->classification !== 'Minor') {
            return null;
        }

        return static::where('student_id', $this->student_id)
            ->where('classification', 'Minor')
            ->where('created_at', '<=', $this->created_at)
            ->orderBy('created_at')
            ->orderBy('id')
            ->pluck('id')
            ->search($this->id) + 1;
    }

    public function resolveOffenseKey(): string
    {
        if ($this->classification !== 'Minor') {
            return match (true) {
                str_contains($this->classification, 'Suspension') => 'major_suspension',
                str_contains($this->classification, 'Dismissal') => 'major_dismissal',
                str_contains($this->classification, 'Expulsion') => 'major_expulsion',
                default => 'major_suspension',
            };
        }

        $minorCount = static::where('student_id', $this->student_id)
            ->where('classification', 'Minor')
            ->where('created_at', '<=', $this->created_at)
            ->count();

        return match (true) {
            $minorCount <= 1 => 'minor_1',
            $minorCount === 2 => 'minor_2',
            $minorCount === 3 => 'minor_3',
            default => 'major_suspension',
        };
    }

    public function stages()
    {
        return $this->hasMany(ViolationStages::class)->orderBy('order');
    }

    public function getCurrentStageAttribute()
    {
        return $this->stages
            ->sortBy('order')
            ->firstWhere('is_complete', false);
    }
}
