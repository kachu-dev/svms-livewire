<?php

use App\Models\ViolationType;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => 'Policy Management'])] class extends Component
{
    public function getFilteredTypesProperty()
    {
        return ViolationType::all()->groupBy('classification');
    }
};
