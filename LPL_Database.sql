-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2025 at 08:39 AM
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
-- Database: `lpl`
--
CREATE DATABASE IF NOT EXISTS `lpl` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `lpl`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `admin_name`, `password`) VALUES
('admin1', 'Asal', 'password123'),
('manager1', 'Tournament Manager', 'securePass');

-- --------------------------------------------------------

--
-- Table structure for table `coach`
--

CREATE TABLE `coach` (
  `coach_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `team_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coach`
--

INSERT INTO `coach` (`coach_id`, `first_name`, `last_name`, `role`, `team_id`) VALUES
(1, 'Alex', 'Ferguson', 'Head Coach', 'T01'),
(2, 'Trevor', 'Bayliss', 'Batting Coach', 'T01'),
(3, 'Chaminda', 'Vaas', 'Bowling Coach', 'T01'),
(4, 'Pep', 'Guardiola', 'Head Coach', 'T02'),
(5, 'Grant', 'Flower', 'Batting Coach', 'T02'),
(6, 'Waqar', 'Younis', 'Bowling Coach', 'T02'),
(7, 'Zinedine', 'Zidane', 'Head Coach', 'T03'),
(8, 'Mahela', 'Jayawardene', 'Batting Coach', 'T03'),
(9, 'Allan', 'Donald', 'Bowling Coach', 'T03'),
(10, 'Jurgen', 'Klopp', 'Head Coach', 'T04'),
(11, 'Hashan', 'Tillakaratne', 'Batting Coach', 'T04'),
(12, 'Chamila', 'Gamage', 'Bowling Coach', 'T04'),
(13, 'Ravi', 'Shastri', 'Head Coach', 'T05'),
(14, 'Arjuna', 'Ranatunga', 'Batting Coach', 'T05'),
(15, 'Courtney', 'Walsh', 'Bowling Coach', 'T05');

-- --------------------------------------------------------

--
-- Table structure for table `live_score`
--

CREATE TABLE `live_score` (
  `id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `batting_team_id` varchar(10) DEFAULT NULL,
  `bowling_team_id` varchar(10) DEFAULT NULL,
  `runs` int(11) DEFAULT 0,
  `wickets` int(11) DEFAULT 0,
  `overs` decimal(4,1) DEFAULT 0.0,
  `striker_id` int(11) DEFAULT NULL,
  `non_striker_id` int(11) DEFAULT NULL,
  `bowler_id` int(11) DEFAULT NULL,
  `innings_no` int(11) NOT NULL,
  `target` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `live_score`
--

INSERT INTO `live_score` (`id`, `match_id`, `batting_team_id`, `bowling_team_id`, `runs`, `wickets`, `overs`, `striker_id`, `non_striker_id`, `bowler_id`, `innings_no`, `target`) VALUES
(1, 1, 'T01', 'T02', 39, 1, 3.0, NULL, 10, 14, 1, 0),
(2, 2, 'T04', 'T03', 0, 0, 0.0, 34, 42, 31, 1, 0),
(3, 1, 'T02', 'T01', 11, 1, 0.2, 21, 18, 4, 2, 0),
(4, 1, 'T02', 'T01', 2, 0, 0.1, 15, 15, 10, 1, 0),
(5, 1, 'T02', 'T01', 5, 0, 0.2, 15, 12, 10, 2, 0),
(6, 1, 'T01', 'T02', 3, 9, 2.0, 11, 6, 12, 1, 0),
(7, 1, 'T02', 'T01', 1, 0, 0.1, 12, 14, 5, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE `player` (
  `player_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `type` enum('Batsman','Captain/Batsman','Bowler','All-Rounder','Wicket-Keeper') NOT NULL,
  `team_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`player_id`, `first_name`, `last_name`, `type`, `team_id`) VALUES
(1, 'Kusal', 'Perera', 'Batsman', 'T01'),
(2, 'Avishka', 'Fernando', 'Batsman', 'T01'),
(3, 'Dinesh', 'Chandimal', 'Batsman', 'T01'),
(4, 'Angelo', 'Mathews', 'Batsman', 'T01'),
(5, 'Thisara', 'Perera', 'Batsman', 'T01'),
(6, 'Lahiru', 'Kumara', 'Batsman', 'T01'),
(7, 'Suranga', 'Lakmal', 'Batsman', 'T01'),
(8, 'Wanindu', 'Hasaranga', 'Batsman', 'T01'),
(9, 'Charith', 'Asalanka', 'Batsman', 'T01'),
(10, 'Isuru', 'Udana', 'Batsman', 'T01'),
(11, 'Dimuth', 'Karunaratne', 'Batsman', 'T01'),
(12, 'Danushka', 'Gunathilaka', 'Batsman', 'T02'),
(14, 'Mohammad', 'Amir', 'Batsman', 'T02'),
(15, 'Lakshan', 'Sandakan', 'Batsman', 'T02'),
(16, 'Ben', 'Cutting', 'Batsman', 'T02'),
(17, 'Colin', 'Ingram', 'Batsman', 'T02'),
(18, 'Dhananjaya', 'de Silva', 'Batsman', 'T02'),
(19, 'Akila', 'Dananjaya', 'Batsman', 'T02'),
(20, 'Shoaib', 'Malik', 'Batsman', 'T02'),
(21, 'Niroshan', 'Dickwella', 'Batsman', 'T02'),
(22, 'Shanaka', 'Dhananjaya', 'Batsman', 'T02'),
(23, 'Chris', 'Gayle', 'Batsman', 'T03'),
(24, 'Andre', 'Russell', 'Batsman', 'T03'),
(25, 'Kusal', 'Mendis', 'Batsman', 'T03'),
(26, 'Ajantha', 'Mendis', 'Batsman', 'T03'),
(27, 'Lahiru', 'Thirimanne', 'Batsman', 'T03'),
(28, 'Chaminda', 'Vaas', 'Batsman', 'T03'),
(29, 'Mujeeb', 'ur Rahman', 'Batsman', 'T03'),
(30, 'Pathum', 'Nissanka', 'Batsman', 'T03'),
(31, 'Ashen', 'Bandara', 'Batsman', 'T03'),
(32, 'Kusal', 'Janith', 'Batsman', 'T03'),
(33, 'Kevin', 'O Brien', 'Batsman', 'T03'),
(34, 'Upul', 'Tharanga', 'Batsman', 'T04'),
(35, 'Mahela', 'Jayawardene', 'Batsman', 'T04'),
(36, 'Sanath', 'Jayasuriya', 'Batsman', 'T04'),
(37, 'Muttiah', 'Muralitharan', 'Batsman', 'T04'),
(38, 'Anil', 'Kumble', 'Batsman', 'T04'),
(39, 'Dwayne', 'Bravo', 'Batsman', 'T04'),
(40, 'Michael', 'Hussey', 'Batsman', 'T04'),
(41, 'Faf', 'du Plessis', 'Batsman', 'T04'),
(42, 'Mitchell', 'Starc', 'Batsman', 'T04'),
(43, 'Kagiso', 'Rabada', 'Batsman', 'T04'),
(44, 'Hashim', 'Amla', 'Batsman', 'T04'),
(45, 'Tillakaratne', 'Dilshan', 'Batsman', 'T05'),
(46, 'Marvan', 'Atapattu', 'Batsman', 'T05'),
(47, 'Roshan', 'Mahanama', 'Batsman', 'T05'),
(48, 'Kumar', 'Sangakkara', 'Batsman', 'T05'),
(49, 'Aravinda', 'de Silva', 'Batsman', 'T05'),
(50, 'Chamari', 'Atapattu', 'Batsman', 'T05'),
(51, 'Lasith', 'Malinga', 'Batsman', 'T05'),
(52, 'Nuwan', 'Kulasekara', 'Batsman', 'T05'),
(53, 'Rangana', 'Herath', 'Batsman', 'T05'),
(54, 'Ajantha', 'Fernando', 'Batsman', 'T05'),
(55, 'Upul', 'Chandana', 'Batsman', 'T05');

-- --------------------------------------------------------

--
-- Table structure for table `player_performance`
--

CREATE TABLE `player_performance` (
  `performance_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `number_of_match` int(11) DEFAULT 0,
  `runs` int(11) DEFAULT 0,
  `wickets` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player_performance`
--

INSERT INTO `player_performance` (`performance_id`, `player_id`, `number_of_match`, `runs`, `wickets`) VALUES
(1, 1, 45, 1200, 0),
(2, 2, 39, 980, 0),
(3, 3, 52, 1400, 0),
(4, 4, 65, 2200, 60),
(5, 5, 70, 1800, 95),
(6, 6, 30, 120, 45),
(7, 7, 80, 300, 110),
(8, 8, 40, 900, 75),
(9, 9, 29, 850, 3),
(10, 10, 60, 450, 82),
(11, 11, 100, 3200, 0),
(12, 12, 55, 1500, 0),
(14, 14, 90, 250, 150),
(15, 15, 60, 120, 85),
(16, 16, 70, 1600, 70),
(17, 17, 75, 2100, 0),
(18, 18, 85, 2000, 65),
(19, 19, 45, 200, 50),
(20, 20, 200, 5000, 150),
(21, 21, 95, 2700, 0),
(22, 22, 50, 1300, 45),
(23, 23, 400, 14500, 30),
(24, 24, 350, 7200, 350),
(25, 25, 80, 2300, 0),
(26, 26, 160, 300, 180),
(27, 27, 120, 3500, 0),
(28, 28, 250, 500, 400),
(29, 29, 60, 100, 70),
(30, 30, 30, 890, 0),
(31, 31, 20, 400, 15),
(32, 32, 110, 2800, 0),
(33, 33, 210, 4200, 120),
(34, 34, 220, 6800, 0),
(35, 35, 500, 12000, 0),
(36, 36, 450, 13500, 300),
(37, 37, 495, 800, 530),
(38, 38, 300, 500, 400),
(39, 39, 400, 6000, 500),
(40, 40, 250, 7500, 0),
(41, 41, 150, 4500, 0),
(42, 42, 120, 300, 220),
(43, 43, 80, 200, 130),
(44, 44, 250, 9000, 0),
(45, 45, 330, 10500, 120),
(46, 46, 350, 8500, 0),
(47, 47, 200, 5500, 0),
(48, 48, 450, 14200, 0),
(49, 49, 380, 9700, 150),
(50, 50, 250, 6500, 120),
(51, 51, 226, 550, 390),
(52, 52, 190, 350, 200),
(53, 53, 175, 250, 180),
(54, 54, 120, 220, 130),
(55, 55, 250, 3700, 180);

-- --------------------------------------------------------

--
-- Table structure for table `point_table`
--

CREATE TABLE `point_table` (
  `id` int(11) NOT NULL,
  `team_id` varchar(10) DEFAULT NULL,
  `played` int(11) DEFAULT 0,
  `won` int(11) DEFAULT 0,
  `lost` int(11) DEFAULT 0,
  `no_result` int(11) DEFAULT 0,
  `nrr` decimal(5,2) DEFAULT 0.00,
  `points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `point_table`
--

INSERT INTO `point_table` (`id`, `team_id`, `played`, `won`, `lost`, `no_result`, `nrr`, `points`) VALUES
(1, 'T01', 3, 2, 1, 0, 0.30, 3),
(2, 'T02', 4, 1, 0, 0, 1.60, 8),
(3, 'T03', 2, 0, 2, 0, -1.20, 0),
(4, 'T04', 2, 1, 1, 0, 0.10, 2),
(5, 'T05', 0, 0, 0, 0, 0.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `recent_match`
--

CREATE TABLE `recent_match` (
  `match_id` int(11) NOT NULL,
  `home_team_id` varchar(10) NOT NULL,
  `visit_team_id` varchar(10) NOT NULL,
  `home_team_runs` int(11) DEFAULT NULL,
  `home_team_wickets` int(11) DEFAULT NULL,
  `home_team_overs` decimal(4,1) DEFAULT NULL,
  `visit_team_runs` int(11) DEFAULT NULL,
  `visit_team_wickets` int(11) DEFAULT NULL,
  `visit_team_overs` decimal(4,1) DEFAULT NULL,
  `final_result` text DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recent_match`
--

INSERT INTO `recent_match` (`match_id`, `home_team_id`, `visit_team_id`, `home_team_runs`, `home_team_wickets`, `home_team_overs`, `visit_team_runs`, `visit_team_wickets`, `visit_team_overs`, `final_result`, `date`) VALUES
(1, 'T01', 'T03', 180, 6, 20.0, 175, 8, 20.0, 'Colombo Kings won by 5 runs', '2025-09-01'),
(2, 'T02', 'T05', 200, 5, 20.0, 198, 7, 20.0, 'Galle Gladiators won by 2 runs', '2025-09-02'),
(3, 'T03', 'T01', 150, 10, 18.4, 151, 4, 17.3, 'Colombo Kings won by 6 wickets', '2025-09-03'),
(4, 'T04', 'T02', 210, 7, 20.0, 205, 9, 20.0, 'Jaffna Stallions won by 5 runs', '2025-09-04'),
(5, 'T05', 'T03', 160, 8, 19.5, 161, 5, 20.0, 'B-Love Kandy won by 5 wickets', '2025-09-05');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `team_id` varchar(10) NOT NULL,
  `team_name` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`team_id`, `team_name`, `logo`) VALUES
('T01', 'Colombo Kings', 'Pictures/Colombo-1.png'),
('T02', 'Galle Gladiators', 'Pictures/Galle-1.png'),
('T03', 'B-Love Kandy', 'Pictures/Kandy-1.png'),
('T04', 'Jaffna Stallions', 'Pictures/Jaffna-1.png'),
('T05', 'Dambulla Giants', 'Pictures/DabullaLogo.png');

-- --------------------------------------------------------

--
-- Table structure for table `upcoming_match`
--

CREATE TABLE `upcoming_match` (
  `match_id` int(11) NOT NULL,
  `home_team_id` varchar(10) NOT NULL,
  `visit_team_id` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `upcoming_match`
--

INSERT INTO `upcoming_match` (`match_id`, `home_team_id`, `visit_team_id`, `date`, `time`) VALUES
(1, 'T01', 'T02', '2025-09-10', '20:00:00'),
(2, 'T03', 'T04', '2025-09-12', '16:00:00'),
(3, 'T05', 'T01', '2025-09-15', '14:30:00'),
(4, 'T02', 'T03', '2025-09-18', '17:00:00'),
(5, 'T04', 'T05', '2025-09-22', '18:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `coach`
--
ALTER TABLE `coach`
  ADD PRIMARY KEY (`coach_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `live_score`
--
ALTER TABLE `live_score`
  ADD PRIMARY KEY (`id`),
  ADD KEY `match_id` (`match_id`),
  ADD KEY `batting_team_id` (`batting_team_id`),
  ADD KEY `bowling_team_id` (`bowling_team_id`),
  ADD KEY `striker_id` (`striker_id`),
  ADD KEY `non_striker_id` (`non_striker_id`),
  ADD KEY `bowler_id` (`bowler_id`);

--
-- Indexes for table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`player_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `player_performance`
--
ALTER TABLE `player_performance`
  ADD PRIMARY KEY (`performance_id`),
  ADD KEY `player_id` (`player_id`);

--
-- Indexes for table `point_table`
--
ALTER TABLE `point_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `recent_match`
--
ALTER TABLE `recent_match`
  ADD PRIMARY KEY (`match_id`),
  ADD KEY `home_team_id` (`home_team_id`),
  ADD KEY `visit_team_id` (`visit_team_id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `upcoming_match`
--
ALTER TABLE `upcoming_match`
  ADD PRIMARY KEY (`match_id`),
  ADD KEY `home_team_id` (`home_team_id`),
  ADD KEY `visit_team_id` (`visit_team_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coach`
--
ALTER TABLE `coach`
  MODIFY `coach_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `live_score`
--
ALTER TABLE `live_score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `player`
--
ALTER TABLE `player`
  MODIFY `player_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `player_performance`
--
ALTER TABLE `player_performance`
  MODIFY `performance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `point_table`
--
ALTER TABLE `point_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `recent_match`
--
ALTER TABLE `recent_match`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `upcoming_match`
--
ALTER TABLE `upcoming_match`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coach`
--
ALTER TABLE `coach`
  ADD CONSTRAINT `coach_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`);

--
-- Constraints for table `live_score`
--
ALTER TABLE `live_score`
  ADD CONSTRAINT `live_score_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `upcoming_match` (`match_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `live_score_ibfk_2` FOREIGN KEY (`batting_team_id`) REFERENCES `team` (`team_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `live_score_ibfk_3` FOREIGN KEY (`bowling_team_id`) REFERENCES `team` (`team_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `live_score_ibfk_4` FOREIGN KEY (`striker_id`) REFERENCES `player` (`player_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `live_score_ibfk_5` FOREIGN KEY (`non_striker_id`) REFERENCES `player` (`player_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `live_score_ibfk_6` FOREIGN KEY (`bowler_id`) REFERENCES `player` (`player_id`) ON DELETE SET NULL;

--
-- Constraints for table `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `player_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE CASCADE;

--
-- Constraints for table `player_performance`
--
ALTER TABLE `player_performance`
  ADD CONSTRAINT `player_performance_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `player` (`player_id`) ON DELETE CASCADE;

--
-- Constraints for table `point_table`
--
ALTER TABLE `point_table`
  ADD CONSTRAINT `point_table_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`);

--
-- Constraints for table `recent_match`
--
ALTER TABLE `recent_match`
  ADD CONSTRAINT `recent_match_ibfk_1` FOREIGN KEY (`home_team_id`) REFERENCES `team` (`team_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recent_match_ibfk_2` FOREIGN KEY (`visit_team_id`) REFERENCES `team` (`team_id`) ON DELETE CASCADE;

--
-- Constraints for table `upcoming_match`
--
ALTER TABLE `upcoming_match`
  ADD CONSTRAINT `upcoming_match_ibfk_1` FOREIGN KEY (`home_team_id`) REFERENCES `team` (`team_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `upcoming_match_ibfk_2` FOREIGN KEY (`visit_team_id`) REFERENCES `team` (`team_id`) ON DELETE CASCADE;
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"lpl\",\"table\":\"live_score\"},{\"db\":\"lpl\",\"table\":\"admin\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2025-10-17 06:36:24', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
