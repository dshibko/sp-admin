-- dsh 19.09
ALTER TABLE  `featured_player` CHANGE  `match_starts`  `number_of_assists` INT( 3 ) NULL DEFAULT  '0',
CHANGE  `minutes_played`  `number_of_shots` INT( 3 ) NULL DEFAULT  '0';

