<div
    @class([
        'fixed z-50 p-4 w-full flex flex-col pointer-events-none sm:p-6 gap-3',
        'bottom-0' => $alignment->is('bottom'),
        'top-1/2 -translate-y-1/2' => $alignment->is('middle'),
        'top-0' => $alignment->is('top'),
        'items-start rtl:items-end' => $position->is('left'),
        'items-center' => $position->is('center'),
        'items-end rtl:items-start' => $position->is('right'),
    ])
    id="toaster"
    role="status"
    x-data="toasterHub(@js($toasts), @js($config))"
>
    <template :key="toast.id" x-for="toast in toasts">
        <div
            @class([
                'relative duration-300 transform transition-all ease-out max-w-sm w-full pointer-events-auto',
                'text-left' => !$position->is('center'),
                'text-center' => $position->is('center'),
            ])
            x-init="$nextTick(() => toast.show($el))"
            x-show="toast.isVisible"
            x-transition:enter-end="translate-y-0 opacity-100 scale-100"
            x-transition:enter-start="translate-y-8 opacity-0 scale-95"
            x-transition:leave-end="opacity-0 scale-95"
            x-transition:leave-start="opacity-100 scale-100"
        >
            <div :class="toast.select({
                error: 'bg-red-500/90 border-red-500/30 text-white',
                info: 'bg-slate-700 border-slate-700/50 text-white shadow-slate-900/20',
                success: 'bg-emerald-600/90 border-emerald-500/30 text-white',
                warning: 'bg-amber-200/90 border-amber-200/50 text-amber-900 shadow-amber-900/10'
            })"
                class="relative flex w-full items-center justify-between overflow-hidden rounded-2xl border p-1.5 shadow-2xl backdrop-blur-md backdrop-filter"
            >

                <p class="w-full select-none px-4 py-3 text-base font-medium leading-snug tracking-tight"
                    x-text="toast.message"
                ></p>

                @if ($closeable)
                    <button
                        @click="toast.dispose()"
                        aria-label="@lang('close')"
                        class="mr-1 flex shrink-0 items-center justify-center rounded-full p-2 opacity-70 transition-all duration-200 hover:bg-black/10 hover:opacity-100 rtl:ml-1 rtl:mr-0"
                    >
                        <svg
                            aria-hidden="true"
                            class="h-5 w-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                clip-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                fill-rule="evenodd"
                            ></path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </template>
</div>
