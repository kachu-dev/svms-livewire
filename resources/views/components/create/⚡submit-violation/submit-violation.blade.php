<x-card header="Violation Details" icon="exclamation-triangle">
        <div class="flex flex-col gap-4">
            <!--wire:submit="save"-->
            <flux:modal.trigger name="set-violation">
                <flux:input
                    readonly
                    :disabled="!$this->student"
                    label="Type of Violation"
                    wire:model="selectedTypeLabel"
                    placeholder="Click to choose violation"
                />
            </flux:modal.trigger>

            <flux:modal.trigger name="set-remark">
                <flux:input
                    readonly
                    :disabled="!$this->student"
                    label="Remarks"
                    wire:model="selectedRemarkLabel"
                    placeholder="Remarks"
                />
            </flux:modal.trigger>

            @if($this->student)
                <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 rounded-lg p-3">
                    <flux:text class="text-sm text-green-800 dark:text-green-200">
                        ✓ Student found: {{ $this->student->name }}
                    </flux:text>
                </div>
            @elseif($notFound)
                <div class="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900 rounded-lg p-3">
                    <flux:text class="text-sm text-red-800 dark:text-red-200">
                        ✗ Student not found. Please check the ID and try again.
                    </flux:text>
                </div>
            @endif

            <div class="flex mt-4 gap-2">
                <flux:button
                    wire:click="confirm"
                    type="submit"
                    variant="primary"
                    icon="paper-airplane"
                    class="flex-1"
                >
                    Submit Violation
                </flux:button>
            </div>
        </div>

        <livewire:modals.type/>
        <livewire:modals.remark/>
        <livewire:modals.confirm/>
</x-card>
