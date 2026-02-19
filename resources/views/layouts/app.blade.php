<!DOCTYPE html>
<html class="" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>{{ $title ?? config('app.name') }}</title>

        <link href="{{ asset('images/osa_logo_2.jpg') }}" rel="icon">

        <link href="https://fonts.bunny.net" rel="preconnect">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @fluxAppearance
        @livewireStyles
    </head>

    <body class="min-h-screen bg-white antialiased dark:bg-zinc-800">

        <flux:sidebar
            class="transition-all! duration-300! ease-in-out! border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
            collapsible
            sticky
        >
            <flux:sidebar.header class="">
                <flux:sidebar.brand
                    href="{{ route('staff.violations.index') }}"
                    logo:dark="{{ asset('images/osa_logo_2.jpg') }}"
                    logo="{{ asset('images/osa_logo_2.jpg') }}"
                    name="OSA - SVMS"
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
{{--                    icon:variant="solid"--}}
                    icon="exclamation-triangle"
                >
                    <flux:sidebar.item href="{{ route('staff.violations.create') }}">Create Violation
                    </flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('staff.violations.index') }}">All Violations</flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('staff.violations.deleted') }}">Deleted Violations
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group
                    class="grid"
                    expandable
                    heading="Policy"
{{--                    icon:variant="solid"--}}
                    icon="document-text"
                >
                    <flux:sidebar.item href="{{ route('staff.policy.index') }}">All Policies</flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('staff.policy.deleted') }}">Deactivated Policies
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group
                    class="grid"
                    expandable
                    heading="User Management"
{{--                    icon:variant="solid"--}}
                    icon="user-group"
                >
                    <flux:sidebar.item href="{{ route('staff.users-mgt.index') }}">All Users</flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('staff.users-mgt.deleted') }}">Deactivated Users
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
                    <flux:menu.radio.group>
                        <flux:menu.radio checked>{{ Auth::user()->name }}</flux:menu.radio>
                        <flux:menu.radio>Truly Delta</flux:menu.radio>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:menu.item href="/logout" icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
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
                <flux:heading class="flex-1 text-center" size="lg">{{ $title }}</flux:heading>
                <flux:spacer />
                <flux:dropdown align="start" position="top">
                    <flux:profile avatar="https://fluxui.dev/img/demo/user.png" />
                    <flux:menu>
                        <flux:menu.radio.group>
                            <flux:menu.radio checked></flux:menu.radio>
                            <flux:menu.radio>Truly Delta</flux:menu.radio>
                        </flux:menu.radio.group>
                        <flux:menu.separator />
                        <flux:menu.item href="/logout" icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </flux:navbar>

            <flux:navbar class="hidden lg:flex">
                <flux:heading class="flex-1" size="xl">{{ $title }}</flux:heading>
            </flux:navbar>
        </flux:header>

        <flux:main class="mx-auto flex min-h-screen w-full flex-col">
            {{ $slot }}
        </flux:main>

        <x-toaster-hub />

        @fluxScripts
        @livewireScripts
    </body>

</html>
