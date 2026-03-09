<x-table-wrapper heading="Guards Recent Violation">
    <div class="p-6">
        <flux:table>
            <flux:table.columns>
                <flux:table.column class="text-lg!">Student ID</flux:table.column>
                <flux:table.column class="text-lg!">Student Name</flux:table.column>
                <flux:table.column class="text-lg!">Violation</flux:table.column>
                <flux:table.column class="text-lg!">Date & Time</flux:table.column>
                <flux:table.column align="center" class="text-lg!">Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->violations as $violation)
                    <flux:table.row :key="$violation->id">

                        <flux:table.cell class="text-lg! tabular-nums" variant="strong">
                            {{ $violation->student_id }}
                        </flux:table.cell>

                        <flux:table.cell class="text-lg!">
                            {{ $violation->student_name }}
                        </flux:table.cell>

                        <flux:table.cell class="text-lg!">
                            @if ($violation->violation_type_code_snapshot === 'C.3.9')
                                {{ $violation->violation_remark_snapshot }}
                            @else
                                {{ $violation->violation_type_code_snapshot }} -
                                {{ $violation->violation_type_name_snapshot }}
                            @endif
                        </flux:table.cell>

                        <flux:table.cell class="text-lg! tabular-nums">
                            {{ $violation->created_at->format('h:i A') }}
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
    </div>

    @teleport('body')
        <div>
            <livewire:modals.violations.request-delete />
        </div>
    @endteleport
</x-table-wrapper>
