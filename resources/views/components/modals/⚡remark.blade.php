<?php

use App\Models\ViolationRemark;
use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $selectedTypeId;

    #[Computed]
    public function violationRemarks()
    {
        if (!$this->selectedTypeId) {
            return collect();
        }

        return ViolationType::find($this->selectedTypeId)
            ->remarks;
    }

    #[On('type-selected')]
    public function setType($violationId): void
    {
        $this->selectedTypeId = $violationId;
    }

    public function setRemark(?int $id): void
    {
        $this->dispatch('remark-selected', remarkId: $id);
        $this->modal('set-remark')->close();
    }
};
?>

<flux:modal name="set-remark" class="w-full max-w-md sm:max-w-96 md:max-w-3xl">
    <div class="space-y-6">

        <div>
            <flux:heading size="lg">Choose Remarks</flux:heading>
            <flux:subheading>Select or make custom remarks</flux:subheading>
        </div>

        <div class="max-h-125 overflow-y-auto space-y-4 pr-2">
            <div>
                <div class="space-y-2">
                    <button
                        type="button"
                        wire:click="setRemark(null)"
                        class="w-full text-left p-4 rounded-lg border-2 border-zinc-200 dark:border-zinc-700 hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-all group"
                    >
                        <div class="flex items-start gap-3">
                            <div
                                class="text-sm text-zinc-600 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-zinc-100 flex-1">
                                None (No specific remarks)
                            </div>
                        </div>
                    </button>

                    @foreach($this->violationRemarks as $remark)
                        <button
                            type="button"
                            wire:click="setRemark({{ $remark->id }})"
                            class="w-full text-left p-4 rounded-lg border-2 border-zinc-200 dark:border-zinc-700 hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-all group"
                        >
                            <div class="flex items-start gap-3">
                                <div
                                    class="text-sm text-zinc-600 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-zinc-100 flex-1">
                                    {{ $remark->label }}
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex gap-2 pt-4 border-t border-zinc-200 dark:border-zinc-700">
            <flux:spacer/>
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
