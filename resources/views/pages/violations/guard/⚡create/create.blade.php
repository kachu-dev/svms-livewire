<div class="flex h-[calc(100vh-64px)] flex-col gap-4">
    <div class="grid flex-1 grid-cols-[2fr_1fr] gap-4">
        <div class="grid gap-4">
            <livewire:violations.search-student />

            <livewire:violations.submit-violation />
        </div>

        <livewire:violations.display-student />
    </div>

    <div class="rounded border-t-4 border-t-blue-500 bg-white shadow dark:bg-zinc-900" wire:ignore>
        <div class="p-5">
            <div class="flex items-center justify-center gap-8">
                <div class="flex items-center gap-2">
                    <flux:icon
                        class="size-8"
                        name="calendar"
                        variant="solid"
                    />
                    <p class="text-3xl font-semibold" id="current-date"></p>
                </div>
                <div class="flex items-center gap-2">
                    <flux:icon
                        class="size-8"
                        name="clock"
                        variant="solid"
                    />
                    <p class="text-3xl font-semibold" id="current-time"></p>
                </div>
            </div>
        </div>
    </div>

    <livewire:violations.modals.confirm />
    <livewire:violations.modals.remark />
    <livewire:violations.modals.type />
    <livewire:violations.modals.results />
</div>
