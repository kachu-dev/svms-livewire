<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @fluxAppearance
        @livewireStyles
    </head>

    <body class="min-h-screen bg-white antialiased dark:bg-zinc-800">
        <flux:sidebar
            sticky
            collapsible
            class="transition-all! duration-300! ease-in-out! border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
        >
            <flux:sidebar.header class="">
                <flux:sidebar.brand
                    href="#"
                    logo="{{ asset('images/osa_logo_2.jpg') }}"
                    logo:dark="{{ asset('images/osa_logo_2.jpg') }}"
                    name="OSA - SVMS"
                />
                <flux:sidebar.collapse
                    class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2"
                />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.item
                    icon="home"
                    href="#"
                    current
                >Home</flux:sidebar.item>
                <flux:sidebar.item
                    icon="inbox"
                    badge="12"
                    href="#"
                >Inbox</flux:sidebar.item>
                <flux:sidebar.item icon="document-text" href="#">Documents</flux:sidebar.item>
                <flux:sidebar.item icon="calendar" href="#">Calendar</flux:sidebar.item>
                <flux:sidebar.group
                    expandable
                    icon="star"
                    heading="Favorites"
                    class="grid"
                >
                    <flux:sidebar.item href="#">Marketing site</flux:sidebar.item>
                    <flux:sidebar.item href="#">Android app</flux:sidebar.item>
                    <flux:sidebar.item href="#">Brand guidelines</flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>
            <flux:sidebar.spacer />
            <flux:sidebar.nav>
                <flux:sidebar.item icon="cog-6-tooth" href="#">Settings</flux:sidebar.item>
                <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
            </flux:sidebar.nav>
            <flux:dropdown
                position="top"
                align="start"
                class="max-lg:hidden"
            >
                <flux:sidebar.profile avatar="https://fluxui.dev/img/demo/user.png" name="Olivia Martin" />
                <flux:menu>
                    <flux:menu.radio.group>
                        <flux:menu.radio checked>Olivia Martin</flux:menu.radio>
                        <flux:menu.radio>Truly Delta</flux:menu.radio>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
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
                <flux:dropdown position="top" align="start">
                    <flux:profile avatar="https://fluxui.dev/img/demo/user.png" />
                    <flux:menu>
                        <flux:menu.radio.group>
                            <flux:menu.radio checked>Olivia Martin</flux:menu.radio>
                            <flux:menu.radio>Truly Delta</flux:menu.radio>
                        </flux:menu.radio.group>
                        <flux:menu.separator />
                        <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </flux:navbar>
            <flux:navbar scrollable>
                <flux:navbar.item href="#" current>Dashboard</flux:navbar.item>
                <flux:navbar.item badge="32" href="#">Orders</flux:navbar.item>
                <flux:navbar.item href="#">Catalog</flux:navbar.item>
                <flux:navbar.item href="#">Configuration</flux:navbar.item>
                <flux:button
                    x-data
                    x-on:click="$flux.dark = ! $flux.dark"
                    icon="moon"
                    variant="subtle"
                    aria-label="Toggle dark mode"
                />
            </flux:navbar>
        </flux:header>

        <flux:main>
            {{ $slot }}
        </flux:main>

        @fluxScripts
        @livewireScripts
    </body>

</html>
