<div>
    <form class="flex max-w-xl flex-col gap-4" wire:submit.prevent="updateDatabases">
        <flux:input label="Student ID" wire:model="studentId" />
        <flux:input label="First Name" wire:model="lastname" />
        <flux:input label="Last Name" wire:model="firstname" />
        <flux:input label="Middle Initial" wire:model="mi" />
        <flux:input label="Rfid Tag" wire:model="rfid" />
        <flux:input
            label="Student ID"
            type="file"
            wire:model="image"
        />
        <flux:button type="submit">Update</flux:button>
    </form>
</div>
