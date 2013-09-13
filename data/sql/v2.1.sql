-- Iteration 2.1

-- dsh 15.08
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_type` enum('MatchReport','ClubUpdate') NOT NULL,
  `user_id` int(11) NOT NULL,
  `was_viewed` tinyint(1) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `message` ADD FOREIGN KEY (  `user_id` ) REFERENCES `user` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

DROP TABLE IF EXISTS `match_report_message`;
CREATE TABLE IF NOT EXISTS `match_report_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL,
  `prediction_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `match_report_message` ADD FOREIGN KEY (  `message_id` ) REFERENCES  `message` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

ALTER TABLE  `match_report_message` ADD FOREIGN KEY (  `prediction_id` ) REFERENCES  `prediction` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

-- dsh 19.08
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (NULL, 'tracking-code', '');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (NULL, 'default-skin-image', '0');

-- dsh 20.08
DROP TABLE IF EXISTS `colour_language`;
CREATE TABLE IF NOT EXISTS `colour_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `type` enum('ContentBackground','FooterBackground') NOT NULL,
  `colour` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `colour_language` ADD FOREIGN KEY (  `language_id` ) REFERENCES  `language` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

INSERT INTO `colour_language` (`language_id`, `type`, `colour`)
SELECT l.id, 'ContentBackground', s.setting_value FROM language l
INNER JOIN settings s ON s.setting_key = 'site-background-colour';

INSERT INTO `colour_language` (`language_id`, `type`, `colour`)
SELECT l.id, 'FooterBackground', s.setting_value FROM language l
INNER JOIN settings s ON s.setting_key = 'site-footer-colour';

DELETE FROM `settings` WHERE `setting_key` = 'site-background-colour' OR `setting_key` = 'site-footer-colour';

-- oko 20.08
ALTER TABLE `competition` DROP COLUMN `start_date`;
ALTER TABLE `competition` DROP COLUMN `end_date`;

DROP TABLE IF EXISTS `competition_season`;
CREATE TABLE `competition_season` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`competition_id` INT NOT NULL ,
`season_id` INT NOT NULL
) ENGINE = InnoDB;
ALTER TABLE `competition_season` ADD FOREIGN KEY (`competition_id`) REFERENCES `competition`(id);
ALTER TABLE `competition_season` ADD FOREIGN KEY (`season_id`) REFERENCES `season`(id);

INSERT INTO `competition_season` (`competition_id`, `season_id`)
SELECT `id`, `season_id` FROM
(SELECT uc.`id`, c.`season_id` FROM `competition` c
INNER JOIN (SELECT `id`, `feeder_id` FROM `competition` c GROUP BY c.`feeder_id`) uc using(`feeder_id`)
ORDER BY c.`id`
) c;

ALTER TABLE `player_competition` DROP FOREIGN KEY player_competition_ibfk_2;
ALTER TABLE `player_competition` CHANGE `competition_id` `competition_season_id` INT NOT NULL;
ALTER TABLE `player_competition` ADD FOREIGN KEY (`competition_season_id`) REFERENCES `competition_season`(id);

ALTER TABLE `team_competition` DROP FOREIGN KEY team_competition_ibfk_2;
ALTER TABLE `team_competition` CHANGE `competition_id` `competition_season_id` INT NOT NULL;
ALTER TABLE `team_competition` ADD FOREIGN KEY (`competition_season_id`) REFERENCES `competition_season`(id);

ALTER TABLE `match` DROP FOREIGN KEY match_ibfk_1;
ALTER TABLE `match` CHANGE `competition_id` `competition_season_id` INT NOT NULL;
ALTER TABLE `match` ADD FOREIGN KEY (`competition_season_id`) REFERENCES `competition_season`(id);

DELETE c FROM `competition` c
WHERE c.`id` NOT IN (SELECT DISTINCT `competition_id` FROM `competition_season`);

ALTER TABLE `competition` DROP FOREIGN KEY competition_ibfk_1;
ALTER TABLE `competition` DROP COLUMN season_id;

ALTER TABLE `competition_season` ADD UNIQUE competition_season_unique (competition_id, season_id);

ALTER TABLE `feed` ADD COLUMN `season_id` INT NOT NULL AFTER `type`;
UPDATE `feed` SET `season_id` = (SELECT `id` FROM `season` ORDER BY `id` DESC LIMIT 1);
ALTER TABLE `feed` ADD FOREIGN KEY (`season_id`) REFERENCES `season`(id);
ALTER TABLE `feed` DROP INDEX `file_name`;
ALTER TABLE `feed` ADD UNIQUE file_name_season_unique (file_name, season_id);

-- oko 22.08
DROP TABLE IF EXISTS `pre_match_report_head_to_head`;
CREATE TABLE `pre_match_report_head_to_head` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`match_id` INT NOT NULL,
`home_team_wins` INT NOT NULL,
`draws` INT NOT NULL,
`away_team_wins` INT NOT NULL
) ENGINE = InnoDB;
ALTER TABLE `pre_match_report_head_to_head` ADD FOREIGN KEY (`match_id`) REFERENCES `match`(id);
ALTER TABLE `pre_match_report_head_to_head` ADD UNIQUE INDEX `unique_match_id` (`match_id`);

DROP TABLE IF EXISTS `pre_match_report_goals_scored`;
CREATE TABLE `pre_match_report_goals_scored` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`match_id` INT NOT NULL,
`home_team_goals` INT NOT NULL,
`away_team_goals` INT NOT NULL
) ENGINE = InnoDB;
ALTER TABLE `pre_match_report_goals_scored` ADD FOREIGN KEY (`match_id`) REFERENCES `match`(id);
ALTER TABLE `pre_match_report_goals_scored` ADD UNIQUE INDEX `unique_match_id` (`match_id`);

