<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductVariantsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('product_variants')->delete();
        
        \DB::table('product_variants')->insert(array (
            0 => 
            array (
                'id' => 9,
                'product_id' => 11,
                'name' => 'katun bintik',
                'specification' => 'APD yang terbuat dari rajutan benang katun dengan bintik PVC/karet di telapak tangan, memberikan cengkeraman anti-selip, kenyamanan maksimal, dan perlindungan tangan dari gesekan serta kotoran.',
                'price' => '15000.00',
                'stock' => 31,
                'image' => NULL,
                'status' => 'active',
                'created_at' => '2026-04-30 15:00:19',
                'updated_at' => '2026-05-07 10:20:03',
            ),
            1 => 
            array (
                'id' => 10,
                'product_id' => 11,
                'name' => 'nitrile',
            'specification' => 'APD berbahan karet sintetis (acrylonitrile butadiene) yang unggul dalam ketahanan terhadap bahan kimia, minyak, pelarut, dan risiko tusukan/sobekan.',
                'price' => '25000.00',
                'stock' => 21,
                'image' => NULL,
                'status' => 'active',
                'created_at' => '2026-04-30 15:01:25',
                'updated_at' => '2026-05-06 10:35:04',
            ),
            2 => 
            array (
                'id' => 11,
                'product_id' => 10,
                'name' => 'Solder Listrik',
            'specification' => 'alat pemanas yang mengubah energi listrik menjadi panas untuk melelehkan timah (logam pengisi) guna menyambungkan komponen elektronik atau kabel pada papan sirkuit (PCB).',
                'price' => '40000.00',
                'stock' => 15,
                'image' => NULL,
                'status' => 'active',
                'created_at' => '2026-04-30 15:03:54',
                'updated_at' => '2026-04-30 15:06:38',
            ),
            3 => 
            array (
                'id' => 12,
                'product_id' => 10,
                'name' => 'Solder Gun',
                'specification' => 'alat penyolderan berbentuk pistol dengan pelatuk, dirancang untuk memanaskan timah dengan cepat menggunakan daya listrik.',
                'price' => '170000.00',
                'stock' => 8,
                'image' => NULL,
                'status' => 'active',
                'created_at' => '2026-04-30 15:04:52',
                'updated_at' => '2026-04-30 15:04:52',
            ),
            4 => 
            array (
                'id' => 13,
                'product_id' => 10,
                'name' => 'Solder Uap',
                'specification' => 'perangkat khusus untuk mengangkat komponen kecil seperti SMD dan BGA ini dibutuhkan solder uap bertemperatur tinggi.',
                'price' => '320000.00',
                'stock' => 2,
                'image' => NULL,
                'status' => 'active',
                'created_at' => '2026-04-30 15:06:16',
                'updated_at' => '2026-04-30 15:06:16',
            ),
            5 => 
            array (
                'id' => 14,
                'product_id' => 9,
            'name' => 'Arduino Uno (R3/R4)',
                'specification' => 'papan mikrokontroler berbasis open-source yang paling populer digunakan untuk membuat proyek elektronik, otomasi, dan robotika.',
                'price' => '50000.00',
                'stock' => 55,
                'image' => NULL,
                'status' => 'active',
                'created_at' => '2026-04-30 15:07:42',
                'updated_at' => '2026-04-30 15:07:42',
            ),
            6 => 
            array (
                'id' => 15,
                'product_id' => 9,
                'name' => 'Arduino Nano',
                'specification' => 'papan pengembangan mikrokontroler berbasis ATmega328P yang berukuran kecil, ringan, dan ramah breadboard, dirilis tahun 2008.',
                'price' => '35000.00',
                'stock' => 68,
                'image' => NULL,
                'status' => 'active',
                'created_at' => '2026-04-30 15:08:27',
                'updated_at' => '2026-04-30 15:08:27',
            ),
            7 => 
            array (
                'id' => 16,
                'product_id' => 9,
                'name' => 'Arduino Mega 2560',
            'specification' => 'papan mikrokontroler berbasis ATmega2560 yang dirancang untuk proyek kompleks, menawarkan 54 pin I/O digital (15 PWM), 16 input analog, dan 4 UART.',
                'price' => '175000.00',
                'stock' => 26,
                'image' => NULL,
                'status' => 'active',
                'created_at' => '2026-04-30 15:09:10',
                'updated_at' => '2026-04-30 15:09:10',
            ),
            8 => 
            array (
                'id' => 17,
                'product_id' => 8,
                'name' => 'ESP32',
            'specification' => 'mikrokontroler System-on-a-Chip (SoC) berbiaya rendah dan hemat energi yang dikembangkan oleh Espressif Systems, dirancang khusus untuk aplikasi Internet of Things (IoT).',
                'price' => '40000.00',
                'stock' => 13,
                'image' => NULL,
                'status' => 'active',
                'created_at' => '2026-04-30 15:10:47',
                'updated_at' => '2026-05-06 09:57:08',
            ),
            9 => 
            array (
                'id' => 18,
                'product_id' => 12,
                'name' => 'omen 015',
                'specification' => 'aptop gaming 15,3 inci yang dirancang ulang untuk performa tinggi dan portabilitas, menampilkan desain elegan dengan branding HyperX.',
                'price' => '23000000.00',
                'stock' => 1,
                'image' => NULL,
                'status' => 'active',
                'created_at' => '2026-04-30 15:12:43',
                'updated_at' => '2026-05-06 13:04:28',
            ),
            10 => 
            array (
                'id' => 19,
                'product_id' => 19,
                'name' => 'Teaching Aid REL',
            'specification' => 'Ukuran (P × L × T) : 120 × 80 × 165 cm
Berat : 51 kg
Material Rangka : Aluminium
Daya Listrik : 380/220 VAC, 3 Fasa

Kelengkapan dalam satu set:

Meja Praktik
Rangka terbuat dari Aluminium Profile
Daun meja terbuat dari Multiplex 18 mm

PCB Matrix
Bahan Akrilik : 5 mm
Ukuran : 21 × 30 × 2 cm
Berat : 1 kg
Jumlah : 10 pcs

Rak Komponen
Bahan : Kayu
Ukuran : 70 × 30 × 120 cm
Jumlah : 1 pcs

Plug-in Komponen
Resistor 0.5 W (berbagai nilai) : 30 pcs
Resistor 5 W : 3 pcs
Resistor 10 W : 3 pcs
Potensiometer (berbagai nilai) : 8 pcs
Kapasitor (berbagai nilai) : 24 pcs
Induktor (berbagai nilai) : 6 pcs
Dioda dan Zenner (berbagai tipe) : 12 pcs
Transistor (berbagai tipe) : 10 pcs
Thyristor (berbagai tipe) : 3 pcs
Op-Amp : 2 pcs
Micro-switch (SPDT) : 1 pcs
Micro-switch (DPDT) : 1 pcs
Dudukan Lampu : 1 pcs
Jumper : 12 pcs
Kontak Jumper : 1 pcs
Bola Lampu (berbagai jenis) : 3 pcs
Kabel (berbagai panjang dan warna) : 42 pcs',
                'price' => '80000000.00',
                'stock' => 4,
                'image' => NULL,
                'status' => 'active',
                'created_at' => '2026-05-07 11:33:09',
                'updated_at' => '2026-05-07 11:33:09',
            ),
            11 => 
            array (
                'id' => 20,
                'product_id' => 20,
                'name' => 'Teaching Aid IML',
                'specification' => 'Umum

Ukuran : 46 × 144 × 171 cm
Berat : 35 kg
Material Rangka : Alumunium
Aktuator : Motor Listrik 3 Fasa
Daya Listrik : 380/220 VAC, 3 Fasa

Kelengkapan dalam satu set:

Motor Listrik
Motor 1 fasa 220V 4,3A 4 poles 0,5HP : 1 unit
Motor 3 fasa 220V 3,6A 4 poles 1HP : 1 unit
Motor Dua Kecepatan : 1 unit

Modul - Panel IML 1
Tombol Emergency 250V 5A : 1 pcs
MCB 3 fasa 10A : 1 pcs
MCB 3 fasa 6A : 1 pcs
MCB 1 fasa 2A : 1 pcs
CAM Starter 500V 15A : 1 pcs
Terminal Block : 15 pcs

Modul - Panel IML 2
CAM Starter 500V 15A : 1 pcs
Saklar SPDT 250V 10A : 2 pcs
Saklar DPDT 250V 15A : 1 pcs
Terminal Block : 23 pcs

Modul - Panel IML 3
Fitting Lampu : 3 pcs
Socket Relay 8 pin : 3 pcs
Relay 8 pin : 3 pcs
Terminal Block : 30 pcs

Modul - Panel IML 4
Star - Delta Switch 690V 16A : 1 pcs
Rotary CAM Switch 440V 20A : 1 pcs
Limit Switch Roller 250V 15A : 2 pcs
Terminal Block : 24 pcs

Modul - Panel IML 5
Kontaktor Magnet : 3 pcs
Terminal Block : 52 pcs

Modul - Panel IML 6
Kontaktor Magnet : 3 pcs
Overload Relay : 1 pcs
Terminal Block : 58 pcs

Modul - Panel IML 7
Tombol Tekan 250V 5A : 4 pcs
Lampu Indikator 250V 10A : 4 pcs
Terminal Block : 24 pcs',
                'price' => '55000000.00',
                'stock' => 7,
                'image' => NULL,
                'status' => 'active',
                'created_at' => '2026-05-07 11:35:08',
                'updated_at' => '2026-05-07 11:35:08',
            ),
            12 => 
            array (
                'id' => 21,
                'product_id' => 21,
                'name' => 'Versi Koper',
                'specification' => 'Teaching Aid PLC versi Koper merupakan alat
bantu pembelajaran yang dirancang secara
khusus ringkas (compact), dan mudah dibawa
kemana mana (portable). Dibuat untuk
mempermudah pembelajaran dan pelatihan
pembuatan program PLC.
Alat ini terdiri dari PLC sebagai unit kendali
utama dan komponen listrik dasar seperti
tombol , selektor switch dan lampu sebagai
simulasi sensor dan aktuator
Alat ini sangat ringkas dan mudah dibawa
kemana mana, memudahkan proses
pembelajaran dan eksplorasi program PLC

Spesifikasi Teknis

Ukuran : 46.5 × 15.5 × 35.5 cm
Berat : 5 kg
Material Koper : Alumunium
Daya Listrik : 220 VAC

Kelengkapan dalam satu set:

PLC Omron CP1H : 1 unit
Tombol Emergency : 1 pcs
Tombol Tekan : 6 pcs
Selektor : 2 pcs
Lampu Indikator : 6 pcs
Fuse : 1 pcs
Socket Listrik AC : 1 pcs
Socket Listrik DC : 6 pcs
Socket IO : 15 pcs
Terminal Block : 1 set
Kabel Banana Jack : 1 paket',
                'price' => '15000000.00',
                'stock' => 8,
                'image' => 'variants/ugQICbptHQWg7I9WhuUGgrgoqgAtjNsQuSNcoki0.png',
                'status' => 'active',
                'created_at' => '2026-05-07 11:40:27',
                'updated_at' => '2026-05-07 13:51:36',
            ),
            13 => 
            array (
                'id' => 22,
                'product_id' => 21,
                'name' => 'versi Plan XYZ',
            'specification' => 'Teaching Aid PLC versi Plan XYZ merupakan alat bantu pembelajaran yang dirancang secara khusus dan terpadu. Dibuat sebagai simulasi proses kendali di industri. Alat ini menggabungkan PLC sebagai unit kendali utama dan komponen pneumatik sebagai aktuator. Dilengkapi dengan variasi tombol dan perangkat HMI (Human Machine Interface) sebagai antarmuka pengguna/operator untuk mengendalikan operasi kerja dari alat.

Alat ini bekerja dengan memindahkan benda dari suatu titik ke titik lain dengan langkah/sekuen kerja tertentu sehingga tidak menimbulkan tabrakan antara benda kerja. Terdapat 6 titik koordinat tempat benda diletakkan, alat dapat bergerak pada sumbu yaitu sumbu X, sumbu Y dan sumbu Z. Peserta pembelajaran harus mampu merancang langkah kerja mesin dan mewujudkannya dalam program PLC serta tampilan operator pada HMI untuk menghasilkan fungsi kerja mesin sesuai harapan.

Spesifikasi Teknis
Umum
Ukuran : 86 x 66 x 137 cm
Berat : 50kg
Material Penutup : Plat Besi
Sensor : Proximity Optic
Aktuator : Silinder Pneumatik
Daya Listrik : 220VAC

Kelengkapan dalam satu set
Panel Input dan Output
Lampu Indikator : 3 pcs
Tombol Emergency : 1 pcs
Tombol Operasi : 2 pcs
Selektor : 2 pcs

Unit Kendali Utama
PLC Omron CP1H : 1 unit
PLC Mitsubishi FX3U : 1 unit

Antarmuka Operator
HMI Omron tipe NB : 1 unit
HMI Mitsubishi tipe GS : 1 unit

Sistem Pneumatik
Silinder sumbu X : 1 unit
Silinder sumbu Y : 1 unit
Silinder sumbu Z : 1 unit
Vaccuum Pad : 1 unit
Selenoid Valve 5/3 : 1 unit
Selenoid Valve 5/2 : 2 unit
Vaccum Venturi Valve : 1 unit
Reed Switch Sensor : 6 pcs
Vaccuum Sensor : 1 pcs
Selang Pneumatik (Tube) : 1 set

Asesoris
Benda Kerja Kotak : 2 pcs
Benda Kerja Bundar : 2 pcs',
            'price' => '69599999.00',
            'stock' => 2,
            'image' => 'variants/y4iwSjU1eIYFRsINLgTgJvfoAsGESSvam4Pqx11b.png',
            'status' => 'active',
            'created_at' => '2026-05-07 11:41:43',
            'updated_at' => '2026-05-07 13:51:00',
        ),
    ));
        
        
    }
}