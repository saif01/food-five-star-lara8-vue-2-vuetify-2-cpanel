-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2022 at 07:41 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `5star`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `allow` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `deleted_temp` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `message`, `image`, `start`, `end`, `allow`, `status`, `deleted_temp`, `deleted_by`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Test Announcement', 'A4WyZahE1657000953.jpeg', '2022-07-26', '2022-07-29', NULL, 1, NULL, NULL, 25, '2022-07-05 00:02:33', '2022-07-25 23:22:24'),
(3, 'banner 2', 'LSmKDmW61657009730.jpeg', '2022-08-30', '2022-08-31', 'owner', 1, NULL, NULL, 25, '2022-07-05 02:28:50', '2022-08-30 01:14:04'),
(4, NULL, 'RtiuVjJJ1659850830.png', '2022-08-30', '2022-08-31', 'operator', 1, NULL, NULL, 25, '2022-08-06 23:40:33', '2022-08-29 21:29:10'),
(5, NULL, 'IYuW9d541663649021.jpeg', '2022-09-12', '2022-09-14', 'all', 1, 1, 25, 25, '2022-09-20 04:43:42', '2022-09-20 04:43:45');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_templates`
--

CREATE TABLE `message_templates` (
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id` bigint(20) UNSIGNED NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `deleted_temp` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_templates`
--

INSERT INTO `message_templates` (`title`, `id`, `message`, `status`, `deleted_temp`, `deleted_by`, `created_by`, `created_at`, `updated_at`) VALUES
('reset-password', 1, 'Your security code is: {otp} for your outlet ({outlet_name}, shop {outlet_address}). Thank you.', 1, NULL, NULL, 25, '2022-09-01 23:47:04', '2022-09-02 02:40:32'),
('order-confirmation', 2, 'Dear Sir, The order for \"{outlet_name}\" has been finalized on {date}. Your total amount is {amount} Taka. Thank you.', NULL, NULL, NULL, 25, '2022-09-02 04:46:42', '2022-09-12 09:46:13'),
('daily-sale-owner', 3, 'Your total sale for {date} is: \" {amount} \" for your shop ({outlet_name}). Thank you.', 1, NULL, NULL, 25, '2022-09-04 02:23:29', '2022-09-04 02:42:07'),
('test-value', 4, '{amount} {date} {outlet_name}', 1, 1, 25, 25, '2022-09-20 04:44:09', '2022-09-20 04:45:42');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_04_19_061036_create_food_users_table', 1),
(6, '2022_04_21_084307_create_food_types_table', 1),
(7, '2022_04_21_084410_create_food_alls_table', 1),
(10, '2022_05_28_112447_create_orders_table', 2),
(11, '2014_10_12_000000_create_users_table', 3),
(12, '2022_06_05_030247_create_outlets_table', 3),
(15, '2022_06_05_030634_create_products_table', 3),
(16, '2022_06_05_030538_create_product_types_table', 4),
(17, '2022_06_13_051859_create_zones_table', 5),
(18, '2022_06_13_054054_create_roles_table', 6),
(19, '2022_06_13_060950_create_user_roles_table', 7),
(20, '2022_06_16_042118_create_user_login_logs_table', 8),
(22, '2022_06_18_114605_create_admin_orders_table', 9),
(23, '2022_06_23_115438_create_orders_table', 10),
(24, '2022_06_23_115615_create_order_summaries_table', 10),
(25, '2022_06_23_162537_create_user_cvcodes_table', 11),
(30, '2022_06_28_041130_create_sale_product_details_table', 13),
(31, '2022_07_03_105302_create_send_sms_table', 14),
(32, '2022_06_05_030437_create_sales_table', 15),
(33, '2022_06_25_063637_create_sales_summuries_table', 15),
(34, '2022_07_04_103526_create_user_languages_table', 16),
(35, '2022_07_05_053901_create_announcements_table', 17),
(36, '2022_07_05_064912_create_order_product_details_table', 18),
(37, '2022_07_06_032329_create_otp_sends_table', 19),
(38, '2022_07_18_062833_create_user_zones_table', 20),
(39, '2022_07_19_054914_create_product_sales_table', 21),
(40, '2022_07_19_054950_create_product_orders_table', 21),
(41, '2022_08_13_062532_create_product_order_types_table', 22),
(42, '2022_09_01_104914_create_message_templates_table', 23),
(43, '2022_09_05_111750_create_smartsoft_invoices_table', 24),
(45, '2022_09_06_042609_create_schedule_tables_table', 25),
(46, '2018_08_08_100000_create_telescope_entries_table', 26),
(47, '2022_09_16_173138_create_tests_table', 27),
(51, '2022_09_19_101110_create_smartsoft_outlets_table', 28);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `outlet_id` int(11) DEFAULT NULL,
  `cv_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` double(8,2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `status_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_summaries`
--

CREATE TABLE `order_summaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_date` date DEFAULT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cv_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_price` double(8,2) DEFAULT NULL,
  `payment_amount` double(8,2) DEFAULT NULL,
  `payment_prove` datetime DEFAULT NULL,
  `payment_doc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `manager_approve` datetime DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `admin_approve` datetime DEFAULT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_by` int(11) DEFAULT NULL,
  `invoice_time` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `status_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_sends`
--

CREATE TABLE `otp_sends` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outlets`
--

CREATE TABLE `outlets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cv_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `officer_id` int(11) DEFAULT NULL,
  `shop_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_mobile_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `division` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `deleted_temp` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outlets`
--

