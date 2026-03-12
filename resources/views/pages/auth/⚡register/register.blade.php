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

    <flux:text class="mt-2 text-center">Already have a student account?
        <flux:link href="{{ route('login') }}" wire:navigate>Login</flux:link>
    </flux:text>
</div>
