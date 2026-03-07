<x-card
    class="flex flex-col items-center gap-12"
    header="Step 2: Check Student Information"
    icon="user-circle"
>
    <div class="aspect-square w-full max-w-64 rounded-2xl">
        @if (!$this->student)
            <div class="h-full w-full flex items-center justify-center rounded-2xl bg-zinc-100 p-1.5 dark:bg-zinc-800">
                <flux:icon
                    class="h-full w-full size-5 text-zinc-600 dark:text-zinc-400"
                    name="user"
                    variant="mini"
                />
            </div>
        @elseif ($this->student->photo)
            <img
                alt="Photo"
                class="h-full w-full rounded-2xl object-cover bg-blend-multiply"
                src="data:image/jpeg;base64,{{ $this->student->photo }}"
            />
        @else
            <div class="flex h-full w-full items-center justify-center rounded-2xl bg-zinc-100 dark:bg-zinc-800">
                <flux:icon class="h-16 w-16 text-zinc-400" name="user-circle" />
            </div>
        @endif
    </div>

    <p class="text-center text-3xl font-bold">
        @if ($this->student)
            <span>
                {{ $this->student->firstname }} {{ $this->student->lastname }}
            </span>
        @elseif ($this->notFound)
            <span class="text-zinc-400 dark:text-zinc-500">Student Not Found</span>
        @else
            <span class="text-zinc-400 dark:text-zinc-500">Search for Student</span>
        @endif
    </p>

    <div class="flex w-full flex-col gap-4">
        <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
            <flux:label class="text-xs font-bold uppercase tracking-widest">Student ID</flux:label>
            <p class="mt-2 text-xl font-bold text-zinc-900 dark:text-white">
                {{ $this->student?->grouptag }}{{ $this->student?->studentid ?? '–' }}
            </p>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
            <flux:label class="text-xs uppercase tracking-widest">Year</flux:label>
            <p class="mt-2 text-xl font-bold text-zinc-900 dark:text-white">
                {{ $this->student?->year ?? '–' }}
            </p>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
            <flux:label class="text-xs uppercase tracking-widest">Course</flux:label>
            <p class="mt-2 text-xl font-bold text-zinc-900 dark:text-white">
                {{ $this->student?->program ?? '–' }}
            </p>
        </div>
    </div>
</x-card>
