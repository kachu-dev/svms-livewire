<flux:card class="border rounded-lg bg-white dark:bg-zinc-900 p-5 gap-4 flex w-full flex-col overflow-hidden">

    @php
        $violations = $this->violations();
        $pending = $violations->where('status', '!=', 'Complete')->count();
        $major = $violations->filter(fn($v) => strtolower($v->classification) !== 'minor')->count();
    @endphp

    @if($violations->isNotEmpty())
        <div class="flex items-center gap-5 border-b border-zinc-200 bg-zinc-50 px-6 py-4 dark:border-zinc-800 dark:bg-zinc-900">

            <span class="font-mono text-base uppercase tracking-widest text-zinc-500 dark:text-zinc-400">
                {{ $violations->count() }} {{ Str::plural('violation', $violations->count()) }}
            </span>

            @if($pending > 0)
                <flux:separator vertical />
                <span class="font-mono text-base uppercase tracking-widest text-yellow-500">
                    {{ $pending }} pending
                </span>
            @endif

            @if($major > 0)
                <flux:separator vertical />
                <span class="font-mono text-base uppercase tracking-widest text-red-500">
                    {{ $major }} major
                </span>
            @endif

            <div class="ml-auto flex gap-2">
                @foreach($violations as $v)
                    <span
                        title="{{ $v->type_code }}"
                        class="h-2.5 w-2.5 rounded-full {{ strtolower($v->classification) !== 'minor' ? 'bg-red-500' : 'bg-blue-400' }}"
                    ></span>
                @endforeach
            </div>

        </div>
    @endif

    @forelse ($violations as $violation)

        <div
            class="group relative flex flex-col gap-4 rounded-lg border-2 border-l-4 px-6 py-5
            {{ strtolower($violation->classification) === 'minor' ? 'border-l-blue-500 dark:border-l-blue-400' : 'border-l-red-500 dark:border-l-red-500' }}
            border-zinc-200 hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/40"
            wire:key="violation-{{ $violation->id }}"
        >

            <div class="flex items-start justify-between gap-6">

                <div class="flex flex-col gap-1">

                    <span class="font-mono text-base font-bold tracking-widest text-zinc-500 dark:text-zinc-400">
                        {{ $violation->type_code }}
                    </span>

                    <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                        {{ $violation->type_name }}
                    </p>

                    <p class="text-base leading-relaxed text-zinc-600 dark:text-zinc-400 max-w-2xl">
                        {{ $violation->remark }}
                    </p>

                </div>

                <div class="shrink-0 text-right">

                    <p class="font-mono text-base tabular-nums text-zinc-500 dark:text-zinc-400">
                        {{ $violation->created_at->format('M d, Y') }}
                    </p>

                    <p class="font-mono text-sm tabular-nums text-zinc-400 dark:text-zinc-600">
                        {{ $violation->created_at->format('g:i A') }}
                    </p>

                </div>

            </div>

            <div class="flex items-center justify-between">

                <div class="flex items-center gap-3">

                    <flux:badge size="lg" color="{{ strtolower($violation->classification) === 'minor' ? 'blue' : 'red' }}">
                        {{ $violation->classification }}
                    </flux:badge>

                    <flux:badge size="lg" color="{{ $violation->status === 'Complete' ? 'green' : 'yellow' }}">
                        {{ $violation->status === 'Complete' ? 'Complete' : 'Pending' }}
                    </flux:badge>

                </div>

                @if($violation->status !== 'Complete')

                    <div class="flex items-center gap-2.5">
                        <span class="size-2.5 animate-pulse rounded-full bg-yellow-400"></span>

                        <span class="font-mono text-base uppercase tracking-widest text-yellow-500">
                            Action required
                        </span>
                    </div>

                @else

                    <flux:icon name="check-circle" class="size-6 text-green-400 opacity-80" />

                @endif

            </div>

        </div>

    @empty

        <div class="flex flex-col items-center gap-5 py-20 text-center">

            <div class="rounded-full bg-green-50 p-6 dark:bg-green-900/20">
                <flux:icon.shield-check class="size-12 text-green-500" />
            </div>

            <div>
                <div class="text-xl font-semibold text-zinc-800 dark:text-zinc-200">
                    You're in good standing
                </div>

                <div class="mt-1 text-base text-zinc-500 dark:text-zinc-400">
                    No violations on record
                </div>
            </div>

        </div>

    @endforelse

</flux:card>
