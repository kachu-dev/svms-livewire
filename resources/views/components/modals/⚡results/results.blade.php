<flux:modal name="results" class="w-full max-w-md sm:max-w-96">
    <div class="space-y-6">
        @if ($resultType === 'success')
            <div class="flex flex-col items-center text-center">
                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                    <svg
                        class="h-8 w-8 text-green-600 dark:text-green-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        ></path>
                    </svg>
                </div>
                <flux:heading size="lg" class="text-green-600 dark:text-green-400">Success!</flux:heading>
                <flux:subheading class="mt-2">{{ $resultMessage ?? 'Operation completed successfully' }}
                </flux:subheading>
            </div>
        @else
            <div class="flex flex-col items-center text-center">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg
                        class="h-8 w-8 text-red-600 dark:text-red-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </div>
                <flux:heading size="lg" class="text-red-600 dark:text-red-400">Error!</flux:heading>
                <flux:subheading class="mt-2">{{ $resultMessage ?? 'Something went wrong' }}</flux:subheading>
            </div>
        @endif

        <div class="flex gap-2 border-t border-zinc-200 pt-4 dark:border-zinc-700">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="primary">Close</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
