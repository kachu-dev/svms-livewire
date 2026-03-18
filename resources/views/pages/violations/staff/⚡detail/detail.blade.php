<div class="space-y-6">

    <div class="rounded-xl border border-zinc-300 bg-white px-6 py-3 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Student
                </p>
                <div class="mt-0.5 flex items-baseline gap-2">
                    <span class="text-base font-semibold text-zinc-800 dark:text-zinc-100">
                        {{ $this->violation->st_last_name }},
                        {{ $this->violation->st_first_name }}{{ $this->violation->st_mi ? ' ' . $this->violation->st_mi . '.' : '' }}
                    </span>
                    <span
                        class="text-sm tabular-nums text-zinc-400 dark:text-zinc-500">{{ $this->violation->student_id }}</span>
                </div>
            </div>

            <flux:separator vertical />

            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Violation
                </p>
                <div class="mt-0.5 flex items-baseline gap-1.5">
                    <span
                        class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $this->violation->type_code }}</span>
                    <span class="text-sm text-zinc-600 dark:text-zinc-300">{{ $this->violation->type_name }}</span>
                </div>
            </div>

            <flux:separator vertical />

            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                    Classification</p>
                <div class="mt-1">
                    <flux:badge
                        :color="match (true) {
                            str_contains($this->violation->classification, 'Expulsion') => 'red',
                            str_contains($this->violation->classification, 'Dismissal') => 'orange',
                            str_contains($this->violation->classification, 'Suspension') => 'amber',
                            default => 'green',
                        }"
                        rounded
                        size="sm"
                    >{{ $this->violation->classification }}</flux:badge>
                </div>
            </div>

            <flux:separator vertical />

            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Status
                </p>
                <div class="mt-1">
                    <flux:badge
                        :color="match ($this->violation->status) {
                            'Oral Reprimand' => 'violet',
                            'Start 2 Days Suspension' => 'amber',
                            default => 'blue',
                        }"
                        rounded
                        size="sm"
                    >{{ $this->violation->status }}</flux:badge>
                </div>
            </div>

            <flux:separator vertical />

            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Date
                    Recorded</p>
                <div class="mt-0.5 text-sm tabular-nums text-zinc-600 dark:text-zinc-300">
                    {{ $this->violation->created_at->format('M j, Y') }}
                    <span class="text-xs text-zinc-400">{{ $this->violation->created_at->format('h:i A') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="flex gap-6" style="height: calc(100vh - 18rem);">
        <div class="w-[28rem] shrink-0">
            <div class="rounded-xl border border-zinc-300 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                <div class="px-5 pb-4 pt-5">
                    <flux:text class="mb-3 text-center text-sm font-semibold uppercase tracking-[0.2em]">
                        Stages - {{ $this->stages->where('is_complete', true)->count() }}/{{ $this->stages->count() }}
                    </flux:text>

                    @php $pct = $this->stages->count() ? ($this->stages->where('is_complete', true)->count() / $this->stages->count()) * 100 : 0; @endphp
                    <flux:progress
                        class="mb-4 mt-4 h-2"
                        color="yellow"
                        value="{{ $pct }}"
                    />

                    <div class="flex flex-col gap-0.5">
                        @foreach ($this->stages as $i => $s)
                            @php
                                $prevStage = $this->stages[$i - 1] ?? null;
                                $nextStage = $this->stages[$i + 1] ?? null;
                                $isActive = $s->id === $this->stage?->id;
                                $canCheck = !$s->is_complete && ($i === 0 || $prevStage?->is_complete);
                                $canUncheck = $s->is_complete && (!$nextStage || !$nextStage->is_complete);
                                $isLocked = !$s->is_complete && !$canCheck;
                                $canToggle = $canCheck || $canUncheck;
                            @endphp

                            <div class="{{ $isActive
                                ? 'bg-accent/10 border border-accent dark:bg-accent/15'
                                : 'border-l-4 border-transparent hover:bg-white/70 dark:hover:bg-zinc-800/50' }} {{ $isLocked && !$isActive ? 'opacity-60' : '' }} group flex items-center gap-2.5 rounded-lg px-3 py-3"
                                wire:key="stage-{{ $s->id }}"
                            >
                                <button
                                    @if ($canToggle) wire:click="toggleStage({{ $s->id }})" @endif
                                    class="{{ $canToggle ? 'cursor-pointer' : 'cursor-not-allowed' }} {{ $s->is_complete
                                        ? 'bg-accent'
                                        : ($s->file_path || $s->remark
                                            ? 'border-2 border-yellow-400 bg-yellow-50 dark:bg-yellow-500/10'
                                            : 'border-2 border-zinc-400 bg-white dark:border-zinc-600 dark:bg-transparent') }} {{ $canCheck ? 'hover:border-accent' : '' }} relative flex h-7 w-7 shrink-0 items-center justify-center rounded-md"
                                    type="button"
                                >
                                    @if ($s->is_complete)
                                        {{ $s->order }}
                                    @elseif ($isLocked)
                                        <flux:icon.lock-closed class="size-4 text-zinc-400 dark:text-zinc-600" />
                                    @elseif ($s->file_path || $s->remark)
                                        <flux:icon.ellipsis-horizontal class="size-4 text-yellow-400" />
                                    @else
                                        {{ $s->order }}
                                    @endif
                                </button>

                                <a
                                    class="min-w-0 flex-1"
                                    href="{{ route('staff.violations.detail', ['violation' => $this->violation, 'stage' => $s->id]) }}"
                                    wire:navigate
                                >
                                    <p
                                        class="{{ $s->is_complete
                                            ? 'text-zinc-400 line-through dark:text-zinc-500'
                                            : ($isActive
                                                ? 'text-accent dark:text-accent'
                                                : 'text-zinc-700 dark:text-zinc-300') }} truncate text-base font-semibold leading-tight">
                                        {{ $s->name }}
                                    </p>
                                    <p
                                        class="{{ $s->is_complete
                                            ? 'text-accent/80'
                                            : ($isLocked
                                                ? 'text-zinc-500 dark:text-zinc-600'
                                                : 'text-zinc-500 dark:text-zinc-500') }} mt-0.5 text-xs">
                                        @if ($s->is_complete)
                                            {{ $s->completed_at?->format('M d, g:i a') ?? 'Complete' }}
                                        @elseif ($isLocked)
                                            Locked
                                        @elseif ($canCheck)
                                            Up next
                                        @else
                                            Pending
                                        @endif
                                    </p>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    @if ($this->stages->isNotEmpty() && $this->stages->every(fn($s) => $s->is_complete))
                        <div
                            class="bg-accent/10 text-accent mt-4 rounded-lg px-3 py-2 text-center text-xs font-semibold tracking-widest">
                            ✦ ALL STAGES COMPLETE ✦
                        </div>
                    @endif

                    <div class="mt-4 flex gap-2 border-t border-zinc-200 pt-4 dark:border-zinc-700">
                        <flux:modal.trigger name="add-stage">
                            <flux:button
                                class="flex-1"
                                icon="plus"
                                size="sm"
                                variant="subtle"
                            >
                                Add Stage
                            </flux:button>
                        </flux:modal.trigger>
                        <flux:modal.trigger name="reset-progress">
                            <flux:button
                                class="flex-1"
                                icon="arrow-path"
                                size="sm"
                                variant="subtle"
                            >
                                Reset Progress
                            </flux:button>
                        </flux:modal.trigger>
                    </div>
                </div>
            </div>
        </div>

        <div class="min-w-0 flex-1">
            @if ($this->stage)
                <div class="rounded-xl border border-zinc-300 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                    <div
                        class="flex items-start justify-between gap-4 border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        <div>
                            <p
                                class="mb-1 text-[11px] font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                                Stage {{ $this->stage->order }}
                            </p>
                            <flux:heading level="2" size="xl">{{ $this->stage->name }}</flux:heading>
                        </div>
                        <span
                            class="{{ $this->stage->is_complete
                                ? 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400'
                                : 'bg-zinc-200 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400' }} mt-1 shrink-0 rounded-full px-3 py-1 text-xs font-semibold"
                        >
                            {{ $this->stage->is_complete ? 'Complete' : 'Pending' }}
                        </span>
                    </div>

                    <div class="space-y-4 px-6 py-4">
                        @if ($this->stage->remark)
                            <flux:callout
                                color="indigo"
                                icon:variant="outline"
                                icon="chat-bubble-bottom-center-text"
                            >
                                <flux:callout.heading>Remarks</flux:callout.heading>
                                <flux:callout.text>{{ $this->stage->remark }}</flux:callout.text>
                            </flux:callout>
                        @endif

                        @if ($this->stage->file_path)
                            @if (in_array($this->fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <div
                                    class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
                                    <img
                                        alt="Attachment"
                                        class="w-full object-contain"
                                        src="{{ asset('storage/' . $this->stage->file_path) }}"
                                        style="max-height: 35vh;"
                                    >
                                    <div
                                        class="flex items-center justify-between border-t border-zinc-100 px-4 py-3 dark:border-zinc-700">
                                        <span
                                            class="text-sm text-zinc-500 dark:text-zinc-400">{{ $this->stage->file_name }}</span>
                                        <flux:link
                                            class="text-sm"
                                            href="{{ asset('storage/' . $this->stage->file_path) }}"
                                            rel="noopener"
                                            target="_blank"
                                        >
                                            Open in new tab →
                                        </flux:link>
                                    </div>
                                </div>
                            @elseif ($this->fileExtension === 'pdf')
                                <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
                                    <iframe
                                        allowtransparency="true"
                                        class="w-full"
                                        src="{{ asset('storage/' . $this->stage->file_path) }}"
                                        style="height: 35vh; display: block;"
                                    ></iframe>
                                    <div
                                        class="flex items-center justify-between border-t border-zinc-100 bg-white px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800">
                                        <span
                                            class="text-sm text-zinc-500 dark:text-zinc-400">{{ $this->stage->file_name }}</span>
                                        <flux:link
                                            class="text-sm"
                                            href="{{ asset('storage/' . $this->stage->file_path) }}"
                                            rel="noopener"
                                            target="_blank"
                                        >
                                            Open in new tab →
                                        </flux:link>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="flex items-center justify-between rounded-xl border border-zinc-200 bg-white px-5 py-4 dark:border-zinc-700 dark:bg-zinc-800">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-700">
                                            <flux:icon.document class="size-5 text-zinc-400" />
                                        </div>
                                        <span
                                            class="text-sm font-medium text-zinc-600 dark:text-zinc-300">{{ $this->stage->file_name }}</span>
                                    </div>
                                    <flux:link
                                        href="{{ asset('storage/' . $this->stage->file_path) }}"
                                        rel="noopener"
                                        target="_blank"
                                    >
                                        Download
                                    </flux:link>
                                </div>
                            @endif
                        @else
                            <div
                                class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-zinc-200 py-10 text-center dark:border-zinc-700">
                                <flux:icon.document class="size-10 text-zinc-300 dark:text-zinc-600" />
                                <p class="mt-3 text-sm text-zinc-400 dark:text-zinc-500">No file attached</p>
                                <p class="mt-1 text-xs text-zinc-300 dark:text-zinc-600">Use Edit Stage Details to
                                    upload a file</p>
                            </div>
                        @endif
                    </div>

                    <div
                        class="flex items-center justify-between gap-3 border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        <div class="flex gap-2">
                            <flux:modal.trigger name="edit-stage">
                                <flux:button icon="pencil-square" variant="primary">Edit Stage Details</flux:button>
                            </flux:modal.trigger>
                            <flux:modal.trigger name="clear-details">
                                <flux:button icon="x-mark" variant="filled">Clear</flux:button>
                            </flux:modal.trigger>
                        </div>

                        @php
                            $stageIndex = $this->stages->search(fn($s) => $s->id === $this->stage->id);
                            $nextStage = $this->stages[$stageIndex + 1] ?? null;
                        @endphp

                        <div class="flex gap-2">
                            <flux:modal.trigger name="rename-stage">
                                <flux:button
                                    icon="pencil"
                                    size="sm"
                                    variant="subtle"
                                >Rename</flux:button>
                            </flux:modal.trigger>
                            @if (!$nextStage)
                                <flux:modal.trigger name="delete-stage">
                                    <flux:button
                                        color="rose"
                                        icon="trash"
                                        size="sm"
                                        variant="subtle"
                                    >Delete</flux:button>
                                </flux:modal.trigger>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div
                    class="flex h-full flex-col items-center justify-center rounded-xl border-2 border-dashed border-zinc-200 text-center dark:border-zinc-700">
                    <flux:icon.inbox class="size-12 text-zinc-300 dark:text-zinc-600" />
                    <p class="mt-3 text-sm font-medium text-zinc-500 dark:text-zinc-400">No stages yet</p>
                    <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-500">No stages have been configured for this
                        violation.</p>
                </div>
            @endif
        </div>
    </div>

    <flux:modal class="md:w-80" name="reset-progress">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Reset Progress</flux:heading>
                <flux:subheading class="mt-1">
                    All stages will be marked as incomplete. Files and remarks will not be affected.
                </flux:subheading>
            </div>
            <flux:callout color="rose" icon="exclamation-triangle">
                <flux:callout.text>This cannot be undone.</flux:callout.text>
            </flux:callout>
            <div class="flex gap-3">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button
                    color="rose"
                    icon="arrow-path"
                    variant="primary"
                    wire:click="resetProgress"
                >
                    Reset
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal class="md:w-96" name="edit-stage">
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

            @if ($attachment)
                @php
                    $ext = strtolower($attachment->getClientOriginalExtension());
                @endphp

                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                    <img class="h-auto max-w-full rounded-lg" src="{{ $attachment->temporaryUrl() }}">
                @elseif ($ext === 'pdf')
                    <embed
                        class="h-96 w-full rounded-lg"
                        src="{{ $attachment->temporaryUrl() }}"
                        type="application/pdf"
                    >
                @endif
            @endif

            <div class="flex gap-3">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button
                    type="submit"
                    variant="primary"
                    wire:click="confirm"
                >Save Changes</flux:button>
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
                >Save Changes</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal class="md:w-80" name="add-stage">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add Stage</flux:heading>
                <flux:subheading class="mt-1">Create a new stage appended to the end.</flux:subheading>
            </div>
            <flux:input
                label="Stage Name"
                placeholder="e.g. Final Review"
                wire:model="newStageName"
            />
            <div class="flex gap-3">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" wire:click="addStage">Add Stage</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal class="md:w-80" name="rename-stage">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Rename Stage</flux:heading>
                <flux:subheading class="mt-1">Update the name of "{{ $this->stage?->name }}".</flux:subheading>
            </div>
            <flux:input label="Stage Name" wire:model="editStageName" />
            <div class="flex gap-3">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" wire:click="renameStage">Save</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal class="md:w-80" name="delete-stage">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Stage</flux:heading>
                <flux:subheading class="mt-1">
                    Delete "{{ $this->stage?->name }}"? Any attached file will also be removed.
                </flux:subheading>
            </div>
            <flux:callout color="rose" icon="exclamation-triangle">
                <flux:callout.text>This cannot be undone.</flux:callout.text>
            </flux:callout>
            <div class="flex gap-3">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button
                    color="rose"
                    icon="trash"
                    variant="primary"
                    wire:click="deleteStage"
                >Delete</flux:button>
            </div>
        </div>
    </flux:modal>

</div>
