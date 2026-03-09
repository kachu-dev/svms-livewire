<flux:modal class="min-w-[28rem]" name="approve-delete">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Approve Request for Archiving</flux:heading>
            <flux:subheading class="mt-2">
                Are you sure you want to archive this violation record?
            </flux:subheading>
        </div>

        <flux:separator />

        <div class="space-y-3">
            <div class="grid grid-cols-[120px_1fr] gap-2">
                <flux:text variant="strong">Student ID:</flux:text>
                <flux:text>{{ $studentId }}</flux:text>
            </div>

            <div class="grid grid-cols-[120px_1fr] gap-2">
                <flux:text variant="strong">Student Name:</flux:text>
                <flux:text>{{ $studentName }}</flux:text>
            </div>

            <div class="grid grid-cols-[120px_1fr] gap-2">
                <flux:text variant="strong">Violation:</flux:text>
                <flux:text>{{ $type }}</flux:text>
            </div>

            <div class="grid grid-cols-[120px_1fr] gap-2">
                <flux:text variant="strong">Remark:</flux:text>
                <flux:text>{{ $remark ?? 'N/A' }}</flux:text>
            </div>

            <div class="grid grid-cols-[120px_1fr] gap-2">
                <flux:text variant="strong">Status:</flux:text>
                <flux:text>{{ $status ?? 'N/A' }}</flux:text>
            </div>
        </div>

        <flux:separator />

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="danger" wire:click="delete">Confirm Delete Violation</flux:button>
        </div>
    </div>
</flux:modal>
