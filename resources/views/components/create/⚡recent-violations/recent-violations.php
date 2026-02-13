<?php

use App\Models\Violation;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function violations()
    {
        return Violation::latest()->take(5)->get();
    }
};
