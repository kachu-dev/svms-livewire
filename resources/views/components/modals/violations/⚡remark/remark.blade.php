<flux:modal name="set-remark" class="w-full max-w-md sm:max-w-96 md:max-w-3xl">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Choose Remarks</flux:heading>
            <flux:subheading>Select or make custom remarks</flux:subheading>
        </div>

        <div class="space-y-2">
            <flux:input
                wire:model="customRemark"
                label="Custom Remark"
                placeholder="Enter custom remark"
            />

            <flux:button wire:click="setCustomRemark" variant="primary">
                Save Custom Remark
            </flux:button>
        </div>

        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
            Preset Remark
        </label>
        <div class="max-h-125 space-y-4 overflow-y-auto">
            <div>
                <div class="space-y-2">
                    <button
                        type="button"
                        @click="$dispatch('remark-selected', {
                            remarkId: null,
                            remarkLabel: 'None'
                        }); $flux.modal('set-remark').close()"
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
                            wire:key="remark-{{ $remark->id }}"
                            type="button"
                            @click="$dispatch('remark-selected', {
                                remarkId: {{ $remark->id }},
                                remarkLabel: @js($remark->label)
                            }); $flux.modal('set-remark').close()"
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
