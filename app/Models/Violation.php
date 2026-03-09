<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use UnexpectedValueException;

class Violation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'student_name',
        'violation_type_id',
        'violation_type_code_snapshot',
        'violation_type_name_snapshot',
        'violation_remark_id',
        'violation_remark_snapshot',
        'classification_snapshot',
        'status',
        'original_violation_type_id',
        'recorded_by',
    ];

    #[Scope]
    protected function search($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('student_id', 'like', "%{$search}%")
                ->orWhere('student_name', 'like', "%{$search}%")
                ->orWhere('violation_type_code_snapshot', 'like', "%{$search}%")
                ->orWhere('violation_type_name_snapshot', 'like', "%{$search}%")
                ->orWhere('violation_remark_snapshot', 'like', "%{$search}%")
                ->orWhere('classification_snapshot', 'like', "%{$search}%");
        });
    }

    public function getMinorOffenseNumberAttribute(): ?int
    {
        if ($this->classification_snapshot !== 'Minor') {
            return null;
        }

        return static::where('student_id', $this->student_id)
            ->where('classification_snapshot', 'Minor')
            ->where('created_at', '<=', $this->created_at)
            ->orderBy('created_at')
            ->orderBy('id')
            ->pluck('id')
            ->search($this->id) + 1;
    }

    public function resolveOffenseKey(): string
    {
        if ($this->classification_snapshot !== 'Minor') {
            return match (true) {
                str_contains($this->classification_snapshot, 'Suspension') => 'major_suspension',
                str_contains($this->classification_snapshot, 'Dismissal') => 'major_dismissal',
                str_contains($this->classification_snapshot, 'Expulsion') => 'major_expulsion',
                default => throw new UnexpectedValueException(
                    'Minor count exceeds expected escalation range'
                ),
            };
        }

        $minorCount = static::where('student_id', $this->student_id)
            ->where('classification_snapshot', 'Minor')
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
        return $this->hasMany(ViolationStages::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'studentid');
    }

    public function getCurrentStageAttribute()
    {
        $sorted = $this->stages->sortBy('order');

        $current = $sorted->firstWhere('is_complete', false);

        return $current ?: $sorted->last();
    }

    public function getLastCompletedStageAttribute()
    {
        return $this->stages
            ->where('is_complete', true)
            ->sortByDesc('order')
            ->first();
    }
}
