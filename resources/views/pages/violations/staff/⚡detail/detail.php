<?php

use App\Models\Violation;
use App\Models\ViolationStages;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::app', ['title' => 'Status Management'])] class extends Component
{
    use WithFileUploads;

    public Violation $violation;

    public ViolationStages $stage;

    public $remarks;

    public $attachment;

    public $fileClear;

    public $remarkClear;

    public function mount(): void
    {
        $this->violation->load('stages');
        $this->remarks = $this->stage->remark;
    }

    // ─── Drag-and-drop reorder ────────────────────────────────────────────────

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

    // ─── Toggle complete via sidebar checkbox ─────────────────────────────────

    public function toggleStage(int $stageId): void
    {
        $stages = $this->violation->stages()->orderBy('order')->get();
        $stage = $stages->firstWhere('id', $stageId);
        $index = $stages->search(fn ($s) => $s->id === $stageId);

        if (! $stage->is_complete) {
            $prev = $stages[$index - 1] ?? null;
            if ($prev && ! $prev->is_complete) {
                Toaster::warning('Complete the previous stage first.');

                return;
            }
        } else {
            $next = $stages[$index + 1] ?? null;
            if ($next && $next->is_complete) {
                Toaster::warning('Undo the next stage first.');

                return;
            }
        }

        $stage->is_complete = ! $stage->is_complete;
        $stage->completed_at = $stage->is_complete ? now() : null;
        $stage->save();

        if ($stage->id === $this->stage->id) {
            $this->stage->is_complete = $stage->is_complete;
            $this->stage->completed_at = $stage->completed_at;
        }

        $this->violation->unsetRelation('stages');
        $this->violation->load('stages');

        $this->updateViolationStatus($stage);

        $status = $stage->is_complete ? 'completed' : 'incomplete';
        Toaster::success("Stage marked as {$status}.");
    }

    public function updateViolationStatus(ViolationStages $stage): void
    {
        $stages = $this->violation->stages()->orderBy('order')->get();
        $isLastStage = $stages->last()->id === $stage->id;

        if ($stage->is_complete && $isLastStage) {
            $this->violation->status = 'Complete';
        } elseif (! $stage->is_complete) {
            $this->violation->status = $stage->name;
        } else {
            $nextStage = $stages->where('order', '>', $stage->order)->first();
            $this->violation->status = $nextStage?->name ?? $stage->name;
        }

        $this->violation->save();
    }

    // ─── Edit stage details ───────────────────────────────────────────────────

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
            $this->modal('edit-status')->close();

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
