-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 11, 2026 at 06:41 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skopx`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED DEFAULT NULL,
  `certificate_number` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_at` timestamp NOT NULL,
  `payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `user_id`, `subscription_id`, `certificate_number`, `issued_at`, `payload`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'THK-CERT-2026-B4QDLEKV', '2026-09-11 00:15:33', '{\"courses\": [\"Product (Education / Training) Masterclass\", \"Yoga (Exercise) Masterclass\", \"Zumba (Exercise) Masterclass\"], \"user_name\": \"uttam kumar mohanta\", \"categories\": [\"Education & Skills\", \"Health & Fitness\"], \"subscription_reference\": 1}', '2026-09-11 00:15:33', '2026-09-11 00:15:33');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `product_id`, `title`, `slug`, `description`, `thumbnail`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Product (Education / Training) Masterclass', 'product-education-training-masterclass', 'Comprehensive short-video educational program on Product (Education / Training). Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:04', '2026-09-11 00:04:04'),
(2, 2, 'Yoga (Exercise) Masterclass', 'yoga-exercise-masterclass', 'Comprehensive short-video educational program on Yoga (Exercise). Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:04', '2026-09-11 00:04:04'),
(3, 3, 'Zumba (Exercise) Masterclass', 'zumba-exercise-masterclass', 'Comprehensive short-video educational program on Zumba (Exercise). Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:04', '2026-09-11 00:04:04'),
(4, 4, 'Fashion (Training) Masterclass', 'fashion-training-masterclass', 'Comprehensive short-video educational program on Fashion (Training). Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:04', '2026-09-11 00:04:04'),
(5, 5, 'Cultivation Masterclass', 'cultivation-masterclass', 'Comprehensive short-video educational program on Cultivation. Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:04', '2026-09-11 00:04:04'),
(6, 6, 'Podcast Masterclass', 'podcast-masterclass', 'Comprehensive short-video educational program on Podcast. Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:05', '2026-09-11 00:04:05'),
(7, 7, 'Cook (Home Delivery) Masterclass', 'cook-home-delivery-masterclass', 'Comprehensive short-video educational program on Cook (Home Delivery). Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:05', '2026-09-11 00:04:05'),
(8, 8, 'Cartoon (Kids) Masterclass', 'cartoon-kids-masterclass', 'Comprehensive short-video educational program on Cartoon (Kids). Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:05', '2026-09-11 00:04:05'),
(9, 9, 'Health Masterclass', 'health-masterclass', 'Comprehensive short-video educational program on Health. Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:05', '2026-09-11 00:04:05'),
(10, 10, 'Education Masterclass', 'education-masterclass', 'Comprehensive short-video educational program on Education. Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:05', '2026-09-11 00:04:05'),
(11, 11, 'Economic Masterclass', 'economic-masterclass', 'Comprehensive short-video educational program on Economic. Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:05', '2026-09-11 00:04:05'),
(12, 12, 'Classification Masterclass', 'classification-masterclass', 'Comprehensive short-video educational program on Classification. Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:05', '2026-09-11 00:04:05'),
(13, 13, 'Devotion Masterclass', 'devotion-masterclass', 'Comprehensive short-video educational program on Devotion. Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:05', '2026-09-11 00:04:05'),
(14, 14, 'Entertainment Masterclass', 'entertainment-masterclass', 'Comprehensive short-video educational program on Entertainment. Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:05', '2026-09-11 00:04:05'),
(15, 15, 'Sports (Local) Masterclass', 'sports-local-masterclass', 'Comprehensive short-video educational program on Sports (Local). Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:05', '2026-09-11 00:04:05'),
(16, 16, 'Story (Billion/Story) Masterclass', 'story-billionstory-masterclass', 'Comprehensive short-video educational program on Story (Billion/Story). Learn key skills and best practices.', NULL, 1, '2026-09-11 00:04:05', '2026-09-11 00:04:05');

-- --------------------------------------------------------

--
-- Table structure for table `course_videos`
--

CREATE TABLE `course_videos` (
  `id` bigint UNSIGNED NOT NULL,
  `course_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_seconds` int UNSIGNED NOT NULL DEFAULT '120',
  `required_watch_percentage` tinyint UNSIGNED NOT NULL DEFAULT '80',
  `sort_order` int UNSIGNED NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_videos`
