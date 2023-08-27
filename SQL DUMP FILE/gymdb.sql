-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2023 at 09:29 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gymdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `bmi`
--

CREATE TABLE `bmi` (
  `username` varchar(255) DEFAULT NULL,
  `height` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `bmi_value` float(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bmi`
--

INSERT INTO `bmi` (`username`, `height`, `weight`, `bmi_value`) VALUES
('maleesha123', 175.26, 70, 22.79);

--
-- Triggers `bmi`
--
DELIMITER $$
CREATE TRIGGER `calculate_bmi` BEFORE INSERT ON `bmi` FOR EACH ROW BEGIN
    SET NEW.bmi_value = CASE
        WHEN NEW.height > 0 AND NEW.weight > 0 THEN NEW.weight / ((NEW.height / 100) * (NEW.height / 100))
        ELSE 0
    END;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_bmi` BEFORE UPDATE ON `bmi` FOR EACH ROW BEGIN
    IF NEW.height = 0 OR NEW.weight = 0 THEN
        SET NEW.bmi_value = 0;
    ELSE
        SET NEW.bmi_value = NEW.weight / ((NEW.height / 100) * (NEW.height / 100));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `id` int(11) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(20) DEFAULT NULL,
  `DOB` varchar(10) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `emerphonenum` varchar(20) DEFAULT NULL,
  `fitnessGoal` varchar(255) DEFAULT NULL,
  `fitnessLevel` varchar(255) DEFAULT NULL,
  `medicalHistory` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`id`, `username`, `fullname`, `email`, `phonenumber`, `DOB`, `gender`, `address`, `emerphonenum`, `fitnessGoal`, `fitnessLevel`, `medicalHistory`) VALUES
(3, 'maleesha123', 'Maleesha Udan', 'maleeshaudan@gmail.com', '0763326092', '2023-08-14', 'Male', 'Maithripala Senanayake Mawatha\r\n1', '0767510613', 'Weight Loss', 'Beginner', 'No medical issues');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(30) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `name`, `password`) VALUES
('maleesha123', 'Maleesha Udan Aththanayaka', '$2y$10$riXZh2J0Dzv90uCtLMTnCeIF6p4Ev41clQdjQq0nymPNKYuAJIS3u');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bmi`
--
ALTER TABLE `bmi`
  ADD KEY `username` (`username`);

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `info`
--
ALTER TABLE `info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bmi`
--
ALTER TABLE `bmi`
  ADD CONSTRAINT `bmi_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`);

--
-- Constraints for table `info`
--
ALTER TABLE `info`
  ADD CONSTRAINT `info_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
