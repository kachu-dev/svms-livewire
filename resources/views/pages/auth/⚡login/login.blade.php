<div class="flex min-h-screen">
    <div class="flex flex-1 items-center justify-center">
        <div class="w-80 max-w-80 space-y-6">
            <div class="flex justify-center opacity-50">
                <a class="group flex items-center gap-3" href="/">
                    <div>
                        <svg
                            class="h-4"
                            fill="none"
                            viewBox="0 0 18 13"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <g>
                                <line
                                    stroke-linecap="round"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    x1="1"
                                    x2="1"
                                    y1="5"
                                    y2="10"
                                ></line>
                                <line
                                    stroke-linecap="round"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    x1="5"
                                    x2="5"
                                    y1="1"
                                    y2="8"
                                ></line>
                                <line
                                    stroke-linecap="round"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    x1="9"
                                    x2="9"
                                    y1="5"
                                    y2="10"
                                ></line>
                                <line
                                    stroke-linecap="round"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    x1="13"
                                    x2="13"
                                    y1="1"
                                    y2="12"
                                ></line>
                                <line
                                    stroke-linecap="round"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    x1="17"
                                    x2="17"
                                    y1="5"
                                    y2="10"
                                ></line>
                            </g>
                        </svg>
                    </div>

                    <span class="text-xl font-semibold">svms</span>
                </a>
            </div>

            <flux:heading class="text-center" size="xl">Welcome back</flux:heading>

            <flux:separator text="Login Page" />

            <form class="flex flex-col gap-6" wire:submit="login">
                {{--
                <flux:input wire:model="email" label="Email" type="email" placeholder="email@example.com" />
--}}
                <flux:input
                    label="Username"
                    placeholder="Your username"
                    type="text"
                    wire:model="username"
                />
                <flux:input
                    label="Password"
                    placeholder="Your password"
                    type="password"
                    wire:model="password"
                />
                <flux:error class="mt-0! text-center" name="credentials"></flux:error>
                <flux:button
                    class="w-full"
                    type="submit"
                    variant="primary"
                >Log in</flux:button>
            </form>
        </div>
    </div>
    {{ Auth::user() }}
    <div class="flex-1 p-4 max-lg:hidden">
        <div class="relative flex h-full w-full flex-col items-start justify-end rounded-lg bg-zinc-900 p-16 text-white"
            style="background-image: url('/img/demo/auth_aurora_2x.png'); background-size: cover"
        >

            <div class="font-base mb-6 text-3xl italic xl:text-4xl">
                Flux has enabled me to design, build, and deliver apps faster than ever before.
            </div>

            <div class="flex gap-4">
                <flux:avatar size="xl" src="https://fluxui.dev/img/demo/caleb.png" />

                <div class="flex flex-col justify-center font-medium">
                    <div class="text-lg">Caleb Porzio</div>
                    <div class="text-zinc-300">Creator of Livewire</div>
                </div>
            </div>
        </div>
    </div>
</div>
