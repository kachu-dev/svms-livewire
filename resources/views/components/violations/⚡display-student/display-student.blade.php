<x-card
    class="flex flex-col items-center gap-6"
    header="Step 2: Check Student Information"
    icon="user-circle"
>
    <div class="aspect-square w-full max-w-64 rounded-2xl" wire:transition>
        @if (!$this->student)
            <div
                class="flex h-full w-full items-center justify-center rounded-2xl border-4 border-zinc-500 bg-zinc-100 p-1.5 dark:bg-zinc-800">
                <flux:icon
                    class="size-5 h-full w-full text-zinc-600 dark:text-zinc-400"
                    name="user"
                    variant="mini"
                />
            </div>
        @elseif ($this->student->photo)
            <img
                alt="Photo"
                class="h-full w-full rounded-2xl border-4 border-zinc-500 object-cover bg-blend-multiply"
                src="{{ $this->student->photo }}"
            />
        @else
            <div
                class="flex h-full w-full items-center justify-center rounded-2xl border-4 border-zinc-500 bg-zinc-100 dark:bg-zinc-800">
                <flux:icon class="h-32 w-32 text-zinc-400" name="question-mark-circle" />
            </div>
        @endif
    </div>

    <p class="text-center text-4xl font-bold">
        @if ($this->student)
            <span>
                {{ $this->student->lastname ?? '-' }}, {{ $this->student->firstname ?? '-' }} {{ $this->student->mi }}.
            </span>
        @elseif ($this->notFound)
            <span class="uppercase text-zinc-400 dark:text-zinc-500">Student Not Found</span>
        @else
            <span class="uppercase text-zinc-400 dark:text-zinc-500">Search for Student</span>
        @endif
    </p>

    <div class="flex w-full flex-col gap-4">
        <div class="rounded-lg border-2 border-zinc-300 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
            <flux:label class="text-lg! font-bold uppercase tracking-widest">Student ID</flux:label>
            <p class="mt-2 text-2xl font-bold text-zinc-900 dark:text-white">
                {{ $this->student?->grouptag ?? '' }}{{ $this->student?->studentid ?? '–' }}
            </p>
        </div>

        <div class="rounded-lg border-2 border-zinc-300 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
            <flux:label class="text-lg! uppercase tracking-widest">Year</flux:label>
            <p class="mt-2 text-2xl font-bold text-zinc-900 dark:text-white">
                {{ $this->student?->year ?? '–' }}
            </p>
        </div>

        <div class="rounded-lg border-2 border-zinc-300 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
            <flux:label class="text-lg! uppercase tracking-widest">Program</flux:label>
            <p class="mt-2 text-2xl font-bold text-zinc-900 dark:text-white">
                {{ $this->student?->program ?? '–' }}
            </p>
        </div>
    </div>
</x-card>
