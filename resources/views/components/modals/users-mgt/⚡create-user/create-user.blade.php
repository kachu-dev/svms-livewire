<flux:modal class="md:w-xl" name="create-user" @close="resetCreateForm">
    <div class="space-y-6 max-h-[80vh]">
        <div>
            <flux:heading size="lg">Create User</flux:heading>
            <flux:text class="mt-2">Create a new user account.</flux:text>
        </div>

        <form class="space-y-6" wire:submit="submit">
            <flux:input
                label="Name"
                placeholder="Enter new name"
                type="text"
                wire:model="name"
            />

            <flux:input
                label="Username"
                placeholder="Enter new username"
                type="text"
                wire:model="username"
            />

            <flux:input
                label="Assigned Gate"
                placeholder="Enter new assigned gate"
                type="number"
                wire:model="assigned_gate"
            />

            <flux:input
                label="Password"
                placeholder="Enter new password"
                type="password"
                viewable
                wire:model="password"
            />

            <flux:input
                label="Confirm Password"
                placeholder="Confirm password"
                type="password"
                viewable
                wire:model="password_confirmation"
            />

            <flux:radio.group wire:model="role" label="Role">
                <flux:radio
                    value="osa"
                    label="OSA Staff"
                    description="OSA Staff have full access of the capabilities of the system."
                />
                <flux:radio
                    value="guard"
                    label="Guard"
                    description="Guards can record a students minor violations, as well as request updates."
                />
            </flux:radio.group>

            <flux:button
                class="w-full"
                type="submit"
                variant="primary"
            >
                Update User Details
            </flux:button>
        </form>
    </div>
</flux:modal>
