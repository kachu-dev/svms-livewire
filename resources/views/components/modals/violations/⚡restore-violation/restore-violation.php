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

    #[On('restore-violation')]
    public function setFields($id): void
    {
        $this->violation = Violation::onlyTrashed()->find($id);

        $this->studentId = $this->violation->student_id;
        $this->studentName = $this->violation->student_name;
        $this->type = $this->violation->violation_type_code_snapshot.' - '.$this->violation->violation_type_name_snapshot;
        $this->remark = $this->violation->violation_remark_snapshot;

        $this->modal('restore-violation')->show();
    }

    public function restore(): void
    {
        $this->violation->restore();

        $this->dispatch('refresh-del-violation');
    }
};
