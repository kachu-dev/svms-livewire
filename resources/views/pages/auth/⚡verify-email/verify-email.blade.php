<div class="space-y-4">
    <flux:text class="text-center text-sm">
        Please verify your email address. A verification link was sent to:
    </flux:text>

    <flux:text class="text-center text-sm text-black">
        {{ auth()->user()->email }}
    </flux:text>

    @if (session('message'))
        <flux:callout variant="success">{{ session('message') }}</flux:callout>
    @endif


    <div class="flex flex-col items-center justify-between space-y-3">

        <form action="{{ route('verification.send') }}"  class="space-y-4" method="POST">
            @csrf
            <flux:button class="w-full" variant="primary" type="submit">
                Resend Verification Email
            </flux:button>
        </form>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <flux:button class="w-full" variant="ghost" type="submit">
                Back to Login
            </flux:button>
        </form>
    </div>
</div>
