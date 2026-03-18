<div class="space-y-10">
    <x-table-wrapper heading="Guards Recent Violation">
        <flux:table>
            <flux:table.columns class="bg-blue-100 px-6 dark:bg-zinc-800">
                <flux:table.column class="text-lg! pl-4!"><strong>Student ID</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Student Name</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Violation</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Date & Time</strong></flux:table.column>
                <flux:table.column align="center" class="text-lg! pr-4!"><strong>Actions</strong></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->violations as $violation)
                    <flux:table.row :key="$violation->id">
                        <flux:table.cell class="text-lg! pl-4! tabular-nums" variant="strong">
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
                                    <p class="text-lg text-gray-500">{{ $violation->remark }}</p>
                                @endif
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-lg! tabular-nums">
                            <p>{{ $violation->created_at->format('M d, Y') ?? 'N/A' }}</p>
                            <p>{{ $violation->created_at->format('h:i:s A') ?? 'N/A' }}</p>
                        </flux:table.cell>
                        <flux:table.cell align="center" class="pr-4!">
                            <div class="flex justify-center gap-2">
                                <flux:button
                                    @click="$dispatch('request-update-violation', {
                                        id: {{ $violation->id }},
                                        remark: '{{ addslashes($violation->remark ?? '') }}',
                                        typeCode: '{{ $violation->type_code }}'
                                    });"
                                    icon="pencil"
                                    variant="primary"
                                >Edit</flux:button>
                                <flux:button
                                    @click="$dispatch('request-delete-violation', { id: {{ $violation->id }} });"
                                    icon="trash"
                                    variant="danger"
                                >Delete</flux:button>
                            </div>
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

    @teleport('body')
        <div>
            <livewire:modals.violations.request-delete />
            <livewire:modals.violations.request-update />
        </div>
    @endteleport
</div>
