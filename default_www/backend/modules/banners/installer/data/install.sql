CREATE TABLE IF NOT EXISTS `banners_standards` (
 `id` INT(11) NOT NULL AUTO_INCREMENT ,
 `name` VARCHAR(255) NOT NULL ,
 `width` INT(5) NOT NULL ,
 `height` INT(5) NOT NULL ,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `banners` (
 `id` INT(11) NOT NULL AUTO_INCREMENT,
 `name` VARCHAR(255) NOT NULL ,
 `standard_id` INT(11) NOT NULL ,
 `file` VARCHAR(255) NOT NULL ,
 `url` TEXT NOT NULL ,
 `language` varchar(5) NOT NULL,
 `date_from` DATETIME NULL ,
 `date_till` DATETIME NULL ,
 `num_clicks` INT NOT NULL DEFAULT 0 ,
 `num_views` INT NOT NULL DEFAULT 0 ,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `banners_groups` (
 `id` INT(11) NOT NULL AUTO_INCREMENT,
 `name` VARCHAR(255) NOT NULL ,
 `standard_id` INT(11) NOT NULL ,
 `language` varchar(5) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `banners_groups_members` (
 `id` INT(11) NOT NULL AUTO_INCREMENT,
 `group_id` INT(11) NOT NULL ,
 `banner_id` INT(11) NOT NULL ,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


INSERT INTO `banners_standards` (`id`, `name`, `width`, `height`) VALUES 
(NULL, 'Leaderboard', '728', '90'), 
(NULL, 'Rectangle', '180', '150');