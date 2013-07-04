-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 01, 2013 at 05:16 AM
-- Server version: 5.5.25
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sp`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_removal`
--

CREATE TABLE IF NOT EXISTS `account_removal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `account_type` enum('direct','facebook') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_type` (`account_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `account_removal`
--


-- --------------------------------------------------------

--
-- Table structure for table `achievement_block`
--

CREATE TABLE IF NOT EXISTS `achievement_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('First Correct Result','First Correct Scorer') DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon_path` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `achievement_block`
--

INSERT INTO `achievement_block` (`id`, `type`, `title`, `description`, `icon_path`, `weight`) VALUES
(1, 'First Correct Result', 'Well done!', 'You predicted correct result first time at the season!', '/img/award/51a6086f44013.png', 1),
(2, 'First Correct Scorer', 'Well done!', 'You predicted correct scorer first time at the season!', '/img/award/51a6086f44012.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `avatar`
--

CREATE TABLE IF NOT EXISTS `avatar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_image_path` varchar(255) NOT NULL,
  `big_image_path` varchar(255) NOT NULL,
  `medium_image_path` varchar(255) NOT NULL,
  `small_image_path` varchar(255) NOT NULL,
  `tiny_image_path` varchar(255) NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `avatar`
--

INSERT INTO `avatar` (`id`, `original_image_path`, `big_image_path`, `medium_image_path`, `small_image_path`, `tiny_image_path`, `is_default`) VALUES
(1, '/img/avatar/original/user_default_1.jpg', '/img/avatar/big/user_default_1.jpg', '/img/avatar/medium/user_default_1.jpg', '/img/avatar/small/user_default_1.jpg', '/img/avatar/tiny/user_default_1.jpg', 1),
(2, '/img/avatar/original/user_default_2.jpg', '/img/avatar/big/user_default_2.jpg', '/img/avatar/medium/user_default_2.jpg', '/img/avatar/small/user_default_2.jpg', '/img/avatar/tiny/user_default_2.jpg', 1),
(3, '/img/avatar/original/user_default_3.jpg', '/img/avatar/big/user_default_3.jpg', '/img/avatar/medium/user_default_3.jpg', '/img/avatar/small/user_default_3.jpg', '/img/avatar/tiny/user_default_3.jpg', 1),
(4, '/img/avatar/original/user_default_4.jpg', '/img/avatar/big/user_default_4.jpg', '/img/avatar/medium/user_default_4.jpg', '/img/avatar/small/user_default_4.jpg', '/img/avatar/tiny/user_default_4.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `competition`
--

CREATE TABLE IF NOT EXISTS `competition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `season_id` int(11) NOT NULL,
  `feeder_id` int(11) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `season_id` (`season_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `competition`
--

INSERT INTO `competition` (`id`, `season_id`, `feeder_id`, `display_name`, `logo_path`, `start_date`, `end_date`) VALUES
(1, 1, 2, 'English League Cup', NULL, NULL, NULL),
(2, 1, 34, 'Friendly', NULL, NULL, NULL),
(3, 1, 8, 'English Barclays Premier League', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `content_image`
--

CREATE TABLE IF NOT EXISTS `content_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `width1280` varchar(255) NOT NULL,
  `width1024` varchar(255) NOT NULL,
  `width600` varchar(255) NOT NULL,
  `width480` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `content_image`
--

INSERT INTO `content_image` (`id`, `width1280`, `width1024`, `width600`, `width480`) VALUES
(20, '/img/content/519cf1c72193e.png', '/img/content/519cf1c748ae5.png', '/img/content/519cf1c77ab0f.png', '/img/content/519cf1c7aa45d.png'),
(21, '/img/content/519cf1da38180.png', '/img/content/519cf1da55e17.png', '/img/content/519cf1da86aa0.png', '/img/content/519cf1daaaf2d.png'),
(22, '/img/content/519cf1eb43630.png', '/img/content/519cf1eb7c92d.png', '/img/content/519cf1ebb74c5.png', '/img/content/519cf1ec0348d.png'),
(23, '/img/content/519e4563356e8.jpg', '/img/content/519e4563685ff.jpg', '/img/content/519e4563a3358.jpg', '/img/content/519e4563e0ff2.jpg'),
(24, '/img/content/51a3672dccb41.jpg', '/img/content/51a3672e09e94.jpg', '/img/content/51a3672e444dd.jpg', '/img/content/51a3672e7e117.jpg'),
(25, '/img/content/51a3672eaa05f.png', '/img/content/51a3672ee1e2a.png', '/img/content/51a3672f193f8.png', '/img/content/51a3672f540ea.png'),
(27, '/img/content/51a36adf4311d.png', '/img/content/51a36adf91a2d.png', '/img/content/51a36adfcc836.png', '/img/content/51a36ae012351.png'),
(28, '/img/content/51d0c09acc0df.png', '/img/content/51d0c09b051d7.png', '/img/content/51d0c09b2f411.png', '/img/content/51d0c09b680af.png');

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `short_name` varchar(30) NOT NULL,
  `iso_code` varchar(2) NOT NULL,
  `dial_code` smallint(6) DEFAULT NULL,
  `flag_image` varchar(64) NOT NULL,
  `original_flag_image` varchar(255) NOT NULL,
  `region_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=214 ;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`, `short_name`, `iso_code`, `dial_code`, `flag_image`, `original_flag_image`, `region_id`, `language_id`) VALUES
(1, 'United States', 'US', 'US', 1, '/img/flags/1.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/a/a4/Flag_of_the_United_States.svg/28px-Flag_of_the_United_States.svg.png', NULL, 1),
(2, 'Canada', 'Canada', 'CA', 1, '/img/flags/2.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/cf/Flag_of_Canada.svg/28px-Flag_of_Canada.svg.png', NULL, 1),
(3, 'Bahamas', 'Bahamas', 'BS', 242, '/img/flags/3.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/93/Flag_of_the_Bahamas.svg/28px-Flag_of_the_Bahamas.svg.png', NULL, NULL),
(4, 'Barbados', 'Barbados', 'BB', 246, '/img/flags/4.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/ef/Flag_of_Barbados.svg/28px-Flag_of_Barbados.svg.png', NULL, NULL),
(5, 'Belize', 'Belize', 'BZ', 501, '/img/flags/5.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Flag_of_Belize.svg/28px-Flag_of_Belize.svg.png', NULL, NULL),
(6, 'Bermuda', 'Bermuda', 'BM', 441, '/img/flags/6.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Flag_of_Bermuda.svg/28px-Flag_of_Bermuda.svg.png', NULL, NULL),
(7, 'British Virgin Islands', 'BVI', 'VG', 284, '/img/flags/7.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/42/Flag_of_the_British_Virgin_Islands.svg/28px-Flag_of_the_British_Virgin_Islands.svg.png', NULL, NULL),
(8, 'Cayman Islands', 'CaymanIsl', 'KY', 345, '/img/flags/8.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_the_Cayman_Islands.svg/28px-Flag_of_the_Cayman_Islands.svg.png', NULL, NULL),
(9, 'Costa Rica', 'CostaRica', 'CR', 506, '/img/flags/9.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f2/Flag_of_Costa_Rica.svg/28px-Flag_of_Costa_Rica.svg.png', NULL, NULL),
(10, 'Cuba', 'Cuba', 'CU', 53, '/img/flags/10.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/Flag_of_Cuba.svg/28px-Flag_of_Cuba.svg.png', NULL, NULL),
(11, 'Dominica', 'Dominica', 'DM', 767, '/img/flags/11.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/c4/Flag_of_Dominica.svg/28px-Flag_of_Dominica.svg.png', NULL, NULL),
(12, 'Dominican Republic', 'DominicanRep', 'DO', 809, '/img/flags/12.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Flag_of_the_Dominican_Republic.svg/28px-Flag_of_the_Dominican_Republic.svg.png', NULL, NULL),
(13, 'El Salvador', 'ElSalvador', 'SV', 503, '/img/flags/13.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Flag_of_El_Salvador.svg/28px-Flag_of_El_Salvador.svg.png', NULL, NULL),
(14, 'Greenland', 'Greenland', 'GL', 299, '/img/flags/14.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Flag_of_Greenland.svg/28px-Flag_of_Greenland.svg.png', NULL, NULL),
(15, 'Grenada', 'Grenada', 'GD', 473, '/img/flags/15.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Grenada.svg/28px-Flag_of_Grenada.svg.png', NULL, NULL),
(16, 'Guadeloupe', 'Guadeloupe', 'GP', 590, '/img/flags/16.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png', NULL, NULL),
(17, 'Guatemala', 'Guatemala', 'GT', 502, '/img/flags/17.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/Flag_of_Guatemala.svg/28px-Flag_of_Guatemala.svg.png', NULL, NULL),
(18, 'Haiti', 'Haiti', 'HT', 509, '/img/flags/18.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Flag_of_Haiti.svg/28px-Flag_of_Haiti.svg.png', NULL, NULL),
(19, 'Honduras', 'Honduras', 'HN', 503, '/img/flags/19.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Flag_of_Honduras.svg/28px-Flag_of_Honduras.svg.png', NULL, NULL),
(20, 'Jamaica', 'Jamaica', 'JM', 876, '/img/flags/20.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/0a/Flag_of_Jamaica.svg/28px-Flag_of_Jamaica.svg.png', NULL, NULL),
(21, 'Martinique', 'Martinique', 'MQ', 596, '/img/flags/21.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png', NULL, NULL),
(22, 'Mexico', 'Mexico', 'MX', 52, '/img/flags/22.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/Flag_of_Mexico.svg/28px-Flag_of_Mexico.svg.png', NULL, NULL),
(23, 'Montserrat', 'Montserrat', 'MS', 664, '/img/flags/23.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Flag_of_Montserrat.svg/28px-Flag_of_Montserrat.svg.png', NULL, NULL),
(24, 'Nicaragua', 'Nicaragua', 'NI', 505, '/img/flags/24.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Nicaragua.svg/28px-Flag_of_Nicaragua.svg.png', NULL, NULL),
(25, 'Panama', 'Panama', 'PA', 507, '/img/flags/25.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Flag_of_Panama.svg/28px-Flag_of_Panama.svg.png', NULL, NULL),
(26, 'Puerto Rico', 'PuertoRico', 'PR', 787, '/img/flags/26.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/28/Flag_of_Puerto_Rico.svg/28px-Flag_of_Puerto_Rico.svg.png', NULL, NULL),
(27, 'Trinidad and Tobago', 'Trinidad-Tobago', 'TT', 868, '/img/flags/27.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Flag_of_Trinidad_and_Tobago.svg/28px-Flag_of_Trinidad_and_Tobago.svg.png', NULL, NULL),
(28, 'United States Virgin Islands', 'USVI', 'VI', 340, '/img/flags/28.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Flag_of_the_United_States_Virgin_Islands.svg/28px-Flag_of_the_United_States_Virgin_Islands.svg.png', NULL, NULL),
(29, 'Argentina', 'Argentina', 'AR', 54, '/img/flags/29.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Flag_of_Argentina.svg/28px-Flag_of_Argentina.svg.png', NULL, NULL),
(30, 'Bolivia', 'Bolivia', 'BO', 591, '/img/flags/30.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Bolivia.svg/28px-Flag_of_Bolivia.svg.png', NULL, NULL),
(31, 'Brazil', 'Brazil', 'BR', 55, '/img/flags/31.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/0/05/Flag_of_Brazil.svg/28px-Flag_of_Brazil.svg.png', NULL, NULL),
(32, 'Chile', 'Chile', 'CL', 56, '/img/flags/32.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Flag_of_Chile.svg/28px-Flag_of_Chile.svg.png', NULL, NULL),
(33, 'Colombia', 'Colombia', 'CO', 57, '/img/flags/33.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Flag_of_Colombia.svg/28px-Flag_of_Colombia.svg.png', NULL, NULL),
(34, 'Ecuador', 'Ecuador', 'EC', 593, '/img/flags/34.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/Flag_of_Ecuador.svg/28px-Flag_of_Ecuador.svg.png', NULL, NULL),
(35, 'Falkland Islands', 'FalklandIsl', 'FK', 500, '/img/flags/35.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_the_Falkland_Islands.svg/28px-Flag_of_the_Falkland_Islands.svg.png', NULL, NULL),
(36, 'French Guiana', 'FrenchGuiana', 'GF', 594, '/img/flags/36.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png', NULL, NULL),
(37, 'Guyana', 'Guyana', 'GY', 592, '/img/flags/37.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_Guyana.svg/28px-Flag_of_Guyana.svg.png', NULL, NULL),
(38, 'Paraguay', 'Paraguay', 'PY', 595, '/img/flags/38.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Paraguay.svg/28px-Flag_of_Paraguay.svg.png', NULL, NULL),
(39, 'Peru', 'Peru', 'PE', 51, '/img/flags/39.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/cf/Flag_of_Peru.svg/28px-Flag_of_Peru.svg.png', NULL, NULL),
(40, 'Suriname', 'Suriname', 'SR', 597, '/img/flags/40.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/60/Flag_of_Suriname.svg/28px-Flag_of_Suriname.svg.png', NULL, NULL),
(41, 'Uruguay', 'Uruguay', 'UY', 598, '/img/flags/41.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_Uruguay.svg/28px-Flag_of_Uruguay.svg.png', NULL, NULL),
(42, 'Venezuela', 'Venezuela', 'VE', 58, '/img/flags/42.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/06/Flag_of_Venezuela.svg/28px-Flag_of_Venezuela.svg.png', NULL, NULL),
(43, 'Albania', 'Albania', 'AL', 355, '/img/flags/43.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/36/Flag_of_Albania.svg/28px-Flag_of_Albania.svg.png', NULL, NULL),
(44, 'Andorra', 'Andorra', 'AD', 376, '/img/flags/44.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Andorra.svg/28px-Flag_of_Andorra.svg.png', NULL, NULL),
(45, 'Armenia', 'Armenia', 'AM', 374, '/img/flags/45.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Flag_of_Armenia.svg/28px-Flag_of_Armenia.svg.png', NULL, NULL),
(46, 'Austria', 'Austria', 'AT', 43, '/img/flags/46.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Flag_of_Austria.svg/28px-Flag_of_Austria.svg.png', NULL, NULL),
(47, 'Azerbaijan', 'Azerbaijan', 'AZ', 994, '/img/flags/47.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Flag_of_Azerbaijan.svg/28px-Flag_of_Azerbaijan.svg.png', NULL, NULL),
(48, 'Belarus', 'Belarus', 'BY', 375, '/img/flags/48.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/85/Flag_of_Belarus.svg/28px-Flag_of_Belarus.svg.png', NULL, NULL),
(49, 'Belgium', 'Belgium', 'BE', 32, '/img/flags/49.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Flag_of_Belgium_%28civil%29.svg/28px-Flag_of_Belgium_%28civil%29.svg.png', NULL, NULL),
(50, 'Bosnia and Herzegovina', 'Bosnia-Herzegovina', 'BA', 387, '/img/flags/50.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Flag_of_Bosnia_and_Herzegovina.svg/28px-Flag_of_Bosnia_and_Herzegovina.svg.png', NULL, NULL),
(51, 'Bulgaria', 'Bulgaria', 'BG', 359, '/img/flags/51.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Bulgaria.svg/28px-Flag_of_Bulgaria.svg.png', NULL, NULL),
(52, 'Croatia', 'Croatia', 'HR', 385, '/img/flags/52.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Flag_of_Croatia.svg/28px-Flag_of_Croatia.svg.png', NULL, NULL),
(53, 'Cyprus', 'Cyprus', 'CY', 357, '/img/flags/53.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Flag_of_Cyprus.svg/28px-Flag_of_Cyprus.svg.png', NULL, NULL),
(54, 'Czech Republic', 'CzechRep', 'CZ', 420, '/img/flags/54.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Flag_of_the_Czech_Republic.svg/28px-Flag_of_the_Czech_Republic.svg.png', NULL, NULL),
(55, 'Denmark', 'Denmark', 'DK', 45, '/img/flags/55.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Flag_of_Denmark.svg/28px-Flag_of_Denmark.svg.png', NULL, NULL),
(56, 'Estonia', 'Estonia', 'EE', 372, '/img/flags/56.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Flag_of_Estonia.svg/28px-Flag_of_Estonia.svg.png', NULL, NULL),
(57, 'Finland', 'Finland', 'FI', 358, '/img/flags/57.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Finland.svg/28px-Flag_of_Finland.svg.png', NULL, NULL),
(58, 'France', 'France', 'FR', 33, '/img/flags/58.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png', NULL, NULL),
(59, 'Georgia', 'Georgia', 'GE', 995, '/img/flags/59.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Georgia.svg/28px-Flag_of_Georgia.svg.png', NULL, NULL),
(60, 'Germany', 'Germany', 'DE', 49, '/img/flags/60.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/b/ba/Flag_of_Germany.svg/28px-Flag_of_Germany.svg.png', NULL, NULL),
(61, 'Gibraltar', 'Gibraltar', 'GI', 350, '/img/flags/61.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/02/Flag_of_Gibraltar.svg/28px-Flag_of_Gibraltar.svg.png', NULL, NULL),
(62, 'Greece', 'Greece', 'GR', 30, '/img/flags/62.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Greece.svg/28px-Flag_of_Greece.svg.png', NULL, NULL),
(63, 'Guernsey', 'Guernsey', 'GG', 44, '/img/flags/63.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_Guernsey.svg/28px-Flag_of_Guernsey.svg.png', NULL, NULL),
(64, 'Hungary', 'Hungary', 'HU', 36, '/img/flags/64.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Flag_of_Hungary.svg/28px-Flag_of_Hungary.svg.png', NULL, NULL),
(65, 'Iceland', 'Iceland', 'IS', 354, '/img/flags/65.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/ce/Flag_of_Iceland.svg/28px-Flag_of_Iceland.svg.png', NULL, NULL),
(66, 'Ireland', 'Ireland', 'IE', 353, '/img/flags/66.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Flag_of_Ireland.svg/28px-Flag_of_Ireland.svg.png', 1, NULL),
(67, 'Isle of Man', 'IsleofMan', 'IM', 44, '/img/flags/67.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_the_Isle_of_Man.svg/28px-Flag_of_the_Isle_of_Man.svg.png', NULL, NULL),
(68, 'Italy', 'Italy', 'IT', 39, '/img/flags/68.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/0/03/Flag_of_Italy.svg/28px-Flag_of_Italy.svg.png', NULL, NULL),
(69, 'Jersey', 'Jersey', 'JE', 44, '/img/flags/69.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/1c/Flag_of_Jersey.svg/28px-Flag_of_Jersey.svg.png', NULL, NULL),
(70, 'Kosovo', 'Kosovo', '', 381, '/img/flags/70.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/1f/Flag_of_Kosovo.svg/28px-Flag_of_Kosovo.svg.png', NULL, NULL),
(71, 'Latvia', 'Latvia', 'LV', 371, '/img/flags/71.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Flag_of_Latvia.svg/28px-Flag_of_Latvia.svg.png', NULL, NULL),
(72, 'Liechtenstein', 'Liechtenstein', 'LI', 423, '/img/flags/72.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/47/Flag_of_Liechtenstein.svg/28px-Flag_of_Liechtenstein.svg.png', NULL, NULL),
(73, 'Lithuania', 'Lithuania', 'LT', 370, '/img/flags/73.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Flag_of_Lithuania.svg/28px-Flag_of_Lithuania.svg.png', NULL, NULL),
(74, 'Luxembourg', 'Luxembourg', 'LU', 352, '/img/flags/74.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/da/Flag_of_Luxembourg.svg/28px-Flag_of_Luxembourg.svg.png', NULL, NULL),
(75, 'Macedonia', 'Macedonia', 'MK', 389, '/img/flags/75.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Flag_of_Macedonia.svg/28px-Flag_of_Macedonia.svg.png', NULL, NULL),
(76, 'Malta', 'Malta', 'MT', 356, '/img/flags/76.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Flag_of_Malta.svg/28px-Flag_of_Malta.svg.png', NULL, NULL),
(77, 'Moldova', 'Moldova', 'MD', 373, '/img/flags/77.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Moldova.svg/28px-Flag_of_Moldova.svg.png', NULL, NULL),
(78, 'Monaco', 'Monaco', 'MC', 377, '/img/flags/78.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/ea/Flag_of_Monaco.svg/28px-Flag_of_Monaco.svg.png', NULL, NULL),
(79, 'Montenegro', 'Montenegro', 'ME', 381, '/img/flags/79.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Flag_of_Montenegro.svg/28px-Flag_of_Montenegro.svg.png', NULL, NULL),
(80, 'Netherlands', 'Netherlands', 'NL', 31, '/img/flags/80.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/20/Flag_of_the_Netherlands.svg/28px-Flag_of_the_Netherlands.svg.png', NULL, NULL),
(81, 'Norway', 'Norway', 'NO', 47, '/img/flags/81.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Flag_of_Norway.svg/28px-Flag_of_Norway.svg.png', NULL, NULL),
(82, 'Poland', 'Poland', 'PL', 48, '/img/flags/82.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/1/12/Flag_of_Poland.svg/28px-Flag_of_Poland.svg.png', NULL, NULL),
(83, 'Portugal', 'Portugal', 'PT', 351, '/img/flags/83.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Portugal.svg/28px-Flag_of_Portugal.svg.png', NULL, NULL),
(84, 'Romania', 'Romania', 'RO', 40, '/img/flags/84.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Flag_of_Romania.svg/28px-Flag_of_Romania.svg.png', NULL, NULL),
(85, 'Russia', 'Russia', 'RU', 7, '/img/flags/85.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/f/f3/Flag_of_Russia.svg/28px-Flag_of_Russia.svg.png', NULL, NULL),
(86, 'San Marino', 'SanMarino', 'SM', 378, '/img/flags/86.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/b1/Flag_of_San_Marino.svg/28px-Flag_of_San_Marino.svg.png', NULL, NULL),
(87, 'Serbia', 'Serbia', 'RS', 381, '/img/flags/87.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/ff/Flag_of_Serbia.svg/28px-Flag_of_Serbia.svg.png', NULL, NULL),
(88, 'Slovakia', 'Slovakia', 'SK', 421, '/img/flags/88.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Flag_of_Slovakia.svg/28px-Flag_of_Slovakia.svg.png', NULL, NULL),
(89, 'Slovenia', 'Slovenia', 'SI', 386, '/img/flags/89.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Flag_of_Slovenia.svg/28px-Flag_of_Slovenia.svg.png', NULL, NULL),
(90, 'Spain', 'Spain', 'ES', 34, '/img/flags/90.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/9/9a/Flag_of_Spain.svg/28px-Flag_of_Spain.svg.png', NULL, NULL),
(91, 'Sweden', 'Sweden', 'SE', 46, '/img/flags/91.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/4/4c/Flag_of_Sweden.svg/28px-Flag_of_Sweden.svg.png', NULL, NULL),
(92, 'Switzerland', 'Switzerland', 'CH', 41, '/img/flags/92.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Flag_of_Switzerland.svg/20px-Flag_of_Switzerland.svg.png', NULL, NULL),
(93, 'Turkey', 'Turkey', 'TR', 90, '/img/flags/93.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Flag_of_Turkey.svg/28px-Flag_of_Turkey.svg.png', NULL, NULL),
(94, 'Ukraine', 'Ukraine', 'UA', 380, '/img/flags/94.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Flag_of_Ukraine.svg/28px-Flag_of_Ukraine.svg.png', NULL, NULL),
(95, 'United Kingdom', 'UK', 'GB', 44, '/img/flags/95.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/a/ae/Flag_of_the_United_Kingdom.svg/28px-Flag_of_the_United_Kingdom.svg.png', 1, 1),
(96, 'Vatican City', 'Vatican', 'VA', 39, '/img/flags/96.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/00/Flag_of_the_Vatican_City.svg/20px-Flag_of_the_Vatican_City.svg.png', NULL, NULL),
(97, 'Afghanistan', 'Afghanistan', 'AF', 93, '/img/flags/97.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Afghanistan.svg/28px-Flag_of_Afghanistan.svg.png', NULL, NULL),
(98, 'Bahrain', 'Bahrain', 'BH', 973, '/img/flags/98.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Flag_of_Bahrain.svg/28px-Flag_of_Bahrain.svg.png', NULL, NULL),
(99, 'Bangladesh', 'Bangladesh', 'BD', 880, '/img/flags/99.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/Flag_of_Bangladesh.svg/28px-Flag_of_Bangladesh.svg.png', NULL, NULL),
(100, 'Bhutan', 'Bhutan', 'BT', 975, '/img/flags/100.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Flag_of_Bhutan.svg/28px-Flag_of_Bhutan.svg.png', NULL, NULL),
(101, 'Brunei', 'Brunei', 'BN', 673, '/img/flags/101.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Flag_of_Brunei.svg/28px-Flag_of_Brunei.svg.png', NULL, NULL),
(102, 'Cambodia', 'Cambodia', 'KH', 855, '/img/flags/102.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_Cambodia.svg/28px-Flag_of_Cambodia.svg.png', NULL, NULL),
(103, 'China', 'China', 'CN', 86, '/img/flags/103.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_the_People%27s_Republic_of_China.svg/28px-Flag_of_the_People%27s_Republic_of_China.svg.png', NULL, NULL),
(104, 'East Timor', 'EastTimor', 'TL', 670, '/img/flags/104.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Flag_of_East_Timor.svg/28px-Flag_of_East_Timor.svg.png', NULL, NULL),
(105, 'Hong Kong', 'HongKong', 'HK', 852, '/img/flags/105.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/28px-Flag_of_Japan.svg.png', NULL, NULL),
(106, 'India', 'India', 'IN', 91, '/img/flags/106.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/4/41/Flag_of_India.svg/28px-Flag_of_India.svg.png', NULL, NULL),
(107, 'Indonesia', 'Indonesia', 'ID', 62, '/img/flags/107.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Flag_of_Indonesia.svg/28px-Flag_of_Indonesia.svg.png', NULL, NULL),
(108, 'Iran', 'Iran', 'IR', 98, '/img/flags/108.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Flag_of_Iran.svg/28px-Flag_of_Iran.svg.png', NULL, NULL),
(109, 'Iraq', 'Iraq', 'IQ', 964, '/img/flags/109.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Flag_of_Iraq.svg/28px-Flag_of_Iraq.svg.png', NULL, NULL),
(110, 'Israel', 'Israel', 'IL', 972, '/img/flags/110.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Flag_of_Israel.svg/28px-Flag_of_Israel.svg.png', NULL, NULL),
(111, 'Japan', 'Japan', 'JP', 81, '/img/flags/111.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/28px-Flag_of_Japan.svg.png', NULL, NULL),
(112, 'Jordan', 'Jordan', 'JO', 962, '/img/flags/112.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/c0/Flag_of_Jordan.svg/28px-Flag_of_Jordan.svg.png', NULL, NULL),
(113, 'Kazakhstan', 'Kazakhstan', 'KZ', 7, '/img/flags/113.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/Flag_of_Kazakhstan.svg/28px-Flag_of_Kazakhstan.svg.png', NULL, NULL),
(114, 'Kuwait', 'Kuwait', 'KW', 965, '/img/flags/114.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/a/aa/Flag_of_Kuwait.svg/28px-Flag_of_Kuwait.svg.png', NULL, NULL),
(115, 'Kyrgyzstan', 'Kyrgyzstan', 'KG', 996, '/img/flags/115.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/c7/Flag_of_Kyrgyzstan.svg/28px-Flag_of_Kyrgyzstan.svg.png', NULL, NULL),
(116, 'Laos', 'Laos', 'LA', 856, '/img/flags/116.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Flag_of_Laos.svg/28px-Flag_of_Laos.svg.png', NULL, NULL),
(117, 'Lebanon', 'Lebanon', 'LB', 961, '/img/flags/117.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/59/Flag_of_Lebanon.svg/28px-Flag_of_Lebanon.svg.png', NULL, NULL),
(118, 'Macau', 'Macau', 'MO', 853, '/img/flags/118.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/63/Flag_of_Macau.svg/28px-Flag_of_Macau.svg.png', NULL, NULL),
(119, 'Malaysia', 'Malaysia', 'MY', 60, '/img/flags/119.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/66/Flag_of_Malaysia.svg/28px-Flag_of_Malaysia.svg.png', NULL, NULL),
(120, 'Maldives', 'Maldives', 'MV', 960, '/img/flags/120.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Maldives.svg/28px-Flag_of_Maldives.svg.png', NULL, NULL),
(121, 'Mongolia', 'Mongolia', 'MN', 976, '/img/flags/121.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Flag_of_Mongolia.svg/28px-Flag_of_Mongolia.svg.png', NULL, NULL),
(122, 'Myanmar (Burma)', 'Myanmar(Burma)', 'MM', 95, '/img/flags/122.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Flag_of_Myanmar.svg/28px-Flag_of_Myanmar.svg.png', NULL, NULL),
(123, 'Nepal', 'Nepal', 'NP', 977, '/img/flags/123.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Flag_of_Nepal.svg/16px-Flag_of_Nepal.svg.png', NULL, NULL),
(124, 'North Korea', 'NorthKorea', 'NP', 850, '/img/flags/124.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Flag_of_North_Korea.svg/28px-Flag_of_North_Korea.svg.png', NULL, NULL),
(125, 'Oman', 'Oman', 'OM', 968, '/img/flags/125.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Flag_of_Oman.svg/28px-Flag_of_Oman.svg.png', NULL, NULL),
(126, 'Pakistan', 'Pakistan', 'PK', 92, '/img/flags/126.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/32/Flag_of_Pakistan.svg/28px-Flag_of_Pakistan.svg.png', NULL, NULL),
(127, 'Philippines', 'Philippines', 'PH', 63, '/img/flags/127.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_the_Philippines.svg/28px-Flag_of_the_Philippines.svg.png', NULL, NULL),
(128, 'Qatar', 'Qatar', 'QA', 974, '/img/flags/128.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/65/Flag_of_Qatar.svg/28px-Flag_of_Qatar.svg.png', NULL, NULL),
(129, 'Saudi Arabia', 'SaudiArabia', 'SA', 966, '/img/flags/129.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/0d/Flag_of_Saudi_Arabia.svg/28px-Flag_of_Saudi_Arabia.svg.png', NULL, NULL),
(130, 'Singapore', 'Singapore', 'SG', 65, '/img/flags/130.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Singapore.svg/28px-Flag_of_Singapore.svg.png', NULL, NULL),
(131, 'South Korea', 'SouthKorea', 'KR', 82, '/img/flags/131.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Flag_of_South_Korea.svg/28px-Flag_of_South_Korea.svg.png', NULL, NULL),
(132, 'Sri Lanka', 'SriLanka', 'LK', 94, '/img/flags/132.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Flag_of_Sri_Lanka.svg/28px-Flag_of_Sri_Lanka.svg.png', NULL, NULL),
(133, 'Syria', 'Syria', 'SY', 963, '/img/flags/133.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Flag_of_Syria.svg/28px-Flag_of_Syria.svg.png', NULL, NULL),
(134, 'Taiwan', 'Taiwan', 'TW', 886, '/img/flags/134.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Flag_of_the_Republic_of_China.svg/28px-Flag_of_the_Republic_of_China.svg.png', NULL, NULL),
(135, 'Tajikistan', 'Tajikistan', 'TJ', 992, '/img/flags/135.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Flag_of_Tajikistan.svg/28px-Flag_of_Tajikistan.svg.png', NULL, NULL),
(136, 'Thailand', 'Thailand', 'TH', 66, '/img/flags/136.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Flag_of_Thailand.svg/28px-Flag_of_Thailand.svg.png', NULL, NULL),
(137, 'Turkmenistan', 'Turkmenistan', 'TM', 993, '/img/flags/137.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Flag_of_Turkmenistan.svg/28px-Flag_of_Turkmenistan.svg.png', NULL, NULL),
(138, 'United Arab Emirates', 'UAE', 'AE', 971, '/img/flags/138.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Flag_of_the_United_Arab_Emirates.svg/28px-Flag_of_the_United_Arab_Emirates.svg.png', NULL, NULL),
(139, 'Uzbekistan', 'Uzbekistan', 'UZ', 998, '/img/flags/139.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Flag_of_Uzbekistan.svg/28px-Flag_of_Uzbekistan.svg.png', NULL, NULL),
(140, 'Vietnam', 'Vietnam', 'VN', 84, '/img/flags/140.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Flag_of_Vietnam.svg/28px-Flag_of_Vietnam.svg.png', NULL, NULL),
(141, 'Yemen', 'Yemen', 'YE', 967, '/img/flags/141.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Flag_of_Yemen.svg/28px-Flag_of_Yemen.svg.png', NULL, NULL),
(142, 'Algeria', 'Algeria', 'DZ', 213, '/img/flags/142.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Flag_of_Algeria.svg/28px-Flag_of_Algeria.svg.png', NULL, NULL),
(143, 'Angola', 'Angola', 'AO', 244, '/img/flags/143.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/Flag_of_Angola.svg/28px-Flag_of_Angola.svg.png', NULL, NULL),
(144, 'Benin', 'Benin', 'BJ', 229, '/img/flags/144.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/0a/Flag_of_Benin.svg/28px-Flag_of_Benin.svg.png', NULL, NULL),
(145, 'Botswana', 'Botswana', 'BW', 267, '/img/flags/145.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_Botswana.svg/28px-Flag_of_Botswana.svg.png', NULL, NULL),
(146, 'Burkina Faso', 'BurkinaFaso', 'BF', 226, '/img/flags/146.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Flag_of_Burkina_Faso.svg/28px-Flag_of_Burkina_Faso.svg.png', NULL, NULL),
(147, 'Burundi', 'Burundi', 'BI', 257, '/img/flags/147.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/50/Flag_of_Burundi.svg/28px-Flag_of_Burundi.svg.png', NULL, NULL),
(148, 'Cameroon', 'Cameroon', 'CM', 237, '/img/flags/148.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Flag_of_Cameroon.svg/28px-Flag_of_Cameroon.svg.png', NULL, NULL),
(149, 'Cape Verde', 'CapeVerde', 'CV', 238, '/img/flags/149.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Flag_of_Cape_Verde.svg/28px-Flag_of_Cape_Verde.svg.png', NULL, NULL),
(150, 'Central African Republic', 'CentralAfricanRep', 'CF', 236, '/img/flags/150.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Flag_of_the_Central_African_Republic.svg/28px-Flag_of_the_Central_African_Republic.svg.png', NULL, NULL),
(151, 'Chad', 'Chad', 'TD', 235, '/img/flags/151.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Flag_of_Chad.svg/28px-Flag_of_Chad.svg.png', NULL, NULL),
(152, 'Congo-Brazzaville', 'Congo-Brazzaville', 'CG', 242, '/img/flags/152.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Flag_of_the_Republic_of_the_Congo.svg/28px-Flag_of_the_Republic_of_the_Congo.svg.png', NULL, NULL),
(153, 'Congo-Kinshasa', 'Congo-Kinshasa', 'CD', 242, '/img/flags/153.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Flag_of_the_Democratic_Republic_of_the_Congo.svg/28px-Flag_of_the_Democratic_Republic_of_the_Congo.svg.png', NULL, NULL),
(154, 'Djibouti', 'Djibouti', 'DJ', 253, '/img/flags/154.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Flag_of_Djibouti.svg/28px-Flag_of_Djibouti.svg.png', NULL, NULL),
(155, 'Egypt', 'Egypt', 'EG', 20, '/img/flags/155.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_Egypt.svg/28px-Flag_of_Egypt.svg.png', NULL, NULL),
(156, 'Equatorial Guinea', 'EquatorialGuinea', 'GQ', 240, '/img/flags/156.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Flag_of_Equatorial_Guinea.svg/28px-Flag_of_Equatorial_Guinea.svg.png', NULL, NULL),
(157, 'Eritrea', 'Eritrea', 'ER', 291, '/img/flags/157.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/29/Flag_of_Eritrea.svg/28px-Flag_of_Eritrea.svg.png', NULL, NULL),
(158, 'Ethiopia', 'Ethiopia', 'ET', 251, '/img/flags/158.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/71/Flag_of_Ethiopia.svg/28px-Flag_of_Ethiopia.svg.png', NULL, NULL),
(159, 'Gabon', 'Gabon', 'GA', 241, '/img/flags/159.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Flag_of_Gabon.svg/28px-Flag_of_Gabon.svg.png', NULL, NULL),
(160, 'Gambia', 'Gambia', 'GM', 220, '/img/flags/160.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Flag_of_The_Gambia.svg/28px-Flag_of_The_Gambia.svg.png', NULL, NULL),
(161, 'Ghana', 'Ghana', 'GH', 233, '/img/flags/161.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Ghana.svg/28px-Flag_of_Ghana.svg.png', NULL, NULL),
(162, 'Guinea', 'Guinea', 'GN', 224, '/img/flags/162.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/ed/Flag_of_Guinea.svg/28px-Flag_of_Guinea.svg.png', NULL, NULL),
(163, 'Guinea-Bissau', 'Guinea-Bissau', 'GW', 245, '/img/flags/163.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/01/Flag_of_Guinea-Bissau.svg/28px-Flag_of_Guinea-Bissau.svg.png', NULL, NULL),
(164, 'Ivory Coast', 'IvoryCoast', 'CI', 225, '/img/flags/164.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_C%C3%B4te_d%27Ivoire.svg/28px-Flag_of_C%C3%B4te_d%27Ivoire.svg.png', NULL, NULL),
(165, 'Kenya', 'Kenya', 'KE', 254, '/img/flags/165.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Flag_of_Kenya.svg/28px-Flag_of_Kenya.svg.png', NULL, NULL),
(166, 'Lesotho', 'Lesotho', 'LS', 266, '/img/flags/166.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/4a/Flag_of_Lesotho.svg/28px-Flag_of_Lesotho.svg.png', NULL, NULL),
(167, 'Liberia', 'Liberia', 'LR', 231, '/img/flags/167.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/Flag_of_Liberia.svg/28px-Flag_of_Liberia.svg.png', NULL, NULL),
(168, 'Libya', 'Libya', 'LY', 218, '/img/flags/168.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Flag_of_Libya.svg/28px-Flag_of_Libya.svg.png', NULL, NULL),
(169, 'Madagascar', 'Madagascar', 'MG', 261, '/img/flags/169.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Madagascar.svg/28px-Flag_of_Madagascar.svg.png', NULL, NULL),
(170, 'Malawi', 'Malawi', 'MW', 265, '/img/flags/170.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Flag_of_Malawi.svg/28px-Flag_of_Malawi.svg.png', NULL, NULL),
(171, 'Mali', 'Mali', 'ML', 223, '/img/flags/171.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Flag_of_Mali.svg/28px-Flag_of_Mali.svg.png', NULL, NULL),
(172, 'Mauritania', 'Mauritania', 'MR', 222, '/img/flags/172.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/43/Flag_of_Mauritania.svg/28px-Flag_of_Mauritania.svg.png', NULL, NULL),
(173, 'Mauritius', 'Mauritius', 'MU', 230, '/img/flags/173.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Flag_of_Mauritius.svg/28px-Flag_of_Mauritius.svg.png', NULL, NULL),
(174, 'Morocco', 'Morocco', 'MA', 212, '/img/flags/174.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Flag_of_Morocco.svg/28px-Flag_of_Morocco.svg.png', NULL, NULL),
(175, 'Mozambique', 'Mozambique', 'MZ', 258, '/img/flags/175.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Flag_of_Mozambique.svg/28px-Flag_of_Mozambique.svg.png', NULL, NULL),
(176, 'Namibia', 'Namibia', 'NA', 264, '/img/flags/176.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/00/Flag_of_Namibia.svg/28px-Flag_of_Namibia.svg.png', NULL, NULL),
(177, 'Niger', 'Niger', 'NE', 227, '/img/flags/177.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f4/Flag_of_Niger.svg/28px-Flag_of_Niger.svg.png', NULL, NULL),
(178, 'Nigeria', 'Nigeria', 'NG', 234, '/img/flags/178.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/79/Flag_of_Nigeria.svg/28px-Flag_of_Nigeria.svg.png', NULL, NULL),
(179, 'Reunion', 'Reunion', 'RE', 262, '/img/flags/179.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png', NULL, NULL),
(180, 'Rwanda', 'Rwanda', 'RW', 250, '/img/flags/180.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Flag_of_Rwanda.svg/28px-Flag_of_Rwanda.svg.png', NULL, NULL),
(181, 'Sao Tome and Principe', 'SaoTome-Principe', 'ST', 239, '/img/flags/181.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Flag_of_Sao_Tome_and_Principe.svg/28px-Flag_of_Sao_Tome_and_Principe.svg.png', NULL, NULL),
(182, 'Senegal', 'Senegal', 'SN', 221, '/img/flags/182.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fd/Flag_of_Senegal.svg/28px-Flag_of_Senegal.svg.png', NULL, NULL),
(183, 'Seychelles', 'Seychelles', 'SC', 248, '/img/flags/183.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/Flag_of_Seychelles.svg/28px-Flag_of_Seychelles.svg.png', NULL, NULL),
(184, 'Sierra Leone', 'SierraLeone', 'SL', 232, '/img/flags/184.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Flag_of_Sierra_Leone.svg/28px-Flag_of_Sierra_Leone.svg.png', NULL, NULL),
(185, 'Somalia', 'Somalia', 'SO', 252, '/img/flags/185.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Flag_of_Somalia.svg/28px-Flag_of_Somalia.svg.png', NULL, NULL),
(186, 'South Africa', 'SouthAfrica', 'ZA', 27, '/img/flags/186.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Flag_of_South_Africa.svg/28px-Flag_of_South_Africa.svg.png', NULL, NULL),
(187, 'Sudan', 'Sudan', 'SD', 249, '/img/flags/187.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/01/Flag_of_Sudan.svg/28px-Flag_of_Sudan.svg.png', NULL, NULL),
(188, 'Swaziland', 'Swaziland', 'SZ', 268, '/img/flags/188.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/Flag_of_Swaziland.svg/28px-Flag_of_Swaziland.svg.png', NULL, NULL),
(189, 'Tanzania', 'Tanzania', 'TZ', 255, '/img/flags/189.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Flag_of_Tanzania.svg/28px-Flag_of_Tanzania.svg.png', NULL, NULL),
(190, 'Togo', 'Togo', 'TG', 228, '/img/flags/190.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/68/Flag_of_Togo.svg/28px-Flag_of_Togo.svg.png', NULL, NULL),
(191, 'Tunisia', 'Tunisia', 'TN', 216, '/img/flags/191.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/ce/Flag_of_Tunisia.svg/28px-Flag_of_Tunisia.svg.png', NULL, NULL),
(192, 'Uganda', 'Uganda', 'UG', 256, '/img/flags/192.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Flag_of_Uganda.svg/28px-Flag_of_Uganda.svg.png', NULL, NULL),
(193, 'Western Sahara', 'WesternSahara', 'EH', 212, '/img/flags/193.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Flag_of_the_Sahrawi_Arab_Democratic_Republic.svg/28px-Flag_of_the_Sahrawi_Arab_Democratic_Republic.svg.png', NULL, NULL),
(194, 'Zambia', 'Zambia', 'ZM', 260, '/img/flags/194.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/06/Flag_of_Zambia.svg/28px-Flag_of_Zambia.svg.png', NULL, NULL),
(195, 'Zimbabwe', 'Zimbabwe', 'ZW', 263, '/img/flags/195.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/Flag_of_Zimbabwe.svg/28px-Flag_of_Zimbabwe.svg.png', NULL, NULL),
(196, 'Australia', 'Australia', 'AU', 61, '/img/flags/196.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/b/b9/Flag_of_Australia.svg/28px-Flag_of_Australia.svg.png', NULL, 1),
(197, 'New Zealand', 'NewZealand', 'NZ', 64, '/img/flags/197.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Flag_of_New_Zealand.svg/28px-Flag_of_New_Zealand.svg.png', NULL, NULL),
(198, 'Fiji', 'Fiji', 'FJ', 679, '/img/flags/198.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Flag_of_Fiji.svg/28px-Flag_of_Fiji.svg.png', NULL, NULL),
(199, 'French Polynesia', 'FrenchPolynesia', 'PF', 689, '/img/flags/199.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/db/Flag_of_French_Polynesia.svg/28px-Flag_of_French_Polynesia.svg.png', NULL, NULL),
(200, 'Guam', 'Guam', 'GU', 671, '/img/flags/200.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/07/Flag_of_Guam.svg/28px-Flag_of_Guam.svg.png', NULL, NULL),
(201, 'Kiribati', 'Kiribati', 'KI', 686, '/img/flags/201.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/Flag_of_Kiribati.svg/28px-Flag_of_Kiribati.svg.png', NULL, NULL),
(202, 'Marshall Islands', 'MarshallIsl', 'MH', 692, '/img/flags/202.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Flag_of_the_Marshall_Islands.svg/28px-Flag_of_the_Marshall_Islands.svg.png', NULL, NULL),
(203, 'Micronesia', 'Micronesia', 'FM', 691, '/img/flags/203.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/e4/Flag_of_the_Federated_States_of_Micronesia.svg/28px-Flag_of_the_Federated_States_of_Micronesia.svg.png', NULL, NULL),
(204, 'Nauru', 'Nauru', 'NR', 674, '/img/flags/204.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/30/Flag_of_Nauru.svg/28px-Flag_of_Nauru.svg.png', NULL, NULL),
(205, 'New Caledonia', 'NewCaledonia', 'NC', 687, '/img/flags/205.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Flag_of_New_Caledonia.svg/28px-Flag_of_New_Caledonia.svg.png', NULL, NULL),
(206, 'Papua New Guinea', 'PapuaNewGuinea', 'PG', 675, '/img/flags/206.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/e3/Flag_of_Papua_New_Guinea.svg/28px-Flag_of_Papua_New_Guinea.svg.png', NULL, NULL),
(207, 'Samoa', 'Samoa', 'WS', 684, '/img/flags/207.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Flag_of_Samoa.svg/28px-Flag_of_Samoa.svg.png', NULL, NULL),
(208, 'Solomon Islands', 'SolomonIsl', 'SB', 677, '/img/flags/208.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/74/Flag_of_the_Solomon_Islands.svg/28px-Flag_of_the_Solomon_Islands.svg.png', NULL, NULL),
(209, 'Tonga', 'Tonga', 'TO', 676, '/img/flags/209.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Tonga.svg/28px-Flag_of_Tonga.svg.png', NULL, NULL),
(210, 'Tuvalu', 'Tuvalu', 'TV', 688, '/img/flags/210.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Flag_of_Tuvalu.svg/28px-Flag_of_Tuvalu.svg.png', NULL, NULL),
(211, 'Vanuatu', 'Vanuatu', 'VU', 678, '/img/flags/211.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Vanuatu.svg/28px-Flag_of_Vanuatu.svg.png', NULL, NULL),
(212, 'Wallis and Futuna', 'Wallis-Futuna', 'WF', 681, '/img/flags/212.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d2/Flag_of_Wallis_and_Futuna.svg/28px-Flag_of_Wallis_and_Futuna.svg.png', NULL, NULL),
(213, 'South Sudan', 'SouthSudan', 'SS', 211, '/img/flags/213.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/7a/Flag_of_South_Sudan.svg/28px-Flag_of_South_Sudan.svg.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `default_report_content`
--

CREATE TABLE IF NOT EXISTS `default_report_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `intro` text NOT NULL,
  `header_image` varchar(255) NOT NULL,
  `region_id` int(11) NOT NULL,
  `report_type` enum('Pre-Match','Post-Match') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_default_report_content_region` (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `default_report_content`
--

INSERT INTO `default_report_content` (`id`, `title`, `intro`, `header_image`, `region_id`, `report_type`) VALUES
(1, 'Pre-Match Report', 'Pre-Match Report Intro', '/img/report/51d06c09013dc.png', 1, 'Pre-Match'),
(2, 'Post-Match Report', 'Post-Match Report Intro', '/img/report/51d062b15a93f.png', 1, 'Post-Match');

-- --------------------------------------------------------

--
-- Table structure for table `emblem`
--

CREATE TABLE IF NOT EXISTS `emblem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `emblem`
--

INSERT INTO `emblem` (`id`, `path`) VALUES
(1, '/img/logotype/51d0c1cf00f0a.png');

-- --------------------------------------------------------

--
-- Table structure for table `featured_goalkeeper`
--

CREATE TABLE IF NOT EXISTS `featured_goalkeeper` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) DEFAULT NULL,
  `saves` int(6) DEFAULT '0',
  `matches_played` int(3) DEFAULT '0',
  `penalty_saves` int(6) DEFAULT '0',
  `clean_sheets` int(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `featured_goalkeeper`
--


-- --------------------------------------------------------

--
-- Table structure for table `featured_player`
--

CREATE TABLE IF NOT EXISTS `featured_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) DEFAULT NULL,
  `goals` int(3) DEFAULT '0',
  `matches_played` int(3) DEFAULT '0',
  `match_starts` int(3) DEFAULT '0',
  `minutes_played` int(6) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `featured_player`
--


-- --------------------------------------------------------

--
-- Table structure for table `featured_prediction`
--

CREATE TABLE IF NOT EXISTS `featured_prediction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `copy` text,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `featured_prediction`
--


-- --------------------------------------------------------

--
-- Table structure for table `feed`
--

CREATE TABLE IF NOT EXISTS `feed` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `type` enum('F1','F2','F7','F40') DEFAULT NULL,
  `last_sync_result` enum('Success','Error') NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_name` (`file_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `feed`
--

INSERT INTO `feed` (`id`, `file_name`, `type`, `last_sync_result`, `last_update`) VALUES
(1, 'srml-2-2013-results.xml', 'F1', 'Success', '2013-07-01 00:28:44'),
(2, 'srml-34-2013-results.xml', 'F1', 'Success', '2013-07-01 00:46:04'),
(3, 'srml-8-2013-results.xml', 'F1', 'Success', '2013-07-01 00:28:07'),
(4, 'srml-8-2013-squads.xml', 'F40', 'Success', '2013-07-01 00:28:07');

-- --------------------------------------------------------

--
-- Table structure for table `footer_image`
--

CREATE TABLE IF NOT EXISTS `footer_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) NOT NULL,
  `footer_image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `footer_image`
--

INSERT INTO `footer_image` (`id`, `region_id`, `footer_image`) VALUES
(1, 1, '/img/content/51d0be58590ac.png');

-- --------------------------------------------------------

--
-- Table structure for table `footer_page`
--

CREATE TABLE IF NOT EXISTS `footer_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `type` enum('terms','privacy','contact-us','cookies-policy','help-and-support') NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_footer_page_language` (`language_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `footer_page`
--


-- --------------------------------------------------------

--
-- Table structure for table `footer_social`
--

CREATE TABLE IF NOT EXISTS `footer_social` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) NOT NULL,
  `url` varchar(500) NOT NULL,
  `copy` varchar(100) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `order` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `footer_social`
--

INSERT INTO `footer_social` (`id`, `region_id`, `url`, `copy`, `icon`, `order`) VALUES
(1, 1, 'http://facebook.com/', 'Like xxx on Facebook', '/img/content/519e82659e70b.png', 1),
(2, 1, 'http://twitter.com/', 'Follow xxx on Twitter', '/img/content/519e83fba9a6d.png', 2);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_code` varchar(5) NOT NULL,
  `display_name` varchar(40) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `language_code`, `display_name`, `is_default`) VALUES
(1, 'en_EN', 'English', 1);

-- --------------------------------------------------------

--
-- Table structure for table `league`
--

CREATE TABLE IF NOT EXISTS `league` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(50) NOT NULL,
  `season_id` int(11) NOT NULL,
  `type` enum('Global','Regional','Mini','Private') NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `creator_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `season_id` (`season_id`),
  KEY `creator_id` (`creator_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `league`
--

INSERT INTO `league` (`id`, `display_name`, `season_id`, `type`, `start_date`, `end_date`, `logo_path`, `creation_date`, `creator_id`) VALUES
(1, 'Global 2013/2014', 1, 'Global', '2013-07-01', '2014-06-01', NULL, '2013-07-01 00:51:29', 1),
(2, 'Regional 2013/2014 British Isles', 1, 'Regional', '2013-07-01', '2014-06-01', NULL, '2013-07-01 00:51:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `league_region`
--

CREATE TABLE IF NOT EXISTS `league_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `league_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `league_id` (`league_id`),
  KEY `region_id` (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `league_region`
--

INSERT INTO `league_region` (`id`, `league_id`, `region_id`, `display_name`) VALUES
(1, 2, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `league_user`
--

CREATE TABLE IF NOT EXISTS `league_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `league_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points` int(11) DEFAULT NULL,
  `accuracy` int(11) DEFAULT NULL,
  `place` int(11) DEFAULT NULL,
  `previous_place` int(11) DEFAULT NULL,
  `join_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `league_id` (`league_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `league_user`
--

INSERT INTO `league_user` (`id`, `league_id`, `user_id`, `points`, `accuracy`, `place`, `previous_place`, `join_date`) VALUES
(1, 1, 1, NULL, NULL, NULL, NULL, '2013-07-01 00:51:29'),
(2, 2, 1, NULL, NULL, NULL, NULL, '2013-07-01 00:51:29');

-- --------------------------------------------------------

--
-- Table structure for table `league_user_place`
--

CREATE TABLE IF NOT EXISTS `league_user_place` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `league_user_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `place` int(11) NOT NULL,
  `previous_place` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `league_user_id` (`league_user_id`),
  KEY `match_id` (`match_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `league_user_place`
--


-- --------------------------------------------------------

--
-- Table structure for table `line_up`
--

CREATE TABLE IF NOT EXISTS `line_up` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `is_start` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `match_id` (`match_id`),
  KEY `team_id` (`team_id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `line_up`
--


-- --------------------------------------------------------

--
-- Table structure for table `logotype`
--

CREATE TABLE IF NOT EXISTS `logotype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `emblem_id` int(11) NOT NULL,
  `logotype_image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_id` (`language_id`),
  KEY `emblem_id` (`emblem_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `logotype`
--

INSERT INTO `logotype` (`id`, `language_id`, `emblem_id`, `logotype_image_path`) VALUES
(1, 1, 1, '/img/logotype/51d0c1cf01965.png');

-- --------------------------------------------------------

--
-- Table structure for table `match`
--

CREATE TABLE IF NOT EXISTS `match` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `competition_id` int(11) NOT NULL,
  `home_team_id` int(11) NOT NULL,
  `away_team_id` int(11) NOT NULL,
  `feeder_id` int(11) DEFAULT NULL,
  `week` smallint(6) DEFAULT NULL,
  `stadium_name` varchar(100) DEFAULT NULL,
  `city_name` varchar(100) DEFAULT NULL,
  `is_double_points` tinyint(1) NOT NULL,
  `featured_prediction_id` int(11) DEFAULT NULL,
  `featured_player_id` int(11) DEFAULT NULL,
  `status` enum('PreMatch','Live','FullTime','Postponed','Abandoned') NOT NULL,
  `home_team_full_time_score` tinyint(4) DEFAULT NULL,
  `away_team_full_time_score` tinyint(4) DEFAULT NULL,
  `home_team_extra_time_score` tinyint(4) DEFAULT NULL,
  `away_team_extra_time_score` tinyint(4) DEFAULT NULL,
  `home_team_shootout_score` tinyint(4) DEFAULT NULL,
  `away_team_shootout_score` tinyint(4) DEFAULT NULL,
  `timezone` varchar(5) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `has_line_up` tinyint(1) NOT NULL DEFAULT '0',
  `is_blocked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `competition_id` (`competition_id`),
  KEY `home_team_id` (`home_team_id`),
  KEY `away_team_id` (`away_team_id`),
  KEY `featured_prediction_id` (`featured_prediction_id`),
  KEY `featured_player_id` (`featured_player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- Dumping data for table `match`
--

INSERT INTO `match` (`id`, `competition_id`, `home_team_id`, `away_team_id`, `feeder_id`, `week`, `stadium_name`, `city_name`, `is_double_points`, `featured_prediction_id`, `featured_player_id`, `status`, `home_team_full_time_score`, `away_team_full_time_score`, `home_team_extra_time_score`, `away_team_extra_time_score`, `home_team_shootout_score`, `away_team_shootout_score`, `timezone`, `start_time`, `has_line_up`, `is_blocked`) VALUES
(1, 2, 1, 2, 691748, NULL, 'Busch Stadium', 'St. Louis', 0, NULL, NULL, 'FullTime', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-05-24 00:30:00', 0, 0),
(2, 2, 1, 2, 691749, NULL, 'Yankee Stadium', 'New York', 0, NULL, NULL, 'FullTime', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-05-25 22:06:00', 0, 0),
(3, 2, 3, 1, 691750, NULL, 'Rajamangala National Stadium', NULL, 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-07-17 13:00:00', 0, 0),
(4, 2, 4, 1, 691751, NULL, 'Shah Alam Stadium', 'Penang', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-07-21 13:45:00', 0, 0),
(5, 2, 5, 1, 691752, NULL, 'Gelora Bung Karno Stadium', 'Jakarta', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-07-25 13:00:00', 0, 0),
(6, 3, 1, 6, 694902, 1, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-08-17 14:00:00', 0, 0),
(7, 3, 7, 1, 694916, 2, 'Old Trafford', 'Manchester', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-08-24 14:00:00', 0, 0),
(8, 3, 1, 8, 694922, 3, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-08-31 14:00:00', 0, 0),
(9, 3, 9, 1, 694932, 4, 'Goodison Park', 'Liverpool', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-09-14 14:00:00', 0, 0),
(10, 3, 1, 10, 694942, 5, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-09-21 14:00:00', 0, 0),
(11, 3, 11, 1, 694960, 6, 'White Hart Lane', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-09-28 14:00:00', 0, 0),
(12, 3, 12, 1, 694966, 7, 'Carrow Road', 'Norwich', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-10-05 14:00:00', 0, 0),
(13, 3, 1, 13, 694972, 8, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-10-19 14:00:00', 0, 0),
(14, 3, 1, 2, 694982, 9, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2013-10-26 14:00:00', 0, 0),
(15, 3, 14, 1, 694996, 10, 'St. James'' Park', 'Newcastle', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2013-11-02 15:00:00', 0, 0),
(16, 3, 1, 15, 695002, 11, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2013-11-09 15:00:00', 0, 0),
(17, 3, 16, 1, 695019, 12, 'Boleyn Ground', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2013-11-23 15:00:00', 0, 0),
(18, 3, 1, 17, 695023, 13, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2013-11-30 15:00:00', 0, 0),
(19, 3, 18, 1, 695037, 14, 'Stadium of Light', 'Sunderland', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2013-12-03 19:45:00', 0, 0),
(20, 3, 19, 1, 695047, 15, 'Britannia Stadium', 'Stoke', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2013-12-07 15:00:00', 0, 0),
(21, 3, 1, 20, 695053, 16, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2013-12-14 15:00:00', 0, 0),
(22, 3, 21, 1, 695061, 17, 'Emirates Stadium', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2013-12-21 15:00:00', 0, 0),
(23, 3, 1, 22, 695073, 18, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2013-12-26 15:00:00', 0, 0),
(24, 3, 1, 23, 695083, 19, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2013-12-28 15:00:00', 0, 0),
(25, 3, 17, 1, 695096, 20, 'St. Mary''s Stadium', 'Southampton', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-01-01 15:00:00', 0, 0),
(26, 3, 6, 1, 695105, 21, 'The KC Stadium', 'Hull', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-01-11 15:00:00', 0, 0),
(27, 3, 1, 7, 695112, 22, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-01-18 15:00:00', 0, 0),
(28, 3, 1, 16, 695129, 23, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-01-29 19:45:00', 0, 0),
(29, 3, 2, 1, 695136, 24, 'Etihad Stadium', 'Manchester', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-02-01 15:00:00', 0, 0),
(30, 3, 1, 14, 695142, 25, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-02-08 15:00:00', 0, 0),
(31, 3, 15, 1, 695155, 26, 'The Hawthorns', 'West Bromwich', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-02-11 20:00:00', 0, 0),
(32, 3, 1, 9, 695162, 27, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-02-22 15:00:00', 0, 0),
(33, 3, 10, 1, 695173, 28, 'Craven Cottage', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-03-01 15:00:00', 0, 0),
(34, 3, 1, 11, 695182, 29, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-03-08 15:00:00', 0, 0),
(35, 3, 8, 1, 695191, 30, 'Villa Park', 'Birmingham', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-03-15 15:00:00', 0, 0),
(36, 3, 1, 21, 695203, 31, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-03-22 15:00:00', 0, 0),
(37, 3, 20, 1, 695212, 32, 'Selhurst Park', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'GMT', '2014-03-29 15:00:00', 0, 0),
(38, 3, 1, 19, 695223, 33, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2014-04-05 14:00:00', 0, 0),
(39, 3, 22, 1, 695238, 34, 'Liberty Stadium', 'Swansea', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2014-04-12 14:00:00', 0, 0),
(40, 3, 1, 18, 695243, 35, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2014-04-19 14:00:00', 0, 0),
(41, 3, 23, 1, 695254, 36, 'Anfield', 'Liverpool', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2014-04-26 14:00:00', 0, 0),
(42, 3, 1, 12, 695263, 37, 'Stamford Bridge', 'London', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2014-05-03 14:00:00', 0, 0),
(43, 3, 13, 1, 695271, 38, 'Cardiff City Stadium', 'Cardiff', 0, NULL, NULL, 'PreMatch', NULL, NULL, NULL, NULL, NULL, NULL, 'BST', '2014-05-11 14:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `match_goal`
--

CREATE TABLE IF NOT EXISTS `match_goal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `type` enum('Goal','Penalty','Own') NOT NULL,
  `period` enum('FirstHalf','SecondHalf','ExtraFirstHalf','ExtraSecondHalf','ShootOut') NOT NULL,
  `minute` smallint(6) DEFAULT NULL,
  `time` datetime NOT NULL,
  `order` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `match_id` (`match_id`),
  KEY `team_id` (`team_id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `match_goal`
--


-- --------------------------------------------------------

--
-- Table structure for table `match_region`
--

CREATE TABLE IF NOT EXISTS `match_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `featured_player_id` int(11) DEFAULT NULL,
  `featured_goalkeeper_id` int(11) DEFAULT NULL,
  `featured_prediction_id` int(11) DEFAULT NULL,
  `pre_match_report_title` varchar(255) DEFAULT NULL,
  `pre_match_report_intro` text,
  `pre_match_report_header_image_path` varchar(255) DEFAULT NULL,
  `post_match_report_title` varchar(255) DEFAULT NULL,
  `post_match_report_intro` text,
  `post_match_report_header_image_path` varchar(255) DEFAULT NULL,
  `display_featured_player` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `match_id` (`match_id`),
  KEY `region_id` (`region_id`),
  KEY `featured_player_id` (`featured_player_id`),
  KEY `featured_goalkeeper_id` (`featured_goalkeeper_id`),
  KEY `featured_prediction_id` (`featured_prediction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `match_region`
--


-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `name`) VALUES
(1, 'view'),
(2, 'edit'),
(3, 'add');

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE IF NOT EXISTS `player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `feeder_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `position` varchar(20) NOT NULL,
  `real_position` varchar(50) DEFAULT NULL,
  `real_position_side` varchar(20) DEFAULT NULL,
  `shirt_number` tinyint(4) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `background_image_path` varchar(255) DEFAULT NULL,
  `is_blocked` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=679 ;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`id`, `team_id`, `feeder_id`, `name`, `surname`, `display_name`, `position`, `real_position`, `real_position_side`, `shirt_number`, `weight`, `height`, `birth_date`, `join_date`, `country`, `image_path`, `background_image_path`, `is_blocked`) VALUES
(1, 21, 59936, 'Wojciech', 'Szczesny', 'Wojciech Szczesny', 'Goalkeeper', 'Goalkeeper', NULL, 1, 84, 196, '1990-04-18', '2008-07-01', 'Poland', NULL, NULL, 0),
(2, 21, 37096, 'Lukasz', 'Fabianski', 'Lukasz Fabianski', 'Goalkeeper', 'Goalkeeper', NULL, 21, 83, 190, '1985-04-18', '2007-05-26', 'Poland', NULL, NULL, 0),
(3, 21, 20487, 'Vito', 'Mannone', 'Vito Mannone', 'Goalkeeper', 'Goalkeeper', NULL, 24, 80, 188, '1988-03-02', '2005-07-04', 'Italy', NULL, NULL, 0),
(4, 21, 98980, 'Damián', 'Martinez', 'Damián Martinez', 'Goalkeeper', 'Goalkeeper', NULL, 36, 89, 183, '1992-09-02', '2011-08-01', 'Argentina', NULL, NULL, 0),
(5, 21, 37748, 'Bacary', 'Sagna', 'Bacary Sagna', 'Defender', 'Full Back', 'Right', 3, 72, 176, '1983-02-14', '2007-07-12', 'France', NULL, NULL, 0),
(6, 21, 17127, 'Per', 'Mertesacker', 'Per Mertesacker', 'Defender', 'Central Defender', 'Centre', 4, 90, 198, '1984-09-29', '2011-08-31', 'Germany', NULL, NULL, 0),
(7, 21, 15943, 'Thomas', 'Vermaelen', 'Thomas Vermaelen', 'Defender', 'Central Defender', 'Left/Centre', 5, 75, 180, '1985-11-14', '2009-06-19', 'Belgium', NULL, NULL, 0),
(8, 21, 51507, 'Laurent', 'Koscielny', 'Laurent Koscielny', 'Defender', 'Central Defender', 'Centre', 6, 75, 186, '1985-09-10', '2010-07-07', 'France', NULL, NULL, 0),
(9, 21, 57214, 'André', 'Clarindo Dos Santos', 'André Santos', 'Defender', 'Full Back', 'Left', 11, 73, 180, '1983-03-08', '2011-08-31', 'Brazil', NULL, NULL, 0),
(10, 21, 38411, 'Nacho', 'Monreal', 'Nacho Monreal', 'Defender', 'Full Back', 'Left', 17, 72, 178, '1986-02-26', '2013-01-31', 'Spain', NULL, NULL, 0),
(11, 21, 80254, 'Carl', 'Jenkinson', 'Carl Jenkinson', 'Defender', 'Full Back', 'Left', 25, 77, 185, '1992-02-08', '2011-06-09', 'England', NULL, NULL, 0),
(12, 21, 42427, 'Kieran', 'Gibbs', 'Kieran Gibbs', 'Defender', 'Full Back', 'Left', 28, 70, 178, '1989-09-26', '2006-07-01', 'England', NULL, NULL, 0),
(13, 21, 133797, 'Martin', 'Angha', 'Martin Angha', 'Defender', 'Central Defender', 'Centre', 38, 81, 187, '1994-01-22', '2012-09-01', 'Switzerland', NULL, NULL, 0),
(14, 21, 98745, 'Hector', 'Bellerin', 'Hector Bellerin', 'Defender', 'Full Back', 'Right', 40, 74, 177, '1993-03-19', '2012-09-22', 'Spain', NULL, NULL, 0),
(15, 21, 88496, 'Daniel', 'Boateng', 'Daniel Boateng', 'Defender', 'Central Defender', 'Centre', 42, 78, 183, '1992-09-02', '2010-09-01', 'England', NULL, NULL, 0),
(16, 21, 90801, 'Ignasi', 'Miquel', 'Ignasi Miquel', 'Defender', 'Central Defender', 'Centre', 54, 85, 193, '1992-09-28', '2010-09-01', 'Spain', NULL, NULL, 0),
(17, 21, 147676, 'Elton', 'Monteiro', 'Elton Monteiro', 'Defender', 'Central Defender', 'Centre', 55, 85, 192, '1994-02-22', '2012-12-01', 'Switzerland', NULL, NULL, 0),
(18, 21, 86364, 'Nicholas', 'Yennaris', 'Nicholas Yennaris', 'Defender', 'Full Back', 'Right', 64, 65, 175, '1993-05-23', '2010-07-20', 'England', NULL, NULL, 0),
(19, 21, 28566, 'Vassiriki', 'Abou Diaby', 'Vassiriki Abou Diaby', 'Midfielder', 'Central Midfielder', 'Left/Centre', 2, 74, 192, '1986-05-11', '2006-01-13', 'France', NULL, NULL, 0),
(20, 21, 8597, 'Tomas', 'Rosicky', 'Tomas Rosicky', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 7, 65, 178, '1980-10-04', '2006-05-23', 'Czech Republic', NULL, NULL, 0),
(21, 21, 8758, 'Mikel', 'Arteta', 'Mikel Arteta', 'Midfielder', 'Central Midfielder', 'Left/Centre/Right', 8, 64, 183, '1982-03-26', '2011-08-31', 'Spain', NULL, NULL, 0),
(22, 21, 54102, 'Jack', 'Wilshere', 'Jack Wilshere', 'Midfielder', 'Attacking Midfielder', 'Left', 10, 68, 172, '1992-01-01', '2008-07-01', 'England', NULL, NULL, 0),
(23, 21, 20467, 'Theo', 'Walcott', 'Theo Walcott', 'Midfielder', 'Winger', 'Right', 14, 68, 176, '1989-03-16', '2006-01-20', 'England', NULL, NULL, 0),
(24, 21, 81880, 'Alex', 'Oxlade-Chamberlain', 'Alex Oxlade-Chamberlain', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 15, 70, 180, '1993-08-15', '2011-08-08', 'England', NULL, NULL, 0),
(25, 21, 41792, 'Aaron', 'Ramsey', 'Aaron Ramsey', 'Midfielder', 'Central Midfielder', 'Centre', 16, 70, 177, '1990-12-26', '2008-07-01', 'Wales', NULL, NULL, 0),
(26, 21, 19524, 'Santiago', 'Cazorla', 'Santiago Cazorla', 'Midfielder', 'Winger', 'Left/Right', 19, 66, 168, '1984-12-13', '2012-08-07', 'Spain', NULL, NULL, 0),
(27, 21, 56864, 'Francis', 'Coquelin', 'Francis Coquelin', 'Midfielder', 'Defensive Midfielder', 'Centre', 22, 73, 178, '1991-05-13', '2008-09-01', 'France', NULL, NULL, 0),
(28, 21, 56861, 'Emmanuel', 'Frimpong', 'Emmanuel Frimpong', 'Midfielder', 'Defensive Midfielder', 'Centre', 26, 67, 180, '1992-01-10', '2008-08-01', 'Ghana', NULL, NULL, 0),
(29, 21, 92372, 'Ryo', 'Miyaichi', 'Ryo Miyaichi', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 31, 71, 183, '1992-12-14', '2011-01-01', 'Japan', NULL, NULL, 0),
(30, 21, 88497, 'Chukwuemeka', 'Aneke', 'Chukwuemeka Aneke', 'Midfielder', 'Attacking Midfielder', 'Centre', 34, 83, 191, '1993-07-03', '2010-09-01', 'England', NULL, NULL, 0),
(31, 21, 111571, 'Thomas', 'Eisfeld', 'Thomas Eisfeld', 'Midfielder', 'Attacking Midfielder', 'Centre', 46, 65, 177, '1993-01-18', '2012-01-31', 'Germany', NULL, NULL, 0),
(32, 21, 133798, 'Serge', 'Gnabry', 'Serge Gnabry', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 47, 73, 173, '1995-07-14', '2012-09-01', 'Germany', NULL, NULL, 0),
(33, 21, 17733, 'Lukas', 'Podolski', 'Lukas Podolski', 'Forward', 'Striker', 'Left/Centre', 9, 83, 182, '1985-06-04', '2012-07-01', 'Germany', NULL, NULL, 0),
(34, 21, 44346, 'Olivier', 'Giroud', 'Olivier Giroud', 'Forward', 'Striker', 'Centre', 12, 88, 192, '1986-09-30', '2012-07-01', 'France', NULL, NULL, 0),
(35, 21, 43274, 'Gervais', 'Yao Kouassi', 'Gervinho', 'Forward', 'Second Striker', 'Left/Centre', 27, 66, 179, '1987-05-27', '2011-07-11', 'Côte d''Ivoire', NULL, NULL, 0),
(36, 21, 15675, 'Marouane', 'Chamakh', 'Marouane Chamakh', 'Forward', 'Striker', 'Centre', 29, 70, 185, '1984-01-10', '2010-07-01', 'Morocco', NULL, NULL, 0),
(37, 21, 36968, 'Chu-Young', 'Park', 'Park Chu-Young', 'Forward', 'Striker', 'Left/Centre/Right', 30, 70, 183, '1985-07-10', '2011-08-31', 'South Korea', NULL, NULL, 0),
(38, 21, 88498, 'Benik', 'Afobe', 'Benik Afobe', 'Forward', 'Striker', 'Centre', 33, 79, 183, '1993-02-12', '2010-09-01', 'England', NULL, NULL, 0),
(39, 21, 147692, 'Zak', 'Ansah', 'Zak Ansah', 'Forward', 'Striker', 'Centre', 39, 73, 179, '1994-05-04', '2012-12-03', 'England', NULL, NULL, 0),
(40, 21, 27697, 'Nicklas', 'Bendtner', 'Nicklas Bendtner', 'Forward', 'Second Striker', 'Centre/Right', 52, 76, 190, '1988-01-16', '2005-07-01', 'Denmark', NULL, NULL, 0),
(41, 21, 147675, 'Chuba', 'Akpom', 'Chuba Akpom', 'Forward', 'Striker', 'Centre', 67, 70, 183, '1995-10-09', '2012-12-03', 'England', NULL, NULL, 0),
(42, 8, 1822, 'Shay', 'Given', 'Shay Given', 'Goalkeeper', 'Goalkeeper', NULL, 1, 84, 183, '1976-04-20', '2011-07-19', 'Republic of Ireland', NULL, NULL, 0),
(43, 8, 41705, 'Brad', 'Guzan', 'Brad Guzan', 'Goalkeeper', 'Goalkeeper', NULL, 22, 94, 193, '1984-09-09', '2008-08-01', 'USA', NULL, NULL, 0),
(44, 8, 99168, 'Benjamin', 'Siegrist', 'Benjamin Siegrist', 'Goalkeeper', 'Goalkeeper', NULL, 39, 85, 194, '1992-01-31', '2012-08-01', 'Switzerland', NULL, NULL, 0),
(45, 8, 80502, 'Jores', 'Okore', 'Jores Okore', 'Defender', 'Central Defender', 'Centre', NULL, 80, 183, '1992-08-11', '2013-06-13', 'Denmark', NULL, NULL, 0),
(46, 8, 15405, 'Alan', 'Hutton', 'Alan Hutton', 'Defender', 'Full Back', 'Right', 2, 72, 185, '1984-11-30', '2011-08-31', 'Scotland', NULL, NULL, 0),
(47, 8, 21095, 'Ron', 'Vlaar', 'Ron Vlaar', 'Defender', 'Central Defender', 'Centre', 4, 80, 189, '1985-02-16', '2012-08-01', 'Netherlands', NULL, NULL, 0),
(48, 8, 58845, 'Ciaran', 'Clark', 'Ciaran Clark', 'Defender', 'Central Defender', 'Centre', 6, 76, 188, '1989-09-26', '2008-12-03', 'Republic of Ireland', NULL, NULL, 0),
(49, 8, 56981, 'Joe', 'Bennett', 'Joe Bennett', 'Defender', 'Full Back', 'Left', 27, 74, 183, '1990-03-28', '2012-08-31', 'England', NULL, NULL, 0),
(50, 8, 63426, 'Enda', 'Stevens', 'Enda Stevens', 'Defender', 'Full Back', 'Left', 29, 78, 183, '1990-07-09', '2012-01-03', 'Republic of Ireland', NULL, NULL, 0),
(51, 8, 52477, 'Nathan', 'Baker', 'Nathan Baker', 'Defender', 'Central Defender', 'Centre', 32, 75, 188, '1991-04-23', '2008-07-01', 'England', NULL, NULL, 0),
(52, 8, 68983, 'Matthew', 'Lowton', 'Matthew Lowton', 'Defender', 'Full Back', 'Right', 34, 78, 180, '1989-06-09', '2012-07-06', 'England', NULL, NULL, 0),
(53, 8, 74297, 'Leandro', 'Bacuna', 'Leandro Bacuna', 'Midfielder', 'Attacking Midfielder', 'Centre/Right', NULL, 77, 187, '1991-08-21', '2013-06-13', 'Netherlands', NULL, NULL, 0),
(54, 8, 20481, 'Stephen', 'Ireland', 'Stephen Ireland', 'Midfielder', 'Central Midfielder', 'Centre', 7, 68, 176, '1986-08-22', '2010-08-18', 'Republic of Ireland', NULL, NULL, 0),
(55, 8, 39242, 'Karim', 'El Ahmadi', 'Karim El Ahmadi', 'Midfielder', 'Defensive Midfielder', 'Centre', 8, 78, 185, '1985-01-27', '2012-07-01', 'Morocco', NULL, NULL, 0),
(56, 8, 18737, 'Charles', 'N''Zogbia', 'Charles N''Zogbia', 'Midfielder', 'Winger', 'Left', 10, 70, 170, '1986-05-28', '2011-07-29', 'France', NULL, NULL, 0),
(57, 8, 51938, 'Marc', 'Albrighton', 'Marc Albrighton', 'Midfielder', 'Central Midfielder', 'Centre', 12, 67, 175, '1989-11-18', '2009-02-01', 'England', NULL, NULL, 0),
(58, 8, 60551, 'Ashley', 'Westwood', 'Ashley Westwood', 'Midfielder', 'Central Midfielder', 'Centre', 15, 67, 175, '1990-04-01', '2012-08-31', 'England', NULL, NULL, 0),
(59, 8, 41823, 'Fabian', 'Delph', 'Fabian Delph', 'Midfielder', 'Central Midfielder', 'Left/Centre', 16, 60, 174, '1989-11-21', '2009-08-05', 'England', NULL, NULL, 0),
(60, 8, 90255, 'Yacouba', 'Sylla', 'Yacouba Sylla', 'Midfielder', 'Central Midfielder', 'Centre', 18, 80, 184, '1990-11-29', '2013-01-31', 'Senegal', NULL, NULL, 0),
(61, 8, 59013, 'Barry', 'Bannan', 'Barry Bannan', 'Midfielder', 'Central Midfielder', 'Left/Centre', 25, 60, 170, '1989-12-01', '2008-12-04', 'Scotland', NULL, NULL, 0),
(62, 8, 49493, 'Chris', 'Herd', 'Chris Herd', 'Midfielder', 'Central Midfielder', 'Centre/Right', 31, 76, 173, '1989-04-04', '2007-07-01', 'Australia', NULL, NULL, 0),
(63, 8, 90590, 'Daniel', 'Johnson', 'Daniel Johnson', 'Midfielder', 'Attacking Midfielder', 'Left/Centre', 35, 67, 174, '1992-10-08', '2010-10-27', 'Jamaica', NULL, NULL, 0),
(64, 8, 77800, 'Gary', 'Gardner', 'Gary Gardner', 'Midfielder', 'Central Midfielder', 'Centre', 38, 82, 188, '1992-06-26', '2009-10-20', 'England', NULL, NULL, 0),
(65, 8, 114042, 'Samir', 'Carruthers', 'Samir Carruthers', 'Midfielder', 'Attacking Midfielder', 'Centre', 40, 70, 173, '1993-04-04', '2012-03-23', 'Republic of Ireland', NULL, NULL, 0),
(66, 8, 83572, 'Nicklas', 'Helenius', 'Nicklas Helenius', 'Forward', 'Striker', 'Centre', NULL, 80, 195, '1991-05-08', '2013-06-18', 'Denmark', NULL, NULL, 0),
(67, 8, 10738, 'Darren', 'Bent', 'Darren Bent', 'Forward', 'Striker', 'Centre', 9, 73, 180, '1984-02-06', '2011-01-18', 'England', NULL, NULL, 0),
(68, 8, 27450, 'Gabriel', 'Agbonlahor', 'Gabriel Agbonlahor', 'Forward', 'Striker', 'Centre', 11, 76, 178, '1986-10-13', '2004-07-01', 'England', NULL, NULL, 0),
(69, 8, 54861, 'Christian', 'Benteke', 'Christian Benteke', 'Forward', 'Striker', 'Centre', 20, 83, 190, '1990-12-03', '2012-08-31', 'Belgium', NULL, NULL, 0),
(70, 8, 56206, 'Jordan', 'Bowery', 'Jordan Bowery', 'Forward', 'Striker', 'Centre', 21, 76, 185, '1991-07-02', '2012-08-31', 'England', NULL, NULL, 0),
(71, 8, 51484, 'Nathan', 'Delfouneso', 'Nathan Delfouneso', 'Forward', 'Striker', 'Centre', 23, 69, 175, '1991-02-02', '2008-07-01', 'England', NULL, NULL, 0),
(72, 8, 80979, 'Andreas', 'Weimann', 'Andreas Weimann', 'Forward', 'Striker', 'Centre', 26, 76, 188, '1991-08-05', '2009-06-01', 'Austria', NULL, NULL, 0),
(73, 8, 106602, 'Graham', 'Burke', 'Graham Burke', 'Forward', 'Striker', 'Centre', 36, 75, 180, '1993-09-21', '2011-07-01', 'Republic of Ireland', NULL, NULL, 0),
(74, 13, 15144, 'David', 'Marshall', 'David Marshall', 'Goalkeeper', 'Goalkeeper', NULL, 1, 83, 191, '1985-03-05', '2009-06-01', 'Scotland', NULL, NULL, 0),
(75, 13, 52476, 'Elliot', 'Parish', 'Elliot Parish', 'Goalkeeper', 'Goalkeeper', NULL, 29, 83, 188, '1990-05-20', '2011-09-23', 'England', NULL, NULL, 0),
(76, 13, 19686, 'Joe', 'Lewis', 'Joe Lewis', 'Goalkeeper', 'Goalkeeper', NULL, 32, 85, 198, '1987-10-06', '2012-07-01', 'England', NULL, NULL, 0),
(77, 13, 2368, 'Kevin', 'McNaughton', 'Kevin McNaughton', 'Defender', 'Full Back', 'Left/Centre/Right', 2, 66, 178, '1982-08-28', '2002-08-01', 'Scotland', NULL, NULL, 0),
(78, 13, 18145, 'Andrew', 'Taylor', 'Andrew Taylor', 'Defender', 'Full Back', 'Left', 3, 69, 178, '1986-08-01', '2011-07-01', 'England', NULL, NULL, 0),
(79, 13, 7638, 'Mark', 'Hudson', 'Mark Hudson', 'Defender', 'Central Defender', 'Centre', 5, 78, 185, '1982-03-30', '2009-07-02', 'England', NULL, NULL, 0),
(80, 13, 36956, 'Ben', 'Turner', 'Ben Turner', 'Defender', 'Central Defender', 'Centre', 6, 91, 193, '1988-08-21', '2011-08-31', 'England', NULL, NULL, 0),
(81, 13, 27698, 'Matthew', 'Connolly', 'Matthew Connolly', 'Defender', 'Central Defender', 'Left/Centre/Right', 12, 84, 188, '1987-09-24', '2012-08-22', 'England', NULL, NULL, 0),
(82, 13, 124989, 'Ben', 'Nugent', 'Ben Nugent', 'Defender', 'Central Defender', 'Centre', 31, 83, 186, '1993-11-28', '2012-08-01', 'Wales', NULL, NULL, 0),
(83, 13, 104074, 'Adedeji', 'Oshilaja', 'Adedeji Oshilaja', 'Defender', 'Central Defender', 'Left/Centre/Right', 35, 75, 181, '1993-02-26', '2011-08-01', 'England', NULL, NULL, 0),
(84, 13, 106419, 'Luke', 'Coulson', 'Luke Coulson', 'Defender', 'Full Back', 'Left', 40, NULL, NULL, '1994-03-06', '2013-01-04', 'England', NULL, NULL, 0),
(85, 13, 67242, 'Filip', 'Kiss', 'Filip Kiss', 'Midfielder', 'Defensive Midfielder', 'Centre/Right', 4, 75, 186, '1990-09-13', '2011-07-21', 'Slovakia', NULL, NULL, 0),
(86, 13, 15282, 'Peter', 'Whittingham', 'Peter Whittingham', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 7, 63, 178, '1984-09-08', '2007-01-11', 'England', NULL, NULL, 0),
(87, 13, 44925, 'Don', 'Cowie', 'Don Cowie', 'Midfielder', 'Central Midfielder', 'Centre/Right', 8, 73, 180, '1983-02-15', '2011-07-01', 'Scotland', NULL, NULL, 0),
(88, 13, 37621, 'Craig', 'Conway', 'Craig Conway', 'Midfielder', 'Winger', 'Left', 11, 67, 171, '1985-05-02', '2011-06-23', 'Scotland', NULL, NULL, 0),
(89, 13, 77877, 'Bo-Kyung', 'Kim', 'Kim Bo-Kyung', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 13, 70, 178, '1989-10-06', '2012-07-27', 'South Korea', NULL, NULL, 0),
(90, 13, 55913, 'Craig', 'Noone', 'Craig Noone', 'Midfielder', 'Winger', 'Left/Right', 16, 69, 177, '1987-11-17', '2012-08-31', 'England', NULL, NULL, 0),
(91, 13, 49845, 'Aron', 'Gunnarsson', 'Aron Gunnarsson', 'Midfielder', 'Defensive Midfielder', 'Centre', 17, 70, 177, '1989-09-22', '2011-07-09', 'Iceland', NULL, NULL, 0),
(92, 13, 49438, 'Jordon', 'Mutch', 'Jordon Mutch', 'Midfielder', 'Central Midfielder', 'Left/Centre', 18, 65, 175, '1991-12-02', '2012-06-22', 'England', NULL, NULL, 0),
(93, 13, 78911, 'Kadeem', 'Harris', 'Kadeem Harris', 'Midfielder', 'Winger', 'Left/Right', 19, 67, 175, '1993-05-29', '2012-01-30', 'England', NULL, NULL, 0),
(94, 13, 104073, 'Joe', 'Ralls', 'Joe Ralls', 'Midfielder', 'Central Midfielder', 'Left/Centre', 21, 70, 183, '1993-10-13', '2011-08-01', 'England', NULL, NULL, 0),
(95, 13, 4374, 'Simon', 'Lappin', 'Simon Lappin', 'Midfielder', 'Winger', 'Left', 24, 60, 180, '1983-01-25', '2013-02-05', 'Scotland', NULL, NULL, 0),
(96, 13, 797, 'Stephen', 'McPhail', 'Stephen McPhail', 'Midfielder', NULL, NULL, 37, 76, 173, '1979-12-09', '2006-08-01', 'Republic of Ireland', NULL, NULL, 0),
(97, 13, 124993, 'Tommy', 'O''Sullivan', 'Tommy O''Sullivan', 'Midfielder', 'Central Midfielder', 'Centre', 38, 71, 176, '1995-01-18', '2012-08-01', 'Wales', NULL, NULL, 0),
(98, 13, 65972, 'Declan', 'John', 'Declan John', 'Midfielder', 'Winger', 'Left', 42, 64, 173, '1991-05-14', '2012-08-01', 'Wales', NULL, NULL, 0),
(99, 13, 149923, 'Ibrahim', 'Mansaray', 'Ibrahim Mansaray', 'Midfielder', NULL, NULL, 44, NULL, NULL, NULL, '2013-01-01', 'Denmark', NULL, NULL, 0),
(100, 13, 110697, 'Theo', 'Wharton', 'Theo Wharton', 'Midfielder', 'Central Midfielder', 'Centre', 45, NULL, NULL, '1994-11-15', '2012-01-01', 'Wales', NULL, NULL, 0),
(101, 13, 124992, 'Jessie', 'Darko', 'Jessie Darko', 'Forward', 'Striker', 'Centre', NULL, NULL, 188, '1993-03-13', '2012-07-01', 'Austria', NULL, NULL, 0),
(102, 13, 95041, 'Alan', 'Curtis', 'Alan Curtis', 'Forward', NULL, NULL, NULL, NULL, NULL, '1954-04-16', '1987-07-01', 'Wales', NULL, NULL, 0),
(103, 13, 67412, 'Etien', 'Velikonja', 'Etien Velikonja', 'Forward', 'Striker', 'Centre', 9, 72, 178, '1988-12-26', '2012-07-25', 'Slovenia', NULL, NULL, 0),
(104, 13, 2019, 'Tommy', 'Smith', 'Tommy Smith', 'Forward', 'Second Striker', 'Left/Centre/Right', 14, 71, 175, '1980-05-22', '2012-08-24', 'England', NULL, NULL, 0),
(105, 13, 49207, 'Rudy', 'Gestede', 'Rudy Gestede', 'Forward', 'Striker', 'Centre', 15, 86, 193, '1988-10-10', '2011-07-25', 'France', NULL, NULL, 0),
(106, 13, 73442, 'Joe', 'Mason', 'Joe Mason', 'Forward', 'Second Striker', 'Left/Centre', 20, 73, 178, '1991-05-13', '2011-07-11', 'Republic of Ireland', NULL, NULL, 0),
(107, 13, 3731, 'Heidar', 'Helguson', 'Heidar Helguson', 'Forward', 'Striker', 'Centre', 22, 78, 178, '1977-08-22', '2012-08-02', 'Iceland', NULL, NULL, 0),
(108, 13, 20441, 'Nicky', 'Maynard', 'Nicky Maynard', 'Forward', 'Striker', 'Centre', 23, 70, 180, '1986-12-11', '2012-08-31', 'England', NULL, NULL, 0),
(109, 13, 28541, 'Fraizer', 'Campbell', 'Fraizer Campbell', 'Forward', 'Striker', 'Centre', 27, 82, 172, '1987-09-13', '2013-01-21', 'England', NULL, NULL, 0),
(110, 13, 131403, 'Rhys', 'Healey', 'Rhys Healey', 'Forward', 'Striker', 'Centre', 28, 72, 180, '1994-06-12', '2013-01-29', 'Wales', NULL, NULL, 0),
(111, 13, 74967, 'Nathaniel', 'Jarvis', 'Nathaniel Jarvis', 'Forward', NULL, NULL, 33, NULL, NULL, '1991-10-20', '2009-08-01', 'England', NULL, NULL, 0),
(112, 13, 1231, 'Craig', 'Bellamy', 'Craig Bellamy', 'Forward', 'Striker', 'Left/Centre/Right', 39, 68, 175, '1979-07-13', '2012-08-10', 'Wales', NULL, NULL, 0),
(113, 13, 124994, 'Gethin', 'Hill', 'Gethin Hill', 'Forward', 'Striker', 'Centre', 43, 750, 183, '1995-01-18', '2012-08-01', 'Wales', NULL, NULL, 0),
(114, 1, 68250, 'Matej', 'Delac', 'Matej Delac', 'Goalkeeper', 'Goalkeeper', NULL, NULL, 80, 190, '1992-08-20', '2010-07-01', 'Croatia', NULL, NULL, 0),
(115, 1, 11334, 'Petr', 'Cech', 'Petr Cech', 'Goalkeeper', 'Goalkeeper', NULL, 1, 92, 196, '1982-05-20', '2004-06-01', 'Czech Republic', NULL, NULL, 0),
(116, 1, 95744, 'Sam', 'Walker', 'Sam Walker', 'Goalkeeper', 'Goalkeeper', NULL, 43, 78, 199, '1991-10-02', '2010-07-01', 'England', NULL, NULL, 0),
(117, 1, 108132, 'Jamal', 'Blackman', 'Jamal Blackman', 'Goalkeeper', 'Goalkeeper', NULL, 46, 82, 196, '1993-10-27', '2011-10-29', 'England', NULL, NULL, 0),
(118, 1, 135363, 'Andreas', 'Christensen', 'Andreas Christensen', 'Defender', NULL, NULL, NULL, NULL, 188, '1996-04-10', '2013-05-19', 'Denmark', NULL, NULL, 0),
(119, 1, 41135, 'Branislav', 'Ivanovic', 'Branislav Ivanovic', 'Defender', 'Central Defender', 'Centre/Right', 2, 84, 188, '1984-02-22', '2008-01-16', 'Serbia', NULL, NULL, 0),
(120, 1, 3785, 'Ashley', 'Cole', 'Ashley Cole', 'Defender', 'Full Back', 'Left', 3, 66, 176, '1980-12-20', '2006-08-31', 'England', NULL, NULL, 0),
(121, 1, 41270, 'David', 'Luiz Moreira Marinho', 'David Luiz', 'Defender', 'Central Defender', 'Centre', 4, 84, 188, '1987-04-22', '2011-01-31', 'Brazil', NULL, NULL, 0),
(122, 1, 19419, 'Gary', 'Cahill', 'Gary Cahill', 'Defender', 'Central Defender', 'Centre', 24, 71, 188, '1985-12-19', '2012-01-16', 'England', NULL, NULL, 0),
(123, 1, 1718, 'John', 'Terry', 'John Terry', 'Defender', 'Central Defender', 'Centre', 26, 90, 187, '1980-12-07', '1997-08-01', 'England', NULL, NULL, 0),
(124, 1, 42428, 'Sam', 'Hutchinson', 'Sam Hutchinson', 'Defender', 'Full Back', 'Right', 27, 73, 182, '1989-08-03', '2006-07-01', 'England', NULL, NULL, 0),
(125, 1, 41328, 'César', 'Azpilicueta', 'César Azpilicueta', 'Defender', 'Full Back', 'Right', 28, 70, 178, '1989-08-28', '2012-08-24', 'Spain', NULL, NULL, 0),
(126, 1, 40146, 'Ryan', 'Bertrand', 'Ryan Bertrand', 'Defender', 'Full Back', 'Left', 34, 85, 179, '1989-08-05', '2005-08-01', 'England', NULL, NULL, 0),
(127, 1, 74230, 'Patrick', 'van Aanholt', 'Patrick van Aanholt', 'Defender', 'Full Back', 'Left', 38, 74, 177, '1990-08-29', '2007-06-01', 'Netherlands', NULL, NULL, 0),
(128, 1, 89085, 'Nathaniel', 'Chalobah', 'Nathaniel Chalobah', 'Defender', 'Central Defender', 'Centre/Right', 45, 75, 185, '1994-12-12', '2010-09-01', 'England', NULL, NULL, 0),
(129, 1, 89082, 'Billy', 'Clifford', 'Billy Clifford', 'Defender', 'Full Back', 'Right', 47, 65, 170, '1992-10-18', '2010-09-01', 'England', NULL, NULL, 0),
(130, 1, 120159, 'Rohan', 'Ince', 'Rohan Ince', 'Defender', 'Central Defender', 'Centre', 51, 80, 191, '1992-11-08', '2011-07-01', 'England', NULL, NULL, 0),
(131, 1, 126184, 'Nathan', 'Aké', 'Nathan Aké', 'Defender', 'Central Defender', 'Left/Centre', 57, 71, 180, '1995-02-18', '2012-12-19', 'Netherlands', NULL, NULL, 0),
(132, 1, 97471, 'Thorgan', 'Hazard', 'Thorgan Hazard', 'Midfielder', 'Attacking Midfielder', 'Centre', NULL, 70, 172, '1993-03-29', '2012-07-25', 'Belgium', NULL, NULL, 0),
(133, 1, 126187, 'Ruben', 'Loftus-Cheek', 'Ruben Loftus-Cheek', 'Midfielder', 'Central Midfielder', 'Centre', NULL, NULL, NULL, '1996-01-23', '2013-05-20', 'England', NULL, NULL, 0),
(134, 1, 8442, 'Michael', 'Essien', 'Michael Essien', 'Midfielder', 'Defensive Midfielder', 'Centre/Right', 5, 78, 183, '1982-12-03', '2005-08-19', 'Ghana', NULL, NULL, 0),
(135, 1, 78056, 'Oriol', 'Romeu', 'Oriol Romeu', 'Midfielder', 'Defensive Midfielder', 'Centre', 6, 79, 182, '1991-09-24', '2011-08-04', 'Spain', NULL, NULL, 0),
(136, 1, 53392, 'Ramires', 'Santos do Nascimento', 'Ramires', 'Midfielder', 'Central Midfielder', 'Left/Centre/Right', 7, 73, 180, '1987-03-24', '2010-08-13', 'Brazil', NULL, NULL, 0),
(137, 1, 2051, 'Frank', 'Lampard', 'Frank Lampard', 'Midfielder', 'Central Midfielder', 'Centre', 8, 88, 184, '1978-06-20', '2001-06-14', 'England', NULL, NULL, 0),
(138, 1, 61262, 'Oscar', 'dos Santos Emboada Junior', 'Oscar', 'Midfielder', 'Attacking Midfielder', 'Centre', 11, 66, 179, '1991-09-09', '2012-07-25', 'Brazil', NULL, NULL, 0),
(139, 1, 28495, 'John Obi', 'Mikel', 'John Obi Mikel', 'Midfielder', 'Defensive Midfielder', 'Centre', 12, 86, 188, '1987-04-22', '2006-06-03', 'Nigeria', NULL, NULL, 0),
(140, 1, 88821, 'Joshua', 'McEachran', 'Joshua McEachran', 'Midfielder', 'Central Midfielder', 'Centre', 20, 66, 178, '1993-03-01', '2010-09-01', 'England', NULL, NULL, 0),
(141, 1, 41450, 'Marko', 'Marin', 'Marko Marin', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 21, 64, 170, '1989-03-13', '2012-07-01', 'Germany', NULL, NULL, 0),
(142, 1, 111565, 'Gustavo Lucas', 'Domingues Piazón', 'Lucas Piazón', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 35, 75, 182, '1994-01-20', '2012-01-01', 'Brazil', NULL, NULL, 0),
(143, 1, 14402, 'Fernando', 'Torres', 'Fernando Torres', 'Forward', 'Striker', 'Centre', 9, 70, 183, '1984-03-20', '2011-01-31', 'Spain', NULL, NULL, 0),
(144, 1, 43670, 'Juan', 'Mata', 'Juan Mata', 'Forward', 'Attacking Midfielder', 'Left/Centre/Right', 10, 63, 170, '1988-04-28', '2011-08-24', 'Spain', NULL, NULL, 0),
(145, 1, 49013, 'Victor', 'Moses', 'Victor Moses', 'Forward', 'Second Striker', 'Left/Right', 13, 75, 177, '1990-12-12', '2012-08-24', 'Nigeria', NULL, NULL, 0),
(146, 1, 42786, 'Eden', 'Hazard', 'Eden Hazard', 'Forward', 'Second Striker', 'Left/Centre/Right', 17, 69, 170, '1991-01-07', '2012-07-01', 'Belgium', NULL, NULL, 0),
(147, 15, 66749, 'Romelu', 'Lukaku', 'Romelu Lukaku', 'Forward', 'Striker', 'Centre', 20, 94, 190, '1993-05-13', '2012-08-10', 'Belgium', NULL, NULL, 0),
(148, 1, 47412, 'Demba', 'Ba', 'Demba Ba', 'Forward', 'Striker', 'Centre', 29, 84, 189, '1985-05-25', '2013-01-04', 'Senegal', NULL, NULL, 0),
(149, 20, 11554, 'Julian', 'Speroni', 'Julian Speroni', 'Goalkeeper', 'Goalkeeper', NULL, 1, 87, 186, '1979-05-18', '2002-08-01', 'Argentina', NULL, NULL, 0),
(150, 20, 16230, 'Lewis', 'Price', 'Lewis Price', 'Goalkeeper', 'Goalkeeper', NULL, 34, 84, 191, '1984-07-19', '2010-07-12', 'Wales', NULL, NULL, 0),
(151, 20, 110219, 'Ross', 'Fitzsimons', 'Ross Fitzsimons', 'Goalkeeper', 'Goalkeeper', NULL, 40, 75, 185, '1994-05-28', '2011-12-01', 'England', NULL, NULL, 0),
(152, 20, 114536, 'Ryan', 'Inniss', 'Ryan Inniss', 'Defender', NULL, NULL, NULL, NULL, NULL, '1995-06-05', '2012-04-01', 'England', NULL, NULL, 0),
(153, 20, 82404, 'Jack', 'Holland', 'Jack Holland', 'Defender', NULL, NULL, NULL, NULL, 191, '1992-03-01', '2010-03-26', 'England', NULL, NULL, 0),
(154, 20, 110701, 'Kadell', 'Daniel', 'Kadell Daniel', 'Defender', 'Full Back', 'Left', NULL, NULL, NULL, '1994-06-03', '2012-01-06', 'England', NULL, NULL, 0),
(155, 20, 111548, 'Michael', 'Chambers', 'Michael Chambers', 'Defender', NULL, NULL, NULL, NULL, NULL, NULL, '2012-03-31', 'England', NULL, NULL, 0),
(156, 20, 55494, 'Joel', 'Ward', 'Joel Ward', 'Defender', 'Full Back', 'Right', 2, 80, 180, '1989-10-29', '2012-05-29', 'England', NULL, NULL, 0),
(157, 20, 62023, 'Jonathan', 'Parr', 'Jonathan Parr', 'Defender', 'Full Back', 'Left', 4, 75, 182, '1988-10-21', '2011-07-19', 'Norway', NULL, NULL, 0),
(158, 20, 15274, 'Patrick', 'McCarthy', 'Patrick McCarthy', 'Defender', 'Central Defender', 'Centre', 5, 79, 185, '1983-05-31', '2008-06-01', 'Republic of Ireland', NULL, NULL, 0),
(159, 20, 36906, 'Darcy', 'Blake', 'Darcy Blake', 'Defender', 'Full Back', 'Centre/Right', 14, 68, 165, '1988-12-13', '2012-08-24', 'Wales', NULL, NULL, 0),
(160, 20, 19635, 'Dean', 'Moxey', 'Dean Moxey', 'Defender', 'Full Back', 'Left', 21, 70, 180, '1986-01-14', '2011-01-31', 'England', NULL, NULL, 0),
(161, 20, 88041, 'Matthew', 'Parsons', 'Matthew Parsons', 'Defender', 'Full Back', 'Left', 26, 74, 178, '1991-12-25', '2010-07-01', 'England', NULL, NULL, 0),
(162, 20, 7906, 'Damien', 'Delaney', 'Damien Delaney', 'Defender', 'Central Defender', 'Centre', 27, 89, 191, '1981-07-29', '2012-08-31', 'Republic of Ireland', NULL, NULL, 0),
(163, 20, 18146, 'Peter', 'Ramage', 'Peter Ramage', 'Defender', 'Central Defender', 'Left/Centre/Right', 28, 76, 185, '1983-11-22', '2012-08-07', 'England', NULL, NULL, 0),
(164, 20, 1059, 'Daniel', 'Gabbidon', 'Daniel Gabbidon', 'Defender', 'Central Defender', 'Centre', 33, 77, 178, '1979-08-08', '2012-09-19', 'Wales', NULL, NULL, 0),
(165, 20, 80276, 'Alex', 'Wynter', 'Alex Wynter', 'Defender', 'Central Defender', 'Centre', 36, 84, 183, '1993-09-16', '2010-01-01', 'England', NULL, NULL, 0),
(166, 20, 111625, 'Quade', 'Taylor', 'Quade Taylor', 'Defender', 'Central Defender', 'Centre', 41, 70, 191, '1993-12-11', '2011-07-01', 'England', NULL, NULL, 0),
(167, 20, 55452, 'Yannick', 'Bolasie', 'Yannick Bolasie', 'Midfielder', 'Winger', 'Left/Right', 7, 84, 188, '1989-05-24', '2012-08-24', 'DR Congo', NULL, NULL, 0),
(168, 20, 46967, 'Kagisho', 'Dikgacoi', 'Kagisho Dikgacoi', 'Midfielder', 'Central Midfielder', 'Centre', 8, 76, 180, '1984-11-24', '2011-07-05', 'South Africa', NULL, NULL, 0),
(169, 20, 19527, 'Owen', 'Garvan', 'Owen Garvan', 'Midfielder', 'Attacking Midfielder', 'Centre', 10, 67, 183, '1988-01-29', '2010-08-03', 'Republic of Ireland', NULL, NULL, 0),
(170, 20, 56982, 'Alex', 'Marrow', 'Alex Marrow', 'Midfielder', 'Central Midfielder', 'Centre', 12, 78, 178, '1990-01-12', '2010-08-20', 'England', NULL, NULL, 0),
(171, 20, 59115, 'Mile', 'Jedinak', 'Mile Jedinak', 'Midfielder', 'Central Midfielder', 'Centre', 15, 81, 189, '1984-08-03', '2011-07-11', 'Australia', NULL, NULL, 0),
(172, 20, 103100, 'Jonathan', 'Williams', 'Jonathan Williams', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 20, 60, 168, '1993-10-09', '2011-07-01', 'Wales', NULL, NULL, 0),
(173, 20, 53582, 'Stuart', 'O''Keefe', 'Stuart O''Keefe', 'Midfielder', 'Central Midfielder', 'Centre', 22, 64, 173, '1991-03-04', '2010-08-18', 'England', NULL, NULL, 0),
(174, 20, 110700, 'Kyle', 'De Silva', 'Kyle De Silva', 'Midfielder', 'Attacking Midfielder', 'Centre', 25, 61, 170, '1993-11-29', '2012-01-06', 'England', NULL, NULL, 0),
(175, 20, 46582, 'André', 'Moritz', 'André Moritz', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 30, 83, 187, '1986-08-06', '2012-08-24', 'Brazil', NULL, NULL, 0),
(176, 20, 130103, 'Hiram', 'Boateng', 'Hiram Boateng', 'Midfielder', 'Central Midfielder', 'Centre', 37, NULL, NULL, '1996-01-08', '2013-01-01', 'England', NULL, NULL, 0),
(177, 20, 107088, 'Jason', 'Banton', 'Jason Banton', 'Midfielder', NULL, NULL, 38, NULL, 183, '1992-12-15', '2013-01-05', 'England', NULL, NULL, 0),
(178, 20, 17955, 'Stephen', 'Dobbie', 'Stephen Dobbie', 'Forward', NULL, NULL, 11, 71, 178, '1982-12-05', '2013-01-31', 'Scotland', NULL, NULL, 0),
(179, 20, 20529, 'Glenn', 'Murray', 'Glenn Murray', 'Forward', 'Striker', 'Centre', 17, 80, 183, '1983-09-25', '2011-05-25', 'England', NULL, NULL, 0),
(180, 20, 1409, 'Aaron', 'Wilbraham', 'Aaron Wilbraham', 'Forward', 'Striker', 'Centre', 18, 72, 191, '1979-10-21', '2012-07-04', 'England', NULL, NULL, 0),
(181, 20, 10946, 'Jermaine', 'Easter', 'Jermaine Easter', 'Forward', NULL, NULL, 19, 77, 175, '1982-01-15', '2011-01-14', 'Wales', NULL, NULL, 0),
(182, 20, 58276, 'Kwesi', 'Appiah', 'Kwesi Appiah', 'Forward', 'Striker', 'Centre', 24, 80, 181, '1990-08-12', '2012-01-31', 'England', NULL, NULL, 0),
(183, 20, 96992, 'Ibra', 'Sekajja', 'Ibra Sekajja', 'Forward', 'Striker', 'Centre', 29, 70, 180, '1992-10-31', '2011-04-30', 'England', NULL, NULL, 0),
(184, 9, 15337, 'Tim', 'Howard', 'Tim Howard', 'Goalkeeper', 'Goalkeeper', NULL, 24, 88, 187, '1979-03-06', '2006-07-04', 'USA', NULL, NULL, 0),
(185, 9, 94064, 'Mason', 'Springthorpe', 'Mason Springthorpe', 'Goalkeeper', NULL, NULL, 46, 72, 188, '1994-06-22', '2013-02-01', 'England', NULL, NULL, 0),
(186, 9, 9007, 'Tony', 'Hibbert', 'Tony Hibbert', 'Defender', 'Full Back', 'Right', 2, 71, 175, '1981-02-20', '2000-08-01', 'England', NULL, NULL, 0),
(187, 9, 12745, 'Leighton', 'Baines', 'Leighton Baines', 'Defender', 'Full Back', 'Left', 3, 74, 170, '1984-12-11', '2007-08-07', 'England', NULL, NULL, 0),
(188, 9, 10466, 'Johnny', 'Heitinga', 'Johnny Heitinga', 'Defender', 'Central Defender', 'Centre/Right', 5, 69, 182, '1983-11-15', '2009-09-01', 'Netherlands', NULL, NULL, 0),
(189, 9, 7645, 'Phil', 'Jagielka', 'Phil Jagielka', 'Defender', 'Central Defender', 'Centre', 6, 83, 183, '1982-08-17', '2007-07-04', 'England', NULL, NULL, 0),
(190, 9, 77762, 'Bryan', 'Oviedo', 'Bryan Oviedo', 'Defender', 'Full Back', 'Left', 8, 69, 172, '1990-02-18', '2012-08-31', 'Costa Rica', NULL, NULL, 0),
(191, 9, 6219, 'Sylvain', 'Distin', 'Sylvain Distin', 'Defender', 'Central Defender', 'Left/Centre', 15, 87, 193, '1977-12-06', '2009-08-27', 'France', NULL, NULL, 0),
(192, 9, 59949, 'Seamus', 'Coleman', 'Seamus Coleman', 'Defender', 'Full Back', 'Right', 23, 67, 178, '1988-10-11', '2009-02-02', 'Republic of Ireland', NULL, NULL, 0),
(193, 9, 97299, 'John', 'Stones', 'John Stones', 'Defender', 'Full Back', 'Right', 26, 70, 188, '1994-05-28', '2013-01-31', 'England', NULL, NULL, 0),
(194, 9, 61933, 'Shane', 'Duffy', 'Shane Duffy', 'Defender', 'Central Defender', 'Centre', 34, 76, 193, '1992-01-01', '2008-06-01', 'Republic of Ireland', NULL, NULL, 0),
(195, 9, 149468, 'Tyias', 'Browning', 'Tyias Browning', 'Defender', 'Central Defender', 'Centre', 40, 76, 181, '1994-05-27', '2012-12-30', 'England', NULL, NULL, 0),
(196, 9, 80178, 'Jake', 'Bidwell', 'Jake Bidwell', 'Defender', 'Full Back', 'Left', 41, 70, 183, '1993-03-21', '2009-07-01', 'England', NULL, NULL, 0),
(197, 9, 80183, 'Luke', 'Garbutt', 'Luke Garbutt', 'Defender', 'Full Back', 'Left', 42, 73, 179, '1993-05-21', '2009-07-01', 'England', NULL, NULL, 0),
(198, 9, 27707, 'Darron', 'Gibson', 'Darron Gibson', 'Midfielder', 'Central Midfielder', 'Centre', 4, 90, 183, '1987-10-25', '2012-01-13', 'Republic of Ireland', NULL, NULL, 0),
(199, 9, 1821, 'Philip', 'Neville', 'Philip Neville', 'Midfielder', 'Defensive Midfielder', 'Centre', 18, 69, 180, '1977-01-21', '2005-08-04', 'England', NULL, NULL, 0),
(200, 9, 88894, 'Ross', 'Barkley', 'Ross Barkley', 'Midfielder', 'Central Midfielder', 'Left/Centre/Right', 20, 76, 189, '1993-12-05', '2010-09-01', 'England', NULL, NULL, 0),
(201, 9, 8378, 'Leon', 'Osman', 'Leon Osman', 'Midfielder', 'Attacking Midfielder', 'Centre/Right', 21, 67, 173, '1981-05-17', '2000-08-01', 'England', NULL, NULL, 0),
(202, 9, 7525, 'Steven', 'Pienaar', 'Steven Pienaar', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 22, 69, 173, '1982-03-17', '2012-07-31', 'South Africa', NULL, NULL, 0),
(203, 9, 41184, 'Marouane', 'Fellaini', 'Marouane Fellaini', 'Midfielder', 'Attacking Midfielder', 'Centre', 25, 85, 194, '1987-11-22', '2008-09-01', 'Belgium', NULL, NULL, 0),
(204, 9, 115596, 'Francisco Santos', 'da Silva Júnior', 'Francisco Júnior', 'Midfielder', 'Central Midfielder', 'Centre', 30, 65, 162, '1992-01-18', '2012-02-15', 'Portugal', NULL, NULL, 0),
(205, 9, 92476, 'Matthew', 'Kennedy', 'Matthew Kennedy', 'Midfielder', 'Winger', 'Left/Right', 31, 68, 175, '1994-11-01', '2012-08-31', 'Scotland', NULL, NULL, 0),
(206, 9, 62419, 'Nikica', 'Jelavic', 'Nikica Jelavic', 'Forward', 'Striker', 'Centre', 7, 84, 187, '1985-08-27', '2012-01-31', 'Croatia', NULL, NULL, 0),
(207, 9, 26901, 'Kevin', 'Mirallas', 'Kevin Mirallas', 'Forward', 'Striker', 'Centre', 11, 68, 179, '1987-10-05', '2012-08-19', 'Belgium', NULL, NULL, 0),
(208, 9, 18981, 'Steven', 'Naismith', 'Steven Naismith', 'Forward', 'Striker', 'Centre', 14, 72, 178, '1986-09-14', '2012-07-22', 'Scotland', NULL, NULL, 0),
(209, 9, 57835, 'Magaye', 'Gueye', 'Magaye Gueye', 'Forward', 'Striker', 'Centre', 19, 73, 179, '1990-07-06', '2010-07-01', 'Senegal', NULL, NULL, 0),
(210, 9, 60865, 'Apostolos', 'Vellios', 'Apostolos Vellios', 'Forward', 'Striker', 'Centre', 27, 79, 191, '1992-01-08', '2011-01-31', 'Greece', NULL, NULL, 0),
(211, 9, 28593, 'Victor', 'Anichebe', 'Victor Anichebe', 'Forward', 'Striker', 'Centre', 28, 80, 185, '1988-04-23', '2005-07-01', 'Nigeria', NULL, NULL, 0),
(212, 9, 80181, 'Conor', 'McAleny', 'Conor McAleny', 'Forward', 'Striker', 'Centre', 43, 66, 172, '1992-08-12', '2009-07-01', 'England', NULL, NULL, 0),
(213, 10, 15903, 'David', 'Stockdale', 'David Stockdale', 'Goalkeeper', 'Goalkeeper', NULL, 13, 84, 191, '1985-09-20', '2008-06-01', 'England', NULL, NULL, 0),
(214, 10, 88734, 'Neil', 'Etheridge', 'Neil Etheridge', 'Goalkeeper', 'Goalkeeper', NULL, 38, 73, 191, '1990-02-07', '2010-09-10', 'Philippines', NULL, NULL, 0),
(215, 10, 4098, 'John Arne', 'Riise', 'John Arne Riise', 'Defender', 'Full Back', 'Left', 3, 77, 185, '1980-09-24', '2011-07-13', 'Norway', NULL, NULL, 0),
(216, 10, 16854, 'Philippe', 'Senderos', 'Philippe Senderos', 'Defender', 'Central Defender', 'Centre', 4, 84, 190, '1985-02-14', '2010-06-08', 'Switzerland', NULL, NULL, 0),
(217, 10, 15284, 'Brede', 'Hangeland', 'Brede Hangeland', 'Defender', 'Central Defender', 'Centre', 5, 85, 195, '1981-06-20', '2008-01-18', 'Norway', NULL, NULL, 0),
(218, 10, 42425, 'Matthew', 'Briggs', 'Matthew Briggs', 'Defender', 'Full Back', 'Right', 17, 76, 184, '1991-03-06', '2006-07-01', 'England', NULL, NULL, 0),
(219, 10, 1869, 'Aaron', 'Hughes', 'Aaron Hughes', 'Defender', 'Central Defender', 'Centre', 18, 71, 183, '1979-11-08', '2007-06-27', 'Northern Ireland', NULL, NULL, 0),
(220, 10, 17160, 'Sascha', 'Riether', 'Sascha Riether', 'Defender', 'Full Back', 'Right', 27, 70, 174, '1983-03-23', '2012-07-06', 'Germany', NULL, NULL, 0),
(221, 10, 82412, 'Jack', 'Grimmer', 'Jack Grimmer', 'Defender', 'Central Defender', 'Centre/Right', 44, 82, 184, '1994-01-25', '2012-01-18', 'Scotland', NULL, NULL, 0),
(222, 10, 11735, 'Steve', 'Sidwell', 'Steve Sidwell', 'Midfielder', 'Central Midfielder', 'Centre', 7, 70, 178, '1982-12-14', '2011-01-07', 'England', NULL, NULL, 0),
(223, 10, 81025, 'Pajtim', 'Kasami', 'Pajtim Kasami', 'Midfielder', 'Central Midfielder', 'Left/Centre/Right', 8, 70, 187, '1992-06-02', '2011-07-25', 'Switzerland', NULL, NULL, 0),
(224, 10, 15073, 'Kieran', 'Richardson', 'Kieran Richardson', 'Midfielder', 'Central Midfielder', 'Left/Centre', 15, 71, 173, '1984-10-21', '2012-08-31', 'England', NULL, NULL, 0),
(225, 10, 1256, 'Damien', 'Duff', 'Damien Duff', 'Midfielder', 'Winger', 'Left', 16, 60, 178, '1979-03-02', '2009-08-18', 'Republic of Ireland', NULL, NULL, 0),
(226, 10, 105086, 'Kerim', 'Frei', 'Kerim Frei', 'Midfielder', 'Winger', 'Right', 21, 67, 172, '1993-11-19', '2011-07-01', 'Turkey', NULL, NULL, 0),
(227, 10, 18427, 'Ashkan', 'Dejagah', 'Ashkan Dejagah', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 24, 77, 181, '1986-07-05', '2012-08-31', 'Iran', NULL, NULL, 0),
(228, 10, 42518, 'Alexander', 'Kacaniklic', 'Alexander Kacaniklic', 'Midfielder', 'Winger', 'Left', 31, 66, 180, '1991-08-13', '2010-08-31', 'Sweden', NULL, NULL, 0),
(229, 10, 129761, 'Chris', 'David', 'Chris David', 'Midfielder', 'Attacking Midfielder', 'Centre', 32, 71, 170, '1993-03-06', '2013-01-04', 'Netherlands', NULL, NULL, 0),
(230, 10, 56069, 'Eyong', 'Enoh', 'Eyong Enoh', 'Midfielder', 'Defensive Midfielder', 'Centre', 33, 70, 174, '1986-03-23', '2013-01-31', 'Cameroon', NULL, NULL, 0),
(231, 10, 112063, 'Charles', 'Banya', 'Charles Banya', 'Midfielder', 'Winger', 'Left/Right', 42, 61, 170, '1993-09-18', '2012-11-22', 'England', NULL, NULL, 0),
(232, 10, 8595, 'Dimitar', 'Berbatov', 'Dimitar Berbatov', 'Forward', 'Striker', 'Centre', 9, 79, 188, '1981-01-30', '2012-08-31', 'Bulgaria', NULL, NULL, 0),
(233, 10, 37084, 'Bryan', 'Ruiz', 'Bryan Ruiz', 'Forward', 'Second Striker', 'Centre', 11, 78, 188, '1985-08-18', '2011-08-31', 'Costa Rica', NULL, NULL, 0),
(234, 10, 37334, 'Hugo', 'Rodallega', 'Hugo Rodallega', 'Forward', 'Striker', 'Centre', 20, 72, 181, '1985-07-25', '2012-07-12', 'Colombia', NULL, NULL, 0),
(235, 10, 107853, 'Marcello', 'Trotta', 'Marcello Trotta', 'Forward', 'Striker', 'Centre', 39, 82, 187, '1992-09-29', '2011-10-01', 'Italy', NULL, NULL, 0),
(236, 6, 11974, 'Eldin', 'Jakupovic', 'Eldin Jakupovic', 'Goalkeeper', 'Goalkeeper', NULL, 1, 78, 188, '1984-10-02', '2012-07-01', 'Switzerland', NULL, NULL, 0),
(237, 6, 49387, 'Mark', 'Oxley', 'Mark Oxley', 'Goalkeeper', 'Goalkeeper', NULL, 13, 73, 180, '1990-06-02', '2008-08-08', 'England', NULL, NULL, 0),
(238, 6, 103917, 'Joe', 'Cracknell', 'Joe Cracknell', 'Goalkeeper', 'Goalkeeper', NULL, 35, NULL, NULL, NULL, '2011-07-01', 'England', NULL, NULL, 0),
(239, 6, 15137, 'Liam', 'Rosenior', 'Liam Rosenior', 'Defender', NULL, NULL, 2, 72, 178, '1984-07-09', '2010-10-29', 'England', NULL, NULL, 0),
(240, 6, 2730, 'Andy', 'Dawson', 'Andy Dawson', 'Defender', 'Full Back', 'Left', 3, 71, 175, '1978-10-20', '2002-08-01', 'England', NULL, NULL, 0),
(241, 6, 43252, 'James', 'Chester', 'James Chester', 'Defender', NULL, NULL, 5, 70, 180, '1989-01-23', '2011-01-07', 'England', NULL, NULL, 0),
(242, 6, 19723, 'Jack', 'Hobbs', 'Jack Hobbs', 'Defender', NULL, NULL, 6, 86, 191, '1988-08-18', '2011-02-15', 'England', NULL, NULL, 0),
(243, 6, 61602, 'Corry', 'Evans', 'Corry Evans', 'Defender', NULL, NULL, 8, 82, 180, '1990-07-30', '2011-07-05', 'Northern Ireland', NULL, NULL, 0),
(244, 6, 17506, 'Paul', 'McShane', 'Paul McShane', 'Defender', 'Central Defender', 'Centre/Right', 15, 72, 183, '1986-01-06', '2009-08-31', 'Republic of Ireland', NULL, NULL, 0),
(245, 6, 80976, 'Joe', 'Dudgeon', 'Joe Dudgeon', 'Defender', NULL, NULL, 19, NULL, NULL, '1990-11-26', '2011-05-10', 'Northern Ireland', NULL, NULL, 0),
(246, 6, 14055, 'Abdoulaye', 'Faye', 'Abdoulaye Faye', 'Defender', NULL, NULL, 23, 87, 188, '1978-02-26', '2012-07-20', 'Senegal', NULL, NULL, 0),
(247, 6, 19687, 'Alex', 'Bruce', 'Alex Bruce', 'Defender', NULL, NULL, 28, 73, 180, '1984-09-28', '2012-07-29', 'Northern Ireland', NULL, NULL, 0),
(248, 6, 79994, 'Cameron', 'Stewart', 'Cameron Stewart', 'Midfielder', NULL, NULL, 7, NULL, NULL, '1991-04-08', '2010-11-25', 'England', NULL, NULL, 0),
(249, 6, 27522, 'Robert', 'Koren', 'Robert Koren', 'Midfielder', NULL, NULL, 10, 71, 173, '1980-09-20', '2010-08-13', 'Slovenia', NULL, NULL, 0),
(250, 6, 76357, 'Tom', 'Cairney', 'Tom Cairney', 'Midfielder', 'Central Midfielder', 'Centre', 14, 71, 175, '1991-01-20', '2009-08-23', 'Scotland', NULL, NULL, 0),
(251, 6, 18439, 'Seyi', 'Olofinjana', 'Seyi Olofinjana', 'Midfielder', 'Defensive Midfielder', 'Centre', 16, 75, 193, '1980-06-30', '2009-08-06', 'Nigeria', NULL, NULL, 0),
(252, 6, 20462, 'Stephen', 'Quinn', 'Stephen Quinn', 'Midfielder', NULL, NULL, 29, 60, 168, '1986-04-04', '2012-08-31', 'Republic of Ireland', NULL, NULL, 0),
(253, 6, 90517, 'Robert', 'Brady', 'Robbie Brady', 'Midfielder', NULL, NULL, 30, 71, 176, '1992-01-14', '2012-11-06', 'Republic of Ireland', NULL, NULL, 0),
(254, 6, 53371, 'David', 'Meyler', 'David Meyler', 'Midfielder', NULL, NULL, 31, 79, 188, '1989-05-29', '2012-11-08', 'Republic of Ireland', NULL, NULL, 0),
(255, 6, 156322, 'Dougie', 'Wilson', 'Dougie Wilson', 'Midfielder', NULL, NULL, 36, NULL, NULL, '1994-03-03', '2012-07-01', 'Northern Ireland', NULL, NULL, 0),
(256, 6, 28244, 'George', 'Boyd', 'George Boyd', 'Midfielder', NULL, NULL, 37, 79, 186, '1985-10-02', '2013-02-22', 'Scotland', NULL, NULL, 0),
(257, 6, 157652, 'Calaum', 'Jahraldo-Martin', 'Calaum Jahraldo-Martin', 'Forward', NULL, NULL, NULL, NULL, NULL, NULL, '2013-03-28', 'England', NULL, NULL, 0),
(258, 6, 10571, 'Aaron', 'McLean', 'Aaron McLean', 'Forward', NULL, NULL, 9, 77, 171, '1983-05-25', '2011-01-01', 'England', NULL, NULL, 0),
(259, 6, 39976, 'Jay', 'Simpson', 'Jay Simpson', 'Forward', NULL, NULL, 11, 85, 180, '1988-12-01', '2012-01-06', 'England', NULL, NULL, 0),
(260, 6, 19182, 'Matty', 'Fryatt', 'Matty Fryatt', 'Forward', NULL, NULL, 12, 70, 178, '1986-03-05', '2011-01-01', 'England', NULL, NULL, 0),
(261, 6, 80281, 'Mark', 'Cullen', 'Mark Cullen', 'Forward', NULL, NULL, 21, NULL, NULL, '1992-04-24', '2010-01-01', 'England', NULL, NULL, 0),
(262, 6, 27503, 'Sone', 'Aluko', 'Sone Aluko', 'Forward', NULL, NULL, 24, 62, 173, '1989-02-19', '2012-07-25', 'Nigeria', NULL, NULL, 0),
(263, 6, 78778, 'Nick', 'Proschwitz', 'Nick Proschwitz', 'Forward', 'Striker', 'Centre', 33, 85, 192, '1986-11-28', '2012-07-18', 'Germany', NULL, NULL, 0),
(264, 23, 9631, 'Bradley', 'Jones', 'Bradley Jones', 'Goalkeeper', 'Goalkeeper', NULL, 1, 76, 191, '1982-03-19', '2010-08-16', 'Australia', NULL, NULL, 0),
(265, 23, 66797, 'Simon', 'Mignolet', 'Simon Mignolet', 'Goalkeeper', 'Goalkeeper', NULL, 22, 87, 193, '1988-03-06', '2013-06-26', 'Belgium', NULL, NULL, 0),
(266, 23, 8432, 'José', 'Reina', 'José Reina', 'Goalkeeper', 'Goalkeeper', NULL, 25, 85, 187, '1982-08-31', '2005-07-04', 'Spain', NULL, NULL, 0),
(267, 23, 95463, 'Danny', 'Ward', 'Danny Ward', 'Goalkeeper', 'Goalkeeper', NULL, 52, 88, 191, '1993-06-22', '2012-07-16', 'Wales', NULL, NULL, 0),
(268, 23, 9047, 'Glen', 'Johnson', 'Glen Johnson', 'Defender', 'Full Back', 'Centre', 2, 70, 178, '1984-08-23', '2009-06-22', 'England', NULL, NULL, 0),
(269, 23, 26725, 'José', 'Enrique Sanchez Diaz', 'José Enrique', 'Defender', 'Full Back', 'Left', 3, 76, 184, '1986-01-23', '2011-08-12', 'Spain', NULL, NULL, 0),
(270, 23, 21094, 'Daniel', 'Agger', 'Daniel Agger', 'Defender', 'Central Defender', 'Centre', 5, 79, 188, '1984-12-12', '2006-01-12', 'Denmark', NULL, NULL, 0),
(271, 23, 78108, 'Sebastián', 'Coates', 'Sebastián Coates', 'Defender', 'Central Defender', 'Centre', 16, 85, 196, '1990-10-07', '2011-08-31', 'Uruguay', NULL, NULL, 0),
(272, 23, 60490, 'Danny', 'Wilson', 'Danny Wilson', 'Defender', 'Central Defender', 'Centre', 22, 79, 187, '1991-12-27', '2010-07-21', 'Scotland', NULL, NULL, 0),
(273, 23, 1809, 'Jamie', 'Carragher', 'Jamie Carragher', 'Defender', 'Central Defender', 'Centre/Right', 23, 76, 185, '1978-01-28', '1994-08-01', 'England', NULL, NULL, 0),
(274, 23, 58786, 'Martin', 'Kelly', 'Martin Kelly', 'Defender', 'Full Back', 'Right', 34, 77, 191, '1990-04-27', '2008-11-25', 'England', NULL, NULL, 0),
(275, 23, 26793, 'Martin', 'Skrtel', 'Martin Skrtel', 'Defender', 'Central Defender', 'Centre', 37, 81, 191, '1984-12-15', '2008-01-11', 'Slovakia', NULL, NULL, 0),
(276, 23, 91979, 'Jon', 'Flanagan', 'Jon Flanagan', 'Defender', 'Full Back', 'Right', 38, 79, 181, '1993-01-01', '2010-12-01', 'England', NULL, NULL, 0),
(277, 23, 71639, 'Stephen', 'Sama', 'Stephen Sama', 'Defender', NULL, NULL, 45, 79, 188, '1993-03-05', '2012-07-16', 'Germany', NULL, NULL, 0),
(278, 23, 89088, 'Andre', 'Wisdom', 'Andre Wisdom', 'Defender', 'Central Defender', 'Centre', 47, 78, 186, '1993-05-09', '2010-09-01', 'England', NULL, NULL, 0),
(279, 23, 83427, 'Jack', 'Robinson', 'Jack Robinson', 'Defender', 'Full Back', 'Left', 49, 64, 168, '1993-09-01', '2010-05-01', 'England', NULL, NULL, 0),
(280, 23, 134382, 'Lloyd', 'Jones', 'Lloyd Jones', 'Defender', 'Central Defender', 'Centre', 51, NULL, NULL, '1995-10-07', '2013-05-12', 'England', NULL, NULL, 0),
(281, 23, 1814, 'Steven', 'Gerrard', 'Steven Gerrard', 'Midfielder', 'Attacking Midfielder', 'Centre', 8, 82, 185, '1980-05-30', '1998-08-01', 'England', NULL, NULL, 0),
(282, 23, 84583, 'Philippe', 'Coutinho', 'Philippe Coutinho', 'Midfielder', 'Attacking Midfielder', 'Centre', 10, 71, 171, '1992-06-12', '2013-01-30', 'Brazil', NULL, NULL, 0),
(283, 23, 56979, 'Jordan', 'Henderson', 'Jordan Henderson', 'Midfielder', 'Central Midfielder', 'Centre', 14, 67, 182, '1990-06-17', '2011-06-09', 'England', NULL, NULL, 0),
(284, 23, 12002, 'Stewart', 'Downing', 'Stewart Downing', 'Midfielder', 'Winger', 'Left', 19, 64, 180, '1984-07-22', '2011-07-15', 'England', NULL, NULL, 0),
(285, 23, 55548, 'Jay', 'Spearing', 'Jay Spearing', 'Midfielder', 'Central Midfielder', 'Centre', 20, 71, 171, '1988-11-25', '2008-08-01', 'England', NULL, NULL, 0),
(286, 23, 43191, 'Lucas', 'Leiva', 'Lucas Leiva', 'Midfielder', 'Central Midfielder', 'Centre', 21, 74, 179, '1987-01-09', '2007-05-13', 'Brazil', NULL, NULL, 0),
(287, 23, 40555, 'Joe', 'Allen', 'Joe Allen', 'Midfielder', 'Central Midfielder', 'Centre', 24, 62, 168, '1990-03-14', '2012-08-10', 'Wales', NULL, NULL, 0),
(288, 23, 103953, 'Jesús', 'Fernández Saez', 'Suso', 'Midfielder', 'Attacking Midfielder', 'Centre', 30, 70, 176, '1993-11-19', '2011-08-10', 'Spain', NULL, NULL, 0),
(289, 23, 103955, 'Raheem', 'Sterling', 'Raheem Sterling', 'Midfielder', 'Winger', 'Left', 31, 69, 170, '1994-12-08', '2011-06-01', 'England', NULL, NULL, 0),
(290, 23, 50232, 'Jonjo', 'Shelvey', 'Jonjo Shelvey', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 33, 80, 185, '1992-02-27', '2010-05-10', 'England', NULL, NULL, 0),
(291, 23, 94147, 'Conor', 'Coady', 'Conor Coady', 'Midfielder', 'Defensive Midfielder', 'Centre', 35, 73, 185, '1993-02-25', '2011-02-16', 'England', NULL, NULL, 0),
(292, 23, 103912, 'Jordon', 'Ibe', 'Jordon Ibe', 'Midfielder', 'Winger', 'Left/Right', 51, 81, 176, '1995-12-08', '2012-07-16', 'England', NULL, NULL, 0),
(293, 23, 39336, 'Luis', 'Suárez', 'Luis Suárez', 'Forward', 'Second Striker', 'Left/Centre/Right', 7, 81, 182, '1987-01-24', '2011-01-31', 'Uruguay', NULL, NULL, 0),
(294, 23, 40142, 'Andy', 'Carroll', 'Andy Carroll', 'Forward', 'Striker', 'Centre', 9, 79, 193, '1989-01-06', '2011-01-31', 'England', NULL, NULL, 0),
(295, 23, 77305, 'Oussama', 'Assaidi', 'Oussama Assaidi', 'Forward', 'Striker', 'Centre', 11, 65, 172, '1988-08-15', '2012-08-16', 'Morocco', NULL, NULL, 0),
(296, 23, 43016, 'Daniel', 'Pacheco', 'Dani Pacheco', 'Forward', 'Second Striker', 'Left/Centre/Right', 12, 65, 170, '1991-01-05', '2007-07-01', 'Spain', NULL, NULL, 0),
(297, 23, 40755, 'Daniel', 'Sturridge', 'Daniel Sturridge', 'Forward', 'Striker', 'Centre/Right', 15, 76, 188, '1989-09-01', '2013-01-02', 'England', NULL, NULL, 0),
(298, 23, 77454, 'Fabio', 'Borini', 'Fabio Borini', 'Forward', 'Striker', 'Left/Centre/Right', 29, 73, 178, '1991-03-29', '2012-07-13', 'Italy', NULL, NULL, 0),
(299, 23, 111289, 'Samed', 'Yesil', 'Samed Yesil', 'Forward', 'Striker', 'Centre', 36, 72, 180, '1994-05-25', '2012-08-31', 'Germany', NULL, NULL, 0),
(300, 23, 133801, 'Jerome', 'Sinclair', 'Jerome Sinclair', 'Forward', 'Striker', 'Centre', 48, 79, 173, '1996-09-20', '2012-09-01', 'England', NULL, NULL, 0),
(301, 23, 111295, 'Adam', 'Morgan', 'Adam Morgan', 'Forward', 'Striker', 'Centre', 50, 70, 174, '1994-04-21', '2012-07-16', 'England', NULL, NULL, 0),
(302, 2, 15749, 'Joe', 'Hart', 'Joe Hart', 'Goalkeeper', 'Goalkeeper', NULL, 1, 91, 196, '1987-04-19', '2006-05-22', 'England', NULL, NULL, 0),
(303, 2, 1101, 'Richard', 'Wright', 'Richard Wright', 'Goalkeeper', 'Goalkeeper', NULL, 29, 83, 188, '1977-11-05', '2012-08-31', 'England', NULL, NULL, 0),
(304, 2, 56827, 'Costel', 'Pantilimon', 'Costel Pantilimon', 'Goalkeeper', 'Goalkeeper', NULL, 30, 96, 203, '1987-02-01', '2011-08-05', 'Romania', NULL, NULL, 0),
(305, 2, 20492, 'Micah', 'Richards', 'Micah Richards', 'Defender', 'Full Back', 'Right', 2, 82, 180, '1988-06-24', '2005-07-01', 'England', NULL, NULL, 0),
(306, 2, 18573, 'Douglas', 'Maicon', 'Maicon', 'Defender', 'Full Back', 'Right', 3, 77, 184, '1981-07-26', '2012-08-31', 'Brazil', NULL, NULL, 0),
(307, 2, 17476, 'Vincent', 'Kompany', 'Vincent Kompany', 'Defender', 'Central Defender', 'Centre', 4, 85, 190, '1986-04-10', '2008-08-22', 'Belgium', NULL, NULL, 0),
(308, 2, 20658, 'Pablo', 'Zabaleta', 'Pablo Zabaleta', 'Defender', 'Full Back', 'Right', 5, 74, 174, '1985-01-16', '2008-08-31', 'Argentina', NULL, NULL, 0),
(309, 2, 7551, 'Joleon', 'Lescott', 'Joleon Lescott', 'Defender', 'Central Defender', 'Centre', 6, 89, 190, '1982-08-16', '2009-08-24', 'England', NULL, NULL, 0),
(310, 2, 42593, 'Aleksandar', 'Kolarov', 'Aleksandar Kolarov', 'Defender', 'Full Back', 'Left', 13, 83, 187, '1985-11-10', '2010-07-24', 'Serbia', NULL, NULL, 0),
(311, 2, 17336, 'Gaël', 'Clichy', 'Gaël Clichy', 'Defender', 'Full Back', 'Left', 22, 65, 176, '1985-07-26', '2011-07-04', 'France', NULL, NULL, 0);
INSERT INTO `player` (`id`, `team_id`, `feeder_id`, `name`, `surname`, `display_name`, `position`, `real_position`, `real_position_side`, `shirt_number`, `weight`, `height`, `birth_date`, `join_date`, `country`, `image_path`, `background_image_path`, `is_blocked`) VALUES
(312, 2, 12450, 'Kolo', 'Touré', 'Kolo Touré', 'Defender', 'Central Defender', 'Centre', 28, 74, 183, '1981-03-19', '2009-07-29', 'Côte d''Ivoire', NULL, NULL, 0),
(313, 2, 84702, 'Matija', 'Nastasic', 'Matija Nastasic', 'Defender', 'Central Defender', 'Centre', 33, 79, 187, '1993-03-28', '2012-08-31', 'Serbia', NULL, NULL, 0),
(314, 2, 80235, 'Dedryck', 'Boyata', 'Dedryck Boyata', 'Defender', 'Central Defender', 'Left/Centre', 38, 84, 188, '1990-11-28', '2009-12-24', 'Belgium', NULL, NULL, 0),
(315, 2, 103040, 'Karim', 'Rekik', 'Karim Rekik', 'Defender', 'Central Defender', 'Centre', 44, 78, 185, '1994-12-02', '2011-07-01', 'Netherlands', NULL, NULL, 0),
(316, 2, 56006, 'Ryan', 'McGivern', 'Ryan McGivern', 'Defender', 'Central Defender', 'Left/Centre', 48, 84, 183, '1990-01-08', '2007-07-01', 'Northern Ireland', NULL, NULL, 0),
(317, 2, 94685, 'Reece', 'Wabara', 'Reece Wabara', 'Defender', 'Central Defender', 'Centre', 57, 79, 182, '1991-12-28', '2011-02-23', 'England', NULL, NULL, 0),
(318, 2, 104537, 'Jeremy', 'Helan', 'Jeremy Helan', 'Midfielder', NULL, NULL, NULL, NULL, 181, '1992-05-09', '2012-09-11', 'France', NULL, NULL, 0),
(319, 2, 15157, 'James', 'Milner', 'James Milner', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 7, 70, 170, '1986-01-04', '2010-08-18', 'England', NULL, NULL, 0),
(320, 2, 28554, 'Samir', 'Nasri', 'Samir Nasri', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 8, 75, 175, '1987-06-26', '2011-08-24', 'France', NULL, NULL, 0),
(321, 2, 19688, 'Scott', 'Sinclair', 'Scott Sinclair', 'Midfielder', 'Winger', 'Left/Right', 11, 64, 175, '1989-03-25', '2012-08-31', 'England', NULL, NULL, 0),
(322, 2, 19534, 'Francisco Javier', 'García Fernández', 'Javi García', 'Midfielder', 'Defensive Midfielder', 'Centre', 14, 85, 180, '1987-02-08', '2012-08-31', 'Spain', NULL, NULL, 0),
(323, 2, 49384, 'Jack', 'Rodwell', 'Jack Rodwell', 'Midfielder', 'Defensive Midfielder', 'Centre', 17, 80, 188, '1991-03-11', '2012-08-13', 'England', NULL, NULL, 0),
(324, 2, 1632, 'Gareth', 'Barry', 'Gareth Barry', 'Midfielder', 'Central Midfielder', 'Left/Centre', 18, 78, 183, '1981-02-23', '2009-06-02', 'England', NULL, NULL, 0),
(325, 2, 20664, 'David', 'Silva', 'David Silva', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 21, 67, 170, '1986-01-08', '2010-06-30', 'Spain', NULL, NULL, 0),
(326, 2, 95545, 'Mohammed', 'Abu', 'Mohammed Abu', 'Midfielder', 'Defensive Midfielder', 'Centre', 31, 75, 176, '1991-11-14', '2011-07-01', 'Ghana', NULL, NULL, 0),
(327, 2, 89572, 'Denis', 'Suárez', 'Denis Suárez', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 36, 69, 171, '1994-01-06', '2011-07-01', 'Spain', NULL, NULL, 0),
(328, 2, 14664, 'Gnegneri Yaya', 'Touré', 'Yaya Touré', 'Midfielder', 'Defensive Midfielder', 'Centre', 42, 90, 189, '1983-05-13', '2010-07-02', 'Côte d''Ivoire', NULL, NULL, 0),
(329, 2, 106422, 'Emyr', 'Huws', 'Emyr Huws', 'Midfielder', NULL, NULL, 52, NULL, NULL, '1993-09-30', '2013-05-20', 'Wales', NULL, NULL, 0),
(330, 2, 84481, 'Albert', 'Rusnák', 'Albert Rusnák', 'Midfielder', NULL, NULL, 55, NULL, 175, '1994-07-07', '2013-05-20', 'Slovakia', NULL, NULL, 0),
(331, 2, 106423, 'George', 'Evans', 'George Evans', 'Midfielder', NULL, NULL, 57, 76, 184, '1994-12-13', '2012-09-01', 'England', NULL, NULL, 0),
(332, 2, 93666, 'Abdul', 'Razak', 'Abdul Razak', 'Midfielder', 'Central Midfielder', 'Left/Centre', 62, 76, 180, '1992-11-11', '2011-01-31', 'Côte d''Ivoire', NULL, NULL, 0),
(333, 2, 109653, 'Marcos Paulo', 'Mesquita Lopes', 'Marcos Lopes', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 64, 68, 174, '1995-12-28', '2013-01-01', 'Portugal', NULL, NULL, 0),
(334, 2, 42544, 'Edin', 'Dzeko', 'Edin Dzeko', 'Forward', 'Striker', 'Centre', 10, 84, 193, '1986-03-17', '2011-01-07', 'Bosnia and Herzegovina', NULL, NULL, 0),
(335, 2, 37572, 'Sergio', 'Agüero', 'Sergio Agüero', 'Forward', 'Striker', 'Centre', 16, 70, 175, '1988-06-02', '2011-07-27', 'Argentina', NULL, NULL, 0),
(336, 2, 20312, 'Carlos', 'Tévez', 'Carlos Tévez', 'Forward', 'Second Striker', 'Centre', 32, 75, 173, '1984-02-05', '2009-07-14', 'Argentina', NULL, NULL, 0),
(337, 2, 77796, 'Alex', 'Nimely', 'Alex Nimely', 'Forward', 'Striker', 'Centre', 43, 74, 180, '1991-05-11', '2010-04-02', 'England', NULL, NULL, 0),
(338, 2, 72799, 'Luca', 'Scapuzzi', 'Luca Scapuzzi', 'Forward', 'Striker', 'Centre', 49, 75, 182, '1991-04-15', '2011-07-28', 'Italy', NULL, NULL, 0),
(339, 2, 82970, 'John', 'Guidetti', 'John Guidetti', 'Forward', 'Striker', 'Centre', 60, 79, 185, '1992-04-15', '2010-09-21', 'Sweden', NULL, NULL, 0),
(340, 7, 51940, 'David', 'De Gea', 'David De Gea', 'Goalkeeper', 'Goalkeeper', NULL, 1, 76, 192, '1990-11-07', '2011-07-01', 'Spain', NULL, NULL, 0),
(341, 7, 39725, 'Anders', 'Lindegaard', 'Anders Lindegaard', 'Goalkeeper', 'Goalkeeper', NULL, 13, 80, 193, '1984-04-13', '2011-01-01', 'Denmark', NULL, NULL, 0),
(342, 7, 51922, 'Ben', 'Amos', 'Ben Amos', 'Goalkeeper', 'Goalkeeper', NULL, 40, 70, 180, '1990-04-10', '2008-07-11', 'England', NULL, NULL, 0),
(343, 7, 101982, 'Sam', 'Johnstone', 'Sam Johnstone', 'Goalkeeper', 'Goalkeeper', NULL, 50, 85, 193, '1993-03-25', '2011-07-01', 'England', NULL, NULL, 0),
(344, 7, 152015, 'Guillermo', 'Varela', 'Guillermo Varela', 'Defender', 'Full Back', 'Right', NULL, 70, 180, '1993-03-24', '2013-06-11', 'Uruguay', NULL, NULL, 0),
(345, 7, 54771, 'Fabio Pereira', 'da Silva', 'Fabio', 'Defender', 'Full Back', 'Left/Right', NULL, 65, 172, '1990-07-09', '2008-07-01', 'Brazil', NULL, NULL, 0),
(346, 7, 54772, 'Rafael Pereira', 'da Silva', 'Rafael', 'Defender', 'Full Back', 'Right', 2, 80, 173, '1990-07-09', '2008-07-01', 'Brazil', NULL, NULL, 0),
(347, 7, 14075, 'Patrice', 'Evra', 'Patrice Evra', 'Defender', 'Full Back', 'Left', 3, 76, 175, '1981-05-15', '2006-01-10', 'France', NULL, NULL, 0),
(348, 7, 76359, 'Phil', 'Jones', 'Phil Jones', 'Defender', 'Central Defender', 'Centre', 4, 72, 178, '1992-02-21', '2011-06-13', 'England', NULL, NULL, 0),
(349, 7, 2034, 'Rio', 'Ferdinand', 'Rio Ferdinand', 'Defender', 'Central Defender', 'Centre', 5, 82, 189, '1978-11-07', '2002-07-22', 'England', NULL, NULL, 0),
(350, 7, 37642, 'Jonny', 'Evans', 'Jonny Evans', 'Defender', 'Central Defender', 'Centre', 6, 77, 188, '1988-01-02', '2006-07-01', 'Northern Ireland', NULL, NULL, 0),
(351, 7, 55909, 'Chris', 'Smalling', 'Chris Smalling', 'Defender', 'Central Defender', 'Centre', 12, 90, 194, '1989-11-22', '2010-07-01', 'England', NULL, NULL, 0),
(352, 7, 14965, 'Nemanja', 'Vidic', 'Nemanja Vidic', 'Defender', 'Central Defender', 'Centre', 15, 84, 189, '1981-10-21', '2006-01-04', 'Serbia', NULL, NULL, 0),
(353, 7, 50004, 'Alexander', 'Büttner', 'Alexander Büttner', 'Defender', 'Full Back', 'Left', 28, 75, 174, '1989-02-11', '2012-08-21', 'Netherlands', NULL, NULL, 0),
(354, 7, 89428, 'Scott', 'Wootton', 'Scott Wootton', 'Defender', 'Central Defender', 'Centre', 31, 78, 188, '1991-09-12', '2010-07-01', 'England', NULL, NULL, 0),
(355, 7, 58747, 'Marnick', 'Vermijl', 'Marnick Vermijl', 'Defender', 'Full Back', 'Right', 36, 70, 180, '1992-01-13', '2011-10-01', 'Belgium', NULL, NULL, 0),
(356, 7, 106611, 'Michael', 'Keane', 'Michael Keane', 'Defender', 'Full Back', 'Right', 38, 68, 172, '1993-01-11', '2011-09-01', 'England', NULL, NULL, 0),
(357, 7, 106612, 'Tom', 'Thorpe', 'Tom Thorpe', 'Defender', 'Central Defender', 'Centre', 39, 89, 185, '1993-01-13', '2011-09-01', 'England', NULL, NULL, 0),
(358, 7, 20695, 'Luis Antonio', 'Valencia', 'Antonio Valencia', 'Midfielder', 'Winger', 'Right', 7, 78, 181, '1985-08-04', '2009-07-01', 'Ecuador', NULL, NULL, 0),
(359, 7, 27258, 'Anderson', 'Oliveira', 'Anderson', 'Midfielder', 'Central Midfielder', 'Centre', 8, 69, 176, '1988-04-13', '2007-07-01', 'Brazil', NULL, NULL, 0),
(360, 7, 3, 'Ryan', 'Giggs', 'Ryan Giggs', 'Midfielder', 'Central Midfielder', 'Left/Centre', 11, 68, 180, '1973-11-29', '1990-12-01', 'Wales', NULL, NULL, 0),
(361, 7, 2404, 'Michael', 'Carrick', 'Michael Carrick', 'Midfielder', 'Central Midfielder', 'Centre', 16, 74, 183, '1981-07-28', '2006-07-31', 'England', NULL, NULL, 0),
(362, 7, 38530, 'Luís', 'Almeida da Cunha', 'Nani', 'Midfielder', 'Winger', 'Left/Right', 17, 66, 175, '1986-11-17', '2007-07-02', 'Portugal', NULL, NULL, 0),
(363, 7, 18892, 'Ashley', 'Young', 'Ashley Young', 'Midfielder', 'Attacking Midfielder', 'Left', 18, 65, 180, '1985-07-09', '2011-06-23', 'England', NULL, NULL, 0),
(364, 7, 363, 'Paul', 'Scholes', 'Paul Scholes', 'Midfielder', 'Central Midfielder', 'Centre', 22, 70, 168, '1974-11-16', '1993-01-29', 'England', NULL, NULL, 0),
(365, 7, 43250, 'Tom', 'Cleverley', 'Tom Cleverley', 'Midfielder', 'Winger', 'Left/Right', 23, 67, 175, '1989-08-12', '2007-07-01', 'England', NULL, NULL, 0),
(366, 7, 14295, 'Darren', 'Fletcher', 'Darren Fletcher', 'Midfielder', 'Central Midfielder', 'Centre/Right', 24, 83, 183, '1984-02-01', '2000-07-01', 'Scotland', NULL, NULL, 0),
(367, 7, 83090, 'Shinji', 'Kagawa', 'Shinji Kagawa', 'Midfielder', 'Attacking Midfielder', 'Centre', 26, 64, 172, '1989-03-17', '2012-07-01', 'Japan', NULL, NULL, 0),
(368, 7, 106610, 'Larnell', 'Cole', 'Larnell Cole', 'Midfielder', NULL, NULL, 34, 63, 168, '1993-03-09', '2011-09-01', 'England', NULL, NULL, 0),
(369, 7, 109322, 'Jesse', 'Lingard', 'Jesse Lingard', 'Midfielder', 'Attacking Midfielder', 'Left/Right', 35, 58, 168, '1992-12-15', '2011-11-30', 'England', NULL, NULL, 0),
(370, 7, 154976, 'Adnan', 'Januzaj', 'Adnan Januzaj', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 44, NULL, NULL, '1995-02-05', '2013-05-19', 'Belgium', NULL, NULL, 0),
(371, 7, 94217, 'Ryan', 'Tunnicliffe', 'Ryan Tunnicliffe', 'Midfielder', 'Defensive Midfielder', 'Centre', 46, 80, 183, '1992-12-30', '2010-02-18', 'England', NULL, NULL, 0),
(372, 7, 82403, 'Wilfried', 'Zaha', 'Wilfried Zaha', 'Forward', 'Second Striker', 'Left/Centre/Right', NULL, 66, 180, '1992-11-10', '2013-01-25', 'England', NULL, NULL, 0),
(373, 7, 13017, 'Wayne', 'Rooney', 'Wayne Rooney', 'Forward', 'Second Striker', 'Left/Centre/Right', 10, 78, 178, '1985-10-24', '2004-08-31', 'England', NULL, NULL, 0),
(374, 7, 43020, 'Javier', 'Hernández', 'Javier Hernández', 'Forward', 'Striker', 'Centre', 14, 62, 173, '1988-06-01', '2010-05-27', 'Mexico', NULL, NULL, 0),
(375, 7, 50175, 'Danny', 'Welbeck', 'Danny Welbeck', 'Forward', 'Striker', 'Centre', 19, 73, 185, '1990-11-26', '2008-04-01', 'England', NULL, NULL, 0),
(376, 7, 12297, 'Robin', 'van Persie', 'Robin van Persie', 'Forward', 'Striker', 'Centre', 20, 71, 183, '1983-08-06', '2012-08-16', 'Netherlands', NULL, NULL, 0),
(377, 7, 125510, 'Angelo', 'Henriquez', 'Angelo Henriquez', 'Forward', 'Striker', 'Centre', 21, 75, 181, '1994-04-13', '2012-08-15', 'Chile', NULL, NULL, 0),
(378, 7, 82205, 'Nick', 'Powell', 'Nick Powell', 'Forward', 'Striker', 'Centre', 25, 65, 183, '1994-03-23', '2012-06-12', 'England', NULL, NULL, 0),
(379, 7, 59970, 'Federico', 'Macheda', 'Federico Macheda', 'Forward', 'Striker', 'Centre', 27, 77, 184, '1991-08-22', '2007-09-01', 'Italy', NULL, NULL, 0),
(380, 7, 84374, 'Tiago Manuel', 'Dias Correia', 'Bebé', 'Forward', 'Second Striker', 'Left/Centre/Right', 33, 75, 190, '1990-07-12', '2010-08-17', 'Portugal', NULL, NULL, 0),
(381, 7, 91126, 'William', 'Keane', 'William Keane', 'Forward', 'Striker', 'Centre', 48, 78, 178, '1993-01-11', '2010-09-01', 'England', NULL, NULL, 0),
(382, 14, 20480, 'Tim', 'Krul', 'Tim Krul', 'Goalkeeper', 'Goalkeeper', NULL, 1, 74, 188, '1988-04-03', '2006-06-01', 'Netherlands', NULL, NULL, 0),
(383, 14, 19838, 'Robert', 'Elliot', 'Robert Elliot', 'Goalkeeper', 'Goalkeeper', NULL, 21, 98, 190, '1986-04-30', '2011-08-31', 'England', NULL, NULL, 0),
(384, 14, 1881, 'Steve', 'Harper', 'Steve Harper', 'Goalkeeper', 'Goalkeeper', NULL, 37, 83, 185, '1975-03-14', '1993-07-05', 'England', NULL, NULL, 0),
(385, 14, 106256, 'Jak', 'Alnwick', 'Jak Alnwick', 'Goalkeeper', 'Goalkeeper', NULL, 42, 82, 188, '1993-06-17', '2012-08-01', 'England', NULL, NULL, 0),
(386, 14, 7933, 'Fabricio', 'Coloccini', 'Fabricio Coloccini', 'Defender', 'Central Defender', 'Centre', 2, 63, 183, '1982-01-22', '2008-08-15', 'Argentina', NULL, NULL, 0),
(387, 14, 55604, 'Davide', 'Santon', 'Davide Santon', 'Defender', 'Full Back', 'Left/Right', 3, 77, 187, '1991-01-02', '2011-08-30', 'Italy', NULL, NULL, 0),
(388, 14, 11378, 'Michael', 'Williamson', 'Michael Williamson', 'Defender', 'Central Defender', 'Centre', 6, 84, 191, '1983-11-08', '2010-01-27', 'England', NULL, NULL, 0),
(389, 14, 44488, 'Mapou', 'Yanga-Mbiwa', 'Mapou Yanga-Mbiwa', 'Defender', 'Central Defender', 'Centre/Right', 13, 77, 184, '1989-05-15', '2013-01-22', 'France', NULL, NULL, 0),
(390, 14, 18846, 'James', 'Perch', 'James Perch', 'Defender', 'Central Defender', 'Left/Centre/Right', 14, 72, 180, '1985-09-28', '2010-07-05', 'England', NULL, NULL, 0),
(391, 14, 14775, 'Ryan', 'Taylor', 'Ryan Taylor', 'Defender', 'Full Back', 'Left/Right', 16, 75, 173, '1984-08-19', '2009-02-02', 'England', NULL, NULL, 0),
(392, 14, 92170, 'Massadio', 'Haidara', 'Massadio Haidara', 'Defender', 'Full Back', 'Left', 19, 76, 181, '1992-12-02', '2013-01-24', 'France', NULL, NULL, 0),
(393, 14, 27334, 'Mathieu', 'Debuchy', 'Mathieu Debuchy', 'Defender', 'Full Back', 'Right', 26, 76, 177, '1985-07-28', '2013-01-04', 'France', NULL, NULL, 0),
(394, 14, 17974, 'Steven', 'Taylor', 'Steven Taylor', 'Defender', 'Central Defender', 'Centre', 27, 81, 188, '1986-01-23', '2002-07-01', 'England', NULL, NULL, 0),
(395, 14, 61944, 'Shane', 'Ferguson', 'Shane Ferguson', 'Defender', 'Full Back', 'Left', 31, 73, 178, '1991-07-12', '2009-06-01', 'Northern Ireland', NULL, NULL, 0),
(396, 14, 124584, 'Curtis', 'Good', 'Curtis Good', 'Defender', 'Central Defender', 'Left/Centre', 33, 85, 187, '1993-03-23', '2012-08-01', 'Australia', NULL, NULL, 0),
(397, 14, 77816, 'James', 'Tavernier', 'James Tavernier', 'Defender', 'Central Defender', 'Left/Centre/Right', 34, 75, 182, '1991-10-31', '2009-09-01', 'England', NULL, NULL, 0),
(398, 14, 106618, 'Paul', 'Dummett', 'Paul Dummett', 'Defender', 'Full Back', 'Left', 36, 65, 178, '1991-09-26', '2011-09-01', 'Wales', NULL, NULL, 0),
(399, 14, 149465, 'Remie', 'Streete', 'Remie Streete', 'Defender', NULL, NULL, 44, 83, 187, '1994-11-02', '2012-12-01', 'England', NULL, NULL, 0),
(400, 14, 149487, 'Conor', 'Newton', 'Conor Newton', 'Midfielder', 'Central Midfielder', 'Centre/Right', NULL, 70, 181, '1991-10-17', '2012-07-01', 'England', NULL, NULL, 0),
(401, 14, 27341, 'Yohan', 'Cabaye', 'Yohan Cabaye', 'Midfielder', 'Central Midfielder', 'Centre', 4, 71, 175, '1986-01-14', '2011-06-11', 'France', NULL, NULL, 0),
(402, 14, 45268, 'Moussa', 'Sissoko', 'Moussa Sissoko', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 7, 83, 187, '1989-08-16', '2013-01-25', 'France', NULL, NULL, 0),
(403, 14, 40799, 'Vurnon', 'Anita', 'Vurnon Anita', 'Midfielder', 'Defensive Midfielder', 'Left/Centre/Right', 8, 63, 168, '1989-04-04', '2012-08-16', 'Netherlands', NULL, NULL, 0),
(404, 14, 18753, 'Hatem', 'Ben Arfa', 'Hatem Ben Arfa', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 10, 69, 177, '1987-03-07', '2010-08-28', 'France', NULL, NULL, 0),
(405, 14, 40387, 'Dan', 'Gosling', 'Dan Gosling', 'Midfielder', 'Central Midfielder', 'Right', 15, 71, 178, '1990-02-02', '2010-07-22', 'England', NULL, NULL, 0),
(406, 14, 86158, 'Romain', 'Amalfitano', 'Romain Amalfitano', 'Midfielder', 'Attacking Midfielder', 'Centre/Right', 17, 69, 175, '1989-08-27', '2012-07-01', 'France', NULL, NULL, 0),
(407, 14, 21060, 'Jonás', 'Gutiérrez', 'Jonás Gutiérrez', 'Midfielder', 'Attacking Midfielder', 'Left/Right', 18, 73, 183, '1983-07-05', '2008-07-02', 'Argentina', NULL, NULL, 0),
(408, 14, 87112, 'Gael', 'Bigirimana', 'Gael Bigirimana', 'Midfielder', 'Central Midfielder', 'Centre', 20, 68, 173, '1993-10-22', '2012-07-06', 'England', NULL, NULL, 0),
(409, 14, 37939, 'Sylvain', 'Marveaux', 'Sylvain Marveaux', 'Midfielder', 'Winger', 'Left/Right', 22, 66, 172, '1986-04-15', '2011-06-18', 'France', NULL, NULL, 0),
(410, 14, 28301, 'Cheik Ismael', 'Tioté', 'Cheik Tioté', 'Midfielder', 'Defensive Midfielder', 'Centre', 24, 76, 175, '1986-06-21', '2010-08-27', 'Côte d''Ivoire', NULL, NULL, 0),
(411, 14, 38429, 'Gabriel', 'Obertan', 'Gabriel Obertan', 'Midfielder', 'Winger', 'Left/Right', 25, 79, 186, '1989-02-26', '2011-08-09', 'France', NULL, NULL, 0),
(412, 14, 59304, 'Haris', 'Vuckic', 'Haris Vuckic', 'Midfielder', 'Attacking Midfielder', 'Centre', 29, 78, 189, '1992-08-21', '2009-01-16', 'Slovenia', NULL, NULL, 0),
(413, 14, 59787, 'Bradden', 'Inman', 'Bradden Inman', 'Midfielder', 'Central Midfielder', 'Centre', 38, 79, 182, '1991-12-10', '2009-01-27', 'Australia', NULL, NULL, 0),
(414, 14, 98746, 'Mehdi', 'Abeid', 'Mehdi Abeid', 'Midfielder', 'Attacking Midfielder', 'Centre', 39, 77, 178, '1992-08-06', '2011-07-01', 'Algeria', NULL, NULL, 0),
(415, 14, 93625, 'Michael', 'Richardson', 'Michael Richardson', 'Midfielder', 'Central Midfielder', 'Centre', 40, NULL, NULL, '1992-03-17', '2011-02-01', 'England', NULL, NULL, 0),
(416, 14, 42758, 'Papiss Demba', 'Cissé', 'Papiss Demba Cissé', 'Forward', 'Striker', 'Centre', 9, 73, 183, '1985-06-03', '2012-01-17', 'Senegal', NULL, NULL, 0),
(417, 14, 42727, 'Yoan', 'Gouffran', 'Yoan Gouffran', 'Forward', 'Second Striker', 'Centre/Right', 11, 76, 176, '1986-05-25', '2013-01-23', 'France', NULL, NULL, 0),
(418, 14, 6240, 'Shola', 'Ameobi', 'Shola Ameobi', 'Forward', 'Striker', 'Centre', 23, 76, 191, '1981-10-12', '2000-08-01', 'Nigeria', NULL, NULL, 0),
(419, 14, 56239, 'Nile', 'Ranger', 'Nile Ranger', 'Forward', 'Striker', 'Centre', 30, 78, 187, '1991-04-11', '2008-08-07', 'England', NULL, NULL, 0),
(420, 14, 132931, 'Adam', 'Campbell', 'Adam Campbell', 'Forward', 'Striker', 'Centre', 49, 57, 170, '1995-01-01', '2012-09-01', 'England', NULL, NULL, 0),
(421, 12, 19236, 'John', 'Ruddy', 'John Ruddy', 'Goalkeeper', 'Goalkeeper', NULL, 1, 97, 193, '1986-10-24', '2010-07-05', 'England', NULL, NULL, 0),
(422, 12, 10954, 'Mark', 'Bunn', 'Mark Bunn', 'Goalkeeper', 'Goalkeeper', NULL, 28, 77, 183, '1984-11-16', '2012-08-31', 'England', NULL, NULL, 0),
(423, 12, 79852, 'Jed', 'Steer', 'Jed Steer', 'Goalkeeper', 'Goalkeeper', NULL, 31, 89, 188, '1992-09-23', '2009-11-27', 'England', NULL, NULL, 0),
(424, 12, 15904, 'Lee', 'Camp', 'Lee Camp', 'Goalkeeper', 'Goalkeeper', NULL, 42, 74, 183, '1984-08-22', '2013-01-29', 'Northern Ireland', NULL, NULL, 0),
(425, 12, 19341, 'Russell', 'Martin', 'Russell Martin', 'Defender', 'Full Back', 'Right', 2, 74, 183, '1986-01-04', '2009-11-25', 'Scotland', NULL, NULL, 0),
(426, 12, 13164, 'Steven', 'Whittaker', 'Steven Whittaker', 'Defender', 'Full Back', 'Right', 3, 87, 185, '1984-06-16', '2012-07-22', 'Scotland', NULL, NULL, 0),
(427, 12, 42744, 'Sébastien', 'Bassong', 'Sébastien Bassong', 'Defender', 'Central Defender', 'Centre', 5, 73, 187, '1986-07-09', '2012-08-21', 'Cameroon', NULL, NULL, 0),
(428, 12, 15864, 'Michael', 'Turner', 'Michael Turner', 'Defender', 'Central Defender', 'Centre', 6, 84, 193, '1983-11-09', '2012-07-27', 'England', NULL, NULL, 0),
(429, 12, 17271, 'Javier', 'Garrido', 'Javier Garrido', 'Defender', 'Full Back', 'Left', 18, 75, 178, '1985-03-15', '2012-08-17', 'Spain', NULL, NULL, 0),
(430, 12, 15201, 'Leon', 'Barnett', 'Leon Barnett', 'Defender', 'Central Defender', 'Centre', 20, 71, 185, '1985-11-30', '2010-08-26', 'England', NULL, NULL, 0),
(431, 12, 11078, 'Elliott', 'Ward', 'Elliott Ward', 'Defender', 'Central Defender', 'Centre', 22, 83, 185, '1985-01-19', '2010-05-26', 'England', NULL, NULL, 0),
(432, 12, 19124, 'Marc', 'Tierney', 'Marc Tierney', 'Defender', 'Full Back', 'Left', 23, 71, 184, '1985-08-23', '2011-01-12', 'England', NULL, NULL, 0),
(433, 12, 41727, 'Ryan', 'Bennett', 'Ryan Bennett', 'Defender', 'Central Defender', 'Centre', 24, 70, 188, '1990-03-06', '2012-01-31', 'England', NULL, NULL, 0),
(434, 12, 75880, 'Daniel', 'Ayala', 'Daniel Ayala', 'Defender', 'Central Defender', 'Centre', 26, 81, 190, '1990-11-07', '2011-08-14', 'Spain', NULL, NULL, 0),
(435, 12, 78472, 'George', 'Francomb', 'George Francomb', 'Defender', 'Full Back', 'Right', 33, 75, 180, '1991-09-08', '2009-10-08', 'England', NULL, NULL, 0),
(436, 12, 19569, 'Bradley', 'Johnson', 'Bradley Johnson', 'Midfielder', 'Central Midfielder', 'Centre', 4, 68, 178, '1987-04-28', '2011-07-01', 'England', NULL, NULL, 0),
(437, 12, 38297, 'Jonathan', 'Howson', 'Jonathan Howson', 'Midfielder', 'Central Midfielder', 'Centre', 8, 77, 180, '1988-05-21', '2012-01-24', 'England', NULL, NULL, 0),
(438, 12, 15237, 'Andrew', 'Surman', 'Andrew Surman', 'Midfielder', 'Attacking Midfielder', 'Centre', 11, 73, 180, '1986-08-20', '2010-06-22', 'England', NULL, NULL, 0),
(439, 12, 40451, 'Anthony', 'Pilkington', 'Anthony Pilkington', 'Midfielder', 'Winger', 'Left/Right', 12, 76, 180, '1988-06-06', '2011-07-06', 'Republic of Ireland', NULL, NULL, 0),
(440, 12, 28499, 'Wes', 'Hoolahan', 'Wes Hoolahan', 'Midfielder', 'Central Midfielder', 'Left', 14, 71, 168, '1982-05-20', '2008-06-26', 'Republic of Ireland', NULL, NULL, 0),
(441, 12, 17812, 'David', 'Fox', 'David Fox', 'Midfielder', 'Central Midfielder', 'Centre', 15, 77, 175, '1983-12-13', '2010-06-04', 'England', NULL, NULL, 0),
(442, 12, 41262, 'Elliott', 'Bennett', 'Elliott Bennett', 'Midfielder', 'Winger', 'Right', 17, 69, 175, '1988-12-18', '2011-07-01', 'England', NULL, NULL, 0),
(443, 12, 47420, 'Jacob', 'Butterfield', 'Jacob Butterfield', 'Midfielder', 'Central Midfielder', 'Centre', 21, 77, 180, '1990-06-10', '2012-07-03', 'England', NULL, NULL, 0),
(444, 12, 60488, 'Tom', 'Adeyemi', 'Tom Adeyemi', 'Midfielder', 'Central Midfielder', 'Centre', 27, 78, 185, '1991-10-24', '2009-03-01', 'England', NULL, NULL, 0),
(445, 12, 18665, 'Alexander', 'Tettey', 'Alexander Tettey', 'Midfielder', 'Defensive Midfielder', 'Centre', 27, 68, 180, '1986-04-04', '2012-08-24', 'Norway', NULL, NULL, 0),
(446, 12, 59129, 'Korey', 'Smith', 'Korey Smith', 'Midfielder', 'Central Midfielder', 'Centre', 32, 78, 183, '1991-01-31', '2009-01-02', 'England', NULL, NULL, 0),
(447, 12, 114245, 'Josh', 'Murphy', 'Josh Murphy', 'Midfielder', 'Winger', 'Left', 34, 67, 173, '1995-02-24', '2012-09-24', 'England', NULL, NULL, 0),
(448, 12, 19916, 'James', 'Vaughan', 'James Vaughan', 'Forward', 'Striker', 'Centre', NULL, 82, 180, '1988-07-14', '2011-06-01', 'England', NULL, NULL, 0),
(449, 12, 161761, 'Ollie', 'Cole', 'Ollie Cole', 'Forward', NULL, NULL, NULL, NULL, NULL, NULL, '2013-05-15', 'England', NULL, NULL, 0),
(450, 12, 18987, 'Robert', 'Snodgrass', 'Robert Snodgrass', 'Forward', 'Striker', 'Centre', 7, 82, 182, '1987-09-07', '2012-07-26', 'Scotland', NULL, NULL, 0),
(451, 12, 5741, 'Grant', 'Holt', 'Grant Holt', 'Forward', 'Striker', 'Centre', 9, 77, 183, '1981-04-12', '2009-07-25', 'England', NULL, NULL, 0),
(452, 12, 19321, 'Simeon', 'Jackson', 'Simeon Jackson', 'Forward', 'Striker', 'Centre', 10, 72, 170, '1987-03-28', '2010-07-16', 'Canada', NULL, NULL, 0),
(453, 12, 19791, 'Luciano', 'Becchio', 'Luciano Becchio', 'Forward', 'Striker', 'Centre', 19, 86, 187, '1983-12-28', '2013-01-31', 'Argentina', NULL, NULL, 0),
(454, 12, 39776, 'Chris', 'Martin', 'Chris Martin', 'Forward', 'Striker', 'Centre', 25, 73, 178, '1988-11-04', '2002-08-01', 'England', NULL, NULL, 0),
(455, 17, 3673, 'Kelvin', 'Davis', 'Kelvin Davis', 'Goalkeeper', 'Goalkeeper', NULL, 1, 74, 185, '1976-09-29', '2006-07-21', 'England', NULL, NULL, 0),
(456, 17, 102884, 'Paulo', 'Gazzaniga', 'Paulo Gazzaniga', 'Goalkeeper', 'Goalkeeper', NULL, 12, 90, 196, '1992-01-02', '2012-07-19', 'Argentina', NULL, NULL, 0),
(457, 17, 101135, 'Cody', 'Cropper', 'Cody Cropper', 'Goalkeeper', 'Goalkeeper', NULL, 30, 91, 191, '1993-02-16', '2012-08-31', 'USA', NULL, NULL, 0),
(458, 17, 18726, 'Artur', 'Boruc', 'Artur Boruc', 'Goalkeeper', 'Goalkeeper', NULL, 31, 89, 193, '1980-02-20', '2012-09-22', 'Poland', NULL, NULL, 0),
(459, 17, 59357, 'Aaron', 'Martin', 'Aaron Martin', 'Defender', 'Central Defender', 'Centre', NULL, 76, 190, '1989-09-29', '2009-11-06', 'England', NULL, NULL, 0),
(460, 17, 57328, 'Nathaniel', 'Clyne', 'Nathaniel Clyne', 'Defender', 'Full Back', 'Right', 2, 67, 175, '1991-04-05', '2012-07-18', 'England', NULL, NULL, 0),
(461, 17, 80447, 'Maya', 'Yoshida', 'Maya Yoshida', 'Defender', 'Central Defender', 'Centre', 3, 78, 186, '1988-08-24', '2012-08-31', 'Japan', NULL, NULL, 0),
(462, 17, 15976, 'Jos', 'Hooiveld', 'Jos Hooiveld', 'Defender', 'Central Defender', 'Centre', 5, 86, 193, '1983-04-22', '2011-08-31', 'Netherlands', NULL, NULL, 0),
(463, 17, 38580, 'Jose', 'Fonte', 'Jose Fonte', 'Defender', 'Central Defender', 'Centre', 6, 81, 187, '1983-12-22', '2010-01-09', 'Portugal', NULL, NULL, 0),
(464, 17, 18421, 'Daniel', 'Fox', 'Daniel Fox', 'Defender', 'Full Back', 'Left', 13, 79, 183, '1986-05-29', '2011-08-15', 'Scotland', NULL, NULL, 0),
(465, 17, 61855, 'Vegard', 'Forren', 'Vegard Forren', 'Defender', 'Central Defender', 'Centre', 15, 85, 186, '1988-02-16', '2013-01-18', 'Norway', NULL, NULL, 0),
(466, 17, 106760, 'Luke', 'Shaw', 'Luke Shaw', 'Defender', 'Full Back', 'Left', 23, 75, 185, '1995-07-12', '2011-09-01', 'England', NULL, NULL, 0),
(467, 17, 88900, 'Jack', 'Stephens', 'Jack Stephens', 'Defender', 'Full Back', 'Centre/Right', 26, 75, 185, '1994-01-27', '2011-04-04', 'England', NULL, NULL, 0),
(468, 17, 42774, 'Morgan', 'Schneiderlin', 'Morgan Schneiderlin', 'Midfielder', 'Defensive Midfielder', 'Centre', 4, 75, 181, '1989-11-08', '2008-06-01', 'France', NULL, NULL, 0),
(469, 17, 17339, 'Steven', 'Davis', 'Steven Davis', 'Midfielder', 'Central Midfielder', 'Centre/Right', 8, 70, 173, '1985-01-01', '2012-07-22', 'Northern Ireland', NULL, NULL, 0),
(470, 17, 78091, 'Gastón', 'Ramírez', 'Gastón Ramírez', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 10, 77, 183, '1990-12-02', '2012-08-31', 'Uruguay', NULL, NULL, 0),
(471, 17, 11848, 'Dean', 'Hammond', 'Dean Hammond', 'Midfielder', 'Central Midfielder', 'Centre', 14, 74, 183, '1983-03-07', '2009-08-19', 'England', NULL, NULL, 0),
(472, 17, 101178, 'James', 'Ward-Prowse', 'James Ward-Prowse', 'Midfielder', 'Central Midfielder', 'Centre', 16, 66, 173, '1994-11-01', '2011-07-01', 'England', NULL, NULL, 0),
(473, 17, 40145, 'Jack', 'Cork', 'Jack Cork', 'Midfielder', 'Central Midfielder', 'Centre', 18, 70, 183, '1989-06-25', '2011-07-07', 'England', NULL, NULL, 0),
(474, 17, 39155, 'Adam', 'Lallana', 'Adam Lallana', 'Midfielder', 'Attacking Midfielder', 'Left/Centre', 20, 73, 173, '1988-05-10', '2006-07-01', 'England', NULL, NULL, 0),
(475, 17, 16091, 'Richard', 'Chaplow', 'Richard Chaplow', 'Midfielder', 'Central Midfielder', 'Centre/Right', 27, 57, 175, '1985-02-02', '2010-09-30', 'England', NULL, NULL, 0),
(476, 17, 101184, 'Calum', 'Chambers', 'Calum Chambers', 'Midfielder', 'Winger', 'Right', 28, 66, 183, '1995-01-20', '2011-07-01', 'England', NULL, NULL, 0),
(477, 17, 53058, 'Steve', 'De Ridder', 'Steve De Ridder', 'Midfielder', 'Winger', 'Left/Right', 33, 73, 179, '1987-02-25', '2011-07-22', 'Belgium', NULL, NULL, 0),
(478, 17, 101183, 'Corby', 'Moore', 'Corby Moore', 'Midfielder', 'Central Midfielder', 'Centre', 34, 64, 173, '1993-11-21', '2011-07-01', 'England', NULL, NULL, 0),
(479, 17, 133639, 'Dominic', 'Gape', 'Dominic Gape', 'Midfielder', 'Central Midfielder', 'Centre', 37, 70, 180, '1994-09-09', '2012-09-11', 'England', NULL, NULL, 0),
(480, 17, 133640, 'Andy', 'Robinson', 'Andy Robinson', 'Midfielder', 'Central Midfielder', 'Centre', 38, 64, 173, '1992-10-16', '2012-09-11', 'England', NULL, NULL, 0),
(481, 17, 101179, 'Lloyd', 'Isgrove', 'Lloyd Isgrove', 'Midfielder', 'Winger', 'Left/Right', 39, 72, 178, '1993-01-12', '2011-07-01', 'Wales', NULL, NULL, 0),
(482, 17, 19197, 'Jason', 'Puncheon', 'Jason Puncheon', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 42, 70, 173, '1986-06-26', '2010-01-29', 'England', NULL, NULL, 0),
(483, 17, 11037, 'Rickie', 'Lambert', 'Rickie Lambert', 'Forward', 'Striker', 'Centre', 7, 77, 188, '1982-02-16', '2009-08-10', 'England', NULL, NULL, 0),
(484, 17, 44683, 'Jay', 'Rodriguez', 'Jay Rodriguez', 'Forward', 'Striker', 'Centre', 9, 80, 185, '1989-07-29', '2012-06-10', 'England', NULL, NULL, 0),
(485, 17, 18867, 'Billy', 'Sharp', 'Billy Sharp', 'Forward', 'Striker', 'Centre', 11, 70, 175, '1986-02-05', '2012-01-30', 'England', NULL, NULL, 0),
(486, 17, 17936, 'Jonathan', 'Forte', 'Jonathan Forte', 'Forward', 'Second Striker', 'Left/Centre/Right', 17, 77, 183, '1986-07-25', '2011-01-31', 'Barbados', NULL, NULL, 0),
(487, 17, 98777, 'Tadanari', 'Lee', 'Tadanari Lee', 'Forward', 'Striker', 'Centre', 19, 73, 182, '1985-12-19', '2012-01-05', 'Japan', NULL, NULL, 0),
(488, 17, 17291, 'Guilherme', 'Do Prado', 'Guly', 'Forward', 'Second Striker', 'Left/Centre/Right', 21, 78, 189, '1981-12-31', '2010-08-23', 'Brazil', NULL, NULL, 0),
(489, 17, 49596, 'Emmanuel', 'Mayuka', 'Emmanuel Mayuka', 'Forward', 'Striker', 'Centre', 24, 75, 178, '1990-11-21', '2012-08-28', 'Zambia', NULL, NULL, 0),
(490, 17, 129024, 'Jake', 'Sinclair', 'Jake Sinclair', 'Forward', 'Striker', 'Centre', 36, 70, 170, '1994-11-29', '2012-08-01', 'England', NULL, NULL, 0),
(491, 19, 105666, 'Jack', 'Butland', 'Jack Butland', 'Goalkeeper', 'Goalkeeper', NULL, NULL, 95, 192, '1993-03-10', '2013-01-31', 'England', NULL, NULL, 0),
(492, 19, 40349, 'Asmir', 'Begovic', 'Asmir Begovic', 'Goalkeeper', 'Goalkeeper', NULL, 1, 83, 198, '1987-06-20', '2010-02-01', 'Bosnia and Herzegovina', NULL, NULL, 0),
(493, 19, 1945, 'Thomas', 'Sørensen', 'Thomas Sørensen', 'Goalkeeper', 'Goalkeeper', NULL, 29, 82, 195, '1976-06-12', '2008-07-30', 'Denmark', NULL, NULL, 0),
(494, 19, 131069, 'Stefan', 'Galinski', 'Stefan Galinski', 'Defender', 'Central Defender', 'Centre', NULL, 78, 185, '1994-04-04', '2012-06-01', 'Poland', NULL, NULL, 0),
(495, 19, 50089, 'Geoff', 'Cameron', 'Geoff Cameron', 'Defender', 'Central Defender', 'Centre', 2, 84, 191, '1985-07-11', '2012-08-08', 'USA', NULL, NULL, 0),
(496, 19, 12413, 'Robert', 'Huth', 'Robert Huth', 'Defender', 'Central Defender', 'Centre/Right', 4, 80, 191, '1984-08-18', '2009-08-27', 'Germany', NULL, NULL, 0),
(497, 19, 32318, 'Marc', 'Wilson', 'Marc Wilson', 'Defender', 'Full Back', 'Left/Right', 12, 80, 188, '1987-08-17', '2010-08-31', 'Republic of Ireland', NULL, NULL, 0),
(498, 19, 37869, 'Ryan', 'Shawcross', 'Ryan Shawcross', 'Defender', 'Central Defender', 'Centre', 17, 76, 191, '1987-10-04', '2007-08-09', 'England', NULL, NULL, 0),
(499, 19, 19714, 'Andy', 'Wilkinson', 'Andy Wilkinson', 'Defender', 'Full Back', 'Right', 28, 69, 180, '1984-08-06', '2001-08-01', 'England', NULL, NULL, 0),
(500, 19, 45158, 'Ryan', 'Shotton', 'Ryan Shotton', 'Defender', 'Central Defender', 'Centre', 30, 85, 191, '1988-10-30', '2007-06-01', 'England', NULL, NULL, 0),
(501, 19, 12150, 'Glenn', 'Whelan', 'Glenn Whelan', 'Midfielder', 'Central Midfielder', 'Centre', 6, 79, 180, '1984-01-13', '2008-01-31', 'Republic of Ireland', NULL, NULL, 0),
(502, 19, 3658, 'Jermaine', 'Pennant', 'Jermaine Pennant', 'Midfielder', 'Winger', 'Right', 7, 64, 173, '1983-01-15', '2010-08-31', 'England', NULL, NULL, 0),
(503, 19, 34296, 'Wilson', 'Palacios', 'Wilson Palacios', 'Midfielder', 'Defensive Midfielder', 'Centre', 8, 71, 178, '1984-07-29', '2011-08-31', 'Honduras', NULL, NULL, 0),
(504, 19, 41587, 'Maurice', 'Edu', 'Maurice Edu', 'Midfielder', 'Central Midfielder', 'Centre', 13, 77, 183, '1986-04-18', '2012-08-31', 'USA', NULL, NULL, 0),
(505, 19, 59305, 'Jamie', 'Ness', 'Jamie Ness', 'Midfielder', 'Central Midfielder', 'Centre', 14, 70, 177, '1991-03-02', '2012-07-11', 'Scotland', NULL, NULL, 0),
(506, 19, 49323, 'Steven', 'N''Zonzi', 'Steven N''Zonzi', 'Midfielder', 'Defensive Midfielder', 'Centre', 15, 75, 190, '1988-12-15', '2012-08-31', 'France', NULL, NULL, 0),
(507, 19, 20208, 'Charlie', 'Adam', 'Charlie Adam', 'Midfielder', 'Central Midfielder', 'Centre', 16, 83, 185, '1985-12-10', '2012-08-31', 'Scotland', NULL, NULL, 0),
(508, 19, 15944, 'Michael', 'Kightly', 'Michael Kightly', 'Midfielder', 'Winger', 'Left/Right', 21, 63, 175, '1986-01-24', '2012-08-09', 'England', NULL, NULL, 0),
(509, 19, 50067, 'Brek', 'Shea', 'Brek Shea', 'Midfielder', 'Winger', 'Left', 22, 82, 191, '1990-02-28', '2013-01-31', 'USA', NULL, NULL, 0),
(510, 19, 2570, 'Matthew', 'Etherington', 'Matthew Etherington', 'Midfielder', 'Winger', 'Left', 26, 67, 178, '1981-08-14', '2009-01-09', 'England', NULL, NULL, 0),
(511, 19, 84489, 'Florent', 'Cuvelier', 'Florent Cuvelier', 'Midfielder', 'Defensive Midfielder', 'Centre', 38, 72, 182, '1992-09-12', '2010-07-08', 'Belgium', NULL, NULL, 0),
(512, 19, 109966, 'Lucas', 'Dawson', 'Lucas Dawson', 'Midfielder', 'Winger', 'Left/Right', 44, 75, 186, '1993-11-12', '2011-12-01', 'England', NULL, NULL, 0),
(513, 19, 18215, 'Kenwyne', 'Jones', 'Kenwyne Jones', 'Forward', 'Striker', 'Centre', 9, 84, 188, '1984-10-05', '2010-08-11', 'Trinidad & Tobago', NULL, NULL, 0),
(514, 19, 12813, 'Jonathan', 'Walters', 'Jonathan Walters', 'Forward', 'Winger', 'Right', 19, 79, 183, '1983-09-20', '2010-08-18', 'Republic of Ireland', NULL, NULL, 0),
(515, 19, 3773, 'Peter', 'Crouch', 'Peter Crouch', 'Forward', 'Striker', 'Centre', 25, 75, 201, '1981-01-30', '2011-08-31', 'England', NULL, NULL, 0),
(516, 19, 18804, 'Cameron', 'Jerome', 'Cameron Jerome', 'Forward', 'Striker', 'Centre', 33, 85, 185, '1986-10-14', '2011-08-31', 'England', NULL, NULL, 0),
(517, 18, 114004, 'Lewis', 'King', 'Lewis King', 'Goalkeeper', 'Goalkeeper', NULL, NULL, NULL, NULL, '1993-05-08', '2012-01-01', 'England', NULL, NULL, 0),
(518, 18, 111234, 'Jordan', 'Pickford', 'Jordan Pickford', 'Goalkeeper', 'Goalkeeper', NULL, 13, 77, 185, '1994-03-07', '2012-08-31', 'England', NULL, NULL, 0),
(519, 18, 20531, 'Keiren', 'Westwood', 'Keiren Westwood', 'Goalkeeper', 'Goalkeeper', NULL, 20, 86, 188, '1984-10-23', '2011-07-01', 'Republic of Ireland', NULL, NULL, 0),
(520, 18, 17997, 'Phillip', 'Bardsley', 'Phillip Bardsley', 'Defender', 'Full Back', 'Right', 2, 74, 180, '1985-06-28', '2008-01-22', 'Scotland', NULL, NULL, 0),
(521, 18, 1841, 'Wes', 'Brown', 'Wes Brown', 'Defender', 'Central Defender', 'Centre/Right', 5, 77, 185, '1979-10-13', '2011-07-07', 'England', NULL, NULL, 0),
(522, 18, 3736, 'John', 'O''Shea', 'John O''Shea', 'Defender', 'Central Defender', 'Left/Centre/Right', 16, 75, 191, '1981-04-30', '2011-07-07', 'Republic of Ireland', NULL, NULL, 0),
(523, 18, 17468, 'Carlos', 'Cuéllar', 'Carlos Cuéllar', 'Defender', 'Central Defender', 'Centre', 24, 84, 190, '1981-08-23', '2012-07-02', 'Spain', NULL, NULL, 0),
(524, 18, 113994, 'Liam', 'Marrs', 'Liam Marrs', 'Defender', 'Full Back', 'Right', 29, 72, 175, '1992-12-15', '2013-04-01', 'England', NULL, NULL, 0),
(525, 18, 111352, 'David', 'Ferguson', 'David Ferguson', 'Defender', NULL, NULL, 35, NULL, NULL, '1994-06-07', '2013-05-01', 'England', NULL, NULL, 0),
(526, 18, 107541, 'Scott', 'Harrison', 'Scott Harrison', 'Defender', 'Central Defender', 'Centre', 36, NULL, NULL, '1993-09-03', '2013-05-01', 'England', NULL, NULL, 0),
(527, 18, 97580, 'Louis', 'Laing', 'Louis Laing', 'Defender', 'Central Defender', 'Centre', 40, 76, 181, '1993-03-06', '2011-05-13', 'England', NULL, NULL, 0),
(528, 18, 108416, 'John', 'Egan', 'John Egan', 'Defender', 'Full Back', 'Left/Right', 42, 75, 185, '1992-10-20', '2011-11-04', 'Republic of Ireland', NULL, NULL, 0),
(529, 18, 49688, 'Alfred', 'N''Diaye', 'Alfred N''Diaye', 'Midfielder', 'Defensive Midfielder', 'Centre', 4, 80, 186, '1990-03-06', '2013-01-09', 'France', NULL, NULL, 0),
(530, 18, 28448, 'Lee', 'Cattermole', 'Lee Cattermole', 'Midfielder', 'Defensive Midfielder', 'Centre', 6, 76, 178, '1988-03-21', '2009-08-12', 'England', NULL, NULL, 0),
(531, 18, 19057, 'Sebastian', 'Larsson', 'Sebastian Larsson', 'Midfielder', 'Central Midfielder', 'Centre/Right', 7, 70, 178, '1985-06-06', '2011-07-01', 'Sweden', NULL, NULL, 0),
(532, 18, 28468, 'Craig', 'Gardner', 'Craig Gardner', 'Midfielder', 'Central Midfielder', 'Centre', 8, 76, 178, '1986-11-25', '2011-07-01', 'England', NULL, NULL, 0),
(533, 18, 58771, 'Jack', 'Colback', 'Jack Colback', 'Midfielder', 'Central Midfielder', 'Centre', 14, 70, 176, '1989-10-24', '2008-11-20', 'England', NULL, NULL, 0),
(534, 18, 7631, 'David', 'Vaughan', 'David Vaughan', 'Midfielder', 'Central Midfielder', 'Left/Centre', 15, 66, 170, '1983-02-18', '2011-07-08', 'Wales', NULL, NULL, 0),
(535, 18, 19959, 'Adam', 'Johnson', 'Adam Johnson', 'Midfielder', 'Winger', 'Left/Right', 21, 63, 175, '1987-07-14', '2012-08-24', 'England', NULL, NULL, 0),
(536, 18, 63370, 'James', 'McClean', 'James McClean', 'Midfielder', 'Winger', 'Left', 23, 68, 175, '1989-04-22', '2011-08-09', 'Republic of Ireland', NULL, NULL, 0),
(537, 18, 37339, 'Ahmed', 'Elmohamady', 'Ahmed Elmohamady', 'Midfielder', 'Winger', 'Right', 27, 81, 180, '1987-09-09', '2010-07-02', 'Egypt', NULL, NULL, 0),
(538, 18, 34392, 'Stéphane', 'Sessegnon', 'Stéphane Sessegnon', 'Midfielder', 'Attacking Midfielder', 'Centre/Right', 28, 72, 172, '1984-06-01', '2011-01-29', 'Benin', NULL, NULL, 0),
(539, 18, 94738, 'Billy', 'Knott', 'Billy Knott', 'Midfielder', 'Attacking Midfielder', 'Centre', 34, 71, 173, '1992-11-28', '2011-02-24', 'England', NULL, NULL, 0),
(540, 18, 94863, 'Adam', 'Mitchell', 'Adam Mitchell', 'Midfielder', NULL, NULL, 37, NULL, NULL, '1994-12-02', '2013-05-01', 'England', NULL, NULL, 0),
(541, 18, 106300, 'David', 'Moberg-Karlsson', 'David Moberg-Karlsson', 'Forward', 'Striker', 'Centre', NULL, 71, 176, '1994-04-20', '2013-06-19', 'Sweden', NULL, NULL, 0),
(542, 18, 15398, 'Danny', 'Graham', 'Danny Graham', 'Forward', 'Striker', 'Centre', 9, 87, 183, '1985-08-12', '2013-01-31', 'England', NULL, NULL, 0),
(543, 18, 59125, 'Connor', 'Wickham', 'Connor Wickham', 'Forward', 'Striker', 'Centre', 10, 73, 191, '1993-03-31', '2011-06-29', 'England', NULL, NULL, 0),
(544, 18, 18953, 'Steven', 'Fletcher', 'Steven Fletcher', 'Forward', 'Striker', 'Centre', 26, 76, 186, '1987-03-26', '2012-08-24', 'Scotland', NULL, NULL, 0),
(545, 18, 114312, 'Mikael', 'Mandron', 'Mikael Mandron', 'Forward', 'Striker', 'Centre', 30, 82, 191, '1994-10-11', '2013-03-01', 'France', NULL, NULL, 0),
(546, 18, 113999, 'Jordan', 'Laidler', 'Jordan Laidler', 'Forward', NULL, NULL, 32, NULL, NULL, '1995-07-01', '2013-04-01', 'England', NULL, NULL, 0),
(547, 18, 96114, 'Craig', 'Lynch', 'Craig Lynch', 'Forward', 'Striker', 'Centre', 38, 64, 175, '1992-03-25', '2011-04-03', 'England', NULL, NULL, 0),
(548, 22, 39215, 'Michel', 'Vorm', 'Michel Vorm', 'Goalkeeper', 'Goalkeeper', NULL, 1, 84, 183, '1983-10-20', '2011-08-10', 'Netherlands', NULL, NULL, 0),
(549, 22, 60084, 'David', 'Cornell', 'David Cornell', 'Goalkeeper', 'Goalkeeper', NULL, 13, 73, 180, '1991-03-28', '2009-02-01', 'Wales', NULL, NULL, 0),
(550, 22, 5288, 'Gerhard', 'Tremmel', 'Gerhard Tremmel', 'Goalkeeper', 'Goalkeeper', NULL, 25, 86, 190, '1978-11-16', '2011-08-31', 'Germany', NULL, NULL, 0),
(551, 22, 59940, 'Kyle', 'Bartley', 'Kyle Bartley', 'Defender', 'Central Defender', 'Centre', 2, 76, 185, '1991-05-22', '2012-08-16', 'England', NULL, NULL, 0),
(552, 22, 47390, 'Neil', 'Taylor', 'Neil Taylor', 'Defender', 'Full Back', 'Left', 3, 65, 175, '1989-02-07', '2010-06-30', 'Wales', NULL, NULL, 0),
(553, 22, 53138, 'José Manuel', 'Flores Moreno', 'Chico', 'Defender', 'Central Defender', 'Centre/Right', 4, 77, 187, '1987-03-06', '2012-07-10', 'Spain', NULL, NULL, 0),
(554, 22, 11883, 'Alan', 'Tate', 'Alan Tate', 'Defender', 'Central Defender', 'Centre', 5, 85, 185, '1982-09-02', '2004-02-06', 'England', NULL, NULL, 0),
(555, 22, 19159, 'Ashley', 'Williams', 'Ashley Williams', 'Defender', 'Central Defender', 'Centre', 6, 77, 183, '1984-08-23', '2008-03-27', 'Wales', NULL, NULL, 0),
(556, 22, 1950, 'Garry', 'Monk', 'Garry Monk', 'Defender', 'Central Defender', 'Centre', 16, 81, 183, '1979-03-06', '2004-01-01', 'England', NULL, NULL, 0),
(557, 22, 39221, 'Dwight', 'Tiendalli', 'Dwight Tiendalli', 'Defender', 'Full Back', 'Left', 21, 69, 180, '1985-10-21', '2012-09-10', 'Netherlands', NULL, NULL, 0),
(558, 22, 42996, 'Angel', 'Rangel', 'Angel Rangel', 'Defender', 'Full Back', 'Left', 22, 84, 188, '1982-10-28', '2007-06-29', 'Spain', NULL, NULL, 0),
(559, 22, 80598, 'Darnel', 'Situ', 'Darnel Situ', 'Defender', 'Central Defender', 'Centre', 23, 77, 187, '1992-03-18', '2012-01-10', 'France', NULL, NULL, 0),
(560, 22, 73983, 'Curtis', 'Obeng', 'Curtis Obeng', 'Defender', 'Full Back', 'Right', 28, 67, 173, '1989-02-14', '2012-01-31', 'England', NULL, NULL, 0),
(561, 22, 115556, 'Ben', 'Davies', 'Ben Davies', 'Defender', 'Full Back', 'Left', 33, 67, 170, '1993-04-24', '2012-04-24', 'Wales', NULL, NULL, 0),
(562, 22, 84933, 'Daniel', 'Alfei', 'Daniel Alfei', 'Defender', 'Central Midfielder', 'Centre', 35, 77, 180, '1992-02-23', '2010-07-01', 'Wales', NULL, NULL, 0),
(563, 22, 15114, 'Leon', 'Britton', 'Leon Britton', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 7, 64, 168, '1982-09-16', '2011-01-20', 'England', NULL, NULL, 0),
(564, 22, 54322, 'Miguel', 'Pérez Cuesta', 'Michu', 'Midfielder', 'Attacking Midfielder', 'Left/Centre', 9, 76, 191, '1986-03-21', '2012-07-20', 'Spain', NULL, NULL, 0),
(565, 22, 28690, 'Pablo', 'Hernández', 'Pablo Hernández', 'Midfielder', 'Winger', 'Left/Right', 11, 64, 173, '1985-04-11', '2012-08-31', 'Spain', NULL, NULL, 0),
(566, 22, 21083, 'Nathan', 'Dyer', 'Nathan Dyer', 'Midfielder', 'Winger', 'Right', 12, 60, 165, '1987-11-29', '2009-01-05', 'England', NULL, NULL, 0),
(567, 22, 18681, 'Roland', 'Lamah', 'Roland Lamah', 'Midfielder', 'Winger', 'Left', 14, 71, 180, '1987-12-31', '2013-01-15', 'Belgium', NULL, NULL, 0),
(568, 22, 11829, 'Wayne', 'Routledge', 'Wayne Routledge', 'Midfielder', 'Winger', 'Right', 15, 64, 170, '1985-01-07', '2011-08-04', 'England', NULL, NULL, 0),
(569, 22, 76542, 'Sung-Yueng', 'Ki', 'Ki Sung-Yueng', 'Midfielder', 'Central Midfielder', 'Centre', 24, 79, 187, '1989-04-24', '2012-08-24', 'South Korea', NULL, NULL, 0),
(570, 22, 39217, 'Kemy', 'Agustien', 'Kemy Agustien', 'Midfielder', 'Central Midfielder', 'Centre', 26, 75, 175, '1986-08-20', '2010-10-07', 'Netherlands', NULL, NULL, 0),
(571, 22, 73459, 'Ashley', 'Richards', 'Ashley Richards', 'Midfielder', 'Winger', 'Right', 29, 78, 185, '1991-04-12', '2009-07-01', 'Wales', NULL, NULL, 0),
(572, 22, 82055, 'Lee', 'Lucas', 'Lee Lucas', 'Midfielder', 'Central Midfielder', 'Centre', 31, 74, 180, '1992-06-10', '2009-06-01', 'Wales', NULL, NULL, 0),
(573, 22, 115557, 'Gwion', 'Edwards', 'Gwion Edwards', 'Midfielder', 'Central Midfielder', 'Centre', 38, 76, 175, '1993-03-01', '2012-03-01', 'Wales', NULL, NULL, 0),
(574, 22, 113693, 'Kurtis', 'March', 'Kurtis March', 'Midfielder', 'Central Midfielder', 'Centre', 39, 71, 175, '1993-03-30', '2011-07-01', 'Wales', NULL, NULL, 0),
(575, 22, 14166, 'Leroy', 'Lita', 'Leroy Lita', 'Forward', 'Striker', 'Centre', 18, 75, 170, '1984-12-28', '2011-07-29', 'England', NULL, NULL, 0),
(576, 22, 15428, 'Luke', 'Moore', 'Luke Moore', 'Forward', 'Striker', 'Centre', 19, 75, 180, '1986-02-13', '2011-01-07', 'England', NULL, NULL, 0),
(577, 22, 89505, 'Rory', 'Donnelly', 'Rory Donnelly', 'Forward', 'Striker', 'Centre', 41, 81, 188, '1992-02-18', '2012-01-10', 'Northern Ireland', NULL, NULL, 0),
(578, 11, 1803, 'Brad', 'Friedel', 'Brad Friedel', 'Goalkeeper', 'Goalkeeper', NULL, 24, 92, 191, '1971-05-18', '2011-07-01', 'USA', NULL, NULL, 0),
(579, 11, 37915, 'Hugo', 'Lloris', 'Hugo Lloris', 'Goalkeeper', 'Goalkeeper', NULL, 25, 73, 188, '1986-12-26', '2012-08-31', 'France', NULL, NULL, 0),
(580, 11, 107084, 'Jordan', 'Archer', 'Jordan Archer', 'Goalkeeper', 'Goalkeeper', NULL, 57, 80, 180, '1993-04-12', '2012-07-25', 'Scotland', NULL, NULL, 0),
(581, 11, 37742, 'Younes', 'Kaboul', 'Younes Kaboul', 'Defender', 'Central Defender', 'Centre/Right', 4, 75, 182, '1986-01-04', '2010-01-30', 'France', NULL, NULL, 0),
(582, 11, 39194, 'Jan', 'Vertonghen', 'Jan Vertonghen', 'Defender', 'Central Defender', 'Centre', 5, 86, 189, '1987-04-24', '2012-07-12', 'Belgium', NULL, NULL, 0),
(583, 11, 49539, 'Kyle', 'Naughton', 'Kyle Naughton', 'Defender', 'Full Back', 'Left/Right', 16, 73, 175, '1988-11-11', '2009-07-21', 'England', NULL, NULL, 0),
(584, 11, 12679, 'Michael', 'Dawson', 'Michael Dawson', 'Defender', 'Central Defender', 'Centre', 20, 76, 188, '1983-11-18', '2005-01-31', 'England', NULL, NULL, 0),
(585, 11, 58621, 'Kyle', 'Walker', 'Kyle Walker', 'Defender', 'Full Back', 'Right', 28, 70, 178, '1990-05-28', '2009-07-22', 'England', NULL, NULL, 0),
(586, 11, 28146, 'Benoit', 'Assou-Ekotto', 'Benoit Assou-Ekotto', 'Defender', 'Full Back', 'Left', 32, 69, 178, '1984-03-24', '2006-06-09', 'Cameroon', NULL, NULL, 0),
(587, 11, 68815, 'Steven', 'Caulker', 'Steven Caulker', 'Defender', 'Central Defender', 'Centre', 33, 76, 191, '1991-12-29', '2009-07-01', 'England', NULL, NULL, 0),
(588, 11, 54469, 'Adam', 'Smith', 'Adam Smith', 'Defender', 'Full Back', 'Right', 39, 74, 178, '1991-04-29', '2012-07-25', 'England', NULL, NULL, 0),
(589, 11, 106449, 'Kevin', 'Stewart', 'Kevin Stewart', 'Defender', 'Full Back', 'Left', 59, NULL, NULL, '1993-09-07', '2011-09-01', 'England', NULL, NULL, 0),
(590, 11, 106603, 'Ezekiel', 'Fryers', 'Ezekiel Fryers', 'Defender', 'Full Back', 'Left', 62, 75, 183, '1992-09-09', '2013-01-03', 'England', NULL, NULL, 0),
(591, 11, 38290, 'Danny', 'Rose', 'Danny Rose', 'Midfielder', 'Winger', 'Left', 3, 72, 173, '1990-07-02', '2007-07-26', 'England', NULL, NULL, 0),
(592, 11, 15109, 'Tom', 'Huddlestone', 'Tom Huddlestone', 'Midfielder', 'Central Midfielder', 'Centre', 6, 80, 180, '1986-12-28', '2005-07-01', 'England', NULL, NULL, 0),
(593, 11, 17349, 'Aaron', 'Lennon', 'Aaron Lennon', 'Midfielder', 'Winger', 'Left/Right', 7, 63, 165, '1987-04-16', '2005-07-01', 'England', NULL, NULL, 0),
(594, 11, 1411, 'Scott', 'Parker', 'Scott Parker', 'Midfielder', 'Central Midfielder', 'Centre', 8, 67, 170, '1980-10-13', '2011-08-31', 'England', NULL, NULL, 0),
(595, 11, 36903, 'Gareth', 'Bale', 'Gareth Bale', 'Midfielder', 'Winger', 'Left', 11, 74, 183, '1989-07-16', '2007-05-25', 'Wales', NULL, NULL, 0),
(596, 11, 39104, 'Mousa', 'Dembélé', 'Mousa Dembélé', 'Midfielder', 'Central Midfielder', 'Left/Centre', 19, 82, 185, '1987-07-16', '2012-08-29', 'Belgium', NULL, NULL, 0),
(597, 11, 55422, 'Gylfi', 'Sigurdsson', 'Gylfi Sigurdsson', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 22, 79, 186, '1989-09-09', '2012-07-04', 'Iceland', NULL, NULL, 0),
(598, 11, 49318, 'Lewis', 'Holtby', 'Lewis Holtby', 'Midfielder', 'Attacking Midfielder', 'Centre/Right', 23, 72, 176, '1990-09-18', '2013-01-28', 'Germany', NULL, NULL, 0),
(599, 11, 49944, 'Jake', 'Livermore', 'Jake Livermore', 'Midfielder', 'Central Midfielder', 'Centre', 29, 76, 180, '1989-11-14', '2006-06-01', 'England', NULL, NULL, 0),
(600, 11, 52876, 'Sandro Ranieri', 'Guimarães Cordeiro', 'Sandro', 'Midfielder', 'Defensive Midfielder', 'Centre', 30, 75, 187, '1989-03-15', '2010-06-01', 'Brazil', NULL, NULL, 0),
(601, 11, 60252, 'Andros', 'Townsend', 'Andros Townsend', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 31, 81, 181, '1991-07-16', '2009-02-25', 'England', NULL, NULL, 0),
(602, 11, 48759, 'Simon', 'Dawkins', 'Simon Dawkins', 'Midfielder', 'Central Midfielder', 'Left/Centre/Right', 36, 73, 178, '1987-12-01', '2007-07-01', 'England', NULL, NULL, 0),
(603, 11, 48897, 'John', 'Bostock', 'John Bostock', 'Midfielder', 'Central Midfielder', 'Centre', 41, 75, 179, '1992-01-15', '2008-07-09', 'England', NULL, NULL, 0),
(604, 11, 81012, 'Ryan', 'Fredericks', 'Ryan Fredericks', 'Midfielder', 'Central Midfielder', 'Centre', 43, 80, 190, '1992-10-10', '2010-01-30', 'England', NULL, NULL, 0),
(605, 11, 101623, 'Cristian', 'Ceballos', 'Cristian Ceballos', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 44, 77, 182, '1992-12-03', '2011-07-01', 'Spain', NULL, NULL, 0),
(606, 11, 93464, 'Thomas', 'Carroll', 'Thomas Carroll', 'Midfielder', 'Central Midfielder', 'Centre', 46, 71, 177, '1992-05-28', '2011-08-24', 'England', NULL, NULL, 0),
(607, 11, 106606, 'Massimo', 'Luongo', 'Massimo Luongo', 'Midfielder', 'Central Midfielder', 'Centre', 48, 66, 176, '1992-09-25', '2011-09-01', 'Australia', NULL, NULL, 0),
(608, 11, 20226, 'Clint', 'Dempsey', 'Clint Dempsey', 'Forward', 'Second Striker', 'Centre', 2, 77, 185, '1983-03-09', '2012-08-31', 'USA', NULL, NULL, 0),
(609, 11, 17500, 'Emmanuel', 'Adebayor', 'Emmanuel Adebayor', 'Forward', 'Striker', 'Centre', 10, 82, 191, '1984-02-26', '2012-08-21', 'Togo', NULL, NULL, 0),
(610, 11, 7958, 'Jermain', 'Defoe', 'Jermain Defoe', 'Forward', 'Striker', 'Centre', 18, 65, 170, '1982-10-07', '2009-01-09', 'England', NULL, NULL, 0),
(611, 11, 78830, 'Harry', 'Kane', 'Harry Kane', 'Forward', 'Striker', 'Centre', 37, 65, 183, '1993-07-28', '2009-07-01', 'England', NULL, NULL, 0),
(612, 11, 156289, 'Shaquile', 'Coulthirst', 'Shaquile Coulthirst', 'Forward', 'Striker', 'Centre', 41, NULL, NULL, '1994-02-11', '2013-03-10', 'England', NULL, NULL, 0),
(613, 11, 58790, 'Jonathan', 'Obika', 'Jonathan Obika', 'Forward', 'Striker', 'Centre', 45, 72, 173, '1990-09-12', '2008-08-01', 'England', NULL, NULL, 0),
(614, 11, 95703, 'Cameron', 'Lancaster', 'Cameron Lancaster', 'Forward', 'Striker', 'Centre', 53, 74, 184, '1992-11-05', '2011-10-01', 'England', NULL, NULL, 0),
(615, 15, 9089, 'Ben', 'Foster', 'Ben Foster', 'Goalkeeper', 'Goalkeeper', NULL, 1, 79, 188, '1983-04-03', '2011-07-29', 'England', NULL, NULL, 0),
(616, 15, 12086, 'Boaz', 'Myhill', 'Boaz Myhill', 'Goalkeeper', 'Goalkeeper', NULL, 13, 91, 191, '1982-11-09', '2010-07-30', 'Wales', NULL, NULL, 0),
(617, 15, 38219, 'Luke', 'Daniels', 'Luke Daniels', 'Goalkeeper', 'Goalkeeper', NULL, 19, 81, 185, '1988-01-05', '2002-08-01', 'England', NULL, NULL, 0),
(618, 15, 3332, 'Steven', 'Reid', 'Steven Reid', 'Defender', 'Full Back', 'Right', 2, 75, 180, '1981-03-10', '2010-05-26', 'Republic of Ireland', NULL, NULL, 0),
(619, 15, 39253, 'Jonas', 'Olsson', 'Jonas Olsson', 'Defender', 'Central Defender', 'Centre', 3, 85, 195, '1983-03-10', '2008-09-01', 'Sweden', NULL, NULL, 0);
INSERT INTO `player` (`id`, `team_id`, `feeder_id`, `name`, `surname`, `display_name`, `position`, `real_position`, `real_position_side`, `shirt_number`, `weight`, `height`, `birth_date`, `join_date`, `country`, `image_path`, `background_image_path`, `is_blocked`) VALUES
(620, 15, 32388, 'Goran', 'Popov', 'Goran Popov', 'Defender', 'Full Back', 'Left', 4, 89, 189, '1984-10-02', '2012-08-31', 'Macedonia', NULL, NULL, 0),
(621, 15, 14278, 'Liam', 'Ridgewell', 'Liam Ridgewell', 'Defender', 'Central Defender', 'Left/Centre', 6, 78, 188, '1984-07-21', '2012-01-31', 'England', NULL, NULL, 0),
(622, 15, 37269, 'Gonzalo', 'Jara', 'Gonzalo Jara', 'Defender', 'Full Back', 'Left', 18, 77, 177, '1985-08-29', '2009-08-25', 'Chile', NULL, NULL, 0),
(623, 15, 19272, 'Gareth', 'McAuley', 'Gareth McAuley', 'Defender', 'Central Defender', 'Centre', 23, 70, 191, '1979-12-05', '2011-05-23', 'Northern Ireland', NULL, NULL, 0),
(624, 15, 60232, 'Craig', 'Dawson', 'Craig Dawson', 'Defender', 'Central Defender', 'Centre', 25, 78, 188, '1990-05-06', '2010-08-31', 'England', NULL, NULL, 0),
(625, 15, 11467, 'Billy', 'Jones', 'Billy Jones', 'Defender', 'Full Back', 'Right', 28, 77, 183, '1987-03-24', '2011-06-03', 'England', NULL, NULL, 0),
(626, 15, 27348, 'Gabriel', 'Tamas', 'Gabriel Tamas', 'Defender', 'Central Defender', 'Centre', 30, 79, 188, '1983-11-09', '2010-01-01', 'Romania', NULL, NULL, 0),
(627, 15, 114727, 'Liam', 'O''Neil', 'Liam O''Neil', 'Defender', 'Central Defender', 'Centre', 40, 80, 180, '1993-07-31', '2011-07-01', 'Wales', NULL, NULL, 0),
(628, 15, 115559, 'Cameron', 'Gayle', 'Cameron Gayle', 'Defender', 'Central Defender', 'Centre', 41, 70, 181, '1992-11-22', '2011-07-01', 'England', NULL, NULL, 0),
(629, 15, 146339, 'Donervon', 'Daniels', 'Donervon Daniels', 'Defender', NULL, NULL, 42, 91, 186, '1993-11-24', '2013-04-01', 'Montserrat', NULL, NULL, 0),
(630, 15, 55829, 'Claudio', 'Yacob', 'Claudio Yacob', 'Midfielder', 'Defensive Midfielder', 'Centre', 5, 77, 181, '1987-07-18', '2012-07-24', 'Argentina', NULL, NULL, 0),
(631, 15, 18008, 'James', 'Morrison', 'James Morrison', 'Midfielder', 'Winger', 'Right', 7, 64, 178, '1986-05-25', '2007-08-07', 'Scotland', NULL, NULL, 0),
(632, 15, 19151, 'Chris', 'Brunt', 'Chris Brunt', 'Midfielder', 'Attacking Midfielder', 'Left', 11, 84, 185, '1984-12-14', '2007-08-15', 'Northern Ireland', NULL, NULL, 0),
(633, 15, 12765, 'Jerome', 'Thomas', 'Jerome Thomas', 'Midfielder', 'Winger', 'Left/Right', 14, 79, 178, '1983-03-23', '2009-08-14', 'England', NULL, NULL, 0),
(634, 15, 79843, 'George', 'Thorne', 'George Thorne', 'Midfielder', 'Attacking Midfielder', 'Centre', 15, 83, 188, '1993-01-04', '2009-11-27', 'England', NULL, NULL, 0),
(635, 15, 84508, 'Scott', 'Allan', 'Scott Allan', 'Midfielder', 'Central Midfielder', 'Centre', 16, 70, 175, '1991-11-28', '2012-01-09', 'Scotland', NULL, NULL, 0),
(636, 15, 20141, 'Graham', 'Dorrans', 'Graham Dorrans', 'Midfielder', 'Central Midfielder', 'Centre', 17, 65, 175, '1987-05-05', '2008-07-04', 'Scotland', NULL, NULL, 0),
(637, 15, 39895, 'Youssuf', 'Mulumbu', 'Youssuf Mulumbu', 'Midfielder', 'Defensive Midfielder', 'Centre', 21, 65, 177, '1987-01-25', '2009-06-10', 'DR Congo', NULL, NULL, 0),
(638, 15, 98760, 'Kemar', 'Roofe', 'Kemar Roofe', 'Midfielder', 'Winger', 'Left/Right', 34, 71, 178, '1993-01-06', '2011-08-18', 'England', NULL, NULL, 0),
(639, 15, 81132, 'Kayleden', 'Brown', 'Kayleden Brown', 'Midfielder', 'Winger', 'Left', 37, 80, 187, '1992-04-15', '2010-02-12', 'Wales', NULL, NULL, 0),
(640, 15, 112516, 'Isaiah', 'Brown', 'Isaiah Brown', 'Midfielder', NULL, NULL, 43, 70, 182, '1997-01-07', '2013-03-01', 'England', NULL, NULL, 0),
(641, 15, 26806, 'Markus', 'Rosenberg', 'Markus Rosenberg', 'Forward', 'Striker', 'Centre', 8, 80, 185, '1982-09-27', '2012-08-07', 'Sweden', NULL, NULL, 0),
(642, 15, 20452, 'Shane', 'Long', 'Shane Long', 'Forward', 'Striker', 'Centre', 9, 70, 178, '1987-01-22', '2011-08-09', 'Republic of Ireland', NULL, NULL, 0),
(643, 15, 26900, 'Peter', 'Odemwingie', 'Peter Odemwingie', 'Forward', 'Striker', 'Centre', 24, 75, 182, '1981-07-15', '2010-08-20', 'Nigeria', NULL, NULL, 0),
(644, 15, 151069, 'Adil', 'Nabi', 'Adil Nabi', 'Forward', NULL, NULL, 36, 68, 172, '1994-02-28', '2013-01-01', 'England', NULL, NULL, 0),
(645, 15, 91972, 'Saido', 'Berahino', 'Saido Berahino', 'Forward', 'Striker', 'Centre', 38, 77, 178, '1993-08-04', '2010-12-01', 'England', NULL, NULL, 0),
(646, 16, 42525, 'Stephen', 'Henderson', 'Stephen Henderson', 'Goalkeeper', 'Goalkeeper', NULL, 13, 87, 188, '1988-05-02', '2012-03-16', 'Republic of Ireland', NULL, NULL, 0),
(647, 16, 1344, 'Jussi', 'Jääskeläinen', 'Jussi Jääskeläinen', 'Goalkeeper', 'Goalkeeper', NULL, 22, 81, 191, '1975-04-19', '2012-07-01', 'Finland', NULL, NULL, 0),
(648, 16, 82257, 'Raphael', 'Spiegel', 'Raphael Spiegel', 'Goalkeeper', 'Goalkeeper', NULL, 30, 89, 197, '1992-12-19', '2012-07-23', 'Switzerland', NULL, NULL, 0),
(649, 16, 48717, 'Winston', 'Reid', 'Winston Reid', 'Defender', 'Central Defender', 'Centre/Right', 2, 87, 190, '1988-07-03', '2010-08-05', 'New Zealand', NULL, NULL, 0),
(650, 16, 5609, 'George', 'McCartney', 'George McCartney', 'Defender', 'Full Back', 'Left/Centre', 3, 69, 180, '1981-04-29', '2012-07-01', 'Northern Ireland', NULL, NULL, 0),
(651, 16, 49413, 'James', 'Tomkins', 'James Tomkins', 'Defender', 'Central Defender', 'Centre', 5, 74, 191, '1989-03-29', '2007-12-01', 'England', NULL, NULL, 0),
(652, 16, 19575, 'Joey', 'O''Brien', 'Joey O''Brien', 'Defender', 'Full Back', 'Right', 17, 70, 180, '1986-02-17', '2011-07-30', 'Republic of Ireland', NULL, NULL, 0),
(653, 16, 10709, 'Emanuel', 'Pogatetz', 'Emanuel Pogatetz', 'Defender', 'Central Defender', 'Centre', 18, 90, 191, '1983-01-16', '2013-01-28', 'Austria', NULL, NULL, 0),
(654, 16, 8380, 'James', 'Collins', 'James Collins', 'Defender', 'Central Defender', 'Centre', 19, 83, 188, '1983-08-23', '2012-08-01', 'Wales', NULL, NULL, 0),
(655, 16, 10356, 'Guy', 'Demel', 'Guy Demel', 'Defender', 'Full Back', 'Right', 20, 88, 188, '1981-06-13', '2011-08-31', 'Côte d''Ivoire', NULL, NULL, 0),
(656, 16, 51933, 'Jordan', 'Spence', 'Jordan Spence', 'Defender', 'Full Back', 'Right', 27, 72, 189, '1990-05-24', '2008-08-01', 'England', NULL, NULL, 0),
(657, 16, 108411, 'Daniel', 'Potts', 'Daniel Potts', 'Defender', 'Central Defender', 'Left/Centre', 33, 70, 172, '1994-04-13', '2011-11-01', 'England', NULL, NULL, 0),
(658, 16, 110140, 'Callum', 'Driver', 'Callum Driver', 'Defender', 'Full Back', 'Right', 41, 72, 174, '1992-10-23', '2011-12-16', 'England', NULL, NULL, 0),
(659, 16, 137951, 'Leo', 'Chambers', 'Leo Chambers', 'Defender', 'Central Defender', 'Centre', 44, 83, 186, '1995-08-05', '2012-10-11', 'England', NULL, NULL, 0),
(660, 16, 90518, 'Ravel', 'Morrison', 'Ravel Morrison', 'Midfielder', 'Attacking Midfielder', 'Centre', NULL, 71, 175, '1993-02-02', '2012-01-31', 'England', NULL, NULL, 0),
(661, 16, 5306, 'Kevin', 'Nolan', 'Kevin Nolan', 'Midfielder', 'Attacking Midfielder', 'Centre', 4, 85, 183, '1982-06-24', '2011-06-16', 'England', NULL, NULL, 0),
(662, 16, 18818, 'Matthew', 'Jarvis', 'Matthew Jarvis', 'Midfielder', 'Winger', 'Left/Right', 7, 70, 173, '1986-05-22', '2012-08-24', 'England', NULL, NULL, 0),
(663, 16, 49414, 'Jack', 'Collison', 'Jack Collison', 'Midfielder', 'Central Midfielder', 'Left/Centre/Right', 10, 75, 183, '1988-10-02', '2007-12-01', 'Wales', NULL, NULL, 0),
(664, 16, 3289, 'Matthew', 'Taylor', 'Matthew Taylor', 'Midfielder', 'Winger', 'Left', 14, 73, 178, '1981-11-27', '2011-07-23', 'England', NULL, NULL, 0),
(665, 16, 18073, 'Mark', 'Noble', 'Mark Noble', 'Midfielder', 'Central Midfielder', 'Centre', 16, 70, 180, '1987-05-08', '2003-07-01', 'England', NULL, NULL, 0),
(666, 16, 28147, 'Mohamed', 'Diamé', 'Mohamed Diamé', 'Midfielder', 'Defensive Midfielder', 'Centre', 21, 80, 184, '1987-06-14', '2012-07-01', 'Senegal', NULL, NULL, 0),
(667, 16, 8742, 'Alou', 'Diarra', 'Alou Diarra', 'Midfielder', 'Defensive Midfielder', 'Centre', 23, 79, 190, '1981-07-15', '2012-08-09', 'France', NULL, NULL, 0),
(668, 16, 2060, 'Joe', 'Cole', 'Joe Cole', 'Midfielder', 'Attacking Midfielder', 'Left/Centre/Right', 26, 63, 175, '1981-11-08', '2013-01-04', 'England', NULL, NULL, 0),
(669, 16, 129028, 'Matthias', 'Fanimo', 'Matthias Fanimo', 'Midfielder', 'Winger', 'Left/Right', 40, 71, 173, '1994-01-28', '2012-08-01', 'England', NULL, NULL, 0),
(670, 16, 105320, 'George', 'Moncur', 'George Moncur', 'Midfielder', 'Central Midfielder', 'Centre', 42, 63, 176, '1993-08-18', '2011-08-23', 'England', NULL, NULL, 0),
(671, 16, 88534, 'Sebastian', 'Lletget', 'Sebastian Lletget', 'Midfielder', 'Attacking Midfielder', 'Centre', 43, 69, 178, '1992-09-03', '2012-11-27', 'USA', NULL, NULL, 0),
(672, 16, 42954, 'Modibo', 'Maiga', 'Modibo Maiga', 'Forward', 'Striker', 'Centre', 11, 76, 185, '1987-09-03', '2012-07-18', 'Mali', NULL, NULL, 0),
(673, 16, 17353, 'Ricardo', 'Vaz Te', 'Ricardo Vaz Te', 'Forward', 'Second Striker', 'Left/Centre/Right', 12, 80, 189, '1986-10-01', '2012-01-31', 'Portugal', NULL, NULL, 0),
(674, 16, 149794, 'Sean', 'Maguire', 'Sean Maguire', 'Forward', 'Striker', 'Centre', 24, 75, 175, '1994-05-01', '2013-02-01', 'Republic of Ireland', NULL, NULL, 0),
(675, 16, 98465, 'Dylan', 'Tombides', 'Dylan Tombides', 'Forward', 'Striker', 'Centre', 39, 80, 180, '1994-03-08', '2011-05-01', 'Australia', NULL, NULL, 0),
(676, 16, 93557, 'Paul', 'McCallum', 'Paul McCallum', 'Forward', 'Striker', 'Centre', 45, 63, 191, '1993-07-28', '2011-01-31', 'England', NULL, NULL, 0),
(677, 16, 105321, 'Robert', 'Hall', 'Robert Hall', 'Forward', 'Striker', 'Centre', 46, 66, 173, '1993-10-20', '2011-08-23', 'England', NULL, NULL, 0),
(678, 16, 149485, 'Elliot', 'Lee', 'Elliot Lee', 'Forward', 'Striker', 'Centre', 47, 72, 180, '1994-12-16', '2013-01-01', 'England', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `player_competition`
--

CREATE TABLE IF NOT EXISTS `player_competition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`),
  KEY `competition_id` (`competition_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=680 ;

--
-- Dumping data for table `player_competition`
--

INSERT INTO `player_competition` (`id`, `player_id`, `competition_id`) VALUES
(1, 1, 3),
(2, 2, 3),
(3, 3, 3),
(4, 4, 3),
(5, 5, 3),
(6, 6, 3),
(7, 7, 3),
(8, 8, 3),
(9, 9, 3),
(10, 10, 3),
(11, 11, 3),
(12, 12, 3),
(13, 13, 3),
(14, 14, 3),
(15, 15, 3),
(16, 16, 3),
(17, 17, 3),
(18, 18, 3),
(19, 19, 3),
(20, 20, 3),
(21, 21, 3),
(22, 22, 3),
(23, 23, 3),
(24, 24, 3),
(25, 25, 3),
(26, 26, 3),
(27, 27, 3),
(28, 28, 3),
(29, 29, 3),
(30, 30, 3),
(31, 31, 3),
(32, 32, 3),
(33, 33, 3),
(34, 34, 3),
(35, 35, 3),
(36, 36, 3),
(37, 37, 3),
(38, 38, 3),
(39, 39, 3),
(40, 40, 3),
(41, 41, 3),
(42, 42, 3),
(43, 43, 3),
(44, 44, 3),
(45, 45, 3),
(46, 46, 3),
(47, 47, 3),
(48, 48, 3),
(49, 49, 3),
(50, 50, 3),
(51, 51, 3),
(52, 52, 3),
(53, 53, 3),
(54, 54, 3),
(55, 55, 3),
(56, 56, 3),
(57, 57, 3),
(58, 58, 3),
(59, 59, 3),
(60, 60, 3),
(61, 61, 3),
(62, 62, 3),
(63, 63, 3),
(64, 64, 3),
(65, 65, 3),
(66, 66, 3),
(67, 67, 3),
(68, 68, 3),
(69, 69, 3),
(70, 70, 3),
(71, 71, 3),
(72, 72, 3),
(73, 73, 3),
(74, 74, 3),
(75, 75, 3),
(76, 76, 3),
(77, 77, 3),
(78, 78, 3),
(79, 79, 3),
(80, 80, 3),
(81, 81, 3),
(82, 82, 3),
(83, 83, 3),
(84, 84, 3),
(85, 85, 3),
(86, 86, 3),
(87, 87, 3),
(88, 88, 3),
(89, 89, 3),
(90, 90, 3),
(91, 91, 3),
(92, 92, 3),
(93, 93, 3),
(94, 94, 3),
(95, 95, 3),
(96, 96, 3),
(97, 97, 3),
(98, 98, 3),
(99, 99, 3),
(100, 100, 3),
(101, 101, 3),
(102, 102, 3),
(103, 103, 3),
(104, 104, 3),
(105, 105, 3),
(106, 106, 3),
(107, 107, 3),
(108, 108, 3),
(109, 109, 3),
(110, 110, 3),
(111, 111, 3),
(112, 112, 3),
(113, 113, 3),
(114, 114, 3),
(115, 115, 3),
(116, 116, 3),
(117, 117, 3),
(118, 118, 3),
(119, 119, 3),
(120, 120, 3),
(121, 121, 3),
(122, 122, 3),
(123, 123, 3),
(124, 124, 3),
(125, 125, 3),
(126, 126, 3),
(127, 127, 3),
(128, 128, 3),
(129, 129, 3),
(130, 130, 3),
(131, 131, 3),
(132, 132, 3),
(133, 133, 3),
(134, 134, 3),
(135, 135, 3),
(136, 136, 3),
(137, 137, 3),
(138, 138, 3),
(139, 139, 3),
(140, 140, 3),
(141, 141, 3),
(142, 142, 3),
(143, 143, 3),
(144, 144, 3),
(145, 145, 3),
(146, 146, 3),
(148, 148, 3),
(149, 149, 3),
(150, 150, 3),
(151, 151, 3),
(152, 152, 3),
(153, 153, 3),
(154, 154, 3),
(155, 155, 3),
(156, 156, 3),
(157, 157, 3),
(158, 158, 3),
(159, 159, 3),
(160, 160, 3),
(161, 161, 3),
(162, 162, 3),
(163, 163, 3),
(164, 164, 3),
(165, 165, 3),
(166, 166, 3),
(167, 167, 3),
(168, 168, 3),
(169, 169, 3),
(170, 170, 3),
(171, 171, 3),
(172, 172, 3),
(173, 173, 3),
(174, 174, 3),
(175, 175, 3),
(176, 176, 3),
(177, 177, 3),
(178, 178, 3),
(179, 179, 3),
(180, 180, 3),
(181, 181, 3),
(182, 182, 3),
(183, 183, 3),
(184, 184, 3),
(185, 185, 3),
(186, 186, 3),
(187, 187, 3),
(188, 188, 3),
(189, 189, 3),
(190, 190, 3),
(191, 191, 3),
(192, 192, 3),
(193, 193, 3),
(194, 194, 3),
(195, 195, 3),
(196, 196, 3),
(197, 197, 3),
(198, 198, 3),
(199, 199, 3),
(200, 200, 3),
(201, 201, 3),
(202, 202, 3),
(203, 203, 3),
(204, 204, 3),
(205, 205, 3),
(206, 206, 3),
(207, 207, 3),
(208, 208, 3),
(209, 209, 3),
(210, 210, 3),
(211, 211, 3),
(212, 212, 3),
(213, 213, 3),
(214, 214, 3),
(215, 215, 3),
(216, 216, 3),
(217, 217, 3),
(218, 218, 3),
(219, 219, 3),
(220, 220, 3),
(221, 221, 3),
(222, 222, 3),
(223, 223, 3),
(224, 224, 3),
(225, 225, 3),
(226, 226, 3),
(227, 227, 3),
(228, 228, 3),
(229, 229, 3),
(230, 230, 3),
(231, 231, 3),
(232, 232, 3),
(233, 233, 3),
(234, 234, 3),
(235, 235, 3),
(236, 236, 3),
(237, 237, 3),
(238, 238, 3),
(239, 239, 3),
(240, 240, 3),
(241, 241, 3),
(242, 242, 3),
(243, 243, 3),
(244, 244, 3),
(245, 245, 3),
(246, 246, 3),
(247, 247, 3),
(248, 248, 3),
(249, 249, 3),
(250, 250, 3),
(251, 251, 3),
(252, 252, 3),
(253, 253, 3),
(254, 254, 3),
(255, 255, 3),
(256, 256, 3),
(257, 257, 3),
(258, 258, 3),
(259, 259, 3),
(260, 260, 3),
(261, 261, 3),
(262, 262, 3),
(263, 263, 3),
(264, 264, 3),
(265, 265, 3),
(266, 266, 3),
(267, 267, 3),
(268, 268, 3),
(269, 269, 3),
(270, 270, 3),
(271, 271, 3),
(272, 272, 3),
(273, 273, 3),
(274, 274, 3),
(275, 275, 3),
(276, 276, 3),
(277, 277, 3),
(278, 278, 3),
(279, 279, 3),
(280, 280, 3),
(281, 281, 3),
(282, 282, 3),
(283, 283, 3),
(284, 284, 3),
(285, 285, 3),
(286, 286, 3),
(287, 287, 3),
(288, 288, 3),
(289, 289, 3),
(290, 290, 3),
(291, 291, 3),
(292, 292, 3),
(293, 293, 3),
(294, 294, 3),
(295, 295, 3),
(296, 296, 3),
(297, 297, 3),
(298, 298, 3),
(299, 299, 3),
(300, 300, 3),
(301, 301, 3),
(302, 302, 3),
(303, 303, 3),
(304, 304, 3),
(305, 305, 3),
(306, 306, 3),
(307, 307, 3),
(308, 308, 3),
(309, 309, 3),
(310, 310, 3),
(311, 311, 3),
(312, 312, 3),
(313, 313, 3),
(314, 314, 3),
(315, 315, 3),
(316, 316, 3),
(317, 317, 3),
(318, 318, 3),
(319, 319, 3),
(320, 320, 3),
(321, 321, 3),
(322, 322, 3),
(323, 323, 3),
(324, 324, 3),
(325, 325, 3),
(326, 326, 3),
(327, 327, 3),
(328, 328, 3),
(329, 329, 3),
(330, 330, 3),
(331, 331, 3),
(332, 332, 3),
(333, 333, 3),
(334, 334, 3),
(335, 335, 3),
(336, 336, 3),
(337, 337, 3),
(338, 338, 3),
(339, 339, 3),
(340, 340, 3),
(341, 341, 3),
(342, 342, 3),
(343, 343, 3),
(344, 344, 3),
(345, 345, 3),
(346, 346, 3),
(347, 347, 3),
(348, 348, 3),
(349, 349, 3),
(350, 350, 3),
(351, 351, 3),
(352, 352, 3),
(353, 353, 3),
(354, 354, 3),
(355, 355, 3),
(356, 356, 3),
(357, 357, 3),
(358, 358, 3),
(359, 359, 3),
(360, 360, 3),
(361, 361, 3),
(362, 362, 3),
(363, 363, 3),
(364, 364, 3),
(365, 365, 3),
(366, 366, 3),
(367, 367, 3),
(368, 368, 3),
(369, 369, 3),
(370, 370, 3),
(371, 371, 3),
(372, 372, 3),
(373, 373, 3),
(374, 374, 3),
(375, 375, 3),
(376, 376, 3),
(377, 377, 3),
(378, 378, 3),
(379, 379, 3),
(380, 380, 3),
(381, 381, 3),
(382, 382, 3),
(383, 383, 3),
(384, 384, 3),
(385, 385, 3),
(386, 386, 3),
(387, 387, 3),
(388, 388, 3),
(389, 389, 3),
(390, 390, 3),
(391, 391, 3),
(392, 392, 3),
(393, 393, 3),
(394, 394, 3),
(395, 395, 3),
(396, 396, 3),
(397, 397, 3),
(398, 398, 3),
(399, 399, 3),
(400, 400, 3),
(401, 401, 3),
(402, 402, 3),
(403, 403, 3),
(404, 404, 3),
(405, 405, 3),
(406, 406, 3),
(407, 407, 3),
(408, 408, 3),
(409, 409, 3),
(410, 410, 3),
(411, 411, 3),
(412, 412, 3),
(413, 413, 3),
(414, 414, 3),
(415, 415, 3),
(416, 416, 3),
(417, 417, 3),
(418, 418, 3),
(419, 419, 3),
(420, 420, 3),
(421, 421, 3),
(422, 422, 3),
(423, 423, 3),
(424, 424, 3),
(425, 425, 3),
(426, 426, 3),
(427, 427, 3),
(428, 428, 3),
(429, 429, 3),
(430, 430, 3),
(431, 431, 3),
(432, 432, 3),
(433, 433, 3),
(434, 434, 3),
(435, 435, 3),
(436, 436, 3),
(437, 437, 3),
(438, 438, 3),
(439, 439, 3),
(440, 440, 3),
(441, 441, 3),
(442, 442, 3),
(443, 443, 3),
(444, 444, 3),
(445, 445, 3),
(446, 446, 3),
(447, 447, 3),
(448, 448, 3),
(449, 449, 3),
(450, 450, 3),
(451, 451, 3),
(452, 452, 3),
(453, 453, 3),
(454, 454, 3),
(455, 455, 3),
(456, 456, 3),
(457, 457, 3),
(458, 458, 3),
(459, 459, 3),
(460, 460, 3),
(461, 461, 3),
(462, 462, 3),
(463, 463, 3),
(464, 464, 3),
(465, 465, 3),
(466, 466, 3),
(467, 467, 3),
(468, 468, 3),
(469, 469, 3),
(470, 470, 3),
(471, 471, 3),
(472, 472, 3),
(473, 473, 3),
(474, 474, 3),
(475, 475, 3),
(476, 476, 3),
(477, 477, 3),
(478, 478, 3),
(479, 479, 3),
(480, 480, 3),
(481, 481, 3),
(482, 482, 3),
(483, 483, 3),
(484, 484, 3),
(485, 485, 3),
(486, 486, 3),
(487, 487, 3),
(488, 488, 3),
(489, 489, 3),
(490, 490, 3),
(491, 491, 3),
(492, 492, 3),
(493, 493, 3),
(494, 494, 3),
(495, 495, 3),
(496, 496, 3),
(497, 497, 3),
(498, 498, 3),
(499, 499, 3),
(500, 500, 3),
(501, 501, 3),
(502, 502, 3),
(503, 503, 3),
(504, 504, 3),
(505, 505, 3),
(506, 506, 3),
(507, 507, 3),
(508, 508, 3),
(509, 509, 3),
(510, 510, 3),
(511, 511, 3),
(512, 512, 3),
(513, 513, 3),
(514, 514, 3),
(515, 515, 3),
(516, 516, 3),
(517, 517, 3),
(518, 518, 3),
(519, 519, 3),
(520, 520, 3),
(521, 521, 3),
(522, 522, 3),
(523, 523, 3),
(524, 524, 3),
(525, 525, 3),
(526, 526, 3),
(527, 527, 3),
(528, 528, 3),
(529, 529, 3),
(530, 530, 3),
(531, 531, 3),
(532, 532, 3),
(533, 533, 3),
(534, 534, 3),
(535, 535, 3),
(536, 536, 3),
(537, 537, 3),
(538, 538, 3),
(539, 539, 3),
(540, 540, 3),
(541, 541, 3),
(542, 542, 3),
(543, 543, 3),
(544, 544, 3),
(545, 545, 3),
(546, 546, 3),
(547, 547, 3),
(548, 548, 3),
(549, 549, 3),
(550, 550, 3),
(551, 551, 3),
(552, 552, 3),
(553, 553, 3),
(554, 554, 3),
(555, 555, 3),
(556, 556, 3),
(557, 557, 3),
(558, 558, 3),
(559, 559, 3),
(560, 560, 3),
(561, 561, 3),
(562, 562, 3),
(563, 563, 3),
(564, 564, 3),
(565, 565, 3),
(566, 566, 3),
(567, 567, 3),
(568, 568, 3),
(569, 569, 3),
(570, 570, 3),
(571, 571, 3),
(572, 572, 3),
(573, 573, 3),
(574, 574, 3),
(575, 575, 3),
(576, 576, 3),
(577, 577, 3),
(578, 578, 3),
(579, 579, 3),
(580, 580, 3),
(581, 581, 3),
(582, 582, 3),
(583, 583, 3),
(584, 584, 3),
(585, 585, 3),
(586, 586, 3),
(587, 587, 3),
(588, 588, 3),
(589, 589, 3),
(590, 590, 3),
(591, 591, 3),
(592, 592, 3),
(593, 593, 3),
(594, 594, 3),
(595, 595, 3),
(596, 596, 3),
(597, 597, 3),
(598, 598, 3),
(599, 599, 3),
(600, 600, 3),
(601, 601, 3),
(602, 602, 3),
(603, 603, 3),
(604, 604, 3),
(605, 605, 3),
(606, 606, 3),
(607, 607, 3),
(608, 608, 3),
(609, 609, 3),
(610, 610, 3),
(611, 611, 3),
(612, 612, 3),
(613, 613, 3),
(614, 614, 3),
(615, 615, 3),
(616, 616, 3),
(617, 617, 3),
(618, 618, 3),
(619, 619, 3),
(620, 620, 3),
(621, 621, 3),
(622, 622, 3),
(623, 623, 3),
(624, 624, 3),
(625, 625, 3),
(626, 626, 3),
(627, 627, 3),
(628, 628, 3),
(629, 629, 3),
(630, 630, 3),
(631, 631, 3),
(632, 632, 3),
(633, 633, 3),
(634, 634, 3),
(635, 635, 3),
(636, 636, 3),
(637, 637, 3),
(638, 638, 3),
(639, 639, 3),
(640, 640, 3),
(641, 641, 3),
(642, 642, 3),
(643, 643, 3),
(644, 644, 3),
(645, 645, 3),
(646, 147, 3),
(647, 646, 3),
(648, 647, 3),
(649, 648, 3),
(650, 649, 3),
(651, 650, 3),
(652, 651, 3),
(653, 652, 3),
(654, 653, 3),
(655, 654, 3),
(656, 655, 3),
(657, 656, 3),
(658, 657, 3),
(659, 658, 3),
(660, 659, 3),
(661, 660, 3),
(662, 661, 3),
(663, 662, 3),
(664, 663, 3),
(665, 664, 3),
(666, 665, 3),
(667, 666, 3),
(668, 667, 3),
(669, 668, 3),
(670, 669, 3),
(671, 670, 3),
(672, 671, 3),
(673, 672, 3),
(674, 673, 3),
(675, 674, 3),
(676, 675, 3),
(677, 676, 3),
(678, 677, 3),
(679, 678, 3);

-- --------------------------------------------------------

--
-- Table structure for table `prediction`
--

CREATE TABLE IF NOT EXISTS `prediction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `home_team_score` tinyint(4) NOT NULL,
  `away_team_score` tinyint(4) NOT NULL,
  `is_correct_result` tinyint(1) DEFAULT NULL,
  `is_correct_score` tinyint(4) DEFAULT NULL,
  `correct_scorers` tinyint(4) DEFAULT NULL,
  `correct_scorers_order` tinyint(4) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `was_viewed` tinyint(1) NOT NULL,
  `last_update_date` datetime NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `match_id` (`match_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prediction`
--


-- --------------------------------------------------------

--
-- Table structure for table `prediction_player`
--

CREATE TABLE IF NOT EXISTS `prediction_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prediction_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `player_id` int(11) DEFAULT NULL,
  `order` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `prediction_id` (`prediction_id`),
  KEY `team_id` (`team_id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prediction_player`
--


-- --------------------------------------------------------

--
-- Table structure for table `prize`
--

CREATE TABLE IF NOT EXISTS `prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `league_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `prize_title` varchar(50) NOT NULL,
  `prize_description` text NOT NULL,
  `prize_image` varchar(255) NOT NULL,
  `post_win_title` varchar(50) NOT NULL,
  `post_win_description` text NOT NULL,
  `post_win_image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `league_id` (`league_id`),
  KEY `region_id` (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `prize`
--

INSERT INTO `prize` (`id`, `league_id`, `region_id`, `prize_title`, `prize_description`, `prize_image`, `post_win_title`, `post_win_description`, `post_win_image`) VALUES
(1, 1, 1, 'GRAND P-RIZE 2013-14 SEASON', '<h2>Visit Chelsea&rsquo;s training ground</h2>\r\n\r\n<p>Win the chance to visit Chelsea&rsquo;s exclusive Training Ground in Cobham to see the players in a training session, an amazing prize for any Chelsea fan, as well as a bounty of CFC merchandise and products from our sponsors.</p>\r\n\r\n<footer>\r\n<p>Winners announced <time>19 May 2013</time></p>\r\n</footer>\r\n', '/img/prizes/51d0d291b7a45.png', 'GRAND P-RIZE 2013-14 SEASON', '<h2>Visit Chelsea&rsquo;s training ground</h2>\r\n\r\n<p>Win the chance to visit Chelsea&rsquo;s exclusive Training Ground in Cobham to see the players in a training session, an amazing prize for any Chelsea fan, as well as a bounty of CFC merchandise and products from our sponsors.</p>\r\n\r\n<footer>\r\n<p>Winners announced <time>19 May 2013</time></p>\r\n</footer>\r\n', '/img/prizes/51d0d291b857f.png');

-- --------------------------------------------------------

--
-- Table structure for table `recovery`
--

CREATE TABLE IF NOT EXISTS `recovery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `recovery`
--


-- --------------------------------------------------------

--
-- Table structure for table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(50) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `region`
--

INSERT INTO `region` (`id`, `display_name`, `is_default`) VALUES
(1, 'British Isles', 1);

-- --------------------------------------------------------

--
-- Table structure for table `region_content`
--

CREATE TABLE IF NOT EXISTS `region_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) NOT NULL,
  `headline_copy` varchar(255) DEFAULT NULL,
  `register_button_copy` varchar(50) DEFAULT NULL,
  `hero_background_image_id` int(11) DEFAULT NULL,
  `hero_foreground_image_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_id`),
  KEY `hero_background_image_id` (`hero_background_image_id`),
  KEY `hero_foreground_image_id` (`hero_foreground_image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `region_content`
--

INSERT INTO `region_content` (`id`, `region_id`, `headline_copy`, `register_button_copy`, `hero_background_image_id`, `hero_foreground_image_id`) VALUES
(1, 1, 'Think you know football? Predict the score and win big', 'Start playing - it''s free', 23, 28);

-- --------------------------------------------------------

--
-- Table structure for table `region_gameplay_content`
--

CREATE TABLE IF NOT EXISTS `region_gameplay_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `foreground_image_id` int(11) DEFAULT NULL,
  `order` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_id`),
  KEY `foreground_image_id` (`foreground_image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `region_gameplay_content`
--

INSERT INTO `region_gameplay_content` (`id`, `region_id`, `heading`, `description`, `foreground_image_id`, `order`) VALUES
(1, 1, 'Home or away, log on to predict the score for the upcoming match', 'Use your computer or mobile consectetur adipiscing elit. <a target="_blank" rel="nofollow" href="http://google.com/">Aenean </a>consectetur interdum suscipit. Curabitur massa magna, sollicitudin ut pharetra ac, ultrices in ipsum. Nullam sit amet nunc et neque interdum.<br>', 20, 1),
(2, 1, 'Play against your friends to become top of your league', 'Use your computer or mobile consectetur adipiscing elit. Aenean consectetur interdum suscipit. Curabitur massa magna, sollicitudin ut pharetra ac, ultrices in ipsum. Nullam sit amet nunc et neque interdum.', 21, 2),
(3, 1, 'Score big and win epic montly prizes', 'Use your computer or mobile consectetur adipiscing elit. Aenean consectetur interdum suscipit. Curabitur massa magna, sollicitudin ut pharetra ac, ultrices in ipsum. Nullam sit amet nunc et neque interdum.', 22, 3);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `parent_id`) VALUES
(1, 'Super Admin', NULL),
(2, 'Regional Manager', 1),
(3, 'User', 2),
(4, 'Guest', 3);

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE IF NOT EXISTS `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `perm_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `perm_id` (`perm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `perm_id`) VALUES
(3, 1, 3),
(5, 2, 2),
(6, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `season`
--

CREATE TABLE IF NOT EXISTS `season` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `feeder_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `season`
--

INSERT INTO `season` (`id`, `display_name`, `start_date`, `end_date`, `feeder_id`) VALUES
(1, '2013/2014', '2013-07-01', '2014-06-01', 2013);

-- --------------------------------------------------------

--
-- Table structure for table `season_region`
--

CREATE TABLE IF NOT EXISTS `season_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `season_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `terms` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `season_id` (`season_id`),
  KEY `region_id` (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `season_region`
--

INSERT INTO `season_region` (`id`, `season_id`, `region_id`, `display_name`, `terms`) VALUES
(1, 1, 1, '2013/2014', '<p>Play and enjoy.</p>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'site-background-colour', '#024593'),
(2, 'site-footer-colour', '#2a3817'),
(3, 'bad-words', ''),
(4, 'ahead-predictions-days', '5'),
(5, 'help-and-support-email', 'admin@truefan.tv'),
(6, 'main-site-link', 'http://truefan.tv/'),
(7, 'send-welcome-email', '1');

-- --------------------------------------------------------

--
-- Table structure for table `share_copy`
--

CREATE TABLE IF NOT EXISTS `share_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `engine` enum('Facebook','Twitter') DEFAULT NULL,
  `target` enum('PreMatchReport','PostMatchReport') DEFAULT NULL,
  `copy` text NOT NULL,
  `weight` int(11) NOT NULL,
  `achievement_block_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `achievement_block_id` (`achievement_block_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `share_copy`
--

INSERT INTO `share_copy` (`id`, `engine`, `target`, `copy`, `weight`, `achievement_block_id`) VALUES
(1, 'Facebook', 'PreMatchReport', 'I''ve made first prediction! Join me my friends!', 1, NULL),
(2, 'Twitter', 'PreMatchReport', 'I''ve made first prediction! Join me my followers!', 1, NULL),
(3, 'Facebook', 'PreMatchReport', 'I''ve made a prediction! Join me my friends!', 3, NULL),
(4, 'Twitter', 'PreMatchReport', 'I''ve made a prediction! Join me my followers!', 3, NULL),
(5, 'Facebook', 'PreMatchReport', '', 3, NULL),
(6, 'Twitter', 'PreMatchReport', '', 3, NULL),
(7, 'Facebook', 'PreMatchReport', '', 3, NULL),
(8, 'Twitter', 'PreMatchReport', '', 3, NULL),
(9, 'Facebook', 'PreMatchReport', '', 3, NULL),
(10, 'Twitter', 'PreMatchReport', '', 3, NULL),
(11, 'Facebook', 'PreMatchReport', '', 3, NULL),
(12, 'Twitter', 'PreMatchReport', '', 3, NULL),
(13, 'Facebook', 'PostMatchReport', 'You predicted correct result first time at the season!', 0, 1),
(14, 'Twitter', 'PostMatchReport', 'You predicted correct result first time at the season!', 0, 1),
(15, 'Facebook', 'PostMatchReport', 'You predicted correct scorer first time at the season!', 0, 2),
(16, 'Twitter', 'PostMatchReport', 'You predicted correct scorer first time at the season!', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(50) NOT NULL,
  `short_name` varchar(10) DEFAULT NULL,
  `feeder_id` int(11) NOT NULL,
  `founded` int(11) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `stadium_capacity` int(11) DEFAULT NULL,
  `stadium_name` varchar(50) DEFAULT NULL,
  `manager` varchar(100) DEFAULT NULL,
  `is_blocked` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `display_name`, `short_name`, `feeder_id`, `founded`, `logo_path`, `stadium_capacity`, `stadium_name`, `manager`, `is_blocked`) VALUES
(1, 'Chelsea', 'CHE', 8, 1905, NULL, 41837, 'Stamford Bridge', 'José Mourinho', 0),
(2, 'Manchester City', 'MCI', 43, 1887, NULL, 47805, 'Etihad Stadium', 'Manuel Pellegrini', 0),
(3, 'Singha All Star', NULL, 8658, NULL, NULL, NULL, NULL, NULL, 0),
(4, 'Malaysia Select XI', NULL, 2878, NULL, NULL, NULL, NULL, NULL, 0),
(5, 'BNI Indonesia All Star', NULL, 8659, NULL, NULL, NULL, NULL, NULL, 0),
(6, 'Hull City', 'HUL', 88, 1904, NULL, 25400, 'The KC Stadium', 'Steve Bruce', 0),
(7, 'Manchester United', 'MUN', 1, 1878, NULL, 75811, 'Old Trafford', 'David Moyes', 0),
(8, 'Aston Villa', 'AVL', 7, 1874, NULL, 42788, 'Villa Park', 'Paul Lambert', 0),
(9, 'Everton', 'EVE', 11, 1878, NULL, 40157, 'Goodison Park', 'Roberto Martinez', 0),
(10, 'Fulham', 'FUL', 54, 1879, NULL, 25700, 'Craven Cottage', 'Martin Jol', 0),
(11, 'Tottenham Hotspur', 'TOT', 6, 1882, NULL, 36230, 'White Hart Lane', 'Andre Villas-Boas', 0),
(12, 'Norwich City', 'NOR', 45, 1902, NULL, 27220, 'Carrow Road', 'Chris Hughton', 0),
(13, 'Cardiff City', 'CAR', 97, 1899, NULL, 27000, 'Cardiff City Stadium', 'Malky Mackay', 0),
(14, 'Newcastle United', 'NEW', 4, 1881, NULL, 52387, 'St. James'' Park', 'Alan Pardew', 0),
(15, 'West Bromwich Albion', 'WBA', 35, 1878, NULL, 26272, 'The Hawthorns', 'Steve Clarke', 0),
(16, 'West Ham United', 'WHU', 21, 1895, NULL, 35016, 'Boleyn Ground', 'Sam Allardyce', 0),
(17, 'Southampton', 'SOU', 20, 1885, NULL, 32690, 'St. Mary''s Stadium', 'Mauricio Pochettino', 0),
(18, 'Sunderland', 'SUN', 56, 1879, NULL, 49000, 'Stadium of Light', 'Paolo Di Canio', 0),
(19, 'Stoke City', 'STK', 110, 1863, NULL, 27740, 'Britannia Stadium', 'Mark Hughes', 0),
(20, 'Crystal Palace', 'CRY', 31, 1905, NULL, 26400, 'Selhurst Park', 'Ian Holloway', 0),
(21, 'Arsenal', 'ARS', 3, 1886, NULL, 60361, 'Emirates Stadium', 'Arsène Wenger', 0),
(22, 'Swansea City', 'SWA', 80, 1912, NULL, 20532, 'Liberty Stadium', 'Michael Laudrup', 0),
(23, 'Liverpool', 'LIV', 14, 1892, NULL, 45276, 'Anfield', 'Brendan Rodgers', 0);

-- --------------------------------------------------------

--
-- Table structure for table `team_competition`
--

CREATE TABLE IF NOT EXISTS `team_competition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`),
  KEY `competition_id` (`competition_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `team_competition`
--

INSERT INTO `team_competition` (`id`, `team_id`, `competition_id`) VALUES
(1, 21, 3),
(2, 8, 3),
(3, 13, 3),
(4, 1, 3),
(5, 20, 3),
(6, 9, 3),
(7, 10, 3),
(8, 6, 3),
(9, 23, 3),
(10, 2, 3),
(11, 7, 3),
(12, 14, 3),
(13, 12, 3),
(14, 17, 3),
(15, 19, 3),
(16, 18, 3),
(17, 22, 3),
(18, 11, 3),
(19, 15, 3),
(20, 16, 3);

-- --------------------------------------------------------

--
-- Table structure for table `term`
--

CREATE TABLE IF NOT EXISTS `term` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_checked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `term`
--


-- --------------------------------------------------------

--
-- Table structure for table `term_copy`
--

CREATE TABLE IF NOT EXISTS `term_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `copy` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `term_id` (`term_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `term_copy`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(5) DEFAULT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `country_id` int(11) NOT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `display_name` varchar(20) NOT NULL,
  `avatar_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `favourite_player_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `facebook_id` bigint(20) unsigned DEFAULT NULL,
  `facebook_access_token` varchar(300) DEFAULT NULL,
  `date` datetime NOT NULL,
  `last_logged_in` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  KEY `avatar_id` (`avatar_id`),
  KEY `language_id` (`language_id`),
  KEY `country_id` (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `title`, `first_name`, `last_name`, `email`, `password`, `country_id`, `birthday`, `gender`, `display_name`, `avatar_id`, `language_id`, `role_id`, `favourite_player_id`, `is_active`, `is_public`, `facebook_id`, `facebook_access_token`, `date`, `last_logged_in`) VALUES
(1, 'Mr.', 'Super', 'Admin', 'super@admin.com', '$1$8a/.vj/.$9VT7aco9Go/VEHRjSnHpa/', 95, '1987-10-31', 'Male', 'Super Admin', 1, 1, 1, NULL, 1, 1, NULL, NULL, '2013-05-04 15:43:00', '2013-06-30 23:20:07');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `competition`
--
ALTER TABLE `competition`
  ADD CONSTRAINT `competition_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `season` (`id`);

--
-- Constraints for table `country`
--
ALTER TABLE `country`
  ADD CONSTRAINT `country_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`),
  ADD CONSTRAINT `country_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);

--
-- Constraints for table `default_report_content`
--
ALTER TABLE `default_report_content`
  ADD CONSTRAINT `FK_default_report_content_region` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Constraints for table `featured_goalkeeper`
--
ALTER TABLE `featured_goalkeeper`
  ADD CONSTRAINT `FK_featured_goalkeeper_player` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`);

--
-- Constraints for table `featured_player`
--
ALTER TABLE `featured_player`
  ADD CONSTRAINT `FK_featured_player_player` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`);

--
-- Constraints for table `footer_image`
--
ALTER TABLE `footer_image`
  ADD CONSTRAINT `footer_image_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Constraints for table `footer_page`
--
ALTER TABLE `footer_page`
  ADD CONSTRAINT `FK_footer_page_language` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);

--
-- Constraints for table `footer_social`
--
ALTER TABLE `footer_social`
  ADD CONSTRAINT `footer_social_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Constraints for table `league`
--
ALTER TABLE `league`
  ADD CONSTRAINT `league_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `season` (`id`),
  ADD CONSTRAINT `league_ibfk_2` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `league_region`
--
ALTER TABLE `league_region`
  ADD CONSTRAINT `league_region_ibfk_1` FOREIGN KEY (`league_id`) REFERENCES `league` (`id`),
  ADD CONSTRAINT `league_region_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Constraints for table `league_user`
--
ALTER TABLE `league_user`
  ADD CONSTRAINT `league_user_ibfk_1` FOREIGN KEY (`league_id`) REFERENCES `league` (`id`),
  ADD CONSTRAINT `league_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `league_user_place`
--
ALTER TABLE `league_user_place`
  ADD CONSTRAINT `league_user_place_ibfk_1` FOREIGN KEY (`league_user_id`) REFERENCES `league_user` (`id`),
  ADD CONSTRAINT `league_user_place_ibfk_2` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`);

--
-- Constraints for table `line_up`
--
ALTER TABLE `line_up`
  ADD CONSTRAINT `line_up_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`),
  ADD CONSTRAINT `line_up_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`),
  ADD CONSTRAINT `line_up_ibfk_3` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`);

--
-- Constraints for table `logotype`
--
ALTER TABLE `logotype`
  ADD CONSTRAINT `FK_logotype_emblem` FOREIGN KEY (`emblem_id`) REFERENCES `emblem` (`id`),
  ADD CONSTRAINT `FK_logotype_language` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);

--
-- Constraints for table `match`
--
ALTER TABLE `match`
  ADD CONSTRAINT `match_ibfk_1` FOREIGN KEY (`competition_id`) REFERENCES `competition` (`id`),
  ADD CONSTRAINT `match_ibfk_2` FOREIGN KEY (`home_team_id`) REFERENCES `team` (`id`),
  ADD CONSTRAINT `match_ibfk_3` FOREIGN KEY (`away_team_id`) REFERENCES `team` (`id`),
  ADD CONSTRAINT `match_ibfk_4` FOREIGN KEY (`featured_prediction_id`) REFERENCES `prediction` (`id`),
  ADD CONSTRAINT `match_ibfk_5` FOREIGN KEY (`featured_player_id`) REFERENCES `player` (`id`);

--
-- Constraints for table `match_goal`
--
ALTER TABLE `match_goal`
  ADD CONSTRAINT `match_goal_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`),
  ADD CONSTRAINT `match_goal_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`),
  ADD CONSTRAINT `match_goal_ibfk_3` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`);

--
-- Constraints for table `match_region`
--
ALTER TABLE `match_region`
  ADD CONSTRAINT `FK_match_region_featured_goalkeeper` FOREIGN KEY (`featured_goalkeeper_id`) REFERENCES `featured_goalkeeper` (`id`),
  ADD CONSTRAINT `FK_match_region_featured_player` FOREIGN KEY (`featured_player_id`) REFERENCES `featured_player` (`id`),
  ADD CONSTRAINT `FK_match_region_featured_prediction` FOREIGN KEY (`featured_prediction_id`) REFERENCES `featured_prediction` (`id`),
  ADD CONSTRAINT `match_region_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`),
  ADD CONSTRAINT `match_region_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Constraints for table `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `player_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`);

--
-- Constraints for table `player_competition`
--
ALTER TABLE `player_competition`
  ADD CONSTRAINT `player_competition_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`),
  ADD CONSTRAINT `player_competition_ibfk_2` FOREIGN KEY (`competition_id`) REFERENCES `competition` (`id`);

--
-- Constraints for table `prediction`
--
ALTER TABLE `prediction`
  ADD CONSTRAINT `prediction_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `prediction_ibfk_2` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`);

--
-- Constraints for table `prediction_player`
--
ALTER TABLE `prediction_player`
  ADD CONSTRAINT `prediction_player_ibfk_1` FOREIGN KEY (`prediction_id`) REFERENCES `prediction` (`id`),
  ADD CONSTRAINT `prediction_player_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`),
  ADD CONSTRAINT `prediction_player_ibfk_3` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`);

--
-- Constraints for table `prize`
--
ALTER TABLE `prize`
  ADD CONSTRAINT `prize_ibfk_1` FOREIGN KEY (`league_id`) REFERENCES `league` (`id`),
  ADD CONSTRAINT `prize_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Constraints for table `recovery`
--
ALTER TABLE `recovery`
  ADD CONSTRAINT `recovery_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `region_content`
--
ALTER TABLE `region_content`
  ADD CONSTRAINT `region_content_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`),
  ADD CONSTRAINT `region_content_ibfk_2` FOREIGN KEY (`hero_background_image_id`) REFERENCES `content_image` (`id`),
  ADD CONSTRAINT `region_content_ibfk_3` FOREIGN KEY (`hero_foreground_image_id`) REFERENCES `content_image` (`id`);

