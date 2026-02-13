<?php

use App\Models\ViolationType;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    public ?ViolationType $policy;

    #[Validate('required|string')]
    public $code = '';

    #[Validate('required|string')]
    public $name = '';

    #[Validate('required|string')]
    public $classification;

    public function mount($id): void
    {
        $this->policy = ViolationType::find($id);

        $this->code = $this->policy->code;
        $this->name = $this->policy->name;
        $this->classification = $this->policy->classification;
    }

    public function save(): void
    {
        $this->validate();

        $this->policy->update(
            $this->only(['code', 'name', 'classification'])
        );

        $this->redirect("/policy", navigate: true);
    }
};
