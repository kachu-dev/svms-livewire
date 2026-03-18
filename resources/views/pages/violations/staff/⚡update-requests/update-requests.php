<?php

use App\Models\ViolationUpdateRequest;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => 'Update Request Management'])] class extends Component
{
    public string $search = '';

    public function resetFilters(): void
    {
        $this->reset(['search']);
    }

    #[Computed]
    public function requests()
    {
        return ViolationUpdateRequest::with(['violation', 'requestedBy'])
            ->whereHas('violation')
            ->where('status', 'pending')
            ->when($this->search, fn ($q) => $q->whereHas('violation', fn ($q) => $q->search($this->search)))
            ->latest()
            ->get();
    }

    #[On('refresh-request')]
    public function refresh(): void {}
};
