-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 17, 2021 at 03:19 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agrinis`
--

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_detail`
--

DROP TABLE IF EXISTS `pembayaran_detail`;
CREATE TABLE IF NOT EXISTS `pembayaran_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_header` int(11) NOT NULL,
  `buruh` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `pembayaran_detail_fk1` (`id_header`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `pembayaran_detail`
--

INSERT INTO `pembayaran_detail` (`id`, `id_header`, `buruh`) VALUES
(16, 7, 0),
(17, 7, 100),
(18, 7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_header`
--

DROP TABLE IF EXISTS `pembayaran_header`;
CREATE TABLE IF NOT EXISTS `pembayaran_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembayaran` int(11) NOT NULL DEFAULT 0,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `pembayaran_header`
--

INSERT INTO `pembayaran_header` (`id`, `pembayaran`, `created_date`) VALUES
(7, 200000, '2021-05-17 10:11:35');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembayaran_detail`
--
ALTER TABLE `pembayaran_detail`
  ADD CONSTRAINT `pembayaran_detail_fk1` FOREIGN KEY (`id_header`) REFERENCES `pembayaran_header` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
