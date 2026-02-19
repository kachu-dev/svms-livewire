<?php

use App\Models\ViolationType;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?ViolationType $policy = null;

    public $name;

    public $code;

    #[On('confirm-delete-policy')]
    public function setFields($id): void
    {
        $this->policy = ViolationType::find($id);

        $this->name = $this->policy->name;
        $this->code = $this->policy->code;

        $this->modal('delete-policy')->show();
    }

    public function delete(): void
    {
        $this->policy->delete();

        Toaster::success('Policy deactivated successfully!');

        $this->redirectRoute('staff.policy.index');
    }
};
