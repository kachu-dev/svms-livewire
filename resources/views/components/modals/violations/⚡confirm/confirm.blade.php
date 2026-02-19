<div>
    <flux:modal class="w-full max-w-md sm:max-w-96 md:max-w-3xl" name="confirm-violation">
        <div class="space-y-6">
            <div class="text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                    <flux:icon class="h-6 w-6 text-red-600 dark:text-red-400" name="exclamation-triangle" />
                </div>
                <flux:heading class="mt-4" size="lg">Confirm Violation Submission</flux:heading>
                <flux:subheading class="mt-2">
                    Please review the details carefully before submitting
                </flux:subheading>
            </div>

            <div class="space-y-3 rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
                <div class="flex items-start justify-between border-b border-zinc-200 pb-2 dark:border-zinc-700">
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Student Name</flux:text>
                    <flux:text class="text-right text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                        {{ $this->student?->firstname ?? 'N/A' }} {{ $this->student?->lastname ?? 'N/A' }}
                    </flux:text>
                </div>

                <div class="flex items-start justify-between border-b border-zinc-200 pb-2 dark:border-zinc-700">
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Student ID</flux:text>
                    <flux:text class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                        {{ $this->student?->studentid ?? 'N/A' }}
                    </flux:text>
                </div>

                <div class="flex items-start justify-between border-b border-zinc-200 pb-2 dark:border-zinc-700">
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Violation Type</flux:text>
                    <flux:text class="max-w-xs text-right text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                        {{ $selectedTypeLabel ?: 'N/A' }}
                    </flux:text>
                </div>

                <div class="flex items-start justify-between">
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Remarks</flux:text>
                    <flux:text class="max-w-xs text-right text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                        {{ $selectedRemarkLabel ?: 'N/A' }}
                    </flux:text>
                </div>
            </div>

            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-900/20">
                <div class="flex gap-3">
                    <flux:icon class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-600 dark:text-amber-400"
                               name="information-circle"
                    />
                    <div>
                        <flux:text class="text-sm font-medium text-amber-800 dark:text-amber-200">
                            Are you sure you want to submit this violation?
                        </flux:text>
                        <flux:text class="mt-1 text-xs text-amber-700 dark:text-amber-300">
                            This action will be recorded in the student's permanent record.
                        </flux:text>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <flux:modal.close class="flex-1">
                    <flux:button class="w-full" variant="ghost">
                        Cancel
                    </flux:button>
                </flux:modal.close>
                <flux:button
                    class="flex-1"
                    icon="paper-airplane"
                    variant="danger"
                    wire:click="save"
                    wire:loading.attr="disabled"
                    wire:target="save"
                >
                    <span wire:loading.remove wire:target="save">Confirm & Submit</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal class="w-full max-w-md sm:max-w-96" name="duplicate-warning">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Duplicate Record</flux:heading>
                <flux:subheading>This student has already been recorded for this violation today.</flux:subheading>
            </div>

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Close</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" wire:click="$dispatch('duplicate-override-confirmed')">Confirm</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
