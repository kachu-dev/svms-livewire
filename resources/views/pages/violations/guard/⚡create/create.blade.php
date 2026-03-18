<div class="grid h-full grid-cols-2 gap-6">
    <div class="flex flex-col gap-6">
        <livewire:violations.search-student size="guard" />
        <livewire:violations.submit-violation size="guard" />
    </div>

    <div class="flex flex-col">
        <livewire:violations.display-student />
    </div>

    @teleport('body')
        <div>
            <livewire:modals.violations.confirm />
            <livewire:modals.violations.remark />
            <livewire:modals.violations.type :minor-only="true" />
            <livewire:modals.violations.results />
        </div>
    @endteleport
</div>
