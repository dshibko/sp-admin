ALTER TABLE `footer_image`  DROP INDEX `region_id`,  DROP FOREIGN KEY `footer_image_ibfk_1`;
ALTER TABLE `footer_image`  CHANGE COLUMN `region_id` `language_id` INT(11) NOT NULL AFTER `id`,  ADD INDEX `language_id` (`language_id`);
ALTER TABLE `footer_image`  ADD CONSTRAINT `FK_footer_image_language` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);

CREATE TABLE IF NOT EXISTS `match_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `match_language`
  ADD CONSTRAINT `FK_match_language_featured_goalkeeper` FOREIGN KEY (`featured_goalkeeper_id`) REFERENCES `featured_goalkeeper` (`id`),
  ADD CONSTRAINT `FK_match_language_featured_player` FOREIGN KEY (`featured_player_id`) REFERENCES `featured_player` (`id`),
  ADD CONSTRAINT `FK_match_language_featured_prediction` FOREIGN KEY (`featured_prediction_id`) REFERENCES `featured_prediction` (`id`),
  ADD CONSTRAINT `match_language_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`),
  ADD CONSTRAINT `match_language_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);

INSERT INTO match_language( `id`, `match_id`, `language_id`, `featured_player_id`, `featured_goalkeeper_id`, `featured_prediction_id`, `pre_match_report_title`, `pre_match_report_intro`, `pre_match_report_header_image_path`, `post_match_report_title`, `post_match_report_intro`, `post_match_report_header_image_path`, `display_featured_player` )
SELECT  `match_region`.`id` ,  `match_region`.`match_id` ,  `match_region`.`region_id` AS language_id,  `match_region`.`featured_player_id` ,  `match_region`.`featured_goalkeeper_id` ,  `match_region`.`featured_prediction_id` , `match_region`.`pre_match_report_title` ,  `match_region`.`pre_match_report_intro` ,  `match_region`.`pre_match_report_header_image_path` ,  `match_region`.`post_match_report_title` ,  `match_region`.`post_match_report_intro` , `match_region`.`post_match_report_header_image_path` ,  `match_region`.`display_featured_player`
FROM  `match_region`
INNER JOIN  `match` ON  `match`.`id` =  `match_region`.`match_id`
INNER JOIN  `language` ON  `language`.`id` =  `match_region`.`region_id`;

DROP TABLE IF EXISTS `match_region`;

CREATE TABLE IF NOT EXISTS `default_report_content_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `intro` text NOT NULL,
  `header_image` varchar(255) NOT NULL,
  `language_id` int(11) NOT NULL,
  `report_type` enum('Pre-Match','Post-Match') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE  `default_report_content_tmp` ADD FOREIGN KEY (  `language_id` ) REFERENCES  `language` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

INSERT INTO `default_report_content_tmp` ( `id`, `title`, `intro`, `header_image`, `language_id`, `report_type`)
SELECT  `default_report_content`.`id` ,  `default_report_content`.`title`,  `default_report_content`.`intro` ,  `default_report_content`.`header_image` ,  `default_report_content`.`region_id` AS language_id ,  `default_report_content`.`report_type`
FROM  `default_report_content`
INNER JOIN  `language` ON  `language`.`id` =  `default_report_content`.`region_id`;

DROP TABLE `default_report_content`;

RENAME TABLE  `default_report_content_tmp` TO  `default_report_content` ;

CREATE TABLE IF NOT EXISTS `footer_social_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `url` varchar(500) NOT NULL,
  `copy` varchar(100) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `order` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE  `footer_social_tmp` ADD FOREIGN KEY (  `language_id` ) REFERENCES  `language` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

INSERT INTO `footer_social_tmp` ( `id`, `language_id`, `url`, `copy`, `icon`, `order`)
SELECT  `footer_social`.`id` ,  `footer_social`.`region_id` AS language_id,  `footer_social`.`url` ,  `footer_social`.`copy` ,  `footer_social`.`icon` ,  `footer_social`.`order`
FROM  `footer_social`
INNER JOIN  `language` ON  `language`.`id` =  `footer_social`.`region_id`;

DROP TABLE `footer_social`;

RENAME TABLE  `footer_social_tmp` TO  `footer_social` ;

CREATE TABLE IF NOT EXISTS `language_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `headline_copy` varchar(255) DEFAULT NULL,
  `register_button_copy` varchar(50) DEFAULT NULL,
  `hero_background_image_id` int(11) DEFAULT NULL,
  `hero_foreground_image_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE  `language_content` ADD FOREIGN KEY (  `language_id` ) REFERENCES  `language` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;
