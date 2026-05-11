<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_methods')->delete();
        
        \DB::table('payment_methods')->insert(array (
            0 => 
            array (
                'id' => 1,
            'name' => 'Xendit (Pembayaran Otomatis)',
                'type' => 'bank_transfer',
                'bank_name' => '',
                'account_number' => '',
                'account_name' => '',
                'instruction' => NULL,
                'is_active' => 1,
                'created_at' => '2026-05-09 14:58:24',
                'updated_at' => '2026-05-09 14:58:24',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Transfer Mandiri',
                'type' => 'bank_transfer',
                'bank_name' => 'Mandiri',
                'account_number' => '1234567890',
                'account_name' => 'Polman Bandung',
                'instruction' => NULL,
                'is_active' => 1,
                'created_at' => '2026-05-09 15:02:04',
                'updated_at' => '2026-05-09 15:02:04',
            ),
        ));
        
        
    }
}