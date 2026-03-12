<div class="space-y-4">
    <div class="sticky top-0 z-10 -mx-4 px-4 py-2">
        <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900">
            <div class="flex flex-wrap items-center gap-3 p-4">
                <div class="flex items-center gap-1 rounded-lg bg-slate-100/80 p-1 dark:bg-slate-800/60">
                    @foreach ([
        'today' => 'Today',
        'week' => 'This Week',
        'month' => 'This Month',
        'year' => 'This Year',
        'all' => 'All Time',
    ] as $key => $label)
                        <flux:button
                            class="{{ $period === $key ? 'shadow-sm' : 'text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200' }}"
                            size="sm"
                            variant="{{ $period === $key ? 'primary' : 'ghost' }}"
                            wire:click="$set('period', '{{ $key }}')"
                        >{{ $label }}</flux:button>
                    @endforeach
                </div>

                <flux:separator vertical />

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

                <flux:separator vertical />

                <flux:select
                    class="w-44"
                    placeholder="All Classifications"
                    wire:model.live="filterClassification"
                >
                    <flux:select.option value="Minor">Minor</flux:select.option>
                    <flux:select.option value="Major - Suspension">Major – Suspension</flux:select.option>
                    <flux:select.option value="Major - Dismissal">Major – Dismissal</flux:select.option>
                    <flux:select.option value="Major - Expulsion">Major – Expulsion</flux:select.option>
                </flux:select>

                <flux:separator vertical />

                <flux:select
                    class="w-36"
                    placeholder="All Courses"
                    wire:model.live="filterProgram"
                >
                    @foreach ($this->programs as $program)
                        <flux:select.option value="{{ $program }}">{{ $program }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select
                    class="w-32"
                    placeholder="All Years"
                    wire:model.live="filterYear"
                >
                    <flux:select.option value="1">1st Year</flux:select.option>
                    <flux:select.option value="2">2nd Year</flux:select.option>
                    <flux:select.option value="3">3rd Year</flux:select.option>
                    <flux:select.option value="4">4th Year</flux:select.option>
                </flux:select>

                <flux:separator vertical />

                <flux:button
                    icon="x-mark"
                    variant="ghost"
                    wire:click="resetFilters"
                >Clear Filters</flux:button>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 gap-1 sm:grid-cols-3 lg:grid-cols-7">
        <x-analytics-card
            :change="$this->totalChange"
            :number="$this->total"
            heading="Total"
        />
        <x-analytics-card
            :change="$this->pendingChange"
            :number="$this->pending"
            heading="Pending"
            variant="warning"
        />
        <x-analytics-card
            :change="$this->resolvedChange"
            :number="$this->resolved"
            heading="Resolved"
            variant="success"
        />
        <x-analytics-card
            :change="$this->minorChange"
            :number="$this->minor"
            heading="Minor"
            variant="neutral"
        />
        <x-analytics-card
            :change="$this->majorSuspensionChange"
            :number="$this->majorSuspension"
            heading="Major Suspensions"
            variant="danger"
        />
        <x-analytics-card
            :change="$this->majorDismissalChange"
            :number="$this->majorDismissal"
            heading="Major Dismissal"
            variant="danger"
        />
        <x-analytics-card
            :change="$this->majorExpulsionChange"
            :number="$this->majorExpulsion"
            heading="Major Expulsion"
            variant="danger"
        />
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">

        <div class="flex flex-col gap-4 lg:col-span-3">

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

                <flux:card class="flex flex-col gap-3 p-5 lg:col-span-2">
                    <flux:text class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        Violations Over Time
                    </flux:text>
                    <div
                        @violations-over-time-updated.window="updateChart($event.detail)"
                        class="relative h-44 w-full"
                        wire:ignore
                        x-data="violationsChart({ labels: @js($this->violationsOverTime['labels']), data: @js($this->violationsOverTime['data']) })"
                    >
                        <canvas class="absolute inset-0" x-ref="canvas"></canvas>
                    </div>
                </flux:card>

                <flux:card class="flex flex-col gap-3 p-5">
                    <flux:text class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        By Status
                    </flux:text>
                    <div
                        @by-status-updated.window="updateChart($event.detail)"
                        class="relative h-44 w-full"
                        wire:ignore
                        x-data="byStatusChart({ pending: @js($this->byStatus['pending']), resolved: @js($this->byStatus['resolved']) })"
                    >
                        <canvas class="absolute inset-0" x-ref="canvas"></canvas>
                    </div>
                </flux:card>

            </div>

            {{-- Row 2: By Classification + By Program + By Year Level --}}
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

                <flux:card class="flex flex-col gap-3 p-5">
                    <flux:text class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        By Classification
                    </flux:text>
                    <div
                        @by-classification-updated.window="updateChart($event.detail)"
                        class="relative h-44 w-full"
                        wire:ignore
                        x-data="byClassificationChart({ minor: @js($this->byClassification['minor']), suspension: @js($this->byClassification['suspension']), dismissal: @js($this->byClassification['dismissal']), expulsion: @js($this->byClassification['expulsion']) })"
                    >
                        <canvas class="absolute inset-0" x-ref="canvas"></canvas>
                    </div>
                </flux:card>

                <flux:card class="flex flex-col gap-3 p-5">
                    <flux:text class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        By Program
                    </flux:text>
                    <div
                        @by-program-updated.window="updateChart($event.detail)"
                        class="relative h-44 w-full"
                        wire:ignore
                        x-data="byProgramChart({ labels: @js($this->byProgram['labels']), data: @js($this->byProgram['data']) })"
                    >
                        <canvas class="absolute inset-0" x-ref="canvas"></canvas>
                    </div>
                </flux:card>

                <flux:card class="flex flex-col gap-3 p-5">
                    <flux:text class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        By Year Level
                    </flux:text>
                    <div
                        @by-year-level-updated.window="updateChart($event.detail)"
                        class="relative h-44 w-full"
                        wire:ignore
                        x-data="byYearLevelChart({ labels: @js($this->byYearLevel['labels']), data: @js($this->byYearLevel['data']) })"
                    >
                        <canvas class="absolute inset-0" x-ref="canvas"></canvas>
                    </div>
                </flux:card>

            </div>

        </div>

        {{-- Right: Top Violation Types — compact, full height --}}
        <flux:card class="flex flex-col gap-2 p-4">
            <flux:text class="text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                Top Violations
            </flux:text>
            <div
                @by-violation-type-updated.window="updateChart($event.detail)"
                class="relative w-full flex-1"
                style="min-height: 420px"
                wire:ignore
                x-data="byViolationTypeChart({ labels: @js($this->byViolationType['labels']), names: @js($this->byViolationType['names']), data: @js($this->byViolationType['data']) })"
            >
                <canvas class="absolute inset-0" x-ref="canvas"></canvas>
            </div>
        </flux:card>

    </div>
</div>

@script
    <script>
        // Shared helpers
        const GRID_COLOR = 'rgba(148,163,184,0.1)'; // slate-400/10
        const TICK_COLOR = 'rgba(148,163,184,0.6)'; // slate-400/60
        const FONT_FAMILY = 'inherit';

        const baseScaleX = () => ({
            grid: {
                color: GRID_COLOR,
                drawBorder: false
            },
            ticks: {
                color: TICK_COLOR,
                font: {
                    family: FONT_FAMILY,
                    size: 11
                },
                precision: 0
            },
            border: {
                display: false
            },
        });

        const baseScaleY = () => ({
            grid: {
                color: GRID_COLOR,
                drawBorder: false
            },
            ticks: {
                color: TICK_COLOR,
                font: {
                    family: FONT_FAMILY,
                    size: 11
                },
                precision: 0
            },
            border: {
                display: false
            },
            beginAtZero: true,
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

        // ── Violations Over Time ─────────────────────────────────────────────────
        Alpine.data('violationsChart', (config) => {
            let chart = null;
            return {
                init() {
                    this.create(config.labels, config.data);
                },

                create(labels, data) {
                    const canvas = this.$refs.canvas;
                    if (!canvas) return;
                    chart?.destroy();

                    chart = new Chart(canvas, {
                        type: 'line',
                        data: {
                            labels: [...labels],
                            datasets: [{
                                label: 'Violations',
                                data: [...data],
                                borderColor: 'rgb(99,102,241)',
                                backgroundColor: (ctx) => {
                                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, ctx
                                        .chart.height);
                                    g.addColorStop(0, 'rgba(99,102,241,0.18)');
                                    g.addColorStop(1, 'rgba(99,102,241,0)');
                                    return g;
                                },
                                borderWidth: 2.5,
                                fill: true,
                                tension: 0.45,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                pointBackgroundColor: 'rgb(99,102,241)',
                                pointBorderColor: 'rgba(99,102,241,0.3)',
                                pointBorderWidth: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    ...baseTooltip(),
                                    callbacks: {
                                        label: (i) => `  ${i.raw} violation${i.raw !== 1 ? 's' : ''}`,
                                    }
                                },
                            },
                            scales: {
                                x: {
                                    ...baseScaleX()
                                },
                                y: {
                                    ...baseScaleY(),
                                    ticks: {
                                        ...baseScaleY().ticks,
                                        stepSize: 1
                                    }
                                },
                            },
                        }
                    });
                },

                updateChart({
                    labels,
                    data
                }) {
                    if (!labels || !data) return;
                    if (chart) {
                        chart.data.labels = [...labels];
                        chart.data.datasets[0].data = [...data];
                        chart.update();
                    } else {
                        this.create(labels, data);
                    }
                }
            };
        });

        // ── By Status ────────────────────────────────────────────────────────────
        Alpine.data('byStatusChart', (config) => {
            let chart = null;
            return {
                init() {
                    this.create(config.pending, config.resolved);
                },

                create(pending, resolved) {
                    const canvas = this.$refs.canvas;
                    if (!canvas) return;
                    chart?.destroy();

                    chart = new Chart(canvas, {
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
                                        color: TICK_COLOR,
                                        font: {
                                            size: 12
                                        },
                                        padding: 16,
                                        usePointStyle: true,
                                        pointStyleWidth: 8,
                                    }
                                },
                                tooltip: {
                                    ...baseTooltip(),
                                    callbacks: {
                                        label: (i) => `  ${i.raw} ${i.label.toLowerCase()}`,
                                    }
                                },
                            },
                        }
                    });
                },

                updateChart({
                    pending,
                    resolved
                }) {
                    if (chart) {
                        chart.data.datasets[0].data = [pending, resolved];
                        chart.update();
                    } else {
                        this.create(pending, resolved);
                    }
                }
            };
        });

        // ── By Classification ────────────────────────────────────────────────────
        Alpine.data('byClassificationChart', (config) => {
            let chart = null;
            return {
                init() {
                    this.create(config);
                },

                create({
                    minor,
                    suspension,
                    dismissal,
                    expulsion
                }) {
                    const canvas = this.$refs.canvas;
                    if (!canvas) return;
                    chart?.destroy();

                    chart = new Chart(canvas, {
                        type: 'bar',
                        data: {
                            labels: ['Minor', 'Suspension', 'Dismissal', 'Expulsion'],
                            datasets: [{
                                label: 'Count',
                                data: [minor, suspension, dismissal, expulsion],
                                backgroundColor: [
                                    'rgba(56,189,248,0.8)',
                                    'rgba(251,191,36,0.8)',
                                    'rgba(249,115,22,0.8)',
                                    'rgba(239,68,68,0.8)',
                                ],
                                hoverBackgroundColor: [
                                    'rgba(56,189,248,1)',
                                    'rgba(251,191,36,1)',
                                    'rgba(249,115,22,1)',
                                    'rgba(239,68,68,1)',
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
                                tooltip: {
                                    ...baseTooltip()
                                },
                            },
                            scales: {
                                x: {
                                    ...baseScaleX(),
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    ...baseScaleY()
                                },
                            },
                        }
                    });
                },

                updateChart({
                    minor,
                    suspension,
                    dismissal,
                    expulsion
                }) {
                    if (chart) {
                        chart.data.datasets[0].data = [minor, suspension, dismissal, expulsion];
                        chart.update();
                    } else {
                        this.create({
                            minor,
                            suspension,
                            dismissal,
                            expulsion
                        });
                    }
                }
            };
        });

        // ── By Program ───────────────────────────────────────────────────────────
        Alpine.data('byProgramChart', (config) => {
            let chart = null;
            return {
                init() {
                    this.create(config.labels, config.data);
                },

                create(labels, data) {
                    const canvas = this.$refs.canvas;
                    if (!canvas) return;
                    chart?.destroy();

                    chart = new Chart(canvas, {
                        type: 'bar',
                        data: {
                            labels: [...labels],
                            datasets: [{
                                label: 'Violations',
                                data: [...data],
                                backgroundColor: 'rgba(99,102,241,0.75)',
                                hoverBackgroundColor: 'rgba(99,102,241,1)',
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
                                tooltip: {
                                    ...baseTooltip()
                                },
                            },
                            scales: {
                                x: {
                                    ...baseScaleX()
                                },
                                y: {
                                    ...baseScaleX(),
                                    grid: {
                                        display: false
                                    }
                                },
                            },
                        }
                    });
                },

                updateChart({
                    labels,
                    data
                }) {
                    if (!labels || !data) return;
                    if (chart) {
                        chart.data.labels = [...labels];
                        chart.data.datasets[0].data = [...data];
                        chart.update();
                    } else {
                        this.create(labels, data);
                    }
                }
            };
        });

        // ── By Year ──────────────────────────────────────────────────────────────
        Alpine.data('byYearLevelChart', (config) => {
            let chart = null;
            return {
                init() {
                    this.create(config.labels, config.data);
                },

                create(labels, data) {
                    const canvas = this.$refs.canvas;
                    if (!canvas) return;
                    chart?.destroy();

                    chart = new Chart(canvas, {
                        type: 'bar',
                        data: {
                            labels: [...labels],
                            datasets: [{
                                label: 'Violations',
                                data: [...data],
                                backgroundColor: [
                                    'rgba(99,102,241,0.75)',
                                    'rgba(139,92,246,0.75)',
                                    'rgba(168,85,247,0.75)',
                                    'rgba(217,70,239,0.75)',
                                ],
                                hoverBackgroundColor: [
                                    'rgba(99,102,241,1)',
                                    'rgba(139,92,246,1)',
                                    'rgba(168,85,247,1)',
                                    'rgba(217,70,239,1)',
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
                                tooltip: {
                                    ...baseTooltip(),
                                    callbacks: {
                                        label: (i) => `  ${i.raw} violation${i.raw !== 1 ? 's' : ''}`,
                                    }
                                },
                            },
                            scales: {
                                x: {
                                    ...baseScaleX(),
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    ...baseScaleY()
                                },
                            },
                        }
                    });
                },

                updateChart({
                    labels,
                    data
                }) {
                    if (!labels || !data) return;
                    if (chart) {
                        chart.data.labels = [...labels];
                        chart.data.datasets[0].data = [...data];
                        chart.update();
                    } else {
                        this.create(labels, data);
                    }
                }
            };
        });

        // ── Top Violation Types ──────────────────────────────────────────────────
        Alpine.data('byViolationTypeChart', (config) => {
            let chart = null;
            return {
                init() {
                    this.create(config.labels, config.names, config.data);
                },

                create(labels, names, data) {
                    const canvas = this.$refs.canvas;
                    if (!canvas) return;
                    chart?.destroy();

                    chart = new Chart(canvas, {
                        type: 'bar',
                        data: {
                            labels: [...labels],
                            datasets: [{
                                label: 'Violations',
                                data: [...data],
                                backgroundColor: 'rgba(99,102,241,0.75)',
                                hoverBackgroundColor: 'rgba(99,102,241,1)',
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
                                tooltip: {
                                    ...baseTooltip(),
                                    callbacks: {
                                        title: (items) => names[items[0].dataIndex],
                                        label: (i) => `  ${i.raw} violation${i.raw !== 1 ? 's' : ''}`,
                                    }
                                },
                            },
                            scales: {
                                x: {
                                    ...baseScaleX()
                                },
                                y: {
                                    ...baseScaleX(),
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        ...baseScaleX().ticks,
                                        font: {
                                            size: 12,
                                            weight: '500'
                                        },
                                    }
                                },
                            },
                        }
                    });

                    chart._names = [...names];
                },

                updateChart({
                    labels,
                    names,
                    data
                }) {
                    if (!labels || !data) return;
                    if (chart) {
                        chart._names = [...names];
                        chart.data.labels = [...labels];
                        chart.data.datasets[0].data = [...data];
                        chart.options.plugins.tooltip.callbacks.title = (items) => names[items[0].dataIndex];
                        chart.update();
                    } else {
                        this.create(labels, names, data);
                    }
                }
            };
        });
    </script>
@endscript
