<div class="space-y-4">
    <form class="space-y-4" wire:submit="register">
        <flux:input
            icon="identification"
            label="Student ID"
            placeholder="Enter Student ID"
            type="text"
            wire:model="username"
        />
        <flux:input
            icon="lock-closed"
            label="Password"
            placeholder="Enter password"
            type="password"
            wire:model="password"
        />
        <flux:input
            icon="lock-closed"
            label="Confirm Password"
            placeholder="Confirm password"
            type="password"
            wire:model="password_confirmation"
        />

        <flux:button
            class="w-full"
            type="submit"
            variant="primary"
        >Create Account</flux:button>
    </form>

    <flux:button
        class="w-full"
        href="{{ route('login') }}"
        variant="ghost"
        wire:navigate
    >
        Already have an account? Log in
    </flux:button>

</div>
