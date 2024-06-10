CREATE TABLE `genders` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50)
);

CREATE TABLE `tournaments` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `date` date,
  `gender_id` int
);

CREATE TABLE `players` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `full_name` varchar(100),
  `hability_level` int,
  `lucky_level` int,
  `gender_id` int
);

CREATE TABLE `attributes` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50)
);

CREATE TABLE `player_attribute_values` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `player_id` int,
  `attribute_id` int,
  `value` int
);

CREATE TABLE `matches` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tournament_id` int,
  `stage` int,
  `player1_id` int,
  `player2_id` int,
  `player_winner_id` int,
  `next_opponent_id` int
);

ALTER TABLE `tournaments` ADD FOREIGN KEY (`gender_id`) REFERENCES `genders` (`id`);

ALTER TABLE `players` ADD FOREIGN KEY (`gender_id`) REFERENCES `genders` (`id`);

ALTER TABLE `player_attribute_values` ADD FOREIGN KEY (`player_id`) REFERENCES `players` (`id`);

ALTER TABLE `player_attribute_values` ADD FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`);

ALTER TABLE `matches` ADD FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`);

ALTER TABLE `matches` ADD FOREIGN KEY (`player1_id`) REFERENCES `players` (`id`);

ALTER TABLE `matches` ADD FOREIGN KEY (`player2_id`) REFERENCES `players` (`id`);

ALTER TABLE `matches` ADD FOREIGN KEY (`player_winner_id`) REFERENCES `players` (`id`);

ALTER TABLE `matches` ADD FOREIGN KEY (`next_opponent_id`) REFERENCES `matches` (`id`);
