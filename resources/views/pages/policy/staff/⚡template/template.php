<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\ViolationStageTemplate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => 'Template Management'])] class extends Component
{
    public string $newName = '';

    public string $newOffenseKey = '';

    public ?int $editingId = null;

    public string $editingName = '';

    public array $offenseKeys = [
        'minor_1',
        'minor_2',
        'minor_3',
        'major_suspension',
        'major_dismissal',
        'major_expulsion',
    ];

    #[Computed]
    public function grouped()
    {
        return collect($this->offenseKeys)->mapWithKeys(fn ($key) => [
            $key => ViolationStageTemplate::where('offense_key', $key)
                ->orderBy('order')
                ->get(),
        ]);
    }

    public function moveStage(int $item, int $position): void
    {
        $stage = ViolationStageTemplate::findOrFail($item);
        $stage->move($position);
    }

    public function createStage(): void
    {
        $this->validate([
            'newName' => 'required|string|max:255',
            'newOffenseKey' => 'required|in:'.implode(',', $this->offenseKeys),
        ]);

        ViolationStageTemplate::create([
            'offense_key' => $this->newOffenseKey,
            'name' => $this->newName,
        ]);

        $this->reset('newName', 'newOffenseKey');
    }

    public function startEdit(int $id): void
    {
        $this->editingId = $id;
        $this->editingName = ViolationStageTemplate::findOrFail($id)->name;
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editingName' => 'required|string|max:255',
        ]);

        ViolationStageTemplate::findOrFail($this->editingId)
            ->update(['name' => $this->editingName]);

        $this->reset('editingId', 'editingName');
    }

    public function cancelEdit(): void
    {
        $this->reset('editingId', 'editingName');
    }

    public function deleteStage(int $id): void
    {
        ViolationStageTemplate::findOrFail($id)->delete();
    }
};
