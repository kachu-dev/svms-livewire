<div class="grid h-full grid-cols-2 grid-rows-2 gap-4">
    <div class="row-span-2 flex flex-col gap-4">
        <livewire:violations.search-student />

        <livewire:violations.submit-violation />
    </div>

    <div class="row-span-2">
        <livewire:violations.display-student />
    </div>

    @teleport('body')
        <div>
            <livewire:modals.violations.confirm />
            <livewire:modals.violations.remark />
            <livewire:modals.violations.type />
            <livewire:modals.violations.results />
        </div>
    @endteleport

</div>
