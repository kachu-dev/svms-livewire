<?php

use App\Models\ViolationUpdateRequest;
use App\Services\ViolationService;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $requestId;

    public $denialReason = '';

    #[On('reject-update')]
    public function setFields(int $violationRequestId): void
    {
        $this->requestId = $violationRequestId;
        $this->denialReason = '';
        $this->modal('reject-update')->show();
    }

    public function reject(): void
    {
        $this->validate(['denialReason' => 'required|string|min:10']);

        $request = ViolationUpdateRequest::with('violation')->findOrFail($this->requestId);
        $violation = $request->violation;

        activity('violation_update_request')
            ->causedBy(auth()->user())
            ->performedOn($violation)
            ->withProperties([
                'request_id' => $this->requestId,
                'denial_reason' => $this->denialReason,
            ])
            ->log('Update request rejected');

        $violation->update(['is_active' => true]);

        app(ViolationService::class)->checkAndEscalateForStudent(
            $violation->student_id,
            $violation->school_year
        );

        $request->update([
            'status' => 'denied',
            'denial_reason' => $this->denialReason,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $this->dispatch('refresh-request');
        Toaster::success('Request rejected.');
        $this->modal('reject-update')->close();
    }
};
