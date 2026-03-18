<!DOCTYPE html>
<html class="" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen bg-white antialiased dark:bg-zinc-800">
        <flux:sidebar
            class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
            collapsible
            sticky
        >
            <flux:sidebar.header>
                <flux:sidebar.brand
                    href="{{ route('staff.violations.index') }}"
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
                    href="{{ route('staff.dashboard') }}"
                    icon="chart-bar"
                    wire:navigate
                >Dashboard</flux:sidebar.item>

                <flux:sidebar.item
                    href="{{ route('staff.violations.index') }}"
                    icon="exclamation-triangle"
                    wire:navigate
                >Violations</flux:sidebar.item>

                <flux:sidebar.item
                    href="{{ route('staff.policy.index') }}"
                    icon="document-text"
                    wire:navigate
                >Policy</flux:sidebar.item>

                <flux:sidebar.item
                    href="{{ route('staff.users-mgt.index') }}"
                    icon="user-group"
                    wire:navigate
                >User Management</flux:sidebar.item>

                <flux:sidebar.item
                    href="{{ route('staff.logs') }}"
                    icon="queue-list"
                    wire:navigate
                >Logs</flux:sidebar.item>
            </flux:sidebar.nav>

        </flux:sidebar>

        <flux:header
            class="block! border-b border-zinc-200 bg-white lg:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
        >
            <flux:navbar class="w-full lg:hidden">
                <flux:sidebar.toggle
                    class="lg:hidden"
                    icon="bars-2"
                    inset="left"
                />
                <flux:spacer />
            </flux:navbar>

            <flux:navbar scrollable>
                @if (request()->routeIs('staff.violations.*'))
                    <flux:navbar.item href="{{ route('staff.violations.create') }}" wire:navigate>Create
                    </flux:navbar.item>
                    <flux:navbar.item href="{{ route('staff.violations.index') }}" wire:navigate>Pending
                    </flux:navbar.item>
                    <flux:navbar.item href="{{ route('staff.violations.complete') }}" wire:navigate>Completed
                    </flux:navbar.item>
                    <flux:navbar.item href="{{ route('staff.violations.deleted') }}" wire:navigate>Archived
                    </flux:navbar.item>
                    <flux:navbar.item href="{{ route('staff.violations.delete-requests') }}" wire:navigate>Delete
                        Requests</flux:navbar.item>
                    <flux:navbar.item href="{{ route('staff.violations.update-requests') }}" wire:navigate>Update
                        Requests</flux:navbar.item>
                @elseif (request()->routeIs('staff.policy.*'))
                    <flux:navbar.item href="{{ route('staff.policy.index') }}" wire:navigate>Active</flux:navbar.item>
                    <flux:navbar.item href="{{ route('staff.policy.deleted') }}" wire:navigate>Deactivated
                    </flux:navbar.item>
                    <flux:navbar.item href="{{ route('staff.policy.template') }}" wire:navigate>Template
                    </flux:navbar.item>
                @elseif (request()->routeIs('staff.users-mgt.*'))
                    <flux:navbar.item href="{{ route('staff.users-mgt.index') }}" wire:navigate>Active
                    </flux:navbar.item>
                    <flux:navbar.item href="{{ route('staff.users-mgt.deleted') }}" wire:navigate>Deactivated
                    </flux:navbar.item>
                @endif

                <flux:spacer />

                <livewire:notifications />

                <flux:dropdown align="end" position="top">
                    <flux:profile
                        avatar:size="xs"
                        class="py-0.5"
                        name="{{ Auth::user()->name }}"
                    />
                    <flux:menu>
                        <flux:radio.group
                            variant="segmented"
                            x-data
                            x-model="$flux.appearance"
                        >
                            <flux:radio icon="sun" value="light"></flux:radio>
                            <flux:radio icon="moon" value="dark"></flux:radio>
                            <flux:radio icon="computer-desktop" value="system"></flux:radio>
                        </flux:radio.group>
                        <flux:menu.separator />
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <flux:menu.item
                                as="button"
                                icon="arrow-right-start-on-rectangle"
                                type="submit"
                            >Logout</flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </flux:navbar>
        </flux:header>

        <flux:main>
            {{ $slot }}
        </flux:main>

        @include('partials.footer')
    </body>

</html>
