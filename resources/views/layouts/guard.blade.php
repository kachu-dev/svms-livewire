<!DOCTYPE html>
<html class="" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen bg-white antialiased dark:bg-zinc-800">
        <flux:header class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle
                class="lg:hidden"
                icon="bars-2"
                inset="left"
            />

            <flux:brand
                class="max-lg:hidden dark:hidden"
                href="{{ route('guard.violations.create') }}"
                logo:dark="{{ asset('images/osa_logo_2.jpg') }}"
                logo="{{ asset('images/osa_logo_2.jpg') }}"
                name="OSA - SVMS"
                wire:navigate
            />

            <flux:brand
                class="max-lg:hidden! hidden dark:flex"
                href="{{ route('guard.violations.create') }}"
                logo:dark="{{ asset('images/osa_logo_2.jpg') }}"
                logo="{{ asset('images/osa_logo_2.jpg') }}"
                name="OSA - SVMS"
                wire:navigate
            />

            <flux:spacer />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item
                    href="{{ route('guard.violations.create') }}"
                    icon="plus-circle"
                    wire:navigate
                >Create Violation</flux:navbar.item>
                <flux:navbar.item
                    href="{{ route('guard.violations.recent') }}"
                    icon="clock"
                    wire:navigate
                >Recently Recorded</flux:navbar.item>
                <flux:navbar.item
                    href="{{ route('guard.violations.requests') }}"
                    icon="clock"
                    wire:navigate
                >Requests</flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:dropdown align="end" position="top">
                <flux:profile name="{{ Auth::user()->name }}" />
                <flux:menu>
                    <flux:radio.group
                        variant="segmented"
                        x-data
                        x-model="$flux.appearance"
                    >
                        <flux:radio icon="sun" value="light">Light</flux:radio>
                        <flux:radio icon="moon" value="dark">Dark</flux:radio>
                        <flux:radio icon="computer-desktop" value="system">System</flux:radio>
                    </flux:radio.group>
                    <flux:menu.separator />
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <flux:menu.item
                            as="button"
                            icon="arrow-right-start-on-rectangle"
                            type="submit"
                        >
                            Logout
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:sidebar
            class="border-r border-zinc-200 bg-zinc-50 lg:hidden dark:border-zinc-700 dark:bg-zinc-900"
            collapsible="mobile"
            sticky
        >
            <flux:sidebar.header>
                <flux:sidebar.brand
                    href="{{ route('student.violations.index') }}"
                    logo:dark="{{ asset('images/osa_logo_2.jpg') }}"
                    logo="{{ asset('images/osa_logo_2.jpg') }}"
                    name="OSA - SVMS"
                    wire:navigate
                />
                <flux:sidebar.collapse
                    class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2"
                />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.item
                    href="{{ route('guard.violations.create') }}"
                    icon="plus-circle"
                    wire:navigate
                >Create Violation</flux:sidebar.item>
                <flux:sidebar.item
                    href="{{ route('guard.violations.recent') }}"
                    icon="clock"
                    wire:navigate
                >Recently Recorded</flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:sidebar.spacer />
        </flux:sidebar>

        <flux:main>
            {{ $slot }}
        </flux:main>

        @include('partials.footer')
    </body>

</html>
