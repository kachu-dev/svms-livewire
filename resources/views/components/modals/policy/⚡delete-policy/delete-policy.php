<?php

use App\Models\ViolationType;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new class extends Component
{
    public ?ViolationType $policy = null;

    public string $name = '';

    public string $code = '';

    #[On('confirm-delete-policy')]
    public function setFields($id): void
    {
        try {
            $this->policy = ViolationType::findOrFail($id);

            $this->name = $this->policy->name;
            $this->code = $this->policy->code;

            $this->modal('delete-policy')->show();
        } catch (Exception) {
            Toaster::error('Policy not found.');
        }
    }

    public function delete(): void
    {
        if (! $this->policy instanceof ViolationType) {
            Toaster::error('No policy selected for deactivation.');

            return;
        }

        try {
            $this->policy->delete();

            $this->modal('delete-policy')->close();
            $this->dispatch('refresh-policy');

            Toaster::success('Policy deactivated successfully!');
        } catch (Exception) {
            Toaster::error('Failed to deactivate policy. Please try again.');
        }
    }
};
