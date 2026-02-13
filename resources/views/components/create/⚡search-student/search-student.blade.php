<x-card header="Search Student" icon="magnifying-glass">
    <form wire:submit="findStudent" class="flex flex-col gap-4">
        <flux:input
            wire:model="studentId"
            label="Student ID"
            placeholder="Input Student ID or Scan RFID"
            size="lg"
            autocomplete="off"
            autofocus
        />

        <div class="mt-4 flex gap-2">
            <flux:button
                type="submit"
                variant="primary"
                icon="magnifying-glass"
                class="flex-1"
                size="lg"
                wire:target="findStudent"
            >
                <span wire:loading.remove wire:target="findStudent">Search for Student</span>
                <span wire:loading wire:target="findStudent">Searching...</span>
            </flux:button>
        </div>
    </form>
</x-card>
