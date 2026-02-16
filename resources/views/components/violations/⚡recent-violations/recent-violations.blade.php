@placeholder
    <div class="rounded border-t-4 border-t-blue-500 bg-white shadow dark:bg-zinc-900">
        <div class="flex gap-1 border-b border-b-gray-200 p-4 dark:border-b-zinc-700">
            <svg
                aria-hidden="true"
                class="size-6 text-gray-700 dark:text-zinc-300"
                data-slot="icon"
                fill="none"
                stroke-width="1.5"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M12.252 6v6h4.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                ></path>
                <path
                    d="M5.887 5.636A9 8.996 45 0 1 16.75 4.208a9 8.996 45 0 1 4.194 10.123 9 8.996 45 0 1-8.69 6.667 9 8.996 45 0 1-8.693-6.67m2.327-8.692L3.38 8.143M3.363 3.15v5.013m0 0h5.013"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                ></path>
            </svg>

            <h3 class="font-semibold text-gray-900 dark:text-zinc-100">Recent Violations</h3>
        </div>
        <div class="flex flex-col items-center gap-8 p-8">
            <div class="flex w-full flex-col gap-3">
                @foreach (range(1, 5) as $item)
                    <flux:card class="px-4 py-3 shadow">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex min-w-0 flex-1 items-center gap-3">
                                <flux:skeleton.line class="w-20" />
                                <flux:skeleton.line class="w-32" />
                            </div>
                            <flux:skeleton.line class="w-28" />
                        </div>
                        <div class="mt-3">
                            <flux:skeleton.line class="w-1/2" />
                            <flux:skeleton.line class="mt-2 w-3/4" />
                        </div>
                    </flux:card>
                @endforeach
            </div>
        </div>
    </div>
@endplaceholder

<div class="rounded border-t-4 border-t-blue-500 bg-white shadow dark:bg-zinc-900">
    <div class="flex gap-1 border-b border-b-gray-200 p-4 dark:border-b-zinc-700">

    </div>
</div>
