<?php

use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $selectedTypeId;

    public $selectedTypeId;

    public $selectedTypeId;

    #[Computed]
    public function violationRemarks()
    {
        if (! $this->selectedTypeId) {
            return collect();
        }

        return ViolationType::find($this->selectedTypeId)
            ->remarks;
    }

    #[On('type-selected')]
    public function setType($violationId): void
    {
        $this->selectedTypeId = $violationId;
    }

    public function setRemark(?int $id): void
    {
        $this->dispatch('remark-selected', remarkId: $id);
        $this->modal('set-remark')->close();
    }
};
