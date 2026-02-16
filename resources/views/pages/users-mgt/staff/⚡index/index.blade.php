<div>
    <x-card header="Policy Types" icon="document-text">
        <div class="mb-6 grid grid-cols-1 gap-3 md:grid-cols-12">
            <div class="md:col-span-4">
                <flux:input
                    icon="magnifying-glass"
                    label="Search"
                    placeholder="Search by ID or name..."
                    wire:model.live.debounce.500ms="search"
                />
            </div>

            <div class="flex gap-3 md:col-span-4 md:items-end">
                <flux:button
                    class="w-full"
                    href="{{ route('staff.policy.deleted') }}"
                    icon="archive-box"
                >
                    Deactivated Accounts
                </flux:button>

                <flux:button
                    @click="$flux.modal('create-policy').show()"
                    class="w-full"
                    icon="plus-circle"
                >
                    Create New User
                </flux:button>

            </div>
        </div>

        <div class="rounded border-t-4 border-t-blue-500 p-4 shadow">
            <flux:table :paginate="$this->users">
                <flux:table.columns>
                    <flux:table.column>ID</flux:table.column>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Role</flux:table.column>
                    <flux:table.column>Assigned Gate</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($this->users as $user)
                        <flux:table.row>
                            <flux:table.cell>{{ $user->id }}</flux:table.cell>
                            <flux:table.cell>{{ $user->email }}</flux:table.cell>
                            <flux:table.cell>{{ $user->name }}</flux:table.cell>
                            <flux:table.cell>{{ $user->role }}</flux:table.cell>
                            <flux:table.cell>{{ $user->assigned_gate }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown position="left">
                                    <flux:button
                                        icon="ellipsis-horizontal"
                                        inset="top bottom"
                                        size="sm"
                                        variant="ghost"
                                    />
                                    <flux:menu>
                                        <flux:menu.item
                                            @click="
                                                $dispatch('update-policy', { id: {{ $user->id }} });
                                                                     $flux.modal('update-policy').show()
                                            "
                                            icon="arrow-path"
                                        >Update
                                        </flux:menu.item>
                                        <flux:menu.item
                                            @click="$dispatch('confirm-delete-policy', {
                                                id: {{ $user->id }},
                                            }); $flux.modal('delete-policy').show()"
                                            icon="archive-box-x-mark"
                                        >
                                            Deactivate</flux:menu.item>
                                        <flux:menu.separator />
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell class="py-12 text-center" colspan="7">
                                <div class="flex flex-col items-center gap-3">
                                    <flux:icon class="h-16 w-16 text-gray-300" name="inbox" />
                                    <div>
                                        <p class="font-medium text-gray-600">No policies found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            @if ($search)
                                                Try adjusting your filters or search terms
                                            @else
                                                No policies have been recorded yet
                                            @endif
                                        </p>
                                    </div>
                                    @if ($search)
                                        <flux:button
                                            icon="arrow-path"
                                            size="sm"
                                            variant="ghost"
                                            wire:click="resetFilters"
                                        >
                                            Reset Filters
                                        </flux:button>
                                    @endif
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </x-card>

    <livewire:violations.modals.create-policy />
    <livewire:violations.modals.update-policy />
    <livewire:violations.modals.delete-policy />
</div>
