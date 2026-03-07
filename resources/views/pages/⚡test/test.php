<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::app', ['title' => 'Testing'])] class extends Component
{
    use WithFileUploads;

    public $image;

    public $studentId;

    public function updatePicture(): void
    {
        $this->validate([
            'image' => 'required|image|max:5120',
            'studentId' => 'required',
        ]);

        DB::connection('imagedb')
            ->table('pictures')
            ->where('idnumber', $this->studentId)
            ->update([
                'idpicture' => file_get_contents($this->image->getRealPath()),
                'ts' => now(),
            ]);

        $this->image = null;
        $this->studentId = '';
    }
};
