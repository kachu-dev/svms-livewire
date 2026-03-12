<form class="space-y-4" wire:submit="login">
    <div class="space-y-4">
        <flux:input
            icon="user"
            label="Username"
            placeholder="Your username"
            type="text"
            wire:model="username"
        />
        <flux:input
            icon="lock-closed"
            label="Password"
            placeholder="Your password"
            type="password"
            wire:model="password"
        />
    </div>

    <flux:error class="text-center text-sm" name="credentials" />

    <div class="space-y-2">
        <flux:button
            class="w-full"
            type="submit"
            variant="primary"
        >Log in</flux:button>
        <flux:text class="mt-2 text-center">Don't have a student account?
            <flux:link href="{{ route('register') }}" wire:navigate>Register</flux:link>
        </flux:text>
    </div>
</form>
