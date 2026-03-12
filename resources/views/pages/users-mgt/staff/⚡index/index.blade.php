<div>
    <x-table-wrapper heading="Active Users">
        <x-slot:searches>
            <div class="flex flex-wrap items-center gap-2 p-6 pb-4 pt-4">
                <div class="min-w-48 max-w-72 flex-1">
                    <flux:input
                        icon="magnifying-glass"
                        placeholder="Search users..."
                        wire:model.live.debounce.500ms="search"
                    />
                </div>

                <flux:separator vertical />

                <div class="w-44">
                    <flux:select placeholder="All Roles" wire:model.live="role">
                        <flux:select.option value="">All Roles</flux:select.option>
                        <flux:select.option value="guard">Guard</flux:select.option>
                        <flux:select.option value="staff">Staff</flux:select.option>
                    </flux:select>
                </div>

                <flux:separator vertical />

                <div class="w-44">
                    <flux:select placeholder="All Gates" wire:model.live="gate">
                        <flux:select.option value="">All Gates</flux:select.option>
                        @foreach ($this->gates as $gate)
                            <flux:select.option value="{{ $gate }}">{{ $gate }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <flux:separator vertical />

                <flux:button
                    icon="x-mark"
                    variant="ghost"
                    wire:click="resetFilters"
                >Clear Filters</flux:button>

                <div class="flex-1"></div>
            </div>
        </x-slot:searches>

        <div class="p-6 pt-0">
            <flux:table :paginate="$this->users">
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Role</flux:table.column>
                    <flux:table.column>Assigned Gate</flux:table.column>
                    <flux:table.column>Username</flux:table.column>
                    <flux:table.column align="center">Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->users as $user)
                        <flux:table.row>
                            <flux:table.cell>{{ $user->name }}</flux:table.cell>
                            <flux:table.cell>{{ $user->role_label }}</flux:table.cell>
                            <flux:table.cell>{{ $user->assigned_gate }}</flux:table.cell>
                            <flux:table.cell>{{ $user->username }}</flux:table.cell>
                            <flux:table.cell align="center">
                                <flux:dropdown position="left">
                                    <flux:button
                                        icon="ellipsis-horizontal"
                                        inset="top bottom"
                                        size="sm"
                                        variant="ghost"
                                    />
                                    <flux:menu>
                                        <flux:menu.item
                                            @click="$dispatch('update-user', {
                                                id: {{ $user->id }},
                                            });"
                                            icon="arrow-path"
                                        >
                                            Update
                                        </flux:menu.item>
                                        <flux:menu.separator />
                                        <flux:menu.item
                                            @click="$dispatch('confirm-delete-user', {
                                                id: {{ $user->id }},
                                            });"
                                            icon="archive-box-x-mark"
                                            variant="danger"
                                        >
                                            Deactivate
                                        </flux:menu.item>
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
                                        <p class="font-medium text-gray-600">No users found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            @if ($search)
                                                Try adjusting your filters or search terms
                                            @else
                                                No users have been recorded yet
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

        <x-slot:actions>
            <flux:button
                class="w-full"
                href="{{ route('staff.users-mgt.deleted') }}"
                icon="archive-box"
                wire:navigate
            >
                Deactivated Accounts
            </flux:button>

            <flux:button
                @click="$flux.modal('create-user').show()"
                class="w-full"
                icon="plus-circle"
                variant="primary"
            >
                Create New User
            </flux:button>
        </x-slot:actions>
    </x-table-wrapper>

    @teleport('body')
        <div>
            <livewire:modals.users-mgt.delete-user />
            <livewire:modals.users-mgt.update-user />
            <livewire:modals.users-mgt.create-user />
        </div>
    @endteleport
</div>
