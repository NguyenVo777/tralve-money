<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Nguyễn Văn A',
            'email' => 'john@smarttravel.io',
            'password' => bcrypt('password'),
        ]);

        \App\Models\ExchangeLocation::insert([
            ['name' => 'Hưng Long Exchange', 'address' => '36 Mạc Thị Bưởi, Quận 1', 'status' => 'Đang mở cửa', 'usd_rate' => 25410.00, 'distance_km' => 0.4, 'rating' => 4.9, 'reviews_count' => 1200],
            ['name' => 'Vietcombank - CN1', 'address' => 'Bến Chương Dương, Quận 1', 'status' => 'Đang mở cửa', 'usd_rate' => 25385.00, 'distance_km' => 1.2, 'rating' => 4.5, 'reviews_count' => 800],
            ['name' => 'Quầy Thu Đổi 59', 'address' => 'Chợ Bến Thành, Quận 1', 'status' => 'Đang mở cửa', 'usd_rate' => 25425.00, 'distance_km' => 0.8, 'rating' => 4.8, 'reviews_count' => 2500],
        ]);
    }
}
