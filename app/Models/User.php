<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'assigned_gate',
        'email_verified_at',
        'username',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    #[Scope]
    protected function search($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('assigned_gate', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
        });
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'studentid');
    }

    public function recordedViolations(): HasMany
    {
        return $this->hasMany(Violation::class, 'recorded_by');
    }

    public function violations(): HasMany
    {
        return $this->hasMany(Violation::class, 'student_id', 'username');
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'guard' => 'Guard',
            'osa' => 'Staff',
            default => ucfirst($this->role),
        };
    }
}
