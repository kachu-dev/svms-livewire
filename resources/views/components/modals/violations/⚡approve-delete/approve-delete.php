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
        $this->studentName = $this->violation->student_name;
        $this->type = $this->violation->violation_type_code_snapshot.' - '.$this->violation->violation_type_name_snapshot;
        $this->remark = $this->violation->violation_remark_snapshot;
        $this->status = $this->violation->status;

        $this->modal('approve-delete')->show();
    }

    public function delete(): void
    {
        $request = ViolationDeleteRequest::findOrFail($this->requestId);

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
