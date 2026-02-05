<x-card header="Search Student" icon="magnifying-glass">
    <form wire:submit.prevent="findStudent()" class="flex flex-col gap-4">
        <flux:input
            wire:model="studentId"
            label="Student ID"
            placeholder="Input Student ID or Scan RFID"
            autocomplete="off"
            autofocus
        />

        <div class="flex mt-4 gap-2">
            <flux:button
                type="submit"
                variant="primary"
                icon="magnifying-glass"
                class="flex-1"
            >
                Search for Student
            </flux:button>
        </div>
    </form>
</x-card>
