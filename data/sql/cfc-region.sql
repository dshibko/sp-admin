-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 08, 2013 at 04:50 PM
-- Server version: 5.5.25
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sp4`
--

-- --------------------------------------------------------

--
-- Table structure for table `region`
--

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `region`;
CREATE TABLE IF NOT EXISTS `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(50) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `region`
--

INSERT INTO `region` (`id`, `display_name`, `is_default`) VALUES
(1, 'Great Britain', 1),
(2, 'Europe', 0),
(3, 'South America', 0),
(4, 'North America', 0),
(5, 'Central America', 0),
(6, 'Caribbean', 0),
(7, 'Asia', 0),
(8, 'Africa', 0),
(9, 'Oceania', 0);
SET FOREIGN_KEY_CHECKS = 1;
