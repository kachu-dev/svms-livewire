<x-card header="Violation Details" icon="exclamation-triangle">
    <div class="flex flex-col gap-4">
        <!--wire:submit="save"-->
        <flux:modal.trigger name="set-violation">
            <flux:input
                readonly
                :disabled="!$this->student"
                label="Type of Violation"
                size="lg"
                wire:model="selectedTypeLabel"
                placeholder="Click to choose violation"
            />
        </flux:modal.trigger>

        <flux:modal.trigger name="set-remark">
            <flux:input
                readonly
                :disabled="!$this->student"
                label="Remarks"
                size="lg"
                wire:model="selectedRemarkLabel"
                placeholder="Remarks"
            />
        </flux:modal.trigger>

        @if ($this->student)
            <div class="rounded-lg border border-green-200 bg-green-50 p-3 dark:border-green-900 dark:bg-green-950/30">
                <flux:text class="text-sm text-green-800 dark:text-green-200">
                    ✓ Student found: {{ $this->student->name }}
                </flux:text>
            </div>
        @elseif($notFound)
            <div class="rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-900 dark:bg-red-950/30">
                <flux:text class="text-sm text-red-800 dark:text-red-200">
                    ✗ Student not found. Please check the ID and try again.
                </flux:text>
            </div>
        @endif

        <div class="mt-4 flex gap-2">
            <flux:button
                wire:click="confirm"
                type="submit"
                variant="primary"
                icon="paper-airplane"
                class="flex-1"
                size="lg"
            >
                Submit Violation
            </flux:button>
        </div>
    </div>

    <livewire:modals.type />
    <livewire:modals.remark />
    <livewire:modals.confirm />
</x-card>
