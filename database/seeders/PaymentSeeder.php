<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\support\facades\DB;
use App\Models\PaymentType;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
        // 1. Create the Payment Providers (The "Types")
        $kbzPay = PaymentType::create([
            'name' => 'KBZ Pay',
            'logo_url' => 'https://example.com/logos/kbzpay.png'
        ]);

        $wavePay = PaymentType::create([
            'name' => 'Wave Money',
            'logo_url' => 'https://example.com/logos/wavepay.png'
        ]);

        $ayapay = PaymentType::create([
            'name' => 'AYA Pay',
            'logo_url' => 'https://example.com/logos/ayapay.png'
        ]);

        // 2. Create the specific Account Details (The "Payments")
        // These are linked via payment_type_id
        Payment::create([
            'payment_type_id' => $kbzPay->id,
            'name'   => 'U Mg Mg (Admin)',
            'number' => '09123456789'
        ]);

        Payment::create([
            'payment_type_id' => $wavePay->id,
            'name'   => 'Daw Hla Hla (Admin)',
            'number' => '09987654321'
        ]);

        Payment::create([
            'payment_type_id' => $kbzPay->id,
            'name'   => 'Office Account 2',
            'number' => '09444555666'
        ]);
    }
    }
}