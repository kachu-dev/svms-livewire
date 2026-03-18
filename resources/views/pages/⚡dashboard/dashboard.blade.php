<div class="space-y-4">

    <div class="sticky top-0 z-10 -mx-4 px-4 py-2">
        <flux:card class="p-0! rounded-lg dark:bg-zinc-900">
            <div class="flex flex-wrap items-center gap-1 p-2">

                <div class="flex items-center gap-1 rounded-lg bg-slate-100/80 p-1 dark:bg-slate-800/60">
                    @foreach (['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year', 'all' => 'All Time'] as $key => $label)
                        <flux:button
                            class="{{ $period === $key ? 'shadow-sm' : 'text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200' }}"
                            size="sm"
                            variant="{{ $period === $key ? 'primary' : 'ghost' }}"
                            wire:click="$set('period', '{{ $key }}')"
                        >{{ $label }}</flux:button>
                    @endforeach
                </div>

                <div class="flex items-center gap-2">
                    <flux:input
                        class="w-36"
                        max="2999-12-31"
                        type="date"
                        wire:model.change.live="dateFrom"
                    />
                    <flux:icon.arrow-long-right class="size-4 shrink-0 text-zinc-300 dark:text-zinc-600" />
                    <flux:input
                        class="w-36"
                        max="2999-12-31"
                        type="date"
                        wire:model.change.live="dateTo"
                    />
                </div>

                <flux:select
                    class="w-44"
                    placeholder="All Classifications"
                    wire:model.live="filterClassification"
                >
                    <flux:select.option value="">All Classifications</flux:select.option>
                    <flux:select.option value="Minor">Minor</flux:select.option>
                    <flux:select.option value="Major - Suspension">Major - Suspension</flux:select.option>
                    <flux:select.option value="Major - Dismissal">Major - Dismissal</flux:select.option>
                    <flux:select.option value="Major - Expulsion">Major - Expulsion</flux:select.option>
                </flux:select>

                <flux:select
                    class="w-36"
                    placeholder="All Programs"
                    wire:model.live="filterProgram"
                >
                    <flux:select.option value="">All Programs</flux:select.option>
                    @foreach ($this->programs as $program)
                        <flux:select.option value="{{ $program }}">{{ $program }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select class="w-32" wire:model.live="filterYear">
                    <flux:select.option value="">All Year Levels</flux:select.option>
                    @foreach ($this->years as $year)
                        <flux:select.option value="{{ $year }}">Year {{ $year }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select class="w-32" wire:model.live="schoolYear">
                    <flux:select.option value="">All School Years</flux:select.option>
                    @foreach ($this->availableYears as $year)
                        <flux:select.option value="{{ $year }}">{{ $year }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:button
                    icon="x-mark"
                    variant="ghost"
                    wire:click="resetFilters"
                >Clear</flux:button>
                <flux:button
                    @click="$dispatch('open-export-modal')"
                    icon="arrow-down-tray"
                    variant="ghost"
                >Export</flux:button>
            </div>
        </flux:card>
    </div>

    <div
        class="hidden"
        data-classification="{{ $filterClassification }}"
        data-date-from="{{ $dateFrom }}"
        data-date-to="{{ $dateTo }}"
        data-period="{{ $period }}"
        data-program="{{ $filterProgram }}"
        data-year="{{ $filterYear }}"
        id="dash-filters"
    ></div>

    <div
        class="hidden"
        data-major-dismissal-change="{{ $this->stats['majorDismissal']['change'] }}"
        data-major-dismissal="{{ $this->stats['majorDismissal']['value'] }}"
        data-major-expulsion-change="{{ $this->stats['majorExpulsion']['change'] }}"
        data-major-expulsion="{{ $this->stats['majorExpulsion']['value'] }}"
        data-major-suspension-change="{{ $this->stats['majorSuspension']['change'] }}"
        data-major-suspension="{{ $this->stats['majorSuspension']['value'] }}"
        data-minor-change="{{ $this->stats['minor']['change'] }}"
        data-minor="{{ $this->stats['minor']['value'] }}"
        data-pending-change="{{ $this->stats['pending']['change'] }}"
        data-pending="{{ $this->stats['pending']['value'] }}"
        data-resolved-change="{{ $this->stats['resolved']['change'] }}"
        data-resolved="{{ $this->stats['resolved']['value'] }}"
        data-total-change="{{ $this->stats['total']['change'] }}"
        data-total="{{ $this->stats['total']['value'] }}"
        id="dash-stats"
    ></div>

    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-7">
        <x-analytics-card
            :change="$this->stats['total']['change']"
            :number="$this->stats['total']['value']"
            heading="Total"
        />
        <x-analytics-card
            :change="$this->stats['pending']['change']"
            :number="$this->stats['pending']['value']"
            heading="Pending"
            variant="warning"
        />
        <x-analytics-card
            :change="$this->stats['resolved']['change']"
            :number="$this->stats['resolved']['value']"
            heading="Resolved"
            variant="success"
        />
        <x-analytics-card
            :change="$this->stats['minor']['change']"
            :number="$this->stats['minor']['value']"
            heading="Minor"
            variant="neutral"
        />
        <x-analytics-card
            :change="$this->stats['majorSuspension']['change']"
            :number="$this->stats['majorSuspension']['value']"
            heading="Major Suspension"
            variant="danger"
        />
        <x-analytics-card
            :change="$this->stats['majorDismissal']['change']"
            :number="$this->stats['majorDismissal']['value']"
            heading="Major Dismissal"
            variant="danger"
        />
        <x-analytics-card
            :change="$this->stats['majorExpulsion']['change']"
            :number="$this->stats['majorExpulsion']['value']"
            heading="Major Expulsion"
            variant="danger"
        />
    </div>

    <div class="grid grid-cols-1 gap-2 lg:grid-cols-4">

        <div class="flex flex-col gap-2 lg:col-span-3">

            <div class="grid grid-cols-1 gap-2 lg:grid-cols-3">

                <flux:card class="flex flex-col gap-3 rounded-lg p-5 lg:col-span-2 dark:bg-zinc-900"
                    id="chart-overtime">
                    <flux:text class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        Violations Over Time</flux:text>
                    <div
                        class="relative h-52 w-full"
                        wire:ignore
                        x-data="makeChart('line', @js(['labels' => $this->violationsOverTime['labels'], 'data' => $this->violationsOverTime['data']]), 'violationsOverTime')"
                    ><canvas class="absolute inset-0" x-ref="canvas"></canvas></div>
                </flux:card>

                <flux:card class="flex flex-col gap-3 rounded-lg p-5 dark:bg-zinc-900" id="chart-status">
                    <flux:text class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        By Status</flux:text>
                    <div
                        class="relative h-52 w-full"
                        wire:ignore
                        x-data="makeChart('doughnut', @js(['pending' => $this->byStatus['pending'], 'resolved' => $this->byStatus['resolved']]), 'byStatus')"
                    ><canvas class="absolute inset-0" x-ref="canvas"></canvas></div>
                </flux:card>

            </div>

            <div class="grid grid-cols-1 gap-2 lg:grid-cols-3">

                <flux:card class="flex flex-col gap-3 rounded-lg p-5 dark:bg-zinc-900" id="chart-classification">
                    <flux:text
                        class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">By
                        Classification</flux:text>
                    <div
                        class="relative h-52 w-full"
                        wire:ignore
                        x-data="makeChart('classificationBar', @js($this->byClassification), 'byClassification')"
                    ><canvas class="absolute inset-0" x-ref="canvas"></canvas></div>
                </flux:card>

                <flux:card class="flex flex-col gap-3 rounded-lg p-5 dark:bg-zinc-900" id="chart-program">
                    <flux:text
                        class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">By
                        Program</flux:text>
                    <div
                        class="relative h-52 w-full"
                        wire:ignore
                        x-data="makeChart('hbar', @js(['labels' => $this->byProgram['labels'], 'data' => $this->byProgram['data']]), 'byProgram')"
                    ><canvas class="absolute inset-0" x-ref="canvas"></canvas></div>
                </flux:card>

                <flux:card class="flex flex-col gap-3 rounded-lg p-5 dark:bg-zinc-900" id="chart-year">
                    <flux:text
                        class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">By
                        Year Level</flux:text>
                    <div
                        class="relative h-52 w-full"
                        wire:ignore
                        x-data="makeChart('yearBar', @js(['labels' => $this->byYearLevel['labels'], 'data' => $this->byYearLevel['data']]), 'byYearLevel')"
                    ><canvas class="absolute inset-0" x-ref="canvas"></canvas></div>
                </flux:card>

            </div>

        </div>

        <flux:card class="flex flex-col gap-2 rounded-lg p-4 dark:bg-zinc-900" id="chart-violations">
            <flux:text class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Top
                Violations</flux:text>
            <div
                class="relative w-full flex-1"
                style="min-height:420px"
                wire:ignore
                x-data="makeChart('topViolations', @js($this->byViolationType), 'byViolationType')"
            ><canvas class="absolute inset-0" x-ref="canvas"></canvas></div>
        </flux:card>

    </div>

    <div
        @keydown.escape.window="open = false"
        @open-export-modal.window="open = true"
        class="fixed inset-0 z-50 flex items-center justify-center"
        x-cloak
        x-data="dashboardExport()"
        x-show="open"
    >
        <div
            @click="open = false"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
            x-transition:enter-end="opacity-100"
            x-transition:enter-start="opacity-0"
            x-transition:enter="transition duration-200"
            x-transition:leave-end="opacity-0"
            x-transition:leave-start="opacity-100"
            x-transition:leave="transition duration-150"
        ></div>

        <div
            class="relative z-10 w-full max-w-md rounded-2xl border border-zinc-200 bg-white p-6 shadow-2xl dark:border-zinc-700 dark:bg-zinc-900"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter="transition duration-200"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition duration-150"
        >
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Export Dashboard</h2>
                    <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Select sections to include in the PDF
                    </p>
                </div>
                <button @click="open = false"
                    class="rounded-lg p-1.5 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-800 dark:hover:text-zinc-300"
                >
                    <svg
                        class="size-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M6 18L18 6M6 6l12 12"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                        />
                    </svg>
                </button>
            </div>

            <div class="mb-5">
                <div class="mb-2 flex items-center justify-between">
                    <p class="text-xs font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Sections
                    </p>
                    <div class="flex gap-2">
                        <button @click="selectAll()" class="text-xs text-indigo-500 hover:underline">All</button>
                        <span class="text-zinc-300 dark:text-zinc-600">·</span>
                        <button @click="selectNone()" class="text-xs text-zinc-400 hover:underline">None</button>
                    </div>
                </div>
                <div class="space-y-1">
                    <template :key="section.id" x-for="section in sections">
                        <label
                            :class="section.selected ?
                                'border-indigo-200 bg-indigo-50/60 dark:border-indigo-800/60 dark:bg-indigo-950/30' :
                                'border-zinc-100 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-800/40'"
                            class="flex cursor-pointer items-center gap-3 rounded-lg border px-3.5 py-2.5 transition"
                        >
                            <input
                                class="sr-only"
                                type="checkbox"
                                x-model="section.selected"
                            />
                            <div :class="section.selected ? 'bg-indigo-500 border-indigo-500' :
                                'border-zinc-300 dark:border-zinc-600'"
                                class="flex size-4 shrink-0 items-center justify-center rounded border transition"
                            >
                                <svg
                                    class="size-2.5 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 12 12"
                                    x-show="section.selected"
                                >
                                    <path d="M10.28 2.28L4.5 8.06 1.72 5.28.28 6.72l4.22 4.22 7.22-7.22z" />
                                </svg>
                            </div>
                            <div class="flex min-w-0 flex-1 items-center gap-2">
                                <span class="shrink-0 text-zinc-400" x-html="section.icon"></span>
                                <span class="truncate text-sm text-zinc-700 dark:text-zinc-300"
                                    x-text="section.label"></span>
                            </div>
                        </label>
                    </template>
                </div>
            </div>

            <div
                class="mb-5 flex items-start gap-2.5 rounded-lg bg-amber-50 px-3.5 py-3 text-xs text-amber-700 dark:bg-amber-950/40 dark:text-amber-400">
                <svg
                    class="mt-0.5 size-3.5 shrink-0"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        clip-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        fill-rule="evenodd"
                    />
                </svg>
                PDF captures live chart images. Make sure your filters are set before exporting.
            </div>

            <div class="flex gap-2">
                <button @click="open = false"
                    class="flex-1 rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm font-medium text-zinc-600 transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-700"
                >Cancel</button>
                <button
                    :disabled="loading || !hasSelection"
                    @click="doExport()"
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700 disabled:cursor-not-allowed disabled:bg-rose-400"
                >
                    <svg
                        class="size-4 animate-spin"
                        fill="none"
                        viewBox="0 0 24 24"
                        x-show="loading"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke-width="4"
                            stroke="currentColor"
                        ></circle>
                        <path
                            class="opacity-75"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                            fill="currentColor"
                        ></path>
                    </svg>
                    <span x-text="loading ? 'Generating...' : 'Export PDF'"></span>
                </button>
            </div>
        </div>
    </div>

</div>

@script
    <script>
        const GRID_COLOR = 'rgba(148,163,184,0.1)';
        const tickColor = () => document.documentElement.classList.contains('dark') ?
            '#94a3b8' :
            '#64748b';
        const indigo = (a = 1) => `rgba(99,102,241,${a})`;

        const scaleX = (extra = {}) => ({
            grid: {
                color: GRID_COLOR,
                drawBorder: false
            },
            ticks: {
                color: tickColor(),
                font: {
                    family: 'inherit',
                    size: 11
                }
            },
            border: {
                display: false
            },
            ...extra,
        });
        const scaleY = (extra = {}) => ({
            ...scaleX(),
            beginAtZero: true,
            ...extra
        });

        const baseTooltip = (extra = {}) => ({
            backgroundColor: 'rgba(15,23,42,0.92)',
            titleColor: '#f1f5f9',
            bodyColor: '#94a3b8',
            borderColor: 'rgba(99,102,241,0.4)',
            borderWidth: 1,
            padding: 10,
            cornerRadius: 8,
            ...extra,
        });

        const CHART_TYPES = {

            line({
                labels,
                data
            }) {
                return {
                    type: 'line',
                    data: {
                        labels: [...labels],
                        datasets: [{
                            label: 'Violations',
                            data: [...data],
                            borderColor: indigo(),
                            fill: true,
                            tension: 0.45,
                            borderWidth: 2.5,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: indigo(),
                            pointBorderColor: indigo(0.3),
                            pointBorderWidth: 4,
                            backgroundColor: ctx => {
                                const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, ctx.chart.height);
                                g.addColorStop(0, indigo(0.18));
                                g.addColorStop(1, indigo(0));
                                return g;
                            },
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: baseTooltip({
                                callbacks: {
                                    label: i => `  ${i.raw} violation${i.raw !== 1 ? 's' : ''}`
                                }
                            })
                        },
                        scales: {
                            x: scaleX(),
                            y: scaleY({
                                ticks: {
                                    ...scaleY().ticks,
                                    stepSize: 1
                                }
                            })
                        },
                    },
                };
            },

            doughnut({
                pending,
                resolved
            }) {
                return {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Resolved'],
                        datasets: [{
                            data: [pending, resolved],
                            backgroundColor: ['rgba(251,191,36,0.85)', 'rgba(52,211,153,0.85)'],
                            hoverBackgroundColor: ['rgba(251,191,36,1)', 'rgba(52,211,153,1)'],
                            borderWidth: 0,
                            hoverOffset: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        layout: {
                            padding: 8
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: tickColor(),
                                    font: {
                                        size: 12
                                    },
                                    padding: 16,
                                    usePointStyle: true,
                                }
                            },
                            tooltip: baseTooltip({
                                callbacks: {
                                    label: i => `  ${i.raw} ${i.label.toLowerCase()}`
                                }
                            }),
                        },
                    },
                };
            },

            classificationBar({
                minor,
                suspension,
                dismissal,
                expulsion
            }) {
                return {
                    type: 'bar',
                    data: {
                        labels: ['Minor', 'Suspension', 'Dismissal', 'Expulsion'],
                        datasets: [{
                            label: 'Count',
                            data: [minor, suspension, dismissal, expulsion],
                            backgroundColor: ['rgba(56,189,248,0.8)', 'rgba(251,191,36,0.8)',
                                'rgba(249,115,22,0.8)', 'rgba(239,68,68,0.8)'
                            ],
                            hoverBackgroundColor: ['rgba(56,189,248,1)', 'rgba(251,191,36,1)',
                                'rgba(249,115,22,1)', 'rgba(239,68,68,1)'
                            ],
                            borderRadius: 8,
                            borderSkipped: false,
                            barPercentage: 0.6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: baseTooltip()
                        },
                        scales: {
                            x: {
                                ...scaleX(),
                                grid: {
                                    display: false
                                }
                            },
                            y: scaleY()
                        },
                    },
                };
            },

            hbar({
                labels,
                data
            }) {
                return {
                    type: 'bar',
                    data: {
                        labels: [...labels],
                        datasets: [{
                            label: 'Violations',
                            data: [...data],
                            backgroundColor: indigo(0.75),
                            hoverBackgroundColor: indigo(),
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.6,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: baseTooltip()
                        },
                        scales: {
                            x: scaleX(),
                            y: {
                                ...scaleX(),
                                grid: {
                                    display: false
                                }
                            }
                        },
                    },
                };
            },

            yearBar({
                labels,
                data
            }) {
                return {
                    type: 'bar',
                    data: {
                        labels: [...labels],
                        datasets: [{
                            label: 'Violations',
                            data: [...data],
                            backgroundColor: ['rgba(99,102,241,0.75)', 'rgba(139,92,246,0.75)',
                                'rgba(168,85,247,0.75)', 'rgba(217,70,239,0.75)'
                            ],
                            hoverBackgroundColor: ['rgba(99,102,241,1)', 'rgba(139,92,246,1)',
                                'rgba(168,85,247,1)', 'rgba(217,70,239,1)'
                            ],
                            borderRadius: 8,
                            borderSkipped: false,
                            barPercentage: 0.6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: baseTooltip({
                                callbacks: {
                                    label: i => `  ${i.raw} violation${i.raw !== 1 ? 's' : ''}`
                                }
                            })
                        },
                        scales: {
                            x: {
                                ...scaleX(),
                                grid: {
                                    display: false
                                }
                            },
                            y: scaleY()
                        },
                    },
                };
            },

            topViolations({
                labels,
                names,
                data
            }) {
                return {
                    type: 'bar',
                    data: {
                        labels: [...labels],
                        datasets: [{
                            label: 'Violations',
                            data: [...data],
                            backgroundColor: indigo(0.75),
                            hoverBackgroundColor: indigo(),
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.65,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: baseTooltip({
                                callbacks: {
                                    title: items => (names ?? [])[items[0].dataIndex],
                                    label: i => `  ${i.raw} violation${i.raw !== 1 ? 's' : ''}`,
                                }
                            }),
                        },
                        scales: {
                            x: scaleX(),
                            y: {
                                ...scaleX(),
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    ...scaleX().ticks,
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            },
                        },
                    },
                };
            },
        };

        Alpine.data('makeChart', (typeName, initialPayload, eventKey) => {
            let chart = null;

            return {
                init() {
                    this._build(initialPayload);

                    window.addEventListener('chart-data-updated', e => {
                        // Livewire dispatches detail as [payload] array
                        const all = e.detail?.[0] ?? e.detail ?? {};
                        const payload = all[eventKey];
                        if (payload == null) return;

                        if (!chart) {
                            this._build(payload);
                            return;
                        }

                        // Patch in-place for smooth animation
                        if (typeName === 'doughnut') {
                            chart.data.datasets[0].data = [payload.pending, payload.resolved];
                        } else if (typeName === 'classificationBar') {
                            chart.data.datasets[0].data = [payload.minor, payload.suspension, payload
                                .dismissal, payload.expulsion
                            ];
                        } else if (typeName === 'topViolations') {
                            chart._names = [...(payload.names ?? [])];
                            chart.data.labels = [...payload.labels];
                            chart.data.datasets[0].data = [...payload.data];
                            chart.options.plugins.tooltip.callbacks.title = items => chart._names[items[0]
                                .dataIndex];
                        } else {
                            chart.data.labels = [...payload.labels];
                            chart.data.datasets[0].data = [...payload.data];
                        }
                        chart.update();
                    });
                },

                _build(payload) {
                    const canvas = this.$refs.canvas;
                    if (!canvas) return;
                    chart?.destroy();
                    const cfg = CHART_TYPES[typeName]?.(payload);
                    if (cfg) {
                        chart = new Chart(canvas, cfg);
                        if (typeName === 'topViolations') chart._names = [...(payload.names ?? [])];
                    }
                },
            };
        });

        Alpine.data('dashboardExport', () => ({
            open: false,
            loading: false,

            sections: [{
                    id: 'stats',
                    label: 'Summary Statistics',
                    selected: true,
                    icon: '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>'
                },
                {
                    id: 'overtime',
                    label: 'Violations Over Time',
                    selected: true,
                    icon: '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>'
                },
                {
                    id: 'byStatus',
                    label: 'By Status',
                    selected: true,
                    icon: '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>'
                },
                {
                    id: 'classification',
                    label: 'By Classification',
                    selected: true,
                    icon: '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>'
                },
                {
                    id: 'byProgram',
                    label: 'By Program',
                    selected: true,
                    icon: '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>'
                },
                {
                    id: 'byYearLevel',
                    label: 'By Year Level',
                    selected: true,
                    icon: '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>'
                },
                {
                    id: 'topViolations',
                    label: 'Top Violation Types',
                    selected: true,
                    icon: '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>'
                },
            ],

            get hasSelection() {
                return this.sections.some(s => s.selected);
            },
            selectAll() {
                this.sections.forEach(s => s.selected = true);
            },
            selectNone() {
                this.sections.forEach(s => s.selected = false);
            },
            isSelected(id) {
                return this.sections.find(s => s.id === id)?.selected ?? false;
            },

            async doExport() {
                if (this.loading) return;
                this.loading = true;
                try {
                    await this._exportPdf();
                } finally {
                    this.loading = false;
                    this.open = false;
                }
            },

            async _exportPdf() {
                if (!window.jspdf) await this._loadScript(
                    'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js');

                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });

                // Page dimensions & palette
                const PW = 210,
                    PH = 297,
                    M = 14,
                    CW = PW - M * 2;
                const C = {
                    indigo: [99, 102, 241],
                    dark: [15, 23, 42],
                    slate: [71, 85, 105],
                    muted: [148, 163, 184],
                    faint: [241, 245, 249],
                    white: [255, 255, 255],
                    green: [22, 163, 74],
                    red: [220, 38, 38],
                    border: [226, 232, 240],
                };

                let y = M;

                const f = (style, size, rgb) => {
                    doc.setFont('helvetica', style);
                    if (size) doc.setFontSize(size);
                    if (rgb) doc.setTextColor(...rgb);
                };
                const fill = (...rgb) => doc.setFillColor(...(rgb.length === 1 ? rgb[0] : rgb));
                const line = (...rgb) => {
                    doc.setDrawColor(...(rgb.length === 1 ? rgb[0] : rgb));
                    doc.setLineWidth(0.3);
                };
                const pageBreakIfNeeded = (need) => {
                    if (y + need > PH - M - 14) {
                        doc.addPage();
                        y = M + 4;
                    }
                };

                let logoB64 = null;
                try {
                    logoB64 = await this._fetchImage('/images/adzu-seal.png');
                } catch {}

                const stampAllPages = (total) => {
                    for (let p = 1; p <= total; p++) {
                        doc.setPage(p);

                        if (logoB64) {
                            try {
                                const s = 200;
                                doc.saveGraphicsState();
                                doc.setGState(new doc.GState({ opacity: 0.06 }));
                                doc.addImage(logoB64, 'PNG', (PW - s) / 2, (PH - s) / 2, s, s);
                                doc.restoreGraphicsState();
                            } catch {}
                        }

                        // Top accent bar
                        fill(C.indigo);
                        doc.rect(0, 0, PW, 2, 'F');

                        // Bottom bar + page number
                        fill(C.indigo);
                        doc.rect(0, PH - 3, PW, 3, 'F');
                        f('normal', 7.5, C.white);
                        doc.text(`Page ${p} of ${total}`, PW / 2, PH - 5.5, {
                            align: 'center'
                        });
                    }
                };

                fill(C.indigo);
                doc.rect(0, 0, PW, 2, 'F'); // top bar (page 1)

                y = M + 4;
                f('bold', 20, C.dark);
                doc.text('Violations Dashboard Report', M, y);

                y += 6;
                f('normal', 8.5, C.muted);
                doc.text(`Generated: ${new Date().toLocaleString()}`, M, y);

                y += 10;

                const filters = this._readFilters();

                fill(C.faint);
                doc.roundedRect(M, y, CW, 8, 2, 2, 'F');
                f('bold', 7, C.slate);
                doc.text('FILTERS', M + 3, y + 5.3);

                const labelW = doc.getTextWidth('FILTERS') + 5;
                let fx = M + labelW + 3;

                if (!filters.length) {
                    f('normal', 7.5, C.muted);
                    doc.text('All data (no filters applied)', fx, y + 5.3);
                } else {
                    f('normal', 7, C.slate);
                    for (const {
                            label,
                            value
                        }
                        of filters) {
                        const txt = `${label}: ${value}`;
                        const pw = doc.getTextWidth(txt) + 6;
                        if (fx + pw > M + CW - 2) {
                            y += 9;
                            fill(C.faint);
                            doc.roundedRect(M, y, CW, 8, 2, 2, 'F');
                            f('bold', 7, C.slate);
                            doc.text('', M + 3, y + 5.3);
                            fx = M + labelW + 3;
                            f('normal', 7, C.slate);
                        }
                        fill(C.white);
                        doc.roundedRect(fx, y + 1.5, pw, 5, 1.5, 1.5, 'F');
                        doc.setTextColor(...C.indigo);
                        doc.text(txt, fx + 3, y + 5.3);
                        fx += pw + 3;
                    }
                }
                y += 13;

                if (this.isSelected('stats')) {
                    pageBreakIfNeeded(60);

                    f('bold', 8.5, C.slate);
                    doc.text('SUMMARY STATISTICS', M, y);
                    y += 5;

                    const COLS = [CW * 0.52, CW * 0.24, CW * 0.24];

                    // Header row
                    fill(C.faint);
                    doc.rect(M, y, CW, 7, 'F');
                    f('bold', 8, C.slate);
                    let tx = M;
                    ['Metric', 'Value', 'Change'].forEach((h, i) => {
                        doc.text(h, tx + 3, y + 5);
                        tx += COLS[i];
                    });
                    y += 7;

                    this._readStats().forEach((row, ri) => {
                        pageBreakIfNeeded(7);
                        if (ri % 2 === 0) {
                            fill(C.faint);
                            doc.rect(M, y, CW, 6.5, 'F');
                        }
                        tx = M;
                        f('normal', 8, C.dark);
                        [row.label, String(row.value), row.change ?? '-'].forEach((cell, ci) => {
                            if (ci === 2 && cell !== '-') {
                                doc.setTextColor(...(cell.startsWith('+') ? C.green : C
                                    .red));
                            }
                            doc.text(cell, tx + 3, y + 4.5);
                            doc.setTextColor(...C.dark);
                            tx += COLS[ci];
                        });
                        y += 6.5;
                    });

                    // Bottom border on table
                    line(C.border);
                    doc.rect(M, y - (this._readStats().length * 6.5), CW, this._readStats().length * 6.5,
                        'S');
                    y += 8;
                }

                const chartDefs = [{
                        id: 'overtime',
                        selector: '#chart-overtime canvas',
                        label: 'Violations Over Time'
                    },
                    {
                        id: 'byStatus',
                        selector: '#chart-status canvas',
                        label: 'By Status'
                    },
                    {
                        id: 'classification',
                        selector: '#chart-classification canvas',
                        label: 'By Classification'
                    },
                    {
                        id: 'byProgram',
                        selector: '#chart-program canvas',
                        label: 'By Program'
                    },
                    {
                        id: 'byYearLevel',
                        selector: '#chart-year canvas',
                        label: 'By Year Level'
                    },
                    {
                        id: 'topViolations',
                        selector: '#chart-violations canvas',
                        label: 'Top Violation Types'
                    },
                ];

                const CHART_H = 62,
                    GAP = 5,
                    COL_W = (CW - GAP) / 2,
                    BOX_H = CHART_H + 13;
                let col = 0;

                for (const def of chartDefs) {
                    if (!this.isSelected(def.id)) continue;
                    const canvas = document.querySelector(def.selector);
                    if (!canvas) continue;

                    if (col === 0) pageBreakIfNeeded(BOX_H + GAP);

                    const x = M + col * (COL_W + GAP);

                    fill(C.white);
                    doc.roundedRect(x, y, COL_W, BOX_H, 2, 2, 'F');
                    line(C.border);
                    doc.roundedRect(x, y, COL_W, BOX_H, 2, 2, 'S');

                    f('bold', 7, C.slate);
                    doc.text(def.label.toUpperCase(), x + 4, y + 6);

                    // Chart image
                    const imgData = canvas.toDataURL('image/png', 1.0);
                    const aspect = canvas.width / canvas.height;
                    const imgW = COL_W - 8;
                    const imgH = Math.min(imgW / aspect, CHART_H - 4);
                    doc.addImage(imgData, 'PNG', x + 4, y + 9, imgW, imgH);

                    col++;
                    if (col >= 2) {
                        col = 0;
                        y += BOX_H + GAP;
                    }
                }

                if (col === 1) y += BOX_H + GAP; // flush odd chart

                stampAllPages(doc.getNumberOfPages());

                doc.save(`violations-report-${this._stamp()}.pdf`);
            },

            _readFilters() {
                const el = document.getElementById('dash-filters');
                if (!el) return [];
                const {
                    period,
                    dateFrom,
                    dateTo,
                    program,
                    year,
                    classification
                } = el.dataset;
                const labels = {
                    today: 'Today',
                    week: 'This Week',
                    month: 'This Month',
                    year: 'This Year',
                    all: 'All Time'
                };
                const out = [];
                if (dateFrom && dateTo) out.push({
                    label: 'Date Range',
                    value: `${dateFrom} - ${dateTo}`
                });
                else if (dateFrom) out.push({
                    label: 'Date From',
                    value: dateFrom
                });
                else if (dateTo) out.push({
                    label: 'Date To',
                    value: dateTo
                });
                else if (period) out.push({
                    label: 'Period',
                    value: labels[period] ?? period
                });
                if (program) out.push({
                    label: 'Program',
                    value: program
                });
                if (year) out.push({
                    label: 'Year Level',
                    value: `Year ${year}`
                });
                if (classification) out.push({
                    label: 'Classification',
                    value: classification
                });
                return out;
            },

            _readStats() {
                const el = document.getElementById('dash-stats');
                if (!el) return [];
                const d = el.dataset;
                return [{
                        label: 'Total',
                        value: d.total,
                        change: d.totalChange || null
                    },
                    {
                        label: 'Pending',
                        value: d.pending,
                        change: d.pendingChange || null
                    },
                    {
                        label: 'Resolved',
                        value: d.resolved,
                        change: d.resolvedChange || null
                    },
                    {
                        label: 'Minor',
                        value: d.minor,
                        change: d.minorChange || null
                    },
                    {
                        label: 'Major - Suspension',
                        value: d.majorSuspension,
                        change: d.majorSuspensionChange || null
                    },
                    {
                        label: 'Major - Dismissal',
                        value: d.majorDismissal,
                        change: d.majorDismissalChange || null
                    },
                    {
                        label: 'Major - Expulsion',
                        value: d.majorExpulsion,
                        change: d.majorExpulsionChange || null
                    },
                ];
            },

            _chartData(selector) {
                const canvas = document.querySelector(selector);
                if (!canvas) return null;
                const inst = Object.values(Chart.instances ?? {}).find(c => c.canvas === canvas);
                if (!inst) return null;
                return {
                    labels: inst.data.labels ?? [],
                    data: inst.data.datasets[0]?.data ?? []
                };
            },

            async _fetchImage(url) {
                const r = await fetch(url);
                if (!r.ok) throw new Error('fetch failed');
                const blob = await r.blob();
                const dataUrl = await new Promise((res, rej) => {
                    const reader = new FileReader();
                    reader.onload = () => res(reader.result);
                    reader.onerror = rej;
                    reader.readAsDataURL(blob);
                });
                return await new Promise((res, rej) => {
                    const img = new Image();
                    img.onload = () => {
                        const c = document.createElement('canvas');
                        c.width = img.naturalWidth;
                        c.height = img.naturalHeight;
                        c.getContext('2d').drawImage(img, 0, 0);
                        res(c.toDataURL('image/png'));
                    };
                    img.onerror = rej;
                    img.src = dataUrl;
                });
            },

            _stamp() {
                return new Date().toISOString().slice(0, 10);
            },

            _loadScript(src) {
                return new Promise((res, rej) => {
                    const s = document.createElement('script');
                    s.src = src;
                    s.onload = res;
                    s.onerror = rej;
                    document.head.appendChild(s);
                });
            },
        }));
    </script>
@endscript
