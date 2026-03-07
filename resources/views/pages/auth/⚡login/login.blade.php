<div class="space-y-4">
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
            wire:click="login"
        >Log in</flux:button>
        <flux:button
            class="w-full"
            href="{{ route('register') }}"
            variant="ghost"
            wire:navigate
        >
            Sign up for a new account
        </flux:button>
    </div>
</div>