INSERT INTO `outlets` (`id`, `cv_code`, `zone_id`, `manager_id`, `officer_id`, `shop_name`, `shop_address`, `owner`, `owner_mobile`, `owner_mobile_2`, `city`, `district`, `division`, `latitude`, `longitude`, `status`, `deleted_temp`, `deleted_by`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '183409', 1, 571, 572, 'Sigma Food', 'House-19,Road-14,sec-14,Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.86662040', '90.38870840', 1, NULL, NULL, NULL, NULL, NULL),
(2, '186329', 1, 571, 572, 'Janani  Food  Corner', 'House-36,Road-01,sec-12,Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.86966720', '90.38336370', 1, NULL, NULL, NULL, NULL, NULL),
(3, '182189', 1, 571, 572, 'Angel Food - 2', 'House-12,Garib-e-nawaz avenue,sec-11,Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.87573350', '90.39085280', 1, NULL, NULL, NULL, NULL, NULL),
(4, '183369', 1, 571, 572, 'Fashion Paradise', 'Road-20,shamukhdum avenue,sec-11,uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.87461430', '90.38420830', 1, NULL, NULL, NULL, NULL, NULL),
(5, '187349', 1, 571, 572, 'Himu Foods', 'House-37,Garib-e-nawaz avenue,sec-11,Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.87854700', '90.39074270', 1, NULL, NULL, NULL, NULL, NULL),
(6, '932119', 1, 571, 572, 'Arian Food - 2', 'House-32,Natore Tower,Road-2,sec-3,uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.86417920', '90.39939460', 1, NULL, NULL, NULL, NULL, NULL),
(7, '186889', 1, 571, 572, 'Ruha Food Corner-2', 'House-41,Road-6,Sec-13.uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.87030620', '90.38785830', 1, NULL, NULL, NULL, NULL, NULL),
(8, '181709', 1, 571, 572, 'Step Inn', 'House-9,R-1/A,Sec-9,Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.87485190', '90.39965650', 1, NULL, NULL, NULL, NULL, NULL),
(9, '189989', 1, 571, 572, 'Arian Food', 'House-51,Road-shamukdum avenue,sec-12,uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.87070030', '90.38383590', 1, NULL, NULL, NULL, NULL, NULL),
(10, '931189', 1, 571, 572, 'Fantasi Fried Chicken', 'Nurjahan mention,Jamgora', NULL, NULL, NULL, NULL, NULL, NULL, '23.93722420', '90.29582390', 1, NULL, NULL, NULL, NULL, NULL),
(11, '931309', 1, 571, 572, 'Navan Fast Food', 'Kamarpara,Road-16,sec-10,Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.89079180', '90.38710420', 1, NULL, NULL, NULL, NULL, NULL),
(12, '931629', 1, 571, 572, 'Tasty Chicken Lovers', 'Diyabari,Bottola,sec-15.Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.86725890', '90.37049400', 1, NULL, NULL, NULL, NULL, NULL),
(13, '931979', 1, 571, 572, 'Tasty Chicken Lover-2', 'House-50,Road-14,sec-11,Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.87750860', '90.38729000', 1, NULL, NULL, NULL, NULL, NULL),
(14, '932499', 1, 571, 572, 'Vega Food', 'Ranavhola Main Road,Turag,Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.88286970', '90.38628480', 1, NULL, NULL, NULL, NULL, NULL),
(15, '931449', 1, 571, 572, 'Amrin Food', '256,West Rampura,Dit Road,1219', NULL, NULL, NULL, NULL, NULL, NULL, '23.75951360', '90.41828490', 1, NULL, NULL, NULL, NULL, NULL),
(16, '182979', 1, 571, 572, 'Goodys', 'House-77,Road-5,Block-c,West Rampura', NULL, NULL, NULL, NULL, NULL, NULL, '23.76427990', '90.41360120', 1, NULL, NULL, NULL, NULL, NULL),
(17, '184189', 1, 571, 572, 'Hoque Foods', '253,Boro Moghbazar,Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.74908280', '90.40361180', 1, NULL, NULL, NULL, NULL, NULL),
(18, '188929', 1, 571, 572, 'Mehzabin Foods', '587,Elephant Road,Modhubag,Mogbazar', NULL, NULL, NULL, NULL, NULL, NULL, '23.75597280', '90.41127440', 1, NULL, NULL, NULL, NULL, NULL),
(19, '931779', 1, 571, 572, 'Fried and Fresh', '639/A,Boro Moghbazar,Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.75264740', '90.40565920', 1, NULL, NULL, NULL, NULL, NULL),
(20, '932139', 1, 571, 572, 'RTC Snacks', 'Hatirjhil,Modhubag,', NULL, NULL, NULL, NULL, NULL, NULL, '23.76024540', '90.41081300', 1, NULL, NULL, NULL, NULL, NULL),
(21, '932749', 1, 571, 572, 'Safe Serve Food', '252,Sikder Market,Diyabari,Sec-17,Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.84829920', '90.37121880', 1, NULL, NULL, NULL, NULL, NULL),
(22, '185679', 1, 571, 573, 'Uttara  Food', 'House: 27, Road: 5, Sector: 7,\r\nUttara, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.87092360', '90.39996555', 1, NULL, NULL, NULL, NULL, NULL),
(23, '188039', 1, 571, 573, 'Mahi  Food - 2', 'Plot-27, Korom ali market,Askona', NULL, NULL, NULL, NULL, NULL, NULL, '23.85147670', '90.41382448', 1, NULL, NULL, NULL, NULL, NULL),
(24, '183039', 1, 571, 573, 'Brothers Snacks', '161/2A, Khilkhet, Moddo Bazar, Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.83018100', '90.42337800', 1, NULL, NULL, NULL, NULL, NULL),
(25, '187889', 1, 571, 573, 'Sami Food', 'House-11,Sec-3, Robindra Sarani, Azampur, Uttara, Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.86742990', '90.39836820', 1, NULL, NULL, NULL, NULL, NULL),
(26, '185909', 1, 571, 573, 'Mahi  Food', 'H-47, R-27-Se-7, Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.87336000', '90.39661400', 1, NULL, NULL, NULL, NULL, NULL),
(27, '186539', 1, 571, 573, 'Hotel  Amania', 'H-34.R-4.Se-9 Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.87449080', '90.39690290', 1, NULL, NULL, NULL, NULL, NULL),
(28, '182319', 1, 571, 573, 'Acute Food', 'H-2.R-13.Se-6 Azompur', NULL, NULL, NULL, NULL, NULL, NULL, '23.86892940', '90.40157760', 1, NULL, NULL, NULL, NULL, NULL),
(29, '182419', 1, 571, 573, 'Angel Food - 03', 'Shop-103,Brac Market,Azompur,Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.86750000', '90.40656300', 1, NULL, NULL, NULL, NULL, NULL),
(30, '188849', 1, 571, 573, 'Jarra Enterprise', 'H-4, R-1,Se-5 , Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.86454700', '90.39208690', 1, NULL, NULL, NULL, NULL, NULL),
(31, '183199', 1, 571, 573, 'Uttra Snacks ', 'H-13, R-13 ,Sector-4 , Uttara, 1230', NULL, NULL, NULL, NULL, NULL, NULL, '23.86452300', '90.40191700', 1, NULL, NULL, NULL, NULL, NULL),
(32, '185619', 1, 571, 573, 'Sami  Food  Company', 'H-4.R-12 Se-6 Uttara Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.87312500', '90.40028400', 1, NULL, NULL, NULL, NULL, NULL),
(33, '932729', 1, 571, 573, 'Nahar\'s Kitchen', 'H-7 R-3/F.Se-9 Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.87729290', '90.39751900', 1, NULL, NULL, NULL, NULL, NULL),
(34, '930949', 1, 571, 573, 'Brothers Snacks-2', 'H-30, Road-1, Nikunja-2, 1229', NULL, NULL, NULL, NULL, NULL, NULL, '23.82860270', '90.41711390', 1, NULL, NULL, NULL, NULL, NULL),
(35, '931649', 1, 571, 573, 'Brothers Snacks-3', 'H-22, Road-16, Nikunja-2, 1229', NULL, NULL, NULL, NULL, NULL, NULL, '23.83420980', '90.41651480', 1, NULL, NULL, NULL, NULL, NULL),
(36, '931909', 1, 571, 573, 'Sheikh Food', 'Shop no -129, Kosai Bazar Dakkin Khan Road, Dakkin Khan, Uttara-1230', NULL, NULL, NULL, NULL, NULL, NULL, '23.85813790', '90.40835540', 1, NULL, NULL, NULL, NULL, NULL),
(37, '932009', 1, 571, 573, 'A . F Food Corner', '885 Akteruzzman Super Market, Uttarkhan Majar Chowrasta, Dhaka -1230', NULL, NULL, NULL, NULL, NULL, NULL, '23.87073860', '90.42277260', 1, NULL, NULL, NULL, NULL, NULL),
(38, '932049', 1, 571, 573, 'Khilkhet Snacks', 'B/99, Panirpump Mur, Namapara, Khilkhet ', NULL, NULL, NULL, NULL, NULL, NULL, '23.83061810', '90.43006290', 1, NULL, NULL, NULL, NULL, NULL),
(39, '932189', 1, 571, 573, 'Aayat Food', 'Rojob Ali Super Market, Dakkin Khan Bazar, Dakkin Khan, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.86081880', '90.42595200', 1, NULL, NULL, NULL, NULL, NULL),
(40, '932309', 1, 571, 573, 'H S Food', 'Shop no: B-1/A, Asain City , Dakkin Khan, Kawla ,Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.84889570', '90.42410610', 1, NULL, NULL, NULL, NULL, NULL),
(41, '182719', 1, 571, 573, 'Continental Foods ', 'H-14,Pailot market,College get Tongi', NULL, NULL, NULL, NULL, NULL, NULL, '23.90796840', '90.39735230', 1, NULL, NULL, NULL, NULL, NULL),
(42, '200119', 1, 571, 573, 'Modern Kitchen', 'Holding # 119, Rajob Ali market, Station Road, Tongi ', NULL, NULL, NULL, NULL, NULL, NULL, '23.89377780', '90.40394060', 1, NULL, NULL, NULL, NULL, NULL),
(43, '200259', 1, 571, 573, 'Amegose Food', '99,Younus Ali Sharker Road,Modhumita, Tongi, Gazipur.', NULL, NULL, NULL, NULL, NULL, NULL, '23.89016460', '90.40519420', 1, NULL, NULL, NULL, NULL, NULL),
(44, '932759', 1, 571, 573, 'Sayem Food Vug Bilash', 'H-1 ,Road-12,Sector-13, Uttara', NULL, NULL, NULL, NULL, NULL, NULL, '23.87291700', '90.39008240', 1, NULL, NULL, NULL, NULL, NULL),
(45, '187869', 1, 571, 583, 'Nishat Foods (Hungry Point)', 'Holding # Ga 81/C ,Badda Link Road', NULL, NULL, NULL, NULL, NULL, NULL, '23.78070660', '90.42401820', 1, NULL, NULL, NULL, NULL, NULL),
(46, '182139', 1, 571, 583, 'Tummy Yammy', 'House #14  , Road# 108 , Gulshan-2, Near Azad Mosjid', NULL, NULL, NULL, NULL, NULL, NULL, '23.78968310', '90.41879470', 1, NULL, NULL, NULL, NULL, NULL),
(47, '183159', 1, 571, 583, 'Sarder Snacks ', 'Shop # B3 ,Shopping Centre , Gulshan -1,1212', NULL, NULL, NULL, NULL, NULL, NULL, '23.78015901', '90.41636516', 1, NULL, NULL, NULL, NULL, NULL),
(48, '183789', 1, 571, 583, 'Monolova Fried Chicken', 'Shop# 73/2 , Road# Bir Uttam AK Khondokher ,Mohakhali Wireless', NULL, NULL, NULL, NULL, NULL, NULL, '23.78024000', '90.40553000', 1, NULL, NULL, NULL, NULL, NULL),
(49, '184729', 1, 571, 583, 'Tanisha Food Court', 'House #61  , Road# 17 ,Block-C,Banani, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.79319240', '90.40398840', 1, NULL, NULL, NULL, NULL, NULL),
(50, '185499', 1, 571, 583, 'Ananda  Foods', 'House #50  ,Block-C, Road# 11  ,Banani,1213', NULL, NULL, NULL, NULL, NULL, NULL, '23.79116800', '90.40193160', 1, NULL, NULL, NULL, NULL, NULL),
(51, '187259', 1, 571, 583, '786  Foods', 'Plot-160, Bhabon Market,Sho no-11,Gulshan-2, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.79564080', '90.41384350', 1, NULL, NULL, NULL, NULL, NULL),
(52, '187329', 1, 571, 583, 'Chicken Addiction', 'Ka- 29/2  ,Kuril,Progoti Shoroni,Vatara,1229', NULL, NULL, NULL, NULL, NULL, NULL, '23.81353730', '90.42100000', 1, NULL, NULL, NULL, NULL, NULL),
(53, '187879', 1, 571, 583, 'Fahad Food', 'House-37,Road-4,Block-D,Niketon.', NULL, NULL, NULL, NULL, NULL, NULL, '23.77281980', '90.41128920', 1, NULL, NULL, NULL, NULL, NULL),
(54, '931409', 1, 571, 583, 'Tabassum Food', 'ka-1/2 , A , Kalachadpur ,North Baridhara ,Gulshan , Dhaka- 1212', NULL, NULL, NULL, NULL, NULL, NULL, '23.80735670', '90.41707500', 1, NULL, NULL, NULL, NULL, NULL),
(55, '931839', 1, 571, 583, 'Cock and Hen', 'ka- 16/3, South kuril,Vatara, 1229', NULL, NULL, NULL, NULL, NULL, NULL, '23.81230350', '90.42005770', 1, NULL, NULL, NULL, NULL, NULL),
(56, '932059', 1, 571, 583, 'Pallabi Food', 'House # Ga 36  , Road# Alatunnesa School ,Middel Badda,1212', NULL, NULL, NULL, NULL, NULL, NULL, '23.77764760', '90.42523430', 1, NULL, NULL, NULL, NULL, NULL),
(57, '932109', 1, 571, 583, 'Premium Fried Chicken', 'Want to Re-locate /   n/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
(58, '932349', 1, 571, 583, 'Dhaka Fast Food', 'Plot- 9, Road- 113/A, Gulshan', NULL, NULL, NULL, NULL, NULL, NULL, '23.78995830', '90.41937190', 1, NULL, NULL, NULL, NULL, NULL),
(59, '932409', 1, 571, 583, 'Arham Food', 'ka- 72/4/C ,Shop No.- 08, Shahajadpur,Gulshan', NULL, NULL, NULL, NULL, NULL, NULL, '23.79144190', '90.42249770', 1, NULL, NULL, NULL, NULL, NULL),
(60, '186249', 1, 571, 583, 'Golaps Food Corner', 'Kha- 214, Alombagh Complex ,Progoti Shoroni, Merul Badda, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.77343770', '90.42566220', 1, NULL, NULL, NULL, NULL, NULL),
(61, '930669', 1, 571, 583, 'AK Food', '336/D , East Nakhalpara, Tejgaon, Dhaka- 1215', NULL, NULL, NULL, NULL, NULL, NULL, '23.77090930', '90.39819710', 1, NULL, NULL, NULL, NULL, NULL),
(62, '187799', 1, 571, 583, 'Rajon Foods', '223/A , Bijoy Shoroni Link Road,Bhuiya Mansion, Tejgaon, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.76448300', '90.39099700', 1, NULL, NULL, NULL, NULL, NULL),
(63, '932379', 1, 571, 583, 'Patipata', '464 (Shahinbagh), West Nakhalpara, Tejgaon, Dhaka- 1215', NULL, NULL, NULL, NULL, NULL, NULL, '23.77340290', '90.39468180', 1, NULL, NULL, NULL, NULL, NULL),
(64, '932479', 1, 571, 583, 'Food Minister', '115/B , East Nakhalpara, Tejgaon, Dhaka- 1215', NULL, NULL, NULL, NULL, NULL, NULL, '23.76855940', '90.39383840', 1, NULL, NULL, NULL, NULL, NULL),
(65, '181699', 1, 571, 583, 'Eskaton Chicken', '4/B, Dilu Road, New Eskaton,Ramna ,1000', NULL, NULL, NULL, NULL, NULL, NULL, '23.74843430', '90.40084920', 1, NULL, NULL, NULL, NULL, NULL),
(66, '186039', 2, 576, 574, 'Hafsa  Snacks', '37, otish diponkor road, Sobujbag, Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.73647250', '90.42943400', 1, NULL, NULL, NULL, NULL, NULL),
(67, '183829', 2, 576, 574, 'Cafe Tushar', '20, north mugdapara, dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.72932860', '90.43049660', 1, NULL, NULL, NULL, NULL, NULL),
(68, '181059', 2, 576, 574, 'French Food', '4 no, mid masapo, tempu stand, dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.74032200', '90.43357520', 1, NULL, NULL, NULL, NULL, NULL),
(69, '183699', 2, 576, 574, 'Tandur Foods', '87, north basapo, dhaka. ', NULL, NULL, NULL, NULL, NULL, NULL, '23.74208520', '90.42886110', 1, NULL, NULL, NULL, NULL, NULL),
(70, '270079', 2, 576, 574, 'Family Chicken fast food', 'House No-03, Road No-14, Hirajheel, Chittagong Road, Shiddhirganj, Narayanganj', NULL, NULL, NULL, NULL, NULL, NULL, '23.69526830', '90.50994600', 1, NULL, NULL, NULL, NULL, NULL),
(71, '186999', 2, 576, 574, 'Lucky Chicken Corner', 'Navan tower, 20 oulet surcular road, rajarbag , dhaka. ', NULL, NULL, NULL, NULL, NULL, NULL, '23.74048860', '90.41987930', 1, NULL, NULL, NULL, NULL, NULL),
(72, '184709', 2, 576, 574, 'Spick & Span Food', '309/a tilpapara, dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.69396060', '90.43525620', 1, NULL, NULL, NULL, NULL, NULL),
(73, '189269', 2, 576, 574, 'Magpie', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
(74, '930129', 2, 576, 574, 'Arisha Enterprise ', 'Shop No-233/2, Hawai Goli-South Goran- Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.74581200', '90.43397700', 1, NULL, NULL, NULL, NULL, NULL),
(75, '189539', 2, 576, 574, 'Chicken Junction(Ck Castle)', '817, Khaza Tower, Barnomala School Road- Donia- Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.70151250', '90.44748850', 1, NULL, NULL, NULL, NULL, NULL),
(76, '270109', 2, 576, 574, 'Tejpata Restaurant', 'Sukur Super Market (Grand Floor), Shiddirganj Pool- Narayangang ', NULL, NULL, NULL, NULL, NULL, NULL, '23.68855750', '90.51404990', 1, NULL, NULL, NULL, NULL, NULL),
(77, '930139', 2, 576, 574, 'Bhai Bon Food Corner', '4-Maniknagr Road, Dhaka-1214.', NULL, NULL, NULL, NULL, NULL, NULL, '23.72264510', '90.42979780', 1, NULL, NULL, NULL, NULL, NULL),
(78, '930899', 2, 576, 574, 'Family Fried Chicken', 'Khilgaon Pura ton paka Mosjid market', NULL, NULL, NULL, NULL, NULL, NULL, '23.75025370', '90.42415090', 1, NULL, NULL, NULL, NULL, NULL),
(79, '931199', 2, 576, 574, 'Café Gold & BBQ', 'Sharighat, Karanigang ', NULL, NULL, NULL, NULL, NULL, NULL, '23.65777510', '90.43185000', 1, NULL, NULL, NULL, NULL, NULL),
(80, '931569', 2, 576, 574, 'Abdullah al arham fast food', '1006/2 Malibag Chawdory para- Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.74984020', '90.41602200', 1, NULL, NULL, NULL, NULL, NULL),
(81, '931769', 2, 576, 574, 'Sumi fried chicken', '131 South Goran- Khilgaon Dhaka ', NULL, NULL, NULL, NULL, NULL, NULL, '23.74823270', '90.43305230', 1, NULL, NULL, NULL, NULL, NULL),
(82, '931559', 2, 576, 574, 'Hangout', '443 Sajahanpur dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.74541170', '90.42415390', 1, NULL, NULL, NULL, NULL, NULL),
(83, '932079', 2, 576, 574, 'New red hot chicken', '217 Middle basabo, sabujbag dhaka ', NULL, NULL, NULL, NULL, NULL, NULL, '23.73967530', '90.42798890', 1, NULL, NULL, NULL, NULL, NULL),
(84, '270129', 2, 576, 574, 'Just Chill Fast Food ', 'Octo office- Chasahara- Narayangang', NULL, NULL, NULL, NULL, NULL, NULL, '23.62629660', '90.49163990', 1, NULL, NULL, NULL, NULL, NULL),
(85, '932209', 2, 576, 574, 'Minu Food ', '374- Taltola Khilgaon', NULL, NULL, NULL, NULL, NULL, NULL, '23.75181650', '90.42275950', 1, NULL, NULL, NULL, NULL, NULL),
(86, '932529', 2, 576, 574, 'Lemon Grass Food Corner', '5832, Hazi toyeb uddin Vokto supermarket- Demra Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.70563870', '90.47177800', 1, NULL, NULL, NULL, NULL, NULL),
(87, '932599', 2, 576, 574, 'Shaki Safi Fast Food', 'Holding no 492 Jurain- Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.69396060', '90.43525620', 1, NULL, NULL, NULL, NULL, NULL),
(88, '270149', 2, 576, 574, 'Spice Up', '208/2 vasa Soinik Road- Balur Math - Chashara- Narayangang', NULL, NULL, NULL, NULL, NULL, NULL, '23.62295960', '90.49975560', 1, NULL, NULL, NULL, NULL, NULL),
(89, '182709', 2, 576, 575, 'Chicken Paradise ', 'Plot-3,B-A,Main road,Aftab nagar,Merul badda,Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.76825538', '90.42487150', 1, NULL, NULL, NULL, NULL, NULL),
(90, '186049', 2, 576, 575, 'Taj  Food  Corner', '50/3,Bhatara,Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.80902620', '90.43118880', 1, NULL, NULL, NULL, NULL, NULL),
(91, '186499', 2, 576, 575, 'Nilu  Foods', 'H-13,R-12,DIT Project,Merul badda,Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.77281980', '90.42779420', 1, NULL, NULL, NULL, NULL, NULL),
(92, '189239', 2, 576, 575, 'Happy Food Corner', '963 ,khilbarir tek, Grave road, Shahjadpur', NULL, NULL, NULL, NULL, NULL, NULL, '23.79403500', '90.42557000', 1, NULL, NULL, NULL, NULL, NULL),
(93, '187829', 2, 576, 575, 'CF', 'Shop no:33,ch-74,Hossen super market,Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.78367000', '90.42584540', 1, NULL, NULL, NULL, NULL, NULL),
(94, '184469', 2, 576, 575, 'Aurora Food Shop', 'H-47,R-4,B-E,Banasree,Rampura,Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.76097700', '90.43627360', 1, NULL, NULL, NULL, NULL, NULL),
(95, '184599', 2, 576, 575, 'Chicken Paradise 2', 'H-1,R-4,B-D,Banasree,Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.76234060', '90.43075120', 1, NULL, NULL, NULL, NULL, NULL),
(96, '188109', 2, 576, 575, 'Madina Trade International', 'House-12,Road-8,Block-H,Banasree, Meradia, Dhaka-1219', NULL, NULL, NULL, NULL, NULL, NULL, '23.75971770', '90.44419400', 1, NULL, NULL, NULL, NULL, NULL),
(97, '188159', 2, 576, 575, 'R R Foods-2', 'House-67,Block-B,Main road south banasree', NULL, NULL, NULL, NULL, NULL, NULL, '23.75061860', '90.44106770', 1, NULL, NULL, NULL, NULL, NULL),
(98, '187979', 2, 576, 575, 'Maisha Food Palace', '356,East Rampura,Dhaka-1219.', NULL, NULL, NULL, NULL, NULL, NULL, '23.76224260', '90.42024330', 1, NULL, NULL, NULL, NULL, NULL),
(99, '931219', 2, 576, 575, 'Razia food shop', 'H-8,Block-K,South banasree main road,Dhaka-1219', NULL, NULL, NULL, NULL, NULL, NULL, '23.75470010', '90.44132510', 1, NULL, NULL, NULL, NULL, NULL),
(100, '931899', 2, 576, 575, 'Mamduha Food', 'House-33, Road-5, Block-A, Banasree, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.76399000', '90.42573240', 1, NULL, NULL, NULL, NULL, NULL),
(101, '930039', 2, 576, 575, 'Zunaid Foods', 'Vatara road, Bashundhora', NULL, NULL, NULL, NULL, NULL, NULL, '23.81233820', '90.42411020', 1, NULL, NULL, NULL, NULL, NULL),
(102, '930869', 2, 576, 575, 'Friends chicken', 'Rakib super market, Plot-5, Block-j, Madani avenue, Vatara, Notunbazar', NULL, NULL, NULL, NULL, NULL, NULL, '23.79784410', '90.42479840', 1, NULL, NULL, NULL, NULL, NULL),
(103, '930909', 2, 576, 575, 'Café Al mahdi fast food', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
(104, '931349', 2, 576, 575, 'Alif food', 'Ka-193 MB Square market, Shop-D1, Ghat par, Kuril, Vatara, Dhaka-1229.', NULL, NULL, NULL, NULL, NULL, NULL, '23.81671220', '90.42463610', 1, NULL, NULL, NULL, NULL, NULL),
(105, '0', 2, 576, 575, 'Zamans foods', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
(106, '931949', 2, 576, 575, 'Adory food', 'Plot 1596, North badda, Satarkul road', NULL, NULL, NULL, NULL, NULL, NULL, '23.78621730', '90.42783330', 1, NULL, NULL, NULL, NULL, NULL),
(107, '931999', 2, 576, 575, 'Sub zero fast food', 'Jamuna future park Food court, Shop No: 272-273', NULL, NULL, NULL, NULL, NULL, NULL, '23.81311185', '90.42440770', 1, NULL, NULL, NULL, NULL, NULL),
(108, '932089', 2, 576, 575, 'Prince food', 'Dag-2495 madani avenue, 100 feet saidnagar, east vatara, Dhaka-1212', NULL, NULL, NULL, NULL, NULL, NULL, '23.79884300', '90.43472350', 1, NULL, NULL, NULL, NULL, NULL),
(109, '932399', 2, 576, 575, 'Afraz Foods', 'Holding no 40, Road- 9, Block-J, Baridhara,Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.80189010', '90.42306000', 1, NULL, NULL, NULL, NULL, NULL),
(110, '932539', 2, 576, 575, 'Raha Food Paradise', 'Kh-27, Sohid Aziz Sorok Jagannathpur, Vatara, Shop no- 01, Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.80975190', '90.42482680', 1, NULL, NULL, NULL, NULL, NULL),
(111, '932669', 2, 576, 575, 'Abdullah Al Araf', 'House-13, Main Road, Block B, Ground Floor, Banasree, Rampura, Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.76469210', '90.42885790', 1, NULL, NULL, NULL, NULL, NULL),
(112, '183909', 2, 576, 589, 'Western Fried Chicken', '55,Siddik Mansion,Shop no.-09,Purana polton,Dhaka-1000', NULL, NULL, NULL, NULL, NULL, NULL, '23.73080970', '90.41185060', 1, NULL, NULL, NULL, NULL, NULL),
(113, '186599', 2, 576, 589, 'Aristocrat Food and Drinks', '37/2,Purana poltan,Dhaka-1000,Pritom Zaman Tower', NULL, NULL, NULL, NULL, NULL, NULL, '23.73288140', '90.41204390', 1, NULL, NULL, NULL, NULL, NULL),
(114, '182669', 2, 576, 589, 'Sugar N Milk', '60 No.Chamilibagh,Shantinogor,Shiplo chargo joynal abedin shorok dhaka 1217', NULL, NULL, NULL, NULL, NULL, NULL, '23.74046000', '90.41344580', 1, NULL, NULL, NULL, NULL, NULL),
(115, '189089', 2, 576, 589, 'Magdy\'s Snacks ', '48/3/1, Avoydash Lane, Tikatuli, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.72000510', '90.42496510', 1, NULL, NULL, NULL, NULL, NULL),
(116, '180859', 2, 576, 589, 'Food Plus', '4/1, here street, wari, Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.71796500', '90.41688600', 1, NULL, NULL, NULL, NULL, NULL),
(117, '187539', 2, 576, 589, 'Seven Star', '1/2, chondichoron bosh street, wari, dhaka. ', NULL, NULL, NULL, NULL, NULL, NULL, '23.71928220', '90.41862720', 1, NULL, NULL, NULL, NULL, NULL),
(118, '187279', 2, 576, 589, 'Chicken  Square', '19/A, Suvash bose evenue,laxmibazar, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.70849500', '90.41461780', 1, NULL, NULL, NULL, NULL, NULL),
(119, '182229', 2, 576, 589, 'D.J. confectionary ', '30,Dinnath sen road, Gendaria, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.70420860', '90.42384630', 1, NULL, NULL, NULL, NULL, NULL),
(120, '182939', 2, 576, 589, 'Chicken Corner', '74, Narinda road, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.71276140', '90.41871360', 1, NULL, NULL, NULL, NULL, NULL),
(121, '183309', 2, 576, 589, 'Hot & Fresh', '10/1/A, B,C,C road , thadari, bazar, nobabpur, dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.72086700', '90.41334400', 1, NULL, NULL, NULL, NULL, NULL),
(122, '182019', 2, 576, 589, 'Yammy N Tummy', '8, sohid faruk road, norht jatrabari, dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.70935000', '90.42850500', 1, NULL, NULL, NULL, NULL, NULL),
(123, '185899', 2, 576, 589, 'Anisa  Food  Zone', '91/1/A, samibag, MPC building, dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.71177130', '90.42315270', 1, NULL, NULL, NULL, NULL, NULL),
(124, '930499', 2, 576, 589, 'Thamid Food Court', 'Zinzira Bus Stand', NULL, NULL, NULL, NULL, NULL, NULL, '23.70802230', '90.39396080', 1, NULL, NULL, NULL, NULL, NULL),
(125, '930689', 2, 576, 589, 'Dhaka Fried Chicken', '33/1 Banglabazar, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.70687700', '90.41102070', 1, NULL, NULL, NULL, NULL, NULL),
(126, '931259', 2, 576, 589, 'Nabila Food', 'Arsingate, Foridabad', NULL, NULL, NULL, NULL, NULL, NULL, '23.69455780', '90.42525890', 1, NULL, NULL, NULL, NULL, NULL),
(127, '931809', 2, 576, 589, 'Kalapata Rannaghor', '24/4 Segunbagisha, Dhaka 1000', NULL, NULL, NULL, NULL, NULL, NULL, '23.73428020', '90.40737610', 1, NULL, NULL, NULL, NULL, NULL),
(128, '931829', 2, 576, 589, 'Thamid Food Court 2', 'Road No 1, Kodomtoli, Keraniganj', NULL, NULL, NULL, NULL, NULL, NULL, '23.70325660', '90.39794430', 1, NULL, NULL, NULL, NULL, NULL),
(129, '189929', 2, 576, 589, 'Bit & Bite (Rainbow Chicken)', '260,Malibagh,Dhaka-1214.', NULL, NULL, NULL, NULL, NULL, NULL, '23.74466960', '90.41515110', 1, NULL, NULL, NULL, NULL, NULL),
(130, '187409', 2, 576, 589, 'Onanna Fried Chicken', '234,Malibagh bazar road,Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '23.74595970', '90.41346700', 1, NULL, NULL, NULL, NULL, NULL),
(131, '931869', 2, 576, 589, 'Mridula Food Bazar', '14/2 Mohammodia Marcket(Room -10) Santinagor Bazar, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.73993090', '90.41136330', 1, NULL, NULL, NULL, NULL, NULL),
(132, '932629', 2, 576, 589, 'Farsi Fried Chicken', '68/2 Siddheswari Road, Ramna, Dhaka 1217', NULL, NULL, NULL, NULL, NULL, NULL, '23.74328180', '90.40850370', 1, NULL, NULL, NULL, NULL, NULL),
(133, '932219', 2, 576, 589, 'Umea Kitchen', 'Monir Tower, 167/1 DIT Extension Road(Culvurt Road), Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '23.73144570', '90.41544360', 1, NULL, NULL, NULL, NULL, NULL),
(134, '182789', 3, 588, 580, 'Right Choice Fast Food', '361/1 shahid Jahnara Imam Shoroni Eliphant Road', NULL, NULL, NULL, NULL, NULL, NULL, '90.38620390', '23.73644480', 1, NULL, NULL, NULL, NULL, NULL),
(135, '180519', 3, 588, 580, 'Red Chilli', 'BDDL Green Mart, Plot -33,33/1, Shop -120A, Green Road, Dhaka-1205.', NULL, NULL, NULL, NULL, NULL, NULL, '90.38598330', '23.74671320', 1, NULL, NULL, NULL, NULL, NULL),
(136, '930159', 3, 588, 580, 'SMS Fast food', 'Multiplan center, Elephant road,Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.38557480', '23.73840840', 1, NULL, NULL, NULL, NULL, NULL),
(137, '185429', 3, 588, 580, 'Three Star Chicken', 'City Corparation Market,Nilkhat,New Market,Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.38593860', '23.73245040', 1, NULL, NULL, NULL, NULL, NULL),
(138, '183179', 3, 588, 580, 'Azimpur Fried Chicken', 'Azimpur Kobor Sthan Road ,Azimpur Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '90.38453290', '23.72685010', 1, NULL, NULL, NULL, NULL, NULL),
(139, '186519', 3, 588, 580, 'Khan  Fast  Food', 'House: 01, Road: Cetral Road, Hatirpul, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.38998960', '23.74169140', 1, NULL, NULL, NULL, NULL, NULL),
(140, '184299', 3, 588, 580, 'Golden Fried Chicken', '73/1, Green Road (behind of Anondo Cinema Hall)', NULL, NULL, NULL, NULL, NULL, NULL, '90.38935600', '23.75564110', 1, NULL, NULL, NULL, NULL, NULL),
(141, '931639', 3, 588, 580, 'Sathi Fast Food', 'Near BRP hospital', NULL, NULL, NULL, NULL, NULL, NULL, '90.38551260', '23.75187410', 1, NULL, NULL, NULL, NULL, NULL),
(142, '931659', 3, 588, 580, 'Favorite Fried Chicken', 'Near RK tower', NULL, NULL, NULL, NULL, NULL, NULL, '90.39228690', '23.74601860', 1, NULL, NULL, NULL, NULL, NULL),
(143, '932509', 3, 588, 580, 'Family,s Dream', '24/1, Mc ray lane, Lalbagh, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.37623000', '23.72552472', 1, NULL, NULL, NULL, NULL, NULL),
(144, '184839', 3, 588, 580, 'Taba Hot & Spices', '21,Monipuri Para, Sangshad Bhaban Avenu Tejgoan, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.38359100', '23.76017600', 1, NULL, NULL, NULL, NULL, NULL),
(145, '184779', 3, 588, 580, 'Fresh Food - 2', 'Taltola Bus Stand, Agargaon.', NULL, NULL, NULL, NULL, NULL, NULL, '90.37775100', '23.78367300', 1, NULL, NULL, NULL, NULL, NULL),
(146, '186139', 3, 588, 580, 'Fresh  Food - 3', 'Bangladesh Parliament, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.37962000', '23.76111980', 1, NULL, NULL, NULL, NULL, NULL),
(147, '180469', 3, 588, 580, 'Food Center', 'Plot # 3/C, Lions Bhaban, Agargaon, Dhaka - 1207', NULL, NULL, NULL, NULL, NULL, NULL, '90.37958300', '23.77928000', 1, NULL, NULL, NULL, NULL, NULL),
(148, '930449', 3, 588, 580, 'M/S Mozammel Traders', 'BRTA, Mirpur - 13, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.37531000', '23.80548500', 1, NULL, NULL, NULL, NULL, NULL),
(149, '932159', 3, 588, 580, '23 East Bengal', 'Wariors Canteen, Banani Contoment, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.39670320', '23.78813850', 1, NULL, NULL, NULL, NULL, NULL),
(150, '330099', 3, 588, 580, 'Food Forward', 'Supari bagan road,Biswas betka, Tangail', NULL, NULL, NULL, NULL, NULL, NULL, '89.92244690', '24.25265570', 1, NULL, NULL, NULL, NULL, NULL),
(151, '330089', 3, 588, 580, 'Kings Kitchen', 'Pouro Super Market, Victoria road, Tangail', NULL, NULL, NULL, NULL, NULL, NULL, '89.91696000', '24.25141000', 1, NULL, NULL, NULL, NULL, NULL),
(152, '932589', 3, 588, 580, 'Nishat Corner', 'Dr. Refatullah Happy Arcade, Ground Floor, Dhanmondi 3, Dhaka 1205', NULL, NULL, NULL, NULL, NULL, NULL, '90.38296000', '23.74012000', 1, NULL, NULL, NULL, NULL, NULL),
(153, '185539', 3, 588, 580, 'Bijoy Caterig  Service', 'BAF Shaheen College Canteen, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.38980000', '23.77724000', 1, NULL, NULL, NULL, NULL, NULL),
(154, '932719', 3, 588, 580, 'Happy Link BD', 'Awlad Hossain Market, Kazi Nazrul Islam Ave, Dhaka 1215', NULL, NULL, NULL, NULL, NULL, NULL, '90.38931900', '23.76297970', 1, NULL, NULL, NULL, NULL, NULL),
(155, '932769', 3, 588, 580, 'Juice Hut Local', 'Suvastu Chirontoni, 26 Indira Rd, Dhaka 1215', NULL, NULL, NULL, NULL, NULL, NULL, '90.38708470', '23.75807890', 1, NULL, NULL, NULL, NULL, NULL),
(156, '182389', 3, 588, 590, 'Chicken hut', 'Road -12, House -19, old Dhanmondi 31, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.37557700', '23.75257500', 1, NULL, NULL, NULL, NULL, NULL),
(157, '189899', 3, 588, 590, 'Optimum Food Corner', 'Holding-14/21,Block-A,Asad Avenue,Mohammadpur', NULL, NULL, NULL, NULL, NULL, NULL, '90.36777100', '23.76005600', 1, NULL, NULL, NULL, NULL, NULL),
(158, '187819', 3, 588, 590, 'Ashraf Enterprise', 'H-117,R-9/a, Sonkor, Dhanmondi', NULL, NULL, NULL, NULL, NULL, NULL, '90.36772600', '23.75020300', 1, NULL, NULL, NULL, NULL, NULL),
(159, '188389', 3, 588, 590, 'A & W Trading Corporation', '10/1, Iqbal Road, Mohammadpur, Dhaka-1207', NULL, NULL, NULL, NULL, NULL, NULL, '90.36884400', '23.76232400', 1, NULL, NULL, NULL, NULL, NULL),
(160, '931159', 3, 588, 590, 'M R Food', '59, Zhigatola, Dhanmondi, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.37232320', '999.99999999', 1, NULL, NULL, NULL, NULL, NULL),
(161, '931469', 3, 588, 590, 'Food River', '6/1. Block - E, Lalamatia, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.36529080', '23.75410120', 1, NULL, NULL, NULL, NULL, NULL),
(162, '189679', 3, 588, 590, 'Fresh Food', '4/4, Block-E, Lalmatia, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.36765800', '23.75564400', 1, NULL, NULL, NULL, NULL, NULL),
(163, '187239', 3, 588, 590, 'DP Confectionary', '46/4 zegatola,Dhaka-1209', NULL, NULL, NULL, NULL, NULL, NULL, '90.37103200', '23.73962180', 1, NULL, NULL, NULL, NULL, NULL),
(164, '184109', 3, 588, 590, 'Erina Food', 'Dhanmondi 15', NULL, NULL, NULL, NULL, NULL, NULL, '90.36946970', '23.74385660', 1, NULL, NULL, NULL, NULL, NULL),
(165, '189549', 3, 588, 590, 'Craze Burger & Coffee Lounge', 'House # 99, Road # 11/A, Dhamondi, Dhaka - 1219.', NULL, NULL, NULL, NULL, NULL, NULL, '90.37123490', '23.74689170', 1, NULL, NULL, NULL, NULL, NULL),
(166, '183649', 3, 588, 590, 'Mirchi Kabab & Grill', '568, Kazipara, Begum Rokeya Sarani, Mirpur, Dhaka -1206.', NULL, NULL, NULL, NULL, NULL, NULL, '90.37204000', '23.79872100', 1, NULL, NULL, NULL, NULL, NULL),
(167, '931329', 3, 588, 590, 'Sister\'s Kitchen', '1/17, Block-B, avenue-1, Section- 10, Mirpur, Dhaka-1216.', NULL, NULL, NULL, NULL, NULL, NULL, '90.36918600', '23.80961900', 1, NULL, NULL, NULL, NULL, NULL),
(168, '931319', 3, 588, 590, 'New Chick N Chill', '808/1, Shewrapara, Begum Rokeya Sharoni, Dhaka – 1216', NULL, NULL, NULL, NULL, NULL, NULL, '90.37544210', '23.79077930', 1, NULL, NULL, NULL, NULL, NULL),
(169, '932489', 3, 588, 590, 'Monohora', 'Shwpno, Mirpur-10', NULL, NULL, NULL, NULL, NULL, NULL, '90.37043000', '23.80677000', 1, NULL, NULL, NULL, NULL, NULL),
(170, '932459', 3, 588, 590, 'Avatar Business World 2', 'Plot-2/A, Shop-5, Sikkhok Somiti Market, Mirpur-13', NULL, NULL, NULL, NULL, NULL, NULL, '90.37645570', '23.80510240', 1, NULL, NULL, NULL, NULL, NULL),
(171, '187049', 3, 588, 590, 'Islam  Fast  Food', 'Shop # 33, Ruhani Super Market 2-D, Section - 2, Zoo Road , Mirpur', NULL, NULL, NULL, NULL, NULL, NULL, '90.35473400', '23.80056100', 1, NULL, NULL, NULL, NULL, NULL),
(172, '930779', 3, 588, 590, 'Avatar Business World', 'House N:1,Road N-9,Block-C,Mirpur-12', NULL, NULL, NULL, NULL, NULL, NULL, '90.36632810', '23.82581880', 1, NULL, NULL, NULL, NULL, NULL),
(173, '185299', 3, 588, 590, 'Z & Z  Fried Chicken', 'Road N: 24, House N 1, Pallabi', NULL, NULL, NULL, NULL, NULL, NULL, '90.36202500', '23.82437600', 1, NULL, NULL, NULL, NULL, NULL),
(174, '931459', 3, 588, 590, 'R.S Enterprise', 'House N:39,Road N:8,Section-2 Mirpur Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.36201570', '23.80707230', 1, NULL, NULL, NULL, NULL, NULL),
(175, '931599', 3, 588, 590, 'Rayat Fast Food', 'Harunabadh Super Market,Mirpur-12', NULL, NULL, NULL, NULL, NULL, NULL, '90.36364160', '23.83015000', 1, NULL, NULL, NULL, NULL, NULL),
(176, '250099', 3, 588, 590, 'Rafat Bakers', 'Afroza Ramzan Girls High School Market, 220 Shahid Rafique Shorok, Manikgong', NULL, NULL, NULL, NULL, NULL, NULL, '90.00258880', '23.86093650', 1, NULL, NULL, NULL, NULL, NULL),
(177, '930959', 3, 588, 590, 'Foodilicious', 'Shap N-4,Behottoro Noakhali market complex 10/2 rupnagar Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.35661211', '23.81279483', 1, NULL, NULL, NULL, NULL, NULL),
(178, '932639', 3, 588, 590, 'Red Chilli Fast Food -2', '26 Kalabagan 1st Ln, Dhaka 1205', NULL, NULL, NULL, NULL, NULL, NULL, '90.38101760', '23.74731530', 1, NULL, NULL, NULL, NULL, NULL),
(179, '931589', 3, 588, 591, 'Arafat Enterprise', 'Haji Ismail Market, Labuni Point, Ghatarchor, Keranigonj, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.33920040', '23.73568590', 1, NULL, NULL, NULL, NULL, NULL),
(180, '185439', 3, 588, 591, 'SF  Chicken  Shop', 'Shop # 32, Hall Market, Dhaka 1207', NULL, NULL, NULL, NULL, NULL, NULL, '90.36510968', '23.77450300', 1, NULL, NULL, NULL, NULL, NULL),
(181, '183479', 3, 588, 591, 'Salt Spice', '5/3, Basbari, Katasur, Sherebangla Road, Mohammadpur, Dhaka -1207', NULL, NULL, NULL, NULL, NULL, NULL, '90.36146257', '23.75624657', 1, NULL, NULL, NULL, NULL, NULL),
(182, '186909', 3, 588, 591, 'N.  Food', 'T/42, Nurjahan Road, Mohammadpur, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.36146800', '23.76132700', 1, NULL, NULL, NULL, NULL, NULL),
(183, '187439', 3, 588, 591, 'Mou food', 'GA/39,Mohammadia housing, Dhaka-1212', NULL, NULL, NULL, NULL, NULL, NULL, '90.35860700', '23.76229200', 1, NULL, NULL, NULL, NULL, NULL),
(184, '932469', 3, 588, 591, 'Hujaifa Food Corner', '17/14, Block-C, Tajmohal Road, Mohammedpur', NULL, NULL, NULL, NULL, NULL, NULL, '90.36135800', '23.76424680', 1, NULL, NULL, NULL, NULL, NULL),
(185, '931119', 3, 588, 591, 'Onsana Food', 'Rd# 3, Shekertek, Adabar, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.35788580', '23.76690440', 1, NULL, NULL, NULL, NULL, NULL),
(186, '931509', 3, 588, 591, 'Mabrur Fast Food', '1/4, beribadh more, mohammodpur.', NULL, NULL, NULL, NULL, NULL, NULL, '90.35635860', '23.75585090', 1, NULL, NULL, NULL, NULL, NULL),
(187, '931819', 3, 588, 591, 'Chicken Bite', 'Road 13, Sekhertek, Adabor, Mohammedpur', NULL, NULL, NULL, NULL, NULL, NULL, '90.35587470', '23.77032164', 1, NULL, NULL, NULL, NULL, NULL),
(188, '183539', 3, 588, 591, 'Popeyes Chicken', 'Lalbagh Kallar Mor,Lalbagh,Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '90.38990240', '23.71968050', 1, NULL, NULL, NULL, NULL, NULL),
(189, '186589', 3, 588, 591, 'Istimam Fast Food', 'ShathRowja,Bongshal Road,Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.40209490', '23.71737420', 1, NULL, NULL, NULL, NULL, NULL),
(190, '181609', 3, 588, 591, 'Chicken Heritage', '51, NajimuddiNroad, Dhaka-1100', NULL, NULL, NULL, NULL, NULL, NULL, '90.40000472', '23.72239143', 1, NULL, NULL, NULL, NULL, NULL),
(191, '182739', 3, 588, 591, 'Ahabar Foods ', '2, Darus Salam Road, Mirpur-1, Dhaka- 1216', NULL, NULL, NULL, NULL, NULL, NULL, '90.35324600', '23.79731400', 1, NULL, NULL, NULL, NULL, NULL),
(192, '930599', 3, 588, 591, 'Mithai Bari', 'New Market, Savar', NULL, NULL, NULL, NULL, NULL, NULL, '90.25911710', '23.85102550', 1, NULL, NULL, NULL, NULL, NULL),
(193, '181439', 3, 588, 591, 'Bhuiyan Food', 'Bus Stand, Hemayetpur, Savar, Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, '90.27376147', '23.79328150', 1, NULL, NULL, NULL, NULL, NULL),
(194, '931059', 3, 588, 591, 'Omega Food', 'Sah-ali-bagh,Mirpur', NULL, NULL, NULL, NULL, NULL, NULL, '90.35665113', '23.79498362', 1, NULL, NULL, NULL, NULL, NULL),
(195, '182379', 3, 588, 591, 'Bhiuyan food-02', 'Thana Road, Savar (In front of Enam Medical College).', NULL, NULL, NULL, NULL, NULL, NULL, '90.25199300', '23.83826500', 1, NULL, NULL, NULL, NULL, NULL),
(196, '184999', 3, 588, 591, 'Bondhon Food', 'Kallaynpur Main Road (Beside Sathi Studio), Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.36112871', '23.77978618', 1, NULL, NULL, NULL, NULL, NULL),
(197, '185449', 3, 588, 591, 'SRM  Foods', 'H#2, R# 5, B# F, Mirpur 1, Dhaka 1216', NULL, NULL, NULL, NULL, NULL, NULL, '90.35134300', '23.79946700', 1, NULL, NULL, NULL, NULL, NULL),
(198, '187749', 3, 588, 591, 'Sigma Food-3', 'Ansarcamp,Mirpur 1', NULL, NULL, NULL, NULL, NULL, NULL, '90.35577200', '23.79114200', 1, NULL, NULL, NULL, NULL, NULL),
(199, '184869', 3, 588, 591, 'Food Lovers', 'Mirpur-10 (Beside Water Pump)', NULL, NULL, NULL, NULL, NULL, NULL, '90.36585262', '23.80614099', 1, NULL, NULL, NULL, NULL, NULL),
(200, '932149', 3, 588, 591, 'Safia Shop', '728 Kamal Soroni, (60 Feet) Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, '90.36362034', '23.79988136', 1, NULL, NULL, NULL, NULL, NULL),
(201, '932659', 3, 588, 591, 'Zarif Fried Chicken', 'Rishipara, Hemayetpur, Savar', NULL, NULL, NULL, NULL, NULL, NULL, '90.25784270', '23.79232900', 1, NULL, NULL, NULL, NULL, NULL),
(202, '934979', 3, 588, 0, 'AB Food Palace', 'Ring Road, Adabar', NULL, NULL, NULL, NULL, NULL, NULL, '90.35848765', '23.76830350', 1, NULL, NULL, NULL, NULL, NULL),
(203, '70029', 6, 577, 578, 'Chicken Lovers', '54, Chattaswari Road, Chawak bazar, Chittagong', NULL, NULL, NULL, NULL, NULL, NULL, '22.35700900', '91.83675000', 1, NULL, NULL, NULL, NULL, NULL),
(204, '70059', 6, 577, 578, 'R2L  Spicy', '565/A, CDA Avenue (GEC Point), Chittagong', NULL, NULL, NULL, NULL, NULL, NULL, '22.35835795', '91.82145099', 1, NULL, NULL, NULL, NULL, NULL),
(205, '70339', 6, 577, 578, 'S. A. Traders', '15, Sahid Sarwardi road, Firingibazar, kotwali, Chittagong', NULL, NULL, NULL, NULL, NULL, NULL, '22.33342850', '91.83572870', 1, NULL, NULL, NULL, NULL, NULL),
(206, '70379', 6, 577, 578, 'Mita  Food  Corner', 'Sodorhghat road, Nalapara, Chittagong', NULL, NULL, NULL, NULL, NULL, NULL, '22.33016532', '91.83128940', 1, NULL, NULL, NULL, NULL, NULL),
(207, '70049', 6, 577, 578, 'Chittagong Fried Chicken', '12/A Sojib Plaza, PC road, Block-G, Halisahar, Chittagong', NULL, NULL, NULL, NULL, NULL, NULL, '22.33259390', '91.78930470', 1, NULL, NULL, NULL, NULL, NULL),
(208, '70569', 6, 577, 578, 'FOOD PALACE', 'Navy hospital gate, South halishahar, EPZ, Chattogram.', NULL, NULL, NULL, NULL, NULL, NULL, '22.28280220', '91.78381190', 1, NULL, NULL, NULL, NULL, NULL),
(209, '70609', 6, 577, 578, 'S. DINE', 'Public college moor, Bayzid bostami, Jalalabad, 6', NULL, NULL, NULL, NULL, NULL, NULL, '22.39417840', '91.81622320', 1, NULL, NULL, NULL, NULL, NULL),
(210, '70069', 6, 577, 578, 'Agrabad Fried Chicken', '1842,SK Mujib Road,Agrabad', NULL, NULL, NULL, NULL, NULL, NULL, '22.32826360', '91.81230240', 1, NULL, NULL, NULL, NULL, NULL),
(211, '70639', 6, 577, 578, 'RTC FOOD', '17,New I block,Artilary Road,Halisohor', NULL, NULL, NULL, NULL, NULL, NULL, '22.33984230', '91.78219200', 1, NULL, NULL, NULL, NULL, NULL),
(212, '70659', 6, 577, 578, 'Noor Enterprise', 'A-1/74P, purba Nasirabad housing society road, paclaish, Chattogram ', NULL, NULL, NULL, NULL, NULL, NULL, '22.36456112', '91.82498050', 1, NULL, NULL, NULL, NULL, NULL),
(213, '188059', 6, 577, 592, 'Rapido Kitchen', 'Fouzdari mor cumilla, opposite of missionary school.', NULL, NULL, NULL, NULL, NULL, NULL, '23.46845300', '91.18045770', 1, NULL, NULL, NULL, NULL, NULL),
(214, '110199', 6, 577, 592, 'YA NOOR HALAL FOOD', 'Bangla bazar cantonmant cumilla ', NULL, NULL, NULL, NULL, NULL, NULL, '23.45819130', '91.12636280', 1, NULL, NULL, NULL, NULL, NULL),
(215, '110469', 6, 577, 592, 'Tasty King', 'College super market companigonj, Nobinogor road', NULL, NULL, NULL, NULL, NULL, NULL, '23.63953460', '90.96959340', 1, NULL, NULL, NULL, NULL, NULL),
(216, '130019', 6, 577, 592, 'Smile Foods', 'Trunk road feni,Beside model thana', NULL, NULL, NULL, NULL, NULL, NULL, '23.01114780', '91.39808540', 1, NULL, NULL, NULL, NULL, NULL),
(217, '130029', 6, 577, 592, 'Moon  Star  Fast  Food', 'Momotaz center,ssk road,mohipal feni.', NULL, NULL, NULL, NULL, NULL, NULL, '23.00960249', '91.38256093', 1, NULL, NULL, NULL, NULL, NULL),
(218, '110229', 6, 577, 592, 'RED HEART', 'Taher mension,college road,debidwar', NULL, NULL, NULL, NULL, NULL, NULL, '23.60254380', '90.98865590', 1, NULL, NULL, NULL, NULL, NULL),
(219, '150019', 6, 577, 592, 'Welcare Cafeteria', 'Manarat Bhaban,Beside pollibiddut office, laxmipur. ', NULL, NULL, NULL, NULL, NULL, NULL, '22.94204060', '90.84893950', 1, NULL, NULL, NULL, NULL, NULL),
(220, '90019', 6, 577, 592, 'A. B. Foods', 'Old jail road,kumarshil,b.baria', NULL, NULL, NULL, NULL, NULL, NULL, '23.97752490', '91.10941180', 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_orders`
--

CREATE TABLE `product_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `packaging_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` double DEFAULT 0,
  `weight_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `tp` double DEFAULT NULL,
  `mrp` double DEFAULT NULL,
  `gp` double DEFAULT NULL,
  `gp_percentage` double DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `status_by` int(11) DEFAULT NULL,
  `deleted_temp` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_orders`
--

INSERT INTO `product_orders` (`id`, `image`, `product_code`, `type`, `type_bn`, `category`, `packaging_type`, `weight`, `weight_type`, `quantity`, `tp`, `mrp`, `gp`, `gp_percentage`, `remark`, `status`, `status_by`, `deleted_temp`, `deleted_by`, `created_by`, `created_at`, `updated_at`) VALUES
(1, NULL, '9001001066', 'Crispy Chicken', 'ক্রিস্পি চিকেন', 1, 'Pack', 1.3, 'kg', 10, 660.16, 940, 279.84, 30, '2 Breasts + 2 Thighs + 2 Legs + 2 Wings + 2 Ribs', 1, NULL, NULL, NULL, 25, '2022-06-27 23:16:09', '2022-09-13 11:22:46'),
(2, 'crispy-leg_VsmNnPTD1663232381.png', '9001001090', 'Crispy: Leg', 'ক্রিস্পি লেগ', 1, 'Pack', 1, 'kg', 10, 585, 650, 365, 38, NULL, 1, NULL, NULL, NULL, 25, '2022-06-27 23:20:50', '2022-09-15 08:59:41'),
(3, NULL, '9001001131', 'FPP Chilled Spicy Maxx 8/10 Pcs Cut', 'স্পাইসি ম্যাক্স', 1, 'Pack', 1.5, 'kg', 8, 595, 850, 255, 30, '2 Breasts + 2 Thighs + 2 Legs + 2 Ribs', 1, NULL, NULL, NULL, 25, '2022-06-27 23:26:14', '2022-09-13 11:22:46'),
(4, NULL, '9001001077', 'Marinate Spicy Fried Chicken (13 Pcs)', 'স্পাইসি চিকেন', 1, 'Pack', 1.5, 'kg', 8, 667.4, 940, 273, 29, '3 Breasts + 4 Thighs + 2 Legs + 2 Wings + 2 Ribs', 1, NULL, NULL, NULL, 25, '2022-06-27 23:27:47', '2022-09-13 11:22:46'),
(5, 'marinate-hot-wing-5-set_f70Y5ch71663232493.png', '9001001020', 'Marinate Hot Wing (5 Set)', 'হট উইংস', 1, 'Pack', 1, 'kg', 20, 463, 600, 137, 23, NULL, 1, NULL, NULL, NULL, 25, '2022-06-27 23:32:54', '2022-09-15 09:01:33'),
(6, 'fpp-chilled-kaarage-chicken-13kg32pcs_QHgGb5lj1663232474.png', '9001001172', 'FPP Chilled Kaarage Chicken-1.3kg/32pcs', 'কারাগে চিকেন', 2, 'Pack', 1.3, 'kg', 1, 739.2, 360, 220.8, 23, NULL, 1, NULL, NULL, NULL, 25, '2022-06-27 23:34:53', '2022-09-15 09:01:15'),
(7, NULL, '9101001002', 'CP Spicy Chicken Ball(1000 Gm)', 'চিকেন বল', 2, 'Pack', 1, 'kg', 1, 456.5, 550, 93.5, 17, NULL, 1, NULL, NULL, NULL, 25, '2022-06-27 23:36:50', '2022-09-13 11:22:46'),
(8, NULL, '9201001006', 'Chicken Hot Sausage (1000g)', 'চিকেন সসেজ', 2, 'Pack', 1, 'kg', 1, 480, NULL, 150, 20, NULL, 1, NULL, NULL, NULL, 25, '2022-06-27 23:46:24', '2022-09-13 11:22:45'),
(9, NULL, '9401001003', 'CP.Chicken Nugget(500g)', 'চিকেন নাগেট', 2, 'Pack', 0.5, 'kg', 1, 348, NULL, 147, 30, NULL, 1, NULL, NULL, NULL, 25, '2022-06-27 23:49:11', '2022-09-13 11:22:46'),
(10, 'cp-chicken-stick-fo-1000-g_7jJjPiKu1663232453.png', '9401001012', 'CP Chicken Stick -FO 1000 g', 'চিকেন স্টিক', 3, 'Pack', 1, 'kg', 1, 562.35, 800, 237.665, 30, NULL, 1, NULL, NULL, NULL, 25, '2022-06-27 23:52:40', '2022-09-15 09:00:53'),
(11, 'cp-bologna-stick-1-kg_yVpEfZJJ1663232418.png', '9301001012', 'CP Bologna Stick  1 Kg', 'সিপি ললিপপ বোলগনা স্টিক', 3, 'Pack', 1, 'kg', 1, 507.65, NULL, 207.84, 29, NULL, 1, NULL, NULL, NULL, 25, '2022-06-27 23:55:42', '2022-09-15 09:00:19'),
(12, 'fried-rice-250gpack_UQF9nlXk1663232355.png', '9601001036', 'Fried Rice 250g/Pack', 'ফ্রাইড রাইস', 3, 'Pack', 0.25, 'kg', 1, 95, NULL, 30, 24, NULL, 1, NULL, NULL, NULL, 25, '2022-06-27 23:56:54', '2022-09-15 08:59:16'),
(13, NULL, '9601001010', 'Egg Pudding  110 g.', 'ডিম পুডিং', 3, 'Cup', 0.11, 'kg', 1, 45, NULL, 45, 15, NULL, 1, NULL, NULL, NULL, 25, '2022-06-27 23:57:53', '2022-09-13 11:22:45'),
(14, NULL, NULL, 'Bread (10 Pcs)', 'ব্রেড', 3, 'Pack', 0, NULL, 2, 150, 200, 50, 25, NULL, NULL, NULL, NULL, NULL, 518, '2022-06-28 00:04:11', '2022-07-18 05:54:00'),
(16, NULL, NULL, 'CP Chicken Khichuri', 'সিপি চিকেন খুচুড়ি', 3, 'Box', 0, NULL, 1, 76, 100, 24, 24, NULL, NULL, NULL, NULL, NULL, 518, '2022-06-28 00:06:51', '2022-07-18 05:53:41'),
(17, NULL, NULL, 'CP Crispy Burger', 'সিপি ক্রিস্পি বার্গার', 3, 'Pack', 0, NULL, 10, 54.22, 99, 44.78, 45, NULL, NULL, NULL, NULL, NULL, 518, '2022-06-28 00:15:00', '2022-07-18 05:53:46'),
(18, NULL, NULL, 'CP Fiery BBQ Burger', 'সিপি বি.বি.কিউ. বার্গার', 3, 'Pack', 0, NULL, 10, 61, 119, 58, 49, NULL, NULL, NULL, NULL, NULL, 518, '2022-06-28 00:16:52', '2022-07-18 05:53:52'),
(19, 'fpp-chilled-marinated-zinger-fillet_B6h2aVvX1663232236.png', '9001001159', 'FPP Chilled Marinated Zinger Fillet', 'ম্যারিনেটেড জিনজার ফিলেট', 3, 'Pack', 1, 'kg', 1, 609, NULL, 44, 32, NULL, 1, NULL, NULL, NULL, 25, '2022-06-28 00:18:02', '2022-09-15 08:57:17'),
(20, NULL, '9X01001260', 'Jhatpot French Fry-2500g', 'ঝটপট ফ্রেঞ্চ ফ্রাইজ', 3, 'Pack', 2.5, 'kg', 1, 550, 99, 67.71, 68, NULL, 1, NULL, NULL, NULL, 25, '2022-06-28 00:19:21', '2022-09-13 11:22:45'),
(21, 'fpp-chilled-marinated-chicken-popcorn_eJNmBP9b1663232187.png', '9001001195', 'FPP Chilled Marinated Chicken Popcorn', 'চিকেন পপকর্ণ', 3, 'Pack', 0.5, 'kg', 45, 443.75, 625, 181.25, 29, NULL, 1, NULL, NULL, NULL, 25, '2022-06-28 00:23:35', '2022-09-15 08:56:27'),
(22, 'fpp-chilled-marinated-crispy-strip_rLR6d2RF1663232179.png', '9001001191', 'FPP Chilled Marinated Crispy Strip', 'ক্রিস্পি চিকেন স্ট্রিপস', 3, 'Pack', 0.9, 'kg', 27, 693, 990, 297, 30, NULL, 1, NULL, NULL, NULL, 25, '2022-06-28 00:24:13', '2022-09-15 08:56:19'),
(23, 'large-brown-bag_6LRK1Atq1663232166.png', '9X01001027', 'Large Brown Bag', 'লারজ ব্রাউন ব্যাগ', 4, 'Pack', 0, NULL, 1, 7, 700, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-06-29 21:54:51', '2022-09-15 08:56:06'),
(24, 'medium-brown-bag_SqkzRpWw1663232138.png', '9X01001028', 'Medium Brown Bag', 'ব্রাউন পেপার মিডিয়াম', 4, NULL, 0, NULL, 1, 5, 500, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-06-29 21:57:32', '2022-09-15 08:55:39'),
(25, NULL, NULL, 'White Bag', 'সাদা ব্যাগ', 4, NULL, 0, NULL, 100, NULL, 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25, '2022-06-29 21:58:31', '2022-07-18 05:53:30'),
(26, 'sweet-chili-sauce-10-g_JaN0pqs61663232121.png', '9901001011', 'Sweet Chili Sauce 10 g', 'সুইট চিলি সস', 5, NULL, 0.01, 'kg', 1, 2.4, 300, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-06-29 22:01:09', '2022-09-15 08:55:21'),
(27, NULL, '9901001012', 'Tomato Ketchup 10 g', 'টমেটো কেচাপ', 5, 'Piece', 0, 'kg', 1, 3.4, 500, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-06-29 22:02:25', '2022-09-13 11:22:46'),
(30, 'meat-ball-stick-bamboo-l20cmxdia250mm-cn_WbdNvLem1663232110.png', '9X01001268', 'Meat Ball Stick Bamboo L20cmXDia2.50mm, CN', 'মিট বল স্টিক ব্যামবো', 3, 'Pack', 0, 'Kg', 1, 35, 35, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-03 06:06:45', '2022-09-15 08:55:10'),
(31, 'fpp-chilled-marinated-maxican-wing_Us6S8fpv1663232096.png', '9001001181', 'FPP Chilled Marinated Maxican Wing', 'মেক্সিকান উইংস', 1, 'Pack', 1.1, 'kg', 1, 460, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:15:14', '2022-09-15 08:54:57'),
(32, 'fpp-chilled-marinated-bbq-honey-wings_iG4y1Igl1663232084.png', '9001001186', 'FPP Chilled Marinated BBQ Honey Wings', 'BBQ হানি উইংস', 1, 'Pack', 1.1, 'kg', 1, 385, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:17:10', '2022-09-15 08:54:45'),
(33, NULL, '9201001025', 'Green Chili Sausage 1000 g', 'গ্রিন চিলি সসেজ', 3, 'Pack', 1, 'kg', 1, 600, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:21:55', '2022-09-13 11:22:46'),
(34, NULL, '9401001005', 'CP Chicken Nuggets Chili 320gm', 'চিকেন নাগেটস', 2, 'Pack', 0.32, 'kg', 1, 224.25, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:30:53', '2022-09-13 11:22:46'),
(35, NULL, '9101001025', 'Black Pepper Chicken Ball 550 g-F', 'ব্ল্যাক পেপার চিকেন বল', 3, 'Pack', 0.55, 'kg', 1, 311.25, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:32:23', '2022-09-13 11:22:46'),
(36, NULL, '9201001042', 'Black Pepper Sausage  340g', 'ব্ল্যাক পেপার সসেজ', 3, 'Pack', 0.34, 'kg', 1, 198.75, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:33:54', '2022-09-13 11:22:46'),
(37, 'chicken-stick-250-g-mt_PNT1JCJo1663232028.png', '9401001015', 'Chicken Stick 250 g MT', 'চিকেন স্টিক', 3, 'Pack', 0.25, 'kg', 1, 172.5, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:38:06', '2022-09-15 08:53:49'),
(38, 'delight-crispy-chicken-popcorn-250g_cv1AJjg11663232016.png', '9401001020', 'Delight Crispy Chicken Popcorn-250g', 'ডিলাইট ক্রিস্পি চিকেন পপকর্ণ', 3, 'Pack', 0.25, 'kg', 1, 171.75, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:39:59', '2022-09-15 08:53:37'),
(39, 'spicy-chicken-ball-500-g_pXBjrKsE1663231992.png', '9101001003', 'Spicy Chicken Ball -500 g', 'স্পাইসি চিকেন বল', 3, 'Pack', 0.5, 'kg', 1, 210.6, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:42:08', '2022-09-15 08:53:12'),
(40, 'cp-chicken-hot-sausage-400-g_1LswJgR61663232003.png', '9201001009', 'CP Chicken Hot Sausage  400 g', 'সিপি সিকেন হট সসেজ', 3, 'Pack', 0.4, 'kg', 1, 210.6, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:43:14', '2022-09-15 08:53:24'),
(41, NULL, '9901001018', 'BBQ Honey Sauce 1 kg/pack', 'BBQ হানি সস', 3, 'Pack', 1, 'kg', 1, 470, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:44:16', '2022-09-13 11:22:46'),
(42, NULL, '9X01001275', 'Spicy BBQ Seasoning (SM-G05582)-500g', 'স্পাইসি BBQ সিসনিং', 3, 'Pack', 0.5, 'kg', 1, 800, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:48:38', '2022-09-13 11:22:46'),
(43, 'burger-bun-60-g_oKFGE7FT1663231952.png', '9X01001124', 'Burger Bun 60 g', 'বার্গার বান ৬০ গ্রাম', 3, 'Piece', 0.06, 'kg', 1, 18.5, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:49:42', '2022-09-15 08:52:32'),
(44, 'mayonnaise-naples-970ml_7cfyM6rB1663231918.png', '9X01001259', 'Mayonnaise Naples 970ml', 'ম্যেয়নিস ন্যাপলস', 3, 'Bootle', 0, 'kg', 1, 292, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:52:10', '2022-09-15 08:51:58'),
(45, 'spicy-mexicano-seasoning-200g_piRfuysm1663231906.png', '9X01001269', 'Spicy Mexicano Seasoning-200g', 'স্পাইসি ম্যাক্সিকান সিসনিং', 3, 'Pack', 0.2, 'kg', 1, 400, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:53:56', '2022-09-15 08:51:46'),
(46, 'yummy-fried-rice-250gpack_a38yO71P1663231891.png', '9601001037', 'Yummy Fried Rice 250g/Pack', 'ইয়াম্মি ফ্রাইড রাইস', 3, 'Pack', 0.25, 'kg', 1, 95, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:57:54', '2022-09-15 08:51:31'),
(47, NULL, '9X01001029', 'Paper Bag White Offset 5.5\"x5.7\" Print 60GSM', 'সাদা অফসেট পেপার ব্যাগ', 4, 'Pack', 0, NULL, 1, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 03:59:55', '2022-09-13 11:22:46'),
(48, 'paper-bag-brown-small_8pskvNn31663231866.png', '9X01001155', 'Paper Bag Brown (Small)', 'পেপার ব্যাগ ব্রাউন (ছোট)', 4, 'Pack', 0, NULL, 1, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:05:23', '2022-09-15 08:51:06'),
(49, 'bond-paper-red-50-gsm-3c-39x40_6TEqr9zu1663231854.png', '9PA1001072', 'Bond Paper Red 50 GSM 3C 3.9\"x4.0\"', 'বন্ড পেপার রেড ৫০ জিএসএম ৩.৯\'\'x৪.০\'\'', 4, 'Piece', 0, NULL, 1, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:07:41', '2022-09-15 08:50:55'),
(50, 'pack-french-fries-220gsm-paper-w95d90mm_ExvdDOJ91663231842.png', '9X01001258', 'Pack French Fries 220GSM Paper W95×D90mm', 'প্যাক ফ্রেঞ্চ ফ্রাইস ২২০ জিএসএম পেপার', 4, 'Piece', 0, NULL, 1, 9, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:09:23', '2022-09-15 08:50:43'),
(51, 'burger-wrap-l16-x-w12-50-pcspack_Yk07WhFR1663231832.png', '9PA1001114', 'Burger Wrap  L16\" x W12\" 50 Pcs/Pack', 'বার্গার র‍্যাপ ১৬\'\'x১২\'\' ৫০পিস/প্যাক', 4, 'Pack', 0, NULL, 1, 220, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:11:02', '2022-09-15 08:50:32'),
(52, NULL, '9PA1001115', 'Tray Boat L 7.3\" x W5.3\" 10 Pcs/Pack', 'ট্রে বোট ৭.৩\'\'x৫.৩\'\' ১০পিস/প্যাক', 7, 'Pack', 0, NULL, 1, 100, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:12:23', '2022-09-13 11:22:46'),
(53, 'fpp-chilled-crispy-chicken-ball_sMu03GKS1663232216.png', '9101001042', 'FPP Chilled Crispy Chicken Ball', 'চিল্ড ক্রিস্পি চিকেন বল', 2, 'Pack', 1, 'kg', 1, 450, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:13:23', '2022-09-15 08:56:57'),
(54, 'jersey-polo-pp-fabric-180gsm-half-sleeve_1qyOGS2b1663231782.png', '9X01001271', 'Jersey Polo PP Fabric 180GSM Half Sleeve', 'জারসি পোলো ১৮০জিএসএম হাফ স্লিভ', 6, 'Piece', 0, NULL, 1, 370, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:15:39', '2022-09-15 08:49:43'),
(55, 'food-thermometer-tp300-digital-probe-type_lqtjDOFw1663231768.png', '9X01001261', 'Food Thermometer TP300 Digital Probe Type', 'ফুড থার্মোমিটার টিপি৩০০ ডিজিটাল প্রব টাইপ', 7, 'Piece', 0, NULL, 1, 400, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:18:24', '2022-09-15 08:49:28'),
(56, 'burger-frying-basket-ss-304-l11xw9xh5_kQ5Qzyxn1663231750.png', '9X01001246', 'Burger Frying Basket SS 304 L11\"XW9\"XH5\"', 'বার্গার ফ্রাইং বাস্কেট', 7, 'Piece', 0, NULL, 1, 2300, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:35:45', '2022-09-15 08:49:11'),
(57, 'orange-t-shirt-polo-cp-half-sl-2s-rubber-print-210gsm_Fig0wOnw1663231739.png', '9X01001262', 'Orange T-Shirt Polo CP Half SL 2S Rubber Print 210GSM', 'অরেঞ্জ টি শার্ট পোলো সিপি হাফ স্লিভ', 6, 'Piece', 0, NULL, 1, 350, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:37:06', '2022-09-15 08:48:59'),
(58, 'cap-cp-branded-net-cotton_IJXNaHcO1663231699.png', '9X01001263', 'Cap CP Branded Net & Cotton', 'ক্যাপ সিপি ব্রান্ডেড নেট এন্ড কটন', 6, 'Piece', 0, NULL, 1, 110, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:38:17', '2022-09-15 08:48:19'),
(59, 'apron-twill-300-350gsm-w253xh264-cp-and-five-star-logo_8TrSa6K61663231689.png', '9X01001264', 'Apron Twill 300-350gsm W25.3\"xH26.4\" CP and Five Star Logo', 'এপ্রোন টিউইল ৩০০-৩৫০ জিএহএম্ম২৫.৩\'\'x২৬.৪\'\' চিপি এন্ড ফাইভ স্টার লোগো', 6, 'Piece', 0, NULL, 1, 280, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:40:56', '2022-09-15 08:48:09'),
(60, 'apron-plastic-42x24-150gsm-cp-logo_BmRZkx8B1663231678.png', '9X01001265', 'Apron Plastic 42\"x24\" 150GSM CP Logo', 'এপ্রোন প্লাস্টিক ৪২\'\'x২৪\'\' ১৫০ জিএসএম সিপি লোগো', 6, 'Piece', 0, NULL, 1, 280, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:43:59', '2022-09-15 08:47:58'),
(61, NULL, '9X01001266', 'Board Laminated Wood Frame 24\"x16\" BD', 'বোর্ড লেমিনেটেড কাঠের ফ্রেম 24\"x16\" বিডি', 7, 'Piece', 0, NULL, 1, 537, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:45:25', '2022-09-13 11:22:46'),
(62, 'basket-fryer-ss304-l11x-w9-x-h55_1yb8nIoL1663231629.png', '9X01001273', 'Basket Fryer SS304 L11\"X W9\" X H5.5', 'বাস্কেট ফ্রায়ার SS304 L11\"X W9\" X H5.5', 7, 'Piece', 0, NULL, 1, 2260, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:46:37', '2022-09-15 08:47:09'),
(63, 'tong-ss-red-5mmx14-bd_bAEBYTZp1663231614.png', '9X01001142', 'Tong SS Red 5mmx14\" BD', 'টং এসএস রেড 5mmx14\" বিডি', 7, 'Piece', 0, NULL, 1, 581, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:47:32', '2022-09-15 08:46:54'),
(64, 'triangular-spatula-ss-l65mmx107mm-bd_Jyi0lefx1663231601.png', '9X01001144', 'Triangular Spatula SS L65mmx107mm BD', 'ট্রাইঅ্যাঙ্গুলার স্প্যাটুলা SS L65mmx107mm BD', 7, 'Piece', 0, NULL, 1, 237, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:49:33', '2022-09-15 08:46:41'),
(65, 'bottle-spray-plastic-2ltr-cn_gNXEjeNy1663231568.png', '9X01001146', 'Bottle Spray Plastic 2Ltr CN', 'বোতল স্প্রে প্লাস্টিক 2Ltr CN', 7, 'Bootle', 0, NULL, 1, 321, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 25, '2022-09-12 04:51:01', '2022-09-15 08:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `product_order_types`
--

CREATE TABLE `product_order_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_temp` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_order_types`
--

INSERT INTO `product_order_types` (`id`, `name_en`, `name_bn`, `deleted_temp`, `deleted_by`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Marinated', 'ম্যারিনেটেড', NULL, NULL, 1, 25, '2022-08-13 00:44:14', '2022-08-13 00:44:14'),
(2, 'Chilled', 'চিলড', NULL, NULL, 1, 25, '2022-08-13 00:45:04', '2022-08-13 00:45:04'),
(3, 'Others', 'অন্যান্য', NULL, NULL, 1, 25, '2022-08-13 00:45:34', '2022-08-13 00:45:34'),
(4, 'Packaging', 'প্যাকেজিং', NULL, NULL, 1, 25, '2022-08-13 00:46:07', '2022-08-13 03:06:07'),
(5, 'Taste Maker', 'টেস্ট মেকার', NULL, NULL, 1, 25, '2022-08-13 00:46:34', '2022-08-21 04:15:15'),
(6, 'Appereal', 'পোশাক', NULL, NULL, 1, 25, '2022-09-12 04:41:47', '2022-09-12 04:41:47'),
(7, 'Accessories', 'আনুষাঙ্গিক', NULL, NULL, 1, 25, '2022-09-12 04:52:49', '2022-09-12 04:52:49');

-- --------------------------------------------------------

--
-- Table structure for table `product_sales`
--

CREATE TABLE `product_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `product_order_id` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `price_offer` int(11) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_per_set` int(11) DEFAULT NULL,
  `free_item` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_temp` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `priority_sale` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_sales`
--

INSERT INTO `product_sales` (`id`, `name_en`, `name_bn`, `type_id`, `product_order_id`, `price`, `price_offer`, `image`, `quantity_per_set`, `free_item`, `deleted_temp`, `deleted_by`, `created_by`, `status`, `priority_sale`, `created_at`, `updated_at`) VALUES
(1, 'Karaage Chicken', 'কারাগে চিকেন', 4, 6, 120, 60, 'hDuC3yxx1650691001.png', 4, NULL, NULL, NULL, 25, 1, NULL, '2022-04-22 23:16:42', '2022-08-06 23:21:58'),
(2, 'Spicy Ball', 'স্পাইসি বল', 4, 7, 35, NULL, 'apiF01As1650691096.png', 4, NULL, NULL, NULL, 25, 1, NULL, '2022-04-22 23:18:16', '2022-06-30 02:59:49'),
(3, 'Hot Sausage', 'হট সসেজ', 4, 8, 30, NULL, 'LAIBUhUs1650691132.png', 4, NULL, NULL, NULL, 25, 1, NULL, '2022-04-22 23:18:52', '2022-06-30 03:00:19'),
(4, 'Lollipop Bologna', 'ললিপপ বোলগনা', 4, 11, 59, NULL, 'rSfrCGYj1650691170.png', NULL, NULL, NULL, NULL, 25, 1, NULL, '2022-04-22 23:19:30', '2022-06-28 05:37:25'),
(5, 'Hot Wings', 'হট উইংস', 4, 5, 120, NULL, 'WDJxEKPX1650691198.png', 4, '29', NULL, NULL, 25, 1, NULL, '2022-04-22 23:19:58', '2022-06-30 03:58:46'),
(6, 'Chicken Nuggets', 'চিকেন নাগেটস', 4, 9, 99, NULL, 'eePhbyVx1650691224.png', 5, NULL, NULL, NULL, 25, 1, NULL, '2022-04-22 23:20:24', '2022-06-30 03:00:55'),
(7, 'Five Star Burger', 'ফাইভ স্টার বার্গার', 4, 17, 130, NULL, 'zM0rblJJ1650691249.png', NULL, NULL, NULL, NULL, 25, NULL, NULL, '2022-04-22 23:20:49', '2022-07-18 05:56:15'),
(8, 'French Fries', 'ফ্রেঞ্চ ফ্রাইজ', 4, 20, 99, NULL, 'WUwTleKq1650691274.png', NULL, NULL, NULL, NULL, 25, 1, NULL, '2022-04-22 23:21:14', '2022-06-28 05:36:31'),
(9, 'Mexican Wings', 'মেক্সিকান উইংস', 4, NULL, 120, NULL, '3VzFNA7y1650691312.png', NULL, NULL, NULL, NULL, 25, NULL, NULL, '2022-04-22 23:21:52', '2022-07-18 05:56:26'),
(10, 'BBQ Honey Wings', 'বি.বি.কিউ. হানি উইংস', 4, 32, 140, NULL, 'DtQRds7N1650691348.png', NULL, NULL, NULL, NULL, 25, NULL, NULL, '2022-04-22 23:22:28', '2022-09-13 09:07:46'),
(11, 'Yummy Fried Rice', 'ইয়াম্মি ফ্রাইড রাইস', 4, 12, 125, NULL, '8A4mY7xI1650691375.png', NULL, NULL, NULL, NULL, 25, 1, NULL, '2022-04-22 23:22:55', '2022-06-28 05:34:38'),
(12, 'Egg Pudding', 'এগ পুডিং', 4, 13, 50, NULL, 'egg-pudding_tqUaLHax1663229537.png', NULL, NULL, NULL, NULL, 25, 1, NULL, '2022-04-22 23:23:19', '2022-09-15 08:12:17'),
(13, 'Crispy Chicken Strips', 'ক্রিস্পি চিকেন স্ট্রিপস', 4, 22, 110, NULL, 'JOmqwHvu1650691440.png', 3, NULL, NULL, NULL, 25, 1, NULL, '2022-04-22 23:24:00', '2022-06-30 03:03:19'),
(14, 'Chicken Popcorn', 'চিকেন পপকর্ণ', 4, 21, 125, NULL, 'ASn2Saq91650691467.png', 9, NULL, NULL, NULL, 25, 1, NULL, '2022-04-22 23:24:27', '2022-06-30 03:03:03'),
(15, 'Thigh', 'থাই', 1, 1, 120, 90, 'thigh_bVHegq3i1663229516.png', NULL, '29', NULL, NULL, 25, 1, 16, '2022-04-22 23:25:17', '2022-09-15 10:56:48'),
(16, 'Rib', 'রিব', 1, 1, 110, NULL, 'rib_4QUk4s2z1663229482.png', NULL, '29', NULL, NULL, 25, 1, 18, '2022-04-22 23:25:42', '2022-09-15 10:56:48'),
(17, 'Breast', 'ব্রেস্ট', 1, 1, 100, NULL, 'breast_3nu9FGXv1663229467.png', NULL, '29', NULL, NULL, 25, 1, NULL, '2022-04-22 23:26:23', '2022-09-15 08:11:07'),
(18, 'Leg', 'লেগ', 1, 2, 90, NULL, 'leg_Icx10Mwf1663229456.png', NULL, '29', NULL, NULL, 25, 1, NULL, '2022-04-22 23:26:56', '2022-09-15 08:10:56'),
(19, 'Wing', 'উইং', 1, 1, 70, 50, 'wing_LA9CiV8R1663229435.png', NULL, '29', NULL, NULL, 25, 1, NULL, '2022-04-22 23:27:52', '2022-09-15 08:10:35'),
(20, 'Breast', 'ব্রেস্ট', 2, 3, 120, NULL, 'breast_IHJyLrQt1663229422.png', NULL, '29', NULL, NULL, 25, 1, NULL, '2022-04-22 23:28:28', '2022-09-15 08:10:22'),
(21, 'Rib', 'রিব', 2, 3, 120, NULL, 'rib_ooRH6Fk11663229374.png', NULL, '29', NULL, NULL, 25, 1, NULL, '2022-04-22 23:29:21', '2022-09-15 08:09:35'),
(22, 'Thigh', 'থাই', 2, 3, 120, NULL, 'thigh_0Tc5ZM8l1663229354.png', NULL, '29', NULL, NULL, 25, 1, NULL, '2022-04-22 23:29:48', '2022-09-15 08:09:14'),
(23, 'Leg', 'লেগ', 2, 3, 100, NULL, 'leg_ZhZNXlSZ1663229344.png', NULL, '29', NULL, NULL, 25, 1, NULL, '2022-04-22 23:30:13', '2022-09-15 08:09:04'),
(24, 'Breast', 'ব্রেস্ট', 3, 4, 120, NULL, 'breast_UeOs5ze01663229285.png', NULL, '29', NULL, NULL, 25, 1, NULL, '2022-04-22 23:32:58', '2022-09-15 08:08:05'),
(25, 'Rib', 'রিব', 3, 4, 120, NULL, 'rib_15C0mYFj1663229401.png', NULL, '29', NULL, NULL, 25, 1, NULL, '2022-04-22 23:34:07', '2022-09-15 08:10:02'),
(26, 'Thigh', 'থাই', 3, 4, 120, NULL, 'thigh_5UNV4L0t1663229230.png', NULL, '29', NULL, NULL, 25, 1, NULL, '2022-04-22 23:34:38', '2022-09-15 08:07:10'),
(27, 'Leg', 'লেগ', 3, 4, 100, NULL, 'leg_r05fLei61663229150.png', NULL, '29', NULL, NULL, 25, 1, NULL, '2022-04-22 23:35:06', '2022-09-15 08:05:51'),
(29, 'Sweet Chili Sause', 'সুইট চিলি সস', 6, 26, 3, NULL, 'qzO3kUJT1663059693.png', NULL, NULL, NULL, NULL, 25, 1, NULL, '2022-06-29 22:44:11', '2022-09-13 09:01:33'),
(30, 'Ketchup', 'কেচাপ', 6, 27, 3, NULL, 'MeW76fkY1663059682.png', NULL, NULL, NULL, NULL, 25, 1, NULL, '2022-06-29 22:45:01', '2022-09-13 09:01:23'),
(31, 'Coca-Cola', 'কোকাকোলা', 7, NULL, 20, NULL, 'coca-cola_ehSeK20P1663234392.png', NULL, NULL, NULL, NULL, 25, 1, NULL, '2022-06-29 23:39:12', '2022-09-15 09:33:12'),
(32, 'Sprite', 'স্প্রাইট', 7, NULL, 20, NULL, 'sprite_veznBHY91663234383.png', NULL, NULL, NULL, NULL, 25, 1, NULL, '2022-06-29 23:39:54', '2022-09-15 09:33:03'),
(33, 'Fanta', 'ফ্যানটা', 7, NULL, 20, NULL, 'fanta_ql4vMeWc1663234373.png', NULL, NULL, NULL, NULL, 25, 1, NULL, '2022-06-29 23:40:37', '2022-09-15 09:32:54'),
(34, 'Kinley', 'কিনলে', 7, 28, 15, NULL, 'kinley_yf0TS9BU1663234364.png', NULL, NULL, NULL, NULL, 25, 1, NULL, '2022-06-29 23:41:26', '2022-09-15 09:32:44');

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_temp` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`id`, `name_en`, `name_bn`, `deleted_temp`, `deleted_by`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Crispy Chicken', 'ক্রিস্পি চিকেন', NULL, NULL, 1, 518, '2022-04-21 02:56:49', '2022-04-21 02:56:49'),
(2, 'Spicy Maxx', 'স্পাইসি ম্যাক্স', NULL, NULL, 1, 518, '2022-04-21 02:57:13', '2022-04-21 02:57:13'),
(3, 'Spicy Chicken', 'স্পাইসি চিকেন', NULL, NULL, 1, 518, '2022-04-21 02:57:31', '2022-04-21 02:57:31'),
(4, 'Regular', 'রেগুলার', NULL, NULL, 1, 518, '2022-04-22 22:21:51', '2022-08-06 23:25:12'),
(6, 'Taste Maker', 'টেস্ট মেকার', NULL, NULL, 1, 25, '2022-06-29 22:40:47', '2022-08-13 03:05:03'),
(7, 'Drinks', 'ড্রিঙ্কস', NULL, NULL, 1, 25, '2022-06-29 22:41:08', '2022-08-13 03:04:52'),
(8, 'o', 'ju', 1, 25, 1, 25, '2022-09-20 03:15:34', '2022-09-20 03:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'administrator', 518, '2022-06-19 03:26:15', '2022-06-19 03:26:15'),
(3, 'Admin', 'admin', 25, '2022-06-13 00:58:06', '2022-09-18 05:13:51'),
(17, 'Announcement-Add', 'announcement-add', 25, '2022-08-02 00:50:19', '2022-09-18 08:04:37'),
(19, 'Message-View', 'message-view', 25, '2022-09-16 10:23:12', '2022-09-18 08:15:36'),
(20, 'Order-View', 'order-view', 25, '2022-09-16 10:26:24', '2022-09-18 09:13:13'),
(21, 'Login-Log-View', 'login-log-view', 25, '2022-09-16 10:27:51', '2022-09-18 05:15:17'),
(26, 'Report', 'report', 25, '2022-09-16 12:03:57', '2022-09-18 05:18:22'),
(27, 'Data Sync-View', 'data-sync-view', 25, '2022-09-16 12:07:20', '2022-09-18 08:33:39'),
(28, 'Admin-View', 'admin-view', 25, '2022-09-17 05:55:38', '2022-09-18 05:14:57'),
(29, 'Admin-Edit', 'admin-edit', 25, '2022-09-17 05:55:56', '2022-09-18 05:14:44'),
(30, 'Admin-Status', 'admin-status', 25, '2022-09-17 06:01:24', '2022-09-18 05:14:54'),
(31, 'Admin-Role-Assign', 'admin-role-assign', 25, '2022-09-17 06:03:02', '2022-09-18 05:14:46'),
(33, 'Admin-Zone-Assign', 'admin-zone-assign', 25, '2022-09-17 06:08:07', '2022-09-18 05:15:02'),
(34, 'Admin-Add', 'admin-add', 25, '2022-09-17 06:43:33', '2022-09-18 05:14:41'),
(35, 'Operator-View', 'operator-view', 25, '2022-09-17 11:22:20', '2022-09-18 05:17:17'),
(36, 'Operator-Add', 'operator-add', 25, '2022-09-17 11:22:28', '2022-09-18 05:15:26'),
(37, 'Operator-Edit', 'operator-edit', 25, '2022-09-17 11:22:37', '2022-09-18 05:15:35'),
(38, 'Operator-Status', 'operator-status', 25, '2022-09-17 11:22:48', '2022-09-18 05:15:38'),
(39, 'Owner-View', 'owner-view', 25, '2022-09-17 11:31:15', '2022-09-18 05:17:50'),
(40, 'Owner-Add', 'owner-add', 25, '2022-09-17 11:31:22', '2022-09-18 05:17:40'),
(41, 'Owner-Edit', 'owner-edit', 25, '2022-09-17 11:31:34', '2022-09-18 05:17:43'),
(42, 'Owner-Status', 'owner-status', 25, '2022-09-17 11:31:48', '2022-09-18 05:17:46'),
(43, 'Zone-View', 'zone-view', 25, '2022-09-17 11:36:33', '2022-09-18 05:18:36'),
(44, 'Zone-Add', 'zone-add', 25, '2022-09-17 11:36:38', '2022-09-18 05:18:29'),
(45, 'Zone-Edit', 'zone-edit', 25, '2022-09-17 11:36:45', '2022-09-18 05:18:32'),
(46, 'Zone-Status', 'zone-status', 25, '2022-09-17 11:36:57', '2022-09-18 05:18:34'),
(47, 'Outlet-View', 'outlet-view', 25, '2022-09-17 11:47:41', '2022-09-18 05:17:37'),
(48, 'Outlet-Add', 'outlet-add', 25, '2022-09-17 11:47:52', '2022-09-18 05:17:22'),
(49, 'Outlet-Edit', 'outlet-edit', 25, '2022-09-17 11:47:59', '2022-09-18 05:17:30'),
(50, 'Outlet-Status', 'outlet-status', 25, '2022-09-17 11:48:08', '2022-09-18 05:17:34'),
(51, 'Outlet-Delete', 'outlet-delete', 25, '2022-09-17 11:52:51', '2022-09-18 05:17:27'),
(52, 'Product Sale-View', 'product-sale-view', 25, '2022-09-18 04:10:40', '2022-09-18 05:18:19'),
(53, 'Product Sale-Add', 'product-sale-add', 25, '2022-09-18 04:10:57', '2022-09-18 05:18:08'),
(54, 'Product Sale-Status', 'product-sale-status', 25, '2022-09-18 04:11:17', '2022-09-18 05:18:16'),
(55, 'Product Sale-Edit', 'product-sale-edit', 25, '2022-09-18 04:11:27', '2022-09-18 05:18:14'),
(56, 'Product Sale-Delete', 'product-sale-delete', 25, '2022-09-18 04:16:36', '2022-09-18 05:18:11'),
(57, 'Product Order-Add', 'product-order-add', 25, '2022-09-18 04:39:47', '2022-09-18 05:17:53'),
(58, 'Product Order-Edit', 'product-order-edit', 25, '2022-09-18 04:39:55', '2022-09-18 05:18:01'),
(59, 'Product Order-Delete', 'product-order-delete', 25, '2022-09-18 04:40:03', '2022-09-18 05:17:56'),
(60, 'Product Order-View', 'product-order-view', 25, '2022-09-18 04:40:10', '2022-09-18 05:18:06'),
(61, 'Product Order-Status', 'product-order-status', 25, '2022-09-18 04:40:21', '2022-09-18 05:18:04'),
(62, 'Operator-Delete', 'operator-delete', 25, '2022-09-18 05:36:20', '2022-09-18 05:36:20'),
(63, 'Product Order Category-Add', 'product-order-category-add', 25, '2022-09-18 06:35:31', '2022-09-18 06:35:31'),
(64, 'Product Order Category-Edit', 'product-order-category-edit', 25, '2022-09-18 06:37:02', '2022-09-18 06:37:02'),
(65, 'Product Order Category-Status', 'product-order-category-status', 25, '2022-09-18 06:43:41', '2022-09-18 06:43:41'),
(66, 'Product Order Category-Delete', 'product-order-category-delete', 25, '2022-09-18 06:53:14', '2022-09-18 06:53:14'),
(67, 'Product Order Category-View', 'product-order-category-view', 25, '2022-09-18 06:54:04', '2022-09-18 06:54:04'),
(68, 'Product Sale Category-View', 'product-sale-category-view', 25, '2022-09-18 06:54:21', '2022-09-18 06:54:21'),
(69, 'Product Sale Category-Add', 'product-sale-category-add', 25, '2022-09-18 06:54:28', '2022-09-18 06:54:28'),
(70, 'Product Sale Category-Edit', 'product-sale-category-edit', 25, '2022-09-18 06:54:39', '2022-09-18 06:54:39'),
(71, 'Product Sale Category-Status', 'product-sale-category-status', 25, '2022-09-18 06:54:47', '2022-09-18 06:54:47'),
(72, 'Product Sale Category-Delete', 'product-sale-category-delete', 25, '2022-09-18 06:55:13', '2022-09-18 06:55:13'),
(73, 'Announcement-View', 'announcement-view', 25, '2022-09-18 08:01:09', '2022-09-18 08:01:09'),
(74, 'Announcement-Edit', 'announcement-edit', 25, '2022-09-18 08:04:44', '2022-09-18 08:04:44'),
(75, 'Announcement-Status', 'announcement-status', 25, '2022-09-18 08:04:50', '2022-09-18 08:04:50'),
(76, 'Announcement-Delete', 'announcement-delete', 25, '2022-09-18 08:04:59', '2022-09-18 08:04:59'),
(77, 'Message-Add', 'message-add', 25, '2022-09-18 08:15:42', '2022-09-18 08:15:42'),
(78, 'Message-Edit', 'message-edit', 25, '2022-09-18 08:15:49', '2022-09-18 08:15:49'),
(79, 'Message-Status', 'message-status', 25, '2022-09-18 08:15:57', '2022-09-18 08:15:57'),
(80, 'Message-Delete', 'message-delete', 25, '2022-09-18 08:16:04', '2022-09-18 08:16:04'),
(82, 'Order-Adjust Order View', 'order-adjust-order-view', 25, '2022-09-18 09:13:32', '2022-09-18 09:20:54'),
(83, 'Order-All Order View', 'order-all-order-view', 25, '2022-09-18 09:14:04', '2022-09-18 09:21:03'),
(84, 'Order-Place Order', 'order-place-order', 25, '2022-09-18 09:14:21', '2022-09-18 09:14:21'),
(85, 'Order-Modify Order', 'order-modify-order', 25, '2022-09-18 09:14:41', '2022-09-18 09:14:41'),
(86, 'Order-Manager Approve', 'order-manager-approve', 25, '2022-09-18 09:22:29', '2022-09-18 09:22:29'),
(87, 'Order-Admin Approve', 'order-admin-approve', 25, '2022-09-18 09:22:40', '2022-09-18 09:22:40'),
(88, 'Order-Status', 'order-status', 25, '2022-09-18 09:25:49', '2022-09-18 09:25:49'),
(89, 'Order-Payment', 'order-payment', 25, '2022-09-18 09:27:40', '2022-09-18 09:27:40'),
(90, 'Order-Details View', 'order-details-view', 25, '2022-09-18 09:29:41', '2022-09-18 09:29:41'),
(91, 'Data Sync-Order', 'data-sync-order', 25, '2022-09-19 03:08:55', '2022-09-19 03:08:55'),
(92, 'Data Sync-Price', 'data-sync-price', 25, '2022-09-19 03:09:02', '2022-09-19 03:09:23'),
(93, 'Data Sync-Outlet', 'data-sync-outlet', 25, '2022-09-19 03:09:10', '2022-09-19 03:09:10'),
(94, 'Admin-Delete', 'admin-delete', 25, '2022-09-19 12:00:37', '2022-09-19 12:00:37'),
(95, 'Owner-Delete', 'owner-delete', 25, '2022-09-19 12:01:33', '2022-09-19 12:01:33'),
(96, 'Zone-Delete', 'zone-delete', 25, '2022-09-19 12:02:17', '2022-09-19 12:02:17');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `cv_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `sales_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `free_item` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_summuries`
--

CREATE TABLE `sales_summuries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_date` datetime DEFAULT NULL,
  `sales_numb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cv_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_price` double(8,2) DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_age` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `status_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_tables`
--

CREATE TABLE `schedule_tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedule_tables`
--

INSERT INTO `schedule_tables` (`id`, `title`, `time`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'product-sync', '3:00,5:00', 1, 25, '2022-09-12 12:06:58', '2022-09-12 12:06:58');

-- --------------------------------------------------------

--
-- Table structure for table `smartsoft_invoices`
--

CREATE TABLE `smartsoft_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `INVOICE_DATE` datetime DEFAULT NULL,
  `INVOICE_NO` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CUSTOMER_CODE` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PRODUCT_CODE` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT NULL,
  `WEIGHT` double(15,2) DEFAULT NULL,
  `AMOUNT` double(15,2) DEFAULT NULL,
  `VAT` double(15,2) DEFAULT NULL,
  `NET_AMOUNT` double(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `smartsoft_outlets`
--

CREATE TABLE `smartsoft_outlets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cv_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `division` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telescope_entries`
--

CREATE TABLE `telescope_entries` (
  `sequence` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT 1,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telescope_entries_tags`
--

CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telescope_monitoring`
--

CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Product-Add', NULL, NULL),
(2, 'Product-Edit', NULL, NULL),
(3, 'Product-Update', NULL, NULL),
(4, 'Product-Delete', NULL, NULL),
(5, 'Zone-Add', NULL, NULL),
(6, 'Zone-Edit', NULL, NULL),
(7, 'Zone-Update', NULL, NULL),
(8, 'Zone-Delete', NULL, NULL),
(9, 'Product-Rifat', NULL, NULL),
(10, 'Test', NULL, NULL),
(11, 'Product Order-Add', NULL, NULL),
(12, 'Product Order-Edit', NULL, NULL),
(13, 'Product Order-Update', NULL, NULL),
(14, 'Product Order-Delete', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cv_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_login` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cv_code_owner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'op',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personal_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personal_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone_office` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `officer_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'of',
  `manager_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verify` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `verify_by` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `status_by` int(11) DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL,
  `deleted_temp` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `cv_code`, `owner_login`, `cv_code_owner`, `password`, `user_type`, `name`, `image`, `department`, `office_id`, `office_contact`, `personal_contact`, `office_email`, `personal_email`, `office`, `zone_office`, `business_unit`, `nid`, `officer_type`, `manager_id`, `verify`, `verify_by`, `status`, `status_by`, `last_activity`, `deleted_temp`, `deleted_by`, `created_at`, `updated_at`) VALUES
(25, 'syful.isl', NULL, NULL, NULL, '123', 'ad', 'Syful Islam-op', 'dMbV4bEh1658400509.jpeg', 'Application Development', 'BD0012486', NULL, '01707080401', 'syful@cpbangladesh.com', 'syful.cse.bd@gmail.com', 'Head Office', 'Head Office', 'Information Technology', '3288540101', 'mg', '15,23', '1', 25, 1, 25, '2022-09-20 05:38:00', NULL, NULL, '0000-00-00 00:00:00', '2022-09-20 05:33:55'),
(26, NULL, '182189', NULL, NULL, 'cpb123', 'op', 'Syful-Operator', 'w7QAQZNx1662977789.jpeg', 'Application Development', 'BD0012486', NULL, '01760430610', 'syful@cpbangladesh.com', 'syful.cse.bd@gmail.com', 'Head Office', 'Head Office', 'Information Technology', '3288540101', 'of', '15,23', '1', 25, 1, 25, '2022-09-19 09:17:00', NULL, NULL, '2022-08-17 04:37:47', '2022-09-19 09:12:41'),
(28, NULL, NULL, '01707080401', NULL, '1', 'wn', 'Syful-Owner', NULL, 'Application Development', 'BD0012486', NULL, '+8801707080401', 'syful@cpbangladesh.com', 'syful.cse.bd@gmail.com', 'Head Office', 'Head Office', 'Information Technology', '3288540101', 'of', '15,23', '1', 25, 1, 25, '2022-09-16 06:29:00', NULL, NULL, '2022-08-08 18:00:00', '2022-09-16 06:29:50'),
(566, NULL, '931599', NULL, NULL, 'cp12345@', 'op', 'Mr. Kamal', 'qumakCWe1663395255.png', NULL, NULL, NULL, '01717520990', NULL, NULL, NULL, NULL, NULL, NULL, 'of', NULL, '0', 25, 1, 25, NULL, NULL, NULL, '2022-06-22 03:45:00', '2022-09-19 08:30:53'),
(567, NULL, NULL, '01760430610', '182719,183369,183409,182189,186329,187349', '123456', 'wn', 'xxxxx', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', NULL, '0', 25, 1, NULL, '2022-09-18 10:32:00', NULL, NULL, '2022-06-27 00:57:50', '2022-09-18 10:32:59'),
(569, NULL, NULL, '01707080402', '189989,931979,931189', 'cp12345@', 'wn', 'ttttttt', NULL, NULL, NULL, NULL, '01707080402', NULL, NULL, NULL, NULL, NULL, NULL, 'of', NULL, '0', 25, 1, 25, NULL, NULL, NULL, '2022-06-27 03:02:21', '2022-08-28 05:00:24'),
(570, 'admin', NULL, NULL, NULL, NULL, 'ad', 'Sales Admin', 'ONZUpzPL1658313920.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mg', '25', '0', NULL, NULL, 25, NULL, NULL, NULL, '2022-06-28 04:51:42', '2022-09-15 11:41:26'),
(571, 'papel.mah', NULL, NULL, NULL, NULL, 'ad', 'Md. Papel Mahmud', '2f6sMUzQ1663240597.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mg', NULL, '0', NULL, 1, 25, NULL, NULL, NULL, '2022-07-19 22:52:59', '2022-09-15 11:16:37'),
(572, 'mahadi.has', NULL, NULL, NULL, NULL, 'ad', 'Mahadi Hasan Rasel', 'wOSBRF3z1663242074.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', '571', '0', NULL, 1, 25, NULL, NULL, NULL, '2022-07-19 22:54:32', '2022-09-15 11:41:14'),
(573, 'sohel.ran', NULL, NULL, NULL, NULL, 'ad', 'Md. Sohel Rana', 'ZoXhV9TV1663242346.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', '571', '0', NULL, 1, 25, NULL, NULL, NULL, '2022-07-19 23:16:27', '2022-09-15 11:45:46'),
(574, 'miraj.hos', NULL, NULL, NULL, NULL, 'ad', 'Md. Miraj Hossain', 'ZkmdZ1rG1663242019.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', '576', '0', NULL, 1, 25, NULL, NULL, NULL, '2022-07-19 23:18:14', '2022-09-15 11:40:19'),
(575, 'musabbir.asm', NULL, NULL, NULL, NULL, 'ad', 'Md. A.S.M Musabbir', 'Aq6bvs8L1663242007.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', '576', '0', NULL, 1, 25, NULL, NULL, NULL, '2022-07-19 23:19:19', '2022-09-15 11:43:06'),
(576, 'moazzem.hos', NULL, NULL, NULL, NULL, 'ad', 'Md. Moazzem Hossain', 'Gzq6PjJU1663240583.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mg', NULL, '0', NULL, 1, 25, NULL, NULL, NULL, '2022-07-19 23:19:47', '2022-09-15 11:16:23'),
(577, 'jubair.cho', NULL, NULL, NULL, NULL, 'ad', 'Md. Jubair Chowdhury', 'ZRLPUVmf1663306031.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mg', NULL, '0', NULL, 1, 25, NULL, NULL, NULL, '2022-07-20 00:04:24', '2022-09-16 05:27:11'),
(578, 'sahadat.oss', NULL, NULL, NULL, NULL, 'ad', 'Sahadat Hossain', '61tIjGwv1663393627.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', '577', '0', NULL, 1, 25, NULL, NULL, NULL, '2022-07-20 00:05:08', '2022-09-17 05:47:07'),
(579, 'uzzal', NULL, NULL, NULL, NULL, 'ad', 'Mr. Uzzal', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mg', NULL, '0', NULL, NULL, 25, NULL, NULL, NULL, '2022-07-20 00:17:29', '2022-09-15 11:39:30'),
(580, 'mezbah.udd', NULL, NULL, NULL, NULL, 'ad', 'Md. Mezbah Uddin', 'qORjXCKE1663241955.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', '588', '0', NULL, 1, 25, NULL, NULL, NULL, '2022-07-20 00:18:16', '2022-09-15 11:55:25'),
(581, 'sajib', NULL, NULL, NULL, NULL, 'ad', 'Mr. Sajib', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', '576', '0', NULL, NULL, 25, NULL, 1, 25, '2022-07-20 00:29:50', '2022-09-19 12:22:19'),
(582, 'fahad', NULL, NULL, NULL, NULL, 'ad', 'Mr. Fahad', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mg', NULL, '0', NULL, NULL, 25, NULL, 1, 25, '2022-07-20 00:32:50', '2022-09-19 12:22:12'),
(583, 'parvez.ahm', NULL, NULL, NULL, NULL, 'ad', 'Md. Parvez Ahmed Tipu', 'C40DCFwk1663242402.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', '571', '0', NULL, 1, 25, NULL, NULL, NULL, '2022-07-20 00:33:12', '2022-09-15 11:48:12'),
(586, NULL, '931999', NULL, NULL, 'cp12345@', 'op', 'Mr. Tamjid', 'Iu5c6EfV1663394994.png', NULL, NULL, NULL, '01776978023', NULL, NULL, NULL, NULL, NULL, NULL, 'of', NULL, '0', 25, 1, 25, '2022-09-16 06:38:00', NULL, NULL, '2022-07-21 04:50:10', '2022-09-19 08:29:22'),
(587, NULL, '932189', NULL, NULL, 'cp12345@', 'op', 'Ratan Datta', 'cXsAhKrr1663394931.png', NULL, NULL, NULL, '01944341117', NULL, NULL, NULL, NULL, NULL, NULL, 'of', NULL, '0', NULL, 1, 25, NULL, NULL, NULL, '2022-09-03 05:02:12', '2022-09-19 08:28:55'),
(588, 'pronob.mal', NULL, NULL, NULL, NULL, 'ad', 'Pronob Kanti Mallik', 'cD9bpI2Z1663240567.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mg', NULL, '0', NULL, 1, 25, '2022-09-16 10:36:00', NULL, NULL, '2022-09-15 11:16:07', '2022-09-16 10:36:21'),
(589, 'rizoneul.haq', NULL, NULL, NULL, NULL, 'ad', 'Rizoneul Haq Reza', 'ASB86KUF1663241812.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', '576', '0', NULL, 1, 25, NULL, NULL, NULL, '2022-09-15 11:22:47', '2022-09-15 11:50:46'),
(590, 'rayhan.kha', NULL, NULL, NULL, NULL, 'ad', 'Md.Rayhan Khan', 'Gh3oB3gl1663241801.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', '588', '0', NULL, 1, 25, NULL, NULL, NULL, '2022-09-15 11:23:23', '2022-09-15 11:54:40'),
(591, 'abdul.azi', NULL, NULL, NULL, NULL, 'ad', 'Md. Abdul Aziz', 'DXGDDIsy1663241778.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', '588', '0', NULL, 1, 25, '2022-09-20 05:32:00', NULL, NULL, '2022-09-15 11:24:19', '2022-09-20 05:32:12'),
(592, 'kamrul.slm', NULL, NULL, NULL, NULL, 'ad', 'Md. Kamrul Islam', 'H8mo4yal1663393665.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'of', '577', '0', NULL, 1, 25, '2022-09-19 11:34:00', NULL, NULL, '2022-09-17 05:47:45', '2022-09-19 11:34:26'),
(593, NULL, '932009', NULL, NULL, 'cp12345@', 'op', 'Mr. Arif', 'Mr. Arif_Uxev1DWT1663576110.png', NULL, NULL, NULL, '01926178418', NULL, NULL, NULL, NULL, NULL, NULL, 'of', NULL, '0', NULL, 1, 25, NULL, NULL, NULL, '2022-09-19 08:28:31', '2022-09-19 08:28:31'),
(594, NULL, '180859', NULL, NULL, 'cp12345@', 'op', 'Md Bachu Mia', 'Md Bachu Mia_glrV7kd01663576188.png', NULL, NULL, NULL, '01746506431', NULL, NULL, NULL, NULL, NULL, NULL, 'of', NULL, '0', NULL, 1, 25, NULL, NULL, NULL, '2022-09-19 08:29:49', '2022-09-19 08:29:49'),
(595, NULL, '180519', NULL, NULL, 'cp12345@', 'op', 'Mr. Mozammel', 'Mr. Mozammel_J7r4zeqD1663576234.png', NULL, NULL, NULL, '01716202818', NULL, NULL, NULL, NULL, NULL, NULL, 'of', NULL, '0', NULL, 1, 25, NULL, NULL, NULL, '2022-09-19 08:30:35', '2022-09-19 08:30:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_languages`
--

CREATE TABLE `user_languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_languages`
--

INSERT INTO `user_languages` (`id`, `user_id`, `language`, `status`, `created_at`, `updated_at`) VALUES
(1, 26, 'english', NULL, NULL, '2022-09-11 09:19:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_login_logs`
--

CREATE TABLE `user_login_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `login_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 25, 1, NULL, NULL, NULL),
(2, 592, 20, NULL, NULL, NULL),
(3, 592, 82, NULL, NULL, NULL),
(4, 592, 90, NULL, NULL, NULL),
(5, 592, 85, NULL, NULL, NULL),
(6, 592, 84, NULL, NULL, NULL),
(7, 592, 83, NULL, NULL, NULL),
(8, 592, 26, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_zones`
--

CREATE TABLE `user_zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_zones`
--

INSERT INTO `user_zones` (`id`, `user_id`, `zone_id`, `created_at`, `updated_at`) VALUES
(1, 572, 1, NULL, NULL),
(8, 25, 2, NULL, NULL),
(14, 571, 1, NULL, NULL),
(15, 581, 4, NULL, NULL),
(16, 581, 2, NULL, NULL),
(18, 579, 5, NULL, NULL),
(19, 577, 6, NULL, NULL),
(21, 576, 2, NULL, NULL),
(22, 582, 3, NULL, NULL),
(23, 574, 2, NULL, NULL),
(26, 575, 2, NULL, NULL),
(27, 578, 6, NULL, NULL),
(30, 573, 1, NULL, NULL),
(31, NULL, 1, NULL, NULL),
(32, NULL, 2, NULL, NULL),
(33, 585, 3, NULL, NULL),
(34, 585, 4, NULL, NULL),
(35, 585, 5, NULL, NULL),
(36, 585, 2, NULL, NULL),
(39, 570, 1, NULL, NULL),
(40, 570, 2, NULL, NULL),
(41, 570, 3, NULL, NULL),
(45, 25, 1, NULL, NULL),
(46, 25, 6, NULL, NULL),
(47, 25, 3, NULL, NULL),
(48, 588, 3, NULL, NULL),
(49, 583, 1, NULL, NULL),
(50, 589, 2, NULL, NULL),
(51, 591, 3, NULL, NULL),
(52, 590, 3, NULL, NULL),
(53, 580, 3, NULL, NULL),
(54, 592, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `deleted_temp` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`id`, `name`, `created_by`, `deleted_temp`, `deleted_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dhaka - 1', 518, NULL, NULL, 1, '2022-06-12 23:28:24', '2022-06-14 21:39:13'),
(2, 'Dhaka - 2', NULL, NULL, NULL, 1, '2022-06-14 21:39:40', '2022-06-14 21:39:40'),
(3, 'Dhaka - 3', 25, NULL, NULL, 1, '2022-06-14 21:39:50', '2022-09-13 09:14:39'),
(4, 'Dhaka - 4', 25, 1, 25, NULL, '2022-06-14 21:39:58', '2022-09-19 12:20:49'),
(5, 'Dhaka - 5', 25, 1, 25, NULL, '2022-06-14 21:40:08', '2022-09-19 12:20:45'),
(6, 'Chittagong', 25, NULL, NULL, 1, '2022-06-14 21:40:17', '2022-09-13 09:14:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `message_templates`
--
ALTER TABLE `message_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_summaries`
--
ALTER TABLE `order_summaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_sends`
--
ALTER TABLE `otp_sends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `outlets`
--
ALTER TABLE `outlets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `outlets_cv_code_unique` (`cv_code`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `product_orders`
--
ALTER TABLE `product_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_order_types`
--
ALTER TABLE `product_order_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sales`
--
ALTER TABLE `product_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_summuries`
--
ALTER TABLE `sales_summuries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule_tables`
--
ALTER TABLE `schedule_tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smartsoft_invoices`
--
ALTER TABLE `smartsoft_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smartsoft_outlets`
--
ALTER TABLE `smartsoft_outlets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `smartsoft_outlets_cv_code_unique` (`cv_code`);

--
-- Indexes for table `telescope_entries`
--
ALTER TABLE `telescope_entries`
  ADD PRIMARY KEY (`sequence`),
  ADD UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  ADD KEY `telescope_entries_batch_id_index` (`batch_id`),
  ADD KEY `telescope_entries_family_hash_index` (`family_hash`),
  ADD KEY `telescope_entries_created_at_index` (`created_at`),
  ADD KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`);

--
-- Indexes for table `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
  ADD KEY `telescope_entries_tags_tag_index` (`tag`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_login_unique` (`login`),
  ADD UNIQUE KEY `owner_login` (`owner_login`),
  ADD UNIQUE KEY `cv_code` (`cv_code`),
  ADD KEY `owner_login_2` (`owner_login`);
ALTER TABLE `users` ADD FULLTEXT KEY `owner_login_3` (`owner_login`);

--
-- Indexes for table `user_languages`
--
ALTER TABLE `user_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_zones`
--
ALTER TABLE `user_zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_templates`
--
ALTER TABLE `message_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_summaries`
--
ALTER TABLE `order_summaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_sends`
--
ALTER TABLE `otp_sends`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outlets`
--
ALTER TABLE `outlets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_orders`
--
ALTER TABLE `product_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `product_order_types`
--
ALTER TABLE `product_order_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_sales`
--
ALTER TABLE `product_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_summuries`
--
ALTER TABLE `sales_summuries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule_tables`
--
ALTER TABLE `schedule_tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `smartsoft_invoices`
--
ALTER TABLE `smartsoft_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `smartsoft_outlets`
--
ALTER TABLE `smartsoft_outlets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `telescope_entries`
--
ALTER TABLE `telescope_entries`
  MODIFY `sequence` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163623;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=596;

--
-- AUTO_INCREMENT for table `user_languages`
--
ALTER TABLE `user_languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_zones`
--
ALTER TABLE `user_zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
