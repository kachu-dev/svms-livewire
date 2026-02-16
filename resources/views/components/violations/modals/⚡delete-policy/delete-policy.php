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
    }

    public function delete(): void
    {
        $this->policy->delete();

        $this->redirect('policy');
    }
};
