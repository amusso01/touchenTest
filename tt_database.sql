-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 23, 2018 at 09:30 PM
-- Server version: 5.7.21
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tt`
--

-- --------------------------------------------------------

--
-- Table structure for table `tt_address`
--

DROP TABLE IF EXISTS `tt_address`;
CREATE TABLE IF NOT EXISTS `tt_address` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `address_line_1` varchar(255) CHARACTER SET latin1 NOT NULL,
  `address_line_2` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `address_line_3` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `town_city` varchar(255) CHARACTER SET latin1 NOT NULL,
  `postcode` varchar(10) CHARACTER SET latin1 NOT NULL,
  `country_id` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_address_idx` (`user_id`),
  KEY `country_id_address_idx` (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tt_country`
--

DROP TABLE IF EXISTS `tt_country`;
CREATE TABLE IF NOT EXISTS `tt_country` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `country` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `country_UNIQUE` (`country`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tt_email`
--

DROP TABLE IF EXISTS `tt_email`;
CREATE TABLE IF NOT EXISTS `tt_email` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `type` enum('home','work') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tt_phone`
--

DROP TABLE IF EXISTS `tt_phone`;
CREATE TABLE IF NOT EXISTS `tt_phone` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `country` varchar(5) CHARACTER SET latin1 NOT NULL,
  `number` varchar(10) CHARACTER SET latin1 NOT NULL,
  `type` enum('home','work') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tt_user`
--

DROP TABLE IF EXISTS `tt_user`;
CREATE TABLE IF NOT EXISTS `tt_user` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `last_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `dob` date DEFAULT NULL,
  `status` enum('active','deceased') CHARACTER SET latin1 NOT NULL DEFAULT 'active',
  `reference` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=218 DEFAULT CHARSET=utf8mb4 COMMENT='				';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tt_address`
--
ALTER TABLE `tt_address`
  ADD CONSTRAINT `country_id_address` FOREIGN KEY (`country_id`) REFERENCES `tt_country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_id_address` FOREIGN KEY (`user_id`) REFERENCES `tt_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tt_email`
--
ALTER TABLE `tt_email`
  ADD CONSTRAINT `user_id_email` FOREIGN KEY (`user_id`) REFERENCES `tt_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tt_phone`
--
ALTER TABLE `tt_phone`
  ADD CONSTRAINT `user_id_phone` FOREIGN KEY (`user_id`) REFERENCES `tt_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
