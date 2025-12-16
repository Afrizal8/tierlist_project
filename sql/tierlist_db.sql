-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2025 at 01:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tierlist_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tier_items`
--

CREATE TABLE `tier_items` (
  `id` int(11) NOT NULL,
  `tier_list_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `tier_level` enum('S','A','B','C','D','E','F') DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tier_items`
--

INSERT INTO `tier_items` (`id`, `tier_list_id`, `item_name`, `tier_level`, `image_path`, `created_at`) VALUES
(27, 14, 'Genghis Khan', 'S', 'assets/uploads/14/1763942991_item1_f8359884fad3.jpg', '2025-11-24 00:09:51'),
(28, 14, 'Napoleon', 'S', 'assets/uploads/14/1763942991_item2_880a9e95eb90.jpg', '2025-11-24 00:09:51'),
(29, 14, 'Mao Zedong', 'B', 'assets/uploads/14/1763942991_item3_e23d55e30933.jpg', '2025-11-24 00:09:51'),
(30, 14, 'Stalin', 'C', 'assets/uploads/14/1763942991_item4_51aca98141e6.jpg', '2025-11-24 00:09:51'),
(31, 15, 'hitler', 'E', 'assets/uploads/15/1763943109_c8afed2ca2d684d3.jpg', '2025-11-24 00:11:49'),
(32, 15, 'karno', 'F', 'assets/uploads/15/1763943109_69698515186b87d7.jpg', '2025-11-24 00:11:49');

-- --------------------------------------------------------

--
-- Table structure for table `tier_lists`
--

CREATE TABLE `tier_lists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `thumbnail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tier_lists`
--

INSERT INTO `tier_lists` (`id`, `user_id`, `title`, `description`, `created_at`, `thumbnail`) VALUES
(14, 4, 'Demo Tier List', 'Ini adalah tier list bawaan — kamu bisa buat tier list seperti ini.', '2025-11-24 00:09:51', 'assets/uploads/14/1763942991_thumb_03d45f7d14ac.jpg'),
(15, 4, 'uyun', 'dedede', '2025-11-24 00:11:49', 'assets/uploads/15/1763943109_c8afed2ca2d684d3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'testusername', 'test@test.com', '$2y$10$JGOKvVzkmdPmHw1qtplAJuFkjeMUM/pbZjS4roILhRN11gWP/0fY6', '2025-11-23 12:06:52'),
(2, 'compsci', 'user@gmail.com', '$2y$10$CDiY5xn.VK9YRxC7eDQBAeu9ldfOKIs2SeROv4BiiLNIWANJmAVQO', '2025-11-23 23:33:06'),
(4, 'jeff', 'jeff@gmail.com', '$2y$10$v3sHYWOMMvT4JBJytYdc8uS809PJCVmotoCp5XchYeU7uMSbI7MFe', '2025-11-24 00:09:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tier_items`
--
ALTER TABLE `tier_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tier_items_list` (`tier_list_id`);

--
-- Indexes for table `tier_lists`
--
ALTER TABLE `tier_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tier_items`
--
ALTER TABLE `tier_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tier_lists`
--
ALTER TABLE `tier_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tier_items`
--
ALTER TABLE `tier_items`
  ADD CONSTRAINT `fk_tier_items_list` FOREIGN KEY (`tier_list_id`) REFERENCES `tier_lists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tier_lists`
--
ALTER TABLE `tier_lists`
  ADD CONSTRAINT `tier_lists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
