<?php

use App\Models\ViolationType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => 'Update Policy'])] class extends Component
{
    public ?ViolationType $policy = null;

    #[Validate('required|string')]
    public $code = '';

    #[Validate('required|string')]
    public $name = '';

    #[Validate('required|string')]
    public $classification;

    #[On('update-policy')]
    public function setFields($id): void
    {
        $this->resetErrorBag();

        $this->policy = ViolationType::find($id);

        $this->code = $this->policy->code;
        $this->name = $this->policy->name;
        $this->classification = $this->policy->classification;

        $this->modal('update-policy')->show();
    }

    public function save(): void
    {
        $this->validate();

        $this->policy->update(
            $this->only(['code', 'name', 'classification'])
        );

        Toaster::success('Policy updated successfully!');

        $this->redirectRoute('staff.policy.index');
    }
};
