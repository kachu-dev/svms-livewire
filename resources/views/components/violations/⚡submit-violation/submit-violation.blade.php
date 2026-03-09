<x-card header="Step 3: Choose Violation Details" icon="exclamation-triangle">
    <div class="flex flex-col gap-4">
        @if ($studentId)
            <flux:modal.trigger name="set-violation">
                <flux:input
                    label:size="lg"
                    label="Type of Violation"
                    placeholder="Click to choose violation"
                    readonly
                    size="accessible"
                    variant="accessible"
                    wire:model="selectedTypeLabel"
                />
            </flux:modal.trigger>

            @if ($selectedTypeId)
                <flux:modal.trigger name="set-remark">
                    <flux:input
                        label:size="lg"
                        label="Remarks"
                        placeholder="Click to choose remark"
                        readonly
                        size="accessible"
                        variant="accessible"
                        wire:model="selectedRemarkLabel"
                    />
                </flux:modal.trigger>
            @else
                <flux:input
                    disabled
                    label:size="lg"
                    label="Remarks"
                    placeholder="Choose a violation type first"
                    readonly
                    size="accessible"
                    variant="accessible"
                />
            @endif

            <flux:button
                :disabled="!$selectedTypeId"
                icon="paper-airplane"
                size="lg"
                variant="primary"
                wire:click="confirmViolation"
            >
                <span wire:loading.remove wire:target="confirmViolation">Submit Violation</span>
                <span wire:loading wire:target="confirmViolation">Submitting...</span>
            </flux:button>
        @else
            <div class="flex flex-col items-center justify-center gap-3 py-12 text-center">
                <flux:icon class="h-12 w-12 text-gray-400" name="x-mark" />
                <flux:heading size="xl">No student selected</flux:heading>
                <flux:text class="text-xl">
                    Scan or search for a student first before recording a violation.
                </flux:text>
            </div>
        @endif
    </div>
</x-card>
