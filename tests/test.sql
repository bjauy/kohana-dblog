CREATE TABLE IF NOT EXISTS `logtest` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`tstamp` INT UNSIGNED NOT NULL,
	`type` TINYTEXT NOT NULL,
	`message` TEXT NOT NULL,
	`details` TEXT NOT NULL DEFAULT '',
	`addint` INT NOT NULL,
	`addtext` TEXT NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;