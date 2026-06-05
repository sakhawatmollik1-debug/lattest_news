-- phpMyAdmin SQL Dump
-- Database: `news_db`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `news_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `news_db`;

-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','editor') NOT NULL DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@news.com', 'admin', '2025-01-01 00:00:00');

-- --------------------------------------------------------

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name_bn` varchar(100) NOT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name_bn`, `name_en`, `slug`, `status`, `created_at`) VALUES
(1, 'জাতীয়', 'National', 'national', 1, '2025-01-01 00:00:00'),
(2, 'আন্তর্জাতিক', 'International', 'international', 1, '2025-01-01 00:00:00'),
(3, 'বাণিজ্য', 'Business', 'business', 1, '2025-01-01 00:00:00'),
(4, 'খেলা', 'Sports', 'sports', 1, '2025-01-01 00:00:00'),
(5, 'বিনোদন', 'Entertainment', 'entertainment', 1, '2025-01-01 00:00:00'),
(6, 'প্রযুক্তি', 'Technology', 'technology', 1, '2025-01-01 00:00:00'),
(7, 'রাজনীতি', 'Politics', 'politics', 1, '2025-01-01 00:00:00'),
(8, 'শিক্ষা', 'Education', 'education', 1, '2025-01-01 00:00:00');

-- --------------------------------------------------------

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title_bn` varchar(500) NOT NULL,
  `title_en` varchar(500) DEFAULT NULL,
  `content_bn` longtext DEFAULT NULL,
  `content_en` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE `breaking_news` (
  `id` int(11) NOT NULL,
  `news_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title_bn` varchar(500) NOT NULL,
  `title_en` varchar(500) DEFAULT NULL,
  `content_bn` longtext DEFAULT NULL,
  `content_en` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE `ads` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `code` text NOT NULL,
  `position` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(500) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `value_bn` text DEFAULT NULL,
  `value_en` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `key_name`, `value_bn`, `value_en`) VALUES
(1, 'site_name', 'খবর ডেইলি', 'Khabar Daily'),
(2, 'site_tagline', 'সর্বশেষ সংবাদ, বিশ্বস্ত খবর', 'Latest News, Trusted Updates'),
(3, 'footer_text', '© ২০২৫ খবর ডেইলি। সর্বস্বত্ব সংরক্ষিত।', '© 2025 Khabar Daily. All Rights Reserved.'),
(4, 'about_us', 'খবর ডেইলি একটি বিশ্বস্ত সংবাদ মাধ্যম যা দেশ ও বিদেশের সর্বশেষ খবর সরবরাহ করে।', 'Khabar Daily is a trusted news platform delivering the latest news from home and abroad.'),
(5, 'contact_email', '', 'info@example.com'),
(6, 'contact_phone', '', '+880 1234-567890'),
(7, 'contact_address', 'ঢাকা, বাংলাদেশ', 'Dhaka, Bangladesh'),
(8, 'social_facebook', '', 'https://facebook.com'),
(9, 'social_twitter', '', 'https://twitter.com'),
(10, 'social_youtube', '', 'https://youtube.com'),
(11, 'social_instagram', '', 'https://instagram.com'),
(12, 'privacy_policy_url', '', '#'),
(13, 'terms_of_use_url', '', '#');

-- --------------------------------------------------------

ALTER TABLE `users` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);
ALTER TABLE `categories` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`);
ALTER TABLE `news` ADD PRIMARY KEY (`id`), ADD KEY `category_id` (`category_id`), ADD KEY `status` (`status`), ADD KEY `featured` (`featured`), ADD KEY `created_at` (`created_at`);
ALTER TABLE `breaking_news` ADD PRIMARY KEY (`id`), ADD KEY `news_id` (`news_id`);
ALTER TABLE `blogs` ADD PRIMARY KEY (`id`);
ALTER TABLE `ads` ADD PRIMARY KEY (`id`), ADD KEY `position` (`position`);
ALTER TABLE `settings` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `key_name` (`key_name`);

ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `categories` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `news` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `breaking_news` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `blogs` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ads` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

ALTER TABLE `news` ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `breaking_news` ADD CONSTRAINT `breaking_news_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
