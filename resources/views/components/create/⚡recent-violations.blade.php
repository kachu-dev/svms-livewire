<?php

use App\Models\Student;
use App\Models\Violation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public function render()
    {
        return $this->view([
            'violations' => Violation::latest()->take(5)->get(),
        ]);
    }
};
?>

<div class="rounded border-t-4 border-t-blue-500 bg-white shadow dark:bg-zinc-900">
    <div class="flex gap-1 border-b border-b-gray-200 p-4 dark:border-b-zinc-700">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            aria-hidden="true"
            data-slot="icon"
            fill="none"
            class="size-6 text-gray-700 dark:text-zinc-300"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12.252 6v6h4.5"
            ></path>
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M5.887 5.636A9 8.996 45 0 1 16.75 4.208a9 8.996 45 0 1 4.194 10.123 9 8.996 45 0 1-8.69 6.667 9 8.996 45 0 1-8.693-6.67m2.327-8.692L3.38 8.143M3.363 3.15v5.013m0 0h5.013"
            ></path>
        </svg>

        <h3 class="font-semibold text-gray-900 dark:text-zinc-100">Recent Violations</h3>
    </div>
    <div class="flex flex-col items-center gap-8 p-8">
        <div class="flex w-full flex-col gap-3">
            @foreach ($violations as $violation)
                <flux:card class="px-4 py-3 shadow">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex min-w-0 items-center gap-3">
                            <span
                                class="text-sm font-bold text-zinc-900 dark:text-white">{{ $violation->student_id }}</span>
                            <span
                                class="truncate text-sm font-bold text-zinc-900 dark:text-white">{{ $violation->student_name }}</span>
                        </div>
                        <span
                            class="text-sm text-zinc-400 dark:text-zinc-500">{{ $violation->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="mt-3 space-y-2">
                        <div class="text-sm font-medium text-zinc-900 dark:text-white">
                            {{ $violation->violation_type_snapshot }}</div>
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $violation->violation_remark_snapshot }}</div>
                    </div>
                </flux:card>
            @endforeach
        </div>
    </div>
</div>
