<?php

use App\Models\ViolationType;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $selectedTypeId;

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

        return Cache::remember(
            "violation_remarks_{$this->selectedTypeId}",
            now()->addHour(),
            fn () => ViolationType::find($this->selectedTypeId)
                ?->remarks()
                ->select('id', 'label')
                ->get()
                ?? collect()
        );
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
