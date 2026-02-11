<?php

use App\Models\Student;
use Livewire\Component;

new class extends Component
{
    public $studentId = '';

    public function findStudent(): void
    {
        $this->validate(['studentId' => 'required']);

        $input = trim((string) $this->studentId);

        $student = $this->isRfid($input)
            ? Student::where('rfidtag', $this->normalizeRfid($input))->first()
            : Student::where('studentid', $input)->first();

        if ($student) {
            $this->dispatch('student-found', studentId: $student->studentid);
        } else {
            $this->dispatch('student-not-found');
        }

        $this->reset('studentId');
    }

    protected function isRfid(string $input): bool
    {
        return (ctype_digit($input) && strlen($input) >= 8 && strlen($input) <= 10)
            || (ctype_xdigit($input) && strlen($input) === 8);
    }

    protected function normalizeRfid(string $input): string
    {
        if (ctype_digit($input)) {
            $hex = str_pad(strtoupper(dechex((int) $input)), 8, '0', STR_PAD_LEFT);

            return implode('', array_reverse(str_split($hex, 2)));
        }

        return strtoupper($input);
    }
};
