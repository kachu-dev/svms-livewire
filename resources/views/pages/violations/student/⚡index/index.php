<?php

use App\Models\Violation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::student', ['title' => 'Recent Violations'])] class extends Component
{

    public function violations()
    {
        return Auth::user()->violations;
    }
};
