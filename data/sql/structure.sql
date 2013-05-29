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