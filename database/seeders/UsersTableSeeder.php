<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 3,
                'name' => 'Test User',
                'email' => 'test@example.com',
                'email_verified_at' => '2026-04-28 13:42:47',
                'password' => '$2y$12$GRvw4KiXjjO/1jh4otmSQeyUxAV0tqDqiN9u1oslTB8qjCf9YfKHG',
                'role' => 'user',
                'account_type' => 'individual',
                'phone' => NULL,
                'company_name' => NULL,
                'contact_person' => NULL,
                'default_recipient_name' => NULL,
                'default_province_id' => NULL,
                'default_province' => NULL,
                'default_city_id' => NULL,
                'default_city' => NULL,
                'default_district_id' => NULL,
                'default_district' => NULL,
                'default_postal_code' => NULL,
                'default_full_address' => NULL,
                'remember_token' => 'k6CmWqMbW7',
                'created_at' => '2026-04-28 13:42:48',
                'updated_at' => '2026-04-28 13:42:48',
            ),
            1 => 
            array (
                'id' => 7,
                'name' => 'Azka Shafa Eka Poetra',
                'email' => 'awc8gt@gmail.com',
                'email_verified_at' => '2026-05-07 09:15:21',
                'password' => '$2y$12$MtraMIMgAC5C.4rdm8YsNefqO2V4lMOMkY69SfhRf.TeI.zXzH6hS',
                'role' => 'admin',
                'account_type' => 'individual',
                'phone' => '+6287876498384',
                'company_name' => NULL,
                'contact_person' => NULL,
                'default_recipient_name' => 'Azka Shafa Eka Poetra',
                'default_province_id' => NULL,
                'default_province' => 'Jawa Barat',
                'default_city_id' => NULL,
                'default_city' => 'Bandung',
                'default_district_id' => NULL,
                'default_district' => 'Coblong',
                'default_postal_code' => '40135',
                'default_full_address' => 'Jl. Kanayakan No.21',
                'remember_token' => NULL,
                'created_at' => '2026-05-07 09:14:19',
                'updated_at' => '2026-05-07 09:16:04',
            ),
        ));
        
        
    }
}