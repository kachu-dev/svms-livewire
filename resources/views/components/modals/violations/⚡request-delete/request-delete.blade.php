<flux:modal class="md:w-xl" name="request-delete">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Request Delete</flux:heading>
            <flux:text class="mt-2">Request a delete for this violation.</flux:text>
        </div>

        <form class="space-y-6" wire:submit="submit">
            <flux:textarea
                label="Reason"
                placeholder="Enter reason for requesting a delete"
                wire:model="reason"
            />
            <flux:button
                class="w-full"
                type="submit"
                variant="danger"
            >
                Request Delete
            </flux:button>
        </form>
    </div>
</flux:modal>
