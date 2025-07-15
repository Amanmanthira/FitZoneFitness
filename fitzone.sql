-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 15, 2025 at 09:13 PM
-- Server version: 8.0.18
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fitzone`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `content`, `created_at`) VALUES
(1, '6 Forearm Exercises for Muscle and Strength', 'Let\'s face it: a lot of gym routines ignore forearm exercises. It\'s seems like it\'s all about biceps and triceps. Yet, neglecting your forearm muscles can lead to imbalance throughout your upper body. Regular exercise not only improves muscle strength and flexibility, but balanced workouts are essential for maximizing physical performance. \r\n\r\nAnd muscular forearms aren\'t just for aesthetics; they\'re essential for everyday, functional fitness tasks—lifting groceries, gripping tools, and opening jars. So let\'s dive into why forearm focus is important to your fitness regimen and share some of our favorite exercises to get the best forearm workout.\r\n\r\nDon\'t start your fitness journey alone. Find a club near you for support and guidance.\r\n\r\nWhat are Forearm Workouts?\r\nForearm workouts target the muscles in your lower arms: the flexors (which grip and bend your wrist) and extensors (which straighten your wrist). These exercises can involve weights, resistance bands, grip trainers, or even everyday objects like water bottles or soup cans!', '2025-07-15 09:32:40'),
(2, 'Tips for Maintaining Motivation and Building Workout Consistency in the Gym', 'Building consistency in the gym is the key to progress. The tricky part is maintaining the motivation you need to stay on track. We get it—it’s hard!\r\n\r\nThat’s why we’ve created the ultimate guide to building consistency in your workouts.\r\n\r\nRead on for over 20 of our top tips for maintaining consistency and motivation in your fitness routine.\r\n\r\nRemember, consistency and dedication are the foundation for long-term success along your fitness journey. You’re not alone—find a Planet Fitness club near you for support and expert guidance when you need it!', '2025-07-15 09:33:14'),
(3, 'Tips for Maintaining Motivation and Building Workout Consistency in the Gym', 'Building consistency in the gym is the key to progress. The tricky part is maintaining the motivation you need to stay on track. We get it—it’s hard!\r\n\r\nThat’s why we’ve created the ultimate guide to building consistency in your workouts.\r\n\r\nRead on for over 20 of our top tips for maintaining consistency and motivation in your fitness routine.\r\n\r\nRemember, consistency and dedication are the foundation for long-term success along your fitness journey. You’re not alone—find a Planet Fitness club near you for support and expert guidance when you need it!', '2025-07-15 09:50:45'),
(4, 'Tips for Maintaining Motivation and Building Workout Consistency in the Gym', 'Building consistency in the gym is the key to progress. The tricky part is maintaining the motivation you need to stay on track. We get it—it’s hard!\r\n\r\nThat’s why we’ve created the ultimate guide to building consistency in your workouts.\r\n\r\nRead on for over 20 of our top tips for maintaining consistency and motivation in your fitness routine.\r\n\r\nRemember, consistency and dedication are the foundation for long-term success along your fitness journey. You’re not alone—find a Planet Fitness club near you for support and expert guidance when you need it!', '2025-07-15 09:52:24'),
(5, 'Tips for Maintaining Motivation and Building Workout Consistency in the Gym', 'Building consistency in the gym is the key to progress. The tricky part is maintaining the motivation you need to stay on track. We get it—it’s hard!\r\n\r\nThat’s why we’ve created the ultimate guide to building consistency in your workouts.\r\n\r\nRead on for over 20 of our top tips for maintaining consistency and motivation in your fitness routine.\r\n\r\nRemember, consistency and dedication are the foundation for long-term success along your fitness journey. You’re not alone—find a Planet Fitness club near you for support and expert guidance when you need it!', '2025-07-15 09:53:46'),
(6, '6 Forearm Exercises for Muscle and Strength', 'Let\'s face it: a lot of gym routines ignore forearm exercises. It\'s seems like it\'s all about biceps and triceps. Yet, neglecting your forearm muscles can lead to imbalance throughout your upper body. Regular exercise not only improves muscle strength and flexibility, but balanced workouts are essential for maximizing physical performance. \r\n\r\nAnd muscular forearms aren\'t just for aesthetics; they\'re essential for everyday, functional fitness tasks—lifting groceries, gripping tools, and opening jars. So let\'s dive into why forearm focus is important to your fitness regimen and share some of our favorite exercises to get the best forearm workout.\r\n\r\nDon\'t start your fitness journey alone. Find a club near you for support and guidance.\r\n\r\nWhat are Forearm Workouts?\r\nForearm workouts target the muscles in your lower arms: the flexors (which grip and bend your wrist) and extensors (which straighten your wrist). These exercises can involve weights, resistance bands, grip trainers, or even everyday objects like water bottles or soup cans!', '2025-07-15 09:53:50');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(100) DEFAULT NULL,
  `schedule` datetime DEFAULT NULL,
  `trainer_id` int(11) DEFAULT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `class_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `membership_plans`
--

DROP TABLE IF EXISTS `membership_plans`;
CREATE TABLE IF NOT EXISTS `membership_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `features` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `membership_plans`
--

INSERT INTO `membership_plans` (`id`, `name`, `price`, `duration`, `features`, `created_at`) VALUES
(2, 'Basic', '6000.00', '1 month', 'test', '2025-07-15 10:02:13');

-- --------------------------------------------------------

--
-- Table structure for table `payment_slips`
--

DROP TABLE IF EXISTS `payment_slips`;
CREATE TABLE IF NOT EXISTS `payment_slips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `month` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment_slips`
--

INSERT INTO `payment_slips` (`id`, `user_id`, `file_path`, `created_at`, `month`) VALUES
(1, 1, 'uploads/687618592ea80_amanManthira.pdf', '2025-07-15 08:59:05', 'January'),
(2, 5, 'uploads/687637743f042_amanManthira.pdf', '2025-07-15 11:11:48', 'January'),
(3, 5, 'uploads/68763822483d0_amanManthira.pdf', '2025-07-15 11:14:42', 'January');

-- --------------------------------------------------------

--
-- Table structure for table `trainer_bookings`
--

DROP TABLE IF EXISTS `trainer_bookings`;
CREATE TABLE IF NOT EXISTS `trainer_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trainer_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `booked_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trainer_id` (`trainer_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `trainer_bookings`
--

INSERT INTO `trainer_bookings` (`id`, `trainer_id`, `customer_id`, `booked_at`) VALUES
(9, 4, 5, '2025-07-15 16:49:01'),
(10, 4, 1, '2025-07-15 20:04:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` text,
  `role` varchar(20) DEFAULT NULL,
  `membership_plan` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `membership_plan`, `created_at`) VALUES
(1, 'Aman', 'aman@gmail.com', '$2y$10$9NW68sdG5DX800v8NWj7xewtYT9A6dMyyLpM2JVH2vs/gr/egj8Ba', 'customer', 'Basic', '2025-07-15 08:37:00'),
(2, 'Admin', 'admin@gmail.com', '$2y$10$S/Sv3jn2yOPzYJcfZtWeuufya59egswQBH2LajEEz.NFbYFXre3l2', 'admin', NULL, '2025-07-15 09:07:04'),
(4, 'trainer', 'trainer@gmail.com', '123', 'trainer', '', '2025-07-15 10:42:57'),
(5, 'alex', 'alex@gmail.com', '$2y$10$ESb926UKlNMRTrfH.gMBwe1r83o2T9Hzq3YwIhayrN/sRAuilsgWu', 'customer', 'Premium', '2025-07-15 11:03:41');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
