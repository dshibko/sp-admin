SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Table structure for table `account_removal`
--
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `account_removal`;
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

DROP TABLE IF EXISTS `achievement_block`;
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
DROP TABLE IF EXISTS `avatar`;
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

DROP TABLE IF EXISTS `competition`;
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `content_image`
--

DROP TABLE IF EXISTS `content_image`;
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

DROP TABLE IF EXISTS `country`;CREATE TABLE IF NOT EXISTS  `country` (
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

--
-- Table structure for table `default_report_content`
--

DROP TABLE IF EXISTS `default_report_content`;CREATE TABLE IF NOT EXISTS  `default_report_content` (
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

DROP TABLE IF EXISTS `emblem`;CREATE TABLE IF NOT EXISTS  `emblem` (
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

DROP TABLE IF EXISTS `featured_goalkeeper`;CREATE TABLE IF NOT EXISTS  `featured_goalkeeper` (
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

DROP TABLE IF EXISTS `featured_player`;CREATE TABLE IF NOT EXISTS  `featured_player` (
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

DROP TABLE IF EXISTS `featured_prediction`;CREATE TABLE IF NOT EXISTS  `featured_prediction` (
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

DROP TABLE IF EXISTS `feed`;CREATE TABLE IF NOT EXISTS  `feed` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `type` enum('F1','F2','F7','F40') DEFAULT NULL,
  `last_sync_result` enum('Success','Error') NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_name` (`file_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `footer_image`
--

DROP TABLE IF EXISTS `footer_image`;CREATE TABLE IF NOT EXISTS  `footer_image` (
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

DROP TABLE IF EXISTS `footer_page`;CREATE TABLE IF NOT EXISTS  `footer_page` (
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

DROP TABLE IF EXISTS `footer_social`;CREATE TABLE IF NOT EXISTS  `footer_social` (
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

DROP TABLE IF EXISTS `language`;CREATE TABLE IF NOT EXISTS  `language` (
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

DROP TABLE IF EXISTS `league`;CREATE TABLE IF NOT EXISTS  `league` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `league_region`
--

DROP TABLE IF EXISTS `league_region`;CREATE TABLE IF NOT EXISTS  `league_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `league_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `league_id` (`league_id`),
  KEY `region_id` (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `league_user`
--

DROP TABLE IF EXISTS `league_user`;CREATE TABLE IF NOT EXISTS  `league_user` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `league_user_place`
--

DROP TABLE IF EXISTS `league_user_place`;CREATE TABLE IF NOT EXISTS  `league_user_place` (
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

DROP TABLE IF EXISTS `line_up`;CREATE TABLE IF NOT EXISTS  `line_up` (
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

DROP TABLE IF EXISTS `logotype`;CREATE TABLE IF NOT EXISTS  `logotype` (
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

DROP TABLE IF EXISTS `match`;CREATE TABLE IF NOT EXISTS  `match` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Table structure for table `match_goal`
--

DROP TABLE IF EXISTS `match_goal`;CREATE TABLE IF NOT EXISTS  `match_goal` (
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

DROP TABLE IF EXISTS `match_region`;CREATE TABLE IF NOT EXISTS  `match_region` (
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

DROP TABLE IF EXISTS `permission`;CREATE TABLE IF NOT EXISTS  `permission` (
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

DROP TABLE IF EXISTS `player`;CREATE TABLE IF NOT EXISTS  `player` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `player_competition`
--

DROP TABLE IF EXISTS `player_competition`;CREATE TABLE IF NOT EXISTS  `player_competition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`),
  KEY `competition_id` (`competition_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `prediction`
--

DROP TABLE IF EXISTS `prediction`;CREATE TABLE IF NOT EXISTS  `prediction` (
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

DROP TABLE IF EXISTS `prediction_player`;CREATE TABLE IF NOT EXISTS  `prediction_player` (
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

DROP TABLE IF EXISTS `prize`;CREATE TABLE IF NOT EXISTS  `prize` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `prize`
--

-- INSERT INTO `prize` (`id`, `league_id`, `region_id`, `prize_title`, `prize_description`, `prize_image`, `post_win_title`, `post_win_description`, `post_win_image`) VALUES
-- (1, 1, 1, 'GRAND P-RIZE 2013-14 SEASON', '<h2>Visit Chelsea&rsquo;s training ground</h2>\r\n\r\n<p>Win the chance to visit Chelsea&rsquo;s exclusive Training Ground in Cobham to see the players in a training session, an amazing prize for any Chelsea fan, as well as a bounty of CFC merchandise and products from our sponsors.</p>\r\n\r\n<footer>\r\n<p>Winners announced <time>19 May 2013</time></p>\r\n</footer>\r\n', '/img/prizes/51d0d291b7a45.png', 'GRAND P-RIZE 2013-14 SEASON', '<h2>Visit Chelsea&rsquo;s training ground</h2>\r\n\r\n<p>Win the chance to visit Chelsea&rsquo;s exclusive Training Ground in Cobham to see the players in a training session, an amazing prize for any Chelsea fan, as well as a bounty of CFC merchandise and products from our sponsors.</p>\r\n\r\n<footer>\r\n<p>Winners announced <time>19 May 2013</time></p>\r\n</footer>\r\n', '/img/prizes/51d0d291b857f.png');

-- --------------------------------------------------------

--
-- Table structure for table `recovery`
--

DROP TABLE IF EXISTS `recovery`;CREATE TABLE IF NOT EXISTS  `recovery` (
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

DROP TABLE IF EXISTS `region`;CREATE TABLE IF NOT EXISTS  `region` (
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

DROP TABLE IF EXISTS `region_content`;CREATE TABLE IF NOT EXISTS  `region_content` (
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

DROP TABLE IF EXISTS `region_gameplay_content`;CREATE TABLE IF NOT EXISTS  `region_gameplay_content` (
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

DROP TABLE IF EXISTS `role`;CREATE TABLE IF NOT EXISTS  `role` (
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

DROP TABLE IF EXISTS `role_permission`;CREATE TABLE IF NOT EXISTS  `role_permission` (
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

DROP TABLE IF EXISTS `season`;CREATE TABLE IF NOT EXISTS  `season` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `feeder_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- INSERT INTO `season` (`id`, `display_name`, `start_date`, `end_date`, `feeder_id`) VALUES
-- (1, '2013/2014', '2013-07-01', '2014-06-01', 2013);

-- --------------------------------------------------------

--
-- Table structure for table `season_region`
--

DROP TABLE IF EXISTS `season_region`;CREATE TABLE IF NOT EXISTS  `season_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `season_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `terms` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `season_id` (`season_id`),
  KEY `region_id` (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `season_region`
--

-- INSERT INTO `season_region` (`id`, `season_id`, `region_id`, `display_name`, `terms`) VALUES
-- (1, 1, 1, '2013/2014', '<p>Play and enjoy.</p>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;CREATE TABLE IF NOT EXISTS  `settings` (
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

DROP TABLE IF EXISTS `share_copy`;CREATE TABLE IF NOT EXISTS  `share_copy` (
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

DROP TABLE IF EXISTS `team`;CREATE TABLE IF NOT EXISTS  `team` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


--
-- Table structure for table `team_competition`
--

DROP TABLE IF EXISTS `team_competition`;CREATE TABLE IF NOT EXISTS  `team_competition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`),
  KEY `competition_id` (`competition_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `term`
--

DROP TABLE IF EXISTS `term`;CREATE TABLE IF NOT EXISTS  `term` (
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

DROP TABLE IF EXISTS `term_copy`;CREATE TABLE IF NOT EXISTS  `term_copy` (
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

DROP TABLE IF EXISTS `user`;CREATE TABLE IF NOT EXISTS  `user` (
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

-- INSERT INTO `user` (`id`, `title`, `first_name`, `last_name`, `email`, `password`, `country_id`, `birthday`, `gender`, `display_name`, `avatar_id`, `language_id`, `role_id`, `favourite_player_id`, `is_active`, `is_public`, `facebook_id`, `facebook_access_token`, `date`, `last_logged_in`) VALUES
-- (1, 'Mr.', 'Super', 'Admin', 'super@admin.com', '$1$8a/.vj/.$9VT7aco9Go/VEHRjSnHpa/', 95, '1987-10-31', 'Male', 'Super Admin', 1, 1, 1, NULL, 1, 1, NULL, NULL, '2013-05-04 15:43:00', '2013-06-30 23:20:07');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `competition`
--
SET FOREIGN_KEY_CHECKS = 1;
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

-- okh 04.07
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('ga-account-id', '');

-- oko 04.07

ALTER TABLE  `league_user` ADD  `correct_results` INT NOT NULL AFTER  `accuracy` ,
ADD  `correct_scores` INT NOT NULL AFTER  `correct_results` ,
ADD  `correct_scorers` INT NOT NULL AFTER  `correct_scores` ,
ADD  `correct_scorers_order` INT NOT NULL AFTER  `correct_scorers` ,
ADD  `predictions_players_count` INT NOT NULL AFTER  `correct_scorers_order` ,
ADD  `predictions_count` INT NOT NULL AFTER  `predictions_players_count`,
ADD  `registration_date` DATETIME NOT NULL AFTER  `previous_place`;

ALTER TABLE  `feed` CHANGE  `last_sync_result`  `last_sync_result` ENUM(  'Success',  'Error',  'InProgress' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

-- okh 08.07

ALTER TABLE `user`  ADD COLUMN `term1` TINYINT(1) NULL DEFAULT NULL AFTER `last_logged_in`,  ADD COLUMN `term2` TINYINT(1) NULL DEFAULT NULL AFTER `term1`;
