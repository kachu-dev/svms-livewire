<div class="flex gap-6">

    {{-- ─── CHECKLIST SIDEBAR ─────────────────────────────────────────────── --}}
    <div class="w-80 shrink-0">
        <div class="rounded-xl border border-slate-300 bg-white dark:border-slate-700 dark:bg-slate-900">
            <div class="px-5 pb-4 pt-5">
                <flux:text class="mb-3 text-center text-sm font-semibold uppercase tracking-[0.2em]">
                    Stages - {{ $this->stages->where('is_complete', true)->count() }}/{{ $this->stages->count() }}
                </flux:text>

                {{-- Progress bar --}}
                @php $pct = $this->stages->count() ? ($this->stages->where('is_complete', true)->count() / $this->stages->count()) * 100 : 0; @endphp
                <div class="mb-4 h-2 w-full rounded-full bg-slate-200 dark:bg-slate-700">
                    <div class="bg-accent h-2 rounded-full" style="width: {{ $pct }}%"></div>
                </div>

                {{-- Sortable list --}}
                <div class="flex flex-col gap-0.5" wire:sort="handleSort">
                    @foreach ($this->stages as $i => $s)
                        @php
                            $prevStage = $this->stages[$i - 1] ?? null;
                            $nextStage = $this->stages[$i + 1] ?? null;
                            $isActive = $s->id === $this->stage->id;
                            $canCheck = !$s->is_complete && ($i === 0 || $prevStage?->is_complete);
                            $canUncheck = $s->is_complete && (!$nextStage || !$nextStage->is_complete);
                            $isLocked = !$s->is_complete && !$canCheck;
                            $canToggle = $canCheck || $canUncheck;
                        @endphp

                        <div
                            class="{{ $isActive
                                ? 'bg-accent/10 border border-accent dark:bg-accent/15'
                                : 'border-l-4 border-transparent hover:bg-white/70 dark:hover:bg-slate-800/50' }} {{ $isLocked && !$isActive ? 'opacity-60' : '' }} group flex items-center gap-2.5 rounded-lg px-3 py-3"
                            wire:key="stage-{{ $s->id }}"
                            wire:sort:item="{{ $s->id }}"
                        >
                            {{-- Drag handle --}}
                            <div class="{{ $canToggle ? 'cursor-grab active:cursor-grabbing' : 'cursor-not-allowed' }} shrink-0 text-slate-400 hover:text-slate-600 dark:text-slate-600"
                                wire:sort:handle
                            >
                                <flux:icon.bars-2 class="size-5" />
                            </div>

                            {{-- Checkbox --}}
                            <button
                                @if ($canToggle) wire:click="toggleStage({{ $s->id }})" @endif
                                class="{{ $canToggle ? 'cursor-pointer' : 'cursor-not-allowed' }} {{ $s->is_complete
                                    ? 'bg-accent'
                                    : ($s->file_path || $s->remark
                                        ? 'border-2 border-yellow-400 bg-yellow-50 dark:bg-yellow-500/10'
                                        : 'border-2 border-slate-400 bg-white dark:border-slate-600 dark:bg-transparent') }} {{ $canCheck ? 'hover:border-accent' : '' }} relative flex h-7 w-7 shrink-0 items-center justify-center rounded-md"
                                type="button"
                                wire:sort:ignore
                            >
                                @if ($s->is_complete)
                                    <flux:icon.check class="size-4 text-white" />
                                @elseif ($isLocked)
                                    <flux:icon.lock-closed class="size-4 text-slate-400 dark:text-slate-600" />
                                @elseif ($s->file_path || $s->remark)
                                    <flux:icon.ellipsis-horizontal class="size-4 text-yellow-400" />
                                @endif
                            </button>

                            {{-- Label --}}
                            <a
                                class="min-w-0 flex-1"
                                href="{{ route('staff.violations.detail', ['violation' => $this->violation, 'stage' => $s->id]) }}"
                                wire:navigate
                                wire:sort:ignore
                            >
                                <p
                                    class="{{ $s->is_complete
                                        ? 'text-slate-400 line-through dark:text-slate-500'
                                        : ($isActive
                                            ? 'text-accent dark:text-accent'
                                            : 'text-slate-700 dark:text-slate-300') }} truncate text-base font-semibold leading-tight">
                                    {{ $s->name }}
                                </p>
                                <p
                                    class="{{ $s->is_complete
                                        ? 'text-accent/80'
                                        : ($isLocked
                                            ? 'text-slate-500 dark:text-slate-600'
                                            : 'text-slate-500 dark:text-slate-500') }} mt-0.5 text-xs">
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

                            <span class="font-mono text-xs text-slate-400 dark:text-slate-600">
                                {{ str_pad($s->order, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                @if ($this->stages->every(fn($s) => $s->is_complete))
                    <div
                        class="bg-accent/10 text-accent mt-4 rounded-lg px-3 py-2 text-center text-xs font-semibold tracking-widest">
                        ✦ ALL STAGES COMPLETE ✦
                    </div>
                @endif

                <div class="mt-4 border-t border-slate-200 pt-4 dark:border-slate-700">
                    <flux:modal.trigger name="reset-progress">
                        <flux:button
                            class="w-full"
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

    {{-- ─── MAIN CONTENT ───────────────────────────────────────────────────── --}}
    <div class="min-w-0 flex-1">
        <div class="rounded-xl border border-slate-300 bg-white dark:border-slate-700 dark:bg-slate-900">

            {{-- Header --}}
            <div
                class="flex items-start justify-between gap-4 border-b border-slate-200 px-8 py-6 dark:border-slate-700">
                <div>
                    <p
                        class="mb-1 text-[11px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        Stage {{ $this->stage->order }}
                    </p>
                    <flux:heading level="2" size="xl">{{ $this->stage->name }}</flux:heading>
                </div>
                <span
                    class="{{ $this->stage->is_complete
                        ? 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400'
                        : 'bg-slate-200 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }} mt-1 shrink-0 rounded-full px-3 py-1 text-xs font-semibold"
                >
                    {{ $this->stage->is_complete ? 'Complete' : 'Pending' }}
                </span>
            </div>

            {{-- Body --}}
            <div class="space-y-6 px-8 py-6">

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

                {{-- File --}}
                @if ($this->stage->file_path)
                    @if (in_array($this->fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        <div
                            class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                            <img
                                alt="Attachment"
                                class="w-full object-contain"
                                src="{{ asset('storage/' . $this->stage->file_path) }}"
                                style="max-height: 55vh;"
                            >
                            <div
                                class="flex items-center justify-between border-t border-slate-100 px-4 py-3 dark:border-slate-700">
                                <span
                                    class="text-sm text-slate-500 dark:text-slate-400">{{ $this->stage->file_name }}</span>
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
                        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700">
                            <iframe
                                allowtransparency="true"
                                class="w-full"
                                src="{{ asset('storage/' . $this->stage->file_path) }}"
                                style="height: 55vh; display: block;"
                            ></iframe>
                            <div
                                class="flex items-center justify-between border-t border-slate-100 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-800">
                                <span
                                    class="text-sm text-slate-500 dark:text-slate-400">{{ $this->stage->file_name }}</span>
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
                            class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-5 py-4 dark:border-slate-700 dark:bg-slate-800">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-700">
                                    <flux:icon.document class="size-5 text-slate-400" />
                                </div>
                                <span
                                    class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ $this->stage->file_name }}</span>
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
                        class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 py-20 text-center dark:border-slate-700">
                        <flux:icon.document class="size-10 text-slate-300 dark:text-slate-600" />
                        <p class="mt-3 text-sm text-slate-400 dark:text-slate-500">No file attached</p>
                        <p class="mt-1 text-xs text-slate-300 dark:text-slate-600">Use Edit Stage Details to upload a
                            file</p>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div
                class="flex items-center justify-between gap-3 border-t border-slate-200 px-8 py-5 dark:border-slate-700">
                <div class="flex gap-2">
                    <flux:modal.trigger name="edit-status">
                        <flux:button icon="pencil-square" variant="primary">Edit Stage Details</flux:button>
                    </flux:modal.trigger>

                    <flux:modal.trigger name="clear-details">
                        <flux:button icon="x-mark" variant="filled">Clear</flux:button>
                    </flux:modal.trigger>
                </div>

                @php
                    $stageIndex = $this->stages->search(fn($s) => $s->id === $this->stage->id);
                    $nextStage = $this->stages[$stageIndex + 1] ?? null;
                    $canUndo = $this->stage->is_complete && (!$nextStage || !$nextStage->is_complete);
                @endphp

                @if ($this->stage->is_complete)
                    <flux:button
                        :disabled="!$canUndo"
                        color="rose"
                        icon="arrow-uturn-left"
                        variant="subtle"
                        wire:click="toggleStage({{ $this->stage->id }})"
                        wire:confirm="Undo completion for this stage?"
                    >
                        Undo Complete
                    </flux:button>
                @endif
            </div>
        </div>
    </div>

    {{-- ─── MODALS ──────────────────────────────────────────────────────────── --}}
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
</div>
