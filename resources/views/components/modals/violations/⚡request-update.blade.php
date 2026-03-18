<?php

use App\Models\Violation;
use App\Models\ViolationRemark;
use App\Models\ViolationRequestReason;
use App\Models\ViolationUpdateRequest;
use App\Models\ViolationType;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $violationId;
    public $violationTypeCode;
    public $currentRemark;
    public $newRemark = '';
    public $reason = '';

    #[Computed]
    public function presetRemarks()
    {
        if (!$this->violationTypeCode) {
            return collect();
        }

        return ViolationType::where('code', $this->violationTypeCode)->first()?->remarks()->select('id', 'label')->get() ?? collect();
    }

    #[Computed]
    public function presetReasons()
    {
        return ViolationRequestReason::forUpdate()->get();
    }

    #[On('request-update-violation')]
    public function setFields(int $id, ?string $remark = null, string $typeCode = ''): void
    {
        $this->violationId = $id;
        $this->violationTypeCode = $typeCode;
        $this->currentRemark = $remark;

        $existing = ViolationUpdateRequest::where('violation_id', $id)->where('status', 'pending')->first();

        $this->newRemark = $existing?->new_remark ?? ($remark ?? '');
        $this->reason = $existing?->reason ?? '';

        $this->modal('request-update')->show();
    }

    public function selectRemark(int $id): void
    {
        $this->newRemark = ViolationRemark::find($id)?->label ?? '';
    }

    public function selectReason(int $id): void
    {
        $this->reason = ViolationRequestReason::find($id)?->label ?? '';
    }

    public function submit(): void
    {
        $this->validate([
            'newRemark' => 'required|string|min:3',
            'reason' => 'required|string|min:10',
        ]);

        $request = ViolationUpdateRequest::updateOrCreate(['violation_id' => $this->violationId, 'status' => 'pending'], ['requested_by' => auth()->id(), 'new_remark' => $this->newRemark, 'reason' => $this->reason]);

        if ($request->wasRecentlyCreated) {
            Violation::where('id', $this->violationId)->update([
                'is_active' => false,
            ]);
        }

        User::osa()->get()->each->notify(new DatabaseNotification(title: 'Update Request', message: "Violation #{$this->violationId} — remark change requested: {$this->newRemark}", type: 'warning', actionUrl: route('staff.violations.update-requests')));

        Toaster::success('Update request submitted.');
        $this->modal('request-update')->close();
    }
};
?>

<flux:modal class="w-full max-w-md sm:max-w-96 md:max-w-5xl" name="request-update">
    <div class="space-y-6">
        <div>
            <flux:heading size="xl">Request remark update</flux:heading>
            <flux:subheading class="mt-1 text-lg">Choose a new remark and provide a reason for this change.
            </flux:subheading>
        </div>

        <flux:separator />

        @if ($currentRemark)
            <div class="rounded-xl bg-zinc-100 px-5 py-4 dark:bg-zinc-700">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Current remark</p>
                <p class="mt-1 text-lg font-medium text-zinc-800 dark:text-zinc-100">{{ $currentRemark }}</p>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-6">

            <div class="space-y-3">
                <flux:label class="text-lg! font-medium!">New remark</flux:label>
                <flux:textarea
                    class="text-lg!"
                    placeholder="Type a custom remark here..."
                    rows="3"
                    wire:model="newRemark"
                />
                <p class="text-sm text-zinc-400">Or select from the list below</p>
                <div class="max-h-72 space-y-2 overflow-y-auto pr-1">
                    @forelse ($this->presetRemarks as $preset)
                        <button
                            :class="$wire.newRemark === @js($preset->label) ?
                                'border-blue-500 bg-blue-50 dark:bg-blue-950/30' :
                                'border-zinc-300 hover:border-blue-400 hover:bg-blue-50 dark:border-zinc-600 dark:hover:bg-blue-950/30'"
                            class="group flex w-full items-center justify-between rounded-xl border-2 px-5 py-4 text-left text-lg transition-all focus:outline-none focus:ring-4 focus:ring-blue-400 active:scale-95"
                            type="button"
                            wire:click="selectRemark({{ $preset->id }})"
                            wire:key="remark-preset-{{ $preset->id }}"
                        >
                            <span
                                class="leading-snug text-zinc-700 group-hover:text-zinc-900 dark:text-zinc-300 dark:group-hover:text-zinc-100"
                            >
                                {{ $preset->label }}
                            </span>
                            <flux:icon
                                class="h-6 w-6 shrink-0 text-blue-500"
                                name="check-circle"
                                x-show="$wire.newRemark === @js($preset->label)"
                            />
                        </button>
                    @empty
                        <p class="text-base text-zinc-400">No preset remarks available.</p>
                    @endforelse
                </div>
            </div>

            <div class="space-y-3">
                <flux:label class="text-lg! font-medium!">Reason for update</flux:label>
                <flux:textarea
                    class="text-lg!"
                    placeholder="Type a custom reason here..."
                    rows="3"
                    wire:model="reason"
                />
                <p class="text-sm text-zinc-400">Or select from the list below</p>
                <div class="max-h-72 space-y-2 overflow-y-auto pr-1">
                    @foreach ($this->presetReasons as $preset)
                        <button
                            :class="$wire.reason === @js($preset->label) ?
                                'border-blue-500 bg-blue-50 dark:bg-blue-950/30' :
                                'border-zinc-300 hover:border-blue-400 hover:bg-blue-50 dark:border-zinc-600 dark:hover:bg-blue-950/30'"
                            class="group flex w-full items-center justify-between rounded-xl border-2 px-5 py-4 text-left text-lg transition-all focus:outline-none focus:ring-4 focus:ring-blue-400 active:scale-95"
                            type="button"
                            wire:click="selectReason({{ $preset->id }})"
                            wire:key="reason-preset-{{ $preset->id }}"
                        >
                            <span
                                class="leading-snug text-zinc-700 group-hover:text-zinc-900 dark:text-zinc-300 dark:group-hover:text-zinc-100"
                            >
                                {{ $preset->label }}
                            </span>
                            <flux:icon
                                class="h-6 w-6 shrink-0 text-blue-500"
                                name="check-circle"
                                x-show="$wire.reason === @js($preset->label)"
                            />
                        </button>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="flex gap-3 border-t border-zinc-200 pt-5 dark:border-zinc-700">
            <flux:spacer />
            <flux:modal.close>
                <flux:button
                    class="text-lg! px-6 py-3"
                    size="lg"
                    variant="ghost"
                >Cancel
                </flux:button>
            </flux:modal.close>
            <flux:button
                class="text-lg! px-6 py-3"
                size="lg"
                variant="primary"
                wire:click="submit"
            >
                Submit update request
            </flux:button>
        </div>
    </div>
</flux:modal>
