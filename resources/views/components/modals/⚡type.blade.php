<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use App\Models\ViolationType;

new class extends Component
{
    public $typeSearch;

    public $selectedTypeId;
    public $selectedTypeLabel;

    #[Computed]
    public function filteredTypes()
    {
        $query = ViolationType::query();

        if ($this->typeSearch) {
            $query->where(function($q) {
                $q->where('code', 'like', '%' . $this->typeSearch . '%')
                    ->orWhere('name', 'like', '%' . $this->typeSearch . '%');
            });
        }
        return $query->get()->groupBy('classification');
    }

    public function setType(int $id): void
    {
        /*$violation = ViolationType::findOrFail($id);

        $this->selectedTypeId = $violation->id;
        $this->selectedTypeLabel = "{$violation->code} — {$violation->name}";*/

        $this->dispatch('type-selected', violationId: $id);
        $this->modal('set-violation')->close();
    }
};
?>

<flux:modal name="set-violation" class="w-full max-w-md sm:max-w-96 md:max-w-3xl">
    <div class="space-y-6">

        <div>
            <flux:heading size="lg">Choose Violation Type</flux:heading>
            <flux:subheading>Search and select from the list of violations</flux:subheading>
        </div>

        <flux:input
            wire:model.live.debounce.300ms="typeSearch"
            placeholder="Type to search violations..."
            icon="magnifying-glass"
        />

        <div class="max-h-125 overflow-y-auto space-y-4 pr-2">
            @forelse($this->filteredTypes as $category => $types)
                <div>
                    <flux:subheading class="sticky rounded-lg shadow mb-3 top-0 bg-white dark:bg-zinc-900 p-4 mt-2">
                        {{ $category }}
                    </flux:subheading>

                    <div class="space-y-2">
                        @foreach($types as $type)
                            <button
                                type="button"
                                wire:click="setType({{ $type->id }})"
                                class="w-full text-left p-4 rounded-lg border-2 border-zinc-200 dark:border-zinc-700 hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-all group"
                            >
                                <div class="flex items-start gap-3">
                                    <div class="font-bold text-sm text-blue-600 dark:text-blue-400 min-w-15">
                                        {{ $type->code }}
                                    </div>
                                    <div class="text-sm text-zinc-600 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-zinc-100 flex-1">
                                        {{ $type->name }}
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <flux:icon name="magnifying-glass" class="size-12 mx-auto mb-4 text-zinc-400" />
                    <flux:subheading>No violations found</flux:subheading>
                    <flux:text>Try searching with different keywords</flux:text>
                </div>
            @endforelse
        </div>

        <div class="flex gap-2 pt-4 border-t border-zinc-200 dark:border-zinc-700">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
