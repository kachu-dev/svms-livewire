<?php

use App\Models\ViolationDeleteRequest;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => 'Violation Management'])] class extends Component
{
    #[Computed]
    public function requests()
    {
        return ViolationDeleteRequest::with(['violation', 'requestedBy'])
            ->where('status', 'pending')
            ->latest()
            ->get();
    }

    #[On('refresh-request')]
    public function refresh(): void {}

    public function deny($requestId): void
    {
        $request = ViolationDeleteRequest::findOrFail($requestId);

        $request->update([
            'status' => 'denied',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        Toaster::success('Request denied.');
    }
};
