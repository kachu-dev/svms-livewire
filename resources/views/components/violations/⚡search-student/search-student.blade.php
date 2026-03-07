<x-card header="Step 1: Search for Student" icon="magnifying-glass">
    <form class="flex flex-col gap-4" wire:submit="findStudent">
        <flux:input
            autocomplete="off"
            autofocus
            label:size="lg"
            label="Student ID"
            placeholder="Input Student ID or Scan RFID"
            size="accessible"
            variant="accessible"
            wire:model="studentId"
        />

        <flux:button
            icon="magnifying-glass"
            size="lg"
            type="submit"
            variant="primary"
        >
            <span wire:loading.remove wire:target="findStudent">Search for Student</span>
            <span wire:loading wire:target="findStudent">Searching...</span>
        </flux:button>

        @if ($foundStudentId)
            <flux:callout
                heading="Student Found"
                icon="check-circle"
                variant="success"
            />
        @elseif ($notFound)
            <flux:callout
                heading="Student Not Found"
                icon="x-circle"
                variant="danger"
            />
        @endif
    </form>
</x-card>
