<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plans')->insert([
            [
                'name' => 'Basic Plan',
                'amount' => 5000,
                'month' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Standard Plan',
                'amount' => 14000,
                'month' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Plan',
                'amount' => 25000,
                'month' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
