<?php

use App\Models\Violation;
use Livewire\Component;

new class extends Component
{
    public function render()
    {
        return $this->view([
            'violations' => Violation::latest()->take(5)->get(),
        ]);
    }
};
