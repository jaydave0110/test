-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2020 at 02:05 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_11_20_125605_create_permission_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(6, 'App\\Models\\User', 3),
(6, 'App\\Models\\User', 4),
(6, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'role-list', 'web', '2020-11-20 08:58:01', '2020-11-20 08:58:01'),
(2, 'role-create', 'web', '2020-11-20 08:58:01', '2020-11-20 08:58:01'),
(3, 'role-edit', 'web', '2020-11-20 08:58:01', '2020-11-20 08:58:01'),
(4, 'role-delete', 'web', '2020-11-20 08:58:02', '2020-11-20 08:58:02'),
(5, 'user-list', 'web', '2020-11-20 08:58:02', '2020-11-20 08:58:02'),
(6, 'user-create', 'web', '2020-11-20 08:58:02', '2020-11-20 08:58:02'),
(7, 'user-edit', 'web', '2020-11-20 08:58:02', '2020-11-20 08:58:02'),
(8, 'user-delete', 'web', '2020-11-20 08:58:02', '2020-11-20 08:58:02');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2020-11-20 07:57:28', '2020-11-20 07:57:28'),
(2, 'subadmin', 'web', '2020-11-20 07:59:04', '2020-11-20 07:59:04'),
(3, 'manager', 'web', '2020-11-20 07:59:15', '2020-11-20 07:59:15'),
(4, 'cityhead', 'web', '2020-11-20 07:59:31', '2020-11-20 07:59:31'),
(5, 'statehead', 'web', '2020-11-20 07:59:38', '2020-11-20 07:59:38'),
(6, 'broker', 'web', '2020-11-20 07:59:38', '2020-11-20 07:59:38');

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
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(8, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_areas`
--

CREATE TABLE `tbl_areas` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `slug` varchar(50) NOT NULL DEFAULT '',
  `city_id` int(11) UNSIGNED NOT NULL,
  `state_id` int(11) UNSIGNED NOT NULL,
  `country_id` int(11) UNSIGNED NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_areas`
--

INSERT INTO `tbl_areas` (`id`, `name`, `slug`, `city_id`, `state_id`, `country_id`, `is_deleted`, `created_at`, `updated_at`) VALUES
(107, 'Subhanpura', 'subhanpura', 1, 5, 1, 0, '2015-02-20 04:32:35', '2015-02-24 00:28:48'),
(116, 'Raopura', 'raopura', 1, 5, 1, 0, '2015-02-22 22:57:27', '2017-03-22 01:04:21'),
(138, 'Dabhoi Road', 'dabhoi_road', 1, 5, 1, 1, '2015-06-05 00:55:02', '2015-06-05 00:55:02'),
(143, 'Vasna  Bhayli', 'vasna_bhayli', 1, 5, 1, 0, '2015-06-09 06:42:31', '2015-08-28 03:33:06'),
(144, 'Jambuva Crossing', 'jambuva_crossing', 1, 5, 1, 1, '2015-06-10 04:16:45', '2015-06-10 04:16:45'),
(147, 'Ajwa Waghodia Road', 'ajwa_waghodia_road', 1, 5, 1, 0, '2015-06-13 02:53:09', '2015-06-13 02:57:25'),
(148, 'Sama Savli', 'sama_savli_road', 1, 5, 1, 0, '2015-06-15 04:24:17', '2015-09-12 06:16:32'),
(150, 'Chhani', 'GEB Substation Chhani', 1, 5, 1, 0, '2015-06-17 04:10:55', '2015-06-17 04:15:41'),
(151, 'Pratap Nagar', 'Opp Gita Mandir', 1, 5, 1, 0, '2015-06-18 23:32:55', '2015-06-18 23:32:55'),
(152, 'Fatehgunj', 'fatehgunj', 1, 5, 1, 0, '2015-06-19 00:43:30', '2015-06-19 00:43:30'),
(154, 'Gendigate', 'Santkabir', 1, 5, 1, 0, '2015-06-19 02:44:49', '2015-06-19 02:44:49'),
(155, 'Race Course', 'Opp. Pashabhai Park , Nr. Citi Bank', 1, 5, 1, 0, '2015-06-19 23:22:28', '2015-06-19 23:26:09'),
(156, 'Alkapuri', 'BPC Road', 1, 5, 1, 0, '2015-06-20 00:03:07', '2015-06-20 00:05:40'),
(157, 'Manjalpur', 'manjalpur', 1, 5, 1, 0, '2015-06-20 00:28:22', '2015-06-20 00:30:32'),
(158, 'O P Road', 'o_p_road', 1, 5, 1, 0, '2015-06-20 01:12:39', '2015-06-20 01:12:39'),
(159, 'Akota', 'akota', 1, 5, 1, 0, '2015-06-20 01:29:08', '2015-06-20 01:29:08'),
(160, 'Ellorapark', 'Subhanpura Road', 1, 5, 1, 1, '2015-06-20 05:43:10', '2015-06-20 05:43:10'),
(161, 'Jambuva', 'jambuva', 1, 5, 1, 0, '2015-06-22 03:54:00', '2015-06-22 03:54:00'),
(162, 'Karelibaug', 'Nr. Kamlanagar Lake', 1, 5, 1, 0, '2015-06-22 04:43:24', '2015-09-07 04:54:02'),
(164, 'Tarsali', 'Nr. bypass, N.H.No.-8,Tarsali', 1, 5, 1, 0, '2015-06-24 01:07:25', '2015-06-24 01:07:25'),
(165, 'Padra Road', 'padra_road', 1, 5, 1, 0, '2015-06-24 02:34:14', '2015-06-24 02:34:14'),
(166, 'Harni', 'harni', 1, 5, 1, 0, '2015-06-25 06:09:55', '2015-06-25 06:09:55'),
(168, 'Dumad', 'dumad', 1, 5, 1, 0, '2015-06-26 03:52:50', '2015-06-26 03:52:50'),
(169, 'SunPharma Road', 'sun_pratham_road', 1, 5, 1, 0, '2015-06-26 05:24:43', '2015-07-02 06:03:18'),
(172, 'Dabhoi waghodia Ring  Road', 'dabhoi_waghodia_road', 1, 5, 1, 0, '2015-06-27 04:15:07', '2015-06-27 04:15:23'),
(173, 'Waghodiya', 'Waghodiya ', 1, 5, 1, 1, '2015-06-28 23:37:52', '2015-09-07 04:34:18'),
(174, 'Bhayli', 'bhayali', 1, 5, 1, 1, '2015-06-30 00:11:45', '2015-08-28 04:42:32'),
(175, 'Ajwa Road', 'ajwa_road', 1, 5, 1, 1, '2015-07-01 04:44:42', '2015-07-01 04:44:42'),
(176, 'Jetalpur road', 'jetalpur_road', 1, 5, 1, 0, '2015-07-08 01:04:44', '2015-07-08 01:04:44'),
(177, 'Bill', 'bill', 1, 5, 1, 1, '2015-07-08 03:06:29', '2015-07-08 03:06:29'),
(178, 'Kalali', 'kalali', 1, 5, 1, 1, '2015-07-08 04:03:57', '2015-07-08 04:03:57'),
(180, 'Gotri Sevasi', 'gotri_sevasi', 1, 5, 1, 0, '2015-07-09 01:29:50', '2015-07-09 01:29:50'),
(181, 'Vemali', 'vemali', 1, 5, 1, 0, '2015-07-10 03:56:38', '2015-07-10 03:56:38'),
(182, 'Makarpura', 'makarpura', 1, 5, 1, 0, '2015-07-13 06:54:31', '2015-07-13 06:54:31'),
(183, 'Talsat', 'Kalali Talsat Road', 1, 5, 1, 1, '2015-07-29 00:21:36', '2015-07-29 00:22:06'),
(187, 'Dandia Bazar Main Road', 'Vasna', 1, 5, 1, 0, '2015-08-10 04:33:30', '2015-08-10 04:33:30'),
(190, 'Atladra Bill', 'atladra_bill_road', 1, 5, 1, 0, '2015-08-14 00:41:47', '2015-08-28 04:59:25'),
(191, 'Vadsar', 'vadsar', 1, 5, 1, 0, '2015-08-20 03:21:05', '2015-08-20 03:21:05'),
(192, 'Nizampura', 'Nizampura', 1, 5, 1, 0, '2015-08-22 01:23:51', '2015-12-07 14:39:37'),
(194, 'Laheripura', 'laheripura', 1, 5, 1, 0, '2015-08-22 02:46:33', '2015-08-22 02:46:33'),
(195, 'M G Road', 'm_g_road', 1, 5, 1, 0, '2015-08-22 03:02:33', '2015-08-22 03:02:33'),
(196, 'Gorwa', 'gorwa', 1, 5, 1, 0, '2015-08-22 03:15:25', '2015-08-22 03:15:25'),
(197, 'Nava Bazar', 'nava_bazar', 1, 5, 1, 0, '2015-08-22 03:26:08', '2015-08-22 03:26:08'),
(198, 'Vadodara Halol Highway', 'vadodara_halol', 1, 5, 1, 0, '2015-08-24 07:03:25', '2015-08-24 07:03:51'),
(199, 'Vadsar Ring Road', 'vadsar_ring_road', 1, 5, 1, 1, '2015-08-25 07:46:34', '2015-08-25 07:46:34'),
(200, 'Sayajigunj', 'sayajigunj', 1, 5, 1, 0, '2015-08-25 08:22:03', '2015-08-25 08:22:03'),
(201, 'Productivity Road', 'productivity_road', 1, 5, 1, 0, '2015-08-25 23:10:22', '2015-08-25 23:10:22'),
(203, 'Undera Koyali', 'Undera Koyali Main Road', 1, 5, 1, 0, '2015-08-26 07:17:22', '2015-09-12 03:23:53'),
(204, 'Vadiwadi', 'Vikram Sarabhai Marg', 1, 5, 1, 0, '2015-08-27 02:52:15', '2015-08-27 02:52:15'),
(205, 'Samta', 'samta', 1, 5, 1, 0, '2015-09-04 03:25:19', '2015-09-04 03:25:19'),
(207, 'V.I.P Road', 'v_i_p_road', 1, 5, 1, 0, '2015-09-20 01:42:20', '2015-09-20 01:42:20'),
(208, 'Chokhandi', 'chokhandi', 1, 5, 1, 0, '2015-10-16 05:42:28', '2015-10-16 05:42:28'),
(209, 'Diwalipura', 'diwalipura', 1, 5, 1, 0, '2015-10-16 05:48:02', '2015-10-16 05:48:02'),
(210, 'Bajwa', 'bajwa', 1, 5, 1, 0, '2015-10-18 01:28:03', '2015-10-18 01:28:03'),
(211, 'Tandalja', 'tandalja', 1, 5, 1, 0, '2015-10-18 01:28:31', '2015-10-18 01:28:31'),
(212, 'sayajipura', 'sayajipura', 1, 5, 1, 0, '2015-10-18 02:01:05', '2015-10-18 02:01:05'),
(213, 'Darjipura', 'darjipura', 1, 5, 1, 0, '2015-10-18 03:17:47', '2015-10-18 03:17:47'),
(214, 'Mujmahuda', 'mujmahuda', 1, 5, 1, 0, '2015-11-16 00:47:12', '2015-11-16 00:47:12'),
(215, 'Rajmahal Road', 'rajmahal_road', 1, 5, 1, 0, '2015-12-07 14:56:33', '2015-12-07 14:56:33'),
(216, 'kothi char rasta', 'kothi_char_rasta', 1, 5, 1, 0, '2015-12-07 19:35:57', '2015-12-07 19:35:57'),
(217, 'Khanderaiao Market', 'khanderaiao_market', 1, 5, 1, 0, '2015-12-11 13:11:33', '2015-12-11 13:11:33'),
(218, 'Bajwada', 'bajwada', 1, 5, 1, 0, '2015-12-12 13:22:36', '2015-12-12 13:22:36'),
(220, 'Lakkadpitha Road', 'lakkadpitha_road', 1, 5, 1, 0, '2015-12-12 15:33:00', '2015-12-12 15:33:00'),
(221, 'Mandvi', 'mandvi', 1, 5, 1, 0, '2015-12-12 17:03:09', '2015-12-12 17:03:09'),
(222, 'Laxmipura', 'laxmipura', 1, 5, 1, 0, '2015-12-18 17:43:35', '2015-12-18 17:43:35'),
(223, 'Baranpura', 'baranpura', 1, 5, 1, 0, '2015-12-19 13:29:37', '2015-12-19 13:29:37'),
(224, 'Genda Circle', 'genda_circle', 1, 5, 1, 0, '2015-12-19 15:03:56', '2015-12-19 15:03:56'),
(225, 'New Alkapuri', 'new_alkapuri', 1, 5, 1, 0, '2015-12-19 18:44:33', '2015-12-19 18:44:33'),
(226, 'Mangal Bazaar', 'mangal_bazaar', 1, 5, 1, 0, '2016-01-02 14:30:05', '2016-01-02 14:30:05'),
(227, 'R V Desai', 'r_v_desai', 1, 5, 1, 0, '2016-01-09 13:25:55', '2016-01-09 13:25:55'),
(228, 'Wadi', 'wadi', 1, 5, 1, 0, '2016-01-18 18:44:37', '2016-01-18 18:44:37'),
(229, 'Dashrath', 'dashrath', 1, 5, 1, 0, '2016-01-29 13:05:55', '2016-01-29 13:05:55'),
(230, 'Jarod', 'jarod', 1, 5, 1, 0, '2016-02-14 19:52:35', '2016-02-14 19:52:35'),
(231, 'Manjusar', 'manjusar', 1, 5, 1, 0, '2016-02-14 19:53:01', '2016-02-14 19:53:01'),
(232, 'New VIP', 'new_vip', 1, 5, 1, 0, '2016-02-27 19:16:00', '2016-02-27 19:16:00'),
(233, 'New Karelibaug', 'new_karelibaug', 1, 5, 1, 0, '2016-03-12 19:32:46', '2016-03-12 19:32:46'),
(234, 'sama', 'sama', 1, 5, 1, 0, '2016-03-21 20:26:23', '2016-03-21 20:26:23'),
(236, 'Vasna', 'vasna', 1, 5, 1, 1, '2016-05-12 19:17:30', '2016-05-12 19:17:30'),
(237, 'Pavagadh  Jambughoda Road', 'pavagadh jambughoda road', 1, 5, 1, 0, '2016-05-19 05:34:19', '2016-05-19 05:34:19'),
(238, 'Madanjhapa', 'madanjhapa', 1, 5, 1, 0, '2016-05-21 05:56:27', '2016-05-21 05:56:36'),
(239, 'Nandesari', 'nandesari', 1, 5, 1, 0, '2016-05-21 06:44:30', '2016-05-21 06:44:30'),
(240, 'Navapura', 'navapura', 1, 5, 1, 0, '2016-05-27 07:57:22', '2016-05-27 07:57:22'),
(241, 'Warasia', 'warasia', 1, 5, 1, 0, '2016-06-13 07:17:37', '2016-06-13 07:17:37'),
(242, 'Harinagar', 'harinagar', 1, 5, 1, 0, '2016-06-20 02:22:42', '2016-06-20 02:22:42'),
(243, 'New Waghodia', 'new_waghodia', 1, 5, 1, 1, '2016-06-28 06:10:51', '2016-06-28 06:10:51'),
(244, 'Bapu Nagar', 'bapu_nagar', 18, 5, 1, 0, '2016-07-12 00:00:25', '2016-07-12 00:00:25'),
(245, 'Prahlad Nagar', 'prahlad_nagar', 18, 5, 1, 0, '2016-07-12 00:10:06', '2016-07-12 00:10:06'),
(246, 'C.G. Road', 'c_g_road', 18, 5, 1, 0, '2016-07-12 00:12:03', '2016-07-12 00:12:03'),
(247, 'S.G. Road', 's_g_road', 18, 5, 1, 0, '2016-07-12 00:13:32', '2016-07-12 00:13:32'),
(248, 'Navrangpura', 'navrangpura', 18, 5, 1, 0, '2016-07-12 00:15:10', '2016-07-12 00:15:10'),
(249, 'Vastrapur', 'vastrapur', 18, 5, 1, 0, '2016-07-12 00:22:24', '2016-07-12 00:22:24'),
(250, 'Ashram Road', 'ashram_road', 18, 5, 1, 0, '2016-07-12 00:23:56', '2016-07-12 00:23:56'),
(251, 'Paldi', 'paldi', 18, 5, 1, 0, '2016-07-12 00:24:36', '2016-07-12 00:24:36'),
(252, 'Saraspur', 'saraspur', 18, 5, 1, 0, '2016-07-12 00:25:29', '2016-07-12 00:25:29'),
(253, 'Satellite', 'satellite', 18, 5, 1, 0, '2016-07-12 00:26:22', '2016-07-12 00:26:40'),
(254, 'Sarangpur', 'sarangpur', 18, 5, 1, 0, '2016-07-12 00:30:14', '2016-07-12 00:30:14'),
(255, 'Ambawadi', 'ambawadi', 18, 5, 1, 0, '2016-07-12 00:32:28', '2016-07-12 00:32:28'),
(256, 'Ellis Bridge', 'ellis_bridge', 18, 5, 1, 0, '2016-07-12 00:44:07', '2016-07-12 00:44:07'),
(257, 'Ghatlodia', 'ghatlodia', 18, 5, 1, 0, '2016-07-12 00:45:33', '2016-07-12 00:45:33'),
(258, 'Gulbai Tekra', 'gulbai_tekra', 18, 5, 1, 0, '2016-07-12 00:48:06', '2016-07-12 00:48:06'),
(259, 'Gita Mandir', 'gita_mandir', 18, 5, 1, 0, '2016-07-12 00:49:59', '2016-07-12 00:49:59'),
(261, 'Memnagar', 'memnagar', 18, 5, 1, 0, '2016-07-14 04:00:30', '2016-07-14 04:00:30'),
(262, 'Naranpura', 'naranpura', 18, 5, 1, 0, '2016-07-14 04:01:38', '2016-07-14 04:01:38'),
(263, 'Vadaj', 'vadaj', 18, 5, 1, 0, '2016-07-14 04:35:55', '2016-07-14 04:35:55'),
(264, 'Vasana', 'vasana', 18, 5, 1, 0, '2016-07-14 04:36:30', '2016-07-14 04:36:30'),
(265, 'Naroda', 'naroda', 18, 5, 1, 0, '2016-07-14 04:37:26', '2016-07-14 04:37:26'),
(266, 'Narol', 'narol', 18, 5, 1, 0, '2016-07-14 04:45:09', '2016-07-14 04:45:09'),
(267, 'Asarwa', 'asarwa', 18, 5, 1, 0, '2016-07-14 04:46:08', '2016-07-14 04:46:08'),
(268, 'Meghani Nagar', 'meghani_nagar', 18, 5, 1, 0, '2016-07-14 04:47:16', '2016-07-14 04:47:16'),
(269, 'Ranip', 'ranip', 18, 5, 1, 0, '2016-07-14 04:48:00', '2016-07-14 04:48:00'),
(270, 'Bopal', 'bopal', 18, 5, 1, 0, '2016-07-14 04:49:17', '2016-07-14 04:49:17'),
(271, 'Sabarmati', 'sabarmati', 18, 5, 1, 0, '2016-07-14 04:51:27', '2016-07-14 04:51:27'),
(272, 'Shahibaug', 'shahibaug', 18, 5, 1, 0, '2016-07-14 04:51:58', '2016-07-14 04:51:58'),
(273, 'Astodia', 'astodia', 18, 5, 1, 0, '2016-07-14 04:54:45', '2016-07-14 04:54:45'),
(274, 'Dariapur', 'dariapur', 18, 5, 1, 0, '2016-07-14 04:56:22', '2016-07-14 04:56:22'),
(275, 'Kalupur', 'kalupur', 18, 5, 1, 0, '2016-07-14 04:57:48', '2016-07-14 04:57:48'),
(276, 'Lal Darwaza', 'lal_darwaza', 18, 5, 1, 0, '2016-07-14 04:58:55', '2016-07-14 04:58:55'),
(277, 'Raipur', 'raipur', 18, 5, 1, 0, '2016-07-14 05:16:03', '2016-07-14 05:16:03'),
(278, 'Shahpur', 'shahpur', 18, 5, 1, 0, '2016-07-14 05:17:41', '2016-07-14 05:17:41'),
(279, 'Dani Limbada', 'dani_limbada', 18, 5, 1, 0, '2016-07-14 05:18:45', '2016-07-14 05:18:45'),
(280, 'Jamalpur', 'jamalpur', 18, 5, 1, 0, '2016-07-14 05:19:16', '2016-07-14 05:19:16'),
(281, 'Kankaria', 'kankaria', 18, 5, 1, 0, '2016-07-14 05:21:46', '2016-07-14 05:21:46'),
(282, 'Odhav', 'odhav', 18, 5, 1, 0, '2016-07-14 05:23:30', '2016-07-14 05:23:30'),
(283, 'Maninagar', 'maninagar', 18, 5, 1, 0, '2016-07-14 05:24:01', '2016-07-14 05:24:01'),
(285, 'Sanand', 'sanand', 18, 5, 1, 0, '2016-07-14 05:51:29', '2016-07-14 05:51:29'),
(286, 'Sola', 'sola', 18, 5, 1, 0, '2016-07-24 01:14:01', '2016-07-24 01:14:01'),
(287, 'Iscon Ambli Road', 'iscon_ambli_road', 18, 5, 1, 0, '2016-07-26 00:12:51', '2016-07-26 00:12:51'),
(288, 'Vejalpur Road', 'vejalpur_road', 18, 5, 1, 0, '2016-07-26 05:02:56', '2016-07-26 05:02:56'),
(289, 'New Ranip', 'new_ranip', 18, 5, 1, 0, '2016-07-29 03:51:32', '2016-07-29 03:51:32'),
(290, 'Gota', 'gota', 18, 5, 1, 0, '2016-07-30 23:59:22', '2016-07-30 23:59:22'),
(291, 'Chandkheda', 'chandkheda', 18, 5, 1, 0, '2016-08-01 00:38:19', '2016-08-01 00:38:19'),
(292, 'Harni Airport Road', 'harni_airport_road', 1, 5, 1, 1, '2016-08-02 00:24:46', '2016-08-02 00:24:46'),
(293, 'Motnath Mahadev Temple Road', 'motnath_mahadev_temple_road', 1, 5, 1, 0, '2016-08-02 00:29:17', '2016-08-02 00:29:17'),
(294, 'Golden Chokdi', 'golden_chokdi', 1, 5, 1, 0, '2016-08-02 00:33:29', '2016-08-02 00:33:29'),
(295, 'Waghodia Main Road', 'waghodia_main_road', 1, 5, 1, 1, '2016-08-02 00:34:03', '2016-08-02 00:34:03'),
(296, 'Gayatri Mandir Road', 'gayatri_mandir_road', 1, 5, 1, 0, '2016-08-02 00:35:05', '2016-08-02 00:35:05'),
(297, 'Thaltej', 'thaltej', 18, 5, 1, 0, '2016-08-05 04:39:41', '2016-08-05 04:40:29'),
(298, 'Narolgam', 'narolgam', 18, 5, 1, 0, '2016-08-09 04:50:02', '2016-08-09 04:50:02'),
(299, 'Drive In Road', 'drive_in_road', 18, 5, 1, 0, '2016-08-11 00:57:20', '2016-08-11 00:57:20'),
(300, 'Ambli', 'ambli', 18, 5, 1, 0, '2016-08-11 23:57:54', '2016-08-11 23:57:54'),
(301, 'New Naroda', 'new_naroda', 18, 5, 1, 0, '2016-08-12 00:49:55', '2016-08-12 00:49:55'),
(302, 'Vataman Dholera Highway', 'vataman_dholera_highway', 18, 5, 1, 0, '2016-08-12 00:59:30', '2016-08-12 00:59:30'),
(303, 'Zundal', 'zundal', 18, 5, 1, 0, '2016-08-13 00:44:50', '2016-08-13 00:44:50'),
(304, 'Kathwada Road', 'kathwada_road', 18, 5, 1, 0, '2016-08-13 03:39:18', '2016-08-13 03:39:18'),
(305, 'New Chandkheda', 'new_chandkheda', 18, 5, 1, 0, '2016-08-13 06:16:43', '2016-08-13 06:16:43'),
(306, 'Lambha', 'lambha', 18, 5, 1, 0, '2016-08-13 06:23:34', '2016-08-13 06:23:34'),
(307, 'Vastral', 'vastral', 18, 5, 1, 0, '2016-08-14 03:07:38', '2016-08-14 03:07:38'),
(308, 'Shilaj', 'shilaj', 18, 5, 1, 0, '2016-08-14 03:59:21', '2016-08-14 03:59:21'),
(309, 'Vejalpur', 'vejalpur', 18, 5, 1, 0, '2016-08-16 00:02:26', '2016-08-16 00:02:26'),
(310, 'Jodhpur Cross Road', 'jodhpur_cross_road', 18, 5, 1, 0, '2016-08-16 00:11:50', '2016-08-16 00:11:50'),
(311, 'Halol', 'halol', 1, 5, 1, 0, '2016-08-19 04:48:52', '2016-08-19 04:48:52'),
(312, 'Vaishnodevi', 'vaishnodevi', 18, 5, 1, 0, '2016-08-22 05:51:57', '2016-08-22 05:51:57'),
(313, 'TRAGAD', 'tragad', 18, 5, 1, 0, '2016-08-22 23:43:52', '2016-08-22 23:43:52'),
(314, 'Iscon Bopal Road', 'iscon_bopal_road', 18, 5, 1, 0, '2016-08-23 01:21:27', '2016-08-23 01:21:27'),
(315, 'vatwa', 'vatwa', 18, 5, 1, 0, '2016-08-23 04:00:12', '2016-08-23 04:00:12'),
(316, 'V.V. Nagar', 'v_v_nagar', 18, 5, 1, 0, '2016-08-24 02:36:18', '2016-08-24 02:36:18'),
(317, 'Delhi Darwaja', 'delhi_darwaja', 18, 5, 1, 0, '2016-08-26 00:55:35', '2016-08-26 00:55:35'),
(318, 'Sarkhej', 'sarkhej', 18, 5, 1, 0, '2016-08-26 01:14:01', '2016-08-26 01:14:01'),
(319, 'Makarba', 'makarba', 18, 5, 1, 0, '2016-08-26 02:23:39', '2016-08-26 02:23:39'),
(320, 'Jagatpur', 'jagatpur', 18, 5, 1, 0, '2016-08-26 23:25:09', '2016-08-26 23:25:09'),
(321, 'Bodakdev', 'bodakdev', 18, 5, 1, 0, '2016-08-27 03:06:13', '2016-08-27 03:06:13'),
(322, 'SP Ring Road', 'sp_ring_road', 18, 5, 1, 0, '2016-08-28 04:18:49', '2016-08-28 04:18:49'),
(323, 'New Nikol', 'new_nikol', 18, 5, 1, 0, '2016-08-28 05:22:55', '2016-08-28 05:22:55'),
(324, 'Law Garden', 'law_garden', 18, 5, 1, 0, '2016-08-28 06:29:00', '2016-08-28 06:29:00'),
(325, 'Bavla', 'bavla', 18, 5, 1, 0, '2016-08-29 00:55:27', '2016-08-29 00:55:27'),
(326, 'KOLAT', 'kolat', 18, 5, 1, 0, '2016-09-01 04:02:14', '2016-09-01 04:02:14'),
(327, 'Gujarat College Road', 'gujarat_college_road', 18, 5, 1, 0, '2016-09-02 00:10:07', '2016-09-02 00:10:07'),
(328, 'Nava Wadaj', 'nava_wadaj', 18, 5, 1, 0, '2016-09-03 00:44:26', '2016-09-03 00:44:26'),
(330, 'Shela', 'shela', 18, 5, 1, 0, '2016-09-07 03:52:37', '2016-09-07 03:52:37'),
(332, 'Motera', 'motera', 18, 5, 1, 0, '2016-09-09 01:58:27', '2016-09-09 01:58:27'),
(333, 'Gandhinagar', 'gandhinagar', 18, 5, 1, 0, '2016-09-09 02:40:08', '2016-09-09 02:40:08'),
(334, 'RTO Circle', 'rto_circle', 18, 5, 1, 0, '2016-09-14 02:26:23', '2016-09-14 02:26:23'),
(335, 'Sanathal', 'sanathal', 18, 5, 1, 0, '2016-09-15 02:53:22', '2016-09-15 02:53:22'),
(336, 'Sargasan', 'sargasan', 18, 5, 1, 0, '2016-09-16 04:14:49', '2016-09-16 04:14:49'),
(337, 'Science City', 'science_city', 18, 5, 1, 0, '2016-09-16 07:14:50', '2016-09-16 07:14:50'),
(338, 'Kudasan', 'kudasan', 18, 5, 1, 0, '2016-09-17 00:06:23', '2016-09-17 00:06:23'),
(339, 'Behrampura', 'behrampura', 18, 5, 1, 0, '2016-09-20 06:45:37', '2016-09-20 06:45:37'),
(340, 'Vishala', 'vishala', 18, 5, 1, 0, '2016-09-22 05:50:02', '2016-09-22 05:50:02'),
(341, 'Commerce Road', 'commerce', 18, 5, 1, 0, '2016-09-23 03:40:33', '2016-09-23 03:41:01'),
(342, 'New Maninagar', 'new_maninagar', 18, 5, 1, 0, '2016-09-23 23:36:14', '2016-09-23 23:36:14'),
(343, 'Vinzol', 'vinzol', 18, 5, 1, 0, '2016-09-24 02:12:20', '2016-09-24 02:12:20'),
(344, 'Chanakyapuri', 'chanakyapuri', 18, 5, 1, 0, '2016-09-25 06:19:24', '2016-09-25 06:19:24'),
(345, 'Changodar', 'changodar', 18, 5, 1, 0, '2016-09-28 00:08:39', '2016-09-28 00:08:39'),
(346, 'Moraiya', 'moraiya', 18, 5, 1, 0, '2016-09-29 00:21:54', '2016-09-29 00:21:54'),
(347, 'hathijan', 'hathijan', 18, 5, 1, 0, '2016-09-29 05:12:39', '2016-09-29 05:12:39'),
(348, 'Chharodi', 'chharodi', 18, 5, 1, 0, '2016-10-08 07:08:55', '2016-10-08 07:08:55'),
(349, 'Mota Chiloda', 'mota_chiloda', 18, 5, 1, 0, '2016-10-13 04:53:28', '2016-10-13 04:53:28'),
(350, 'Koba', 'koba', 18, 5, 1, 0, '2016-10-18 23:54:10', '2016-10-18 23:54:10'),
(351, 'Raysan', 'raysan', 18, 5, 1, 0, '2016-10-25 03:06:23', '2016-10-25 03:06:23'),
(352, 'Chandlodia', 'chandlodia', 18, 5, 1, 0, '2016-10-26 01:26:54', '2016-10-26 01:26:54'),
(353, 'Nana Chiloda', 'nana_chiloda', 18, 5, 1, 0, '2016-11-07 00:36:20', '2016-11-07 00:36:20'),
(354, 'Gotri', 'gotri', 1, 5, 1, 1, '2016-11-30 05:39:12', '2016-11-30 05:39:12'),
(355, 'Sherkhi', 'sherkhi', 1, 5, 1, 0, '2016-12-01 00:00:38', '2016-12-01 00:00:38'),
(356, 'Koyali', 'koyali', 1, 5, 1, 1, '2016-12-04 00:59:25', '2016-12-04 00:59:25'),
(357, 'Akshar Chowk', 'akshar_chowk', 1, 5, 1, 0, '2016-12-05 00:53:31', '2016-12-05 00:53:31'),
(359, 'Ajwa', 'ajwa', 1, 5, 1, 1, '2016-12-06 06:02:44', '2017-03-22 01:01:54'),
(360, 'Maneja', 'maneja', 1, 5, 1, 1, '2017-03-22 01:34:21', '2017-03-22 01:34:21'),
(361, 'Umeta', 'Umeta', 1, 5, 1, 0, '2017-04-26 03:54:09', '2017-04-26 03:54:09'),
(362, 'Pal', 'pal', 19, 5, 1, 0, '2017-06-30 05:42:51', '2017-06-30 05:42:51'),
(363, 'Katargam', 'katargam', 19, 5, 1, 0, '2017-07-04 01:33:13', '2017-07-04 01:33:13'),
(364, 'Ved Road', 'ved_road', 19, 5, 1, 0, '2017-07-04 01:33:44', '2017-07-04 01:33:44'),
(365, 'Utran', 'utran', 19, 5, 1, 0, '2017-07-04 01:34:01', '2017-07-04 01:34:01'),
(366, 'Amroli', 'amroli', 19, 5, 1, 0, '2017-07-04 01:34:15', '2017-07-04 01:34:15'),
(367, 'Katargam GIDC', 'katargam_gidc', 19, 5, 1, 0, '2017-07-04 01:34:29', '2017-07-04 01:34:29'),
(368, 'Kosad', 'kosad', 19, 5, 1, 0, '2017-07-04 01:34:48', '2017-07-04 01:34:48'),
(369, 'Bharthana', 'bharthana', 19, 5, 1, 0, '2017-07-04 01:35:02', '2017-07-04 01:35:02'),
(370, 'Chhapra Bhatha', 'chhapra_bhatha', 19, 5, 1, 0, '2017-07-04 01:35:17', '2017-07-04 01:35:17'),
(371, 'Tarwadi', 'tarwadi', 19, 5, 1, 0, '2017-07-04 01:35:37', '2017-07-04 01:35:37'),
(372, 'Mota Varachha', 'mota_varachha', 19, 5, 1, 0, '2017-07-04 01:35:52', '2017-07-04 01:35:52'),
(373, 'Abrama Kathor', 'abrama_kathor', 19, 5, 1, 0, '2017-07-04 01:36:12', '2017-07-04 01:36:12'),
(374, 'Station Road', 'station_road', 19, 5, 1, 0, '2017-07-04 01:36:43', '2017-07-04 01:36:43'),
(375, 'Varachha Road', 'varachha_road', 19, 5, 1, 0, '2017-07-04 01:37:03', '2017-07-04 01:37:03'),
(376, 'Sumul Dairy', 'sumul_dairy', 19, 5, 1, 0, '2017-07-04 01:37:19', '2017-07-04 01:37:19'),
(377, 'Hira baug', 'hira_baug', 19, 5, 1, 0, '2017-07-04 01:37:57', '2017-07-04 01:37:57'),
(378, 'A K Road', 'a_k_road', 19, 5, 1, 0, '2017-07-04 01:38:17', '2017-07-04 01:38:17'),
(379, 'L H Road', 'l_h_road', 19, 5, 1, 0, '2017-07-04 01:38:32', '2017-07-04 01:38:32'),
(380, 'Punagam', 'punagam', 19, 5, 1, 0, '2017-07-04 01:38:48', '2017-07-04 01:38:48'),
(381, 'Bombay Market', 'bombay_market', 19, 5, 1, 0, '2017-07-04 01:39:04', '2017-07-04 01:39:04'),
(382, 'Sarthana Jakatnaka', 'sarthana_jakatnaka', 19, 5, 1, 0, '2017-07-04 01:43:13', '2017-07-04 01:43:13'),
(383, 'Yogi chock', 'yogi_chock', 19, 5, 1, 0, '2017-07-04 01:43:26', '2017-07-04 01:43:26'),
(384, 'Valak', 'valak', 19, 5, 1, 0, '2017-07-04 01:43:39', '2017-07-04 01:43:39'),
(385, 'Laskana', 'laskana', 19, 5, 1, 0, '2017-07-04 01:43:52', '2017-07-04 01:43:52'),
(386, 'Adajan Gam', 'adajan_gam', 19, 5, 1, 0, '2017-07-04 01:44:07', '2017-07-04 01:44:07'),
(387, 'Palanpur', 'palanpur', 19, 5, 1, 0, '2017-07-04 01:44:50', '2017-07-04 01:44:50'),
(388, 'Bhatha', 'bhatha', 19, 5, 1, 0, '2017-07-04 01:45:01', '2017-07-04 01:45:01'),
(389, 'Jahangirpura', 'jahangirpura', 19, 5, 1, 0, '2017-07-04 01:45:13', '2017-07-04 01:45:13'),
(390, 'Jahangirabad', 'jahangirabad', 19, 5, 1, 0, '2017-07-04 01:45:27', '2017-07-04 01:45:27'),
(391, 'Olpad', 'olpad', 19, 5, 1, 0, '2017-07-04 01:45:38', '2017-07-04 01:45:38'),
(392, 'Rander', 'rander', 19, 5, 1, 0, '2017-07-04 01:45:50', '2017-07-04 01:45:50'),
(393, 'Hazira', 'hazira', 19, 5, 1, 0, '2017-07-04 01:46:03', '2017-07-04 01:46:03'),
(394, 'Kawas', 'kawas', 19, 5, 1, 0, '2017-07-04 01:46:18', '2017-07-04 01:46:18'),
(395, 'Ichchhapor', 'ichchhapor', 19, 5, 1, 0, '2017-07-04 01:46:30', '2017-07-04 01:46:30'),
(396, 'Ghoddodroad', 'ghoddodroad', 19, 5, 1, 0, '2017-07-04 01:47:45', '2017-07-04 01:47:45'),
(397, 'Piplod', 'piplod', 19, 5, 1, 0, '2017-07-04 01:48:01', '2017-07-04 01:48:01'),
(398, 'Citylight', 'citylight', 19, 5, 1, 0, '2017-07-04 01:48:16', '2017-07-04 01:48:16'),
(399, 'New Citylight', 'new_citylight', 19, 5, 1, 0, '2017-07-04 01:48:31', '2017-07-04 01:48:31'),
(400, 'Bhatar', 'bhatar', 19, 5, 1, 0, '2017-07-04 01:48:52', '2017-07-04 01:48:52'),
(401, 'Vesu', 'vesu', 19, 5, 1, 0, '2017-07-04 01:49:09', '2017-07-04 01:49:09'),
(402, 'Bhimpore', 'bhimpore', 19, 5, 1, 0, '2017-07-04 01:49:28', '2017-07-04 01:49:28'),
(403, 'Dummas', 'dummas', 19, 5, 1, 0, '2017-07-04 01:49:42', '2017-07-04 01:49:42'),
(404, 'Magdalla', 'magdalla', 19, 5, 1, 0, '2017-07-04 01:50:04', '2017-07-04 01:50:04'),
(405, 'Althan', 'althan', 19, 5, 1, 0, '2017-07-04 01:50:18', '2017-07-04 01:50:18'),
(406, 'Udhna', 'udhna', 19, 5, 1, 0, '2017-07-04 01:50:38', '2017-07-04 01:50:38'),
(407, 'Pandesara', 'pandesara', 19, 5, 1, 0, '2017-07-04 01:50:53', '2017-07-04 01:50:53'),
(408, 'Sachin', 'sachin', 19, 5, 1, 0, '2017-07-04 01:51:08', '2017-07-04 01:51:08'),
(409, 'Bhestan', 'bhestan', 19, 5, 1, 0, '2017-07-04 01:51:30', '2017-07-04 01:51:30'),
(410, 'Limbayat', 'limbayat', 19, 5, 1, 0, '2017-07-04 01:51:45', '2017-07-04 01:51:45'),
(411, 'Kharwar Nagar', 'kharwar_nagar', 19, 5, 1, 0, '2017-07-04 01:52:00', '2017-07-04 01:52:00'),
(412, 'Parvat Patiya', 'parvat_patiya', 19, 5, 1, 0, '2017-07-04 01:52:14', '2017-07-04 01:52:14'),
(413, 'Aaspass', 'aaspass', 19, 5, 1, 0, '2017-07-04 01:52:30', '2017-07-04 01:52:30'),
(414, 'Kharwasa', 'kharwasa', 19, 5, 1, 0, '2017-07-04 01:52:47', '2017-07-04 01:52:47'),
(415, 'Magob', 'magob', 19, 5, 1, 0, '2017-07-04 01:53:01', '2017-07-04 01:53:01'),
(416, 'Bhagal', 'bhagal', 19, 5, 1, 0, '2017-07-04 01:53:19', '2017-07-04 01:53:19'),
(417, 'Nanpura', 'nanpura', 19, 5, 1, 0, '2017-07-04 01:53:40', '2017-07-04 01:53:40'),
(418, 'Salabatpura', 'salabatpura', 19, 5, 1, 0, '2017-07-04 01:53:55', '2017-07-04 01:53:55'),
(419, 'Chok', 'chok', 19, 5, 1, 0, '2017-07-04 01:54:13', '2017-07-04 01:54:13'),
(420, 'Begampura', 'begampura', 19, 5, 1, 0, '2017-07-04 01:54:30', '2017-07-04 01:54:30'),
(421, 'Mughalisara', 'mughalisara', 19, 5, 1, 0, '2017-07-04 01:54:46', '2017-07-04 01:54:46'),
(422, 'Rampura', 'rampura', 19, 5, 1, 0, '2017-07-04 01:55:09', '2017-07-04 01:55:09'),
(423, 'Sagrampura', 'sagrampura', 19, 5, 1, 0, '2017-07-04 01:55:23', '2017-07-04 01:55:23'),
(424, 'Delhigate', 'delhigate', 19, 5, 1, 0, '2017-07-04 01:55:41', '2017-07-04 01:55:41'),
(425, 'Kamrej', 'kamrej', 19, 5, 1, 0, '2017-07-04 01:56:25', '2017-07-04 01:56:25'),
(426, 'Kholwad', 'kholwad', 19, 5, 1, 0, '2017-07-04 01:56:43', '2017-07-04 01:56:43'),
(427, 'Nandsad', 'nandsad', 19, 5, 1, 0, '2017-07-04 01:57:00', '2017-07-04 01:57:00'),
(428, 'Kim', 'kim', 19, 5, 1, 0, '2017-07-04 01:59:28', '2017-07-04 01:59:28'),
(429, 'Kosamba', 'kosamba', 19, 5, 1, 0, '2017-07-04 02:00:29', '2017-07-04 02:00:29'),
(430, 'Velanja', 'velanja', 19, 5, 1, 0, '2017-07-04 02:00:41', '2017-07-04 02:00:41'),
(431, 'Kolibharthana', 'kolibharthana', 19, 5, 1, 0, '2017-07-04 02:00:54', '2017-07-04 02:00:54'),
(432, 'Kadodara', 'kadodara', 19, 5, 1, 0, '2017-07-04 02:01:07', '2017-07-04 02:01:07'),
(433, 'Bardoli Road', 'bardoli_road', 19, 5, 1, 0, '2017-07-04 02:01:27', '2017-07-04 02:01:27'),
(434, 'Navsari Road', 'navsari_road', 19, 5, 1, 0, '2017-07-04 02:01:38', '2017-07-04 02:01:38'),
(436, 'Chalthan', 'chalthan', 19, 5, 1, 0, '2017-07-04 02:02:06', '2017-07-04 02:02:06'),
(437, 'Zolwa', 'zolwa', 19, 5, 1, 0, '2017-07-04 02:02:19', '2017-07-04 02:02:19'),
(438, 'Dabholi', 'dabholi', 19, 5, 1, 0, '2017-07-04 02:02:41', '2017-07-04 02:02:41'),
(439, 'Causeway Road', 'causeway_road', 19, 5, 1, 0, '2017-07-04 02:51:13', '2017-07-04 02:51:13'),
(440, 'Masma', 'masma', 19, 5, 1, 0, '2017-07-05 06:57:03', '2017-07-05 06:57:03'),
(441, 'Parle Point', 'parle_point', 19, 5, 1, 0, '2017-07-07 06:33:19', '2017-07-07 06:33:19'),
(442, 'L. P. Savani Road', 'l_p_savani_road', 19, 5, 1, 0, '2017-07-10 02:02:30', '2017-07-10 02:02:30'),
(443, 'Kalali Talsat', 'Kalali talsat', 1, 5, 1, 0, '2017-07-10 06:44:19', '2017-07-10 06:53:55'),
(445, 'Variav', 'variav', 19, 5, 1, 0, '2017-07-15 00:08:23', '2017-07-15 00:08:23'),
(446, 'Adajan', 'adajan', 19, 5, 1, 0, '2017-07-15 01:51:51', '2017-07-15 01:51:51'),
(447, 'Ring Road', 'ring_road', 19, 5, 1, 0, '2017-07-22 06:02:39', '2017-07-22 06:02:39'),
(448, 'Dumbhal', 'dumbhal', 19, 5, 1, 0, '2017-07-26 01:35:58', '2017-07-26 01:35:58'),
(449, 'Karadva', 'karadva', 19, 5, 1, 0, '2017-07-26 05:14:09', '2017-07-26 05:14:09'),
(450, 'Dindoli', 'dindoli', 19, 5, 1, 0, '2017-07-26 05:45:10', '2017-07-26 05:45:10'),
(451, 'Bamroli', 'bamroli', 19, 5, 1, 0, '2017-08-02 03:26:12', '2017-08-02 03:26:12'),
(452, 'Bhimrad', 'bhimrad', 19, 5, 1, 0, '2017-08-04 03:31:18', '2017-08-04 03:31:18'),
(453, 'Palsana', 'palsana', 19, 5, 1, 0, '2017-08-09 05:59:33', '2017-08-09 05:59:33'),
(454, 'Umarwada', 'umarwada', 19, 5, 1, 0, '2017-08-10 06:47:53', '2017-08-10 06:47:53'),
(455, 'Vadod', 'vadod', 19, 5, 1, 0, '2017-08-26 01:54:43', '2017-08-26 01:54:43'),
(456, 'U. M. Road', 'u_m_road', 19, 5, 1, 0, '2017-08-26 05:39:02', '2017-08-26 05:39:02'),
(457, 'Anand mahal Road', 'anand_mahal_road', 19, 5, 1, 0, '2017-08-30 04:54:40', '2017-08-30 04:54:40'),
(458, 'Athwagate', 'athwagate', 19, 5, 1, 0, '2017-09-01 06:16:24', '2017-09-01 06:16:24'),
(459, 'Morabhagal', 'Morabhagal', 19, 5, 1, 0, '2017-11-14 23:37:09', '2017-11-14 23:37:09'),
(460, 'Bardoli', 'Bardoli', 19, 5, 1, 0, '2017-11-14 23:37:31', '2017-11-14 23:37:31'),
(461, 'Navsari', 'Navsari', 19, 5, 1, 0, '2017-11-14 23:38:12', '2017-11-14 23:38:12'),
(462, 'Vagech Kuvadiya', 'Vagech Kuvadiya', 19, 5, 1, 0, '2017-11-15 01:26:31', '2017-11-15 01:26:31'),
(463, 'Godadara', 'Godadara', 19, 5, 1, 0, '2017-11-15 04:08:10', '2017-11-15 04:08:10'),
(464, 'Khadsad', 'Khadsad ', 19, 5, 1, 0, '2017-11-16 02:35:30', '2017-11-16 02:35:30'),
(465, 'Kumbhariya', 'Kumbhariya', 19, 5, 1, 0, '2017-11-16 03:08:41', '2017-11-16 03:08:41'),
(466, 'Puna Kumbhariya', 'Puna Kumbhariya', 19, 5, 1, 0, '2017-11-16 03:09:02', '2017-11-16 03:09:02'),
(467, 'Atodara', 'Atodara', 19, 5, 1, 0, '2017-11-25 05:09:38', '2017-11-25 05:09:38'),
(468, 'Khatodara', 'Khatodara', 19, 5, 1, 0, '2017-11-30 03:47:50', '2017-11-30 03:47:50'),
(469, 'Maroli', 'Maroli', 19, 5, 1, 0, '2017-11-30 04:36:05', '2017-11-30 04:36:05'),
(470, 'Dihen', 'Dihen', 19, 5, 1, 0, '2017-12-02 04:07:05', '2017-12-02 04:07:05'),
(471, 'Aerthan', 'Aerthan', 19, 5, 1, 0, '2017-12-05 04:31:01', '2017-12-05 04:31:01'),
(472, 'Narthan', 'Narthan', 19, 5, 1, 0, '2017-12-07 02:39:46', '2017-12-07 02:39:46'),
(473, 'Dandi Road', 'Dandi Road', 19, 5, 1, 0, '2018-01-10 01:36:23', '2018-01-10 01:36:23'),
(474, 'ugat', 'ugat', 19, 5, 1, 0, '2018-02-28 01:11:56', '2018-02-28 01:11:56'),
(475, 'Canal Road Ugat', 'Canal Road Ugat', 19, 5, 1, 0, '2018-02-28 01:13:19', '2018-02-28 01:13:19'),
(476, 'New Tandalja', 'new-tandalja', 1, 5, 1, 0, '2018-08-21 10:51:29', '2018-08-21 10:51:29'),
(477, 'VIP Road Surat', 'vip-road-surat', 19, 5, 1, 0, '2019-01-21 05:46:28', '2019-01-21 05:46:28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banks`
--

CREATE TABLE `tbl_banks` (
  `id` int(11) UNSIGNED NOT NULL,
  `bank_name` varchar(50) NOT NULL DEFAULT '',
  `interest_rate` float NOT NULL,
  `bank_logo` varchar(250) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_banks`
--

INSERT INTO `tbl_banks` (`id`, `bank_name`, `interest_rate`, `bank_logo`, `created_at`, `updated_at`) VALUES
(4, 'Bank of Baroda', 10.53, 'images/bankImages/CP3CDn5tNfysDiVIyrR9XzSNSRnPnAm2nIUn2I9X.png', '2015-05-22 01:52:28', '2018-05-12 04:21:24'),
(5, 'Bank of India', 9.56, 'images/bankImages/B9wmGjKbubzkzStEvvuPbipvOk4vHfXwDmFJQJuW.png', '2015-05-22 02:42:16', '2018-05-12 04:21:22'),
(6, 'HDFC bank', 10, 'images/bankImages/GMKoOdAZ7yy8xWlPUZ1xXfp8E6qRz8fssP6zstOz.png', '2015-05-22 02:42:59', '2018-06-26 11:04:33'),
(7, 'Kotak Mahindra Bank', 10, 'images/bankImages/ajN03ljIcKBXGpu8uil4h392kalMM9A5V9UOlm0k.png', '2015-05-22 02:43:35', '2018-06-26 11:04:21'),
(8, 'ICICI Bank', 9.56, 'images/bankImages/9l00sCtQYobqPTxQ5sgn8XbUWoLaduB82Vp4BdwX.png', '2015-05-22 02:44:02', '2018-05-12 04:21:13'),
(9, 'Axis Bank', 9.77, 'images/bankImages/wRmQvhAxy9yMF0Y1DdU7jOAc2FVFifTOikAY7VTv.png', '2015-05-22 02:44:33', '2018-05-12 04:21:05'),
(10, 'State Bank of India', 10, 'images/bankImages/ddHPKJSPYfq0ABipKR1xEROzbjyuB3Ke0PXfZ00t.png', '2015-05-22 03:25:36', '2018-05-12 04:21:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bookings`
--

CREATE TABLE `tbl_bookings` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `mobile_number` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `handle_by` int(11) DEFAULT NULL COMMENT 'if broker then id of broker ',
  `broker_id` int(11) DEFAULT NULL,
  `site_id` int(11) DEFAULT NULL,
  `bhk` varchar(100) DEFAULT NULL,
  `package` int(11) DEFAULT NULL COMMENT 'siteoffers id here',
  `amount` varchar(100) DEFAULT NULL,
  `is_discount` int(11) DEFAULT NULL,
  `discount_amount` varchar(100) DEFAULT NULL,
  `final_amount` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_bookings`
--

INSERT INTO `tbl_bookings` (`id`, `customer_name`, `mobile_number`, `address`, `email`, `handle_by`, `broker_id`, `site_id`, `bhk`, `package`, `amount`, `is_discount`, `discount_amount`, `final_amount`, `created_at`, `updated_at`) VALUES
(1, 'test', '12121212', 'test', 'test', 1, 4, 1, '1', 2, '10000', 1, '1000', '9000', '2020-12-02 09:10:47', '2020-12-03 08:34:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_booking_cash_amount`
--

CREATE TABLE `tbl_booking_cash_amount` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `status` int(2) NOT NULL,
  `amount_date` datetime DEFAULT NULL,
  `payment_type` int(2) NOT NULL COMMENT '	0=cash,1=cheque,2=online	',
  `cheque_number` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `date_of_cheque` date DEFAULT NULL,
  `cheque_image` varchar(100) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_mode` varchar(100) DEFAULT NULL,
  `online_photo` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_booking_cash_amount`
--

INSERT INTO `tbl_booking_cash_amount` (`id`, `booking_id`, `amount`, `status`, `amount_date`, `payment_type`, `cheque_number`, `bank_name`, `date_of_cheque`, `cheque_image`, `transaction_id`, `payment_mode`, `online_photo`, `created_at`, `updated_at`) VALUES
(2, 1, '5000', 0, '2020-12-03 00:00:00', 1, '67676 76767 67678', 'sbi', '2020-12-04', '1606997570.jpg', '', '', '', '2020-12-03 06:42:50', '2020-12-03 06:42:50');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_booking_direct_amount`
--

CREATE TABLE `tbl_booking_direct_amount` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `status` int(2) DEFAULT NULL,
  `amount_date` datetime DEFAULT NULL,
  `payment_type` int(2) NOT NULL COMMENT '	0=cash,1=cheque,2=online	',
  `cheque_number` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `date_of_cheque` varchar(100) DEFAULT NULL,
  `cheque_image` varchar(100) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_mode` varchar(11) DEFAULT NULL,
  `online_photo` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_booking_direct_amount`
--

INSERT INTO `tbl_booking_direct_amount` (`id`, `booking_id`, `amount`, `status`, `amount_date`, `payment_type`, `cheque_number`, `bank_name`, `date_of_cheque`, `cheque_image`, `transaction_id`, `payment_mode`, `online_photo`, `created_at`, `updated_at`) VALUES
(2, 1, '35000', 0, '2020-02-12 00:00:00', 2, NULL, NULL, NULL, NULL, '3434343434', 'google pay', '1606996496.jpg', '2020-12-03 06:24:56', '2020-12-03 06:24:56'),
(3, 1, '12000', 1, '2020-03-12 00:00:00', 0, NULL, NULL, NULL, NULL, '', '', '', '2020-12-03 06:26:06', '2020-12-03 06:26:06');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_booking_loan_amount`
--

CREATE TABLE `tbl_booking_loan_amount` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `amount_sanction` varchar(100) DEFAULT NULL,
  `la_amount` varchar(100) DEFAULT NULL,
  `emi` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_booking_loan_amount`
--

INSERT INTO `tbl_booking_loan_amount` (`id`, `booking_id`, `bank_name`, `amount_sanction`, `la_amount`, `emi`, `created_at`, `updated_at`) VALUES
(1, 1, 'sbi', '1200000', '230000', '4000', '2020-12-03 06:58:07', '2020-12-03 06:58:07');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_booking_total_amount`
--

CREATE TABLE `tbl_booking_total_amount` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `status` int(2) NOT NULL,
  `amount_date` datetime DEFAULT NULL,
  `payment_type` int(2) DEFAULT NULL COMMENT '0=cash,1=cheque,2=online',
  `cheque_number` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `date_of_cheque` datetime DEFAULT NULL,
  `cheque_image` varchar(100) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_mode` varchar(11) DEFAULT NULL,
  `online_photo` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_booking_total_amount`
--

INSERT INTO `tbl_booking_total_amount` (`id`, `booking_id`, `amount`, `status`, `amount_date`, `payment_type`, `cheque_number`, `bank_name`, `date_of_cheque`, `cheque_image`, `transaction_id`, `payment_mode`, `online_photo`, `created_at`, `updated_at`) VALUES
(4, 1, '1233300', 0, '2020-12-03 00:00:00', 2, NULL, NULL, NULL, NULL, '121212121', 'test', '1607003948.png', '2020-12-03 08:29:08', '2020-12-03 08:29:08');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_builder_sites`
--

CREATE TABLE `tbl_builder_sites` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `property_type` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=new, 2=resale',
  `site_name` varchar(256) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `display_units` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1=display_original, 2=display_on_request',
  `total_buildings` smallint(6) UNSIGNED NOT NULL DEFAULT '0',
  `sold_buildings` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `unsold_buildings` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `lead_by` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `address` varchar(200) DEFAULT '',
  `area_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `city_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `state_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `country_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '1',
  `latitude` varchar(20) NOT NULL DEFAULT '0',
  `longitude` varchar(20) NOT NULL DEFAULT '0',
  `possession_status` enum('0','1') DEFAULT '0' COMMENT '0=available from,1=ready to move',
  `possession_date` date DEFAULT NULL,
  `age_of_construction` varchar(150) DEFAULT NULL,
  `price_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=display, 2=negotiable, 3=hidden',
  `sample_house` enum('0','1','2','3') DEFAULT '0' COMMENT '0=no, 1=yes, 2=infuture, 3=under construction',
  `sample_house_date` text COMMENT 'if sample house selcted as future then date',
  `sample_house_360_link` text,
  `sample_house_video_link` text,
  `video_link` text COMMENT 'site video link',
  `loan_approval` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=not available, 1=available',
  `water_supply` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes',
  `power_backup` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes',
  `is_featured` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes',
  `is_suspended` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes',
  `is_soldout` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0=inactive, 1=active, 2=deleted, 4=pending verification',
  `sort_number` tinyint(3) UNSIGNED NOT NULL DEFAULT '99' COMMENT 'sort according to package',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `handler_user_id` int(11) DEFAULT NULL,
  `rera_no` varchar(250) DEFAULT NULL,
  `rera_certificate` varchar(500) DEFAULT NULL,
  `brochure` varchar(500) DEFAULT NULL,
  `website_url` text,
  `is_resale_featured` enum('0','1') DEFAULT '0' COMMENT '0=no, 1=yes',
  `seo_title` varchar(150) DEFAULT NULL,
  `seo_keywords` varchar(150) DEFAULT NULL,
  `seo_descriptions` varchar(150) DEFAULT NULL,
  `og_title` varchar(150) DEFAULT NULL,
  `og_image` varchar(150) DEFAULT NULL,
  `og_url` varchar(150) DEFAULT NULL,
  `usp_one` varchar(120) DEFAULT NULL,
  `usp_two` varchar(120) DEFAULT NULL,
  `usp_three` varchar(120) DEFAULT NULL,
  `usp_four` varchar(120) DEFAULT NULL,
  `usp_five` varchar(120) DEFAULT NULL,
  `usp_six` varchar(120) DEFAULT NULL,
  `contact_person_name` varchar(150) DEFAULT NULL,
  `contact_person_email` varchar(150) DEFAULT NULL,
  `contact_person_phone` varchar(150) DEFAULT NULL,
  `brokrage_type` int(11) DEFAULT NULL,
  `brokrage_amount` varchar(11) DEFAULT NULL,
  `brokrage_percent` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_builder_sites`
--

INSERT INTO `tbl_builder_sites` (`id`, `user_id`, `property_type`, `site_name`, `description`, `display_units`, `total_buildings`, `sold_buildings`, `unsold_buildings`, `lead_by`, `address`, `area_id`, `city_id`, `state_id`, `country_id`, `latitude`, `longitude`, `possession_status`, `possession_date`, `age_of_construction`, `price_status`, `sample_house`, `sample_house_date`, `sample_house_360_link`, `sample_house_video_link`, `video_link`, `loan_approval`, `water_supply`, `power_backup`, `is_featured`, `is_suspended`, `is_soldout`, `status`, `sort_number`, `created_at`, `updated_at`, `code`, `handler_user_id`, `rera_no`, `rera_certificate`, `brochure`, `website_url`, `is_resale_featured`, `seo_title`, `seo_keywords`, `seo_descriptions`, `og_title`, `og_image`, `og_url`, `usp_one`, `usp_two`, `usp_three`, `usp_four`, `usp_five`, `usp_six`, `contact_person_name`, `contact_person_email`, `contact_person_phone`, `brokrage_type`, `brokrage_amount`, `brokrage_percent`) VALUES
(1, 1, 1, 'first', 'test', '1', 0, 0, 0, 0, 'test', 255, 18, 5, 1, '1111111', '222222', '0', '1970-01-01', NULL, 1, '0', '2020-11-01', NULL, NULL, 'test', '1', 1, 1, '0', '0', '0', 1, 99, '2020-11-27 07:28:59', '2020-11-28 08:19:30', '00001000010000100001', NULL, 'test', 'images/siteImages/FPLzx6cpRNLk3b0cwqZxZ9AMXa8BIXHyIPFtFc22.jpeg', 'images/siteImages/MYD0JbscuONZkd4ZLmp2WE3sdicCJuGoaxOClwd9.jpeg', 'test', '0', NULL, NULL, NULL, NULL, NULL, NULL, '33', '33', '33', '33', '33', '33', 'test', 'test@gmail.com', '33333333', 1, '10000', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_builder_sites_images`
--

CREATE TABLE `tbl_builder_sites_images` (
  `id` int(11) NOT NULL,
  `attachment_id` int(11) DEFAULT '0',
  `site_id` int(11) NOT NULL,
  `image_name` varchar(200) NOT NULL,
  `image_title` varchar(200) DEFAULT NULL,
  `is_featured` varchar(10) DEFAULT '0',
  `is_covered` varchar(10) DEFAULT '0',
  `image_type` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_builder_sites_images`
--

INSERT INTO `tbl_builder_sites_images` (`id`, `attachment_id`, `site_id`, `image_name`, `image_title`, `is_featured`, `is_covered`, `image_type`, `created_at`, `updated_at`) VALUES
(1, 0, 1, 'images/pt3lpZG6GLaiYIR3ryLAiCpJIwfWWPphBDz9wzvv.jpeg', NULL, '0', '0', 'project_pictures', '2020-11-27 12:58:59', '2020-11-28 13:49:15'),
(2, 0, 1, 'images/ZK5FEiS0niLBswmC06WIrGNFGixGwlAM7lw44bIH.jpeg', NULL, '0', '0', 'house_pictures', '2020-11-27 12:59:00', '2020-11-28 13:49:15'),
(3, 0, 1, 'images/siteImages/oslHr1Fn44vUTxbhnuTANi61MLCOWBNb8E7SbSVQ.jpeg', NULL, '0', '1', NULL, '2020-11-28 13:49:13', '2020-11-28 13:49:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cities`
--

CREATE TABLE `tbl_cities` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `slug` varchar(50) NOT NULL DEFAULT '',
  `country_id` int(11) UNSIGNED NOT NULL,
  `state_id` int(11) UNSIGNED NOT NULL,
  `status` enum('1','2','3') DEFAULT '1' COMMENT '1 = Active, 2 = Inactive, 3 = Deleted',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_cities`
--

INSERT INTO `tbl_cities` (`id`, `name`, `slug`, `country_id`, `state_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Vadodara', 'vadodara', 1, 5, '1', '2015-02-13 04:56:01', '2015-02-24 00:28:48'),
(18, 'Ahmedabad', 'ahmedabad', 1, 5, '1', '2016-07-12 00:00:25', '2020-11-24 05:43:39'),
(19, 'Surat', 'surat', 1, 5, '1', '2017-06-30 05:04:57', '2017-06-30 05:04:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_countries`
--

CREATE TABLE `tbl_countries` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `slug` varchar(50) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_countries`
--

INSERT INTO `tbl_countries` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'India', 'india', '2014-08-06 03:36:25', '2014-09-04 05:02:56');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_furniture_detail`
--

CREATE TABLE `tbl_furniture_detail` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `cost` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_furniture_detail`
--

INSERT INTO `tbl_furniture_detail` (`id`, `name`, `cost`, `created_at`, `updated_at`) VALUES
(1, 'Sofaset', '1000', '2020-12-04 09:46:56', '0000-00-00 00:00:00'),
(2, 'Center Table', '1200', '2020-12-04 09:59:26', '0000-00-00 00:00:00'),
(3, 'Tv Unit', '3000', '2020-12-04 09:59:26', '0000-00-00 00:00:00'),
(4, 'Kingsize Bed', '5000', '2020-12-04 09:59:51', '0000-00-00 00:00:00'),
(5, 'Mattress', '7000', '2020-12-04 09:59:51', '0000-00-00 00:00:00'),
(6, 'Three Door Wordrobe', '8500', '2020-12-04 10:00:15', '0000-00-00 00:00:00'),
(7, 'Marble Dinning Table', '1350', '2020-12-04 10:00:15', '0000-00-00 00:00:00'),
(8, 'Kitchen Furniture', '7500', '2020-12-04 10:00:41', '0000-00-00 00:00:00'),
(9, 'Platform Setup', '6500', '2020-12-04 10:00:41', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_membership_packages`
--

CREATE TABLE `tbl_membership_packages` (
  `id` int(11) UNSIGNED NOT NULL,
  `package_name` varchar(50) DEFAULT NULL,
  `package_for` int(11) DEFAULT NULL COMMENT '1=builder, 2=agent, 3=user',
  `package_type` int(11) NOT NULL DEFAULT '0' COMMENT '1=paid, 2=free, 3=trial',
  `is_microsite` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes',
  `add_property_limit_no` int(11) DEFAULT NULL,
  `enquiry_limit_no` int(11) DEFAULT NULL,
  `details` text,
  `created_by` int(11) DEFAULT NULL,
  `sort_order` tinyint(4) DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=active, 1=deleted',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_properties`
--

CREATE TABLE `tbl_properties` (
  `id` int(11) UNSIGNED NOT NULL,
  `code` varchar(15) DEFAULT NULL,
  `company_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `site_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `cat_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `sub_cat_id` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(100) DEFAULT '',
  `sub_title` varchar(255) DEFAULT NULL,
  `description` longtext,
  `video_link` varchar(255) DEFAULT NULL,
  `transaction_type` tinyint(4) UNSIGNED DEFAULT '1' COMMENT '1=sale, 2=rent',
  `price` bigint(20) UNSIGNED DEFAULT '0',
  `status` tinyint(4) DEFAULT '1' COMMENT '1=active, 2=soldout',
  `sort_num` int(11) DEFAULT '0',
  `number` varchar(20) DEFAULT NULL,
  `society_name` varchar(250) DEFAULT NULL,
  `maintenance` varchar(50) DEFAULT NULL,
  `property_in` varchar(50) DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `tp_scheme_no` varchar(50) DEFAULT NULL,
  `survey_no` varchar(50) DEFAULT NULL,
  `fp_no` varchar(50) DEFAULT NULL,
  `booking_amount` bigint(20) DEFAULT NULL,
  `registration_charge` enum('0','1') DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_properties`
--

INSERT INTO `tbl_properties` (`id`, `code`, `company_id`, `site_id`, `cat_id`, `sub_cat_id`, `name`, `sub_title`, `description`, `video_link`, `transaction_type`, `price`, `status`, `sort_num`, `number`, `society_name`, `maintenance`, `property_in`, `village`, `tp_scheme_no`, `survey_no`, `fp_no`, `booking_amount`, `registration_charge`, `created_at`, `updated_at`) VALUES
(1, NULL, 0, 1, 1, 9, '', 'A', NULL, NULL, NULL, 200000, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '2020-11-28 07:37:25', '2020-12-07 11:24:16'),
(2, NULL, 0, 1, 1, 14, NULL, 'B', NULL, NULL, NULL, 40000, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '2020-11-28 07:50:09', '2020-12-07 11:31:31');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_properties_unit_category`
--

CREATE TABLE `tbl_properties_unit_category` (
  `id` int(11) UNSIGNED NOT NULL,
  `property_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `cat_id` int(11) UNSIGNED NOT NULL,
  `sub_cat_id` tinyint(4) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_property_category`
--

CREATE TABLE `tbl_property_category` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(256) CHARACTER SET latin1 NOT NULL,
  `slug` varchar(256) CHARACTER SET latin1 NOT NULL,
  `status` enum('0','1','2') DEFAULT '1' COMMENT '0=InActive, 1=Active, 2=Deleted',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_property_category`
--

INSERT INTO `tbl_property_category` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Residential', 'residential', '1', '2018-04-28 12:39:20', '0000-00-00 00:00:00'),
(2, 'Commercial', 'commercial', '1', '2018-04-28 12:39:20', '0000-00-00 00:00:00'),
(3, 'Industrial', 'industrial', '1', '2018-04-28 12:39:20', '0000-00-00 00:00:00'),
(4, 'Land', 'land', '1', '2018-04-28 12:39:20', '0000-00-00 00:00:00'),
(5, 'Open Plots', 'open-plots', '0', '2019-08-06 01:43:32', '2019-09-03 10:10:15'),
(6, 'Weekend Home', 'weekend-home', '0', '2019-08-06 01:43:32', '2019-09-03 10:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_property_features`
--

CREATE TABLE `tbl_property_features` (
  `id` int(11) NOT NULL,
  `property_id` int(11) UNSIGNED NOT NULL,
  `bedrooms` varchar(4) DEFAULT NULL,
  `bathrooms` varchar(4) DEFAULT NULL,
  `balconies` varchar(4) DEFAULT NULL,
  `foyer_area` varchar(50) DEFAULT NULL,
  `store_room` varchar(50) DEFAULT '',
  `pooja_room` varchar(50) DEFAULT NULL,
  `study_room` varchar(50) DEFAULT NULL,
  `parking_area` varchar(50) DEFAULT NULL,
  `no_of_parking` int(11) DEFAULT NULL,
  `open_sides` varchar(50) DEFAULT NULL,
  `total_unit` int(10) DEFAULT NULL,
  `servant_room` varchar(50) DEFAULT NULL,
  `area_covered` int(10) DEFAULT NULL,
  `area_covered_unit` varchar(15) DEFAULT NULL,
  `sb_area` int(10) DEFAULT NULL,
  `sb_area_unit` varchar(15) DEFAULT NULL,
  `carpet_area` varchar(15) DEFAULT NULL,
  `carpet_area_unit` varchar(15) DEFAULT NULL,
  `built_area` int(10) DEFAULT NULL,
  `built_area_unit` varchar(15) DEFAULT NULL,
  `plot_area` varchar(100) DEFAULT NULL,
  `plot_area_unit` varchar(15) DEFAULT NULL,
  `plot_area_project` varchar(100) DEFAULT NULL,
  `plot_area_project_unit` varchar(15) DEFAULT NULL,
  `commencement` date DEFAULT NULL,
  `vastu` varchar(50) DEFAULT NULL,
  `furnished_status` varchar(500) DEFAULT NULL,
  `interior_details` varchar(500) DEFAULT NULL,
  `shed_area` varchar(120) DEFAULT NULL,
  `shed_area_unit` varchar(15) DEFAULT NULL,
  `electricity_connection` varchar(10) DEFAULT NULL,
  `crane_facility` varchar(10) DEFAULT NULL,
  `etp` varchar(5) DEFAULT NULL,
  `cabins` varchar(10) DEFAULT NULL,
  `workstation` varchar(10) DEFAULT NULL,
  `acs` varchar(10) DEFAULT NULL,
  `shed_height` varchar(200) DEFAULT NULL,
  `shed_height_unit` varchar(10) DEFAULT NULL,
  `no_of_towers` varchar(15) DEFAULT NULL,
  `no_of_houses` varchar(15) DEFAULT NULL,
  `total_floors` varchar(3) DEFAULT NULL,
  `property_on_floor` varchar(3) DEFAULT NULL COMMENT 'Specify property is on which floor',
  `plot_size_range` varchar(64) DEFAULT NULL,
  `plot_size_range_unit` varchar(15) DEFAULT NULL,
  `price_sq_ft` varchar(100) DEFAULT NULL,
  `construction_facility` tinyint(4) DEFAULT NULL COMMENT '1 = Yes, 2 = No',
  `width_of_road_facing_plot` varchar(50) DEFAULT NULL,
  `is_corner_plot` tinyint(1) DEFAULT NULL,
  `is_corner_shop` tinyint(1) DEFAULT NULL,
  `is_main_road_facing` tinyint(1) DEFAULT NULL,
  `personal_washroom` tinyint(1) DEFAULT NULL,
  `cafeteria` tinyint(1) DEFAULT NULL,
  `covered_area` varchar(15) DEFAULT NULL,
  `covered_area_unit` varchar(15) DEFAULT NULL,
  `width_of_enterance` varchar(45) DEFAULT NULL,
  `road_approach` varchar(50) DEFAULT NULL,
  `road1_width` int(10) DEFAULT NULL,
  `road1_width_unit` varchar(20) DEFAULT NULL,
  `road2_width` int(10) DEFAULT NULL,
  `road2_width_unit` varchar(20) DEFAULT NULL,
  `road_width` int(10) DEFAULT NULL,
  `road_width_unit` varchar(20) DEFAULT NULL,
  `power_capacity` varchar(50) DEFAULT NULL,
  `currently_leased_out` tinyint(1) DEFAULT NULL,
  `assured_returns` tinyint(1) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `usp` varchar(250) DEFAULT NULL,
  `no_of_flats` varchar(100) DEFAULT NULL,
  `length` varchar(100) DEFAULT NULL,
  `width` varchar(100) DEFAULT NULL,
  `frontage` varchar(250) DEFAULT NULL,
  `facing` varchar(250) DEFAULT NULL,
  `ideal_for` varchar(250) DEFAULT NULL,
  `length_width_unit` varchar(15) DEFAULT NULL,
  `frontage_unit` varchar(15) DEFAULT NULL,
  `payment_terms` varchar(250) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_property_features`
--

INSERT INTO `tbl_property_features` (`id`, `property_id`, `bedrooms`, `bathrooms`, `balconies`, `foyer_area`, `store_room`, `pooja_room`, `study_room`, `parking_area`, `no_of_parking`, `open_sides`, `total_unit`, `servant_room`, `area_covered`, `area_covered_unit`, `sb_area`, `sb_area_unit`, `carpet_area`, `carpet_area_unit`, `built_area`, `built_area_unit`, `plot_area`, `plot_area_unit`, `plot_area_project`, `plot_area_project_unit`, `commencement`, `vastu`, `furnished_status`, `interior_details`, `shed_area`, `shed_area_unit`, `electricity_connection`, `crane_facility`, `etp`, `cabins`, `workstation`, `acs`, `shed_height`, `shed_height_unit`, `no_of_towers`, `no_of_houses`, `total_floors`, `property_on_floor`, `plot_size_range`, `plot_size_range_unit`, `price_sq_ft`, `construction_facility`, `width_of_road_facing_plot`, `is_corner_plot`, `is_corner_shop`, `is_main_road_facing`, `personal_washroom`, `cafeteria`, `covered_area`, `covered_area_unit`, `width_of_enterance`, `road_approach`, `road1_width`, `road1_width_unit`, `road2_width`, `road2_width_unit`, `road_width`, `road_width_unit`, `power_capacity`, `currently_leased_out`, `assured_returns`, `total_price`, `usp`, `no_of_flats`, `length`, `width`, `frontage`, `facing`, `ideal_for`, `length_width_unit`, `frontage_unit`, `payment_terms`, `created_at`, `updated_at`) VALUES
(7, 1, '1', '1', '1', '12', '1', '1', '1', '121', NULL, NULL, NULL, '1', 12, 'sq_yrd', 12, 'sq_yrd', '12', 'sq_yrd', 12, 'sq_yrd', '', '', NULL, NULL, NULL, 'yes', NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, '', '', '12', NULL, NULL, '', NULL, NULL, '12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-01 11:43:42', '2020-12-07 15:56:34'),
(8, 1, '1', '1', '1', '1', '1', '1', '1', '1', NULL, NULL, NULL, '1', 1, 'sq_yrd', 1, 'sq_yrd', '1', 'sq_yrd', 1, 'sq_yrd', '', '', NULL, NULL, NULL, 'yes', NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, '', '', '1', NULL, NULL, '', NULL, NULL, '1112212', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-01 11:44:12', '2020-12-07 16:52:20'),
(10, 2, '2', '1', '1', '1', '1', '1', '1', '1', NULL, NULL, NULL, '1', 15000, 'sq_yrd', 1200, NULL, '12000', NULL, 15000, NULL, '', '', NULL, NULL, NULL, 'yes', NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, '', '', '1', NULL, NULL, '', NULL, NULL, '1500000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-07 11:38:47', '2020-12-07 11:38:47');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_property_images`
--

CREATE TABLE `tbl_property_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `attachment_id` int(11) DEFAULT NULL,
  `property_id` int(11) UNSIGNED NOT NULL,
  `image_name` varchar(250) NOT NULL DEFAULT '',
  `image_title` varchar(100) NOT NULL DEFAULT '',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_covered` tinyint(1) NOT NULL DEFAULT '0',
  `image_type` varchar(100) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_property_images`
--

INSERT INTO `tbl_property_images` (`id`, `attachment_id`, `property_id`, `image_name`, `image_title`, `is_featured`, `is_covered`, `image_type`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'images/propertyImages/0CWDju5faTbVvM9N0MMe8LzenG898S6NKTd9WnOj.jpeg', '', 0, 0, 'layout_diagrams', '2020-11-28 07:37:25', '2020-11-28 07:37:25'),
(2, NULL, 1, 'images/propertyImages/vSK8PirNDPxpFF8eDAdtuQhoECf9t4DOajbKeXVm.jpeg', '', 0, 0, 'layout_diagrams', '2020-11-28 07:37:25', '2020-11-28 07:37:25'),
(3, NULL, 2, 'images/propertyImages/l0mwNrqNaLXFP0BSVhwY09N5q3HGg9CRLqZ1L1jL.jpeg', '', 0, 0, 'layout_diagrams', '2020-11-28 07:50:09', '2020-11-28 07:50:09'),
(5, NULL, 2, 'images/propertyImages/EfEzWr800ql4GyclxfpQ9OeKWzUb75KrEdYl7v54.jpeg', '', 0, 0, 'layout_diagrams', '2020-11-29 23:40:27', '2020-11-29 23:40:27'),
(6, NULL, 2, 'images/propertyImages/Gad8LcBBYVOWca1bv4KwGy1mj1bNULWUXQmDjpYe.jpeg', '', 0, 0, 'layout_diagrams', '2020-11-29 23:40:27', '2020-11-29 23:40:27'),
(7, NULL, 2, 'images/propertyImages/b2cUtslmDI7KzFbsVBb5uWu0XXSndYxHVbPxS9eK.jpeg', '', 0, 0, 'layout_diagrams', '2020-11-29 23:43:49', '2020-11-29 23:43:49');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_property_metas`
--

CREATE TABLE `tbl_property_metas` (
  `id` int(11) NOT NULL,
  `property_id` int(11) UNSIGNED NOT NULL,
  `meta_key` varchar(100) NOT NULL DEFAULT '',
  `meta_value` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_property_metas`
--

INSERT INTO `tbl_property_metas` (`id`, `property_id`, `meta_key`, `meta_value`, `created_at`, `updated_at`) VALUES
(103, 1, 'land_zone', 'no', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(104, 1, 'no_of_owners', 'no', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(105, 1, 'land_location', 'no', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(106, 1, 'good_for', 'no', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(107, 1, 'living_room_area', '12', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(108, 1, 'living_room_balcony', '12', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(109, 1, 'living_room_bathroom', '12', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(110, 1, 'dining_attached_with_living_room', 'yes', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(111, 1, 'dining_attached_with_kitchen', 'yes', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(112, 1, 'seperate_dining', '12', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(113, 1, 'kitchen_area', '12', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(114, 1, 'kitchen_wash_area', '12', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(115, 1, 'bedroom_area_1', '12', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(116, 1, 'bedroom_balcony_1', '12', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(117, 1, 'bedroom_bathroom_1', '12', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(118, 1, 'bedroom_bathroom_dressing_space_1', '12', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(119, 1, 'master_bedroom', '1', '2020-12-01 06:13:42', '2020-12-01 17:13:42'),
(158, 2, 'land_zone', 'no', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(159, 2, 'no_of_owners', 'no', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(160, 2, 'land_location', 'no', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(161, 2, 'good_for', 'no', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(162, 2, 'living_room_area', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(163, 2, 'living_room_balcony', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(164, 2, 'living_room_bathroom', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(165, 2, 'dining_attached_with_living_room', 'yes', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(166, 2, 'dining_attached_with_kitchen', 'yes', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(167, 2, 'seperate_dining', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(168, 2, 'kitchen_area', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(169, 2, 'kitchen_wash_area', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(170, 2, 'bedroom_area_1', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(171, 2, 'bedroom_balcony_1', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(172, 2, 'bedroom_bathroom_1', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(173, 2, 'bedroom_bathroom_dressing_space_1', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(174, 2, 'master_bedroom', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(175, 2, 'bedroom_area_2', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(176, 2, 'bedroom_balcony_2', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(177, 2, 'bedroom_bathroom_2', '1', '2020-12-07 06:08:47', '2020-12-07 17:08:47'),
(178, 2, 'bedroom_bathroom_dressing_space_2', 'no', '2020-12-07 06:08:47', '2020-12-07 17:08:47');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_property_sub_category`
--

CREATE TABLE `tbl_property_sub_category` (
  `id` tinyint(4) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `slug` varchar(50) NOT NULL DEFAULT '',
  `cat_id` int(11) UNSIGNED NOT NULL,
  `menu_order` tinyint(4) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '1' COMMENT '0=InActive, 1=Active, 2=Deleted',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_property_sub_category`
--

INSERT INTO `tbl_property_sub_category` (`id`, `name`, `slug`, `cat_id`, `menu_order`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Bunglows', 'bunglow', 1, 6, '2', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(5, 'Office', 'office', 2, 13, '1', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(8, 'Duplex', 'duplex', 1, 4, '2', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(9, 'House / Villa', 'villas', 1, 7, '1', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(10, 'Open Plots / Weekend Home', 'open_plots', 1, 9, '1', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(11, 'Shop', 'shop', 2, 11, '1', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(12, 'Showroom', 'showroom', 2, 12, '1', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(13, 'Pent House', 'pent_house', 1, 2, '2', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(14, 'Flats', 'apartment', 1, 1, '1', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(16, 'Industrial Plot', 'plot', 3, 14, '1', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(17, 'Industrial Shed', 'shed', 3, 15, '1', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(20, 'Land', 'land', 4, 15, '1', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(22, 'Godown/Warehouse', 'godown_warehouse', 3, 16, '1', '2017-09-24 04:57:34', '2017-09-24 04:57:45'),
(23, 'NA', 'na', 4, 17, '1', '2019-08-06 01:43:32', '2019-08-06 01:43:32'),
(24, 'Old Tenure', 'old_tenure', 4, 18, '1', '2019-08-06 01:43:32', '2019-08-06 01:43:32'),
(25, 'New Tenure', 'new_tenure', 4, 19, '1', '2019-08-06 01:43:32', '2019-08-06 01:43:32'),
(26, 'Other', 'other', 4, 20, '1', '2019-08-06 01:43:32', '2019-08-06 01:43:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_site_loans`
--

CREATE TABLE `tbl_site_loans` (
  `id` int(11) UNSIGNED NOT NULL,
  `site_id` int(11) UNSIGNED NOT NULL,
  `bank_id` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_site_loans`
--

INSERT INTO `tbl_site_loans` (`id`, `site_id`, `bank_id`, `created_at`) VALUES
(7, 1, 4, '2020-11-28 08:19:30'),
(8, 1, 5, '2020-11-28 08:19:30'),
(9, 2, 4, '2020-11-29 22:57:20'),
(10, 3, 4, '2020-11-29 22:57:48');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_site_marketers`
--

CREATE TABLE `tbl_site_marketers` (
  `id` int(11) UNSIGNED NOT NULL,
  `site_id` int(11) UNSIGNED NOT NULL,
  `person_name` varchar(50) DEFAULT '',
  `person_phone` varchar(15) DEFAULT '',
  `person_email` varchar(50) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_site_metas`
--

CREATE TABLE `tbl_site_metas` (
  `id` int(11) UNSIGNED NOT NULL,
  `site_id` int(11) UNSIGNED NOT NULL,
  `meta_key` varchar(200) NOT NULL DEFAULT '',
  `meta_value` text NOT NULL,
  `meta_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=ameneties, 2=specifications',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_site_metas`
--

INSERT INTO `tbl_site_metas` (`id`, `site_id`, `meta_key`, `meta_value`, `meta_type`, `created_at`) VALUES
(100, 1, 'lift', '1', 1, '2020-11-28 13:49:30'),
(101, 1, 'garden', '1', 1, '2020-11-28 13:49:30'),
(102, 1, 'library', '1', 1, '2020-11-28 13:49:30'),
(103, 1, 'internet', '1', 1, '2020-11-28 13:49:30'),
(104, 1, 'infinity_swimming_pool', '1', 1, '2020-11-28 13:49:30'),
(105, 1, 'volleyball', '1', 1, '2020-11-28 13:49:30'),
(106, 1, 'butterfly_park', '1', 1, '2020-11-28 13:49:30'),
(107, 1, 'temple', '1', 1, '2020-11-28 13:49:30'),
(108, 1, 'specification_flooring_balcony', 'test', 2, '2020-11-28 13:49:30'),
(109, 1, 'specification_flooring_bathroom', 'test', 2, '2020-11-28 13:49:30'),
(110, 1, 'specification_flooring_livingroom', 'test', 2, '2020-11-28 13:49:30'),
(111, 1, 'specification_flooring_master_bedroom', 'test', 2, '2020-11-28 13:49:30'),
(112, 1, 'specification_flooring_kitchen', 'test', 2, '2020-11-28 13:49:30'),
(113, 1, 'specification_flooring_bedroom', 'test', 2, '2020-11-28 13:49:30'),
(114, 1, 'specification_flooring_terrace', 'test', 2, '2020-11-28 13:49:30'),
(115, 1, 'specification_fitting_doors', '11', 2, '2020-11-28 13:49:30'),
(116, 1, 'specification_fitting_electrical', '11', 2, '2020-11-28 13:49:30'),
(117, 1, 'specification_fitting_bathroom', '11', 2, '2020-11-28 13:49:30'),
(118, 1, 'specification_fitting_sink', '11', 2, '2020-11-28 13:49:30'),
(119, 1, 'specification_fitting_kitchen_platform', '11', 2, '2020-11-28 13:49:30'),
(120, 1, 'specification_fitting_windows', '11', 2, '2020-11-28 13:49:30'),
(121, 1, 'specification_fitting_toilet', '11', 2, '2020-11-28 13:49:30'),
(122, 1, 'specification_walls_exterior', '22', 2, '2020-11-28 13:49:30'),
(123, 1, 'specification_walls_kitchen', '22', 2, '2020-11-28 13:49:30'),
(124, 1, 'specification_walls_balcony', '22', 2, '2020-11-28 13:49:30'),
(125, 1, 'specification_walls_interior', '22', 2, '2020-11-28 13:49:30'),
(126, 1, 'specification_walls_toilet', '22', 2, '2020-11-28 13:49:30'),
(127, 1, 'usp_one', '33', 2, '2020-11-28 13:49:30'),
(128, 1, 'usp_two', '33', 2, '2020-11-28 13:49:30'),
(129, 1, 'usp_three', '33', 2, '2020-11-28 13:49:30'),
(130, 1, 'usp_four', '33', 2, '2020-11-28 13:49:30'),
(131, 1, 'usp_five', '33', 2, '2020-11-28 13:49:30'),
(132, 1, 'usp_six', '33', 2, '2020-11-28 13:49:30'),
(133, 2, 'lift', '1', 1, '2020-11-30 04:27:19'),
(134, 2, 'garden', '1', 1, '2020-11-30 04:27:19'),
(135, 2, 'library', '1', 1, '2020-11-30 04:27:19'),
(136, 2, 'internet', '1', 1, '2020-11-30 04:27:19'),
(137, 2, 'infinity_swimming_pool', '1', 1, '2020-11-30 04:27:19'),
(138, 2, 'volleyball', '1', 1, '2020-11-30 04:27:19'),
(139, 2, 'butterfly_park', '1', 1, '2020-11-30 04:27:19'),
(140, 2, 'temple', '1', 1, '2020-11-30 04:27:19'),
(141, 3, 'lift', '1', 1, '2020-11-30 04:27:48'),
(142, 3, 'garden', '1', 1, '2020-11-30 04:27:48'),
(143, 3, 'library', '1', 1, '2020-11-30 04:27:48'),
(144, 3, 'internet', '1', 1, '2020-11-30 04:27:48'),
(145, 3, 'infinity_swimming_pool', '1', 1, '2020-11-30 04:27:48'),
(146, 3, 'volleyball', '1', 1, '2020-11-30 04:27:48'),
(147, 3, 'butterfly_park', '1', 1, '2020-11-30 04:27:48'),
(148, 3, 'temple', '1', 1, '2020-11-30 04:27:48');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_site_offers`
--

CREATE TABLE `tbl_site_offers` (
  `id` int(11) UNSIGNED NOT NULL,
  `site_id` int(11) UNSIGNED NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `option_name` varchar(155) DEFAULT NULL,
  `final_price` varchar(155) NOT NULL,
  `govt_subcidy_price` varchar(100) DEFAULT NULL,
  `basic_cost` varchar(100) DEFAULT NULL,
  `reg_cost` varchar(100) DEFAULT NULL,
  `emi_cost` varchar(100) DEFAULT NULL,
  `is_furniture` int(11) DEFAULT NULL,
  `furniture_components` varchar(100) DEFAULT NULL,
  `is_registration` int(11) DEFAULT NULL,
  `registration_cost` varchar(100) DEFAULT NULL,
  `stamp_cost` varchar(100) DEFAULT NULL,
  `gst_cost` varchar(100) DEFAULT NULL,
  `development_cost` varchar(100) DEFAULT NULL,
  `other_expense` varchar(100) DEFAULT NULL,
  `kitchen_components` int(11) DEFAULT NULL,
  `kitchen_cost` varchar(100) DEFAULT NULL,
  `platform_cost` varchar(100) DEFAULT NULL,
  `furniture_cost` varchar(100) DEFAULT NULL,
  `unit_left` varchar(100) DEFAULT NULL,
  `days_left` varchar(11) NOT NULL,
  `interest_subvention` varchar(110) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_site_offers`
--

INSERT INTO `tbl_site_offers` (`id`, `site_id`, `property_id`, `option_name`, `final_price`, `govt_subcidy_price`, `basic_cost`, `reg_cost`, `emi_cost`, `is_furniture`, `furniture_components`, `is_registration`, `registration_cost`, `stamp_cost`, `gst_cost`, `development_cost`, `other_expense`, `kitchen_components`, `kitchen_cost`, `platform_cost`, `furniture_cost`, `unit_left`, `days_left`, `interest_subvention`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 'test', '1000', '1000', '1000', '1000', '1000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1000', '1000', '1000', '1000', '2020-12-02 07:05:40', '2020-12-02 07:05:40'),
(3, 1, 2, 'Xyz', '2000', '2000', '2000', '2000', '2000', 1, '[\"1\",\"2\",\"5\",\"9\"]', 1, '12000', '12000', '12000', '11120000', '14400000', 1, '120000', '140000', '2000', '2000', '2000', '2000', '2020-12-02 07:05:59', '2020-12-04 07:36:33'),
(4, 1, 1, 'test', '12000', '4800', '1500', '12000', '1500', 1, '[\"1\",\"3\",\"4\",\"5\",\"7\",\"8\",\"9\"]', 1, '7800', '15000', '15000', '14000', '14000', 1, '45000', '45000', '45000', '2500', '10', '14000', '2020-12-04 05:29:42', '2020-12-04 06:14:41'),
(5, 1, 2, 'n test', '2300000', '1000000', '1000000', '100000', '140000', NULL, NULL, 1, '150000', '142000', '5400', '65000', '7800', 1, '7800', '1400', NULL, '14', '14', NULL, '2020-12-04 06:44:46', '2020-12-04 06:44:46');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_site_payments`
--

CREATE TABLE `tbl_site_payments` (
  `id` int(11) UNSIGNED NOT NULL,
  `site_id` int(11) UNSIGNED NOT NULL,
  `site_city_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `package_id` int(11) UNSIGNED NOT NULL,
  `package_cost` double(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT 'cost of package',
  `discount_percentage` float(5,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT 'if discount applied then percentage of discount',
  `subscription_duration_month` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'for how many month package taken',
  `gst_no` varchar(50) DEFAULT NULL COMMENT 'Builder GST Registration No',
  `cgst_total` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '9% country level gst after discount on invoice amount',
  `sgst_total` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '9% state level gst after discount on invoice amount',
  `invoice_amount` double(10,2) UNSIGNED NOT NULL COMMENT 'total invoice amount package cost * subscription duration month',
  `invoice_name` varchar(20) DEFAULT NULL COMMENT 'invoice name',
  `payment_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=none, 1=cash, 2=cheque',
  `paid_amount` double UNSIGNED NOT NULL,
  `paid_by` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `cash_payment_date` datetime DEFAULT NULL,
  `cheque_pay_date` datetime DEFAULT NULL,
  `name_on_cheque` varchar(255) DEFAULT NULL,
  `cheque_number` varchar(255) DEFAULT NULL,
  `cheque_image` varchar(255) DEFAULT NULL,
  `subscription_duration_from` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `subscription_duration_to` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_microsite` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=not enabled, 1=enabled',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=pending verification, 1=active, 2=rejected 3=inactive',
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `cron_update_time` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_site_visit_enquiry`
--

CREATE TABLE `tbl_site_visit_enquiry` (
  `id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `visitor` varchar(10) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `email` varchar(70) NOT NULL,
  `status` enum('1','2') DEFAULT '1' COMMENT '1=pending, 2=completed',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_states`
--

CREATE TABLE `tbl_states` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `slug` varchar(50) NOT NULL DEFAULT '',
  `country_id` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_states`
--

INSERT INTO `tbl_states` (`id`, `name`, `slug`, `country_id`, `created_at`, `updated_at`) VALUES
(5, 'Gujarat', 'gujarat', 1, '2014-11-14 01:04:16', '2014-11-14 01:04:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_wish_list`
--

CREATE TABLE `tbl_wish_list` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `site_id` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_image_upload`
--

CREATE TABLE `tmp_image_upload` (
  `id` int(11) UNSIGNED NOT NULL,
  `image_name` varchar(300) NOT NULL DEFAULT '',
  `image_category` varchar(100) NOT NULL DEFAULT '',
  `extra_params` varchar(1000) DEFAULT NULL,
  `datecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tmp_image_upload`
--

INSERT INTO `tmp_image_upload` (`id`, `image_name`, `image_category`, `extra_params`, `datecreated`) VALUES
(3, 'images/oadAIYw9YKgvHzOw1KyW6XuGdVeWVG1kdBXYzbkI.jpeg', 'layout_diagrams', NULL, '2020-11-29 23:31:00'),
(4, 'images/5mY4URKAFPuNi61CSsrimR6L5UXdyeLuVu2TJ8qB.jpeg', 'layout_diagrams', NULL, '2020-11-29 23:31:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_verification_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_verified` int(5) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `sms_verification_code`, `sms_verified`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin Jay', 'admin@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$AxU7KCiLWsztaPB0iX8eKuNuNpZJ9ri4b4TMOKqbSLstuvU4zJdMe', NULL, '2020-11-20 07:57:08', '2020-11-20 07:57:08'),
(2, 'subadmin', 'subadmin@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$oQeieSz.4zMciE.s5q5JEeu5m1yUOBkD8uZjSRhmUJOS0Mtm0DyEK', NULL, '2020-11-20 09:15:46', '2020-11-20 09:18:40'),
(3, 'user', 'user@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$bT6spZAopSmJsC19TF41luJynqop/nmYL.Al4hCBiAwmO/Jc6gVjq', NULL, '2020-11-22 23:10:34', '2020-11-22 23:10:34'),
(4, 'abc', 'abc@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$bT6spZAopSmJsC19TF41luJynqop/nmYL.Al4hCBiAwmO/Jc6gVjq', NULL, '2020-11-22 23:10:34', '2020-11-22 23:10:34'),
(5, 'broker', 'broker@gmail.com', NULL, '142959', 0, NULL, '$2y$10$zbeIyQ0oTsY7YMR6diGOXOPf7hOLC04Xttqhi5OYRTxUT.ZEAc6GW', NULL, '2020-12-07 06:48:12', '2020-12-07 07:33:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `tbl_areas`
--
ALTER TABLE `tbl_areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_banks`
--
ALTER TABLE `tbl_banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_bookings`
--
ALTER TABLE `tbl_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_booking_cash_amount`
--
ALTER TABLE `tbl_booking_cash_amount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_booking_direct_amount`
--
ALTER TABLE `tbl_booking_direct_amount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_booking_loan_amount`
--
ALTER TABLE `tbl_booking_loan_amount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_booking_total_amount`
--
ALTER TABLE `tbl_booking_total_amount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_builder_sites`
--
ALTER TABLE `tbl_builder_sites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_builder_sites_images`
--
ALTER TABLE `tbl_builder_sites_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_cities`
--
ALTER TABLE `tbl_cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_countries`
--
ALTER TABLE `tbl_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_furniture_detail`
--
ALTER TABLE `tbl_furniture_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_properties`
--
ALTER TABLE `tbl_properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_property_category`
--
ALTER TABLE `tbl_property_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_property_features`
--
ALTER TABLE `tbl_property_features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_property_images`
--
ALTER TABLE `tbl_property_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_property_metas`
--
ALTER TABLE `tbl_property_metas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_property_sub_category`
--
ALTER TABLE `tbl_property_sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_site_loans`
--
ALTER TABLE `tbl_site_loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_site_marketers`
--
ALTER TABLE `tbl_site_marketers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_site_metas`
--
ALTER TABLE `tbl_site_metas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_site_offers`
--
ALTER TABLE `tbl_site_offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_site_payments`
--
ALTER TABLE `tbl_site_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_site_visit_enquiry`
--
ALTER TABLE `tbl_site_visit_enquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_states`
--
ALTER TABLE `tbl_states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_wish_list`
--
ALTER TABLE `tbl_wish_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tmp_image_upload`
--
ALTER TABLE `tmp_image_upload`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_areas`
--
ALTER TABLE `tbl_areas`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=478;

--
-- AUTO_INCREMENT for table `tbl_banks`
--
ALTER TABLE `tbl_banks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_bookings`
--
ALTER TABLE `tbl_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_booking_cash_amount`
--
ALTER TABLE `tbl_booking_cash_amount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_booking_direct_amount`
--
ALTER TABLE `tbl_booking_direct_amount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_booking_loan_amount`
--
ALTER TABLE `tbl_booking_loan_amount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_booking_total_amount`
--
ALTER TABLE `tbl_booking_total_amount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_builder_sites`
--
ALTER TABLE `tbl_builder_sites`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_builder_sites_images`
--
ALTER TABLE `tbl_builder_sites_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_cities`
--
ALTER TABLE `tbl_cities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_countries`
--
ALTER TABLE `tbl_countries`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_furniture_detail`
--
ALTER TABLE `tbl_furniture_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_properties`
--
ALTER TABLE `tbl_properties`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_property_category`
--
ALTER TABLE `tbl_property_category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_property_features`
--
ALTER TABLE `tbl_property_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_property_images`
--
ALTER TABLE `tbl_property_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_property_metas`
--
ALTER TABLE `tbl_property_metas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `tbl_property_sub_category`
--
ALTER TABLE `tbl_property_sub_category`
  MODIFY `id` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_site_loans`
--
ALTER TABLE `tbl_site_loans`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_site_marketers`
--
ALTER TABLE `tbl_site_marketers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_site_metas`
--
ALTER TABLE `tbl_site_metas`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `tbl_site_offers`
--
ALTER TABLE `tbl_site_offers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_site_payments`
--
ALTER TABLE `tbl_site_payments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_site_visit_enquiry`
--
ALTER TABLE `tbl_site_visit_enquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_states`
--
ALTER TABLE `tbl_states`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_wish_list`
--
ALTER TABLE `tbl_wish_list`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tmp_image_upload`
--
ALTER TABLE `tmp_image_upload`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
