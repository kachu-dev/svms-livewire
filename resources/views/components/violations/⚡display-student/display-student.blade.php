<x-card
    class="flex flex-col items-center gap-8"
    header="Student Information"
    icon="user-circle"
    wire:transition
>
    <div class="aspect-square w-full max-w-64 rounded-2xl">
        <flux:icon.user class="h-full w-full" />
    </div>

    <p class="text-center text-3xl font-bold">
        @if ($this->student)
            {{ $this->student->firstname }} {{ $this->student->lastname }}
        @elseif($this->notFound)
            <span class="text-zinc-400 dark:text-zinc-500">Student Not Found</span>
        @else
            <span class="text-zinc-400 dark:text-zinc-500">Search for Student</span>
        @endif
    </p>

    <div class="flex w-full flex-col gap-2">
        <flux:label>Student ID</flux:label>
        <flux:card class="flex min-h-12 items-center p-2">
            {{ $this->student?->grouptag }}{{ $this->student?->studentid ?? '' }}
        </flux:card>

        <flux:label class="mt-1">Course</flux:label>
        <flux:card class="flex min-h-12 items-center p-2">
            {{ $this->student?->program ?? '' }}
        </flux:card>

        <flux:label class="mt-1">Year Level</flux:label>
        <flux:card class="flex min-h-12 items-center p-2">
            {{ $this->student?->year ?? '' }}
        </flux:card>
    </div>
</x-card>
