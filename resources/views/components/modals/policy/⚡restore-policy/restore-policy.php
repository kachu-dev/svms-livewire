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

    #[On('restore-policy')]
    public function setFields($id): void
    {
        try {
            $this->policy = ViolationType::onlyTrashed()->findOrFail($id);

            $this->name = $this->policy->name;
            $this->code = $this->policy->code;

            $this->modal('restore-policy')->show();
        } catch (Exception) {
            Toaster::error('Policy not found.');
        }
    }

    public function restore(): void
    {
        if (! $this->policy instanceof ViolationType) {
            Toaster::error('No policy selected for restoration.');

            return;
        }

        try {
            $this->policy->restore();

            $this->modal('restore-policy')->close();
            $this->dispatch('refresh-del-policy');

            Toaster::success('Policy reactivated successfully!');
        } catch (Exception) {
            Toaster::error('Failed to restore policy. Please try again.');
        }
    }
};
