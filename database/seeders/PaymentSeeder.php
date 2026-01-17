<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\support\facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payments')->insert([
            [
            'type' => 'KBZ Pay',
            'name' => 'Kaung Htet Zaw',
            'number' => "09967922343",
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
                'type' => 'Wave Pay',
                'name' => 'Kaung Htet Zaw',
                'number' => "09967922343",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