--

INSERT INTO `course_videos` (`id`, `course_id`, `title`, `description`, `video_url`, `duration_seconds`, `required_watch_percentage`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Introduction to Product (Education / Training)', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:04', '2026-09-11 00:04:04'),
(2, 1, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(3, 1, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(4, 1, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(5, 2, 'Introduction to Yoga (Exercise)', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(6, 2, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(7, 2, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(8, 2, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(9, 3, 'Introduction to Zumba (Exercise)', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(10, 3, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(11, 3, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(12, 3, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(13, 4, 'Introduction to Fashion (Training)', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(14, 4, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(15, 4, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(16, 4, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(17, 5, 'Introduction to Cultivation', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(18, 5, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(19, 5, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:04', '2026-09-11 00:12:27'),
(20, 5, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(21, 6, 'Introduction to Podcast', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(22, 6, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(23, 6, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(24, 6, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(25, 7, 'Introduction to Cook (Home Delivery)', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(26, 7, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(27, 7, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(28, 7, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(29, 8, 'Introduction to Cartoon (Kids)', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(30, 8, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(31, 8, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(32, 8, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(33, 9, 'Introduction to Health', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(34, 9, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(35, 9, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(36, 9, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(37, 10, 'Introduction to Education', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(38, 10, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(39, 10, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(40, 10, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(41, 11, 'Introduction to Economic', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(42, 11, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(43, 11, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(44, 11, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(45, 12, 'Introduction to Classification', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(46, 12, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(47, 12, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(48, 12, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(49, 13, 'Introduction to Devotion', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(50, 13, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(51, 13, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(52, 13, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(53, 14, 'Introduction to Entertainment', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(54, 14, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(55, 14, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(56, 14, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(57, 15, 'Introduction to Sports (Local)', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(58, 15, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(59, 15, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(60, 15, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(61, 16, 'Introduction to Story (Billion/Story)', 'Overview and key fundamentals.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 1, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(62, 16, 'Core Techniques & Best Practices', 'Step-by-step guidance and practical methods.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 120, 80, 2, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(63, 16, 'Advanced Concepts & Case Studies', 'Real-world applications and tips.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 150, 80, 3, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27'),
(64, 16, 'Summary & Key Takeaways', 'Review of core lessons and practical action steps.', 'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1', 90, 80, 4, 1, '2026-09-11 00:04:05', '2026-09-11 00:12:27');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_07_000001_create_yellow_pages_tables', 1),
(5, '2026_09_10_000001_update_users_table_for_registration', 2),
(6, '2026_09_10_000002_create_otps_table', 2),
(7, '2026_09_11_000001_add_status_to_users_table', 3),
(8, '2026_09_11_000002_create_subscription_tables', 3),
(9, '2026_09_11_000003_create_learning_system_tables', 4);

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` bigint UNSIGNED NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `attempts` smallint UNSIGNED NOT NULL DEFAULT '0',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otps`
--

INSERT INTO `otps` (`id`, `phone`, `otp_hash`, `expires_at`, `verified_at`, `used_at`, `attempts`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, '9999999999', '$2y$12$RdNlOloiPB8obu3/ITCw2esUmaV2YhNU6aY1n0gI.Rcfg0PWdlIFu', '2026-09-10 11:28:03', '2026-09-10 11:23:34', NULL, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-10 11:23:03', '2026-09-10 11:23:34'),
(2, '8888888888', '$2y$12$Flf7fOTboaKkmbxoxDx2VO4JcWeDS6xaaUY43s8GuUSvbXaL5wVga', '2026-09-10 11:31:42', '2026-09-10 11:27:03', '2026-09-10 11:27:09', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-10 11:26:42', '2026-09-10 11:27:09'),
(3, '9999999999', '$2y$12$3S54B68vRU7diHNCPRQv8e8zmFfv9HFEZBBKTCkbo.nI8AN1F36SK', '2026-09-10 23:30:30', '2026-09-10 23:27:02', NULL, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-10 23:25:30', '2026-09-10 23:27:02'),
(4, '9999999999', '$2y$12$2SnutqdEsNZphpS4QGtgPeZ0hNmBGGfIF3UYPPgPfk9YwlNq/5sqC', '2026-09-10 23:33:07', NULL, NULL, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-10 23:28:07', '2026-09-10 23:28:07'),
(5, '1111111111', '$2y$12$ANqlt.RjIk3WJOLxKOgajOhe7kjeUqd10t1HET47A9tuZrJQwLQMO', '2026-09-10 23:34:38', '2026-09-10 23:29:43', '2026-09-10 23:30:56', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-10 23:29:38', '2026-09-10 23:30:56'),
(6, '1111111111', '$2y$12$.IkSNuQrG7G3iD3yMqJRyOzal5sHoc.aek0MNhkzk41MhdiCwozy2', '2026-09-10 23:35:47', '2026-09-10 23:30:52', '2026-09-10 23:30:56', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-10 23:30:47', '2026-09-10 23:30:56');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `yellow_page_application_id` bigint UNSIGNED NOT NULL,
  `order_reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` int UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `gateway_payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 0xF09F93A6,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Product (Education / Training)', 'Education & Skills', '🎓', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(2, 'Yoga (Exercise)', 'Health & Fitness', '🧘', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(3, 'Zumba (Exercise)', 'Health & Fitness', '💃', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(4, 'Fashion (Training)', 'Lifestyle & Design', '👗', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(5, 'Cultivation', 'Agriculture & Gardening', '🌾', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(6, 'Podcast', 'Media & Audio', '🎙️', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(7, 'Cook (Home Delivery)', 'Food & Dining', '🍲', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(8, 'Cartoon (Kids)', 'Kids & Animation', '🎨', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(9, 'Health', 'Wellness & Medical', '🏥', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(10, 'Education', 'Academic Learning', '📚', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(11, 'Economic', 'Business & Finance', '📈', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(12, 'Classification', 'Directory Services', '🗂️', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(13, 'Devotion', 'Spiritual & Culture', '🙏', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(14, 'Entertainment', 'Shows & Movies', '🎬', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(15, 'Sports (Local)', 'Athletics & Games', '⚽', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43'),
(16, 'Story (Billion/Story)', 'Literature & Audiobooks', '📖', 1, '2026-09-10 23:48:43', '2026-09-10 23:48:43');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` bigint UNSIGNED NOT NULL,
  `yellow_page_application_id` bigint UNSIGNED NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_token` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('pabK34dKhhhLhggwOAyx8aRjPhdeuUptCuRXMgsa', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ0eTd4QlgzYVRXU0JIS3J4U3hienV0Z2tiRlNNS1pGSEsxZWN6WFJHIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2Rhc2hib2FyZCIsInJvdXRlIjoiZGFzaGJvYXJkIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==', 1789105594);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` int UNSIGNED NOT NULL DEFAULT '200',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `activated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `amount`, `status`, `activated_at`, `created_at`, `updated_at`) VALUES
(1, 2, 200, 'active', '2026-09-10 23:51:51', '2026-09-10 23:51:40', '2026-09-10 23:51:51');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_payments`
--

CREATE TABLE `subscription_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_reference` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway_transaction_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` int UNSIGNED NOT NULL DEFAULT '200',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `gateway_payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_payments`
--

INSERT INTO `subscription_payments` (`id`, `subscription_id`, `user_id`, `order_reference`, `gateway_transaction_id`, `amount`, `status`, `paid_at`, `gateway_payload`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'SUB-LPPE8UMCRCZI', 'TXN-SUB-0H3SUI1FR6', 200, 'success', '2026-09-10 23:51:51', '{\"ip\": \"127.0.0.1\", \"status\": \"success\", \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36\", \"transaction_id\": null}', '2026-09-10 23:51:40', '2026-09-10 23:51:51');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_product`
--

CREATE TABLE `subscription_product` (
  `id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_product`
--

INSERT INTO `subscription_product` (`id`, `subscription_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-09-10 23:51:40', '2026-09-10 23:51:40'),
(2, 1, 2, '2026-09-10 23:51:40', '2026-09-10 23:51:40'),
(3, 1, 3, '2026-09-10 23:51:40', '2026-09-10 23:51:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `learning_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `referral_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_by_id` bigint UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `phone_verified_at`, `email`, `status`, `learning_status`, `email_verified_at`, `password`, `address`, `referral_code`, `referred_by_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Prasant Kumar', '8888888888', '2026-09-10 11:27:09', 'kprasant631@gmail.com', 'pending', 'pending', NULL, NULL, 'erererwrwrwr', 'THKU1QTF1', NULL, NULL, '2026-09-10 11:27:09', '2026-09-10 11:27:09'),
(2, 'uttam kumar mohanta', '1111111111', '2026-09-10 23:30:56', 'kprasant635@gmail.com', 'active', 'certified', NULL, NULL, 'hoise 6747887', 'THK3WJKS5', 1, NULL, '2026-09-10 23:30:56', '2026-09-11 00:15:33');

-- --------------------------------------------------------

--
-- Table structure for table `user_course_progress`
--

CREATE TABLE `user_course_progress` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `course_id` bigint UNSIGNED NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_course_progress`
--

INSERT INTO `user_course_progress` (`id`, `user_id`, `course_id`, `is_completed`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 1, '2026-09-11 00:14:30', '2026-09-11 00:14:30', '2026-09-11 00:14:30'),
(2, 2, 3, 1, '2026-09-11 00:15:20', '2026-09-11 00:15:20', '2026-09-11 00:15:20'),
(3, 2, 1, 1, '2026-09-11 00:15:33', '2026-09-11 00:15:33', '2026-09-11 00:15:33');

-- --------------------------------------------------------

--
-- Table structure for table `user_video_progress`
--

CREATE TABLE `user_video_progress` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `course_id` bigint UNSIGNED NOT NULL,
  `video_id` bigint UNSIGNED NOT NULL,
  `watch_time_seconds` int UNSIGNED NOT NULL DEFAULT '0',
  `percentage_watched` decimal(5,2) NOT NULL DEFAULT '0.00',
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_video_progress`
--

INSERT INTO `user_video_progress` (`id`, `user_id`, `course_id`, `video_id`, `watch_time_seconds`, `percentage_watched`, `is_completed`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 5, 90, 100.00, 1, '2026-09-11 00:13:58', '2026-09-11 00:13:58', '2026-09-11 00:13:58'),
(2, 2, 2, 6, 120, 100.00, 1, '2026-09-11 00:14:25', '2026-09-11 00:14:25', '2026-09-11 00:14:25'),
(3, 2, 2, 8, 90, 100.00, 1, '2026-09-11 00:14:28', '2026-09-11 00:14:28', '2026-09-11 00:14:28'),
(4, 2, 2, 7, 150, 100.00, 1, '2026-09-11 00:14:30', '2026-09-11 00:14:30', '2026-09-11 00:14:30'),
(5, 2, 3, 9, 90, 100.00, 1, '2026-09-11 00:15:14', '2026-09-11 00:15:14', '2026-09-11 00:15:14'),
(6, 2, 3, 10, 120, 100.00, 1, '2026-09-11 00:15:16', '2026-09-11 00:15:16', '2026-09-11 00:15:16'),
(7, 2, 3, 11, 150, 100.00, 1, '2026-09-11 00:15:18', '2026-09-11 00:15:18', '2026-09-11 00:15:18'),
(8, 2, 3, 12, 90, 100.00, 1, '2026-09-11 00:15:20', '2026-09-11 00:15:20', '2026-09-11 00:15:20'),
(9, 2, 1, 1, 90, 100.00, 1, '2026-09-11 00:15:26', '2026-09-11 00:15:26', '2026-09-11 00:15:26'),
(10, 2, 1, 2, 120, 100.00, 1, '2026-09-11 00:15:29', '2026-09-11 00:15:29', '2026-09-11 00:15:29'),
(11, 2, 1, 3, 150, 100.00, 1, '2026-09-11 00:15:31', '2026-09-11 00:15:31', '2026-09-11 00:15:31'),
(12, 2, 1, 4, 90, 100.00, 1, '2026-09-11 00:15:33', '2026-09-11 00:15:33', '2026-09-11 00:15:33');

-- --------------------------------------------------------

--
-- Table structure for table `yellow_pages`
--

CREATE TABLE `yellow_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `eligibility` text COLLATE utf8mb4_unicode_ci,
  `required_documents` json DEFAULT NULL,
  `application_fee` int UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `yellow_pages`
--

INSERT INTO `yellow_pages` (`id`, `name`, `category`, `location`, `description`, `eligibility`, `required_documents`, `application_fee`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Business Registration', 'Business Services', 'Central District', 'Register a new local business with guided support.', 'Valid government-issued identity and proof of address.', '[\"Government-issued identity\", \"Proof of address\"]', 499, 1, '2026-09-06 23:24:40', '2026-09-06 23:24:40'),
(2, 'Food Safety Licence', 'Licences', 'North Zone', 'Apply for food business licensing and inspections.', 'Valid government-issued identity and proof of address.', '[\"Government-issued identity\", \"Proof of address\"]', 750, 1, '2026-09-06 23:24:40', '2026-09-06 23:24:40'),
(3, 'Trade Permit Renewal', 'Permits', 'Central District', 'Renew your municipal trade permit online.', 'Valid government-issued identity and proof of address.', '[\"Government-issued identity\", \"Proof of address\"]', 350, 1, '2026-09-06 23:24:40', '2026-09-06 23:24:40'),
(4, 'Community Hall Booking', 'Civic Services', 'East Zone', 'Book public community spaces for approved events.', 'Valid government-issued identity and proof of address.', '[\"Government-issued identity\", \"Proof of address\"]', 1000, 1, '2026-09-06 23:24:40', '2026-09-06 23:24:40'),
(5, 'Vendor Registration', 'Business Services', 'West Zone', 'Join the verified municipal vendor directory.', 'Valid government-issued identity and proof of address.', '[\"Government-issued identity\", \"Proof of address\"]', 600, 1, '2026-09-06 23:24:40', '2026-09-06 23:24:40'),
(6, 'Archived Service', 'Permits', 'North Zone', 'This listing is currently unavailable.', 'Valid government-issued identity and proof of address.', '[\"Government-issued identity\", \"Proof of address\"]', 250, 0, '2026-09-06 23:24:40', '2026-09-06 23:24:40');

-- --------------------------------------------------------

--
-- Table structure for table `yellow_page_applications`
--

CREATE TABLE `yellow_page_applications` (
  `id` bigint UNSIGNED NOT NULL,
  `yellow_page_id` bigint UNSIGNED NOT NULL,
  `owner_key` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicant_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `documents` json DEFAULT NULL,
  `amount` int UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'payment_pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificates_certificate_number_unique` (`certificate_number`),
  ADD UNIQUE KEY `certificates_user_id_subscription_id_unique` (`user_id`,`subscription_id`),
  ADD KEY `certificates_subscription_id_foreign` (`subscription_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_slug_unique` (`slug`),
  ADD KEY `courses_product_id_foreign` (`product_id`);

--
-- Indexes for table `course_videos`
--
ALTER TABLE `course_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_videos_course_id_foreign` (`course_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otps_phone_created_at_index` (`phone`,`created_at`),
  ADD KEY `otps_phone_index` (`phone`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_order_reference_unique` (`order_reference`),
  ADD UNIQUE KEY `payments_gateway_transaction_id_unique` (`gateway_transaction_id`),
  ADD KEY `payments_yellow_page_application_id_foreign` (`yellow_page_application_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipts_yellow_page_application_id_unique` (`yellow_page_application_id`),
  ADD UNIQUE KEY `receipts_reference_unique` (`reference`),
  ADD UNIQUE KEY `receipts_verification_token_unique` (`verification_token`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`);

--
-- Indexes for table `subscription_payments`
--
ALTER TABLE `subscription_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_payments_order_reference_unique` (`order_reference`),
  ADD UNIQUE KEY `subscription_payments_gateway_transaction_id_unique` (`gateway_transaction_id`),
  ADD KEY `subscription_payments_subscription_id_foreign` (`subscription_id`),
  ADD KEY `subscription_payments_user_id_foreign` (`user_id`);

--
-- Indexes for table `subscription_product`
--
ALTER TABLE `subscription_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_product_subscription_id_foreign` (`subscription_id`),
  ADD KEY `subscription_product_product_id_foreign` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  ADD KEY `users_referred_by_id_foreign` (`referred_by_id`);

--
-- Indexes for table `user_course_progress`
--
ALTER TABLE `user_course_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_course_progress_user_id_course_id_unique` (`user_id`,`course_id`),
  ADD KEY `user_course_progress_course_id_foreign` (`course_id`);

--
-- Indexes for table `user_video_progress`
--
ALTER TABLE `user_video_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_video_progress_user_id_video_id_unique` (`user_id`,`video_id`),
  ADD KEY `user_video_progress_course_id_foreign` (`course_id`),
  ADD KEY `user_video_progress_video_id_foreign` (`video_id`);

--
-- Indexes for table `yellow_pages`
--
ALTER TABLE `yellow_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yellow_page_applications`
--
ALTER TABLE `yellow_page_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `yellow_page_applications_reference_unique` (`reference`),
  ADD KEY `yellow_page_applications_yellow_page_id_foreign` (`yellow_page_id`),
  ADD KEY `yellow_page_applications_owner_key_index` (`owner_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `course_videos`
--
ALTER TABLE `course_videos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscription_payments`
--
ALTER TABLE `subscription_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscription_product`
--
ALTER TABLE `subscription_product`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_course_progress`
--
ALTER TABLE `user_course_progress`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_video_progress`
--
ALTER TABLE `user_video_progress`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `yellow_pages`
--
ALTER TABLE `yellow_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `yellow_page_applications`
--
ALTER TABLE `yellow_page_applications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_videos`
--
ALTER TABLE `course_videos`
  ADD CONSTRAINT `course_videos_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_yellow_page_application_id_foreign` FOREIGN KEY (`yellow_page_application_id`) REFERENCES `yellow_page_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_yellow_page_application_id_foreign` FOREIGN KEY (`yellow_page_application_id`) REFERENCES `yellow_page_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_payments`
--
ALTER TABLE `subscription_payments`
  ADD CONSTRAINT `subscription_payments_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscription_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_product`
--
ALTER TABLE `subscription_product`
  ADD CONSTRAINT `subscription_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscription_product_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_referred_by_id_foreign` FOREIGN KEY (`referred_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_course_progress`
--
ALTER TABLE `user_course_progress`
  ADD CONSTRAINT `user_course_progress_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_course_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_video_progress`
--
ALTER TABLE `user_video_progress`
  ADD CONSTRAINT `user_video_progress_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_video_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_video_progress_video_id_foreign` FOREIGN KEY (`video_id`) REFERENCES `course_videos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `yellow_page_applications`
--
ALTER TABLE `yellow_page_applications`
  ADD CONSTRAINT `yellow_page_applications_yellow_page_id_foreign` FOREIGN KEY (`yellow_page_id`) REFERENCES `yellow_pages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
