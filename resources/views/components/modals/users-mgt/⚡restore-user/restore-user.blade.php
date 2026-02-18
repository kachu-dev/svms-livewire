<flux:modal class="min-w-[22rem]" name="restore-user">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Reactivate User?</flux:heading>
            <flux:text class="mt-2">
                You're about to reactivate this user: <br>
            </flux:text>
            <flux:text class="mt-2" variant="strong">
                {{ $name }}
            </flux:text>
        </div>

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button
                color="green"
                variant="primary"
                wire:click="restore"
            >Reactivate User</flux:button>
        </div>
    </div>
</flux:modal>
