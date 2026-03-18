<?php

use App\Models\Violation;
use App\Models\ViolationUpdateRequest;
use App\Services\ViolationService;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?Violation $violation = null;

    public $studentId;

    public $studentName;

    public $type;

    public $currentRemark;

    public $newRemark;

    public $reason;

    public $requestId;

    #[On('approve-update')]
    public function setFields($violationId, $violationRequestId): void
    {
        $this->violation = Violation::find($violationId);
        $this->requestId = $violationRequestId;

        $request = ViolationUpdateRequest::find($violationRequestId);

        $this->studentId = $this->violation->student_id;
        $this->studentName = $this->violation->st_first_name.' '.$this->violation->st_last_name;
        $this->type = $this->violation->type_code.' - '.$this->violation->type_name;
        $this->currentRemark = $this->violation->remark;
        $this->newRemark = $request->new_remark;
        $this->reason = $request->reason;

        $this->modal('approve-update')->show();
    }

    public function approve(): void
    {
        $request = ViolationUpdateRequest::with('violation')->findOrFail($this->requestId);
        $violation = $request->violation;

        activity('violation_update_request')
            ->causedBy(auth()->user())
            ->performedOn($violation)
            ->withProperties([
                'student_id' => $this->studentId,
                'student_name' => $this->studentName,
                'type' => $this->type,
                'old_remark' => $this->currentRemark,
                'new_remark' => $this->newRemark,
                'reason' => $this->reason,
                'request_id' => $this->requestId,
            ])
            ->log('Update request approved — remark changed');

        $violation->update([
            'remark' => $request->new_remark,
            'is_active' => true,
        ]);

        app(ViolationService::class)->checkAndEscalateForStudent(
            $violation->student_id,
            $violation->school_year
        );

        $request->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $this->dispatch('refresh-request');
        Toaster::success('Remark updated.');
    }
};
