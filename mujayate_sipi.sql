-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 08, 2025 at 06:18 AM
-- Server version: 10.6.21-MariaDB-cll-lve
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mujayate_sipi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telp` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `user_id`, `nama`, `alamat`, `telp`, `photo`, `inserted_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mushaf', 'Jl. Alamat', '085423444567', NULL, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(2, 2, 'Leonardo', 'Jl. Jalan', '0854331212', NULL, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('captcha_1f3c50c4f1ed0ca81eb052922fb0e933', 'a:4:{i:0;s:1:\"h\";i:1;s:1:\"x\";i:2;s:1:\"d\";i:3;s:1:\"a\";}', 1741386081),
('captcha_26f07cf7ebb8cda5f9d1195d790fcfce', 'a:4:{i:0;s:1:\"t\";i:1;s:1:\"t\";i:2;s:1:\"u\";i:3;s:1:\"m\";}', 1741385911),
('captcha_2b38a3492d0058cb67b302fe0be717c4', 'a:4:{i:0;s:1:\"e\";i:1;s:1:\"m\";i:2;s:1:\"p\";i:3;s:1:\"4\";}', 1741385769),
('captcha_2cb7211e6688a5c946066428b446c5d9', 'a:4:{i:0;s:1:\"t\";i:1;s:1:\"g\";i:2;s:1:\"d\";i:3;s:1:\"e\";}', 1741386138),
('captcha_35d7ab1d46a9cb1629b60c35b7a777e4', 'a:4:{i:0;s:1:\"3\";i:1;s:1:\"u\";i:2;s:1:\"g\";i:3;s:1:\"q\";}', 1741385749),
('captcha_3e33b93067322ccf640f4acffd614ce7', 'a:4:{i:0;s:1:\"t\";i:1;s:1:\"d\";i:2;s:1:\"y\";i:3;s:1:\"t\";}', 1741387209),
('captcha_455b71d3202309bb1947588d93347c77', 'a:4:{i:0;s:1:\"8\";i:1;s:1:\"b\";i:2;s:1:\"c\";i:3;s:1:\"q\";}', 1741386047),
('captcha_8f5d9c42ff59f6fb1ad2aab48716227d', 'a:4:{i:0;s:1:\"c\";i:1;s:1:\"8\";i:2;s:1:\"m\";i:3;s:1:\"8\";}', 1741387063),
('captcha_96950f356e1b768182d5dad837b0eb6d', 'a:4:{i:0;s:1:\"d\";i:1;s:1:\"u\";i:2;s:1:\"j\";i:3;s:1:\"t\";}', 1741385834),
('captcha_970de4a008bbe0c13c19a7eb52709cad', 'a:4:{i:0;s:1:\"t\";i:1;s:1:\"p\";i:2;s:1:\"z\";i:3;s:1:\"x\";}', 1741389190),
('captcha_a872b0e6ac373f367caafba1a9809239', 'a:4:{i:0;s:1:\"e\";i:1;s:1:\"y\";i:2;s:1:\"p\";i:3;s:1:\"f\";}', 1741190719),
('captcha_ba4473de88571ac6fe4d82064c66917a', 'a:4:{i:0;s:1:\"n\";i:1;s:1:\"c\";i:2;s:1:\"j\";i:3;s:1:\"n\";}', 1741385980),
('captcha_c808e80a3cf595a499909c68ae49f3d5', 'a:4:{i:0;s:1:\"j\";i:1;s:1:\"g\";i:2;s:1:\"u\";i:3;s:1:\"u\";}', 1741385584),
('captcha_d5158c4e1f618507e23f4b9899bd280a', 'a:4:{i:0;s:1:\"g\";i:1;s:1:\"h\";i:2;s:1:\"n\";i:3;s:1:\"7\";}', 1741385783),
('captcha_d7b40a5f5fd93236cf6f5a718a62478c', 'a:4:{i:0;s:1:\"p\";i:1;s:1:\"d\";i:2;s:1:\"q\";i:3;s:1:\"x\";}', 1741387063),
('captcha_db9563f9f359c63c24ed838231658647', 'a:4:{i:0;s:1:\"x\";i:1;s:1:\"x\";i:2;s:1:\"m\";i:3;s:1:\"y\";}', 1741386918),
('captcha_e66a38b33ee83d1d03566a31b0c342cf', 'a:4:{i:0;s:1:\"u\";i:1;s:1:\"b\";i:2;s:1:\"n\";i:3;s:1:\"e\";}', 1741385904),
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:32:{i:0;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"role-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:11:\"role-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:9:\"role-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:11:\"role-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"dosen-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"dosen-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:10:\"dosen-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:12:\"dosen-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:18:\"verifikasi pi-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:24:\"verifikasi pi-verifikasi\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:9:\"user-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:11:\"user-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:9:\"user-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:11:\"user-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:16:\"mahasiswa-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:16:\"mahasiswa-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:16;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:14:\"mahasiswa-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:14:\"mahasiswa-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:18;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:16:\"group-verifikasi\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:16:\"lokasi pi-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:14:\"lokasi pi-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:16:\"lokasi pi-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:22;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:14:\"lokasi pi-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:23;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:15:\"pembekalan-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:24;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:17:\"pembekalan-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:25;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:15:\"pembekalan-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:26;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:21:\"pembekalan-verifikasi\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:27;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:17:\"pembekalan-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:28;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:15:\"permission-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:17:\"permission-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:15:\"permission-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:17:\"permission-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"superadmin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}}}', 1741475410);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosens`
--

