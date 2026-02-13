<div>
    <x-card header="Policy Types" icon="document-text">


        <form wire:submit="save">
            <div>
                <flux:input
                    wire:model="code"
                    label="Code"
                    type="text"
                    placeholder="Code"
                />
            </div>

            <div>
                <flux:textarea
                    wire:model="name"
                    label="Name"
                    placeholder="Name"
                />
            </div>

            <div>
                <label for="classification">Classification</label>
                <flux:select wire:model="classification" placeholder="Select classification...">
                    <flux:select.option> Minor </flux:select.option>
                    <flux:select.option> Major - Suspension </flux:select.option>
                    <flux:select.option> Major - Dismissal </flux:select.option>
                    <flux:select.option> Major - Expulsion </flux:select.option>
                </flux:select>
            </div>

            <flux:button type="submit">Update</flux:button>
        </form>
    </x-card>
</div>
