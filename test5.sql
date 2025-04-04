-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 04, 2025 at 06:09 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test5`
--

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `description`) VALUES
(1, 'test1', 'a'),
(2, 'test1', 'est'),
(3, 'test2', 'a');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `emotional_connection` int(11) DEFAULT NULL,
  `exploratory_learning` int(11) DEFAULT NULL,
  `visual_feedback` int(11) DEFAULT NULL,
  `real_life_relevance` int(11) DEFAULT NULL,
  `progress_tracking` int(11) DEFAULT NULL,
  `conceptual_understanding` int(11) DEFAULT NULL,
  `collaboration_experimentation` int(11) DEFAULT NULL,
  `game_balance` int(11) DEFAULT NULL,
  `immediate_feedback` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `game_id`, `emotional_connection`, `exploratory_learning`, `visual_feedback`, `real_life_relevance`, `progress_tracking`, `conceptual_understanding`, `collaboration_experimentation`, `game_balance`, `immediate_feedback`) VALUES
(1, 11, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 12, 3, 1, 1, 4, 6, 3, 5, 6, 3, 8);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `pas` char(225) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `name`, `pas`, `date`) VALUES
(1, 'naz', '123', '2025-04-03'),
(2, 'asd', '$2y$10$CF.lJv8TfVn6/ycfKr4VnukVkcp0pCShzbWLhHGnnaabLqLKS4UBC', '2025-04-03'),
(3, 'nazar', '$2y$10$Yo8HmIez4HTaCPDetE3zkuNKMULZuDfGlTtb8/vbzOuWLo1KvMqxO', '2025-04-03'),
(4, 'linh', '$2y$10$IdTp/WQPD6qGqvimWHNYhe8LNn0xIso4/1cu0SdC8CpGMte.acm12', '2025-04-03'),
(5, 'hhh', '$2y$10$W8gN03RhwdwdXuVnZ9rQ0uhK90MrBNY/MBaT5DxPAJEbFcw8FPNu.', '2025-04-03'),
(6, 'nn', '$2y$10$X9V2qmabFL2BEquCyZi4oeGUu.Ay2mQFz8TdGL2yTgN0w4M05Ai0u', '2025-04-03'),
(7, 'qqq', '$2y$10$waL7nHAjebMZksdsVke9lua2OAC5a7DhgwnqjkvrHxWZS01lgL1zy', '2025-04-04'),
(8, 'iii', '$2y$10$GcG41HgAStwhoCTRUiFl/OXTm.ifUjKsVncGZqsGyMk9tpWNxhDgm', '2025-04-04'),
(9, 'cc', '$2y$10$sPkWA5djfRxVuRmJ0M9UtOS7EyGJ.StHD/nUq9XM0z8LynS9uskby', '2025-04-04'),
(10, 'lin', '$2y$10$64w3HNY6OCkkUysrQmAxjOm8uajrQ3eDry4tuDZFFZ8ZppZt.6FbO', '2025-04-04'),
(11, 'n', '$2y$10$7O5mJ6HqrH/7FVU9N4K4w.7h0L4bvaIdEBr6HswskUmCAUog9QM.W', '2025-04-04'),
(12, 'aa', '$2y$10$PVjJ1C3T3BcwrkWSWBjEceOOtDlZnOulgUiTl1Dy3nIJnOXzNTP/a', '2025-04-04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
