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

    public $previousStage;

    public $fileClear;

    public $remarkClear;

    public function mount(): void
    {
        $this->remarks = $this->stage->remark;
    }

    public function confirm(): void
    {
        // Validate if attachment is provided
        if ($this->attachment) {
            $this->validate([
                'attachment' => 'file|max:10240', // 10MB max
            ]);
        }

        try {
            // Handle file upload
            if ($this->attachment) {
                // Delete old file if exists
                if ($this->stage->file_path) {
                    Storage::disk('public')->delete($this->stage->file_path);
                }

                $path = $this->attachment->store('violation_stages', 'public');
                $fileName = $this->attachment->getClientOriginalName();

                $this->stage->file_path = $path;
                $this->stage->file_name = $fileName;
            }

            // Update remarks
            $this->stage->remark = $this->remarks;
            $this->stage->save();

            // Reset and close
            $this->reset('attachment');
            $this->resetValidation();
            $this->modal('edit-status')->close();

            Toaster::success('Stage details updated successfully!');
        } catch (Exception) {
            Toaster::error('Failed to update stage details.');
        }
    }

    #[Computed]
    public function fileExtension()
    {
        return $this->stage?->file_path
            ? strtolower(pathinfo($this->stage->file_path, PATHINFO_EXTENSION))
            : null;
    }

    public function next(): void
    {
        $nextStage = $this->violation->stages()
            ->where('order', '>', $this->stage->order)
            ->first();

        if (! $nextStage) {
            Toaster::info('This is the last stage.');

            return;
        }

        $this->redirectRoute('staff.violations.detail', [
            'violation' => $this->violation,
            'stage' => $nextStage->id,
        ], navigate: true);
    }

    public function previous(): void
    {
        $previousStage = $this->violation->stages()
            ->where('order', '<', $this->stage->order)
            ->orderByDesc('order')
            ->first();

        if (! $previousStage) {
            Toaster::info('This is the first stage.');

            return;
        }

        $this->redirectRoute('staff.violations.detail', [
            'violation' => $this->violation,
            'stage' => $previousStage->id,
        ], navigate: true);
    }

    public function toggleComplete(): void
    {
        $this->stage->is_complete = ! $this->stage->is_complete;
        $this->stage->save();

        $this->updateViolationStatus();

        $status = $this->stage->is_complete ? 'completed' : 'incomplete';
        Toaster::success("Stage marked as {$status}.");
    }

    public function updateViolationStatus(): void
    {
        $stages = $this->violation->stages()->orderBy('order')->get();
        $isLastStage = $stages->last()->id === $this->stage->id;

        if ($this->stage->is_complete && $isLastStage) {
            // Last stage completed — violation is fully done
            $this->violation->status = 'Complete';
        } elseif (! $this->stage->is_complete) {
            // Stage was just marked incomplete — revert status back to this stage's name
            $this->violation->status = $this->stage->name;
        } else {
            // Stage complete but not the last — move status forward to next stage
            $nextStage = $stages->where('order', '>', $this->stage->order)->first();
            $this->violation->status = $nextStage?->name ?? $this->stage->name;
        }

        $this->violation->save();
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
            // Clear file
            if ($this->fileClear && $this->stage->file_path) {
                Storage::disk('public')->delete($this->stage->file_path);
                $this->stage->file_path = null;
                $this->stage->file_name = null;
            }

            // Clear remark
            if ($this->remarkClear) {
                $this->stage->remark = null;
                $this->remarks = null; // Also clear the component property
            }

            $this->stage->save();

            $this->reset('remarkClear', 'fileClear');
            $this->modal('clear-details')->close();

            Toaster::success('Details cleared successfully!');
        } catch (Exception) {
            Toaster::error('Failed to clear details.');
        }
    }

    public function updatedAttachment(): void
    {
        $this->resetValidation('attachment');
    }
};
