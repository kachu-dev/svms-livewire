<?php

use App\Models\Violation;
use App\Models\ViolationDeleteRequest;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?Violation $violation = null;

    public $studentId;

    public $studentName;

    public $type;

    public $remark;

    public $status;

    public $requestId;

    #[On('approve-delete')]
    public function setFields($violationId, $violationRequestId): void
    {
        $this->violation = Violation::find($violationId);
        $this->requestId = $violationRequestId;
        $this->studentId = $this->violation->student_id;
        $this->studentName = $this->violation->st_first_name.' '.$this->violation->st_last_name;
        $this->type = $this->violation->type_code.' - '.$this->violation->type_name;
        $this->remark = $this->violation->remark;
        $this->status = $this->violation->status;

        $this->modal('approve-delete')->show();
    }

    public function delete(): void
    {
        $request = ViolationDeleteRequest::findOrFail($this->requestId);

        activity('violation_delete_request')
            ->causedBy(auth()->user())
            ->performedOn($request->violation)
            ->withProperties([
                'student_id' => $this->studentId,
                'student_name' => $this->studentName,
                'type' => $this->type,
                'remark' => $this->remark,
                'request_id' => $this->requestId,
            ])
            ->log('Delete request approved — violation deleted');

        $request->violation->delete();

        $request->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $this->dispatch('refresh-request');
        Toaster::success('Violation deleted.');
    }
};
