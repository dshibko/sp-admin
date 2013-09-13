SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE `league_user`;
ALTER TABLE `league_user` AUTO_INCREMENT = 1;
TRUNCATE `season`;
ALTER TABLE `season` AUTO_INCREMENT = 1;
TRUNCATE `season_region`;
ALTER TABLE `season_region` AUTO_INCREMENT = 1;
TRUNCATE `featured_goalkeeper`;
ALTER TABLE `featured_goalkeeper` AUTO_INCREMENT = 1;
TRUNCATE `featured_player`;
ALTER TABLE `featured_player` AUTO_INCREMENT = 1;
TRUNCATE `featured_prediction`;
ALTER TABLE `featured_prediction` AUTO_INCREMENT = 1;
TRUNCATE `feed`;
ALTER TABLE `feed` AUTO_INCREMENT = 1;
TRUNCATE `league`;
ALTER TABLE `league` AUTO_INCREMENT = 1;
TRUNCATE `league_region`;
ALTER TABLE `league_region` AUTO_INCREMENT = 1;
TRUNCATE `league_user_place`;
ALTER TABLE `league_user_place` AUTO_INCREMENT = 1;
TRUNCATE `line_up`;
ALTER TABLE `line_up` AUTO_INCREMENT = 1;
TRUNCATE `match`;
ALTER TABLE `match` AUTO_INCREMENT = 1;
TRUNCATE `match_goal`;
ALTER TABLE `match_goal` AUTO_INCREMENT = 1;
TRUNCATE `match_region`;
ALTER TABLE `match_region` AUTO_INCREMENT = 1;
TRUNCATE `player_competition`;
ALTER TABLE `player_competition` AUTO_INCREMENT = 1;
TRUNCATE `prediction`;
ALTER TABLE `prediction` AUTO_INCREMENT = 1;
TRUNCATE `prediction_player`;
ALTER TABLE `prediction_player` AUTO_INCREMENT = 1;
TRUNCATE `prize`;
ALTER TABLE `prize` AUTO_INCREMENT = 1;
TRUNCATE `team_competition`;
ALTER TABLE `team_competition` AUTO_INCREMENT = 1;

update country set region_id = null;
DROP TABLE IF EXISTS `region`;
CREATE TABLE IF NOT EXISTS `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(50) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;
INSERT INTO `region` (`id`, `display_name`, `is_default`) VALUES
(1, 'English', 1),
(2, 'Russian', 0),
(3, 'Chinese', 0),
(4, 'Japanese', 0),
(5, 'Indonesian', 0),
(6, 'Korean', 0),
(7, 'Thai', 0);

UPDATE  `country` SET  `region_id` =  '1' WHERE  `country`.`id` =1 ;
UPDATE  `country` SET  `region_id` =  '1' WHERE  `country`.`id` =2 ;
UPDATE  `country` SET  `region_id` =  '1' WHERE  `country`.`id` =95 ;
UPDATE  `country` SET  `region_id` =  '1' WHERE  `country`.`id` =196 ;
UPDATE  `country` SET  `region_id` =  '2' WHERE  `country`.`id` =85 ;
UPDATE  `country` SET  `region_id` =  '3' WHERE  `country`.`id` =103 ;
UPDATE  `country` SET  `region_id` =  '3' WHERE  `country`.`id` =103 ;
UPDATE  `country` SET  `region_id` =  '4' WHERE  `country`.`id` =111 ;
UPDATE  `country` SET  `region_id` =  '5' WHERE  `country`.`id` =107 ;
UPDATE  `country` SET  `region_id` =  '6' WHERE  `country`.`id` =124 ;
UPDATE  `country` SET  `region_id` =  '6' WHERE  `country`.`id` =131 ;
UPDATE  `country` SET  `region_id` =  '7' WHERE  `country`.`id` =136 ;

SET FOREIGN_KEY_CHECKS = 1;