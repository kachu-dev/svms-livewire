<?php

use App\Models\Violation;
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

    #[On('delete-violation')]
    public function setFields($id): void
    {
        $this->violation = Violation::find($id);

        $this->studentId = $this->violation->student_id;
        $this->studentName = $this->violation->student_name;
        $this->type = $this->violation->violation_type_snapshot;
        $this->remark = $this->violation->violation_remark_snapshot;
        $this->status = $this->violation->status;

        $this->modal('delete-violation')->show();
    }

    public function delete(): void
    {
        $this->violation->delete();

        $this->redirectRoute('staff.violations.index');
    }
};
