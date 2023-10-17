<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Strategy;
class StrategySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Strategy::create(['name' => 'DESKTOP']);
        Strategy::create(['name' => 'MOBILE']);
    }
}
