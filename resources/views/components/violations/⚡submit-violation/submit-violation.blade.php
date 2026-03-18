<x-card header="Step 3: Choose Violation Details" icon="exclamation-triangle">
    <div class="flex flex-col gap-10">
        @if ($studentId)
            <div class="flex flex-col gap-2">
                <flux:modal.trigger name="set-violation">
                    <flux:input
                        placeholder="Click to choose violation"
                        readonly
                        size="{{ $size }}"
                        wire:model="selectedTypeLabel"
                    />
                </flux:modal.trigger>
            </div>

            <div class="flex flex-col gap-2">
                @if ($selectedTypeId)
                    <flux:modal.trigger name="set-remark">
                        <flux:input
                            placeholder="Click to choose remark"
                            readonly
                            size="{{ $size }}"
                            wire:model="selectedRemarkLabel"
                        />
                    </flux:modal.trigger>
                @else
                    <flux:input
                        placeholder="Choose a violation type first"
                        readonly
                        size="{{ $size }}"
                    />
                @endif
            </div>

            <flux:button
                :disabled="!$selectedTypeId"
                class="w-full"
                icon="paper-airplane"
                size="{{ $size }}"
                variant="primary"
                wire:click="confirmViolation"
            >
                <span wire:loading.remove wire:target="confirmViolation">Submit Violation</span>
                <span wire:loading wire:target="confirmViolation">Submitting...</span>
            </flux:button>
        @else
            <div class="flex flex-col items-center justify-center gap-3 py-12 text-center">
                <flux:icon class="size-14" name="user-circle" />
                <p class="text-2xl font-semibold">No student selected</p>
                <p class="text-xl">Complete Step 1 first to enable this section.</p>
            </div>
        @endif
    </div>
</x-card>