DROP TABLE IF EXISTS `pre_match_report_form_guide`;
CREATE TABLE `pre_match_report_form_guide` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`match_id` INT NOT NULL,
`home_team_form` VARCHAR(5) NOT NULL,
`away_team_form` VARCHAR(5) NOT NULL
) ENGINE = InnoDB;
ALTER TABLE `pre_match_report_form_guide` ADD FOREIGN KEY (`match_id`) REFERENCES `match`(id);
ALTER TABLE `pre_match_report_form_guide` ADD UNIQUE INDEX `unique_match_id` (`match_id`);

DROP TABLE IF EXISTS `pre_match_report_avg_goals_scored`;
CREATE TABLE `pre_match_report_avg_goals_scored` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`match_id` INT NOT NULL,
`home_team_avg_goals` DECIMAL(5,2) NOT NULL,
`away_team_avg_goals` DECIMAL(5,2) NOT NULL
) ENGINE = InnoDB;
ALTER TABLE `pre_match_report_avg_goals_scored` ADD FOREIGN KEY (`match_id`) REFERENCES `match`(id);
ALTER TABLE `pre_match_report_avg_goals_scored` ADD UNIQUE INDEX `unique_match_id` (`match_id`);

DROP TABLE IF EXISTS `pre_match_report_last_season_match`;
CREATE TABLE `pre_match_report_last_season_match` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`match_id` INT NOT NULL,
`home_team_score` INT NOT NULL,
`away_team_score` INT NOT NULL
) ENGINE = InnoDB;
ALTER TABLE `pre_match_report_last_season_match` ADD FOREIGN KEY (`match_id`) REFERENCES `match`(id);
ALTER TABLE `pre_match_report_last_season_match` ADD UNIQUE INDEX `unique_match_id` (`match_id`);

DROP TABLE IF EXISTS `pre_match_report_most_recent_scorer`;
CREATE TABLE `pre_match_report_most_recent_scorer` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`match_id` INT NOT NULL,
`player_id` INT NOT NULL,
`team_id` INT NOT NULL,
`place` INT NOT NULL
) ENGINE = InnoDB;
ALTER TABLE `pre_match_report_most_recent_scorer` ADD FOREIGN KEY (`match_id`) REFERENCES `match`(id);
ALTER TABLE `pre_match_report_most_recent_scorer` ADD FOREIGN KEY (`player_id`) REFERENCES `player`(id);
ALTER TABLE `pre_match_report_most_recent_scorer` ADD FOREIGN KEY (`team_id`) REFERENCES `team`(id);
ALTER TABLE `pre_match_report_most_recent_scorer` ADD UNIQUE `match_player_team_unique` (`match_id`, `player_id`, `team_id`);

DROP TABLE IF EXISTS `pre_match_report_top_scorer`;
CREATE TABLE `pre_match_report_top_scorer` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`match_id` INT NOT NULL,
`player_id` INT NOT NULL,
`team_id` INT NOT NULL,
`goals` INT NOT NULL,
`place` INT NOT NULL
) ENGINE = InnoDB;
ALTER TABLE `pre_match_report_top_scorer` ADD FOREIGN KEY (`match_id`) REFERENCES `match`(id);
ALTER TABLE `pre_match_report_top_scorer` ADD FOREIGN KEY (`player_id`) REFERENCES `player`(id);
ALTER TABLE `pre_match_report_top_scorer` ADD FOREIGN KEY (`team_id`) REFERENCES `team`(id);
ALTER TABLE `pre_match_report_top_scorer` ADD UNIQUE `match_player_team_unique` (`match_id`, `player_id`, `team_id`);

-- oko 26.08

ALTER TABLE `achievement_block` CHANGE COLUMN `type` `type` ENUM('First Correct Result', 'First Correct Scorer', 'Perfect Prediction', 'Correct Score') NOT NULL AFTER `id`;

INSERT INTO  `achievement_block` (
`type` ,
`title` ,
`description` ,
`icon_path` ,
`weight`
)
VALUES (
'Perfect Prediction',  'Well done!',  'You predicted correct match results in 3 consecutive matches in the season!',  '/img/award/51a6086f44014.png',  '3'
), (
'Correct Score',  'Well done!',  'You predicted correct score line!',  '/img/award/51a6086f44015.png',  '10'
);

INSERT INTO  `share_copy` (
`engine` ,
`target` ,
`copy` ,
`weight` ,
`achievement_block_id`
)
VALUES (
'Facebook',  'PostMatchReport',  'You predicted correct match results in 3 consecutive matches in the season!',  '0',  '3'
), (
'Twitter',  'PostMatchReport',  'You predicted correct match results in 3 consecutive matches in the season!',  '0',  '3'
), (
'Facebook',  'PostMatchReport',  'You predicted correct score line!',  '0',  '4'
), (
'Twitter',  'PostMatchReport',  'You predicted correct score line!',  '0',  '4'
);

INSERT INTO `share_copy` (
`engine` ,
`target` ,
`copy` ,
`weight`
)
VALUES (
'Facebook',  'PreMatchReport',  'I''ve made prediction number %s! Join me my friends!',  '2'
), (
'Twitter',  'PreMatchReport',  'I''ve made prediction number %s! Join me my friends!',  '2'
);

-- oko 27.08

DROP TABLE IF EXISTS `pre_match_report_config`;
CREATE TABLE `pre_match_report_config` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`match_id` INT NOT NULL,
`weight` SMALLINT NOT NULL,
`display_index` SMALLINT NOT NULL
) ENGINE = InnoDB;
ALTER TABLE `pre_match_report_config` ADD FOREIGN KEY (`match_id`) REFERENCES `match`(id);

SET FOREIGN_KEY_CHECKS = 1;