<div>
    <div class="mx-auto">
        <div class="mb-8 flex items-center justify-between">
            @foreach ($this->stages as $stage)
                @php
                    $isCurrent = $stage->id === $this->stage->id;
                    $isComplete = $stage->is_complete;
                @endphp

                <a
                    class="flex items-center"
                    href="{{ route('staff.violations.detail', [
                        'violation' => $this->violation,
                        'stage' => $stage->id,
                    ]) }}"
                    wire:navigate
                >
                    <div
                        class="{{ $isCurrent ? 'ring-2 ring-accent-content rounded!' : '' }} {{ $stage->is_complete
                            ? 'bg-accent text-white'
                            : ($stage->file_path || $stage->remark
                                ? 'bg-yellow-500'
                                : 'border-2 border-slate-700 text-slate-400') }} flex h-10 w-10 items-center justify-center rounded-full font-semibold">
                        {{ $stage->order }}
                    </div>

                    <div class="ml-4">
                        <div class="{{ $stage->is_complete ? 'text-slate-200' : 'text-slate-400' }} text-sm font-medium">
                            {{ $stage->name }}
                        </div>

                        <div class="text-xs text-slate-500">
                            {{ $stage->is_complete ? 'Complete' : 'Pending' }}
                        </div>
                    </div>
                </a>

                @if (!$loop->last)
                    <div class="mx-4 h-px flex-1 bg-slate-700"></div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="mx-auto flex max-w-7xl flex-col gap-6 rounded-xl bg-white p-8 shadow-lg dark:bg-slate-900">
        {{ $this->violation->status }}
        @if ($this->stage->remark)
            <flux:callout
                color="indigo"
                icon:variant="outline"
                icon="chat-bubble-bottom-center-text"
            >
                <flux:callout.heading>Remarks</flux:callout.heading>

                <flux:callout.text>
                    {{ $this->stage->remark }}
                </flux:callout.text>
            </flux:callout>
        @endif

        @if ($this->stage->file_path)
            <flux:card>
                @if (in_array($this->fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <img
                        alt="Attachment"
                        class="mx-auto max-h-96 w-auto rounded-lg shadow-md"
                        src="{{ asset('storage/' . $this->stage->file_path) }}"
                    >
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-sm text-slate-300">{{ $this->stage->file_name }}</span>
                        <flux:link
                            class="text-sm"
                            href="{{ asset('storage/' . $this->stage->file_path) }}"
                            rel="noopener"
                            target="_blank"
                        >
                            Open in new tab →
                        </flux:link>
                    </div>
                @elseif($this->fileExtension === 'pdf')
                    <iframe
                        allowtransparency="true"
                        class="h-96 w-full rounded-lg border border-slate-700 shadow-inner"
                        src="{{ asset('storage/' . $this->stage->file_path) }}"
                    ></iframe>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-sm text-slate-300">{{ $this->stage->file_name }}</span>
                        <flux:link
                            class="text-sm"
                            href="{{ asset('storage/' . $this->stage->file_path) }}"
                            rel="noopener"
                            target="_blank"
                        >
                            Open in new tab →
                        </flux:link>
                    </div>
                @else
                    <div class="flex items-center justify-between">
                        <span class="text-slate-300">{{ $this->stage->file_name }}</span>
                        <flux:link
                            href="{{ asset('storage/' . $this->stage->file_path) }}"
                            rel="noopener"
                            target="_blank"
                        >
                            Download File
                        </flux:link>
                    </div>
                @endif
            </flux:card>
        @else
            <div class="rounded-lg border-2 border-dashed border-slate-700 p-12 text-center">
                <svg
                    class="mx-auto h-12 w-12 text-slate-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                    />
                </svg>
                <p class="mt-2 text-slate-400">No file attached</p>
            </div>
        @endif

        <flux:separator />

        <div class="flex justify-between">

            <div class="flex gap-2">
                <flux:modal.trigger name="edit-status">
                    <flux:button icon="pencil-square" variant="primary">Edit Stage Details</flux:button>
                </flux:modal.trigger>

                <flux:modal.trigger name="clear-details">
                    <flux:button icon="x-mark" variant="filled">Clear</flux:button>
                </flux:modal.trigger>
            </div>

            @if (
                $this->violation->current_stage->id === $this->stage->id ||
                    $this->violation->last_completed_stage?->id === $this->stage->id)
                <flux:button
                    color="{{ $this->stage->is_complete ? 'rose' : 'green' }}"
                    icon="{{ $this->stage->is_complete ? 'arrow-uturn-left' : 'check' }}"
                    variant="primary"
                    wire:click="toggleComplete"
                >
                    {{ $this->stage->is_complete ? 'Set as Incomplete' : 'Set as Complete' }}
                </flux:button>
            @endif
        </div>
    </div>

    <div class="mx-auto mt-6 flex max-w-7xl justify-between gap-4">
        <flux:button
            icon="arrow-left"
            variant="ghost"
            wire:click="previous"
        >
            Previous
        </flux:button>
        <flux:button
            icon:trailing="arrow-right"
            variant="primary"
            wire:click="next"
        >
            Next Stage
        </flux:button>
    </div>

    <flux:modal class="md:w-96" name="edit-status">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Update Stage</flux:heading>
                <flux:subheading class="mt-1">Add remarks or attach files to this stage.</flux:subheading>
            </div>
            <flux:textarea
                label="Remarks"
                placeholder="Enter any notes or comments..."
                rows="4"
                wire:model="remarks"
            ></flux:textarea>
            <flux:input
                label="File Attachment"
                type="file"
                wire:model="attachment"
            ></flux:input>
            <div class="flex gap-3">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button
                    type="submit"
                    variant="primary"
                    wire:click="confirm"
                >
                    Save Changes
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal class="md:w-96" name="clear-details">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Clear Details</flux:heading>
                <flux:subheading class="mt-1">Choose what to clear from this stage.</flux:subheading>
            </div>

            <flux:checkbox.group>
                <flux:checkbox label="File" wire:model="fileClear" />
                <flux:checkbox label="Remark" wire:model="remarkClear" />
            </flux:checkbox.group>

            <div class="flex gap-3">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button
                    type="submit"
                    variant="primary"
                    wire:click="clearDetails"
                >
                    Save Changes
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
