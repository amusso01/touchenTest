-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 23, 2018 at 09:29 PM
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
-- Database: `source`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `reference` varchar(255) CHARACTER SET utf8 NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `postcode` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`reference`, `address`, `postcode`, `country`) VALUES
('SMI13', '2 Brushfield St,     LONDON   ', 'E1 6AA', 'United Kingdom'),
('JON11', '  4  Gilmerton Dykes Rd, Edinburgh', 'EH17 8PG', 'uk'),
('TAY29', '     3 Melbourne    Grove, East Dulwich, London', 'SE22 8PL', 'UK'),
('WHI09', '    1 Queens Dr, Liverpool', 'L15 7NE    ', 'United Kingdom'),
('EVA10', '6 Alma Vale Rd, Bristol', 'BS8 2HY', 'Uk'),
('ROB52', '6 Kensington High St  ,Kensington,LONDON', 'W14 8NS   ', 'UK'),
('WAL99', '   4 Score Ln   , LIVERPOOL', 'L16', 'United Kingdom'),
('WIL45', '1 Old Compton Street,London', '   W1D 4HS', 'United Kingdom'),
('SMI27', '18    Lancashire Ct, Mayfair,      LONDON', 'W1S 1EY   ', 'United Kingdom'),
('COO87', '3   Front St  , Naburn,   YORK', 'YO19', 'Uk   '),
('JON11', '5   St Katharines\'s & Wapping, London', 'E1W 2SF', '    UK'),
('ROB52', '23      Newcraighall Rd, Edinburgh', 'EH16 4AA', 'United   Kingdom');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `reference` varchar(255) CHARACTER SET utf8 NOT NULL,
  `contact` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`reference`, `contact`, `type`, `description`) VALUES
('SMI27', '(+44)   1728928764', 'phone', 'business'),
('JON11', '(+44 )2451 1235 24', 'phone', 'work'),
('TAY29', '     taylor.ella@gmail.com', 'email', 'home'),
('WHI09', 'white123@yahoo.co.uk', 'email', 'work'),
('EVA10', '(  +4 4)1 334 55 2233', 'phone', 'home'),
('ROB52', '(+44)123 2435 455', 'phone', '    business'),
('WAL99', 'walker.h@gmail.com     ', 'email', 'home'),
('WIL45', 'g.williams@yahoo.co.uk', 'email', 'home'),
('COO87', '(+44) 223 13234 12', 'phone', 'work'),
('COO87', '(+ 44) 3454 532 223', '     phone', 'private'),
('SMI27', 'smith_l@mail.com', 'email', 'home'),
('JON11', '(     +44    )145 234 2345', 'phone', 'home'),
('COO87', 'rosecooper@hotmail.com', 'email', 'work'),
('WHI09', '(+4 4)1456223445', 'phone', 'work'),
('EVA10', 'evans4@gmail.com', 'email', 'home');

-- --------------------------------------------------------

--
-- Table structure for table `deceased`
--

DROP TABLE IF EXISTS `deceased`;
CREATE TABLE IF NOT EXISTS `deceased` (
  `reference` varchar(255) CHARACTER SET utf8 NOT NULL,
  `deceased` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deceased`
--

INSERT INTO `deceased` (`reference`, `deceased`) VALUES
('SMI13', '03/14/2005');

-- --------------------------------------------------------

--
-- Table structure for table `dob`
--

DROP TABLE IF EXISTS `dob`;
CREATE TABLE IF NOT EXISTS `dob` (
  `reference` varchar(255) CHARACTER SET utf8 NOT NULL,
  `dob` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dob`
--

INSERT INTO `dob` (`reference`, `dob`) VALUES
('SMI13', '03/12/1940'),
('JON11', '12/04/1987'),
('TAY29', '02/20/1967'),
('WHI09', '11/11/1987'),
('EVA10', '10/30/1988'),
('ROB52', '09/24/2000'),
('WAL99', '01/01/2001'),
('WIL45', '04/27/1976'),
('SMI27', '05/20/1999'),
('COO87', '10/13/1985');

-- --------------------------------------------------------

--
-- Table structure for table `name`
--

DROP TABLE IF EXISTS `name`;
CREATE TABLE IF NOT EXISTS `name` (
  `reference` varchar(255) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `surname` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `name`
--

INSERT INTO `name` (`reference`, `name`, `surname`) VALUES
('SMI13', 'John', 'Smith'),
('JON11', 'Noah', 'Jones'),
('TAY29', 'Ella', 'Taylor'),
('WHI09', 'Lily', 'White'),
('EVA10', 'Lucy', 'Evans'),
('ROB52', 'Jessica', 'Robinson'),
('WAL99', 'Harry', 'Walker'),
('WIL45', 'George', 'Williams'),
('SMI27', 'Lucas', 'Smith'),
('COO87', 'Rose', 'Cooper');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
