<flux:modal
    @close="resetCreateForm"
    class="md:w-xl"
    name="create-user"
>
    <div class="max-h-[80vh] space-y-6">
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

            <flux:radio.group label="Role" wire:model="role">
                <flux:radio
                    description="OSA Staff have full access of the capabilities of the system."
                    label="OSA Staff"
                    value="osa"
                />
                <flux:radio
                    description="Guards can record a students minor violations, as well as request updates."
                    label="Guard"
                    value="guard"
                />
            </flux:radio.group>

            <div x-show="$wire.role === 'guard'" x-transition>
                <flux:input
                    label="Assigned Gate"
                    placeholder="Enter new assigned gate"
                    type="number"
                    wire:model="assigned_gate"
                />
            </div>

            <flux:button
                class="w-full"
                type="submit"
                variant="primary"
            >
                Create User
            </flux:button>
        </form>
    </div>
</flux:modal>
