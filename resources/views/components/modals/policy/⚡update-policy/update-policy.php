<?php

use App\Models\ViolationType;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new class extends Component
{
    public ?ViolationType $policy = null;

    #[Validate('required|string|max:20')]
    public string $code = '';

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string')]
    public string $classification = '';

    #[On('update-policy')]
    public function setFields($id): void
    {
        try {
            $this->resetErrorBag();

            $this->policy = ViolationType::findOrFail($id);

            $this->code = $this->policy->code;
            $this->name = $this->policy->name;
            $this->classification = $this->policy->classification;

            $this->modal('update-policy')->show();
        } catch (Exception) {
            Toaster::error('Policy not found.');
        }
    }

    public function save(): void
    {
        if (! $this->policy instanceof ViolationType) {  // ← Add null check
            Toaster::error('No policy selected for update.');

            return;
        }

        $this->validate([
            'code' => ['required', 'string', 'max:20', 'unique:violation_types,code,'.$this->policy->id],
        ]);

        try {
            $this->policy->update(
                $this->only(['code', 'name', 'classification'])
            );

            $this->modal('update-policy')->close();
            $this->dispatch('refresh-policy');

            Toaster::success('Policy updated successfully!');
        } catch (Exception) {
            Toaster::error('Failed to update policy. Please try again.');
        }
    }
};
