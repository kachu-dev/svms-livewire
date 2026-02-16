<flux:modal name="delete-policy" class="min-w-[22rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Deactivate Policy?</flux:heading>
            <flux:text class="mt-2">
                You're about to deactivate this policy: <br>
            </flux:text>
            <flux:text class="mt-2" variant="strong">
                {{ $code }} - {{ $name }}
            </flux:text>
        </div>

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button wire:click="delete" variant="danger">Deactivate Policy</flux:button>
        </div>
    </div>
</flux:modal>
