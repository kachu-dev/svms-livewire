<?php

use App\Models\ViolationDeleteRequest;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $violationId;

    public $reason;

    public $show = false;

    #[On('request-delete-violation')]
    public function setFields(int $id): void
    {
        $this->violationId = $id;

        $existing = ViolationDeleteRequest::where('violation_id', $id)
            ->where('status', 'pending')
            ->first();

        $this->reason = $existing?->reason ?? '';

        $this->modal('request-delete')->show();
    }

    public function submit(): void
    {
        $this->validate(['reason' => 'required|string|min:10']);

        ViolationDeleteRequest::updateOrCreate(
            [
                'violation_id' => $this->violationId,
                'status' => 'pending',
            ],
            [
                'requested_by' => auth()->id(),
                'reason' => $this->reason,
            ]
        );

        Toaster::success('Delete request submitted.');
        $this->modal('request-delete')->close();
    }
};
