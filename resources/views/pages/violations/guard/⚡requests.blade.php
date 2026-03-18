<?php

use App\Models\ViolationDeleteRequest;
use App\Models\ViolationUpdateRequest;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::guard', ['title' => 'Violation Request'])] class extends Component {
    #[Computed]
    public function deleteRequests()
    {
        return ViolationDeleteRequest::query()
            ->whereHas('violation')
            ->where('requested_by', auth()->id())
            ->whereDate('created_at', today())
            ->latest()
            ->get();
    }

    #[Computed]
    public function updateRequests()
    {
        return ViolationUpdateRequest::query()
            ->whereHas('violation')
            ->where('requested_by', auth()->id())
            ->whereDate('created_at', today())
            ->latest()
            ->get();
    }
};
?>

<div class="space-y-6">
    <x-table-wrapper heading="Delete Requests">
        <flux:table>
            <flux:table.columns class="bg-blue-100 px-6 dark:bg-zinc-800">
                <flux:table.column class="text-lg! px-4!"><strong>Student ID</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Student Name</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Violation</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Reason</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Date & Time</strong></flux:table.column>
                <flux:table.column align="center" class="text-lg! pr-4!"><strong>Status</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Denial Reason</strong></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->deleteRequests as $request)
                    <flux:table.row :key="$request->id">
                        <flux:table.cell class="text-lg! px-4! tabular-nums" variant="strong">
                            {{ $request->violation->student_id }}
                        </flux:table.cell>
                        <flux:table.cell class="text-lg!">
                            {{ $request->violation->st_last_name }}, {{ $request->violation->st_first_name }}
                            {{ $request->violation->st_mi }}.
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="space-y-1">
                                <p class="whitespace-normal text-lg font-medium">
                                    {{ $request->violation->type_code }} - {{ $request->violation->type_name }}
                                </p>
                                @if ($request->violation->remark)
                                    <p class="text-lg text-gray-500">{{ $request->violation->remark }}</p>
                                @endif
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-lg!">{{ $request->reason }}</flux:table.cell>
                        <flux:table.cell class="text-lg! tabular-nums">
                            <p>{{ $request->created_at->format('M d, Y') }}</p>
                            <p>{{ $request->created_at->format('h:i:s A') }}</p>
                        </flux:table.cell>
                        <flux:table.cell align="center" class="pr-4!">
                            <flux:badge :color="match ($request->status) {
                                'pending' => 'yellow',
                                'approved' => 'green',
                                'denied' => 'red',
                                default => 'zinc',
                            }">
                                {{ ucfirst($request->status) }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-lg!">
                            @if ($request->status === 'denied' && $request->denial_reason)
                                <p class="text-red-500">{{ $request->denial_reason }}</p>
                            @else
                                <p class="text-zinc-400">—</p>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell class="py-12 text-center" colspan="7">
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

    <x-table-wrapper heading="Update Requests">
        <flux:table>
            <flux:table.columns class="bg-blue-100 px-6 dark:bg-zinc-800">
                <flux:table.column class="text-lg! px-4!"><strong>Student ID</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Student Name</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Violation</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>New Remark</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Reason</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Date & Time</strong></flux:table.column>
                <flux:table.column align="center" class="text-lg! pr-4!"><strong>Status</strong></flux:table.column>
                <flux:table.column class="text-lg!"><strong>Denial Reason</strong></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->updateRequests as $request)
                    <flux:table.row :key="$request->id">
                        <flux:table.cell class="text-lg! px-4! tabular-nums" variant="strong">
                            {{ $request->violation->student_id }}
                        </flux:table.cell>
                        <flux:table.cell class="text-lg!">
                            {{ $request->violation->st_last_name }}, {{ $request->violation->st_first_name }}
                            {{ $request->violation->st_mi }}.
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="space-y-1">
                                <p class="whitespace-normal text-lg font-medium">
                                    {{ $request->violation->type_code }} - {{ $request->violation->type_name }}
                                </p>
                                @if ($request->violation->remark)
                                    <p class="text-lg text-gray-500">{{ $request->violation->remark }}</p>
                                @endif
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-lg!">{{ $request->new_remark }}</flux:table.cell>
                        <flux:table.cell class="text-lg!">{{ $request->reason }}</flux:table.cell>
                        <flux:table.cell class="text-lg! tabular-nums">
                            <p>{{ $request->created_at->format('M d, Y') }}</p>
                            <p>{{ $request->created_at->format('h:i:s A') }}</p>
                        </flux:table.cell>
                        <flux:table.cell align="center" class="pr-4!">
                            <flux:badge :color="match ($request->status) {
                                'pending' => 'yellow',
                                'approved' => 'green',
                                'denied' => 'red',
                                default => 'zinc',
                            }">
                                {{ ucfirst($request->status) }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-lg!">
                            @if ($request->status === 'denied' && $request->denial_reason)
                                <p class="text-red-500">{{ $request->denial_reason }}</p>
                            @else
                                <p class="text-zinc-400">—</p>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell class="py-12 text-center" colspan="8">
                            <div class="flex flex-col items-center gap-2">
                                <flux:icon class="h-10 w-10 text-zinc-300" name="check-circle" />
                                <flux:text class="text-zinc-400">No recent update requests</flux:text>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </x-table-wrapper>
</div>
