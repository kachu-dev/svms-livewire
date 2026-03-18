<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::app', ['title' => 'Testing'])] class extends Component
{
    use WithFileUploads;

    public $image;

    public $studentId;

    public $rfid;

    public $firstname;

    public $lastname;

    public $mi;

    public function updateDatabases(): void
    {
        $this->validate([
            'studentId' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'mi' => 'required',
        ]);

        if ($this->rfid) {
            $this->normalizeRfid($this->rfid);
        }

        /* dd(random_int(10000,100000)); */

        DB::connection('imagedb')
            ->table('pictures')
            ->updateOrInsert(
                ['idnumber' => $this->studentId],
                [
                    'imgid' => random_int(500000, 599999),
                    'idpicture' => $this->image ? file_get_contents($this->image->getRealPath()) : null,
                    'idgroup' => 'co',
                    'ts' => now(),
                ]
            );

        DB::connection('collegedb')
            ->table('stud_program')
            ->updateOrInsert(
                [
                    'sp_idnum' => $this->studentId,
                ],
                [
                    'sprog_id' => strtoupper(Str::random(9)),
                    'sp_pr_acronym' => '0067',
                    'sp_year' => 4,
                    'sp_status' => 'OLD STUDENT',
                    'sp_sy' => '2025-2026',
                    'sp_sem' => 2,
                    'ts' => now(),
                    'sp_group' => '',
                    'sp_sreg' => 1,
                    'sp_online' => 1,
                    'sp_graduating' => 0,
                    'sp_graduation_type' => 1,
                    'sp_graduation_disqualified' => 0,
                    'sp_graduation_batch' => 0,
                    'sp_graduation_remarks' => '',
                    'sp_no_retreat' => 0,
                    'lll_type' => 0,
                    'sp_special_case' => 0,
                    'sp_special_remarks' => '',
                ]
            );

        DB::connection('arturn_db')
            ->table('profile')
            ->updateOrInsert(
                ['studentid' => $this->studentId],
                [
                    'rfidtag' => $this->rfid ?? random_int(10000000, 99999999),
                    'firstname' => strtoupper((string) $this->firstname),
                    'lastname' => strtoupper((string) $this->lastname),
                    'mi' => strtoupper((string) $this->mi),
                    'grouptag' => 'co',
                    'hextag' => 'F004B',
                    'expdate' => '2026-08-31',
                    'regdatetime' => '2026-02-09 16:42:58',
                    'cmdate' => now(),
                ]
            );

        $this->reset(['image', 'studentId', 'firstname', 'lastname', 'mi']);
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
