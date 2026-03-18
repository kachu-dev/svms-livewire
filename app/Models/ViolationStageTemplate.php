<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationStageTemplate extends Model
{
    protected $fillable = ['offense_key', 'order', 'name'];

    protected static function booted(): void
    {
        static::creating(function ($stage) {
            if (is_null($stage->order)) {
                $stage->order = static::where('offense_key', $stage->offense_key)
                    ->max('order') + 1 ?? 1;
            }
        });

        static::deleting(function ($stage) {
            static::where('offense_key', $stage->offense_key)
                ->where('order', '>', $stage->order)
                ->decrement('order');
        });
    }

    public function move(int $newPosition): void
    {
        $newPosition += 1;

        $oldPosition = $this->order;

        if ($newPosition === $oldPosition) {
            return;
        }

        if ($newPosition < $oldPosition) {
            static::where('offense_key', $this->offense_key)
                ->whereBetween('order', [$newPosition, $oldPosition - 1])
                ->increment('order');
        } else {
            static::where('offense_key', $this->offense_key)
                ->whereBetween('order', [$oldPosition + 1, $newPosition])
                ->decrement('order');
        }

        $this->update(['order' => $newPosition]);
    }
}