--
-- Constraints for table `region_gameplay_content`
--
ALTER TABLE `region_gameplay_content`
  ADD CONSTRAINT `region_gameplay_content_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`),
  ADD CONSTRAINT `region_gameplay_content_ibfk_2` FOREIGN KEY (`foreground_image_id`) REFERENCES `content_image` (`id`);

--
-- Constraints for table `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `role_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  ADD CONSTRAINT `role_permission_ibfk_2` FOREIGN KEY (`perm_id`) REFERENCES `permission` (`id`);

--
-- Constraints for table `season_region`
--
ALTER TABLE `season_region`
  ADD CONSTRAINT `season_region_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `season` (`id`),
  ADD CONSTRAINT `season_region_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Constraints for table `share_copy`
--
ALTER TABLE `share_copy`
  ADD CONSTRAINT `share_copy_ibfk_1` FOREIGN KEY (`achievement_block_id`) REFERENCES `achievement_block` (`id`);

--
-- Constraints for table `team_competition`
--
ALTER TABLE `team_competition`
  ADD CONSTRAINT `team_competition_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`),
  ADD CONSTRAINT `team_competition_ibfk_2` FOREIGN KEY (`competition_id`) REFERENCES `competition` (`id`);

--
-- Constraints for table `term_copy`
--
ALTER TABLE `term_copy`
  ADD CONSTRAINT `FK_term_copy_language` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
  ADD CONSTRAINT `FK_term_copy_term` FOREIGN KEY (`term_id`) REFERENCES `term` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`avatar_id`) REFERENCES `avatar` (`id`),
  ADD CONSTRAINT `user_ibfk_4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
  ADD CONSTRAINT `user_ibfk_5` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);


-- okh 02.07
ALTER TABLE `country`  ADD INDEX `name` (`name`);

-- oko 03.07

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `iso_code` varchar(2) NOT NULL,
  `dial_code` smallint(6) DEFAULT NULL,
  `flag_image` varchar(64) NOT NULL,
  `original_flag_image` varchar(255) NOT NULL,
  `region_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_id`),
  KEY `language_id` (`language_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=224 ;

