-- dsh 04.09
INSERT INTO `share_copy` (`id`, `engine`, `target`, `copy`, `weight`, `achievement_block_id`)
VALUES (NULL, 'Facebook', 'PreMatchReport', '', '5', NULL),
(NULL, 'Twitter', 'PreMatchReport', '', '5', NULL),
(NULL, 'Facebook', 'PostMatchReport', '', '4', NULL),
(NULL, 'Twitter', 'PostMatchReport', '', '4', NULL);

-- dsh 19.09
ALTER TABLE  `featured_player` CHANGE  `match_starts`  `number_of_assists` INT( 3 ) NULL DEFAULT  '0',
CHANGE  `minutes_played`  `number_of_shots` INT( 3 ) NULL DEFAULT  '0';
