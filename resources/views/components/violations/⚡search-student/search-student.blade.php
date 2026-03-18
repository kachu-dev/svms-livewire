<x-card header="Step 1: Search for Student" icon="magnifying-glass">
    <form class="flex flex-col gap-6" wire:submit="findStudent">
        <div>
            <flux:field>
                <flux:input
                    autocomplete="off"
                    autofocus
                    placeholder="Click Here and Type Student ID or Scan RFID"
                    size="{{ $size }}"
                    wire:model="studentId"
                />
                <flux:error name="studentId" />
            </flux:field>
        </div>

        <flux:button
            class="w-full"
            icon="magnifying-glass"
            size="{{ $size }}"
            type="submit"
            variant="primary"
        >
            <span wire:loading.remove wire:target="findStudent">Search for Student</span>
            <span wire:loading wire:target="findStudent">Searching...</span>
        </flux:button>

    </form>
</x-card>
