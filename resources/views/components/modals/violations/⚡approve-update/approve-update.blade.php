<flux:modal class="min-w-[28rem]" name="approve-update">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Approve Remark Update Request</flux:heading>
            <flux:subheading class="mt-2">
                Are you sure you want to update this violation's remark?
            </flux:subheading>
        </div>

        <flux:separator />

        @if ($studentId)
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
                    <flux:text variant="strong">Reason:</flux:text>
                    <flux:text>{{ $reason }}</flux:text>
                </div>
            </div>

            <flux:separator />

            <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-3">
                <flux:callout class="flex flex-col text-center" color="green">
                    <flux:heading>Current Remark</flux:heading>
                    <flux:text>{{ $currentRemark ?? 'N/A' }}</flux:text>
                </flux:callout>
                <flux:icon name="arrow-long-right" />
                <flux:callout class="flex flex-col text-center" color="blue">
                    <flux:heading>New Remark</flux:heading>
                    <flux:text>{{ $newRemark }}</flux:text>
                </flux:callout>
            </div>

            <flux:separator />
        @endif

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="approve">Confirm Update</flux:button>
        </div>
    </div>
</flux:modal>
