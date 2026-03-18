<flux:modal class="w-full max-w-md sm:max-w-96 md:max-w-3xl" name="set-remark">
    <div class="space-y-6">
        <div>
            <flux:heading size="xl">Choose Remarks</flux:heading>
            <flux:subheading class="text-base">Select from the list or enter a custom remark</flux:subheading>
        </div>

        <flux:separator />

        <div class="space-y-3">
            <flux:input
                class="text-base"
                label:size="lg"
                label="Custom Remark"
                placeholder="Enter custom remark"
                size="accessible"
                variant="accessible"
                wire:model="customRemark"
            />

            <flux:button
                size="lg"
                variant="primary"
                wire:click="setCustomRemark"
            >
                Save Custom Remark
            </flux:button>
        </div>

        <flux:subheading class="text-lg font-bold">
            Preset Remarks
        </flux:subheading>

        <div class="max-h-125 space-y-3 overflow-y-auto">
            <button
                @click="$dispatch('remark-selected', {
                    remarkId: null,
                    remarkLabel: null
                }); $flux.modal('set-remark').close()"
                class="group w-full rounded-xl border-2 border-zinc-500 p-5 text-left transition-all hover:border-blue-500 hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-400 active:scale-95 dark:border-zinc-600 dark:hover:bg-blue-950/30"
                type="button"
            >
                <div
                    class="text-base font-medium text-zinc-700 group-hover:text-zinc-900 dark:text-zinc-300 dark:group-hover:text-zinc-100">
                    None (No specific remarks)
                </div>
            </button>

            @foreach ($this->violationRemarks as $remark)
                <button
                    :class="{{ $remark->id }} === $wire.selectedRemarkId ?
                        'border-blue-500 bg-blue-50 dark:bg-blue-950/30' :
                        'border-zinc-500 hover:border-blue-500 hover:bg-blue-50 dark:border-zinc-600 dark:hover:bg-blue-950/30'"
                    @click="
                    $wire.selectedRemarkId = {{ $remark->id }};
                    $dispatch('remark-selected', {
                        remarkId: {{ $remark->id }},
                        remarkLabel: @js($remark->label)
                    }); $flux.modal('set-remark').close()"
                    class="group w-full rounded-xl border-2 p-5 text-left transition-all focus:outline-none focus:ring-4 focus:ring-blue-400 active:scale-95"
                    type="button"
                    wire:key="remark-{{ $remark->id }}"
                >
                    <div class="flex items-center justify-between">
                        <p class="text-base">
                            {{ $remark->label }}
                        </p class="text-base">
                        <flux:icon
                            class="h-5 w-5 text-blue-500"
                            name="check-circle"
                            x-show="{{ $remark->id }} === $wire.selectedRemarkId"
                        />
                    </div>
                </button>
            @endforeach
        </div>

        <div class="flex gap-2 border-t border-zinc-200 pt-4 dark:border-zinc-700">
            <flux:spacer />
            <flux:modal.close>
                <flux:button size="lg" variant="ghost">Cancel</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
