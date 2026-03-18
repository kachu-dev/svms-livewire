<div>
    <x-table-wrapper heading="Update Request Management">
        <div
            class="flex flex-wrap items-center gap-2 border-zinc-200 bg-zinc-100 p-4 dark:border-white/10 dark:bg-white/5">
            <div class="min-w-48 max-w-72 flex-1">
                <flux:input
                    icon="magnifying-glass"
                    placeholder="Search requests..."
                    wire:model.live.debounce.500ms="search"
                />
            </div>

            <flux:separator vertical />

            <flux:button
                icon="x-mark"
                variant="ghost"
                wire:click="resetFilters"
            >
                Clear Filters
            </flux:button>

            <div class="flex-1"></div>

            @if ($this->requests->isNotEmpty())
                <span
                    class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/15 dark:text-amber-400"
                >
                    {{ $this->requests->count() }} pending
                </span>
            @endif
        </div>

        <div>
            <flux:table>
                <flux:table.columns class="bg-blue-100 px-6 dark:bg-zinc-800">
                    <flux:table.column class="p-0! px-4! w-10 border-r"><strong>ID</strong></flux:table.column>
                    <flux:table.column class="w-52">Student</flux:table.column>
                    <flux:table.column class="w-64">Violation</flux:table.column>
                    <flux:table.column align="center" class="w-56">Remark Change</flux:table.column>
                    <flux:table.column>Reason</flux:table.column>
                    <flux:table.column class="w-36">Requested by</flux:table.column>
                    <flux:table.column class="w-36">Date</flux:table.column>
                    <flux:table.column class="w-16">Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->requests as $request)
                        <flux:table.row wire:key="request-{{ $request->id }}">

                            <flux:table.cell class="px-0! pl-4! border-r border-zinc-800/10 dark:border-white/20"
                                variant="strong"
                            >
                                {{ $request->id }}
                            </flux:table.cell>

                            <flux:table.cell class="flex items-center gap-3">
                                <flux:avatar
                                    name="{{ $request->violation->st_last_name }} {{ $request->violation->st_first_name }}"
                                    size="xs"
                                />
                                <div>
                                    <div class="truncate font-medium leading-snug">
                                        {{ Str::title($request->violation->st_last_name) }},
                                        {{ Str::title($request->violation->st_first_name) }}{{ $request->violation->st_mi ? ' ' . Str::upper($request->violation->st_mi) . '.' : '' }}
                                    </div>
                                    <div class="mt-0.5 text-xs text-zinc-400">
                                        <flux:link
                                            class="tabular-nums"
                                            href="{{ route('staff.violations.student', $request->violation->student_id) }}"
                                            wire:navigate
                                        >
                                            {{ $request->violation->student_id }}
                                        </flux:link>
                                    </div>
                                </div>
                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:tooltip>
                                    <div class="w-64">
                                        <div class="flex items-baseline gap-1 truncate">
                                            <span class="shrink-0 text-sm font-medium text-blue-600 dark:text-blue-400">
                                                {{ $request->violation->type_code }}
                                            </span>
                                            <span class="truncate text-sm">{{ $request->violation->type_name }}</span>
                                        </div>
                                    </div>
                                    <flux:tooltip.content class="max-w-xs">
                                        <p class="text-sm font-medium">{{ $request->violation->type_code }} —
                                            {{ $request->violation->type_name }}</p>
                                    </flux:tooltip.content>
                                </flux:tooltip>
                            </flux:table.cell>

                            <flux:table.cell>
                                <div class="flex flex-col items-center gap-1 text-xs">
                                    <span
                                        class="rounded border border-green-200 bg-green-50 px-1.5 py-0.5 text-green-700 dark:border-green-700/40 dark:bg-green-500/10 dark:text-green-400"
                                    >
                                        {{ $request->violation->remark ?? 'N/A' }}
                                    </span>

                                    <flux:icon.arrow-long-down class="size-3.5 text-zinc-400" />

                                    <span
                                        class="rounded border border-blue-200 bg-blue-50 px-1.5 py-0.5 text-blue-700 dark:border-blue-700/40 dark:bg-blue-500/10 dark:text-blue-400"
                                    >
                                        {{ $request->new_remark }}
                                    </span>
                                </div>
                            </flux:table.cell>

                            <flux:table.cell>
                                <p class="text-sm text-zinc-700 dark:text-zinc-300">{{ $request->reason }}</p>
                            </flux:table.cell>

                            <flux:table.cell class="whitespace-nowrap">
                                <div class="text-sm">{{ $request->requestedBy->name }}</div>
                            </flux:table.cell>

                            <flux:table.cell class="whitespace-nowrap tabular-nums">
                                <div class="text-sm">{{ $request->created_at->format('M j, Y') }}</div>
                                <div class="text-xs text-zinc-400">{{ $request->created_at->format('h:i:s A') }}</div>
                            </flux:table.cell>

                            <flux:table.cell align="center" class="pr-4!">
                                <div class="flex items-center gap-4">
                                    <flux:button
                                        @click="$dispatch('approve-update', {
                                                violationId: {{ $request->violation->id }},
                                                violationRequestId: {{ $request->id }},
                                            })"
                                        class="w-full"
                                        icon="check"
                                        size="sm"
                                        variant="primary"
                                    >Approve</flux:button>
                                    <flux:button
                                        @click="$dispatch('reject-update', { violationRequestId: {{ $request->id }} })"
                                        class="w-full"
                                        icon="x-mark"
                                        size="sm"
                                        variant="danger"
                                    >Reject</flux:button>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell class="py-12 text-center" colspan="8">
                                <div class="flex flex-col items-center gap-3">
                                    <flux:icon class="h-14 w-14 text-zinc-300" name="inbox" />
                                    <div>
                                        <flux:text class="font-medium text-zinc-500">No pending requests</flux:text>
                                        <flux:text class="mt-1 text-sm text-zinc-400">All update requests have been
                                            reviewed.</flux:text>
                                    </div>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <x-slot:actions>
            <flux:button class="w-full" icon="archive-box">
                Resolved Requests
            </flux:button>
        </x-slot>

    </x-table-wrapper>

    @teleport('body')
        <div>
            <livewire:modals.violations.approve-update />
            <livewire:modals.violations.reject-update />
        </div>
    @endteleport
</div>
