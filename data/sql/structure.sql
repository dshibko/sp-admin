CREATE TABLE `user` (
	id INT AUTO_INCREMENT NOT NULL,
	title VARCHAR(5) NOT NULL,
	first_name VARCHAR(30) NOT NULL,
	last_name VARCHAR(30) NOT NULL,
	email VARCHAR(50) NOT NULL,
	password VARCHAR(100) NOT NULL,
	country_id INT NOT NULL,
	birthday DATE NOT NULL,
	gender VARCHAR(10) NOT NULL,
	display_name VARCHAR(20) NOT NULL,
	avatar_id INT NOT NULL,
	region_id INT NOT NULL,
	language_id INT NOT NULL,
	role_id INT NOT NULL,
	favourite_player_id INT,
	is_active TINYINT(1) NOT NULL DEFAULT '0',
	is_public TINYINT(1) NOT NULL DEFAULT '1',
	facebook_id BIGINT UNSIGNED NULL DEFAULT NULL,
	facebook_access_token VARCHAR(300) NULL DEFAULT NULL,
	date DATETIME NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `user`  ADD UNIQUE INDEX `facebook_id` (`facebook_id`);
ALTER TABLE `user`  ADD UNIQUE INDEX `email` (`email`);
CREATE TABLE `role` (
	id INT AUTO_INCREMENT NOT NULL,
	name VARCHAR(20) NOT NULL,
	parent_id INT DEFAULT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `role` ADD FOREIGN KEY (parent_id) REFERENCES `role`(id);
ALTER TABLE `user` ADD FOREIGN KEY (role_id) REFERENCES `role`(id);
CREATE TABLE `permission` (
	id INT AUTO_INCREMENT NOT NULL,
	name VARCHAR(50) NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE TABLE `role_permission` (
	id INT AUTO_INCREMENT NOT NULL,
	role_id INT NOT NULL,
	perm_id INT NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `role_permission` ADD FOREIGN KEY (role_id) REFERENCES `role`(id);
ALTER TABLE `role_permission` ADD FOREIGN KEY (perm_id) REFERENCES `permission`(id);
CREATE TABLE `recovery` (
	id INT AUTO_INCREMENT NOT NULL,
	hash VARCHAR(255) NOT NULL,
	user_id INT NOT NULL,
	date DATETIME NOT NULL,
	is_active TINYINT(1) DEFAULT 1,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `recovery` ADD FOREIGN KEY (user_id) REFERENCES `user`(id);
CREATE TABLE `avatar` (
	id INT AUTO_INCREMENT NOT NULL,
	original_image_path VARCHAR(255) NOT NULL,
	big_image_path VARCHAR(255) NOT NULL,
	medium_image_path VARCHAR(255) NOT NULL,
	small_image_path VARCHAR(255) NOT NULL,
	tiny_image_path VARCHAR(255) NOT NULL,
	is_default TINYINT(1) DEFAULT 0,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `user` ADD FOREIGN KEY (avatar_id) REFERENCES `avatar`(id);
CREATE TABLE `region` (
	id INT AUTO_INCREMENT NOT NULL,
	display_name VARCHAR(50) NOT NULL,
	is_default TINYINT(1) DEFAULT 0,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE TABLE `language` (
	id INT AUTO_INCREMENT NOT NULL,
	language_code VARCHAR(5) NOT NULL,
	display_name VARCHAR(40) NOT NULL,
	is_default TINYINT(1) DEFAULT 0,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE TABLE `country` (
	id INT AUTO_INCREMENT NOT NULL,
	name VARCHAR(40) NOT NULL,
	short_name VARCHAR(30) NOT NULL,
	iso_code VARCHAR(2) NOT NULL,
	dial_code SMALLINT,
	region_id INT NULL,
	language_id INT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `user` ADD FOREIGN KEY (region_id) REFERENCES `region`(id);
ALTER TABLE `user` ADD FOREIGN KEY (language_id) REFERENCES `language`(id);
ALTER TABLE `user` ADD FOREIGN KEY (country_id) REFERENCES `country`(id);
ALTER TABLE `country` ADD FOREIGN KEY (region_id) REFERENCES `region`(id);
ALTER TABLE `country` ADD FOREIGN KEY (language_id) REFERENCES `language`(id);
CREATE TABLE `season` (
	id INT AUTO_INCREMENT NOT NULL,
	display_name VARCHAR(100) NOT NULL,
	start_date DATE NOT NULL,
	end_date DATE NOT NULL,
	feeder_id INT NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE TABLE `league` (
	id INT AUTO_INCREMENT NOT NULL,
	display_name VARCHAR(50) NOT NULL,
	season_id INT NOT NULL,
	is_global TINYINT(1) NOT NULL DEFAULT 0,
	is_private TINYINT(1) NOT NULL DEFAULT 0,
	region_id INT,
	start_date DATE,
	end_date DATE,
	logo_path VARCHAR(255),
	creation_date DATETIME NOT NULL,
	creator_id INT,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `league` ADD FOREIGN KEY (season_id) REFERENCES `season`(id);
ALTER TABLE `league` ADD FOREIGN KEY (creator_id) REFERENCES `user`(id);
ALTER TABLE `league` ADD FOREIGN KEY (region_id) REFERENCES `region`(id);
CREATE TABLE `league_user` (
	id INT AUTO_INCREMENT NOT NULL,
	league_id INT NOT NULL,
	user_id INT NOT NULL,
	points INT,
	accuracy INT,
	place INT,
	join_date DATETIME NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `league_user` ADD FOREIGN KEY (league_id) REFERENCES `league`(id);
ALTER TABLE `league_user` ADD FOREIGN KEY (user_id) REFERENCES `user`(id);
CREATE TABLE `prize` (
	id INT AUTO_INCREMENT NOT NULL,
	league_id INT NOT NULL,
	region_id INT NOT NULL,
	prize_title VARCHAR(50) NOT NULL,
	prize_description text NOT NULL,
	prize_image VARCHAR(255) NOT NULL,
	post_win_title VARCHAR(50) NOT NULL,
	post_win_description text NOT NULL,
	post_win_image VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `prize` ADD FOREIGN KEY (league_id) REFERENCES `league`(id);
ALTER TABLE `prize` ADD FOREIGN KEY (region_id) REFERENCES `region`(id);
CREATE TABLE `competition` (
	id INT AUTO_INCREMENT NOT NULL,
	season_id INT NOT NULL,
	feeder_id INT NOT NULL,
	display_name VARCHAR(100) NOT NULL,
	logo_path VARCHAR(255),
	start_date DATE,
	end_date DATE,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `competition` ADD FOREIGN KEY (season_id) REFERENCES `season`(id);
CREATE TABLE `match` (
	id INT AUTO_INCREMENT NOT NULL,
	competition_id INT NOT NULL,
	home_team_id INT NOT NULL,
	away_team_id INT NOT NULL,
	feeder_id INT NOT NULL,
	week SMALLINT,
	stadium_name VARCHAR(100),
	city_name VARCHAR(100),
	is_double_points TINYINT(1) DEFAULT 0,
	featured_prediction_id INT,
	featured_player_id INT,
	home_team_full_time_score TINYINT,
	away_team_full_time_score TINYINT,
	home_team_extra_time_score TINYINT,
	away_team_extra_time_score TINYINT,
	home_team_shootout_score TINYINT,
	away_team_shootout_score TINYINT,
	status ENUM('PreMatch', 'Live', 'FullTime', 'Postponed', 'Abandoned'),
	timezone VARCHAR(5),
	start_time DATETIME,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE TABLE `team` (
	id INT AUTO_INCREMENT NOT NULL,
	display_name VARCHAR(50) NOT NULL,
	short_name VARCHAR(10),
	feeder_id INT NOT NULL,
	founded INT,
	logo_path VARCHAR(255),
	stadium_capacity INT,
	stadium_name VARCHAR(50),
	manager VARCHAR(100),
	is_blocked TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `match` ADD FOREIGN KEY (competition_id) REFERENCES `competition`(id);
ALTER TABLE `match` ADD FOREIGN KEY (home_team_id) REFERENCES `team`(id);
ALTER TABLE `match` ADD FOREIGN KEY (away_team_id) REFERENCES `team`(id);
CREATE TABLE `player` (
	id INT AUTO_INCREMENT NOT NULL,
	team_id INT NOT NULL,
	feeder_id INT NOT NULL,
	name VARCHAR(100) NOT NULL,
	surname VARCHAR(100) NOT NULL,
	display_name VARCHAR(50) NOT NULL,
	position VARCHAR(20) NOT NULL,
	real_position VARCHAR(50),
	real_position_side VARCHAR(20),
	shirt_number TINYINT,
	weight INT,
	height INT,
	birth_date DATE,
	join_date DATE,
	country VARCHAR(100),
	image_path VARCHAR(255),
	background_image_path VARCHAR(255) NULL DEFAULT NULL,
	is_blocked TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `player` ADD FOREIGN KEY (team_id) REFERENCES `team`(id);
CREATE TABLE `prediction` (
	id INT AUTO_INCREMENT NOT NULL,
	user_id INT NOT NULL,
	match_id INT NOT NULL,
	home_team_score TINYINT NOT NULL,
	away_team_score TINYINT NOT NULL,
	is_correct_result TINYINT(1),
	is_correct_score TINYINT(1),
	correct_scorers TINYINT,
	correct_scorers_order TINYINT,
	scorers_predicted TINYINT NOT NULL,
	points INT,
	last_update_date DATETIME NOT NULL,
	creation_date DATETIME NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `prediction` ADD FOREIGN KEY (user_id) REFERENCES `user`(id);
ALTER TABLE `prediction` ADD FOREIGN KEY (match_id) REFERENCES `match`(id);
CREATE TABLE `prediction_player` (
	id INT AUTO_INCREMENT NOT NULL,
	prediction_id INT NOT NULL,
	team_id INT NOT NULL,
	player_id INT,
	`order` TINYINT NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `prediction_player` ADD FOREIGN KEY (prediction_id) REFERENCES `prediction`(id);
ALTER TABLE `prediction_player` ADD FOREIGN KEY (team_id) REFERENCES `team`(id);
ALTER TABLE `prediction_player` ADD FOREIGN KEY (player_id) REFERENCES `player`(id);
ALTER TABLE `match` ADD FOREIGN KEY (featured_prediction_id) REFERENCES `prediction`(id);
ALTER TABLE `match` ADD FOREIGN KEY (featured_player_id) REFERENCES `player`(id);
CREATE TABLE `season_region` (
	id INT AUTO_INCREMENT NOT NULL,
	season_id INT NOT NULL,
	region_id INT NOT NULL,
	display_name VARCHAR(100) NOT NULL,
	terms text NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `season_region` ADD FOREIGN KEY (season_id) REFERENCES `season`(id);
ALTER TABLE `season_region` ADD FOREIGN KEY (region_id) REFERENCES `region`(id);
CREATE TABLE `player_competition` (
	id INT AUTO_INCREMENT NOT NULL,
	player_id INT NOT NULL,
	competition_id INT NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `player_competition` ADD FOREIGN KEY (player_id) REFERENCES `player`(id);
ALTER TABLE `player_competition` ADD FOREIGN KEY (competition_id) REFERENCES `competition`(id);
CREATE TABLE `team_competition` (
	id INT AUTO_INCREMENT NOT NULL,
	team_id INT NOT NULL,
	competition_id INT NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `team_competition` ADD FOREIGN KEY (team_id) REFERENCES `team`(id);
ALTER TABLE `team_competition` ADD FOREIGN KEY (competition_id) REFERENCES `competition`(id);
CREATE TABLE `match_goal` (
	id INT AUTO_INCREMENT NOT NULL,
	match_id INT NOT NULL,
	team_id INT NOT NULL,
	player_id INT NOT NULL,
	type ENUM('Goal', 'Penalty', 'Own') NOT NULL,
	period ENUM('FirstHalf', 'SecondHalf', 'ExtraFirstHalf', 'ExtraSecondHalf', 'ShootOut') NOT NULL,
	minute SMALLINT,
    time DATETIME NOT NULL,
	`order` TINYINT,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `match_goal` ADD FOREIGN KEY (match_id) REFERENCES `match`(id);
ALTER TABLE `match_goal` ADD FOREIGN KEY (team_id) REFERENCES `team`(id);
ALTER TABLE `match_goal` ADD FOREIGN KEY (player_id) REFERENCES `player`(id);
CREATE TABLE `region_content` (
	id INT AUTO_INCREMENT NOT NULL,
	region_id INT NOT NULL,
	headline_copy VARCHAR(255),
	register_button_copy VARCHAR(50),
	hero_background_image_id INT,
	hero_foreground_image_id INT,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE TABLE `content_image` (
	id INT AUTO_INCREMENT NOT NULL,
	width1280 VARCHAR(255) NOT NULL,
	width1024 VARCHAR(255) NOT NULL,
	width600 VARCHAR(255) NOT NULL,
	width480 VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `region_content` ADD FOREIGN KEY (region_id) REFERENCES `region`(id);
ALTER TABLE `region_content` ADD FOREIGN KEY (hero_background_image_id) REFERENCES `content_image`(id);
ALTER TABLE `region_content` ADD FOREIGN KEY (hero_foreground_image_id) REFERENCES `content_image`(id);
CREATE TABLE `region_gameplay_content` (
	id INT AUTO_INCREMENT NOT NULL,
	region_id INT NOT NULL,
	heading VARCHAR(255) NOT NULL,
	description text NOT NULL,
	foreground_image_id INT,
	`order` TINYINT NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `region_gameplay_content` ADD FOREIGN KEY (region_id) REFERENCES `region`(id);
ALTER TABLE `region_gameplay_content` ADD FOREIGN KEY (foreground_image_id) REFERENCES `content_image`(id);
CREATE TABLE `footer_image` (
	id INT AUTO_INCREMENT NOT NULL,
	region_id INT NOT NULL,
	footer_image VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `footer_image` ADD FOREIGN KEY (region_id) REFERENCES `region`(id);
CREATE TABLE `footer_social` (
	id INT AUTO_INCREMENT NOT NULL,
	region_id INT NOT NULL,
	url VARCHAR(500) NOT NULL,
	copy VARCHAR(100) NOT NULL,
	icon VARCHAR(255) NOT NULL,
	`order` TINYINT NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `footer_social` ADD FOREIGN KEY (region_id) REFERENCES `region`(id);
CREATE TABLE `settings` (
	id INT AUTO_INCREMENT NOT NULL,
	setting_key VARCHAR(100) NOT NULL,
	setting_value VARCHAR(500) NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;

-- oko 28.05

ALTER TABLE  `league` ADD  `type` ENUM(  'Global',  'Regional',  'Mini',  'Private' ) NOT NULL AFTER  `season_id`;
UPDATE `league` SET `type` = 'Global' WHERE `is_global` = 1;
UPDATE `league` SET `type` = 'Regional' WHERE `region_id` is not null;
ALTER TABLE  `league` DROP  `is_global` , DROP  `is_private` ;

CREATE TABLE `league_region` (
	id INT AUTO_INCREMENT NOT NULL,
	league_id INT NOT NULL,
	region_id INT NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `league_region` ADD FOREIGN KEY (league_id) REFERENCES `league`(id);
ALTER TABLE `league_region` ADD FOREIGN KEY (region_id) REFERENCES `region`(id);

INSERT INTO `league_region` (`league_id`, `region_id`)
SELECT `id`, `region_id` FROM `league` WHERE `region_id` is not null;

ALTER TABLE `league` DROP FOREIGN KEY `league_ibfk_3`;
ALTER TABLE  `league` DROP  `region_id`;

-- oko 29.05

ALTER TABLE  `prediction` DROP  `scorers_predicted`;

-- okh 29.05

ALTER TABLE `match`  ADD COLUMN `is_blocked` TINYINT(1) NOT NULL DEFAULT '0' AFTER `start_time`;
ALTER TABLE `match`  CHANGE COLUMN `feeder_id` `feeder_id` INT(11) NULL AFTER `away_team_id`;

-- oko 30.05

ALTER TABLE  `league_region` ADD  `display_name` VARCHAR( 255 ) NULL;
ALTER TABLE `user` DROP FOREIGN KEY `user_ibfk_3`;
ALTER TABLE  `user` DROP  `region_id`;

-- oko 31.05

ALTER TABLE  `prediction` ADD  `was_viewed` TINYINT( 1 ) NOT NULL AFTER  `points`;
ALTER TABLE  `league_user` ADD  `previous_place` INT NULL AFTER  `place`;

-- oko 03.06

CREATE TABLE `line_up` (
	id INT AUTO_INCREMENT NOT NULL,
	match_id INT NOT NULL,
	team_id INT NOT NULL,
	player_id INT NOT NULL,
	is_start TINYINT(1) NOT NULL,
	PRIMARY KEY (id)
) ENGINE = InnoDB;
ALTER TABLE `line_up` ADD FOREIGN KEY (match_id) REFERENCES `match`(id);
ALTER TABLE `line_up` ADD FOREIGN KEY (team_id) REFERENCES `team`(id);
ALTER TABLE `line_up` ADD FOREIGN KEY (player_id) REFERENCES `player`(id);
ALTER TABLE `match`  ADD COLUMN `has_line_up` TINYINT(1) NOT NULL DEFAULT '0' AFTER `start_time`;

-- oko 05.06

ALTER TABLE  `country` ADD  `flag_image` VARCHAR( 64 ) NOT NULL AFTER  `dial_code` ,
ADD  `original_flag_image` VARCHAR( 255 ) NOT NULL AFTER  `flag_image`;

UPDATE `country` SET `flag_image` = "/img/flags/1.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/a/a4/Flag_of_the_United_States.svg/28px-Flag_of_the_United_States.svg.png" WHERE `id` = 1;
UPDATE `country` SET `flag_image` = "/img/flags/2.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/c/cf/Flag_of_Canada.svg/28px-Flag_of_Canada.svg.png" WHERE `id` = 2;
UPDATE `country` SET `flag_image` = "/img/flags/4.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/e/ef/Flag_of_Barbados.svg/28px-Flag_of_Barbados.svg.png" WHERE `id` = 4;
UPDATE `country` SET `flag_image` = "/img/flags/5.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Flag_of_Belize.svg/28px-Flag_of_Belize.svg.png" WHERE `id` = 5;
UPDATE `country` SET `flag_image` = "/img/flags/6.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Flag_of_Bermuda.svg/28px-Flag_of_Bermuda.svg.png" WHERE `id` = 6;
UPDATE `country` SET `flag_image` = "/img/flags/7.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/42/Flag_of_the_British_Virgin_Islands.svg/28px-Flag_of_the_British_Virgin_Islands.svg.png" WHERE `id` = 7;
UPDATE `country` SET `flag_image` = "/img/flags/8.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_the_Cayman_Islands.svg/28px-Flag_of_the_Cayman_Islands.svg.png" WHERE `id` = 8;
UPDATE `country` SET `flag_image` = "/img/flags/9.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f2/Flag_of_Costa_Rica.svg/28px-Flag_of_Costa_Rica.svg.png" WHERE `id` = 9;
UPDATE `country` SET `flag_image` = "/img/flags/10.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/Flag_of_Cuba.svg/28px-Flag_of_Cuba.svg.png" WHERE `id` = 10;
UPDATE `country` SET `flag_image` = "/img/flags/11.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/c/c4/Flag_of_Dominica.svg/28px-Flag_of_Dominica.svg.png" WHERE `id` = 11;
UPDATE `country` SET `flag_image` = "/img/flags/12.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Flag_of_the_Dominican_Republic.svg/28px-Flag_of_the_Dominican_Republic.svg.png" WHERE `id` = 12;
UPDATE `country` SET `flag_image` = "/img/flags/13.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Flag_of_El_Salvador.svg/28px-Flag_of_El_Salvador.svg.png" WHERE `id` = 13;
UPDATE `country` SET `flag_image` = "/img/flags/14.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Flag_of_Greenland.svg/28px-Flag_of_Greenland.svg.png" WHERE `id` = 14;
UPDATE `country` SET `flag_image` = "/img/flags/15.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Grenada.svg/28px-Flag_of_Grenada.svg.png" WHERE `id` = 15;
UPDATE `country` SET `flag_image` = "/img/flags/16.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png" WHERE `id` = 16;
UPDATE `country` SET `flag_image` = "/img/flags/17.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/Flag_of_Guatemala.svg/28px-Flag_of_Guatemala.svg.png" WHERE `id` = 17;
UPDATE `country` SET `flag_image` = "/img/flags/18.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Flag_of_Haiti.svg/28px-Flag_of_Haiti.svg.png" WHERE `id` = 18;
UPDATE `country` SET `flag_image` = "/img/flags/19.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Flag_of_Honduras.svg/28px-Flag_of_Honduras.svg.png" WHERE `id` = 19;
UPDATE `country` SET `flag_image` = "/img/flags/20.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/0a/Flag_of_Jamaica.svg/28px-Flag_of_Jamaica.svg.png" WHERE `id` = 20;
UPDATE `country` SET `flag_image` = "/img/flags/21.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png" WHERE `id` = 21;
UPDATE `country` SET `flag_image` = "/img/flags/22.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/Flag_of_Mexico.svg/28px-Flag_of_Mexico.svg.png" WHERE `id` = 22;
UPDATE `country` SET `flag_image` = "/img/flags/23.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Flag_of_Montserrat.svg/28px-Flag_of_Montserrat.svg.png" WHERE `id` = 23;
UPDATE `country` SET `flag_image` = "/img/flags/24.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Nicaragua.svg/28px-Flag_of_Nicaragua.svg.png" WHERE `id` = 24;
UPDATE `country` SET `flag_image` = "/img/flags/25.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Flag_of_Panama.svg/28px-Flag_of_Panama.svg.png" WHERE `id` = 25;
UPDATE `country` SET `flag_image` = "/img/flags/26.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/28/Flag_of_Puerto_Rico.svg/28px-Flag_of_Puerto_Rico.svg.png" WHERE `id` = 26;
UPDATE `country` SET `flag_image` = "/img/flags/27.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Flag_of_Trinidad_and_Tobago.svg/28px-Flag_of_Trinidad_and_Tobago.svg.png" WHERE `id` = 27;
UPDATE `country` SET `flag_image` = "/img/flags/28.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Flag_of_the_United_States_Virgin_Islands.svg/28px-Flag_of_the_United_States_Virgin_Islands.svg.png" WHERE `id` = 28;
UPDATE `country` SET `flag_image` = "/img/flags/29.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Flag_of_Argentina.svg/28px-Flag_of_Argentina.svg.png" WHERE `id` = 29;
UPDATE `country` SET `flag_image` = "/img/flags/30.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Bolivia.svg/28px-Flag_of_Bolivia.svg.png" WHERE `id` = 30;
UPDATE `country` SET `flag_image` = "/img/flags/31.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/0/05/Flag_of_Brazil.svg/28px-Flag_of_Brazil.svg.png" WHERE `id` = 31;
UPDATE `country` SET `flag_image` = "/img/flags/32.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Flag_of_Chile.svg/28px-Flag_of_Chile.svg.png" WHERE `id` = 32;
UPDATE `country` SET `flag_image` = "/img/flags/33.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Flag_of_Colombia.svg/28px-Flag_of_Colombia.svg.png" WHERE `id` = 33;
UPDATE `country` SET `flag_image` = "/img/flags/34.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/Flag_of_Ecuador.svg/28px-Flag_of_Ecuador.svg.png" WHERE `id` = 34;
UPDATE `country` SET `flag_image` = "/img/flags/35.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_the_Falkland_Islands.svg/28px-Flag_of_the_Falkland_Islands.svg.png" WHERE `id` = 35;
UPDATE `country` SET `flag_image` = "/img/flags/36.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png" WHERE `id` = 36;
UPDATE `country` SET `flag_image` = "/img/flags/37.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_Guyana.svg/28px-Flag_of_Guyana.svg.png" WHERE `id` = 37;
UPDATE `country` SET `flag_image` = "/img/flags/38.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Paraguay.svg/28px-Flag_of_Paraguay.svg.png" WHERE `id` = 38;
UPDATE `country` SET `flag_image` = "/img/flags/39.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/c/cf/Flag_of_Peru.svg/28px-Flag_of_Peru.svg.png" WHERE `id` = 39;
UPDATE `country` SET `flag_image` = "/img/flags/40.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/6/60/Flag_of_Suriname.svg/28px-Flag_of_Suriname.svg.png" WHERE `id` = 40;
UPDATE `country` SET `flag_image` = "/img/flags/41.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_Uruguay.svg/28px-Flag_of_Uruguay.svg.png" WHERE `id` = 41;
UPDATE `country` SET `flag_image` = "/img/flags/42.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/06/Flag_of_Venezuela.svg/28px-Flag_of_Venezuela.svg.png" WHERE `id` = 42;
UPDATE `country` SET `flag_image` = "/img/flags/3.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/93/Flag_of_the_Bahamas.svg/28px-Flag_of_the_Bahamas.svg.png" WHERE `id` = 3;
UPDATE `country` SET `flag_image` = "/img/flags/45.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Flag_of_Armenia.svg/28px-Flag_of_Armenia.svg.png" WHERE `id` = 45;
UPDATE `country` SET `flag_image` = "/img/flags/47.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Flag_of_Azerbaijan.svg/28px-Flag_of_Azerbaijan.svg.png" WHERE `id` = 47;
UPDATE `country` SET `flag_image` = "/img/flags/53.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Flag_of_Cyprus.svg/28px-Flag_of_Cyprus.svg.png" WHERE `id` = 53;
UPDATE `country` SET `flag_image` = "/img/flags/59.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Georgia.svg/28px-Flag_of_Georgia.svg.png" WHERE `id` = 59;
UPDATE `country` SET `flag_image` = "/img/flags/85.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/f/f3/Flag_of_Russia.svg/28px-Flag_of_Russia.svg.png" WHERE `id` = 85;
UPDATE `country` SET `flag_image` = "/img/flags/93.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Flag_of_Turkey.svg/28px-Flag_of_Turkey.svg.png" WHERE `id` = 93;
UPDATE `country` SET `flag_image` = "/img/flags/97.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Afghanistan.svg/28px-Flag_of_Afghanistan.svg.png" WHERE `id` = 97;
UPDATE `country` SET `flag_image` = "/img/flags/98.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Flag_of_Bahrain.svg/28px-Flag_of_Bahrain.svg.png" WHERE `id` = 98;
UPDATE `country` SET `flag_image` = "/img/flags/99.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/Flag_of_Bangladesh.svg/28px-Flag_of_Bangladesh.svg.png" WHERE `id` = 99;
UPDATE `country` SET `flag_image` = "/img/flags/100.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Flag_of_Bhutan.svg/28px-Flag_of_Bhutan.svg.png" WHERE `id` = 100;
UPDATE `country` SET `flag_image` = "/img/flags/101.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Flag_of_Brunei.svg/28px-Flag_of_Brunei.svg.png" WHERE `id` = 101;
UPDATE `country` SET `flag_image` = "/img/flags/102.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_Cambodia.svg/28px-Flag_of_Cambodia.svg.png" WHERE `id` = 102;
UPDATE `country` SET `flag_image` = "/img/flags/104.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Flag_of_East_Timor.svg/28px-Flag_of_East_Timor.svg.png" WHERE `id` = 104;
UPDATE `country` SET `flag_image` = "/img/flags/105.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/28px-Flag_of_Japan.svg.png" WHERE `id` = 105;
UPDATE `country` SET `flag_image` = "/img/flags/106.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/4/41/Flag_of_India.svg/28px-Flag_of_India.svg.png" WHERE `id` = 106;
UPDATE `country` SET `flag_image` = "/img/flags/107.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Flag_of_Indonesia.svg/28px-Flag_of_Indonesia.svg.png" WHERE `id` = 107;
UPDATE `country` SET `flag_image` = "/img/flags/108.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Flag_of_Iran.svg/28px-Flag_of_Iran.svg.png" WHERE `id` = 108;
UPDATE `country` SET `flag_image` = "/img/flags/109.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Flag_of_Iraq.svg/28px-Flag_of_Iraq.svg.png" WHERE `id` = 109;
UPDATE `country` SET `flag_image` = "/img/flags/110.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Flag_of_Israel.svg/28px-Flag_of_Israel.svg.png" WHERE `id` = 110;
UPDATE `country` SET `flag_image` = "/img/flags/111.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/28px-Flag_of_Japan.svg.png" WHERE `id` = 111;
UPDATE `country` SET `flag_image` = "/img/flags/112.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/c/c0/Flag_of_Jordan.svg/28px-Flag_of_Jordan.svg.png" WHERE `id` = 112;
UPDATE `country` SET `flag_image` = "/img/flags/113.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/Flag_of_Kazakhstan.svg/28px-Flag_of_Kazakhstan.svg.png" WHERE `id` = 113;
UPDATE `country` SET `flag_image` = "/img/flags/114.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/a/aa/Flag_of_Kuwait.svg/28px-Flag_of_Kuwait.svg.png" WHERE `id` = 114;
UPDATE `country` SET `flag_image` = "/img/flags/115.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/c/c7/Flag_of_Kyrgyzstan.svg/28px-Flag_of_Kyrgyzstan.svg.png" WHERE `id` = 115;
UPDATE `country` SET `flag_image` = "/img/flags/116.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Flag_of_Laos.svg/28px-Flag_of_Laos.svg.png" WHERE `id` = 116;
UPDATE `country` SET `flag_image` = "/img/flags/117.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/5/59/Flag_of_Lebanon.svg/28px-Flag_of_Lebanon.svg.png" WHERE `id` = 117;
UPDATE `country` SET `flag_image` = "/img/flags/119.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/6/66/Flag_of_Malaysia.svg/28px-Flag_of_Malaysia.svg.png" WHERE `id` = 119;
UPDATE `country` SET `flag_image` = "/img/flags/120.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Maldives.svg/28px-Flag_of_Maldives.svg.png" WHERE `id` = 120;
UPDATE `country` SET `flag_image` = "/img/flags/121.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Flag_of_Mongolia.svg/28px-Flag_of_Mongolia.svg.png" WHERE `id` = 121;
UPDATE `country` SET `flag_image` = "/img/flags/123.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Flag_of_Nepal.svg/16px-Flag_of_Nepal.svg.png" WHERE `id` = 123;
UPDATE `country` SET `flag_image` = "/img/flags/124.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Flag_of_North_Korea.svg/28px-Flag_of_North_Korea.svg.png" WHERE `id` = 124;
UPDATE `country` SET `flag_image` = "/img/flags/125.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Flag_of_Oman.svg/28px-Flag_of_Oman.svg.png" WHERE `id` = 125;
UPDATE `country` SET `flag_image` = "/img/flags/126.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/3/32/Flag_of_Pakistan.svg/28px-Flag_of_Pakistan.svg.png" WHERE `id` = 126;
UPDATE `country` SET `flag_image` = "/img/flags/127.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_the_Philippines.svg/28px-Flag_of_the_Philippines.svg.png" WHERE `id` = 127;
UPDATE `country` SET `flag_image` = "/img/flags/128.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/6/65/Flag_of_Qatar.svg/28px-Flag_of_Qatar.svg.png" WHERE `id` = 128;
UPDATE `country` SET `flag_image` = "/img/flags/129.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/0d/Flag_of_Saudi_Arabia.svg/28px-Flag_of_Saudi_Arabia.svg.png" WHERE `id` = 129;
UPDATE `country` SET `flag_image` = "/img/flags/130.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Singapore.svg/28px-Flag_of_Singapore.svg.png" WHERE `id` = 130;
UPDATE `country` SET `flag_image` = "/img/flags/131.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Flag_of_South_Korea.svg/28px-Flag_of_South_Korea.svg.png" WHERE `id` = 131;
UPDATE `country` SET `flag_image` = "/img/flags/132.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Flag_of_Sri_Lanka.svg/28px-Flag_of_Sri_Lanka.svg.png" WHERE `id` = 132;
UPDATE `country` SET `flag_image` = "/img/flags/133.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Flag_of_Syria.svg/28px-Flag_of_Syria.svg.png" WHERE `id` = 133;
UPDATE `country` SET `flag_image` = "/img/flags/135.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Flag_of_Tajikistan.svg/28px-Flag_of_Tajikistan.svg.png" WHERE `id` = 135;
UPDATE `country` SET `flag_image` = "/img/flags/136.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Flag_of_Thailand.svg/28px-Flag_of_Thailand.svg.png" WHERE `id` = 136;
UPDATE `country` SET `flag_image` = "/img/flags/137.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Flag_of_Turkmenistan.svg/28px-Flag_of_Turkmenistan.svg.png" WHERE `id` = 137;
UPDATE `country` SET `flag_image` = "/img/flags/138.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Flag_of_the_United_Arab_Emirates.svg/28px-Flag_of_the_United_Arab_Emirates.svg.png" WHERE `id` = 138;
UPDATE `country` SET `flag_image` = "/img/flags/139.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Flag_of_Uzbekistan.svg/28px-Flag_of_Uzbekistan.svg.png" WHERE `id` = 139;
UPDATE `country` SET `flag_image` = "/img/flags/140.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Flag_of_Vietnam.svg/28px-Flag_of_Vietnam.svg.png" WHERE `id` = 140;
UPDATE `country` SET `flag_image` = "/img/flags/141.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Flag_of_Yemen.svg/28px-Flag_of_Yemen.svg.png" WHERE `id` = 141;
UPDATE `country` SET `flag_image` = "/img/flags/122.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Flag_of_Myanmar.svg/28px-Flag_of_Myanmar.svg.png" WHERE `id` = 122;
UPDATE `country` SET `flag_image` = "/img/flags/103.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_the_People%27s_Republic_of_China.svg/28px-Flag_of_the_People%27s_Republic_of_China.svg.png" WHERE `id` = 103;
UPDATE `country` SET `flag_image` = "/img/flags/134.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Flag_of_the_Republic_of_China.svg/28px-Flag_of_the_Republic_of_China.svg.png" WHERE `id` = 134;
UPDATE `country` SET `flag_image` = "/img/flags/142.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Flag_of_Algeria.svg/28px-Flag_of_Algeria.svg.png" WHERE `id` = 142;
UPDATE `country` SET `flag_image` = "/img/flags/143.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/Flag_of_Angola.svg/28px-Flag_of_Angola.svg.png" WHERE `id` = 143;
UPDATE `country` SET `flag_image` = "/img/flags/144.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/0a/Flag_of_Benin.svg/28px-Flag_of_Benin.svg.png" WHERE `id` = 144;
UPDATE `country` SET `flag_image` = "/img/flags/145.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_Botswana.svg/28px-Flag_of_Botswana.svg.png" WHERE `id` = 145;
UPDATE `country` SET `flag_image` = "/img/flags/146.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Flag_of_Burkina_Faso.svg/28px-Flag_of_Burkina_Faso.svg.png" WHERE `id` = 146;
UPDATE `country` SET `flag_image` = "/img/flags/147.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/5/50/Flag_of_Burundi.svg/28px-Flag_of_Burundi.svg.png" WHERE `id` = 147;
UPDATE `country` SET `flag_image` = "/img/flags/148.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Flag_of_Cameroon.svg/28px-Flag_of_Cameroon.svg.png" WHERE `id` = 148;
UPDATE `country` SET `flag_image` = "/img/flags/149.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Flag_of_Cape_Verde.svg/28px-Flag_of_Cape_Verde.svg.png" WHERE `id` = 149;
UPDATE `country` SET `flag_image` = "/img/flags/150.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Flag_of_the_Central_African_Republic.svg/28px-Flag_of_the_Central_African_Republic.svg.png" WHERE `id` = 150;
UPDATE `country` SET `flag_image` = "/img/flags/151.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Flag_of_Chad.svg/28px-Flag_of_Chad.svg.png" WHERE `id` = 151;
UPDATE `country` SET `flag_image` = "/img/flags/154.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Flag_of_Djibouti.svg/28px-Flag_of_Djibouti.svg.png" WHERE `id` = 154;
UPDATE `country` SET `flag_image` = "/img/flags/155.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_Egypt.svg/28px-Flag_of_Egypt.svg.png" WHERE `id` = 155;
UPDATE `country` SET `flag_image` = "/img/flags/163.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/01/Flag_of_Guinea-Bissau.svg/28px-Flag_of_Guinea-Bissau.svg.png" WHERE `id` = 163;
UPDATE `country` SET `flag_image` = "/img/flags/157.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/29/Flag_of_Eritrea.svg/28px-Flag_of_Eritrea.svg.png" WHERE `id` = 157;
UPDATE `country` SET `flag_image` = "/img/flags/158.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/7/71/Flag_of_Ethiopia.svg/28px-Flag_of_Ethiopia.svg.png" WHERE `id` = 158;
UPDATE `country` SET `flag_image` = "/img/flags/159.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Flag_of_Gabon.svg/28px-Flag_of_Gabon.svg.png" WHERE `id` = 159;
UPDATE `country` SET `flag_image` = "/img/flags/160.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Flag_of_The_Gambia.svg/28px-Flag_of_The_Gambia.svg.png" WHERE `id` = 160;
UPDATE `country` SET `flag_image` = "/img/flags/161.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Ghana.svg/28px-Flag_of_Ghana.svg.png" WHERE `id` = 161;
UPDATE `country` SET `flag_image` = "/img/flags/162.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/e/ed/Flag_of_Guinea.svg/28px-Flag_of_Guinea.svg.png" WHERE `id` = 162;
UPDATE `country` SET `flag_image` = "/img/flags/165.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Flag_of_Kenya.svg/28px-Flag_of_Kenya.svg.png" WHERE `id` = 165;
UPDATE `country` SET `flag_image` = "/img/flags/166.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/4a/Flag_of_Lesotho.svg/28px-Flag_of_Lesotho.svg.png" WHERE `id` = 166;
UPDATE `country` SET `flag_image` = "/img/flags/167.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/Flag_of_Liberia.svg/28px-Flag_of_Liberia.svg.png" WHERE `id` = 167;
UPDATE `country` SET `flag_image` = "/img/flags/168.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Flag_of_Libya.svg/28px-Flag_of_Libya.svg.png" WHERE `id` = 168;
UPDATE `country` SET `flag_image` = "/img/flags/169.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Madagascar.svg/28px-Flag_of_Madagascar.svg.png" WHERE `id` = 169;
UPDATE `country` SET `flag_image` = "/img/flags/170.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Flag_of_Malawi.svg/28px-Flag_of_Malawi.svg.png" WHERE `id` = 170;
UPDATE `country` SET `flag_image` = "/img/flags/171.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Flag_of_Mali.svg/28px-Flag_of_Mali.svg.png" WHERE `id` = 171;
UPDATE `country` SET `flag_image` = "/img/flags/172.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/43/Flag_of_Mauritania.svg/28px-Flag_of_Mauritania.svg.png" WHERE `id` = 172;
UPDATE `country` SET `flag_image` = "/img/flags/173.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Flag_of_Mauritius.svg/28px-Flag_of_Mauritius.svg.png" WHERE `id` = 173;
UPDATE `country` SET `flag_image` = "/img/flags/174.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Flag_of_Morocco.svg/28px-Flag_of_Morocco.svg.png" WHERE `id` = 174;
UPDATE `country` SET `flag_image` = "/img/flags/175.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Flag_of_Mozambique.svg/28px-Flag_of_Mozambique.svg.png" WHERE `id` = 175;
UPDATE `country` SET `flag_image` = "/img/flags/176.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/00/Flag_of_Namibia.svg/28px-Flag_of_Namibia.svg.png" WHERE `id` = 176;
UPDATE `country` SET `flag_image` = "/img/flags/177.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f4/Flag_of_Niger.svg/28px-Flag_of_Niger.svg.png" WHERE `id` = 177;
UPDATE `country` SET `flag_image` = "/img/flags/178.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/7/79/Flag_of_Nigeria.svg/28px-Flag_of_Nigeria.svg.png" WHERE `id` = 178;
UPDATE `country` SET `flag_image` = "/img/flags/180.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Flag_of_Rwanda.svg/28px-Flag_of_Rwanda.svg.png" WHERE `id` = 180;
UPDATE `country` SET `flag_image` = "/img/flags/182.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/fd/Flag_of_Senegal.svg/28px-Flag_of_Senegal.svg.png" WHERE `id` = 182;
UPDATE `country` SET `flag_image` = "/img/flags/183.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/Flag_of_Seychelles.svg/28px-Flag_of_Seychelles.svg.png" WHERE `id` = 183;
UPDATE `country` SET `flag_image` = "/img/flags/184.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Flag_of_Sierra_Leone.svg/28px-Flag_of_Sierra_Leone.svg.png" WHERE `id` = 184;
UPDATE `country` SET `flag_image` = "/img/flags/185.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Flag_of_Somalia.svg/28px-Flag_of_Somalia.svg.png" WHERE `id` = 185;
UPDATE `country` SET `flag_image` = "/img/flags/186.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Flag_of_South_Africa.svg/28px-Flag_of_South_Africa.svg.png" WHERE `id` = 186;
UPDATE `country` SET `flag_image` = "/img/flags/187.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/01/Flag_of_Sudan.svg/28px-Flag_of_Sudan.svg.png" WHERE `id` = 187;
UPDATE `country` SET `flag_image` = "/img/flags/188.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/Flag_of_Swaziland.svg/28px-Flag_of_Swaziland.svg.png" WHERE `id` = 188;
UPDATE `country` SET `flag_image` = "/img/flags/189.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Flag_of_Tanzania.svg/28px-Flag_of_Tanzania.svg.png" WHERE `id` = 189;
UPDATE `country` SET `flag_image` = "/img/flags/190.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/6/68/Flag_of_Togo.svg/28px-Flag_of_Togo.svg.png" WHERE `id` = 190;
UPDATE `country` SET `flag_image` = "/img/flags/191.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/c/ce/Flag_of_Tunisia.svg/28px-Flag_of_Tunisia.svg.png" WHERE `id` = 191;
UPDATE `country` SET `flag_image` = "/img/flags/192.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Flag_of_Uganda.svg/28px-Flag_of_Uganda.svg.png" WHERE `id` = 192;
UPDATE `country` SET `flag_image` = "/img/flags/193.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Flag_of_the_Sahrawi_Arab_Democratic_Republic.svg/28px-Flag_of_the_Sahrawi_Arab_Democratic_Republic.svg.png" WHERE `id` = 193;
UPDATE `country` SET `flag_image` = "/img/flags/194.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/06/Flag_of_Zambia.svg/28px-Flag_of_Zambia.svg.png" WHERE `id` = 194;
UPDATE `country` SET `flag_image` = "/img/flags/195.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/Flag_of_Zimbabwe.svg/28px-Flag_of_Zimbabwe.svg.png" WHERE `id` = 195;
UPDATE `country` SET `flag_image` = "/img/flags/213.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/7/7a/Flag_of_South_Sudan.svg/28px-Flag_of_South_Sudan.svg.png" WHERE `id` = 213;
UPDATE `country` SET `flag_image` = "/img/flags/152.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Flag_of_the_Republic_of_the_Congo.svg/28px-Flag_of_the_Republic_of_the_Congo.svg.png" WHERE `id` = 152;
UPDATE `country` SET `flag_image` = "/img/flags/153.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Flag_of_the_Democratic_Republic_of_the_Congo.svg/28px-Flag_of_the_Democratic_Republic_of_the_Congo.svg.png" WHERE `id` = 153;
UPDATE `country` SET `flag_image` = "/img/flags/181.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Flag_of_Sao_Tome_and_Principe.svg/28px-Flag_of_Sao_Tome_and_Principe.svg.png" WHERE `id` = 181;
UPDATE `country` SET `flag_image` = "/img/flags/164.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_C%C3%B4te_d%27Ivoire.svg/28px-Flag_of_C%C3%B4te_d%27Ivoire.svg.png" WHERE `id` = 164;
UPDATE `country` SET `flag_image` = "/img/flags/43.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/3/36/Flag_of_Albania.svg/28px-Flag_of_Albania.svg.png" WHERE `id` = 43;
UPDATE `country` SET `flag_image` = "/img/flags/44.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Andorra.svg/28px-Flag_of_Andorra.svg.png" WHERE `id` = 44;
UPDATE `country` SET `flag_image` = "/img/flags/46.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Flag_of_Austria.svg/28px-Flag_of_Austria.svg.png" WHERE `id` = 46;
UPDATE `country` SET `flag_image` = "/img/flags/48.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/8/85/Flag_of_Belarus.svg/28px-Flag_of_Belarus.svg.png" WHERE `id` = 48;
UPDATE `country` SET `flag_image` = "/img/flags/49.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Flag_of_Belgium_%28civil%29.svg/28px-Flag_of_Belgium_%28civil%29.svg.png" WHERE `id` = 49;
UPDATE `country` SET `flag_image` = "/img/flags/50.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Flag_of_Bosnia_and_Herzegovina.svg/28px-Flag_of_Bosnia_and_Herzegovina.svg.png" WHERE `id` = 50;
UPDATE `country` SET `flag_image` = "/img/flags/51.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Bulgaria.svg/28px-Flag_of_Bulgaria.svg.png" WHERE `id` = 51;
UPDATE `country` SET `flag_image` = "/img/flags/52.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Flag_of_Croatia.svg/28px-Flag_of_Croatia.svg.png" WHERE `id` = 52;
UPDATE `country` SET `flag_image` = "/img/flags/54.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Flag_of_the_Czech_Republic.svg/28px-Flag_of_the_Czech_Republic.svg.png" WHERE `id` = 54;
UPDATE `country` SET `flag_image` = "/img/flags/55.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Flag_of_Denmark.svg/28px-Flag_of_Denmark.svg.png" WHERE `id` = 55;
UPDATE `country` SET `flag_image` = "/img/flags/56.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Flag_of_Estonia.svg/28px-Flag_of_Estonia.svg.png" WHERE `id` = 56;
UPDATE `country` SET `flag_image` = "/img/flags/57.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Finland.svg/28px-Flag_of_Finland.svg.png" WHERE `id` = 57;
UPDATE `country` SET `flag_image` = "/img/flags/58.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png" WHERE `id` = 58;
UPDATE `country` SET `flag_image` = "/img/flags/60.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/b/ba/Flag_of_Germany.svg/28px-Flag_of_Germany.svg.png" WHERE `id` = 60;
UPDATE `country` SET `flag_image` = "/img/flags/61.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/02/Flag_of_Gibraltar.svg/28px-Flag_of_Gibraltar.svg.png" WHERE `id` = 61;
UPDATE `country` SET `flag_image` = "/img/flags/62.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Greece.svg/28px-Flag_of_Greece.svg.png" WHERE `id` = 62;
UPDATE `country` SET `flag_image` = "/img/flags/63.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_Guernsey.svg/28px-Flag_of_Guernsey.svg.png" WHERE `id` = 63;
UPDATE `country` SET `flag_image` = "/img/flags/64.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Flag_of_Hungary.svg/28px-Flag_of_Hungary.svg.png" WHERE `id` = 64;
UPDATE `country` SET `flag_image` = "/img/flags/65.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/c/ce/Flag_of_Iceland.svg/28px-Flag_of_Iceland.svg.png" WHERE `id` = 65;
UPDATE `country` SET `flag_image` = "/img/flags/66.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Flag_of_Ireland.svg/28px-Flag_of_Ireland.svg.png" WHERE `id` = 66;
UPDATE `country` SET `flag_image` = "/img/flags/67.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_the_Isle_of_Man.svg/28px-Flag_of_the_Isle_of_Man.svg.png" WHERE `id` = 67;
UPDATE `country` SET `flag_image` = "/img/flags/68.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/0/03/Flag_of_Italy.svg/28px-Flag_of_Italy.svg.png" WHERE `id` = 68;
UPDATE `country` SET `flag_image` = "/img/flags/69.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/1c/Flag_of_Jersey.svg/28px-Flag_of_Jersey.svg.png" WHERE `id` = 69;
UPDATE `country` SET `flag_image` = "/img/flags/70.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/1f/Flag_of_Kosovo.svg/28px-Flag_of_Kosovo.svg.png" WHERE `id` = 70;
UPDATE `country` SET `flag_image` = "/img/flags/71.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Flag_of_Latvia.svg/28px-Flag_of_Latvia.svg.png" WHERE `id` = 71;
UPDATE `country` SET `flag_image` = "/img/flags/72.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/47/Flag_of_Liechtenstein.svg/28px-Flag_of_Liechtenstein.svg.png" WHERE `id` = 72;
UPDATE `country` SET `flag_image` = "/img/flags/73.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Flag_of_Lithuania.svg/28px-Flag_of_Lithuania.svg.png" WHERE `id` = 73;
UPDATE `country` SET `flag_image` = "/img/flags/74.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/da/Flag_of_Luxembourg.svg/28px-Flag_of_Luxembourg.svg.png" WHERE `id` = 74;
UPDATE `country` SET `flag_image` = "/img/flags/76.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Flag_of_Malta.svg/28px-Flag_of_Malta.svg.png" WHERE `id` = 76;
UPDATE `country` SET `flag_image` = "/img/flags/77.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Moldova.svg/28px-Flag_of_Moldova.svg.png" WHERE `id` = 77;
UPDATE `country` SET `flag_image` = "/img/flags/78.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/e/ea/Flag_of_Monaco.svg/28px-Flag_of_Monaco.svg.png" WHERE `id` = 78;
UPDATE `country` SET `flag_image` = "/img/flags/79.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Flag_of_Montenegro.svg/28px-Flag_of_Montenegro.svg.png" WHERE `id` = 79;
UPDATE `country` SET `flag_image` = "/img/flags/80.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/20/Flag_of_the_Netherlands.svg/28px-Flag_of_the_Netherlands.svg.png" WHERE `id` = 80;
UPDATE `country` SET `flag_image` = "/img/flags/81.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Flag_of_Norway.svg/28px-Flag_of_Norway.svg.png" WHERE `id` = 81;
UPDATE `country` SET `flag_image` = "/img/flags/82.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/1/12/Flag_of_Poland.svg/28px-Flag_of_Poland.svg.png" WHERE `id` = 82;
UPDATE `country` SET `flag_image` = "/img/flags/83.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Portugal.svg/28px-Flag_of_Portugal.svg.png" WHERE `id` = 83;
UPDATE `country` SET `flag_image` = "/img/flags/84.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Flag_of_Romania.svg/28px-Flag_of_Romania.svg.png" WHERE `id` = 84;
UPDATE `country` SET `flag_image` = "/img/flags/86.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/b/b1/Flag_of_San_Marino.svg/28px-Flag_of_San_Marino.svg.png" WHERE `id` = 86;
UPDATE `country` SET `flag_image` = "/img/flags/87.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/ff/Flag_of_Serbia.svg/28px-Flag_of_Serbia.svg.png" WHERE `id` = 87;
UPDATE `country` SET `flag_image` = "/img/flags/88.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Flag_of_Slovakia.svg/28px-Flag_of_Slovakia.svg.png" WHERE `id` = 88;
UPDATE `country` SET `flag_image` = "/img/flags/89.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Flag_of_Slovenia.svg/28px-Flag_of_Slovenia.svg.png" WHERE `id` = 89;
UPDATE `country` SET `flag_image` = "/img/flags/90.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/9/9a/Flag_of_Spain.svg/28px-Flag_of_Spain.svg.png" WHERE `id` = 90;
UPDATE `country` SET `flag_image` = "/img/flags/91.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/4/4c/Flag_of_Sweden.svg/28px-Flag_of_Sweden.svg.png" WHERE `id` = 91;
UPDATE `country` SET `flag_image` = "/img/flags/92.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Flag_of_Switzerland.svg/20px-Flag_of_Switzerland.svg.png" WHERE `id` = 92;
UPDATE `country` SET `flag_image` = "/img/flags/94.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Flag_of_Ukraine.svg/28px-Flag_of_Ukraine.svg.png" WHERE `id` = 94;
UPDATE `country` SET `flag_image` = "/img/flags/95.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/a/ae/Flag_of_the_United_Kingdom.svg/28px-Flag_of_the_United_Kingdom.svg.png" WHERE `id` = 95;
UPDATE `country` SET `flag_image` = "/img/flags/96.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/00/Flag_of_the_Vatican_City.svg/20px-Flag_of_the_Vatican_City.svg.png" WHERE `id` = 96;
UPDATE `country` SET `flag_image` = "/img/flags/75.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Flag_of_Macedonia.svg/28px-Flag_of_Macedonia.svg.png" WHERE `id` = 75;
UPDATE `country` SET `flag_image` = "/img/flags/196.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/b/b9/Flag_of_Australia.svg/28px-Flag_of_Australia.svg.png" WHERE `id` = 196;
UPDATE `country` SET `flag_image` = "/img/flags/197.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Flag_of_New_Zealand.svg/28px-Flag_of_New_Zealand.svg.png" WHERE `id` = 197;
UPDATE `country` SET `flag_image` = "/img/flags/198.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Flag_of_Fiji.svg/28px-Flag_of_Fiji.svg.png" WHERE `id` = 198;
UPDATE `country` SET `flag_image` = "/img/flags/199.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/db/Flag_of_French_Polynesia.svg/28px-Flag_of_French_Polynesia.svg.png" WHERE `id` = 199;
UPDATE `country` SET `flag_image` = "/img/flags/200.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/0/07/Flag_of_Guam.svg/28px-Flag_of_Guam.svg.png" WHERE `id` = 200;
UPDATE `country` SET `flag_image` = "/img/flags/201.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/Flag_of_Kiribati.svg/28px-Flag_of_Kiribati.svg.png" WHERE `id` = 201;
UPDATE `country` SET `flag_image` = "/img/flags/202.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Flag_of_the_Marshall_Islands.svg/28px-Flag_of_the_Marshall_Islands.svg.png" WHERE `id` = 202;
UPDATE `country` SET `flag_image` = "/img/flags/204.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/3/30/Flag_of_Nauru.svg/28px-Flag_of_Nauru.svg.png" WHERE `id` = 204;
UPDATE `country` SET `flag_image` = "/img/flags/205.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Flag_of_New_Caledonia.svg/28px-Flag_of_New_Caledonia.svg.png" WHERE `id` = 205;
UPDATE `country` SET `flag_image` = "/img/flags/206.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/e/e3/Flag_of_Papua_New_Guinea.svg/28px-Flag_of_Papua_New_Guinea.svg.png" WHERE `id` = 206;
UPDATE `country` SET `flag_image` = "/img/flags/207.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Flag_of_Samoa.svg/28px-Flag_of_Samoa.svg.png" WHERE `id` = 207;
UPDATE `country` SET `flag_image` = "/img/flags/208.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/7/74/Flag_of_the_Solomon_Islands.svg/28px-Flag_of_the_Solomon_Islands.svg.png" WHERE `id` = 208;
UPDATE `country` SET `flag_image` = "/img/flags/209.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Tonga.svg/28px-Flag_of_Tonga.svg.png" WHERE `id` = 209;
UPDATE `country` SET `flag_image` = "/img/flags/210.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Flag_of_Tuvalu.svg/28px-Flag_of_Tuvalu.svg.png" WHERE `id` = 210;
UPDATE `country` SET `flag_image` = "/img/flags/211.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Vanuatu.svg/28px-Flag_of_Vanuatu.svg.png" WHERE `id` = 211;
UPDATE `country` SET `flag_image` = "/img/flags/212.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/d/d2/Flag_of_Wallis_and_Futuna.svg/28px-Flag_of_Wallis_and_Futuna.svg.png" WHERE `id` = 212;
UPDATE `country` SET `flag_image` = "/img/flags/203.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/e/e4/Flag_of_the_Federated_States_of_Micronesia.svg/28px-Flag_of_the_Federated_States_of_Micronesia.svg.png" WHERE `id` = 203;
UPDATE `country` SET `flag_image` = "/img/flags/118.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/6/63/Flag_of_Macau.svg/28px-Flag_of_Macau.svg.png" WHERE `id` = 118;
UPDATE `country` SET `flag_image` = "/img/flags/179.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png" WHERE `id` = 179;
UPDATE `country` SET `flag_image` = "/img/flags/156.png", `original_flag_image` = "http://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Flag_of_Equatorial_Guinea.svg/28px-Flag_of_Equatorial_Guinea.svg.png" WHERE `id` = 156;

-- okh 05.06

CREATE TABLE `match_region` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`match_id` INT(11) NOT NULL,
	`region_id` INT(11) NOT NULL,
	`title` VARCHAR(255) NULL DEFAULT NULL,
	`intro` TEXT NULL,
	`header_image_path` VARCHAR(255) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `match_id` (`match_id`),
	INDEX `region_id` (`region_id`),
	CONSTRAINT `match_region_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`),
	CONSTRAINT `match_region_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

CREATE TABLE `featured_player` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `player_id` INT(11) NULL,
  `goals` INT(3) NULL DEFAULT '0',
  `matches_played` INT(3) NULL DEFAULT '0',
  `match_starts` INT(3) NULL DEFAULT '0',
  `minutes_played` INT(6) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `player_id` (`player_id`),
  CONSTRAINT `FK_featured_player_player` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
  ROW_FORMAT=DEFAULT;

CREATE TABLE `featured_goalkeeper` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `player_id` INT(11) NULL,
  `saves` INT(6) NULL DEFAULT '0',
  `matches_played` INT(3) NULL DEFAULT '0',
  `penalty_saves` INT(6) NULL DEFAULT '0',
  `clean_sheets` INT(3) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `player_id` (`player_id`),
  CONSTRAINT `FK_featured_goalkeeper_player` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
  ROW_FORMAT=DEFAULT;

ALTER TABLE `match_region`  ADD COLUMN `featured_player_id` INT(11) NULL DEFAULT NULL AFTER `region_id`,  ADD COLUMN `featured_goalkeeper_id` INT(11) NULL DEFAULT NULL AFTER `featured_player_id`,  ADD INDEX `featured_player_id` (`featured_player_id`),  ADD INDEX `featured_goalkeeper_id` (`featured_goalkeeper_id`),  ADD CONSTRAINT `FK_match_region_featured_player` FOREIGN KEY (`featured_player_id`) REFERENCES `featured_player` (`id`),  ADD CONSTRAINT `FK_match_region_featured_goalkeeper` FOREIGN KEY (`featured_goalkeeper_id`) REFERENCES `featured_goalkeeper` (`id`);


CREATE TABLE `featured_prediction` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NULL,
	`copy` TEXT NULL,
	`image_path` VARCHAR(255) NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

ALTER TABLE `match_region`  ADD COLUMN `featured_prediction_id` INT(11) NULL DEFAULT NULL AFTER `featured_goalkeeper_id`,  ADD INDEX `featured_prediction_id` (`featured_prediction_id`),  ADD CONSTRAINT `FK_match_region_featured_prediction` FOREIGN KEY (`featured_prediction_id`) REFERENCES `featured_prediction` (`id`);

-- okh 11.06

ALTER TABLE `match_region`  ADD COLUMN `display_featured_player` TINYINT(1) NULL DEFAULT NULL AFTER `header_image_path`;
ALTER TABLE `match_region`  CHANGE COLUMN `title` `pre_match_report_title` VARCHAR(255) NULL DEFAULT NULL AFTER `featured_prediction_id`,  CHANGE COLUMN `intro` `pre_match_report_intro` TEXT NULL AFTER `pre_match_report_title`,  CHANGE COLUMN `header_image_path` `pre_match_report_header_image_path` VARCHAR(255) NULL DEFAULT NULL AFTER `pre_match_report_intro`;
ALTER TABLE `match_region`  CHANGE COLUMN `pre_match_report_intro` `pre_match_report_intro` TEXT NULL DEFAULT NULL AFTER `pre_match_report_title`;
ALTER TABLE `match_region`  ADD COLUMN `post_match_report_title` VARCHAR(255) NULL DEFAULT NULL AFTER `pre_match_report_header_image_path`,  ADD COLUMN `post_match_report_intro` TEXT NULL DEFAULT NULL AFTER `post_match_report_title`,  ADD COLUMN `post_match_report_header_image_path` VARCHAR(255) NULL DEFAULT NULL AFTER `post_match_report_intro`;

-- okh 14.06
CREATE TABLE `footer_page` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `language_id` INT(11) NOT NULL,
  `type` ENUM('terms','privacy','contact-us','cookies-policy') NOT NULL,
  `content` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `FK_footer_page_language` (`language_id`),
  INDEX `type` (`type`),
  CONSTRAINT `FK_footer_page_language` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

-- oko 14.06

CREATE TABLE `share_copy` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`engine` ENUM('Facebook', 'Twitter'),
	`target` ENUM('PreMatchReport', 'PostMatchReport'),
	`copy` TEXT NOT NULL,
	`weight` INT NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

INSERT INTO `share_copy` (
`engine` ,
`target` ,
`copy` ,
`weight`
)
VALUES (
'Facebook',  'PreMatchReport',  'I''ve made first prediction! Join me my friends!',  '1'
), (
'Twitter',  'PreMatchReport',  'I''ve made first prediction! Join me my followers!',  '1'
), (
'Facebook',  'PreMatchReport',  'I''ve made a prediction! Join me my friends!',  '3'
), (
'Twitter',  'PreMatchReport',  'I''ve made a prediction! Join me my followers!',  '3'
), (
'Facebook',  'PreMatchReport',  '',  '3'
), (
'Twitter',  'PreMatchReport',  '',  '3'
), (
'Facebook',  'PreMatchReport',  '',  '3'
), (
'Twitter',  'PreMatchReport',  '',  '3'
), (
'Facebook',  'PreMatchReport',  '',  '3'
), (
'Twitter',  'PreMatchReport',  '',  '3'
), (
'Facebook',  'PreMatchReport',  '',  '3'
), (
'Twitter',  'PreMatchReport',  '',  '3'
);

CREATE TABLE `achievement_block` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`type` ENUM('First Correct Result', 'First Correct Scorer'),
	`title` VARCHAR(255) NOT NULL,
	`description` TEXT NOT NULL,
	`icon_path` VARCHAR(255) NOT NULL,
	`weight` INT NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

INSERT INTO  `achievement_block` (
`type` ,
`title` ,
`description` ,
`icon_path` ,
`weight`
)
VALUES (
'First Correct Result',  'Well done!',  'You predicted correct result first time at the season!',  '/img/award/51a6086f44013.png',  '1'
), (
'First Correct Scorer',  'Well done!',  'You predicted correct scorer first time at the season!',  '/img/award/51a6086f44012.png',  '1'
);

ALTER TABLE  `share_copy` ADD  `achievement_block_id` INT NULL AFTER  `weight`;
ALTER TABLE `share_copy` ADD FOREIGN KEY (achievement_block_id) REFERENCES `achievement_block`(id);

INSERT INTO  `share_copy` (
`engine` ,
`target` ,
`copy` ,
`weight` ,
`achievement_block_id`
)
VALUES (
'Facebook',  'PostMatchReport',  'You predicted correct result first time at the season!',  '0',  '1'
), (
'Twitter',  'PostMatchReport',  'You predicted correct result first time at the season!',  '0',  '1'
), (
 'Facebook',  'PostMatchReport',  'You predicted correct scorer first time at the season!',  '0',  '2'
), (
 'Twitter',  'PostMatchReport',  'You predicted correct scorer first time at the season!',  '0',  '2'
);

-- oko 17.06

CREATE TABLE `league_user_place` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`league_user_id` INT NOT NULL,
	`match_id` INT NOT NULL,
	`place` INT NOT NULL,
	`previous_place` INT,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;
ALTER TABLE `league_user_place` ADD FOREIGN KEY (league_user_id) REFERENCES `league_user`(id);
ALTER TABLE `league_user_place` ADD FOREIGN KEY (match_id) REFERENCES `match`(id);

-- okh 17.06

INSERT INTO `settings` VALUES (null, 'help-and-support-email',''),(null, 'main-site-link','');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (null, 'send-welcome-email', '0');

-- okh 18.06
ALTER TABLE `footer_page`  CHANGE COLUMN `type` `type` ENUM('terms','privacy','contact-us','cookies-policy','help-and-support') NOT NULL AFTER `language_id`;

-- okh 19.06
ALTER TABLE `user`  CHANGE COLUMN `gender` `gender` VARCHAR(10) NULL AFTER `birthday`;

CREATE TABLE `logotype` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `language_id` INT(11) NOT NULL,
  `emblem_image_path` VARCHAR(255) NOT NULL,
  `logotype_image_path` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_logotype_language` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

ALTER TABLE `logotype`  ADD UNIQUE INDEX `language_id` (`language_id`);

CREATE TABLE `term` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `is_required` TINYINT(1) NOT NULL DEFAULT '0',
  `is_checked` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

-- okh 20.06

CREATE TABLE `term_copy` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `term_id` INT(11) NOT NULL,
  `language_id` INT(11) NOT NULL,
  `copy` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `term_id` (`term_id`),
  INDEX `language_id` (`language_id`),
  CONSTRAINT `FK_term_copy_term` FOREIGN KEY (`term_id`) REFERENCES `term` (`id`),
  CONSTRAINT `FK_term_copy_language` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

-- oko 26.06

CREATE TABLE `feed` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_name` VARCHAR(255) NOT NULL,
  `type` ENUM('F1', 'F2', 'F7', 'F40') NULL,
  `last_sync_result` ENUM('Success', 'Error') NOT NULL,
  `last_update` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_name` (`file_name`)
) COLLATE='utf8_general_ci' ENGINE=InnoDB;

-- okh 26.06
ALTER TABLE `match`  CHANGE COLUMN `stadium_name` `stadium_name` VARCHAR(100) NULL AFTER `week`;
ALTER TABLE `match`  CHANGE COLUMN `city_name` `city_name` VARCHAR(100) NULL AFTER `stadium_name`;

-- okh 27.06
CREATE TABLE `emblem` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
  ROW_FORMAT=DEFAULT;
ALTER TABLE `logotype`  CHANGE COLUMN `emblem_image_path` `emblem_id` INT(11) NOT NULL AFTER `language_id`,  ADD INDEX `emblem_id` (`emblem_id`);
ALTER TABLE `logotype`  ADD CONSTRAINT `FK_logotype_emblem` FOREIGN KEY (`emblem_id`) REFERENCES `emblem` (`id`);

CREATE TABLE `account_removal` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date` DATETIME NOT NULL,
  `account_type` ENUM('direct','facebook') NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `account_type` (`account_type`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
  ROW_FORMAT=DEFAULT;

-- okh 28.06
ALTER TABLE `role`  DROP INDEX `parent_id`,  ADD INDEX `parent_id` (`parent_id`),  ADD INDEX `name` (`name`);
ALTER TABLE `user`  ADD COLUMN `last_logged_in` DATETIME NULL AFTER `date`;
-- When create admin we don't set these fields
ALTER TABLE `user`  CHANGE COLUMN `title` `title` VARCHAR(5) NULL AFTER `id`,  CHANGE COLUMN `birthday` `birthday` DATE NULL AFTER `country_id`;

-- oko 28.06

ALTER TABLE  `match_goal` CHANGE  `player_id`  `player_id` INT( 11 ) NULL;
CREATE TABLE `default_report_content` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `intro` TEXT NOT NULL,
  `header_image` VARCHAR(255) NOT NULL,
  `region_id` INT(11) NOT NULL,
  `report_type` ENUM('Pre-Match', 'Post-Match') NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_default_report_content_region` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`)
) COLLATE='utf8_general_ci' ENGINE=InnoDB;

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

-- okh 09.07

CREATE TABLE `howtoplay_content` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `language_id` INT(11) NOT NULL,
  `heading` VARCHAR(255) NULL,
  `description` TEXT NULL,
  `foreground_image_id` INT(11) NULL,
  `order` INT(4) NULL,
  PRIMARY KEY (`id`),
  INDEX `language_id` (`language_id`),
  INDEX `foreground_image_id` (`foreground_image_id`),
  CONSTRAINT `FK_howtoplay_content_language` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
  CONSTRAINT `FK_howtoplay_content_content_image` FOREIGN KEY (`foreground_image_id`) REFERENCES `content_image` (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
  ROW_FORMAT=DEFAULT;


-- okh 19.07

ALTER TABLE `footer_page`  CHANGE COLUMN `content` `content` TEXT NULL AFTER `type`;
ALTER TABLE `logotype`  CHANGE COLUMN `logotype_image_path` `logotype_image_path` VARCHAR(255) NULL AFTER `emblem_id`;
ALTER TABLE `term_copy`  CHANGE COLUMN `copy` `copy` TEXT NULL AFTER `language_id`;
ALTER TABLE `footer_image`  DROP INDEX `region_id`,  DROP FOREIGN KEY `footer_image_ibfk_1`;
ALTER TABLE `footer_image`  CHANGE COLUMN `region_id` `language_id` INT(11) NOT NULL AFTER `id`,  ADD INDEX `language_id` (`language_id`);
ALTER TABLE `footer_image`  ADD CONSTRAINT `FK_footer_image_language` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);

--dsh 22.07

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
  PRIMARY KEY (`id`),
  KEY `match_id` (`match_id`),
  KEY `featured_player_id` (`featured_player_id`),
  KEY `featured_goalkeeper_id` (`featured_goalkeeper_id`),
  KEY `featured_prediction_id` (`featured_prediction_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `match_language`
  ADD CONSTRAINT `FK_match_region_featured_goalkeeper` FOREIGN KEY (`featured_goalkeeper_id`) REFERENCES `featured_goalkeeper` (`id`),
  ADD CONSTRAINT `FK_match_region_featured_player` FOREIGN KEY (`featured_player_id`) REFERENCES `featured_player` (`id`),
  ADD CONSTRAINT `FK_match_region_featured_prediction` FOREIGN KEY (`featured_prediction_id`) REFERENCES `featured_prediction` (`id`),
  ADD CONSTRAINT `match_language_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`),
  ADD CONSTRAINT `match_language_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);

INSERT INTO match_language( `id`, `match_id`, `language_id`, `featured_player_id`, `featured_goalkeeper_id`, `featured_prediction_id`, `pre_match_report_title`, `pre_match_report_intro`, `pre_match_report_header_image_path`, `post_match_report_title`, `post_match_report_intro`, `post_match_report_header_image_path`, `display_featured_player` )
SELECT  `match_region`.`id` ,  `match_region`.`match_id` ,  `match_region`.`region_id` AS language_id,  `match_region`.`featured_player_id` ,  `match_region`.`featured_goalkeeper_id` ,  `match_region`.`featured_prediction_id` , `match_region`.`pre_match_report_title` ,  `match_region`.`pre_match_report_intro` ,  `match_region`.`pre_match_report_header_image_path` ,  `match_region`.`post_match_report_title` ,  `match_region`.`post_match_report_intro` , `match_region`.`post_match_report_header_image_path` ,  `match_region`.`display_featured_player`
FROM  `match_region`
INNER JOIN  `match` ON  `match`.`id` =  `match_region`.`match_id`
INNER JOIN  `language` ON  `language`.`id` =  `match_region`.`region_id`

DROP TABLE `match_region`;

--dsh 24.07

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

--dsh 26.07

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

-- oko  26.07

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

--dsh 06.08

ALTER TABLE  `default_report_content` CHANGE  `header_image`  `header_image` VARCHAR( 255 ) CHARACTER SET utf8 NULL

-- oko 05.08

CREATE TABLE `private_league` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`league_id` INT NOT NULL ,
`unique_hash` VARCHAR(10) NOT NULL
) ENGINE = InnoDB;
ALTER TABLE `private_league` ADD FOREIGN KEY (league_id) REFERENCES `league`(id);
ALTER TABLE `private_league` ADD UNIQUE INDEX `unique_hash` (`unique_hash`);

-- oko 09.08
ALTER TABLE `user` DROP `is_active`;