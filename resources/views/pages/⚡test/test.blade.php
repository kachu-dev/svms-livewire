<div class="bg-accent h-full">
    <x-card class="h-full">
        <div>
            <form class="flex max-w-xl flex-col gap-4" wire:submit.prevent="updatePicture">
                <flux:input label="Student ID" wire:model="studentId"></flux:input>
                <flux:input type="file" wire:model="image" />
                <flux:button type="submit">Update</flux:button>
            </form>
        </div>
    </x-card>
</div>
