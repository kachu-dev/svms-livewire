<x-card header="Violation Details" icon="exclamation-triangle">
    <div class="flex flex-col gap-4">
        @if ($this->student)
            <flux:modal.trigger name="set-violation">
                <flux:input
                    readonly
                    label="Type of Violation"
                    size="lg"
                    wire:model="selectedTypeLabel"
                    placeholder="Click to choose violation"
                />
            </flux:modal.trigger>
        @else
            <flux:input
                readonly
                disabled
                label="Type of Violation"
                size="lg"
                wire:model="selectedTypeLabel"
                placeholder="Click to choose violation"
            />
        @endif

        @if ($this->selectedTypeId)
            <flux:modal.trigger name="set-remark">
                <flux:input
                    readonly
                    label="Remarks"
                    size="lg"
                    wire:model="selectedRemarkLabel"
                    placeholder="Remarks"
                />
            </flux:modal.trigger>
        @else
            <flux:input
                readonly
                disabled
                label="Remarks"
                size="lg"
                wire:model="selectedRemarkLabel"
                placeholder="Remarks"
            />
        @endif

        <div class="mt-4 flex gap-2">
            <flux:button
                wire:click="confirmViolation()"
                type="submit"
                variant="primary"
                icon="paper-airplane"
                class="flex-1"
                size="lg"
                wire:loading.attr="disabled"
                wire:target="confirmViolation"
            >
                <span wire:loading.remove wire:target="confirmViolation">Submit Violation</span>
                <span wire:loading wire:target="confirmViolation">Submitting...</span>
            </flux:button>
        </div>
    </div>
</x-card>
