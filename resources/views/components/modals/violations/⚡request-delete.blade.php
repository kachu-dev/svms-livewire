<?php

use App\Models\User;
use App\Models\Violation;
use App\Models\ViolationDeleteRequest;
use App\Models\ViolationRequestReason;
use App\Notifications\DatabaseNotification;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $violationId;
    public $reason = '';

    #[Computed]
    public function presetReasons()
    {
        return ViolationRequestReason::forDelete()->get();
    }

    #[On('request-delete-violation')]
    public function setFields(int $id): void
    {
        $this->violationId = $id;

        $existing = ViolationDeleteRequest::where('violation_id', $id)->where('status', 'pending')->first();

        $this->reason = $existing?->reason ?? '';
        $this->modal('request-delete')->show();
    }

    public function selectPreset(int $id): void
    {
        $this->reason = ViolationRequestReason::find($id)?->label ?? '';
    }

    public function submit(): void
    {
        $this->validate(['reason' => 'required|string|min:10']);

        $request = ViolationDeleteRequest::updateOrCreate(['violation_id' => $this->violationId, 'status' => 'pending'], ['requested_by' => auth()->id(), 'reason' => $this->reason, 'type' => 'delete']);

        if ($request->wasRecentlyCreated) {
            Violation::where('id', $this->violationId)->update([
                'is_active' => false,
            ]);
        }

        User::osa()->get()->each->notify(
            new DatabaseNotification(
                title: 'Delete Request',
                message: "Violation #{$this->violationId} delete requested: {$this->reason}",
                type: 'warning',
                actionUrl: route('staff.violations.delete-requests'),
                meta: [
                    'violation_id' => $this->violationId,
                ],
            ),
        );

        Toaster::success('Delete request submitted.');
        $this->modal('request-delete')->close();
    }
};
?>

<flux:modal class="w-full max-w-md sm:max-w-96 md:max-w-3xl" name="request-delete">
    <div class="space-y-6">
        <div>
            <flux:heading size="xl">Request Delete</flux:heading>
            <flux:subheading class="text-base">Select a preset reason or type a custom one</flux:subheading>
        </div>

        <flux:separator />

        <flux:textarea
            label:size="lg"
            label="Reason"
            placeholder="Enter or select a reason for deletion"
            size="accessible"
            wire:model="reason"
        />

        <flux:subheading class="text-lg font-bold">Preset Reasons</flux:subheading>

        <div class="max-h-72 space-y-3 overflow-y-auto">
            @foreach ($this->presetReasons as $preset)
                <button
                    :class="$wire.reason === @js($preset->label) ?
                        'border-red-500 bg-red-50 dark:bg-red-950/30' :
                        'border-zinc-500 hover:border-red-500 hover:bg-red-50 dark:border-zinc-600 dark:hover:bg-red-950/30'"
                    class="group w-full rounded-xl border-2 p-5 text-left transition-all focus:outline-none focus:ring-4 focus:ring-red-400 active:scale-95"
                    type="button"
                    wire:click="selectPreset({{ $preset->id }})"
                    wire:key="delete-preset-{{ $preset->id }}"
                >
                    <div class="flex items-center justify-between">
                        <p
                            class="text-base text-zinc-700 group-hover:text-zinc-900 dark:text-zinc-300 dark:group-hover:text-zinc-100">
                            {{ $preset->label }}
                        </p>
                        <flux:icon
                            class="h-5 w-5 shrink-0 text-red-500"
                            name="check-circle"
                            x-show="$wire.reason === @js($preset->label)"
                        />
                    </div>
                </button>
            @endforeach
        </div>

        <div class="flex gap-2 border-t border-zinc-200 pt-4 dark:border-zinc-700">
            <flux:spacer />
            <flux:modal.close>
                <flux:button size="lg" variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button
                size="lg"
                variant="danger"
                wire:click="submit"
            >
                Request Delete
            </flux:button>
        </div>
    </div>
</flux:modal>
