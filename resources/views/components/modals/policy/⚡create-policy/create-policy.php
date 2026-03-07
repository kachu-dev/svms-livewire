<?php

use App\Models\ViolationType;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new class extends Component
{
    #[Validate('required|string|max:20|unique:violation_types,code')]
    public string $code = '';

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string')]
    public string $classification = '';

    public function submit(): void
    {
        $validated = $this->validate();

        try {
            ViolationType::create($validated);

            $this->resetCreateForm();
            $this->modal('create-policy')->close();
            $this->dispatch('refresh-policy');

            Toaster::success('Policy created successfully!');
        } catch (Exception) {
            Toaster::error('Failed to create policy. Please try again.');
        }
    }

    public function resetCreateForm(): void
    {
        $this->resetValidation();
        $this->reset();
    }
};
