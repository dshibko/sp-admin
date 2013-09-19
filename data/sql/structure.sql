ALTER TABLE `league_user` ADD UNIQUE league_user_unique (league_id, user_id);

ALTER TABLE  `featured_player` CHANGE  `match_starts`  `number_of_assists` INT( 3 ) NULL DEFAULT  '0',
CHANGE  `minutes_played`  `number_of_shots` INT( 6 ) NULL DEFAULT  '0';