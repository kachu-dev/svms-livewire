<flux:modal name="set-remark" class="w-full max-w-md sm:max-w-96 md:max-w-3xl">
    <div class="space-y-6">

        <div>
            <flux:heading size="lg">Choose Remarks</flux:heading>
            <flux:subheading>Select or make custom remarks</flux:subheading>
        </div>

        <form wire:submit.prevent="setCustomRemark" />
        <flux:input
            label="Custom Remark"
            wire:model="customRemark"
            placeholder="Type your custom remark here..."
        ></flux:input>

        <flux:button
            type="submit"
            variant="primary"
            icon="paper-airplane"
            class="mt-3"
            size="sm"
        >
            Choose Remark
        </flux:button>
        </form>

        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
            Preset Remark
        </label>
        <div class="max-h-125 space-y-4 overflow-y-auto">
            <div>
                <div class="space-y-2">
                    <button
                        type="button"
                        wire:click="setRemark(null)"
                        class="group w-full rounded-lg border-2 border-zinc-200 p-4 text-left transition-all hover:border-blue-500 hover:bg-blue-50 dark:border-zinc-700 dark:hover:bg-blue-950/30"
                    >
                        <div class="flex items-start gap-3">
                            <div
                                class="flex-1 text-sm text-zinc-600 group-hover:text-zinc-900 dark:text-zinc-400 dark:group-hover:text-zinc-100">
                                None (No specific remarks)
                            </div>
                        </div>
                    </button>

                    @foreach ($this->violationRemarks as $remark)
                        <button
                            type="button"
                            wire:click="setRemark({{ $remark->id }})"
                            class="group w-full rounded-lg border-2 border-zinc-200 p-4 text-left transition-all hover:border-blue-500 hover:bg-blue-50 dark:border-zinc-700 dark:hover:bg-blue-950/30"
                        >
                            <div class="flex items-start gap-3">
                                <div
                                    class="flex-1 text-sm text-zinc-600 group-hover:text-zinc-900 dark:text-zinc-400 dark:group-hover:text-zinc-100">
                                    {{ $remark->label }}
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex gap-2 border-t border-zinc-200 pt-4 dark:border-zinc-700">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
