<div>
    <x-table-wrapper heading="Request Management">
        <div
            class="{{ $this->requests->isEmpty() ? 'items-center justify-center' : '' }} flex min-h-[46rem] flex-col gap-2 p-6 pb-4 pt-4">
            @forelse ($this->requests as $request)
                <flux:card
                    @click="open = !open"
                    class="w-full"
                    wire:key="request-{{ $request->id }}"
                    x-data="{ open: false }"
                >
                    {{-- Collapsed Header --}}
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex flex-col gap-1">
                            <flux:heading size="lg">
                                <flux:link
                                    href="{{ route('staff.violations.student', $request->violation->student_id) }}"
                                    wire:navigate
                                >
                                    {{ $request->violation->student_id }}
                                </flux:link>
                                {{ $request->violation->st_last_name }}, {{ $request->violation->st_first_name }}
                                {{ $request->violation->st_mi }}.
                            </flux:heading>

                            <flux:text>
                                {{ $request->violation->type_code }}
                                —
                                {{ $request->violation->type_name }}
                            </flux:text>
                        </div>
                        <flux:text>{{ $request->created_at->format('M j, Y - h:i A') }}</flux:text>
                    </div>

                    {{-- Expanded Content --}}
                    <div x-show="open">
                        <flux:separator class="my-4" />

                        <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-3">
                            <flux:callout class="flex flex-col text-center" color="green">
                                <flux:heading>Current</flux:heading>
                                <flux:text>{{ $request->violation->type_name }}</flux:text>
                            </flux:callout>
                            <flux:icon name="arrow-long-right" />
                            <flux:callout class="flex flex-col text-center" color="red">
                                <flux:heading>Requested</flux:heading>
                                <flux:text>Delete</flux:text>
                            </flux:callout>
                        </div>

                        <div class="mt-4">
                            <flux:heading size="lg">Reason</flux:heading>
                            <flux:text>{{ $request->reason }}</flux:text>
                        </div>

                        <div class="mb-4 mt-4">
                            <flux:heading size="lg">Requested By:</flux:heading>
                            <flux:text>{{ $request->requestedBy->name }}</flux:text>
                        </div>

                        <flux:separator />

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <flux:button
                                @click="$dispatch('approve-delete', {
                                    violationId: {{ $request->violation->id }},
                                    violationRequestId: {{ $request->id }},
                                });"
                                icon="arrow-path"
                                variant="primary"
                            >
                                Approve
                            </flux:button>
                            <flux:button
                                @click.stop="$dispatch('reject-delete', { violationRequestId: {{ $request->id }} });"
                                icon="x-mark"
                                variant="danger"
                            >
                                Reject
                            </flux:button>
                        </div>
                    </div>
                </flux:card>
            @empty
                <div class="flex flex-col items-center gap-3 text-center">
                    <flux:icon class="h-12 w-12 text-gray-400" name="check-circle" />
                    <flux:heading size="xl">No pending requests</flux:heading>
                    <flux:text class="text-xl">All delete requests have been reviewed</flux:text>
                </div>
            @endforelse
        </div>
    </x-table-wrapper>

    @teleport('body')
        <div>
            <livewire:modals.violations.reject-delete />
            <livewire:modals.violations.approve-delete />
        </div>
    @endteleport
</div>
