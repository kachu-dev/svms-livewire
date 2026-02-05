<x-card
    class="flex flex-col items-center gap-8"
    header="Student Information"
    icon="user-circle"
>
    <div class="max-h-64 max-w-64 rounded-2xl">
        <flux:icon.user class="h-full w-full">

        </flux:icon.user>
        {{-- <flux:avatar icon:variant="outline" src="https://unavatar.io/x/calebporzio" class="size-full max-w-64 max-h-64" /> --}}
    </div>

    <p class="text-center text-3xl font-bold">
        @if ($this->student)
            {{ $this->student->name }}
        @elseif($this->notFound)
            <span class="text-zinc-400 dark:text-zinc-500">Student Not Found</span>
        @else
            <span class="text-zinc-400 dark:text-zinc-500">Search for Student</span>
        @endif
    </p>

    <div class="flex w-full flex-col gap-2">
        <flux:input
            disabled
            label="Student ID"
            size="lg"
            value="{{ $this->student?->id ?? '' }}"
        />
        <flux:input
            disabled
            label="Course"
            size="lg"
            value="{{ $this->student?->course ?? '' }}"
        />
        <flux:input
            disabled
            label="Year Level"
            size="lg"
            value="{{ $this->student?->year ?? '' }}"
        />
    </div>
</x-card>
