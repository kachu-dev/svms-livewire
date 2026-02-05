<div class="flex h-[calc(100vh-(64px+67px))] flex-col gap-4">
    <div class="grid flex-1 grid-cols-[2fr_1fr] gap-4">
        <div class="grid gap-4">
            <livewire:create.search-student />

            <livewire:create.submit-violation />
        </div>

        <livewire:create.display-student />
    </div>

    {{-- Bottom Clock --}}
    <div wire:ignore class="rounded border-t-4 border-t-blue-500 shadow dark:bg-zinc-900">
        <div class="p-5">
            <div class="flex items-center justify-center gap-8">
                <div class="flex items-center gap-2">
                    <flux:icon
                        name="calendar"
                        variant="solid"
                        class="size-8"
                    />
                    <p class="text-3xl font-semibold" id="current-date"></p>
                </div>
                <div class="flex items-center gap-2">
                    <flux:icon
                        name="clock"
                        variant="solid"
                        class="size-8"
                    />
                    <p class="text-3xl font-semibold" id="current-time"></p>
                </div>
            </div>
        </div>
    </div>
</div>
