<div class="space-y-10">
    <x-table-wrapper heading="Guards Recent Violation">
        <flux:table>
            <flux:table.columns class="bg-zinc-100 dark:bg-zinc-800 px-6">
                <flux:table.column class="text-lg! pl-4!">Student ID</flux:table.column>
                <flux:table.column class="text-lg!">Student Name</flux:table.column>
                <flux:table.column class="text-lg!">Violation</flux:table.column>
                <flux:table.column class="text-lg!">Date & Time</flux:table.column>
                <flux:table.column align="center" class="text-lg!">Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->violations as $violation)
                    <flux:table.row :key="$violation->id">

                        <flux:table.cell class="text-lg! tabular-nums pl-4!" variant="strong">
                            {{ $violation->student_id }}
                        </flux:table.cell>

                        <flux:table.cell class="text-lg!">
                            {{ $violation->st_last_name }}, {{ $violation->st_first_name }} {{ $violation->st_mi }}.
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="space-y-1">
                                <p class="whitespace-normal text-lg font-medium">
                                    {{ $violation->type_code }} - {{ $violation->type_name }}
                                </p>
                                @if ($violation->remark)
                                    <p class="text-sm text-gray-500">{{ $violation->remark }}</p>
                                @endif
                            </div>
                        </flux:table.cell>

                        <flux:table.cell class="text-lg! tabular-nums">
                            <p>
                                {{ $violation->created_at->format('M d, y') ?? 'N/A' }}
                            </p>
                            <p>
                                {{ $violation->created_at->format('h:i A') ?? 'N/A' }}
                            </p>
                        </flux:table.cell>

                        <flux:table.cell align="center">
                            <flux:button
                                @click="$dispatch('request-delete-violation', { id: {{ $violation->id }} });"
                                icon="trash"
                                variant="danger"
                            >Delete</flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell class="py-12 text-center" colspan="5">
                            <div class="flex flex-col items-center gap-2">
                                <flux:icon class="h-10 w-10 text-zinc-300" name="check-circle" />
                                <flux:text class="text-zinc-400">No recent violations</flux:text>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </x-table-wrapper>

    <x-table-wrapper heading="Delete Requests">
        <flux:table>
            <flux:table.columns class="bg-zinc-100 dark:bg-zinc-800 px-6">
                <flux:table.column class="text-lg! px-4!">Student ID</flux:table.column>
                <flux:table.column class="text-lg!">Student Name</flux:table.column>
                <flux:table.column class="text-lg!">Violation</flux:table.column>
                <flux:table.column class="text-lg!">Date & Time</flux:table.column>
                <flux:table.column align="center" class="text-lg!">Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->deleteRequests as $request)

                @empty
                    <flux:table.row>
                        <flux:table.cell class="py-12 text-center" colspan="5">
                            <div class="flex flex-col items-center gap-2">
                                <flux:icon class="h-10 w-10 text-zinc-300" name="check-circle" />
                                <flux:text class="text-zinc-400">No recent delete requests</flux:text>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </x-table-wrapper>

    @teleport('body')
        <div>
            <livewire:modals.violations.request-delete />
        </div>
    @endteleport
</div>
