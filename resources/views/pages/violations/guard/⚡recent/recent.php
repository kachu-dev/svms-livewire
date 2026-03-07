<?php

use App\Models\Violation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::guard', ['title' => 'Recent Violations'])] class extends Component
{
    #[Computed]
    public function violations()
    {
        return Violation::query()
            ->where('recorded_by', auth()->id())
            ->latest()
            ->take(5)
            ->get();
    }
};
