-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2024 at 06:53 AM
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
-- Database: `db_ipl`
--

-- --------------------------------------------------------

--
-- Table structure for table `ipl_forecasting_branch_data`
--

CREATE TABLE `ipl_forecasting_branch_data` (
  `forecasting_branch_id` int(11) NOT NULL,
  `forecasting_master_id` int(11) DEFAULT NULL,
  `forecasting_branchkey` varchar(100) DEFAULT NULL,
  `version_no` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `forecasting_month` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ipl_forecasting_branch_data`
--

INSERT INTO `ipl_forecasting_branch_data` (`forecasting_branch_id`, `forecasting_master_id`, `forecasting_branchkey`, `version_no`, `branch_id`, `company_id`, `forecasting_month`) VALUES
(19, 49, 'PL_10510204_2024-09', 1, 105, 10204, '2024-09'),
(20, 49, 'PL_14010204_2024-09', 1, 140, 10204, '2024-09'),
(21, 49, 'PL_14310204_2024-09', 1, 143, 10204, '2024-09'),
(25, 51, 'PL_10510204_2024-09', 2, 105, 10204, '2024-09'),
(26, 51, 'PL_14010204_2024-09', 2, 140, 10204, '2024-09'),
(27, 51, 'PL_14310204_2024-09', 2, 143, 10204, '2024-09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ipl_forecasting_branch_data`
--
ALTER TABLE `ipl_forecasting_branch_data`
  ADD PRIMARY KEY (`forecasting_branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ipl_forecasting_branch_data`
--
ALTER TABLE `ipl_forecasting_branch_data`
  MODIFY `forecasting_branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
