SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+01:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE `admin_logs` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `admin_username` text COLLATE utf8_unicode_ci NOT NULL,
  `ip` text COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `browser` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `passwd` text COLLATE utf8_unicode_ci NOT NULL,
  `ga_token` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `admins` (`id`, `username`, `passwd`, `ga_token`) VALUES
(1,	'admin',	'21232f297a57a5a743894a0e4a801fc3',	'');

DROP TABLE IF EXISTS `chat`;
CREATE TABLE `chat` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `sender` int(255) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `deposits`;
CREATE TABLE `deposits` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `player_id` int(255) NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `received` int(1) NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `txid` text COLLATE utf8_unicode_ci NOT NULL,
  `time_generated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `player` int(255) NOT NULL,
  `player_deck` text COLLATE utf8_unicode_ci NOT NULL,
  `player_deck_stand` int(1) NOT NULL DEFAULT '0',
  `player_deck_2` text COLLATE utf8_unicode_ci NOT NULL,
  `player_deck_2_stand` int(1) NOT NULL DEFAULT '0',
  `dealer_deck` text COLLATE utf8_unicode_ci NOT NULL,
  `ended` int(1) NOT NULL DEFAULT '0',
  `bet_amount` double NOT NULL,
  `winner` text COLLATE utf8_unicode_ci NOT NULL,
  `multiplier` double NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `initial_shuffle` text COLLATE utf8_unicode_ci NOT NULL,
  `client_seed` text COLLATE utf8_unicode_ci NOT NULL,
  `final_shuffle` text COLLATE utf8_unicode_ci NOT NULL,
  `used_cards` int(255) NOT NULL,
  `accessable_actions` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `giveaway_ip_limit`;
CREATE TABLE `giveaway_ip_limit` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `ip` text COLLATE utf8_unicode_ci NOT NULL,
  `claimed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ga_players`;
CREATE TABLE `ga_players` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `passwd` text COLLATE utf8_unicode_ci NOT NULL,
  `ga_token` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `ga_players` (`id`, `username`, `passwd`, `ga_token`) VALUES
(1, 'playertest',  '6d2aff483952d904179ca0c8c536a2c7', '');

DROP TABLE IF EXISTS `players`;
CREATE TABLE `players` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `hash` text COLLATE utf8_unicode_ci NOT NULL,
  `balance` double NOT NULL DEFAULT '0',
  `alias` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_last_active` datetime NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `lastip` text COLLATE utf8_unicode_ci NOT NULL,
  `initial_shuffle` text COLLATE utf8_unicode_ci NOT NULL,
  `client_seed` text COLLATE utf8_unicode_ci NOT NULL,
  `last_initial_shuffle` text COLLATE utf8_unicode_ci NOT NULL,
  `last_client_seed` text COLLATE utf8_unicode_ci NOT NULL,
  `last_final_shuffle` text COLLATE utf8_unicode_ci NOT NULL,
  `t_bets` int(255) NOT NULL DEFAULT '0',
  `t_wagered` double NOT NULL DEFAULT '0',
  `t_wins` int(255) NOT NULL DEFAULT '0',
  `t_profit` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `system`;
CREATE TABLE `system` (
  `id` int(1) NOT NULL DEFAULT '1',
  `autoalias_increment` int(255) NOT NULL DEFAULT '1',
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `currency` text COLLATE utf8_unicode_ci NOT NULL,
  `currency_sign` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `giveaway` int(1) NOT NULL DEFAULT '0',
  `giveaway_amount` double NOT NULL DEFAULT '0',
  `chat_enable` int(1) NOT NULL DEFAULT '1',
  `t_bets` int(255) NOT NULL DEFAULT '0',
  `t_wagered` double NOT NULL DEFAULT '0',
  `t_wins` int(255) NOT NULL DEFAULT '0',
  `t_player_profit` double NOT NULL DEFAULT '0',
  `giveaway_freq` int(255) NOT NULL DEFAULT '0',
  `min_withdrawal` double NOT NULL DEFAULT '0',
  `min_deposit` double NOT NULL DEFAULT '0',
  `min_confirmations` int(255) NOT NULL DEFAULT '1',
  `bankroll_maxbet_ratio` double NOT NULL DEFAULT '25',
  `number_of_decks` double NOT NULL DEFAULT '1',
  `deposits_last_round` time NOT NULL,
  `hits_on_soft` int(1) NOT NULL DEFAULT '0',
  `bj_pays` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `system` (`id`, `autoalias_increment`, `title`, `url`, `currency`, `currency_sign`, `description`, `giveaway`, `giveaway_amount`, `chat_enable`, `t_bets`, `t_wagered`, `t_wins`, `t_player_profit`, `giveaway_freq`, `min_withdrawal`, `min_deposit`, `min_confirmations`, `bankroll_maxbet_ratio`, `number_of_decks`, `deposits_last_round`, `hits_on_soft`, `bj_pays`) VALUES
(1,	1,	'default',	'default',	'default',	'default',	'default',	0,	0.0000005,	1,	0,	0,	0,	0,	30,	0.0002,	0.00000001,	1,	25,	4,	'00:00:00',	0,	0);

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `player_id` int(255) NOT NULL,
  `amount` double NOT NULL,
  `txid` text COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

