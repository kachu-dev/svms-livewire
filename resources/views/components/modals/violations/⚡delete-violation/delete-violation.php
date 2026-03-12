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
        $this->type = $this->violation->type_code.' - '.$this->violation->type_name;
        $this->remark = $this->violation->remark;
        $this->status = $this->violation->status;

        $this->modal('delete-violation')->show();
    }

    public function delete(): void
    {
        $this->violation->delete();

        $this->dispatch('refresh-violation');
    }
};
