<?php

use App\Models\Violation;
use App\Models\ViolationStages;
use App\Services\ViolationService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::app', ['title' => 'Status Management'])] class extends Component
{
    use WithFileUploads;

    public Violation $violation;

    public ViolationStages $stage;

    protected ViolationService $violationService;

    public $remarks;

    public $attachment;

    public $fileClear;

    public $remarkClear;

    public function mount(): void
    {
        $this->violation->load('stages');
        $this->remarks = $this->stage->remark;
    }

    public function boot(ViolationService $violationService): void
    {
        $this->violationService = $violationService;
    }

    public function handleSort(int $id, int $position): void
    {
        $stages = $this->violation->stages()->orderBy('order')->get();

        $stage = $stages->firstWhere('id', $id);

        if (! $stage) {
            return;
        }

        $reordered = $stages->reject(fn ($s) => $s->id === $id)->values();
        $reordered->splice($position, 0, [$stage]);

        // Use a large temp offset to avoid unique constraint collisions mid-update
        $offset = $stages->count() + 100;

        foreach ($reordered as $index => $s) {
            ViolationStages::where('id', $s->id)->update(['order' => $offset + $index]);
        }

        foreach ($reordered as $index => $s) {
            ViolationStages::where('id', $s->id)->update(['order' => $index + 1]);
        }

        $this->violation->unsetRelation('stages');
        $this->violation->load('stages');

        Toaster::success('Stage order updated.');
    }

    public function toggleStage(int $stageId): void
    {
        $result = $this->violationService->toggleStage($this->violation, $stageId);

        match ($result) {
            'previous_incomplete' => Toaster::warning('Complete the previous stage first.'),
            'next_complete' => Toaster::warning('Undo the next stage first.'),
            'completed' => Toaster::success('Stage marked as completed.'),
            'incomplete' => Toaster::success('Stage marked as incomplete.'),
        };

        // Sync local stage state
        $this->stage->refresh();
        $this->violation->unsetRelation('stages');
        $this->violation->load('stages');
    }

    public function confirm(): void
    {
        if ($this->attachment) {
            $this->validate([
                'attachment' => 'file|max:10240',
            ]);
        }

        try {
            if ($this->attachment) {
                if ($this->stage->file_path) {
                    Storage::disk('public')->delete($this->stage->file_path);
                }

                $path = $this->attachment->store('violation_stages', 'public');
                $fileName = $this->attachment->getClientOriginalName();

                $this->stage->file_path = $path;
                $this->stage->file_name = $fileName;
            }

            $this->stage->remark = $this->remarks;
            $this->stage->save();

            $this->reset('attachment');
            $this->resetValidation();
            $this->modal('edit-stage')->close();

            Toaster::success('Stage details updated successfully!');
        } catch (Exception) {
            Toaster::error('Failed to update stage details.');
        }
    }

    #[Computed]
    public function fileExtension(): ?string
    {
        return $this->stage?->file_path
            ? strtolower(pathinfo($this->stage->file_path, PATHINFO_EXTENSION))
            : null;
    }

    public function getStagesProperty()
    {
        return $this->violation
            ->stages()
            ->orderBy('order')
            ->get();
    }

    public function clearDetails(): void
    {
        if (! $this->fileClear && ! $this->remarkClear) {
            Toaster::warning('Please select at least one option to clear.');

            return;
        }

        try {
            if ($this->fileClear && $this->stage->file_path) {
                Storage::disk('public')->delete($this->stage->file_path);
                $this->stage->file_path = null;
                $this->stage->file_name = null;
            }

            if ($this->remarkClear) {
                $this->stage->remark = null;
                $this->remarks = null;
            }

            $this->stage->save();

            $this->reset('remarkClear', 'fileClear');
            $this->modal('clear-details')->close();

            Toaster::success('Details cleared successfully!');
        } catch (Exception) {
            Toaster::error('Failed to clear details.');
        }
    }

    public function resetProgress(): void
    {
        $this->violation->stages()->update([
            'is_complete' => false,
            'completed_at' => null,
        ]);

        $this->violation->unsetRelation('stages');
        $this->violation->load('stages');

        $firstStage = $this->violation->stages()->orderBy('order')->first();
        $this->violation->status = $firstStage?->name ?? 'Pending';
        $this->violation->save();

        $this->stage->is_complete = false;
        $this->stage->completed_at = null;

        $this->modal('reset-progress')->close();

        Toaster::success('Progress reset. Files and remarks preserved.');
    }

    public function updatedAttachment(): void
    {
        $this->resetValidation('attachment');
    }
};
