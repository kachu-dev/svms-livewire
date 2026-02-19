<?php

use App\Models\ViolationType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => 'Create Policy'])] class extends Component
{
    #[Validate('required|string')]
    public string $code;

    #[Validate('required|string')]
    public string $name;

    #[Validate('required|string')]
    public string $classification;

    public function submit(): void
    {
        $this->validate();

        ViolationType::create([
            'code' => $this->code,
            'name' => $this->name,
            'classification' => $this->classification,
        ]);

        $this->reset();

        Toaster::success('Policy created successfully!');

        $this->redirectRoute('staff.policy.index');
    }

    public function resetCreateForm(): void
    {
        $this->resetValidation();
        $this->reset();
    }

};
