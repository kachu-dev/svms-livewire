<?php

use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $selectedTypeId;

    public $selectedRemarkId;

    public $customRemark;

    #[On('type-selected')]
    public function setType($id): void
    {
        $this->selectedTypeId = $id;
    }

    #[Computed]
    public function violationRemarks()
    {
        if (! $this->selectedTypeId) {
            return collect();
        }

        return ViolationType::find($this->selectedTypeId)
            ?->remarks()
            ->select('id', 'label')
            ->get()
            ?? collect();
    }

    public function setCustomRemark(): void
    {
        $this->validate([
            'customRemark' => 'required',
        ]);

        $this->dispatch('custom-remark', remark: $this->customRemark);
        $this->reset('customRemark');
        $this->modal('set-remark')->close();
    }
};
