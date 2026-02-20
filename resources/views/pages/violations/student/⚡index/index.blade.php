<div>
    <x-card header="My Violations" icon="exclamation-triangle">
        <div class="flex flex-col items-center gap-8">
            <div class="flex w-full flex-col gap-3">
                @foreach ($this->violations() as $violation)
                    <flux:card class="px-4 py-3 shadow" wire:key="violation-{{ $violation->id }}">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex min-w-0 items-center gap-3">
                                <span
                                    class="text-sm font-bold text-zinc-900 dark:text-white">{{ $violation->student_id }}</span>
                                <span
                                    class="truncate text-sm font-bold text-zinc-900 dark:text-white">{{ $violation->student_name }}</span>
                            </div>
                            <span
                                class="text-sm text-zinc-400 dark:text-zinc-500">{{ $violation->created_at->format('M d, Y g:i A') }}</span>
                        </div>
                        <div class="mt-3 space-y-2">
                            <div class="text-sm font-medium text-zinc-900 dark:text-white">
                                {{ $violation->violation_type_snapshot }}</div>
                            <div class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $violation->violation_remark_snapshot }}</div>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        </div>
    </x-card>
</div>
