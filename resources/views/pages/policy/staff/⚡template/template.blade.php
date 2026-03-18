<div>
    <div class="flex min-h-10 items-end justify-between">
        <div>
            <flux:heading size="xl">Violation Stage Templates</flux:heading>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-6 pb-6 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($this->grouped as $offenseKey => $stages)
            <div class="flex flex-col">

                <div class="mb-2 flex items-center justify-between px-1">
                    <div class="flex items-center gap-2">
                        <span
                            class="@if (str_starts_with($offenseKey, 'minor_1')) bg-emerald-400
                            @elseif(str_starts_with($offenseKey, 'minor_2')) bg-yellow-400
                            @elseif(str_starts_with($offenseKey, 'minor_3')) bg-orange-400
                            @elseif(str_contains($offenseKey, 'suspension')) bg-red-400
                            @elseif(str_contains($offenseKey, 'dismissal')) bg-rose-500
                            @else bg-purple-400 @endif h-2 w-2 rounded-full"
                        >
                        </span>
                        <span class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            {{ str_replace('_', ' ', ucwords($offenseKey, '_')) }}
                        </span>
                    </div>
                    <span
                        class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-slate-800 dark:text-slate-400"
                    >
                        {{ $stages->count() }}
                    </span>
                </div>

                <flux:card class="flex h-[300px] flex-col overflow-y-auto p-3">
                    <div class="space-y-2" wire:sort="moveStage">
                        @forelse ($stages as $stage)
                            <div
                                class="group mb-2 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm hover:border-slate-300 dark:border-slate-700 dark:bg-slate-800"
                                wire:key="{{ $stage->id }}"
                                wire:sort:item="{{ $stage->id }}"
                            >
                                <div class="flex items-center gap-2">
                                    <svg
                                        class="h-3.5 w-3.5 flex-shrink-0 cursor-grab text-slate-300 group-hover:text-slate-400"
                                        fill="none"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M3.75 9h16.5m-16.5 6.75h16.5"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>

                                    <span class="w-4 flex-shrink-0 text-center text-xs font-medium text-slate-400">
                                        {{ $stage->order }}
                                    </span>

                                    @if ($editingId === $stage->id)
                                        <input
                                            autofocus
                                            class="min-w-0 flex-1 rounded border border-slate-300 bg-white px-2 py-0.5 text-xs text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                                            type="text"
                                            wire:keydown.enter="saveEdit"
                                            wire:keydown.escape="cancelEdit"
                                            wire:model="editingName"
                                        />
                                        <button class="text-emerald-500 hover:text-emerald-400" wire:click="saveEdit">
                                            <svg
                                                class="h-3.5 w-3.5"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    clip-rule="evenodd"
                                                    d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                                    fill-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                        <button class="text-slate-400 hover:text-slate-300" wire:click="cancelEdit">
                                            <svg
                                                class="h-3.5 w-3.5"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"
                                                />
                                            </svg>
                                        </button>
                                    @else
                                        <span class="flex-1 truncate text-xs text-slate-700 dark:text-slate-200">
                                            {{ $stage->name }}
                                        </span>
                                        <div class="flex flex-shrink-0 gap-1 opacity-0 group-hover:opacity-100">
                                            <button class="text-slate-400 hover:text-blue-500"
                                                wire:click="startEdit({{ $stage->id }})"
                                            >
                                                <svg
                                                    class="h-3.5 w-3.5"
                                                    fill="currentColor"
                                                    viewBox="0 0 16 16"
                                                >
                                                    <path
                                                        d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.263a1.75 1.75 0 0 0 0-2.474Z"
                                                    />
                                                    <path
                                                        d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"
                                                    />
                                                </svg>
                                            </button>
                                            <flux:modal.trigger :name="'delete-stage-' . $stage->id">
                                                <button class="text-slate-400 hover:text-red-500">
                                                    <svg
                                                        class="h-3.5 w-3.5"
                                                        fill="currentColor"
                                                        viewBox="0 0 16 16"
                                                    >
                                                        <path
                                                            clip-rule="evenodd"
                                                            d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z"
                                                            fill-rule="evenodd"
                                                        />
                                                    </svg>
                                                </button>
                                            </flux:modal.trigger>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <flux:modal :name="'delete-stage-' . $stage->id" class="min-w-[22rem]">
                                <div class="space-y-6">
                                    <div>
                                        <flux:heading size="lg">Delete stage?</flux:heading>
                                        <flux:text class="mt-2">
                                            You're about to delete {{ $stage->name }}.<br>
                                        </flux:text>
                                    </div>
                                    <div class="flex gap-2">
                                        <flux:spacer />
                                        <flux:modal.close>
                                            <flux:button variant="ghost">Cancel</flux:button>
                                        </flux:modal.close>
                                        <flux:button
                                            type="submit"
                                            variant="danger"
                                            wire:click="deleteStage({{ $stage->id }})"
                                        >Delete stage</flux:button>
                                    </div>
                                </div>
                            </flux:modal>
                        @empty
                            <div class="flex items-center justify-center py-8 text-xs text-slate-400">
                                No stages yet
                            </div>
                        @endforelse
                    </div>

                    @if ($newOffenseKey === $offenseKey)
                        <div class="mt-1.5 flex gap-1.5">
                            <flux:input
                                class="flex-1 text-xs"
                                placeholder="Stage name..."
                                wire:keydown.enter="createStage"
                                wire:keydown.escape="$set('newOffenseKey', null)"
                                wire:model="newName"
                                x-ref="addInput"
                            />
                            <flux:button
                                variant="primary"
                                wire:click="createStage"
                            >Add</flux:button>
                        </div>
                        @error('newName')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    @endif

                </flux:card>

                <flux:button
                    size="sm"
                    @click="$nextTick(() => $refs.addInput?.focus())"
                    class="mt-2 w-full rounded-lg border border-dashed border-slate-200 py-1.5 text-xs text-slate-400 hover:border-slate-300 hover:text-slate-500 dark:border-slate-700 dark:hover:border-slate-600"
                    wire:click="$set('newOffenseKey', '{{ $offenseKey }}')"
                    x-data
                >
                    + Add stage
                </flux:button>
            </div>
        @endforeach
    </div>
</div>
