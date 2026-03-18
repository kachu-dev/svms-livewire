<?php

use App\Models\Violation;
use App\Models\ViolationStages;
use App\Services\ViolationService;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

new #[Layout('layouts::app', ['title' => 'Status Management'])] class extends Component
{
    use WithFileUploads;

    public Violation $violation;

    public ?ViolationStages $stage = null;

    protected ViolationService $violationService;

    public $remarks;

    public $attachment;

    public bool $fileClear = false;

    public bool $remarkClear = false;

    public string $newStageName = '';

    public string $editStageName = '';

    public function mount(): void
    {
        if (! $this->stage instanceof ViolationStages) {
            $first = $this->violation->stages()->orderBy('order')->first();
            if ($first) {
                $this->redirect(route('staff.violations.detail', [
                    'violation' => $this->violation,
                    'stage' => $first->id,
                ]), navigate: true);

                return;
            }
        }

        $this->remarks = $this->stage?->remark;
    }

    public function boot(ViolationService $violationService): void
    {
        $this->violationService = $violationService;
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

        $this->stage?->refresh();
        $this->violation->unsetRelation('stages');
        $this->violation->load('stages');
    }

    public function confirm(): void
    {
        if (! $this->stage instanceof ViolationStages) {
            return;
        }

        if ($this->attachment) {
            $this->validate([
                'attachment' => 'file|max:10240',
            ]);
        }

        try {
            $changes = [];

            if ($this->attachment) {
                $path = $this->attachment->store('violation_stages', 'public');
                $fileName = $this->attachment->getClientOriginalName();

                if ($this->stage->file_path) {
                    Storage::disk('public')->delete($this->stage->file_path);
                }

                $this->stage->file_path = $path;
                $this->stage->file_name = $fileName;
                $changes['attachment'] = $fileName;
            }

            if ($this->remarks !== $this->stage->remark) {
                $changes['old_remark'] = $this->stage->remark;
                $changes['new_remark'] = $this->remarks;
            }

            $this->stage->remark = $this->remarks;
            $this->stage->save();

            activity('violation_stage')
                ->causedBy(auth()->user())
                ->performedOn($this->violation)
                ->withProperties([
                    'stage_id' => $this->stage->id,
                    'stage_name' => $this->stage->name,
                    'stage_order' => $this->stage->order,
                    ...$changes,
                ])
                ->log("Stage \"{$this->stage->name}\" details updated");

            $this->reset('attachment');
            $this->resetValidation();
            $this->modal('edit-stage')->close();

            Toaster::success('Stage details updated successfully!');
        } catch (Exception $e) {
            report($e);
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

    #[Computed]
    public function stages()
    {
        return $this->violation
            ->stages()
            ->orderBy('order')
            ->get();
    }

    public function clearDetails(): void
    {
        if (! $this->stage instanceof ViolationStages) {
            return;
        }

        if (! $this->fileClear && ! $this->remarkClear) {
            Toaster::warning('Please select at least one option to clear.');

            return;
        }

        try {
            $cleared = [];

            if ($this->fileClear && $this->stage->file_path) {
                Storage::disk('public')->delete($this->stage->file_path);
                $cleared['attachment'] = $this->stage->file_name;
                $this->stage->file_path = null;
                $this->stage->file_name = null;
            }

            if ($this->remarkClear) {
                $cleared['remark'] = $this->stage->remark;
                $this->stage->remark = null;
                $this->remarks = null;
            }

            $this->stage->save();

            activity('violation_stage')
                ->causedBy(auth()->user())
                ->performedOn($this->violation)
                ->withProperties([
                    'stage_id' => $this->stage->id,
                    'stage_name' => $this->stage->name,
                    'stage_order' => $this->stage->order,
                    'cleared' => $cleared,
                ])
                ->log("Stage \"{$this->stage->name}\" details cleared");

            $this->reset('remarkClear', 'fileClear');
            $this->modal('clear-details')->close();

            Toaster::success('Details cleared successfully!');
        } catch (Exception $e) {
            report($e);
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

        $this->syncViolationStatus();

        if ($this->stage instanceof ViolationStages) {
            $this->stage->refresh();
        }

        $this->modal('reset-progress')->close();

        Toaster::success('Progress reset. Files and remarks preserved.');
    }

    public function updatedAttachment(): void
    {
        $this->resetValidation('attachment');
    }

    private function syncViolationStatus(): void
    {
        $this->violation->unsetRelation('stages');

        $currentStage = $this->violation->stages()
            ->orderBy('order')
            ->where('is_complete', false)
            ->first();

        $this->violation->status = $currentStage?->name ?? 'Pending';
        $this->violation->save();
    }

    public function addStage(): void
    {
        $this->validate(['newStageName' => 'required|string|max:255']);

        try {
            $maxOrder = $this->violation->stages()->max('order') ?? 0;

            $stage = $this->violation->stages()->create([
                'name' => trim($this->newStageName),
                'order' => $maxOrder + 1,
            ]);

            activity('violation_stage')
                ->causedBy(auth()->user())
                ->performedOn($this->violation)
                ->withProperties(['stage_name' => $stage->name, 'stage_order' => $stage->order])
                ->log("Stage \"{$stage->name}\" created");

            $this->violation->unsetRelation('stages');
            $this->violation->load('stages');

            $this->reset('newStageName');
            $this->modal('add-stage')->close();

            Toaster::success('Stage added successfully!');
        } catch (Exception $e) {
            report($e);
            Toaster::error('Failed to add stage.');
        }
    }

    public function renameStage(): void
    {
        if (! $this->stage instanceof ViolationStages) {
            return;
        }

        $this->validate(['editStageName' => 'required|string|max:255']);

        try {
            $old = $this->stage->name;
            $this->stage->name = trim($this->editStageName);
            $this->stage->save();

            activity('violation_stage')
                ->causedBy(auth()->user())
                ->performedOn($this->violation)
                ->withProperties(['old_name' => $old, 'new_name' => $this->stage->name])
                ->log("Stage renamed from \"{$old}\" to \"{$this->stage->name}\"");

            $this->violation->unsetRelation('stages');
            $this->violation->load('stages');
            $this->syncViolationStatus();

            $this->modal('rename-stage')->close();

            Toaster::success('Stage renamed successfully!');
        } catch (Exception $e) {
            report($e);
            Toaster::error('Failed to rename stage.');
        }
    }

    public function deleteStage(): void
    {
        if (! $this->stage instanceof ViolationStages) {
            return;
        }

        $lastStage = $this->violation->stages()->orderBy('order', 'desc')->first();

        if ($lastStage?->id !== $this->stage->id) {
            Toaster::warning('Only the last stage can be deleted.');

            return;
        }

        try {
            $name = $this->stage->name;
            $order = $this->stage->order;

            if ($this->stage->file_path) {
                Storage::disk('public')->delete($this->stage->file_path);
            }

            $this->stage->delete();

            activity('violation_stage')
                ->causedBy(auth()->user())
                ->performedOn($this->violation)
                ->withProperties(['stage_name' => $name, 'stage_order' => $order])
                ->log("Stage \"{$name}\" deleted");

            $this->stage = null;
            $this->violation->unsetRelation('stages');
            $this->violation->load('stages');
            $this->syncViolationStatus();

            $newLast = $this->violation->stages()->orderBy('order', 'desc')->first();

            $this->redirect(route('staff.violations.detail', [
                'violation' => $this->violation,
                'stage' => $newLast?->id,
            ]), navigate: true);

            Toaster::success('Stage deleted.');
        } catch (Exception $e) {
            report($e);
            Toaster::error('Failed to delete stage.');
        }
    }
};
