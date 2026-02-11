<?php

use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $selectedTypeId;

    public $customRemark;

    #[On('type-selected')]
    public function setType($violationId): void
    {
        $this->selectedTypeId = $violationId;
    }

    #[Computed]
    public function violationRemarks()
    {
        if (! $this->selectedTypeId) {
            return collect();
        }

        return ViolationType::find($this->selectedTypeId)->remarks;
    }

    public function setRemark(?int $id): void
    {
        $this->dispatch('remark-selected', remarkId: $id);
        $this->resetValidation('customRemark');
        $this->modal('set-remark')->close();
    }

    public function setCustomRemark(): void
    {
        $this->validate([
            'customRemark' => 'required',
        ]);

        $this->dispatch('custom-remark', remark: $this->customRemark);
        $this->resetValidation('customRemark');
        $this->reset('customRemark');
        $this->modal('set-remark')->close();
    }
};
