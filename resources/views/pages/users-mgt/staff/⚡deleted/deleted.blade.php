<div>
    <x-table-wrapper heading="Deactivated Users">
        <div
            class="flex flex-wrap items-center gap-2 border-zinc-200 bg-zinc-100 p-4 dark:border-white/10 dark:bg-white/5">
            <div class="min-w-48 max-w-72 flex-1">
                <flux:input
                    icon="magnifying-glass"
                    placeholder="Search archived users..."
                    wire:model.live.debounce.500ms="search"
                />
            </div>

            <flux:separator vertical />

            <div class="w-44">
                <flux:select placeholder="All Roles" wire:model.live="role">
                    <flux:select.option value="">All Roles</flux:select.option>
                    <flux:select.option value="guard">Guard</flux:select.option>
                    <flux:select.option value="osa">Staff</flux:select.option>
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
            >
                Clear Filters
            </flux:button>

            <div class="flex-1"></div>
        </div>

        <div>
            <flux:table>
                <flux:table.columns class="bg-blue-100 px-6 dark:bg-zinc-800">
                    <flux:table.column
                        :direction="$sortDirection"
                        :sorted="$sortBy === 'name'"
                        class="px-4!"
                        sortable
                        wire:click="sort('name')"
                    ><strong>Name</strong></flux:table.column>
                    <flux:table.column
                        :direction="$sortDirection"
                        :sorted="$sortBy === 'role'"
                        sortable
                        wire:click="sort('role')"
                    >Role</flux:table.column>
                    <flux:table.column
                        :direction="$sortDirection"
                        :sorted="$sortBy === 'assigned_gate'"
                        sortable
                        wire:click="sort('assigned_gate')"
                    >Assigned Gate</flux:table.column>
                    <flux:table.column
                        :direction="$sortDirection"
                        :sorted="$sortBy === 'username'"
                        sortable
                        wire:click="sort('username')"
                    >Username</flux:table.column>
                    <flux:table.column align="center">Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->users as $user)
                        <flux:table.row :key="$user->id">
                            <flux:table.cell class="px-4! w-32">
                                <div class="font-medium leading-snug">{{ $user->name }}</div>
                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:badge
                                    :color="match ($user->role) {
                                        'guard' => 'blue',
                                        'staff' => 'violet',
                                        default => 'zinc',
                                    }"
                                    rounded
                                    size="sm"
                                >
                                    {{ $user->role_label }}
                                </flux:badge>
                            </flux:table.cell>

                            <flux:table.cell class="whitespace-nowrap">
                                {{ $user->assigned_gate ?? '' }}
                            </flux:table.cell>

                            <flux:table.cell class="tabular-nums">
                                {{ $user->username }}
                            </flux:table.cell>

                            <flux:table.cell align="center">
                                <flux:dropdown position="left">
                                    <flux:button
                                        icon="ellipsis-horizontal"
                                        inset="top bottom"
                                        variant="ghost"
                                    />
                                    <flux:menu>
                                        <flux:menu.item
                                            @click="$dispatch('restore-user', { id: {{ $user->id }} });"
                                            icon="archive-box-x-mark"
                                            variant="success"
                                        >
                                            Reactivate
                                        </flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell class="py-12 text-center" colspan="5">
                                <div class="flex flex-col items-center gap-3">
                                    <flux:icon class="h-14 w-14 text-zinc-300" name="inbox" />
                                    <div>
                                        <flux:text class="font-medium text-zinc-500">No users found</flux:text>
                                        <flux:text class="mt-1 text-sm text-zinc-400">
                                            @if ($search || $role || $gate)
                                                Try adjusting your filters or search terms
                                            @else
                                                No users have been deactivated yet
                                            @endif
                                        </flux:text>
                                    </div>
                                    @if ($search || $role || $gate)
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
            <flux:pagination :paginator="$this->users" class="p-4" />
        </div>

        <x-slot:actions>
            <flux:button
                class="w-full"
                href="{{ route('staff.users-mgt.index') }}"
                icon="archive-box"
                wire:navigate
            >
                Back to Active Accounts
            </flux:button>
        </x-slot:actions>
    </x-table-wrapper>

    @teleport('body')
        <div>
            <livewire:modals.users-mgt.restore-user />
        </div>
    @endteleport
</div>
