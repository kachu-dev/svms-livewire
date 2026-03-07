<!DOCTYPE html>
<html class="" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />

        <title>{{ $title ?? config('app.name') }}</title>

        <link href="{{ asset('images/osa_logo_2.jpg') }}" rel="icon" />

        <link href="https://fonts.bunny.net" rel="preconnect" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>

    <body>
        <main class="relative flex min-h-screen items-center justify-center pb-16">

            <div class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('{{ asset('images/bg.jpg') }}')"
            ></div>
            <div class="absolute inset-0 bg-blue-900/60"></div>

            <flux:card class="relative z-10 w-full max-w-md space-y-6 p-6 shadow-md">

                <div class="flex flex-col items-center gap-3 text-center">
                    <img
                        alt="logo"
                        class="h-32 w-32 rounded-full object-cover"
                        src="{{ asset('images/osa_logo.jpg') }}"
                    >
                    <div>
                        <flux:heading size="xl">{{ $heading }}</flux:heading>
                        <flux:text class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">
                            Student Violation Management System
                        </flux:text>
                    </div>
                </div>

                <flux:separator />

                {{ $slot }}

            </flux:card>
        </main>

        @fluxScripts
        @livewireScripts
    </body>

</html>
