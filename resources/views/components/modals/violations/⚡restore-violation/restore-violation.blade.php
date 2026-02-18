<flux:modal class="min-w-[28rem]" name="restore-violation">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Restore Violation</flux:heading>
            <flux:subheading class="mt-2">
                Are you sure you want to restore this violation record?
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
        </div>

        <flux:separator />

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button
                color="green"
                variant="primary"
                wire:click="restore"
            >Restore Violation</flux:button>
        </div>
    </div>
</flux:modal>
