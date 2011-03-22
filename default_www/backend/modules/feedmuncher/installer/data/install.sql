CREATE  TABLE IF NOT EXISTS `feedmuncher_feeds` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `url` TEXT NOT NULL ,
  `source` VARCHAR(255) NOT NULL ,
  `language` VARCHAR(5) NOT NULL ,
  `author_user_id` INT(11) NOT NULL ,
  `auto_publish` ENUM('Y','N') NOT NULL ,
  `deleted` ENUM('Y','N') NOT NULL DEFAULT 'N' ,
  `category_id` INT(11) NOT NULL ,
  `target` ENUM('feedmuncher','blog') NOT NULL DEFAULT 'feedmuncher' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `feedmuncher_categories` (
 `id` int(11) NOT NULL auto_increment,
 `meta_id` int(11) NOT NULL,
 `language` varchar(5) NOT NULL,
 `title` varchar(255) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE  TABLE IF NOT EXISTS `feedmuncher_posts` (
  `id` INT(11) NOT NULL ,
  `revision_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `category_id` INT(11) NOT NULL ,
  `feed_id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
  `meta_id` INT(11) NOT NULL ,
  `language` VARCHAR(5) NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `introduction` TEXT NULL ,
  `text` TEXT,
  `date` DATETIME NOT NULL ,
  `edited_on` DATETIME NOT NULL ,
  `created_on` DATETIME NOT NULL ,
  `hidden` ENUM('Y','N') NOT NULL DEFAULT 'N' ,
  `allow_comments` ENUM('Y','N') NOT NULL DEFAULT 'N' ,
  `num_comments` INT(11) NOT NULL DEFAULT 0 ,
  `deleted` ENUM('Y','N') NOT NULL DEFAULT 'N' ,
  `status` enum('active','archived','draft') NOT NULL DEFAULT 'active',
  `target` ENUM('feedmuncher','blog') NOT NULL DEFAULT 'feedmuncher' ,
  `blog_post_id` INT(11) DEFAULT NULL ,
  PRIMARY KEY (`revision_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE  TABLE IF NOT EXISTS `feedmuncher_comments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `post_id` INT(11) NOT NULL ,
  `language` VARCHAR(5)  NOT NULL ,
  `created_on` DATETIME NOT NULL ,
  `author` VARCHAR(255) NOT NULL ,
  `email` VARCHAR(255) NOT NULL ,
  `website` VARCHAR(255) DEFAULT NULL ,
  `text` TEXT NOT NULL ,
  `type` ENUM('comment','trackback') NOT NULL DEFAULT 'comment' ,
  `status` ENUM('published','moderation','spam') NOT NULL DEFAULT 'moderation' ,
  `data` TEXT COMMENT 'Serialized array with extra data' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
