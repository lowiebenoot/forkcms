<?php

/**
 * Global configuration options and constants of the FORK CMS
 *
 * @package	Fork
 *
 * @author	Davy Hellemans <davy@netlash.com>
 * @author	Tijs Verkoyen <tijs@netlash.com>
 */

/**
 * Spoon configuration
 */
// should the debug information be shown
define('SPOON_DEBUG', true);
// mailaddress where the exceptions will be mailed to (<tag>-bugs@fork-cms.be)
define('SPOON_DEBUG_EMAIL', '');
// message for the visitors when an exception occur
define('SPOON_DEBUG_MESSAGE', 'Internal error.');
// default charset used in spoon.
define('SPOON_CHARSET', 'utf-8');


/**
 * Fork configuration
 */
// version of Fork
define('FORK_VERSION', '2.0.2');


/**
 * Database configuration
 */
// type of connection
define('DB_TYPE', 'mysql');
// database name
define('DB_DATABASE', 'banners');
// database host
define('DB_HOSTNAME', 'localhost');
// database port
define('DB_PORT', '3306');
// database username
define('DB_USERNAME', 'root');
// datebase password
define('DB_PASSWORD', 'root');


/**
 * Site configuration
 */
// the domain (without http)
define('SITE_DOMAIN', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'feedmuncher');
// the default title
define('SITE_DEFAULT_TITLE', 'Fork CMS');
// the url
define('SITE_URL', 'http://'. SITE_DOMAIN);
// is the site multilanguage?
define('SITE_MULTILANGUAGE', true);


/**
 * Path configuration
 *
 * Depends on the server layout
 */
// path to the website itself
define('PATH_WWW', '/Users/lowie/Documents/Webdesign/hosts/feedmuncher/forkcms/default_www');
// path to the library
define('PATH_LIBRARY', '/Users/lowie/Documents/Webdesign/hosts/feedmuncher/forkcms/library');

?>