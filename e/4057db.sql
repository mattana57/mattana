-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2025 at 11:04 AM
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
-- Database: `4057db`
--
CREATE DATABASE IF NOT EXISTS `4057db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `4057db`;

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `a_id` int(7) NOT NULL,
  `a_position` varchar(100) NOT NULL,
  `a_prefix` varchar(10) NOT NULL,
  `a_firstname` varchar(20) NOT NULL,
  `a_lastname` varchar(100) NOT NULL,
  `a_dob` varchar(10) NOT NULL,
  `a_email` varchar(100) NOT NULL,
  `a_education` varchar(100) NOT NULL,
  `a_skills` varchar(255) NOT NULL,
  `a_experience` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`a_id`, `a_position`, `a_prefix`, `a_firstname`, `a_lastname`, `a_dob`, `a_email`, `a_education`, `a_skills`, `a_experience`) VALUES
(1, 'data-scientist', 'นางสาว', 'สุพาภรณ์', 'รัตนแสง', '2025-12-04', 'supapon@gmail.com', 'bachelor', 'no', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `r_id` int(6) NOT NULL,
  `r_name` varchar(255) NOT NULL,
  `r_phone` varchar(255) NOT NULL,
  `r_height` int(3) NOT NULL,
  `r_address` varchar(255) NOT NULL,
  `r_birthday` varchar(10) NOT NULL,
  `r_color` varchar(50) NOT NULL,
  `r_major` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`r_id`, `r_name`, `r_phone`, `r_height`, `r_address`, `r_birthday`, `r_color`, `r_major`) VALUES
(1, 'มัทนา รัตนแสง', '', 0, '', '', '', ''),
(2, 'ปริมาภรณ์ วริปัญโญ', '', 0, '', '', '', ''),
(3, 'มัทนา วริปัญโญ', '04555555', 0, '', '', '', ''),
(4, 'mumu rattanasang', '0366666', 0, '', '', '', ''),
(7, 'ภัสรา รัตนแสง', '045621386', 161, 'นครพนม', '2025-12-20', '#c4b9ca', 'การจัดการ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`r_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `a_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `r_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
