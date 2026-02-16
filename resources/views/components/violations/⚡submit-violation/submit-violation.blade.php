<x-card header="Violation Details" icon="exclamation-triangle">
    <div class="flex flex-col gap-4">
        @if ($studentId)
            <flux:modal.trigger name="set-violation">
                <flux:input
                    label:size="lg"
                    label="Type of Violation"
                    placeholder="Click to choose violation"
                    readonly
                    size="lg"
                    wire:model="selectedTypeLabel"
                />
            </flux:modal.trigger>
        @else
            <flux:input
                disabled
                label:size="lg"
                label="Type of Violation"
                placeholder="Click to choose violation"
                readonly
                size="lg"
            />
        @endif

        @if ($selectedTypeId)
            <flux:modal.trigger name="set-remark">
                <flux:input
                    label:size="lg"
                    label="Remarks"
                    placeholder="Remarks"
                    readonly
                    size="lg"
                    wire:model="selectedRemarkLabel"
                />
            </flux:modal.trigger>
        @else
            <flux:input
                disabled
                label:size="lg"
                label="Remarks"
                placeholder="Remarks"
                readonly
                size="lg"
            />
        @endif

        <flux:button
            class="mt-4"
            icon="paper-airplane"
            size="lg"
            variant="primary"
            wire:click="confirmViolation"
        >
            <span wire:loading.remove wire:target="confirmViolation">Submit Violation</span>
            <span wire:loading wire:target="confirmViolation">Submitting...</span>
        </flux:button>
    </div>
</x-card>
