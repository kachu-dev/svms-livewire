<?php

use App\Models\Student;
use Livewire\Component;

new class extends Component
{
    public $rfid;

    public $student;

    protected function detectInputType(string $input): string
    {
        $input = trim($input);

        if (ctype_digit($input) && strlen($input) >= 8 && strlen($input) <= 10) {
            return 'rfid_decimal';
        }

        if (ctype_xdigit($input) && strlen($input) === 8) {
            return 'rfid_hex';
        }

        return 'student_id';
    }

    protected function normalizeRfid(string $input): string
    {
        $input = trim($input);

        if (ctype_digit($input)) {
            $hex = strtoupper(dechex((int) $input));
            $hex = str_pad($hex, 8, '0', STR_PAD_LEFT);

            return implode('', array_reverse(str_split($hex, 2)));
        }

        if (ctype_xdigit($input)) {
            return strtoupper($input);
        }

        throw new InvalidArgumentException('Not an RFID value');
    }

    public function findStudent(): void
    {
        $input = trim((string) $this->rfid);

        switch ($this->detectInputType($input)) {

            case 'rfid_decimal':
            case 'rfid_hex':
                $rfid = $this->normalizeRfid($input);
                $this->student = Student::where('rfid_uid', $rfid)->first();
                break;

            case 'student_id':
                $this->student = Student::where('student_id', $input)->first();
                break;
        }
    }
};
