<?php

use App\Models\ViolationType;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?ViolationType $policy = null;

    public $name;

    public $code;

    #[On('restore-policy')]
    public function setFields($id): void
    {
        $this->policy = ViolationType::onlyTrashed()->find($id);

        $this->name = $this->policy->name;
        $this->code = $this->policy->code;
    }

    public function restore(): void
    {
        $this->policy->restore();

        $this->redirectRoute('staff.policy.deleted');
    }
};
