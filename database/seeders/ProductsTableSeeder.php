<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('products')->delete();
        
        \DB::table('products')->insert(array (
            0 => 
            array (
                'id' => 8,
                'category_id' => 13,
                'name' => 'ESP',
                'slug' => 'ESP',
            'description' => 'keluarga chip dan modul System-on-a-Chip (SoC) berbiaya rendah dan berdaya rendah yang dikembangkan oleh Espressif Systems.',
                'price' => '0.00',
                'stock' => 0,
                'image' => 'products/rBjLYPwXHdKsDGKSeLbR3t0A1HFUbhBPuDWQPWyE.jpg',
                'status' => 'active',
                'created_at' => '2026-04-30 14:54:14',
                'updated_at' => '2026-04-30 14:54:14',
            ),
            1 => 
            array (
                'id' => 9,
                'category_id' => 13,
                'name' => 'Arduino',
                'slug' => 'Arduino',
            'description' => 'sebuah platform open-source yang menggabungkan perangkat keras (board) dan perangkat lunak (IDE) untuk memudahkan pengembangan proyek elektronik.',
                'price' => '0.00',
                'stock' => 0,
                'image' => 'products/iCbDHiAg0xAW1KRY9vAwjaYUjwWKXM07wPa6arDI.jpg',
                'status' => 'active',
                'created_at' => '2026-04-30 14:55:13',
                'updated_at' => '2026-04-30 14:55:13',
            ),
            2 => 
            array (
                'id' => 10,
                'category_id' => 14,
                'name' => 'Solder',
                'slug' => 'solder',
            'description' => 'proses penyambungan dua komponen logam atau lebih (biasanya komponen elektronik ke PCB) dengan menggunakan logam pengisi (timah) yang dilelehkan.',
                'price' => '0.00',
                'stock' => 0,
                'image' => 'products/M8fdXYNjD2bEfe8kFJypWMMU8Vu6auiTsBu6lruq.jpg',
                'status' => 'active',
                'created_at' => '2026-04-30 14:57:12',
                'updated_at' => '2026-04-30 15:02:36',
            ),
            3 => 
            array (
                'id' => 11,
                'category_id' => 15,
                'name' => 'Sarung tangan kerja',
                'slug' => 'sarung-tangan-kerja',
            'description' => 'alat pelindung diri (APD) yang dirancang untuk melindungi tangan dan jari dari risiko cedera seperti sayatan, tusukan, bahan kimia, suhu ekstrem, hingga benturan.',
                'price' => '0.00',
                'stock' => 0,
                'image' => 'products/l62zeXKsDDz5MnFTBjUFP9BQKg9kSid8eoX9IU4z.jpg',
                'status' => 'active',
                'created_at' => '2026-04-30 14:59:00',
                'updated_at' => '2026-04-30 14:59:00',
            ),
            4 => 
            array (
                'id' => 12,
                'category_id' => 17,
                'name' => 'Laptop',
                'slug' => 'laptop',
            'description' => 'komputer pribadi (PC) portabel yang ringan dan ringkas, dirancang untuk mudah dibawa-bawa.',
                'price' => '0.00',
                'stock' => 0,
                'image' => 'products/default_1777536719.png',
                'status' => 'active',
                'created_at' => '2026-04-30 15:11:59',
                'updated_at' => '2026-04-30 15:11:59',
            ),
            5 => 
            array (
                'id' => 13,
                'category_id' => 15,
                'name' => 'Helm',
                'slug' => 'helm',
                'description' => 'untuk melindungi kepala',
                'price' => '0.00',
                'stock' => 0,
                'image' => 'products/default_1777864339.png',
                'status' => 'active',
                'created_at' => '2026-05-04 10:12:19',
                'updated_at' => '2026-05-04 10:12:19',
            ),
            6 => 
            array (
                'id' => 19,
                'category_id' => 18,
                'name' => 'Teaching Aid REL',
                'slug' => 'teaching aid-rel',
            'description' => 'Teaching Aid REL (Rangkaian Elektrik dan Elektronika Analog) merupakan alat bantu pembelajaran yang dirancang secara khusus untuk membantu proses pembelajaran dan praktik Pengukuran elektrik, Instalasi rangkaian elektrik dan elektronika analog.
Dilengkapi dengan lemari komponen untuk memudahkan peletakan dan penataan komponen.
Ruang lingkup pembelajaran dan praktik mengarah pada pengukuran elektrik, instrumentasi, instalasi rangkaian elektrik, dan instalasi rangkaian elektronika analog.
Komponen elektrik meliputi resistor, induktor dan kapasitor dalam rangkaian yang terhubung pada sumber tegangan AC maupun DC.
Komponen elektronika analog meliputi Dioda, Transistor bipolar, Operational Amplifier, SCR, FET, dsb.',
            'price' => '0.00',
            'stock' => 0,
            'image' => 'products/53FNHfLQ5iupjNJztWclhmNR1XMfNXC1RHLJhynS.png',
            'status' => 'active',
            'created_at' => '2026-05-07 11:32:02',
            'updated_at' => '2026-05-07 11:32:02',
        ),
        7 => 
        array (
            'id' => 20,
            'category_id' => 18,
            'name' => 'Teaching Aid IML',
            'slug' => 'teaching-aid-iml',
        'description' => 'Teaching Aid IML (Instalasi Mesin Listrik)
merupakan alat bantu pembelajaran yang
dirancang secara khusus untuk membantu
proses pembelajaran dan praktik Instalasi
Mesin Listrik. Dilengkapi dengan laci
komponen untuk memudahkan peletakan
dan penataan komponen
Ruang lingkup pembelajaran dan praktik
mengarah pada instalasi listrik untuk
melayani dan mengoperasikan mesin listrik
dengan kapasitas daya relatif rendah (<5 HP)
Instalasi listrik yang dapat diuji dan dipelajari
pada alat ini berkaitan dengan rangkaian Seri,
rangkaian Paralel, rangkaian campuran Seri-
Paralel, penggunaan Timer, pengasutan
Motor Listrik Star-Delta, pengereman Motor,
Listrik, kendali arah putar Motor Listrik,
kendali Motor Listrik dua kecepatan, dsb.',
        'price' => '0.00',
        'stock' => 0,
        'image' => 'products/Qah7ryZGA9UmkF4Cr1FYYWcUqHLblJerk0vor5rx.png',
        'status' => 'active',
        'created_at' => '2026-05-07 11:34:13',
        'updated_at' => '2026-05-07 11:34:13',
    ),
    8 => 
    array (
        'id' => 21,
        'category_id' => 18,
        'name' => 'Teaching Aid PLC',
        'slug' => 'teaching-aid-plc',
        'description' => 'Teaching Aid PLC merupakan media pembelajaran interaktif yang dirancang untuk menjembatani teori pemrograman dengan aplikasi industri, mulai dari simulasi logika dasar hingga pengendalian sistem pneumatik dan antarmuka HMI yang kompleks.',
        'price' => '0.00',
        'stock' => 0,
        'image' => 'products/h3Y1QAjrJ7LYM8bUmRqNtLiXQ9C7Mt7G4cnYx72P.png',
        'status' => 'active',
        'created_at' => '2026-05-07 11:38:46',
        'updated_at' => '2026-05-07 11:38:46',
    ),
));
        
        
    }
}