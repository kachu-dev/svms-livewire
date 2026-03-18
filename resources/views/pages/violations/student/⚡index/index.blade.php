<flux:card class="flex w-full flex-col gap-3 overflow-hidden rounded-lg border bg-white p-3 dark:bg-zinc-900">

    @php
        $violations = $this->violations();
        $pending = $violations->where('status', '!=', 'Complete')->count();
        $major = $violations->filter(fn($v) => strtolower($v->classification) !== 'minor')->count();
    @endphp

    @if ($violations->isNotEmpty())
        <div
            class="flex flex-wrap items-center gap-3 border-b border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-800 dark:bg-zinc-900">

            <span class="font-mono text-xs uppercase tracking-widest text-zinc-500 dark:text-zinc-400">
                {{ $violations->count() }} {{ Str::plural('violation', $violations->count()) }}
            </span>

            @if ($pending > 0)
                <span class="font-mono text-xs uppercase tracking-widest text-yellow-500">
                    {{ $pending }} pending
                </span>
            @endif

            @if ($major > 0)
                <span class="font-mono text-xs uppercase tracking-widest text-red-500">
                    {{ $major }} major
                </span>
            @endif
        </div>
    @endif

    @forelse ($violations as $violation)
        <div class="{{ strtolower($violation->classification) === 'minor' ? 'border-l-blue-500 dark:border-l-blue-400' : 'border-l-red-500' }} group relative flex flex-col gap-3 rounded-lg border-2 border-l-4 border-zinc-200 px-4 py-4 hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/40"
            wire:key="violation-{{ $violation->id }}"
        >

            <div class="flex items-start justify-between gap-3">

                <div class="flex min-w-0 flex-col gap-0.5">

                    <span class="font-mono text-xs font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">
                        {{ $violation->type_code }}
                    </span>

                    <p class="text-base font-bold leading-snug text-zinc-900 dark:text-zinc-100">
                        {{ $violation->type_name }}
                    </p>

                    @if ($violation->remark)
                        <p class="mt-1 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                            {{ $violation->remark }}
                        </p>
                    @endif

                </div>

                <div class="shrink-0 text-right">
                    <p class="font-mono text-xs tabular-nums text-zinc-500 dark:text-zinc-400">
                        {{ $violation->created_at->format('M d, Y') }}
                    </p>
                    <p class="font-mono text-xs tabular-nums text-zinc-400 dark:text-zinc-600">
                        {{ $violation->created_at->format('g:i A') }}
                    </p>
                </div>

            </div>

            <div class="flex flex-wrap items-center justify-between gap-2">

                <div class="flex flex-wrap items-center gap-2">
                    <flux:badge color="{{ strtolower($violation->classification) === 'minor' ? 'blue' : 'red' }}"
                        size="sm"
                    >{{ $violation->classification }}</flux:badge>

                    <flux:badge color="{{ $violation->status === 'Complete' ? 'green' : 'yellow' }}" size="sm">
                        {{ $violation->status === 'Complete' ? 'Complete' : 'Pending' }}</flux:badge>
                </div>

                @if ($violation->status !== 'Complete')
                    <div class="flex items-center gap-1.5">
                        <span class="size-2 animate-pulse rounded-full bg-yellow-400"></span>
                        <span class="font-mono text-xs uppercase tracking-widest text-yellow-500">Action required</span>
                    </div>
                @else
                    <flux:icon class="size-5 text-green-400 opacity-80" name="check-circle" />
                @endif

            </div>

        </div>

    @empty

        <div class="flex flex-col items-center gap-4 py-16 text-center">

            <div class="rounded-full bg-green-50 p-5 dark:bg-green-900/20">
                <flux:icon.shield-check class="size-10 text-green-500" />
            </div>

            <div>
                <div class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">
                    You're in good standing
                </div>
                <div class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    No violations on record
                </div>
            </div>

        </div>
    @endforelse

</flux:card>
