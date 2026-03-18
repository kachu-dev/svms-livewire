<flux:modal class="min-w-[28rem]" name="reject-update">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Reject update request</flux:heading>
            <flux:subheading class="mt-2">Provide a reason for rejecting this update request.</flux:subheading>
        </div>

        <flux:separator />

        <flux:textarea
            label="Denial reason"
            placeholder="Enter reason for rejection (min. 10 characters)"
            wire:model="denialReason"
        />

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="danger" wire:click="reject">Confirm rejection</flux:button>
        </div>
    </div>
</flux:modal>
