<?php

use App\Models\ViolationDeleteRequest;
use App\Services\ViolationService;
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
        $request = ViolationDeleteRequest::with('violation')->findOrFail($this->requestId);
        $violation = $request->violation;

        activity('violation_delete_request')
            ->causedBy(auth()->user())
            ->performedOn($violation)
            ->withProperties([
                'request_id' => $this->requestId,
                'denial_reason' => $this->reason,
            ])
            ->log('Delete request denied');

        $violation->update(['is_active' => true]);

        app(ViolationService::class)->checkAndEscalateForStudent(
            $violation->student_id,
            $violation->school_year
        );

        $request->update([
            'status' => 'denied',
            'reviewed_by' => auth()->id(),
            'denial_reason' => $this->reason,
            'reviewed_at' => now(),
        ]);

        Toaster::success('Request declined.');
        $this->modal('reject-delete')->close();
        $this->dispatch('refresh-request');
    }
};
