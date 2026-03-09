<flux:modal class="md:w-xl" name="reject-delete">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Reject Delete Request</flux:heading>
            <flux:text class="mt-2">Provide a reason for declining this request.</flux:text>
        </div>

        <form class="space-y-6" wire:submit="submit">
            <flux:textarea
                label="Reason"
                placeholder="Enter reason for declining"
                wire:model="reason"
            />
            <flux:button
                class="w-full"
                type="submit"
                variant="danger"
            >
                Decline Request
            </flux:button>
        </form>
    </div>
</flux:modal>
