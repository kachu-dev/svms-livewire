<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\SchoolYearHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use UnexpectedValueException;

class Violation extends Model
{
    use LogsActivity, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('violation')
            ->logOnly([
                'student_id', 'type_code', 'type_name',
                'remark', 'classification', 'status',
                'is_escalated', 'recorded_by',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => "Violation was {$event}");
    }

    protected $fillable = [
        'student_id',
        'student_name',
        'type_code',
        'type_name',
        'remark',
        'classification',
        'is_escalated',
        'status',
        'recorded_by',
        'st_first_name',
        'st_last_name',
        'st_program',
        'st_year',
        'st_mi',
        'school_year',
        'is_active',
        'created_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    #[Scope]
    protected function search($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('student_id', 'like', "%{$search}%")
                ->orWhere('st_last_name', 'like', "%{$search}%")
                ->orWhere('st_first_name', 'like', "%{$search}%")
                ->orWhere('st_mi', 'like', "%{$search}%")
                ->orWhere('st_program', 'like', "%{$search}%")
                ->orWhere('st_year', 'like', "%{$search}%")
                ->orWhere('type_code', 'like', "%{$search}%")
                ->orWhere('type_name', 'like', "%{$search}%")
                ->orWhere('remark', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhere('classification', 'like', "%{$search}%");
            $q->orWhereHas('recordedBy', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('assigned_gate', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        });
    }

    #[Scope]
    protected function period($query, string $period = 'month', ?string $from = null, ?string $to = null)
    {
        if ($from && $to) {
            return $query->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        }

        if ($from) {
            return $query->where('created_at', '>=', Carbon::parse($from)->startOfDay());
        }

        if ($to) {
            return $query->where('created_at', '<=', Carbon::parse($to)->endOfDay());
        }

        return match ($period) {
            'today' => $query->whereDate('created_at', today()),
            'week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
            'year' => $query->whereYear('created_at', now()->year),
            default => $query,
        };
    }

    /*#[Scope]
    protected function total(Builder $query): Builder
    {
        return $query->count();
    }*/

    #[Scope]
    protected function pending(Builder $query): Builder
    {
        return $query->where('status', '!=', 'Complete');
    }

    #[Scope]
    protected function resolved(Builder $query): Builder
    {
        return $query->where('status', 'Complete');
    }

    #[Scope]
    protected function minor(Builder $query): Builder
    {
        return $query->where('classification', 'Minor');
    }

    #[Scope]
    public function currentYear($query): void
    {
        $query->where('school_year', SchoolYearHelper::current());
    }

    #[Scope]
    public function forYear($query, string $year): void
    {
        $query->where('school_year', $year);
    }

    #[Scope]
    protected function majorSuspension(Builder $query): Builder
    {
        return $query->where('classification', 'Major - Suspension');
    }

    #[Scope]
    protected function majorDismissal(Builder $query): Builder
    {
        return $query->where('classification', 'Major - Dismissal');
    }

    #[Scope]
    protected function majorExpulsion(Builder $query): Builder
    {
        return $query->where('classification', 'Major - Expulsion');
    }

    public function resolveOffenseKey(): string
    {
        if ($this->classification !== 'Minor') {
            return match (true) {
                str_contains($this->classification, 'Suspension') => 'major_suspension',
                str_contains($this->classification, 'Dismissal') => 'major_dismissal',
                str_contains($this->classification, 'Expulsion') => 'major_expulsion',
                default => throw new UnexpectedValueException(
                    'Minor count exceeds expected escalation range'
                ),
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
