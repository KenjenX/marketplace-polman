<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 13,
                'name' => 'Mikrokontroler',
                'slug' => 'mikrokontroler',
            'description' => 'komputer kecil dalam satu chip IC (Integrated Circuit) yang berisi inti prosesor (CPU), memori (RAM/ROM), dan port I/O, dirancang khusus untuk mengendalikan fungsi atau tugas tertentu (embedded system).',
                'created_at' => '2026-04-30 14:47:49',
                'updated_at' => '2026-04-30 14:47:49',
            ),
            1 => 
            array (
                'id' => 14,
                'name' => 'Alat Praktikum',
                'slug' => 'alat-praktikum',
            'description' => 'peralatan industri berat dan teknologi manufaktur presisi. Karena sistem pendidikannya berbasis produksi (Production Based Education), alat yang digunakan setara dengan standar industri.',
                'created_at' => '2026-04-30 14:49:52',
                'updated_at' => '2026-04-30 14:49:52',
            ),
            2 => 
            array (
                'id' => 15,
                'name' => 'K3',
                'slug' => 'k3',
            'description' => 'perlengkapan wajib yang dirancang untuk melindungi pekerja dari risiko kecelakaan dan penyakit akibat kerja (PAK) dengan cara menjadi penghalang antara tenaga kerja dan bahaya. APD mencakup pelindung kepala, mata, telinga, pernapasan, tangan, hingga kaki, yang disesuaikan dengan jenis pekerjaan.',
                'created_at' => '2026-04-30 14:50:57',
                'updated_at' => '2026-04-30 14:50:57',
            ),
            3 => 
            array (
                'id' => 16,
                'name' => 'Komponen Mekanik',
                'slug' => 'komponen-mekanik',
            'description' => 'bagian-bagian tunggal yang dirancang dengan fungsi khusus, dimensi, dan material tertentu untuk digunakan dalam perakitan berbagai alat atau mesin. Komponen ini umumnya diproduksi secara massal dan mengacu pada standar industri tertentu (seperti ISO, DIN, JIS, atau ANSI) agar dapat dipertukarkan (interchangeable).',
                'created_at' => '2026-04-30 14:51:53',
                'updated_at' => '2026-04-30 14:51:53',
            ),
            4 => 
            array (
                'id' => 17,
                'name' => 'Elektronik',
                'slug' => 'elektronik',
            'description' => 'alat, perangkat, atau sistem yang beroperasi berdasarkan prinsip elektronika, menggunakan arus listrik kecil, komponen aktif (seperti transistor/mikrochip) untuk memproses, menyimpan, atau mengirim informasi.',
                'created_at' => '2026-04-30 15:11:26',
                'updated_at' => '2026-04-30 15:11:26',
            ),
            5 => 
            array (
                'id' => 18,
                'name' => 'Teaching Aid',
                'slug' => 'teaching-aid',
            'description' => 'Teaching aids (alat bantu mengajar) adalah berbagai material, alat, atau perangkat yang digunakan oleh guru atau instruktur di dalam kelas untuk membuat proses belajar-mengajar lebih menarik, mudah dipahami, efektif, dan interaktif. Alat ini berfungsi sebagai pendukung materi pelajaran, bukan sebagai pengganti utama pengajaran, dan bertujuan untuk merangsang minat serta meningkatkan potensi belajar siswa.',
                'created_at' => '2026-05-07 11:28:32',
                'updated_at' => '2026-05-07 11:28:32',
            ),
        ));
        
        
    }
}