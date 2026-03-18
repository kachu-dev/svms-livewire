<?php

use App\Models\ViolationDeleteRequest;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => 'Violation Management'])] class extends Component
{
    public string $search = '';

    public function resetFilters(): void
    {
        $this->reset(['search']);
    }

    #[Computed]
    public function requests()
    {
        return ViolationDeleteRequest::with(['violation', 'requestedBy'])
            ->where('status', 'pending')
            ->when($this->search, fn ($q) => $q->whereHas('violation', fn ($q) => $q->search($this->search)))
            ->latest()
            ->get();
    }

    #[On('refresh-request')]
    public function refresh(): void {}
};
