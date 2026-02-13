<?php

use App\Models\ViolationType;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
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
    }
};
