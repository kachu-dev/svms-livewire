<!DOCTYPE html>
<html class="" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen bg-zinc-50 antialiased dark:bg-zinc-800">
        <flux:sidebar
            class="border-r border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900"
            collapsible
            sticky
        >
            <flux:sidebar.header>
                <flux:sidebar.brand
                    href="{{ route('guard.violations.create') }}"
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
                <flux:sidebar.group
                    class="grid"
                    expandable
                    heading="Violations"
                    icon="exclamation-triangle"
                >
                    <flux:sidebar.item href="{{ route('guard.violations.create') }}" wire:navigate>
                        Create Violation
                    </flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('guard.violations.recent') }}" wire:navigate>
                        Recent Violations
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>
            <flux:sidebar.spacer />
            <flux:sidebar.nav>
                <flux:sidebar.item href="#" icon="cog-6-tooth">Settings</flux:sidebar.item>
                <flux:sidebar.item href="#" icon="information-circle">Help</flux:sidebar.item>
            </flux:sidebar.nav>
            <flux:dropdown
                align="start"
                class="max-lg:hidden"
                position="top"
            >
                <flux:sidebar.profile name="{{ Auth::user()->name }}" />
                <flux:menu>
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
        </flux:sidebar>

        <flux:header class="block! border-b border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <flux:navbar class="w-full lg:hidden">
                <flux:sidebar.toggle
                    class="lg:hidden"
                    icon="bars-2"
                    inset="left"
                />
                <flux:spacer />
                <flux:heading class="flex-1 text-center" size="lg">{{ $title }}</flux:heading>
                <flux:spacer />
                <flux:dropdown align="start" position="top">
                    <flux:profile name="{{ Auth::user()->name }}" />
                    <flux:menu>
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
            </flux:navbar>

            <flux:navbar class="hidden lg:flex">
                <flux:heading class="flex-1" size="xl">{{ $title }}</flux:heading>
                <flux:spacer />
                <div class="flex items-center gap-2">
                    <span class="text-xl" id="current-date"></span>
                    <flux:separator vertical />
                    <span class="text-xl" id="current-time"></span>
                </div>
            </flux:navbar>
        </flux:header>

        <flux:main>
            {{ $slot }}
        </flux:main>

        @include('partials.footer')
    </body>

</html>
