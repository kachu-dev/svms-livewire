<div>
    <div class="rounded border p-4 shadow-sm">
        <h2 class="mb-2 text-lg font-semibold">{{ $stage->name }}</h2>

        <ul class="space-y-1">
            <li><strong>ID:</strong> {{ $stage->id }}</li>
            <li><strong>Violation ID:</strong> {{ $stage->violation_id }}</li>
            <li><strong>Order:</strong> {{ $stage->order }}</li>
            <li><strong>Complete:</strong> {{ $stage->is_complete ? 'Yes' : 'No' }}</li>
            <li><strong>Remark:</strong> {{ $stage->remark ?? '-' }}</li>
            <li><strong>File Path:</strong> {{ $stage->file_path ?? '-' }}</li>
            <li><strong>Next</strong> {{ $this->nextStage()->id ?? '-' }}</li>
            <li><strong>Previous</strong> {{ $this->previousStage()->id ?? '-' }}</li>
        </ul>
    </div>

    <div class="flex flex-col gap-4 mt-4 max-w-60">
        <flux:textarea label="Remarks" wire:model="remarks"></flux:textarea>
        <flux:input type="file" wire:model="attachment" label="File Attachment"></flux:input>
        @if($this->canComplete())
            <flux:button variant="primary" wire:click="confirm">Complete</flux:button>
        @endif
        @if($this->canUndo())
            <flux:button variant="danger" wire:click="undo">Undo</flux:button>
        @endif
    </div>
</div>
