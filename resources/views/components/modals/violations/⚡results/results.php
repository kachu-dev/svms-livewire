<?php

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $resultType = 'success';

    public $resultMessage = '';

    #[On('show-result')]
    public function showResult($type, $message): void
    {
        $this->resultType = $type;
        $this->resultMessage = $message;
        $this->modal('results')->show();
    }
};
