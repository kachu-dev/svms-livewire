<?php

use App\Models\Violation;
use App\Models\ViolationDeleteRequest;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::guard', ['title' => 'Violation Request'])] class extends Component
{
    #[Computed]
    public function violations()
    {
        return Violation::query()
            ->where('recorded_by', auth()->id())
            ->whereDate('created_at', today())
            ->latest()
            ->take(5)
            ->get();
    }

    #[Computed]
    public function deleteRequests()
    {
        return collect();

        return ViolationDeleteRequest::query()
            ->where('requested_by', auth()->id())
            ->get();
    }
};