INSERT INTO `country` (`id`, `name`, `iso_code`, `dial_code`, `flag_image`, `original_flag_image`, `region_id`, `language_id`) VALUES
(1, 'United States', 'US', 1, '/img/flags/1.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/a/a4/Flag_of_the_United_States.svg/28px-Flag_of_the_United_States.svg.png', NULL, 1),
(2, 'Canada', 'CA', 1, '/img/flags/2.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/cf/Flag_of_Canada.svg/28px-Flag_of_Canada.svg.png', NULL, 1),
(3, 'Bahamas', 'BS', 242, '/img/flags/3.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/93/Flag_of_the_Bahamas.svg/28px-Flag_of_the_Bahamas.svg.png', NULL, NULL),
(4, 'Barbados', 'BB', 246, '/img/flags/4.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/ef/Flag_of_Barbados.svg/28px-Flag_of_Barbados.svg.png', NULL, NULL),
(5, 'Belize', 'BZ', 501, '/img/flags/5.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Flag_of_Belize.svg/28px-Flag_of_Belize.svg.png', NULL, NULL),
(6, 'Bermuda', 'BM', 441, '/img/flags/6.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Flag_of_Bermuda.svg/28px-Flag_of_Bermuda.svg.png', NULL, NULL),
(7, 'British Virgin Islands', 'VG', 284, '/img/flags/7.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/42/Flag_of_the_British_Virgin_Islands.svg/28px-Flag_of_the_British_Virgin_Islands.svg.png', NULL, NULL),
(8, 'Cayman Islands', 'KY', 345, '/img/flags/8.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_the_Cayman_Islands.svg/28px-Flag_of_the_Cayman_Islands.svg.png', NULL, NULL),
(9, 'Costa Rica', 'CR', 506, '/img/flags/9.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f2/Flag_of_Costa_Rica.svg/28px-Flag_of_Costa_Rica.svg.png', NULL, NULL),
(10, 'Cuba', 'CU', 53, '/img/flags/10.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/Flag_of_Cuba.svg/28px-Flag_of_Cuba.svg.png', NULL, NULL),
(11, 'Dominica', 'DM', 767, '/img/flags/11.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/c4/Flag_of_Dominica.svg/28px-Flag_of_Dominica.svg.png', NULL, NULL),
(12, 'Dominican Republic', 'DO', 809, '/img/flags/12.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Flag_of_the_Dominican_Republic.svg/28px-Flag_of_the_Dominican_Republic.svg.png', NULL, NULL),
(13, 'El Salvador', 'SV', 503, '/img/flags/13.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Flag_of_El_Salvador.svg/28px-Flag_of_El_Salvador.svg.png', NULL, NULL),
(14, 'Greenland', 'GL', 299, '/img/flags/14.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Flag_of_Greenland.svg/28px-Flag_of_Greenland.svg.png', NULL, NULL),
(15, 'Grenada', 'GD', 473, '/img/flags/15.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Grenada.svg/28px-Flag_of_Grenada.svg.png', NULL, NULL),
(16, 'Guadeloupe', 'GP', 590, '/img/flags/16.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png', NULL, NULL),
(17, 'Guatemala', 'GT', 502, '/img/flags/17.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/Flag_of_Guatemala.svg/28px-Flag_of_Guatemala.svg.png', NULL, NULL),
(18, 'Haiti', 'HT', 509, '/img/flags/18.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Flag_of_Haiti.svg/28px-Flag_of_Haiti.svg.png', NULL, NULL),
(19, 'Honduras', 'HN', 503, '/img/flags/19.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Flag_of_Honduras.svg/28px-Flag_of_Honduras.svg.png', NULL, NULL),
(20, 'Jamaica', 'JM', 876, '/img/flags/20.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/0a/Flag_of_Jamaica.svg/28px-Flag_of_Jamaica.svg.png', NULL, NULL),
(21, 'Martinique', 'MQ', 596, '/img/flags/21.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png', NULL, NULL),
(22, 'Mexico', 'MX', 52, '/img/flags/22.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/Flag_of_Mexico.svg/28px-Flag_of_Mexico.svg.png', NULL, NULL),
(23, 'Montserrat', 'MS', 664, '/img/flags/23.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Flag_of_Montserrat.svg/28px-Flag_of_Montserrat.svg.png', NULL, NULL),
(24, 'Nicaragua', 'NI', 505, '/img/flags/24.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Nicaragua.svg/28px-Flag_of_Nicaragua.svg.png', NULL, NULL),
(25, 'Panama', 'PA', 507, '/img/flags/25.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Flag_of_Panama.svg/28px-Flag_of_Panama.svg.png', NULL, NULL),
(26, 'Puerto Rico', 'PR', 787, '/img/flags/26.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/28/Flag_of_Puerto_Rico.svg/28px-Flag_of_Puerto_Rico.svg.png', NULL, NULL),
(27, 'Trinidad and Tobago', 'TT', 868, '/img/flags/27.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Flag_of_Trinidad_and_Tobago.svg/28px-Flag_of_Trinidad_and_Tobago.svg.png', NULL, NULL),
(28, 'United States Virgin Islands', 'VI', 340, '/img/flags/28.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Flag_of_the_United_States_Virgin_Islands.svg/28px-Flag_of_the_United_States_Virgin_Islands.svg.png', NULL, NULL),
(29, 'Argentina', 'AR', 54, '/img/flags/29.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Flag_of_Argentina.svg/28px-Flag_of_Argentina.svg.png', NULL, NULL),
(30, 'Bolivia', 'BO', 591, '/img/flags/30.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Bolivia.svg/28px-Flag_of_Bolivia.svg.png', NULL, NULL),
(31, 'Brazil', 'BR', 55, '/img/flags/31.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/0/05/Flag_of_Brazil.svg/28px-Flag_of_Brazil.svg.png', NULL, NULL),
(32, 'Chile', 'CL', 56, '/img/flags/32.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Flag_of_Chile.svg/28px-Flag_of_Chile.svg.png', NULL, NULL),
(33, 'Colombia', 'CO', 57, '/img/flags/33.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Flag_of_Colombia.svg/28px-Flag_of_Colombia.svg.png', NULL, NULL),
(34, 'Ecuador', 'EC', 593, '/img/flags/34.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/Flag_of_Ecuador.svg/28px-Flag_of_Ecuador.svg.png', NULL, NULL),
(35, 'Falkland Islands', 'FK', 500, '/img/flags/35.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_the_Falkland_Islands.svg/28px-Flag_of_the_Falkland_Islands.svg.png', NULL, NULL),
(36, 'French Guiana', 'GF', 594, '/img/flags/36.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png', NULL, NULL),
(37, 'Guyana', 'GY', 592, '/img/flags/37.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_Guyana.svg/28px-Flag_of_Guyana.svg.png', NULL, NULL),
(38, 'Paraguay', 'PY', 595, '/img/flags/38.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Paraguay.svg/28px-Flag_of_Paraguay.svg.png', NULL, NULL),
(39, 'Peru', 'PE', 51, '/img/flags/39.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/cf/Flag_of_Peru.svg/28px-Flag_of_Peru.svg.png', NULL, NULL),
(40, 'Suriname', 'SR', 597, '/img/flags/40.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/60/Flag_of_Suriname.svg/28px-Flag_of_Suriname.svg.png', NULL, NULL),
(41, 'Uruguay', 'UY', 598, '/img/flags/41.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_Uruguay.svg/28px-Flag_of_Uruguay.svg.png', NULL, NULL),
(42, 'Venezuela', 'VE', 58, '/img/flags/42.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/06/Flag_of_Venezuela.svg/28px-Flag_of_Venezuela.svg.png', NULL, NULL),
(43, 'Albania', 'AL', 355, '/img/flags/43.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/36/Flag_of_Albania.svg/28px-Flag_of_Albania.svg.png', NULL, NULL),
(44, 'Andorra', 'AD', 376, '/img/flags/44.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Andorra.svg/28px-Flag_of_Andorra.svg.png', NULL, NULL),
(45, 'Armenia', 'AM', 374, '/img/flags/45.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Flag_of_Armenia.svg/28px-Flag_of_Armenia.svg.png', NULL, NULL),
(46, 'Austria', 'AT', 43, '/img/flags/46.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Flag_of_Austria.svg/28px-Flag_of_Austria.svg.png', NULL, NULL),
(47, 'Azerbaijan', 'AZ', 994, '/img/flags/47.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Flag_of_Azerbaijan.svg/28px-Flag_of_Azerbaijan.svg.png', NULL, NULL),
(48, 'Belarus', 'BY', 375, '/img/flags/48.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/43/Flag_of_Belarus_%281991-1995%29.svg/28px-Flag_of_Belarus_%281991-1995%29.svg.png', NULL, NULL),
(49, 'Belgium', 'BE', 32, '/img/flags/49.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Flag_of_Belgium_%28civil%29.svg/28px-Flag_of_Belgium_%28civil%29.svg.png', NULL, NULL),
(50, 'Bosnia and Herzegovina', 'BA', 387, '/img/flags/50.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Flag_of_Bosnia_and_Herzegovina.svg/28px-Flag_of_Bosnia_and_Herzegovina.svg.png', NULL, NULL),
(51, 'Bulgaria', 'BG', 359, '/img/flags/51.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Bulgaria.svg/28px-Flag_of_Bulgaria.svg.png', NULL, NULL),
(52, 'Croatia', 'HR', 385, '/img/flags/52.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Flag_of_Croatia.svg/28px-Flag_of_Croatia.svg.png', NULL, NULL),
(53, 'Cyprus', 'CY', 357, '/img/flags/53.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Flag_of_Cyprus.svg/28px-Flag_of_Cyprus.svg.png', NULL, NULL),
(54, 'Czech Republic', 'CZ', 420, '/img/flags/54.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Flag_of_the_Czech_Republic.svg/28px-Flag_of_the_Czech_Republic.svg.png', NULL, NULL),
(55, 'Denmark', 'DK', 45, '/img/flags/55.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Flag_of_Denmark.svg/28px-Flag_of_Denmark.svg.png', NULL, NULL),
(56, 'Estonia', 'EE', 372, '/img/flags/56.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Flag_of_Estonia.svg/28px-Flag_of_Estonia.svg.png', NULL, NULL),
(57, 'Finland', 'FI', 358, '/img/flags/57.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Finland.svg/28px-Flag_of_Finland.svg.png', NULL, NULL),
(58, 'France', 'FR', 33, '/img/flags/58.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png', NULL, NULL),
(59, 'Georgia', 'GE', 995, '/img/flags/59.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Georgia.svg/28px-Flag_of_Georgia.svg.png', NULL, NULL),
(60, 'Germany', 'DE', 49, '/img/flags/60.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/b/ba/Flag_of_Germany.svg/28px-Flag_of_Germany.svg.png', NULL, NULL),
(61, 'Gibraltar', 'GI', 350, '/img/flags/61.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/02/Flag_of_Gibraltar.svg/28px-Flag_of_Gibraltar.svg.png', NULL, NULL),
(62, 'Greece', 'GR', 30, '/img/flags/62.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Greece.svg/28px-Flag_of_Greece.svg.png', NULL, NULL),
(63, 'Guernsey', 'GG', 44, '/img/flags/63.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_Guernsey.svg/28px-Flag_of_Guernsey.svg.png', NULL, NULL),
(64, 'Hungary', 'HU', 36, '/img/flags/64.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Flag_of_Hungary.svg/28px-Flag_of_Hungary.svg.png', NULL, NULL),
(65, 'Iceland', 'IS', 354, '/img/flags/65.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/ce/Flag_of_Iceland.svg/28px-Flag_of_Iceland.svg.png', NULL, NULL),
(66, 'Ireland', 'IE', 353, '/img/flags/66.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Flag_of_Ireland.svg/28px-Flag_of_Ireland.svg.png', 1, NULL),
(67, 'Isle of Man', 'IM', 44, '/img/flags/67.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_the_Isle_of_Man.svg/28px-Flag_of_the_Isle_of_Man.svg.png', NULL, NULL),
(68, 'Italy', 'IT', 39, '/img/flags/68.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/0/03/Flag_of_Italy.svg/28px-Flag_of_Italy.svg.png', NULL, NULL),
(69, 'Jersey', 'JE', 44, '/img/flags/69.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/1c/Flag_of_Jersey.svg/28px-Flag_of_Jersey.svg.png', NULL, NULL),
(70, 'Kosovo', '', 381, '/img/flags/70.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/1f/Flag_of_Kosovo.svg/28px-Flag_of_Kosovo.svg.png', NULL, NULL),
(71, 'Latvia', 'LV', 371, '/img/flags/71.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Flag_of_Latvia.svg/28px-Flag_of_Latvia.svg.png', NULL, NULL),
(72, 'Liechtenstein', 'LI', 423, '/img/flags/72.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/47/Flag_of_Liechtenstein.svg/28px-Flag_of_Liechtenstein.svg.png', NULL, NULL),
(73, 'Lithuania', 'LT', 370, '/img/flags/73.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Flag_of_Lithuania.svg/28px-Flag_of_Lithuania.svg.png', NULL, NULL),
(74, 'Luxembourg', 'LU', 352, '/img/flags/74.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/da/Flag_of_Luxembourg.svg/28px-Flag_of_Luxembourg.svg.png', NULL, NULL),
(75, 'Macedonia', 'MK', 389, '/img/flags/75.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Flag_of_Macedonia.svg/28px-Flag_of_Macedonia.svg.png', NULL, NULL),
(76, 'Malta', 'MT', 356, '/img/flags/76.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Flag_of_Malta.svg/28px-Flag_of_Malta.svg.png', NULL, NULL),
(77, 'Moldova', 'MD', 373, '/img/flags/77.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Moldova.svg/28px-Flag_of_Moldova.svg.png', NULL, NULL),
(78, 'Monaco', 'MC', 377, '/img/flags/78.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/ea/Flag_of_Monaco.svg/28px-Flag_of_Monaco.svg.png', NULL, NULL),
(79, 'Montenegro', 'ME', 381, '/img/flags/79.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Flag_of_Montenegro.svg/28px-Flag_of_Montenegro.svg.png', NULL, NULL),
(80, 'Netherlands', 'NL', 31, '/img/flags/80.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/20/Flag_of_the_Netherlands.svg/28px-Flag_of_the_Netherlands.svg.png', NULL, NULL),
(81, 'Norway', 'NO', 47, '/img/flags/81.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Flag_of_Norway.svg/28px-Flag_of_Norway.svg.png', NULL, NULL),
(82, 'Poland', 'PL', 48, '/img/flags/82.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/1/12/Flag_of_Poland.svg/28px-Flag_of_Poland.svg.png', NULL, NULL),
(83, 'Portugal', 'PT', 351, '/img/flags/83.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Portugal.svg/28px-Flag_of_Portugal.svg.png', NULL, NULL),
(84, 'Romania', 'RO', 40, '/img/flags/84.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Flag_of_Romania.svg/28px-Flag_of_Romania.svg.png', NULL, NULL),
(85, 'Russia', 'RU', 7, '/img/flags/85.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/f/f3/Flag_of_Russia.svg/28px-Flag_of_Russia.svg.png', NULL, NULL),
(86, 'San Marino', 'SM', 378, '/img/flags/86.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/b1/Flag_of_San_Marino.svg/28px-Flag_of_San_Marino.svg.png', NULL, NULL),
(87, 'Serbia', 'RS', 381, '/img/flags/87.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/ff/Flag_of_Serbia.svg/28px-Flag_of_Serbia.svg.png', NULL, NULL),
(88, 'Slovakia', 'SK', 421, '/img/flags/88.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Flag_of_Slovakia.svg/28px-Flag_of_Slovakia.svg.png', NULL, NULL),
(89, 'Slovenia', 'SI', 386, '/img/flags/89.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Flag_of_Slovenia.svg/28px-Flag_of_Slovenia.svg.png', NULL, NULL),
(90, 'Spain', 'ES', 34, '/img/flags/90.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/9/9a/Flag_of_Spain.svg/28px-Flag_of_Spain.svg.png', NULL, NULL),
(91, 'Sweden', 'SE', 46, '/img/flags/91.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/4/4c/Flag_of_Sweden.svg/28px-Flag_of_Sweden.svg.png', NULL, NULL),
(92, 'Switzerland', 'CH', 41, '/img/flags/92.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Flag_of_Switzerland.svg/20px-Flag_of_Switzerland.svg.png', NULL, NULL),
(93, 'Turkey', 'TR', 90, '/img/flags/93.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Flag_of_Turkey.svg/28px-Flag_of_Turkey.svg.png', NULL, NULL),
(94, 'Ukraine', 'UA', 380, '/img/flags/94.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Flag_of_Ukraine.svg/28px-Flag_of_Ukraine.svg.png', NULL, NULL),
(95, 'United Kingdom', 'GB', 44, '/img/flags/95.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/a/ae/Flag_of_the_United_Kingdom.svg/28px-Flag_of_the_United_Kingdom.svg.png', 1, 1),
(96, 'Vatican City', 'VA', 39, '/img/flags/96.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/00/Flag_of_the_Vatican_City.svg/20px-Flag_of_the_Vatican_City.svg.png', NULL, NULL),
(97, 'Afghanistan', 'AF', 93, '/img/flags/97.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Afghanistan.svg/28px-Flag_of_Afghanistan.svg.png', NULL, NULL),
(98, 'Bahrain', 'BH', 973, '/img/flags/98.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Flag_of_Bahrain.svg/28px-Flag_of_Bahrain.svg.png', NULL, NULL),
(99, 'Bangladesh', 'BD', 880, '/img/flags/99.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/Flag_of_Bangladesh.svg/28px-Flag_of_Bangladesh.svg.png', NULL, NULL),
(100, 'Bhutan', 'BT', 975, '/img/flags/100.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Flag_of_Bhutan.svg/28px-Flag_of_Bhutan.svg.png', NULL, NULL),
(101, 'Brunei', 'BN', 673, '/img/flags/101.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Flag_of_Brunei.svg/28px-Flag_of_Brunei.svg.png', NULL, NULL),
(102, 'Cambodia', 'KH', 855, '/img/flags/102.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_Cambodia.svg/28px-Flag_of_Cambodia.svg.png', NULL, NULL),
(103, 'China', 'CN', 86, '/img/flags/103.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_the_People%27s_Republic_of_China.svg/28px-Flag_of_the_People%27s_Republic_of_China.svg.png', NULL, NULL),
(104, 'East Timor', 'TL', 670, '/img/flags/104.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Flag_of_East_Timor.svg/28px-Flag_of_East_Timor.svg.png', NULL, NULL),
(105, 'Hong Kong', 'HK', 852, '/img/flags/105.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/28px-Flag_of_Japan.svg.png', NULL, NULL),
(106, 'India', 'IN', 91, '/img/flags/106.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/4/41/Flag_of_India.svg/28px-Flag_of_India.svg.png', NULL, NULL),
(107, 'Indonesia', 'ID', 62, '/img/flags/107.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Flag_of_Indonesia.svg/28px-Flag_of_Indonesia.svg.png', NULL, NULL),
(108, 'Iran', 'IR', 98, '/img/flags/108.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Flag_of_Iran.svg/28px-Flag_of_Iran.svg.png', NULL, NULL),
(109, 'Iraq', 'IQ', 964, '/img/flags/109.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Flag_of_Iraq.svg/28px-Flag_of_Iraq.svg.png', NULL, NULL),
(110, 'Israel', 'IL', 972, '/img/flags/110.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Flag_of_Israel.svg/28px-Flag_of_Israel.svg.png', NULL, NULL),
(111, 'Japan', 'JP', 81, '/img/flags/111.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/28px-Flag_of_Japan.svg.png', NULL, NULL),
(112, 'Jordan', 'JO', 962, '/img/flags/112.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/c0/Flag_of_Jordan.svg/28px-Flag_of_Jordan.svg.png', NULL, NULL),
(113, 'Kazakhstan', 'KZ', 7, '/img/flags/113.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/Flag_of_Kazakhstan.svg/28px-Flag_of_Kazakhstan.svg.png', NULL, NULL),
(114, 'Kuwait', 'KW', 965, '/img/flags/114.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/a/aa/Flag_of_Kuwait.svg/28px-Flag_of_Kuwait.svg.png', NULL, NULL),
(115, 'Kyrgyzstan', 'KG', 996, '/img/flags/115.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/c7/Flag_of_Kyrgyzstan.svg/28px-Flag_of_Kyrgyzstan.svg.png', NULL, NULL),
(116, 'Laos', 'LA', 856, '/img/flags/116.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Flag_of_Laos.svg/28px-Flag_of_Laos.svg.png', NULL, NULL),
(117, 'Lebanon', 'LB', 961, '/img/flags/117.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/59/Flag_of_Lebanon.svg/28px-Flag_of_Lebanon.svg.png', NULL, NULL),
(118, 'Macau', 'MO', 853, '/img/flags/118.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/63/Flag_of_Macau.svg/28px-Flag_of_Macau.svg.png', NULL, NULL),
(119, 'Malaysia', 'MY', 60, '/img/flags/119.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/66/Flag_of_Malaysia.svg/28px-Flag_of_Malaysia.svg.png', NULL, NULL),
(120, 'Maldives', 'MV', 960, '/img/flags/120.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Maldives.svg/28px-Flag_of_Maldives.svg.png', NULL, NULL),
(121, 'Mongolia', 'MN', 976, '/img/flags/121.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Flag_of_Mongolia.svg/28px-Flag_of_Mongolia.svg.png', NULL, NULL),
(122, 'Myanmar (Burma)', 'MM', 95, '/img/flags/122.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Flag_of_Myanmar.svg/28px-Flag_of_Myanmar.svg.png', NULL, NULL),
(123, 'Nepal', 'NP', 977, '/img/flags/123.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Flag_of_Nepal.svg/16px-Flag_of_Nepal.svg.png', NULL, NULL),
(124, 'North Korea', 'NP', 850, '/img/flags/124.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Flag_of_North_Korea.svg/28px-Flag_of_North_Korea.svg.png', NULL, NULL),
(125, 'Oman', 'OM', 968, '/img/flags/125.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Flag_of_Oman.svg/28px-Flag_of_Oman.svg.png', NULL, NULL),
(126, 'Pakistan', 'PK', 92, '/img/flags/126.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/32/Flag_of_Pakistan.svg/28px-Flag_of_Pakistan.svg.png', NULL, NULL),
(127, 'Philippines', 'PH', 63, '/img/flags/127.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_the_Philippines.svg/28px-Flag_of_the_Philippines.svg.png', NULL, NULL),
(128, 'Qatar', 'QA', 974, '/img/flags/128.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/65/Flag_of_Qatar.svg/28px-Flag_of_Qatar.svg.png', NULL, NULL),
(129, 'Saudi Arabia', 'SA', 966, '/img/flags/129.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/0d/Flag_of_Saudi_Arabia.svg/28px-Flag_of_Saudi_Arabia.svg.png', NULL, NULL),
(130, 'Singapore', 'SG', 65, '/img/flags/130.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Singapore.svg/28px-Flag_of_Singapore.svg.png', NULL, NULL),
(131, 'South Korea', 'KR', 82, '/img/flags/131.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Flag_of_South_Korea.svg/28px-Flag_of_South_Korea.svg.png', NULL, NULL),
(132, 'Sri Lanka', 'LK', 94, '/img/flags/132.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Flag_of_Sri_Lanka.svg/28px-Flag_of_Sri_Lanka.svg.png', NULL, NULL),
(133, 'Syria', 'SY', 963, '/img/flags/133.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Flag_of_Syria.svg/28px-Flag_of_Syria.svg.png', NULL, NULL),
(134, 'Taiwan', 'TW', 886, '/img/flags/134.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Flag_of_the_Republic_of_China.svg/28px-Flag_of_the_Republic_of_China.svg.png', NULL, NULL),
(135, 'Tajikistan', 'TJ', 992, '/img/flags/135.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Flag_of_Tajikistan.svg/28px-Flag_of_Tajikistan.svg.png', NULL, NULL),
(136, 'Thailand', 'TH', 66, '/img/flags/136.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Flag_of_Thailand.svg/28px-Flag_of_Thailand.svg.png', NULL, NULL),
(137, 'Turkmenistan', 'TM', 993, '/img/flags/137.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Flag_of_Turkmenistan.svg/28px-Flag_of_Turkmenistan.svg.png', NULL, NULL),
(138, 'United Arab Emirates', 'AE', 971, '/img/flags/138.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Flag_of_the_United_Arab_Emirates.svg/28px-Flag_of_the_United_Arab_Emirates.svg.png', NULL, NULL),
(139, 'Uzbekistan', 'UZ', 998, '/img/flags/139.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Flag_of_Uzbekistan.svg/28px-Flag_of_Uzbekistan.svg.png', NULL, NULL),
(140, 'Vietnam', 'VN', 84, '/img/flags/140.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Flag_of_Vietnam.svg/28px-Flag_of_Vietnam.svg.png', NULL, NULL),
(141, 'Yemen', 'YE', 967, '/img/flags/141.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Flag_of_Yemen.svg/28px-Flag_of_Yemen.svg.png', NULL, NULL),
(142, 'Algeria', 'DZ', 213, '/img/flags/142.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Flag_of_Algeria.svg/28px-Flag_of_Algeria.svg.png', NULL, NULL),
(143, 'Angola', 'AO', 244, '/img/flags/143.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/Flag_of_Angola.svg/28px-Flag_of_Angola.svg.png', NULL, NULL),
(144, 'Benin', 'BJ', 229, '/img/flags/144.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/0a/Flag_of_Benin.svg/28px-Flag_of_Benin.svg.png', NULL, NULL),
(145, 'Botswana', 'BW', 267, '/img/flags/145.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_Botswana.svg/28px-Flag_of_Botswana.svg.png', NULL, NULL),
(146, 'Burkina Faso', 'BF', 226, '/img/flags/146.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Flag_of_Burkina_Faso.svg/28px-Flag_of_Burkina_Faso.svg.png', NULL, NULL),
(147, 'Burundi', 'BI', 257, '/img/flags/147.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/5/50/Flag_of_Burundi.svg/28px-Flag_of_Burundi.svg.png', NULL, NULL),
(148, 'Cameroon', 'CM', 237, '/img/flags/148.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Flag_of_Cameroon.svg/28px-Flag_of_Cameroon.svg.png', NULL, NULL),
(149, 'Cape Verde', 'CV', 238, '/img/flags/149.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Flag_of_Cape_Verde.svg/28px-Flag_of_Cape_Verde.svg.png', NULL, NULL),
(150, 'Central African Republic', 'CF', 236, '/img/flags/150.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Flag_of_the_Central_African_Republic.svg/28px-Flag_of_the_Central_African_Republic.svg.png', NULL, NULL),
(151, 'Chad', 'TD', 235, '/img/flags/151.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Flag_of_Chad.svg/28px-Flag_of_Chad.svg.png', NULL, NULL),
(152, 'Congo-Brazzaville', 'CG', 242, '/img/flags/152.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Flag_of_the_Republic_of_the_Congo.svg/28px-Flag_of_the_Republic_of_the_Congo.svg.png', NULL, NULL),
(153, 'Congo-Kinshasa', 'CD', 242, '/img/flags/153.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Flag_of_the_Democratic_Republic_of_the_Congo.svg/28px-Flag_of_the_Democratic_Republic_of_the_Congo.svg.png', NULL, NULL),
(154, 'Djibouti', 'DJ', 253, '/img/flags/154.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Flag_of_Djibouti.svg/28px-Flag_of_Djibouti.svg.png', NULL, NULL),
(155, 'Egypt', 'EG', 20, '/img/flags/155.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_Egypt.svg/28px-Flag_of_Egypt.svg.png', NULL, NULL),
(156, 'Equatorial Guinea', 'GQ', 240, '/img/flags/156.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Flag_of_Equatorial_Guinea.svg/28px-Flag_of_Equatorial_Guinea.svg.png', NULL, NULL),
(157, 'Eritrea', 'ER', 291, '/img/flags/157.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/29/Flag_of_Eritrea.svg/28px-Flag_of_Eritrea.svg.png', NULL, NULL),
(158, 'Ethiopia', 'ET', 251, '/img/flags/158.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/71/Flag_of_Ethiopia.svg/28px-Flag_of_Ethiopia.svg.png', NULL, NULL),
(159, 'Gabon', 'GA', 241, '/img/flags/159.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Flag_of_Gabon.svg/28px-Flag_of_Gabon.svg.png', NULL, NULL),
(160, 'Gambia', 'GM', 220, '/img/flags/160.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Flag_of_The_Gambia.svg/28px-Flag_of_The_Gambia.svg.png', NULL, NULL),
(161, 'Ghana', 'GH', 233, '/img/flags/161.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Ghana.svg/28px-Flag_of_Ghana.svg.png', NULL, NULL),
(162, 'Guinea', 'GN', 224, '/img/flags/162.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/ed/Flag_of_Guinea.svg/28px-Flag_of_Guinea.svg.png', NULL, NULL),
(163, 'Guinea-Bissau', 'GW', 245, '/img/flags/163.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/01/Flag_of_Guinea-Bissau.svg/28px-Flag_of_Guinea-Bissau.svg.png', NULL, NULL),
(164, 'Ivory Coast', 'CI', 225, '/img/flags/164.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_C%C3%B4te_d%27Ivoire.svg/28px-Flag_of_C%C3%B4te_d%27Ivoire.svg.png', NULL, NULL),
(165, 'Kenya', 'KE', 254, '/img/flags/165.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Flag_of_Kenya.svg/28px-Flag_of_Kenya.svg.png', NULL, NULL),
(166, 'Lesotho', 'LS', 266, '/img/flags/166.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/4a/Flag_of_Lesotho.svg/28px-Flag_of_Lesotho.svg.png', NULL, NULL),
(167, 'Liberia', 'LR', 231, '/img/flags/167.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/Flag_of_Liberia.svg/28px-Flag_of_Liberia.svg.png', NULL, NULL),
(168, 'Libya', 'LY', 218, '/img/flags/168.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Flag_of_Libya.svg/28px-Flag_of_Libya.svg.png', NULL, NULL),
(169, 'Madagascar', 'MG', 261, '/img/flags/169.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Madagascar.svg/28px-Flag_of_Madagascar.svg.png', NULL, NULL),
(170, 'Malawi', 'MW', 265, '/img/flags/170.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Flag_of_Malawi.svg/28px-Flag_of_Malawi.svg.png', NULL, NULL),
(171, 'Mali', 'ML', 223, '/img/flags/171.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Flag_of_Mali.svg/28px-Flag_of_Mali.svg.png', NULL, NULL),
(172, 'Mauritania', 'MR', 222, '/img/flags/172.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/43/Flag_of_Mauritania.svg/28px-Flag_of_Mauritania.svg.png', NULL, NULL),
(173, 'Mauritius', 'MU', 230, '/img/flags/173.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Flag_of_Mauritius.svg/28px-Flag_of_Mauritius.svg.png', NULL, NULL),
(174, 'Morocco', 'MA', 212, '/img/flags/174.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Flag_of_Morocco.svg/28px-Flag_of_Morocco.svg.png', NULL, NULL),
(175, 'Mozambique', 'MZ', 258, '/img/flags/175.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Flag_of_Mozambique.svg/28px-Flag_of_Mozambique.svg.png', NULL, NULL),
(176, 'Namibia', 'NA', 264, '/img/flags/176.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/00/Flag_of_Namibia.svg/28px-Flag_of_Namibia.svg.png', NULL, NULL),
(177, 'Niger', 'NE', 227, '/img/flags/177.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f4/Flag_of_Niger.svg/28px-Flag_of_Niger.svg.png', NULL, NULL),
(178, 'Nigeria', 'NG', 234, '/img/flags/178.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/79/Flag_of_Nigeria.svg/28px-Flag_of_Nigeria.svg.png', NULL, NULL),
(179, 'Reunion', 'RE', 262, '/img/flags/179.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png', NULL, NULL),
(180, 'Rwanda', 'RW', 250, '/img/flags/180.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Flag_of_Rwanda.svg/28px-Flag_of_Rwanda.svg.png', NULL, NULL),
(181, 'Sao Tome and Principe', 'ST', 239, '/img/flags/181.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Flag_of_Sao_Tome_and_Principe.svg/28px-Flag_of_Sao_Tome_and_Principe.svg.png', NULL, NULL),
(182, 'Senegal', 'SN', 221, '/img/flags/182.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fd/Flag_of_Senegal.svg/28px-Flag_of_Senegal.svg.png', NULL, NULL),
(183, 'Seychelles', 'SC', 248, '/img/flags/183.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/Flag_of_Seychelles.svg/28px-Flag_of_Seychelles.svg.png', NULL, NULL),
(184, 'Sierra Leone', 'SL', 232, '/img/flags/184.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Flag_of_Sierra_Leone.svg/28px-Flag_of_Sierra_Leone.svg.png', NULL, NULL),
(185, 'Somalia', 'SO', 252, '/img/flags/185.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Flag_of_Somalia.svg/28px-Flag_of_Somalia.svg.png', NULL, NULL),
(186, 'South Africa', 'ZA', 27, '/img/flags/186.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Flag_of_South_Africa.svg/28px-Flag_of_South_Africa.svg.png', NULL, NULL),
(187, 'Sudan', 'SD', 249, '/img/flags/187.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/01/Flag_of_Sudan.svg/28px-Flag_of_Sudan.svg.png', NULL, NULL),
(188, 'Swaziland', 'SZ', 268, '/img/flags/188.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/Flag_of_Swaziland.svg/28px-Flag_of_Swaziland.svg.png', NULL, NULL),
(189, 'Tanzania', 'TZ', 255, '/img/flags/189.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Flag_of_Tanzania.svg/28px-Flag_of_Tanzania.svg.png', NULL, NULL),
(190, 'Togo', 'TG', 228, '/img/flags/190.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/68/Flag_of_Togo.svg/28px-Flag_of_Togo.svg.png', NULL, NULL),
(191, 'Tunisia', 'TN', 216, '/img/flags/191.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/c/ce/Flag_of_Tunisia.svg/28px-Flag_of_Tunisia.svg.png', NULL, NULL),
(192, 'Uganda', 'UG', 256, '/img/flags/192.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Flag_of_Uganda.svg/28px-Flag_of_Uganda.svg.png', NULL, NULL),
(193, 'Western Sahara', 'EH', 212, '/img/flags/193.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Flag_of_the_Sahrawi_Arab_Democratic_Republic.svg/28px-Flag_of_the_Sahrawi_Arab_Democratic_Republic.svg.png', NULL, NULL),
(194, 'Zambia', 'ZM', 260, '/img/flags/194.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/06/Flag_of_Zambia.svg/28px-Flag_of_Zambia.svg.png', NULL, NULL),
(195, 'Zimbabwe', 'ZW', 263, '/img/flags/195.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/Flag_of_Zimbabwe.svg/28px-Flag_of_Zimbabwe.svg.png', NULL, NULL),
(196, 'Australia', 'AU', 61, '/img/flags/196.png', 'http://upload.wikimedia.org/wikipedia/en/thumb/b/b9/Flag_of_Australia.svg/28px-Flag_of_Australia.svg.png', NULL, 1),
(197, 'New Zealand', 'NZ', 64, '/img/flags/197.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Flag_of_New_Zealand.svg/28px-Flag_of_New_Zealand.svg.png', NULL, NULL),
(198, 'Fiji', 'FJ', 679, '/img/flags/198.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Flag_of_Fiji.svg/28px-Flag_of_Fiji.svg.png', NULL, NULL),
(199, 'French Polynesia', 'PF', 689, '/img/flags/199.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/db/Flag_of_French_Polynesia.svg/28px-Flag_of_French_Polynesia.svg.png', NULL, NULL),
(200, 'Guam', 'GU', 671, '/img/flags/200.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/07/Flag_of_Guam.svg/28px-Flag_of_Guam.svg.png', NULL, NULL),
(201, 'Kiribati', 'KI', 686, '/img/flags/201.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/Flag_of_Kiribati.svg/28px-Flag_of_Kiribati.svg.png', NULL, NULL),
(202, 'Marshall Islands', 'MH', 692, '/img/flags/202.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Flag_of_the_Marshall_Islands.svg/28px-Flag_of_the_Marshall_Islands.svg.png', NULL, NULL),
(203, 'Micronesia', 'FM', 691, '/img/flags/203.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/e4/Flag_of_the_Federated_States_of_Micronesia.svg/28px-Flag_of_the_Federated_States_of_Micronesia.svg.png', NULL, NULL),
(204, 'Nauru', 'NR', 674, '/img/flags/204.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/30/Flag_of_Nauru.svg/28px-Flag_of_Nauru.svg.png', NULL, NULL),
(205, 'New Caledonia', 'NC', 687, '/img/flags/205.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Flag_of_New_Caledonia.svg/28px-Flag_of_New_Caledonia.svg.png', NULL, NULL),
(206, 'Papua New Guinea', 'PG', 675, '/img/flags/206.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/e3/Flag_of_Papua_New_Guinea.svg/28px-Flag_of_Papua_New_Guinea.svg.png', NULL, NULL),
(207, 'Samoa', 'WS', 684, '/img/flags/207.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Flag_of_Samoa.svg/28px-Flag_of_Samoa.svg.png', NULL, NULL),
(208, 'Solomon Islands', 'SB', 677, '/img/flags/208.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/74/Flag_of_the_Solomon_Islands.svg/28px-Flag_of_the_Solomon_Islands.svg.png', NULL, NULL),
(209, 'Tonga', 'TO', 676, '/img/flags/209.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Tonga.svg/28px-Flag_of_Tonga.svg.png', NULL, NULL),
(210, 'Tuvalu', 'TV', 688, '/img/flags/210.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Flag_of_Tuvalu.svg/28px-Flag_of_Tuvalu.svg.png', NULL, NULL),
(211, 'Vanuatu', 'VU', 678, '/img/flags/211.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Vanuatu.svg/28px-Flag_of_Vanuatu.svg.png', NULL, NULL),
(212, 'Wallis and Futuna', 'WF', 681, '/img/flags/212.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/d/d2/Flag_of_Wallis_and_Futuna.svg/28px-Flag_of_Wallis_and_Futuna.svg.png', NULL, NULL),
(213, 'South Sudan', 'SS', 211, '/img/flags/213.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/7/7a/Flag_of_South_Sudan.svg/28px-Flag_of_South_Sudan.svg.png', NULL, NULL),
(214, 'Antigua and Barbuda', 'AG', 16, '/img/flags/214.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Flag_of_Antigua_and_Barbuda.svg/28px-Flag_of_Antigua_and_Barbuda.svg.png', NULL, NULL),
(215, 'Aruba', 'AW', 533, '/img/flags/215.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Flag_of_Aruba.svg/28px-Flag_of_Aruba.svg.png', NULL, NULL),
(216, 'Comoros', 'KM', 174, '/img/flags/216.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/94/Flag_of_the_Comoros.svg/28px-Flag_of_the_Comoros.svg.png', NULL, NULL),
(217, 'Cook Islands', 'CK', 184, '/img/flags/217.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/35/Flag_of_the_Cook_Islands.svg/28px-Flag_of_the_Cook_Islands.svg.png', NULL, NULL),
(218, 'Faroe Islands', 'FO', 234, '/img/flags/218.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Flag_of_the_Faroe_Islands.svg/28px-Flag_of_the_Faroe_Islands.svg.png', NULL, NULL),
(219, 'Niue', 'NU', 570, '/img/flags/219.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/0/01/Flag_of_Niue.svg/28px-Flag_of_Niue.svg.png', NULL, NULL),
(220, 'Palau', 'PW', 585, '/img/flags/220.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Palau.svg/28px-Flag_of_Palau.svg.png', NULL, NULL),
(221, 'Saint Kitts and Nevis', 'KN', 659, '/img/flags/221.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_Saint_Kitts_and_Nevis.svg/28px-Flag_of_Saint_Kitts_and_Nevis.svg.png', NULL, NULL),
(222, 'Saint Lucia', 'LC', 662, '/img/flags/222.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Flag_of_Saint_Lucia.svg/28px-Flag_of_Saint_Lucia.svg.png', NULL, NULL),
(223, 'Saint Vincent and the Grenadines', 'VC', 670, '/img/flags/223.png', 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/6d/Flag_of_Saint_Vincent_and_the_Grenadines.svg/28px-Flag_of_Saint_Vincent_and_the_Grenadines.svg.png', NULL, NULL);

ALTER TABLE `country`
  ADD CONSTRAINT `country_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`),
  ADD CONSTRAINT `country_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);


-- okh 04.07
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('ga-account-id', '');
