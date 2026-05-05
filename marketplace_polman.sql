-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Bulan Mei 2026 pada 10.29
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marketplace_polman`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `full_address` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-04-28 06:31:17', '2026-04-28 06:31:17'),
(2, 2, '2026-04-28 07:05:22', '2026-04-28 07:05:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_variant_id`, `quantity`, `created_at`, `updated_at`) VALUES
(26, 2, 10, 5, '2026-05-04 05:53:23', '2026-05-04 05:53:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(13, 'Mikrokontroler', 'mikrokontroler', 'komputer kecil dalam satu chip IC (Integrated Circuit) yang berisi inti prosesor (CPU), memori (RAM/ROM), dan port I/O, dirancang khusus untuk mengendalikan fungsi atau tugas tertentu (embedded system).', '2026-04-30 07:47:49', '2026-04-30 07:47:49'),
(14, 'Alat Praktikum', 'alat-praktikum', 'peralatan industri berat dan teknologi manufaktur presisi. Karena sistem pendidikannya berbasis produksi (Production Based Education), alat yang digunakan setara dengan standar industri.', '2026-04-30 07:49:52', '2026-04-30 07:49:52'),
(15, 'K3', 'k3', 'perlengkapan wajib yang dirancang untuk melindungi pekerja dari risiko kecelakaan dan penyakit akibat kerja (PAK) dengan cara menjadi penghalang antara tenaga kerja dan bahaya. APD mencakup pelindung kepala, mata, telinga, pernapasan, tangan, hingga kaki, yang disesuaikan dengan jenis pekerjaan.', '2026-04-30 07:50:57', '2026-04-30 07:50:57'),
(16, 'Komponen Mekanik', 'komponen-mekanik', 'bagian-bagian tunggal yang dirancang dengan fungsi khusus, dimensi, dan material tertentu untuk digunakan dalam perakitan berbagai alat atau mesin. Komponen ini umumnya diproduksi secara massal dan mengacu pada standar industri tertentu (seperti ISO, DIN, JIS, atau ANSI) agar dapat dipertukarkan (interchangeable).', '2026-04-30 07:51:53', '2026-04-30 07:51:53'),
(17, 'Elektronik', 'elektronik', 'alat, perangkat, atau sistem yang beroperasi berdasarkan prinsip elektronika, menggunakan arus listrik kecil, komponen aktif (seperti transistor/mikrochip) untuk memproses, menyimpan, atau mengirim informasi.', '2026-04-30 08:11:26', '2026-04-30 08:11:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_21_045648_add_role_to_users_table', 1),
(5, '2026_04_21_054513_create_categories_table', 1),
(6, '2026_04_21_060319_create_products_table', 1),
(7, '2026_04_21_070240_create_product_variants_table', 1),
(8, '2026_04_22_052333_create_carts_table', 1),
(9, '2026_04_22_052334_create_cart_items_table', 1),
(10, '2026_04_22_055340_create_addresses_table', 1),
(11, '2026_04_22_055340_create_orders_table', 1),
(12, '2026_04_22_055341_create_order_items_table', 1),
(13, '2026_04_23_020406_create_payment_receipts_table', 1),
(14, '2026_04_23_063138_add_payment_deadline_at_to_orders_table', 1),
(15, '2026_04_27_013111_create_payment_methods_table', 1),
(16, '2026_04_27_013112_add_payment_snapshot_to_orders_table', 1),
(17, '2026_04_28_102712_add_account_type_fields_to_users_table', 1),
(18, '2026_04_28_110736_add_default_address_fields_to_users_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `address_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_code` varchar(255) NOT NULL,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) NOT NULL DEFAULT 'bank_transfer',
  `payment_method_name` varchar(255) DEFAULT NULL,
  `payment_bank_name` varchar(255) DEFAULT NULL,
  `payment_account_number` varchar(255) DEFAULT NULL,
  `payment_account_name` varchar(255) DEFAULT NULL,
  `payment_instruction` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'waiting_payment',
  `payment_deadline_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `variant_name` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'bank_transfer',
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `instruction` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment_receipts`
--

CREATE TABLE `payment_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `receipt_file` varchar(255) NOT NULL,
  `validation_status` varchar(255) NOT NULL DEFAULT 'pending',
  `admin_note` text DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `stock`, `image`, `status`, `created_at`, `updated_at`) VALUES
(8, 13, 'ESP', 'ESP', 'keluarga chip dan modul System-on-a-Chip (SoC) berbiaya rendah dan berdaya rendah yang dikembangkan oleh Espressif Systems.', 0.00, 0, 'products/rBjLYPwXHdKsDGKSeLbR3t0A1HFUbhBPuDWQPWyE.jpg', 'active', '2026-04-30 07:54:14', '2026-04-30 07:54:14'),
(9, 13, 'Arduino', 'Arduino', 'sebuah platform open-source yang menggabungkan perangkat keras (board) dan perangkat lunak (IDE) untuk memudahkan pengembangan proyek elektronik.', 0.00, 0, 'products/iCbDHiAg0xAW1KRY9vAwjaYUjwWKXM07wPa6arDI.jpg', 'active', '2026-04-30 07:55:13', '2026-04-30 07:55:13'),
(10, 14, 'Solder', 'solder', 'proses penyambungan dua komponen logam atau lebih (biasanya komponen elektronik ke PCB) dengan menggunakan logam pengisi (timah) yang dilelehkan.', 0.00, 0, 'products/M8fdXYNjD2bEfe8kFJypWMMU8Vu6auiTsBu6lruq.jpg', 'active', '2026-04-30 07:57:12', '2026-04-30 08:02:36'),
(11, 15, 'Sarung tangan kerja', 'sarung-tangan-kerja', 'alat pelindung diri (APD) yang dirancang untuk melindungi tangan dan jari dari risiko cedera seperti sayatan, tusukan, bahan kimia, suhu ekstrem, hingga benturan.', 0.00, 0, 'products/l62zeXKsDDz5MnFTBjUFP9BQKg9kSid8eoX9IU4z.jpg', 'active', '2026-04-30 07:59:00', '2026-04-30 07:59:00'),
(12, 17, 'Laptop', 'laptop', 'komputer pribadi (PC) portabel yang ringan dan ringkas, dirancang untuk mudah dibawa-bawa.', 0.00, 0, 'products/default_1777536719.png', 'active', '2026-04-30 08:11:59', '2026-04-30 08:11:59'),
(13, 15, 'Helm', 'helm', 'untuk melindungi kepala', 0.00, 0, 'products/default_1777864339.png', 'active', '2026-05-04 03:12:19', '2026-05-04 03:12:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `specification` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `name`, `specification`, `price`, `stock`, `status`, `created_at`, `updated_at`) VALUES
(9, 11, 'katun bintik', 'APD yang terbuat dari rajutan benang katun dengan bintik PVC/karet di telapak tangan, memberikan cengkeraman anti-selip, kenyamanan maksimal, dan perlindungan tangan dari gesekan serta kotoran.', 15000.00, 31, 'active', '2026-04-30 08:00:19', '2026-04-30 08:00:19'),
(10, 11, 'nitrile', 'APD berbahan karet sintetis (acrylonitrile butadiene) yang unggul dalam ketahanan terhadap bahan kimia, minyak, pelarut, dan risiko tusukan/sobekan.', 25000.00, 24, 'active', '2026-04-30 08:01:25', '2026-04-30 08:01:25'),
(11, 10, 'Solder Listrik', 'alat pemanas yang mengubah energi listrik menjadi panas untuk melelehkan timah (logam pengisi) guna menyambungkan komponen elektronik atau kabel pada papan sirkuit (PCB).', 40000.00, 15, 'active', '2026-04-30 08:03:54', '2026-04-30 08:06:38'),
(12, 10, 'Solder Gun', 'alat penyolderan berbentuk pistol dengan pelatuk, dirancang untuk memanaskan timah dengan cepat menggunakan daya listrik.', 170000.00, 8, 'active', '2026-04-30 08:04:52', '2026-04-30 08:04:52'),
(13, 10, 'Solder Uap', 'perangkat khusus untuk mengangkat komponen kecil seperti SMD dan BGA ini dibutuhkan solder uap bertemperatur tinggi.', 320000.00, 2, 'active', '2026-04-30 08:06:16', '2026-04-30 08:06:16'),
(14, 9, 'Arduino Uno (R3/R4)', 'papan mikrokontroler berbasis open-source yang paling populer digunakan untuk membuat proyek elektronik, otomasi, dan robotika.', 50000.00, 55, 'active', '2026-04-30 08:07:42', '2026-04-30 08:07:42'),
(15, 9, 'Arduino Nano', 'papan pengembangan mikrokontroler berbasis ATmega328P yang berukuran kecil, ringan, dan ramah breadboard, dirilis tahun 2008.', 35000.00, 68, 'active', '2026-04-30 08:08:27', '2026-04-30 08:08:27'),
(16, 9, 'Arduino Mega 2560', 'papan mikrokontroler berbasis ATmega2560 yang dirancang untuk proyek kompleks, menawarkan 54 pin I/O digital (15 PWM), 16 input analog, dan 4 UART.', 175000.00, 26, 'active', '2026-04-30 08:09:10', '2026-04-30 08:09:10'),
(17, 8, 'ESP32', 'mikrokontroler System-on-a-Chip (SoC) berbiaya rendah dan hemat energi yang dikembangkan oleh Espressif Systems, dirancang khusus untuk aplikasi Internet of Things (IoT).', 40000.00, 13, 'active', '2026-04-30 08:10:47', '2026-04-30 08:10:47'),
(18, 12, 'omen 015', 'aptop gaming 15,3 inci yang dirancang ulang untuk performa tinggi dan portabilitas, menampilkan desain elegan dengan branding HyperX.', 23000000.00, 1, 'active', '2026-04-30 08:12:43', '2026-04-30 08:12:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `account_type` varchar(255) NOT NULL DEFAULT 'individual',
  `phone` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `default_recipient_name` varchar(255) DEFAULT NULL,
  `default_province` varchar(255) DEFAULT NULL,
  `default_city` varchar(255) DEFAULT NULL,
  `default_district` varchar(255) DEFAULT NULL,
  `default_postal_code` varchar(255) DEFAULT NULL,
  `default_full_address` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `account_type`, `phone`, `company_name`, `contact_person`, `default_recipient_name`, `default_province`, `default_city`, `default_district`, `default_postal_code`, `default_full_address`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Azka Shafa Eka Poetra', 'awc8gt@gmail.com', NULL, '$2y$12$MmLJNlVhFKv174Nt1XiiiOEKLQEO28K99DOof7iKf2mA5.diVOOtC', 'user', 'individual', '+6287876498384', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'oMjx2SS0tHIGKLQvZ1vapiY7xmH9wJ6MPgpCU4NhNgEFlV4KzCQ9eFwZC0O0', '2026-04-28 06:30:34', '2026-04-28 06:30:34'),
(2, 'Eka Poetra', 'azka123456b@gmail.com', NULL, '$2y$12$IWaLBtygNlYbHqfk4PUG..XnpZL2SYR5mYNXylplhdF3.pcOFLWR.', 'admin', 'company', '+6287876498384', 'Chamenoss', 'Eka Poetra', NULL, NULL, NULL, NULL, NULL, NULL, 'cRPFQKXXQuKwwcHGZhoq90zF4VONxOwnN1CXDMLRme9Gv60u2GS1f7PkqUYL', '2026-04-28 06:39:33', '2026-04-28 06:39:33'),
(3, 'Test User', 'test@example.com', '2026-04-28 06:42:47', '$2y$12$GRvw4KiXjjO/1jh4otmSQeyUxAV0tqDqiN9u1oslTB8qjCf9YfKHG', 'user', 'individual', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'k6CmWqMbW7', '2026-04-28 06:42:48', '2026-04-28 06:42:48');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carts_user_id_unique` (`user_id`);

--
-- Indeks untuk tabel `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_cart_id_product_variant_id_unique` (`cart_id`,`product_variant_id`),
  ADD KEY `cart_items_product_variant_id_foreign` (`product_variant_id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_address_id_foreign` (`address_id`),
  ADD KEY `orders_payment_method_id_foreign` (`payment_method_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_product_variant_id_foreign` (`product_variant_id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payment_receipts`
--
ALTER TABLE `payment_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_receipts_order_id_unique` (`order_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indeks untuk tabel `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `payment_receipts`
--
ALTER TABLE `payment_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payment_receipts`
--
ALTER TABLE `payment_receipts`
  ADD CONSTRAINT `payment_receipts_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
