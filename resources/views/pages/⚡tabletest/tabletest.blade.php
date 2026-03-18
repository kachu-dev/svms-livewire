<div class="rounded-2xl bg-white p-8 dark:bg-zinc-900">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-2">
                <flux:select class="" size="sm">
                    <option>Last 7 days</option>
                    <option>Last 14 days</option>
                    <option selected>Last 30 days</option>
                    <option>Last 60 days</option>
                    <option>Last 90 days</option>
                </flux:select>

                <flux:subheading class="whitespace-nowrap max-md:hidden">compared to</flux:subheading>

                <flux:select class="max-md:hidden" size="sm">
                    <option selected>Previous period</option>
                    <option>Same period last year</option>
                    <option>Last month</option>
                    <option>Last quarter</option>
                    <option>Last 6 months</option>
                    <option>Last 12 months</option>
                </flux:select>
            </div>

            <flux:separator class="mx-2 my-2 max-lg:hidden" vertical />

            <div class="flex items-center justify-start gap-2 max-lg:hidden">
                <flux:subheading class="whitespace-nowrap">Filter by:</flux:subheading>

                <flux:badge
                    as="button"
                    color="zinc"
                    icon="plus"
                    rounded
                    size="lg"
                >Amount</flux:badge>
                <flux:badge
                    as="button"
                    class="max-md:hidden"
                    color="zinc"
                    icon="plus"
                    rounded
                    size="lg"
                >Status</flux:badge>
                <flux:badge
                    as="button"
                    color="zinc"
                    icon="plus"
                    rounded
                    size="lg"
                >More filters...</flux:badge>
            </div>
        </div>
    </div>

    <div class="mb-6 flex gap-6">
        @foreach ($this->stats as $stat)
            <div
                class="{{ $loop->iteration > 1 ? 'max-md:hidden' : '' }} {{ $loop->iteration > 3 ? 'max-lg:hidden' : '' }} relative flex-1 rounded-lg bg-zinc-50 px-6 py-4 dark:bg-zinc-700">
                <flux:subheading>{{ $stat['title'] }}</flux:subheading>

                <flux:heading class="mb-2" size="xl">{{ $stat['value'] }}</flux:heading>

                <div
                    class="@if ($stat['trendUp']) text-green-600 dark:text-green-400 @else text-red-500 dark:text-red-400 @endif flex items-center gap-1 text-sm font-medium">
                    <flux:icon :icon="$stat['trendUp'] ? 'arrow-trending-up' : 'arrow-trending-down'" variant="micro" /> {{ $stat['trend'] }}
                </div>

                <div class="absolute right-0 top-0 pr-2 pt-2">
                    <flux:button
                        icon="ellipsis-horizontal"
                        size="sm"
                        variant="subtle"
                    />
                </div>
            </div>
        @endforeach
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column></flux:table.column>
            <flux:table.column class="max-md:hidden">ID</flux:table.column>
            <flux:table.column class="max-md:hidden">Date</flux:table.column>
            <flux:table.column class="max-md:hidden">Status</flux:table.column>
            <flux:table.column><span class="max-md:hidden">Customer</span>
                <div class="w-6 md:hidden"></div>
            </flux:table.column>
            <flux:table.column>Purchase</flux:table.column>
            <flux:table.column>Revenue</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->rows as $row)
                <flux:table.row>
                    <flux:table.cell class="pr-2">
                        <flux:checkbox />
                    </flux:table.cell>
                    <flux:table.cell class="max-md:hidden">#{{ $row['id'] }}</flux:table.cell>
                    <flux:table.cell class="max-md:hidden">{{ $row['date'] }}</flux:table.cell>
                    <flux:table.cell class="max-md:hidden">
                        <flux:badge
                            :color="$row['status_color']"
                            inset="top bottom"
                            size="sm"
                        >{{ $row['status'] }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell class="min-w-6">
                        <div class="flex items-center gap-2">
                            <flux:avatar size="xs" src="https://i.pravatar.cc/48?img={{ $loop->index }}" />
                            <span class="max-md:hidden">{{ $row['customer'] }}</span>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell class="max-w-6 truncate">{{ $row['purchase'] }}</flux:table.cell>
                    <flux:table.cell class="" variant="strong">{{ $row['amount'] }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown
                            align="end"
                            offset="-15"
                            position="bottom"
                        >
                            <flux:button
                                icon="ellipsis-horizontal"
                                inset="top bottom"
                                size="sm"
                                variant="ghost"
                            ></flux:button>

                            <flux:menu>
                                <flux:menu.item icon="document-text">View invoice</flux:menu.item>
                                <flux:menu.item icon="receipt-refund">Refund</flux:menu.item>
                                <flux:menu.item icon="archive-box" variant="danger">Archive</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
