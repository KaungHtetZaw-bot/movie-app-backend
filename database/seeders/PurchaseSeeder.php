<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Plan;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::where('role_id', 1)->pluck('id');
        $adminIds = User::whereIn('role_id', [2, 3])->pluck('id');
        $planIds = Plan::pluck('id');

        if ($userIds->isEmpty() || $planIds->isEmpty()) {
            $this->command->warn("Please seed Users and Plans first!");
            return;
        }

        Purchase::create([
            'user_id'     => $userIds->random(),
            'plan_id'     => $planIds->random(),
            'photo'       => 'receipts/sample_receipt.png',
            'status'      => 'pending',
            'provider_id' => null, 
        ]);

        Purchase::create([
            'user_id'     => $userIds->random(),
            'plan_id'     => $planIds->random(),
            'photo'       => 'receipts/approved_receipt.png',
            'status'      => 'approved',
            'provider_id' => $adminIds->random(),
        ]);

        Purchase::create([
            'user_id'     => $userIds->random(),
            'plan_id'     => $planIds->random(),
            'photo'       => 'receipts/bad_receipt.png',
            'status'      => 'rejected',
            'provider_id' => $adminIds->random(),
        ]);
    }
}
