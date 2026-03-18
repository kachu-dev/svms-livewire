<?php

use Livewire\Component;

new class extends Component
{
    public $stats = [];

    public $rows = [];

    public function mount(): void
    {
        $this->stats = [
            [
                'title' => 'Total Revenue',
                'value' => '$12,540',
                'trend' => '12.5%',
                'trendUp' => true,
            ],
            [
                'title' => 'Orders',
                'value' => '1,204',
                'trend' => '4.3%',
                'trendUp' => true,
            ],
            [
                'title' => 'Refunds',
                'value' => '32',
                'trend' => '2.1%',
                'trendUp' => false,
            ],
            [
                'title' => 'New Customers',
                'value' => '98',
                'trend' => '8.9%',
                'trendUp' => true,
            ],
        ];

        $statuses = [
            ['label' => 'Paid', 'color' => 'green'],
            ['label' => 'Pending', 'color' => 'yellow'],
            ['label' => 'Failed', 'color' => 'red'],
        ];

        for ($i = 1; $i <= 15; $i++) {
            $status = fake()->randomElement($statuses);

            $this->rows[] = [
                'id' => fake()->numberBetween(1000, 9999),
                'date' => fake()->date(),
                'status' => $status['label'],
                'status_color' => $status['color'],
                'customer' => fake()->name(),
                'purchase' => fake()->sentence(3),
                'amount' => '$'.fake()->numberBetween(20, 500),
            ];
        }
    }
};
