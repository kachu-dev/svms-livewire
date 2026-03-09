<?php

use App\Models\ViolationDeleteRequest;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $requestId;

    public $reason;

    #[On('reject-delete')]
    public function setFields(int $violationRequestId): void
    {
        $this->requestId = $violationRequestId;
        $this->reason = '';
        $this->modal('reject-delete')->show();
    }

    public function submit(): void
    {
        ViolationDeleteRequest::findOrFail($this->requestId)->update([
            'status' => 'denied',
            'reviewed_by' => auth()->id(),
            'denial_reason' => $this->reason,
            'reviewed_at' => now(),
        ]);

        Toaster::success('Request declined.');
        $this->modal('reject-delete')->close();
        $this->dispatch('refresh-request');
    }

    public function resetForm(): void
    {
        $this->resetValidation();
        $this->reset();
    }
};
