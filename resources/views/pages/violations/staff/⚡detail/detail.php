<?php

use App\Models\Violation;
use App\Models\ViolationStages;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::app', ['title' => 'TESTING STEPS'])] class extends Component {

    use WithFileUploads;

    public Violation $violation;

    public ViolationStages $stage;

    public $remarks;

    public $attachment;

    public function confirm(): void
    {
        $path = null;

        if ($this->attachment) {
            $path = $this->attachment->store('violation_stages', 'public');
        }

        $this->stage->is_complete = true;
        $this->stage->completed_at = now();
        $this->stage->remark = $this->remarks;

        if ($path) {
            $this->stage->file_path = $path;
        }

        $this->stage->save();

        $nextStage = $this->nextStage();

        if ($nextStage) {

            $this->violation->update([
                'status' => $nextStage->name,
            ]);

            $this->redirectRoute('staff.violations.detail',
                ['violation' => $this->violation->id, 'stage' => $nextStage->id]);
        } else {

            $this->violation->update([
                'status' => 'Completed',
            ]);

            $this->redirectRoute('staff.violations.detail', [
                'violation' => $this->violation->id,
                'stage' => $this->stage->id,
            ]);
        }
    }

    public function undo(): void
    {
        $lastCompleted = $this->violation->stages()
            ->where('is_complete', true)
            ->orderBy('order')
            ->get()
            ->last();

        if (!$lastCompleted) return;

        $lastCompleted->update([
            'is_complete' => false,
            'completed_at' => null,
            'remark' => null,
            'file_path' => null,
        ]);

        $this->violation->update([
            'status' => $lastCompleted->name,
        ]);

        $this->redirectRoute('staff.violations.detail', [
            'violation' => $this->violation->id,
            'stage' => $lastCompleted->id,
        ]);
    }

    public function nextStage()
    {
        return $this->violation->stages()
            ->where('is_complete', false)
            ->orderBy('order')
            ->first();
    }

    public function previousStage()
    {
        return $this->violation->stages
            ->where('is_complete', true)
            ->where('order', '<', $this->stage->order)
            ->sortByDesc('order')
            ->first();
    }

    public function canComplete(): bool
    {
        $currentStage = $this->violation->stages()
            ->where('is_complete', false)
            ->orderBy('order')
            ->first();

        return $currentStage
            && $this->stage->id === $currentStage->id;
    }

    public function canUndo(): bool
    {
        $lastCompleted = $this->violation->stages()
            ->where('is_complete', true)
            ->orderBy('order')
            ->get()
            ->last();

        if (!$lastCompleted) return false;

        $previousStage = $this->previousStage();

        return $lastCompleted->id === $this->stage->id
            || ($previousStage && $lastCompleted->id === $previousStage->id);
    }
};
