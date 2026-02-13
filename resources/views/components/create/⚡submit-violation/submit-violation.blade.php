<x-card header="Violation Details" icon="exclamation-triangle">
    <div class="flex flex-col gap-4">
        @if ($studentId)
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
                placeholder="Click to choose violation"
            />
        @endif

        {{-- Remarks Input --}}
        @if ($selectedTypeId)
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
                placeholder="Remarks"
            />
        @endif

        <flux:button
            wire:click="confirmViolation"
            variant="primary"
            icon="paper-airplane"
            class="mt-4"
            size="lg"
        >
            <span wire:loading.remove wire:target="confirmViolation">Submit Violation</span>
            <span wire:loading wire:target="confirmViolation">Submitting...</span>
        </flux:button>
    </div>
</x-card>
