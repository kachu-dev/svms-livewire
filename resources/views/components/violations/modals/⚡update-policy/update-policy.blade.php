<flux:modal class="md:w-xl" name="update-policy">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Update Policy</flux:heading>
            <flux:text class="mt-2">Update details for this policy type.</flux:text>
        </div>

        <form class="space-y-6" wire:submit="save">
            <flux:input
                label="Code"
                placeholder="Enter policy code"
                type="text"
                wire:model="code"
            />

            <flux:textarea
                label="Name"
                placeholder="Enter policy name"
                rows="3"
                wire:model="name"
            />

            <flux:select
                label="Classification"
                placeholder="Select classification..."
                wire:model="classification"
            >
                <flux:select.option>Minor</flux:select.option>
                <flux:select.option>Major - Suspension</flux:select.option>
                <flux:select.option>Major - Dismissal</flux:select.option>
                <flux:select.option>Major - Expulsion</flux:select.option>
            </flux:select>

            <flux:button
                class="w-full"
                type="submit"
                variant="primary"
            >
                Update Policy Type
            </flux:button>
        </form>
    </div>
</flux:modal>