ALTER TABLE  `language_content` ADD FOREIGN KEY (  `hero_background_image_id` ) REFERENCES  `content_image` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;
ALTER TABLE  `language_content` ADD FOREIGN KEY (  `hero_foreground_image_id` ) REFERENCES  `content_image` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

INSERT INTO `language_content` ( `id`, `language_id`, `headline_copy`, `register_button_copy`, `hero_background_image_id`, `hero_foreground_image_id`)
SELECT  `region_content`.`id` ,  `region_content`.`region_id` AS language_id,  `region_content`.`headline_copy` ,  `region_content`.`register_button_copy` ,  `region_content`.`hero_background_image_id` ,  `region_content`.`hero_foreground_image_id`
FROM  `region_content`
INNER JOIN  `language` ON  `language`.`id` =  `region_content`.`region_id`;

DROP TABLE `region_content`;

CREATE TABLE IF NOT EXISTS `language_gameplay_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `foreground_image_id` int(11) DEFAULT NULL,
  `order` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

ALTER TABLE  `language_gameplay_content` ADD FOREIGN KEY (  `language_id` ) REFERENCES  `language` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;
ALTER TABLE  `language_gameplay_content` ADD FOREIGN KEY (  `foreground_image_id` ) REFERENCES  `content_image` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

INSERT INTO `language_gameplay_content` ( `id`, `language_id`, `heading`, `description`, `foreground_image_id`, `order`)
SELECT  `region_gameplay_content`.`id` ,  `region_gameplay_content`.`region_id` AS language_id,  `region_gameplay_content`.`heading` ,  `region_gameplay_content`.`description` ,  `region_gameplay_content`.`foreground_image_id` ,  `region_gameplay_content`.`order`
FROM  `region_gameplay_content`
INNER JOIN  `language` ON  `language`.`id` =  `region_gameplay_content`.`region_id`;

DROP TABLE `region_gameplay_content`;

CREATE TABLE `season_language` (
	id INT AUTO_INCREMENT NOT NULL,
	season_id INT NOT NULL,
	language_id INT NOT NULL,
	display_name VARCHAR(255) NULL,
	terms text NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `season_language` ADD FOREIGN KEY (season_id) REFERENCES `season`(id);
ALTER TABLE `season_language` ADD FOREIGN KEY (language_id) REFERENCES `language`(id);

INSERT INTO season_language( `id`, `season_id`, `language_id`, `display_name`, `terms`)
SELECT  s.`id`, s.`season_id`, s.`region_id`, s.`display_name`, s.`terms`
FROM  `season_region` s
INNER JOIN  `season` ON  `season`.`id` =  s.`season_id`
INNER JOIN  `language` ON  `language`.`id` =  s.`region_id`;

DROP TABLE `season_region`;

CREATE TABLE `league_language` (
	id INT AUTO_INCREMENT NOT NULL,
	league_id INT NOT NULL,
	language_id INT NOT NULL,
	display_name VARCHAR(255) NULL,
	prize_title VARCHAR(50) NULL,
	prize_description text NULL,
	prize_image VARCHAR(255) NULL,
	post_win_title VARCHAR(50) NULL,
	post_win_description text NULL,
	post_win_image VARCHAR(255) NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `league_language` ADD FOREIGN KEY (league_id) REFERENCES `league`(id);
ALTER TABLE `league_language` ADD FOREIGN KEY (language_id) REFERENCES `language`(id);

INSERT INTO `league_language`( `league_id`, `language_id`)
SELECT  l.`id`, la.`id`
FROM  `league` l
INNER JOIN  `language` la;

UPDATE `league_language` ll
INNER JOIN `prize` p ON p.league_id = ll.league_id and ll.language_id = p.region_id
SET ll.`prize_description` = p.`prize_description`,
ll.`prize_title` = p.`prize_title`, ll.`prize_image` = p.`prize_image`, ll.`post_win_title` = p.`post_win_title`,
ll.`post_win_description` = p.`post_win_description`, ll.`post_win_image` = p.`post_win_image`;

UPDATE `league_language` ll
INNER JOIN `league` l ON ll.league_id = l.id
SET ll.`display_name` = l.type;

DROP TABLE `prize`;

ALTER TABLE `league_region` DROP `display_name`;

ALTER TABLE  `default_report_content` CHANGE  `header_image`  `header_image` VARCHAR( 255 ) CHARACTER SET utf8 NULL;

CREATE TABLE IF NOT EXISTS `private_league` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`league_id` INT NOT NULL ,
`unique_hash` VARCHAR(10) NOT NULL
) ENGINE = InnoDB;
ALTER TABLE `private_league` ADD FOREIGN KEY (league_id) REFERENCES `league`(id);
ALTER TABLE `private_league` ADD UNIQUE INDEX `unique_hash` (`unique_hash`);

ALTER TABLE `user` DROP `is_active`;