<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@travelmoney.com'],
            [
                'full_name' => 'System Administrator',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'country' => 'Vietnam',
                'language' => 'vi',
                'status' => 'active',
            ]
        );

        // Regular User
        \App\Models\User::updateOrCreate(
            ['email' => 'user@travelmoney.com'],
            [
                'full_name' => 'Regular User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'user',
                'country' => 'Thailand',
                'language' => 'th',
                'status' => 'active',
            ]
        );

        $rates = [
            ['country' => 'Vietnam', 'code' => 'VND', 'name' => 'Vietnamese Dong', 'rate' => 25450.00],
            ['country' => 'Thailand', 'code' => 'THB', 'name' => 'Thai Baht', 'rate' => 36.75],
            ['country' => 'Indonesia', 'code' => 'IDR', 'name' => 'Indonesian Rupiah', 'rate' => 16050.00],
            ['country' => 'Malaysia', 'code' => 'MYR', 'name' => 'Malaysian Ringgit', 'rate' => 4.74],
            ['country' => 'Philippines', 'code' => 'PHP', 'name' => 'Philippine Peso', 'rate' => 57.35],
            ['country' => 'Singapore', 'code' => 'SGD', 'name' => 'Singapore Dollar', 'rate' => 1.35],
            ['country' => 'Cambodia', 'code' => 'KHR', 'name' => 'Cambodian Riel', 'rate' => 4070.00],
            ['country' => 'Laos', 'code' => 'LAK', 'name' => 'Lao Kip', 'rate' => 21350.00],
            ['country' => 'Myanmar', 'code' => 'MMK', 'name' => 'Myanmar Kyat', 'rate' => 2100.00],
        ];

        foreach ($rates as $rateData) {
            $rate = \App\Models\ExchangeRate::updateOrCreate(
                ['currency_code' => $rateData['code']],
                [
                    'country' => $rateData['country'],
                    'currency_name' => $rateData['name'],
                    'rate_to_usd' => $rateData['rate'],
                ]
            );

            // Add some history
            for ($i = 1; $i <= 5; $i++) {
                $rate->history()->create([
                    'rate' => $rateData['rate'] * (1 + (rand(-5, 5) / 100)),
                    'created_at' => now()->subDays($i),
                ]);
            }
        }
    }
}