CREATE TABLE `dosens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `pangkat` varchar(255) DEFAULT NULL,
  `golongan` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `telp` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `prodi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosens`
--

INSERT INTO `dosens` (`id`, `user_id`, `nama`, `nip`, `pangkat`, `golongan`, `jabatan`, `telp`, `photo`, `prodi_id`, `inserted_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Mushaf S.Kom.,M.Kom.', '199400124423212', 'Assisten Ahli', 'IIIB', 'Dosen', '085676555432', NULL, 2, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `fakultas`
--

CREATE TABLE `fakultas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `fakultas` varchar(255) NOT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fakultas`
--

INSERT INTO `fakultas` (`id`, `kode`, `fakultas`, `inserted_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`) VALUES
(1, 'FT', 'Teknik', NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lokasi_pi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pembimbing_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_month` smallint(5) UNSIGNED NOT NULL,
  `end_month` smallint(5) UNSIGNED NOT NULL,
  `year` year(4) NOT NULL,
  `nama_pembimbing_lapangan` varchar(255) DEFAULT NULL,
  `no_pembimbing_lapangan` varchar(255) DEFAULT NULL,
  `no_surat` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `send` enum('N','Y') NOT NULL,
  `admin_verify` enum('N','Y','X') NOT NULL,
  `done` enum('N','Y') NOT NULL,
  `done_catatan` varchar(255) DEFAULT NULL,
  `done_verify` enum('N','Y','X') NOT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_buktis`
--

CREATE TABLE `group_buktis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sertifikat_industri` varchar(255) DEFAULT NULL,
  `laporan_pi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_details`
--

CREATE TABLE `group_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `verify` enum('N','Y') NOT NULL,
  `verify_at` datetime DEFAULT NULL,
  `pembekalan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pembekalan_verify` enum('N','Y') NOT NULL,
  `pembekalan_verify_at` datetime DEFAULT NULL,
  `pembekalan_verify_by` bigint(20) UNSIGNED DEFAULT NULL,
  `pembekalan_sertifikat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_dokumens`
--

CREATE TABLE `group_dokumens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jenis_dokumen` varchar(255) DEFAULT NULL,
  `nomor` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_lokasis`
--

CREATE TABLE `group_lokasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_lokasi` varchar(255) DEFAULT NULL,
  `deskripsi` longtext DEFAULT NULL,
  `kompetensi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_dokumens`
--

CREATE TABLE `jenis_dokumens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `jenis_dokumen` varchar(255) NOT NULL,
  `starting_number` int(11) NOT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_dokumens`
--

INSERT INTO `jenis_dokumens` (`id`, `kode`, `jenis_dokumen`, `starting_number`, `inserted_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`) VALUES
(1, 'IOB', 'izin observersi', 2122, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(2, 'IPI', 'izin pi', 2122, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(3, 'PPB', 'permohonan pembimbing', 2122, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `jurusans`
--

CREATE TABLE `jurusans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `jurusan` varchar(255) NOT NULL,
  `pengelola_nama` varchar(255) DEFAULT NULL,
  `pengelola_nip` varchar(255) DEFAULT NULL,
  `kajur_nama` varchar(255) DEFAULT NULL,
  `kajur_nip` varchar(255) DEFAULT NULL,
  `sekjur_nama` varchar(255) DEFAULT NULL,
  `sekjur_nip` varchar(255) DEFAULT NULL,
  `kepala_lab_nama` varchar(255) DEFAULT NULL,
  `kepala_lab_nip` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telp` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `hp` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `kota` varchar(255) DEFAULT NULL,
  `fakultas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jurusans`
--

INSERT INTO `jurusans` (`id`, `kode`, `jurusan`, `pengelola_nama`, `pengelola_nip`, `kajur_nama`, `kajur_nip`, `sekjur_nama`, `sekjur_nip`, `kepala_lab_nama`, `kepala_lab_nip`, `alamat`, `telp`, `fax`, `hp`, `email`, `website`, `kota`, `fakultas_id`, `inserted_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`) VALUES
(1, 'JTIK', 'Teknik Informatika dan Komputer', 'Shabrina Syntha Dewi, S.Pd., M.Pd', '199310052019032026', 'Dr. Ir. Abdul Muis Mappalotteng, S.Pd., M.Pd., M.T., IPM.', '196910181994031001', 'Dr. Sanatang., S.Pd., M.T', '197520072010122001', 'Dr. Eng. Jumadi M Parenreng, M.Kom', '19781103 201012 1 002', 'Jl. Daeng Tata Raya Parang Tambung Makassar - 90224', '0411-864935', '0411-861507', '0853-1122-4040', 'jtik@unm.ac.id', 'tik.ft.unm.ac.id', 'Makassar', 1, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `logbooks`
--

CREATE TABLE `logbooks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kegiatan` text NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `verify` enum('N','Y') NOT NULL,
  `verify_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_pis`
--

CREATE TABLE `lokasi_pis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lokasi_pi` varchar(255) NOT NULL,
  `kota` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telp` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `kompetensi` text DEFAULT NULL,
  `deskripsi` longtext DEFAULT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lokasi_pis`
--

INSERT INTO `lokasi_pis` (`id`, `lokasi_pi`, `kota`, `alamat`, `telp`, `keterangan`, `kompetensi`, `deskripsi`, `inserted_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`) VALUES
(1, 'PT. Pertamina', 'Makassar', 'Jl. AP. Pettarani', '08565555455', NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(2, 'PT. Kumala Motor Sejahtera', 'Makassar', 'Jl. Kumala', '0411093929', NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(3, 'PT. Mencari Cinta Sejati', 'Makassar', 'Jl. Jalan Poros', '0411123442', NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswas`
--

CREATE TABLE `mahasiswas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `kelas` varchar(255) DEFAULT NULL,
  `telp` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `prodi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswas`
--

INSERT INTO `mahasiswas` (`id`, `user_id`, `nama`, `nim`, `kelas`, `telp`, `photo`, `prodi_id`, `inserted_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`) VALUES
(1, 3, 'Alimuddin', '20240201', 'TEKOM A', '085212555432', NULL, 2, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(2, 4, 'Harlina', '20240202', 'TEKOM A', '08544235432', NULL, 2, NULL, NULL, NULL, '2025-03-05 08:04:15', '2025-03-05 08:04:15'),
(3, 5, 'Basuki', '20240203', 'TEKOM A', '08534325432', NULL, 2, NULL, NULL, NULL, '2025-03-05 08:04:15', '2025-03-05 08:04:15'),
(4, 6, 'Jajang', '20240204', 'TEKOM A', '08521253232', NULL, 2, NULL, NULL, NULL, '2025-03-05 08:04:15', '2025-03-05 08:04:15');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_admins_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2024_08_01_114401_create_settings_table', 1),
(6, '2024_10_19_155814_create_fakultas_table', 1),
(7, '2024_10_19_155815_create_jurusans_table', 1),
(8, '2024_10_19_155816_create_jenis_dokumens_table', 1),
(9, '2024_10_19_155823_create_prodis_table', 1),
(10, '2024_10_19_160909_create_mahasiswas_table', 1),
(11, '2024_10_19_160919_create_dosens_table', 1),
(12, '2024_10_20_111806_create_lokasi_pis_table', 1),
(13, '2024_10_25_094702_create_groups_table', 1),
(14, '2024_10_25_094703_create_pembekalans_table', 1),
(15, '2024_10_25_094708_create_group_details_table', 1),
(16, '2024_11_15_064959_create_logbooks_table', 1),
(17, '2024_11_16_090856_create_group_dokumen_table', 1),
(18, '2024_11_16_090857_create_group_lokasis_table', 1),
(19, '2024_11_16_090858_create_group_bukti_table', 1),
(20, '2025_01_05_101702_create_permission_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembekalans`
--

CREATE TABLE `pembekalans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(3, 'role-list', 'web', '2025-01-09 01:42:45', '2025-01-09 01:42:45'),
(4, 'role-create', 'web', '2025-01-09 01:42:53', '2025-01-09 01:42:53'),
(5, 'role-edit', 'web', '2025-01-09 01:43:21', '2025-01-09 01:43:21'),
(6, 'role-delete', 'web', '2025-01-09 01:43:28', '2025-01-09 01:43:28'),
(7, 'dosen-list', 'web', '2025-01-09 01:46:46', '2025-01-09 01:46:46'),
(8, 'dosen-create', 'web', '2025-01-09 01:46:56', '2025-01-09 01:46:56'),
(9, 'dosen-edit', 'web', '2025-01-09 01:47:04', '2025-01-09 01:47:04'),
(10, 'dosen-delete', 'web', '2025-01-09 01:47:17', '2025-01-09 01:47:17'),
(11, 'verifikasi pi-list', 'web', '2025-01-09 02:00:11', '2025-01-09 02:00:11'),
(12, 'verifikasi pi-verifikasi', 'web', '2025-01-09 02:00:26', '2025-01-09 02:00:26'),
(13, 'user-list', 'web', '2025-01-09 06:58:58', '2025-01-09 06:58:58'),
(14, 'user-create', 'web', '2025-01-09 06:59:04', '2025-01-09 06:59:04'),
(15, 'user-edit', 'web', '2025-01-09 06:59:10', '2025-01-09 06:59:10'),
(16, 'user-delete', 'web', '2025-01-09 06:59:19', '2025-01-09 06:59:19'),
(17, 'mahasiswa-delete', 'web', '2025-03-07 16:03:18', '2025-03-07 16:03:18'),
(18, 'mahasiswa-create', 'web', '2025-03-07 16:03:27', '2025-03-07 16:03:27'),
(19, 'mahasiswa-edit', 'web', '2025-03-07 16:03:36', '2025-03-07 16:03:36'),
(20, 'mahasiswa-list', 'web', '2025-03-07 16:03:46', '2025-03-07 16:03:46'),
(21, 'group-verifikasi', 'web', '2025-03-07 16:05:06', '2025-03-07 16:05:06'),
(22, 'lokasi pi-create', 'web', '2025-03-07 16:05:37', '2025-03-07 16:05:37'),
(23, 'lokasi pi-edit', 'web', '2025-03-07 16:05:46', '2025-03-07 16:05:46'),
(24, 'lokasi pi-delete', 'web', '2025-03-07 16:05:54', '2025-03-07 16:05:54'),
(25, 'lokasi pi-list', 'web', '2025-03-07 16:06:03', '2025-03-07 16:06:03'),
(26, 'pembekalan-list', 'web', '2025-03-07 16:06:47', '2025-03-07 16:06:47'),
(27, 'pembekalan-create', 'web', '2025-03-07 16:06:54', '2025-03-07 16:06:54'),
(28, 'pembekalan-edit', 'web', '2025-03-07 16:07:01', '2025-03-07 16:07:01'),
(29, 'pembekalan-verifikasi', 'web', '2025-03-07 16:07:08', '2025-03-07 16:07:08'),
(30, 'pembekalan-delete', 'web', '2025-03-07 16:07:14', '2025-03-07 16:07:14'),
(31, 'permission-list', 'web', '2025-03-07 16:07:48', '2025-03-07 16:07:48'),
(32, 'permission-create', 'web', '2025-03-07 16:07:59', '2025-03-07 16:07:59'),
(33, 'permission-edit', 'web', '2025-03-07 16:08:06', '2025-03-07 16:08:06'),
(34, 'permission-delete', 'web', '2025-03-07 16:08:13', '2025-03-07 16:08:13');

-- --------------------------------------------------------

--
-- Table structure for table `prodis`
--

CREATE TABLE `prodis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `prodi` varchar(255) NOT NULL,
  `kaprodi_nama` varchar(255) DEFAULT NULL,
  `kaprodi_nip` varchar(255) DEFAULT NULL,
  `jurusan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prodis`
--

INSERT INTO `prodis` (`id`, `kode`, `prodi`, `kaprodi_nama`, `kaprodi_nip`, `jurusan_id`, `inserted_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`) VALUES
(1, 'PTIK', 'Pendidikan Teknik Informatika', 'Fathahillah, S.Pd, M.Eng.', '198603262015041001', 1, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(2, 'TEKOM', 'Teknik Komputer', 'Dr. Satria Gunawan Zain, S.PD., M.T.', '198008092010121002', 1, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'web', '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(2, 'admin', 'web', '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(3, 'mahasiswa', 'web', '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(4, 'dosen', 'web', '2025-03-05 08:04:14', '2025-03-05 08:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(17, 1),
(17, 2),
(18, 1),
(18, 2),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(25, 1),
(25, 2),
(26, 1),
(26, 2),
(27, 1),
(27, 2),
(28, 1),
(28, 2),
(29, 1),
(29, 2),
(30, 1),
(30, 2),
(31, 1),
(32, 1),
(33, 1),
(34, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2JVHncaxYQsuEHbzTmIyvadGaNQ6pBpKD8djuHVS', NULL, '103.147.154.56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS0NaQVdadTVoWFZSTmN0RjhLdzM0WlhWWTBPMmlEbGVWWUYzWnBFZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTc6Imh0dHBzOi8vcGkubXVqYXlhdGVjaG5vbG9neS5jb20vY2FwdGNoYS9kZWZhdWx0P1dBRVpMd1JEPSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NzoiY2FwdGNoYSI7YTozOntzOjk6InNlbnNpdGl2ZSI7YjowO3M6Mzoia2V5IjtzOjYwOiIkMnkkMTIkSWhoZ05NWHhPNEVZTDhzd1JKTy9tTzYyTHQ3ejFZOWRGT2xKb3lLUzR1T1gvSnl2OWcvLzYiO3M6NzoiZW5jcnlwdCI7YjowO319', 1741385524),
('ypHMcLAjJJ4KbvUfQfQjsJT9MwVBPj6XRTHsUmWp', NULL, '2404:c0:4760::10b1:fe61', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1NzoiaHR0cHM6Ly9waS5tdWpheWF0ZWNobm9sb2d5LmNvbS9jYXB0Y2hhL2RlZmF1bHQ/YnVQWlpEQ3E9Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo2OiJfdG9rZW4iO3M6NDA6IkcwTzhoOHRkN3FydDVJM0FNWHVhQUdIVjhBNDhhb21GUkc4RExMbGQiO3M6NzoiY2FwdGNoYSI7YTozOntzOjk6InNlbnNpdGl2ZSI7YjowO3M6Mzoia2V5IjtzOjYwOiIkMnkkMTIkd2NyWHoub3ZlZ1Y3RFhrNGxXWlVBLi9nSGQwMlFDeGV3U29WQnZubjBQam05L0NISXE4UHUiO3M6NzoiZW5jcnlwdCI7YjowO319', 1741389130);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` varchar(255) NOT NULL,
  `nama_web` varchar(255) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `maps` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `wa` varchar(255) DEFAULT NULL,
  `telp` varchar(255) DEFAULT NULL,
  `tax` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `nama_web`, `deskripsi`, `maps`, `alamat`, `email`, `wa`, `telp`, `tax`, `logo`, `favicon`, `created_at`, `updated_at`) VALUES
('setting', 'My Company', NULL, NULL, 'Jl. Jalan', 'hi@company.com', '085875342122', '085423444567', NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `aktif` enum('Y','N') NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `inserted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `type`, `role`, `email`, `email_verified_at`, `username`, `password`, `aktif`, `remember_token`, `inserted_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`) VALUES
(1, 'admins', 'superadmin', 'mushafdev@gmail.com', NULL, 'superadmin', '$2y$12$3j6H5caDnVfm7Zpuhv3Kpu2dEzXnz118Sm.Gq0EOetgo9DPV08tAe', 'Y', NULL, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(2, 'admins', 'admin', 'leonardo@gmail.com', NULL, 'admin123', '$2y$12$.YAa4ZGgcvzvMTz3VjgYhuB8hU0AYZ0koQhvN84R7kuuNTomomu26', 'Y', NULL, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(3, 'mahasiswas', 'mahasiswa', 'alimuddin@gmail.com', NULL, 'mhs001', '$2y$12$wRiGZeeC3Uy24m2t6wNXueg7B7OubxW0JynbfohGKlmwhfYxIfrFG', 'Y', NULL, NULL, NULL, NULL, '2025-03-05 08:04:14', '2025-03-05 08:04:14'),
(4, 'mahasiswas', 'mahasiswa', 'herlina@gmail.com', NULL, 'mhs002', '$2y$12$2Hi0uMvMADgVHRT4tkGU0uTgf3nJWa91fZigfJuGC461h2Fo6UF/m', 'Y', NULL, NULL, NULL, NULL, '2025-03-05 08:04:15', '2025-03-05 08:04:15'),
(5, 'mahasiswas', 'mahasiswa', 'basuki@gmail.com', NULL, 'mhs003', '$2y$12$lfKrA2a6B5adVPbze5c/neUJfdJp58k8Kxlo6ph4ElTJlVXD2Xt72', 'Y', NULL, NULL, NULL, NULL, '2025-03-05 08:04:15', '2025-03-05 08:04:15'),
(6, 'mahasiswas', 'mahasiswa', 'jajang@gmail.com', NULL, 'mhs004', '$2y$12$Nm3u1Q7zUbMM/Kl76BiaV.POUjTklxg3b9AIRybjQAbrbZYPOhN6C', 'Y', NULL, NULL, NULL, NULL, '2025-03-05 08:04:15', '2025-03-05 08:04:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admins_user_id_foreign` (`user_id`),
  ADD KEY `admins_inserted_by_foreign` (`inserted_by`),
  ADD KEY `admins_updated_by_foreign` (`updated_by`),
  ADD KEY `admins_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `dosens`
--
ALTER TABLE `dosens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dosens_user_id_foreign` (`user_id`),
  ADD KEY `dosens_prodi_id_foreign` (`prodi_id`),
  ADD KEY `dosens_inserted_by_foreign` (`inserted_by`),
  ADD KEY `dosens_updated_by_foreign` (`updated_by`),
  ADD KEY `dosens_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fakultas_inserted_by_foreign` (`inserted_by`),
  ADD KEY `fakultas_updated_by_foreign` (`updated_by`),
  ADD KEY `fakultas_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `groups_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `groups_lokasi_pi_id_foreign` (`lokasi_pi_id`),
  ADD KEY `groups_pembimbing_id_foreign` (`pembimbing_id`),
  ADD KEY `groups_inserted_by_foreign` (`inserted_by`),
  ADD KEY `groups_updated_by_foreign` (`updated_by`),
  ADD KEY `groups_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `group_buktis`
--
ALTER TABLE `group_buktis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_buktis_group_id_foreign` (`group_id`),
  ADD KEY `group_buktis_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `group_buktis_inserted_by_foreign` (`inserted_by`),
  ADD KEY `group_buktis_updated_by_foreign` (`updated_by`),
  ADD KEY `group_buktis_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `group_details`
--
ALTER TABLE `group_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_details_group_id_foreign` (`group_id`),
  ADD KEY `group_details_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `group_details_pembekalan_id_foreign` (`pembekalan_id`),
  ADD KEY `group_details_pembekalan_verify_by_foreign` (`pembekalan_verify_by`);

--
-- Indexes for table `group_dokumens`
--
ALTER TABLE `group_dokumens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_dokumens_group_id_foreign` (`group_id`),
  ADD KEY `group_dokumens_inserted_by_foreign` (`inserted_by`),
  ADD KEY `group_dokumens_updated_by_foreign` (`updated_by`),
  ADD KEY `group_dokumens_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `group_lokasis`
--
ALTER TABLE `group_lokasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_lokasis_group_id_foreign` (`group_id`),
  ADD KEY `group_lokasis_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `group_lokasis_inserted_by_foreign` (`inserted_by`),
  ADD KEY `group_lokasis_updated_by_foreign` (`updated_by`),
  ADD KEY `group_lokasis_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `jenis_dokumens`
--
ALTER TABLE `jenis_dokumens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jenis_dokumens_inserted_by_foreign` (`inserted_by`),
  ADD KEY `jenis_dokumens_updated_by_foreign` (`updated_by`),
  ADD KEY `jenis_dokumens_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurusans`
--
ALTER TABLE `jurusans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurusans_fakultas_id_foreign` (`fakultas_id`),
  ADD KEY `jurusans_inserted_by_foreign` (`inserted_by`),
  ADD KEY `jurusans_updated_by_foreign` (`updated_by`),
  ADD KEY `jurusans_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `logbooks`
--
ALTER TABLE `logbooks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logbooks_group_id_foreign` (`group_id`),
  ADD KEY `logbooks_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `logbooks_inserted_by_foreign` (`inserted_by`),
  ADD KEY `logbooks_updated_by_foreign` (`updated_by`),
  ADD KEY `logbooks_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `lokasi_pis`
--
ALTER TABLE `lokasi_pis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lokasi_pis_inserted_by_foreign` (`inserted_by`),
  ADD KEY `lokasi_pis_updated_by_foreign` (`updated_by`),
  ADD KEY `lokasi_pis_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswas_user_id_foreign` (`user_id`),
  ADD KEY `mahasiswas_prodi_id_foreign` (`prodi_id`),
  ADD KEY `mahasiswas_inserted_by_foreign` (`inserted_by`),
  ADD KEY `mahasiswas_updated_by_foreign` (`updated_by`),
  ADD KEY `mahasiswas_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembekalans`
--
ALTER TABLE `pembekalans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembekalans_inserted_by_foreign` (`inserted_by`),
  ADD KEY `pembekalans_updated_by_foreign` (`updated_by`),
  ADD KEY `pembekalans_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `prodis`
--
ALTER TABLE `prodis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prodis_jurusan_id_foreign` (`jurusan_id`),
  ADD KEY `prodis_inserted_by_foreign` (`inserted_by`),
  ADD KEY `prodis_updated_by_foreign` (`updated_by`),
  ADD KEY `prodis_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_inserted_by_foreign` (`inserted_by`),
  ADD KEY `users_updated_by_foreign` (`updated_by`),
  ADD KEY `users_deleted_by_foreign` (`deleted_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dosens`
--
ALTER TABLE `dosens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_buktis`
--
ALTER TABLE `group_buktis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_details`
--
ALTER TABLE `group_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_dokumens`
--
ALTER TABLE `group_dokumens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_lokasis`
--
ALTER TABLE `group_lokasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_dokumens`
--
ALTER TABLE `jenis_dokumens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurusans`
--
ALTER TABLE `jurusans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logbooks`
--
ALTER TABLE `logbooks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lokasi_pis`
--
ALTER TABLE `lokasi_pis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pembekalans`
--
ALTER TABLE `pembekalans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `prodis`
--
ALTER TABLE `prodis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admins_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admins_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dosens`
--
ALTER TABLE `dosens`
  ADD CONSTRAINT `dosens_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `dosens_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `dosens_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodis` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `dosens_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `dosens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fakultas`
--
ALTER TABLE `fakultas`
  ADD CONSTRAINT `fakultas_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fakultas_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fakultas_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_lokasi_pi_id_foreign` FOREIGN KEY (`lokasi_pi_id`) REFERENCES `lokasi_pis` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_pembimbing_id_foreign` FOREIGN KEY (`pembimbing_id`) REFERENCES `dosens` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `group_buktis`
--
ALTER TABLE `group_buktis`
  ADD CONSTRAINT `group_buktis_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_buktis_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_buktis_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_buktis_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_buktis_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `group_details`
--
ALTER TABLE `group_details`
  ADD CONSTRAINT `group_details_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_details_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_details_pembekalan_id_foreign` FOREIGN KEY (`pembekalan_id`) REFERENCES `pembekalans` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_details_pembekalan_verify_by_foreign` FOREIGN KEY (`pembekalan_verify_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `group_dokumens`
--
ALTER TABLE `group_dokumens`
  ADD CONSTRAINT `group_dokumens_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_dokumens_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_dokumens_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_dokumens_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `group_lokasis`
--
ALTER TABLE `group_lokasis`
  ADD CONSTRAINT `group_lokasis_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_lokasis_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_lokasis_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_lokasis_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `group_lokasis_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `jenis_dokumens`
--
ALTER TABLE `jenis_dokumens`
  ADD CONSTRAINT `jenis_dokumens_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jenis_dokumens_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jenis_dokumens_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `jurusans`
--
ALTER TABLE `jurusans`
  ADD CONSTRAINT `jurusans_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jurusans_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jurusans_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jurusans_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `logbooks`
--
ALTER TABLE `logbooks`
  ADD CONSTRAINT `logbooks_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `logbooks_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `logbooks_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `logbooks_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `logbooks_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `lokasi_pis`
--
ALTER TABLE `lokasi_pis`
  ADD CONSTRAINT `lokasi_pis_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `lokasi_pis_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `lokasi_pis_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  ADD CONSTRAINT `mahasiswas_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `mahasiswas_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `mahasiswas_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodis` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `mahasiswas_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `mahasiswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembekalans`
--
ALTER TABLE `pembekalans`
  ADD CONSTRAINT `pembekalans_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pembekalans_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pembekalans_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `prodis`
--
ALTER TABLE `prodis`
  ADD CONSTRAINT `prodis_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `prodis_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `prodis_jurusan_id_foreign` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusans` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `prodis_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_inserted_by_foreign` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
