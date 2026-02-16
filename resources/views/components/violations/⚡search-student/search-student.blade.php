<x-card header="Search Student" icon="magnifying-glass">
    <form class="flex flex-col gap-4" wire:submit="findStudent">
        <flux:input
            autocomplete="off"
            autofocus
            label:size="lg"
            label="Student ID"
            placeholder="Input Student ID or Scan RFID"
            size="lg"
            wire:model="studentId"
        />

        <div class="mt-4 flex gap-2">
            <flux:button
                class="flex-1"
                icon="magnifying-glass"
                size="lg"
                type="submit"
                variant="primary"
                wire:target="findStudent"
            >
                <span wire:loading.remove wire:target="findStudent">Search for Student</span>
                <span wire:loading wire:target="findStudent">Searching...</span>
            </flux:button>
        </div>
    </form>
</x-card>
