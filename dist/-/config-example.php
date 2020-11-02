<?php

// LOGIN
define('USERNAME',	'ville');
define('PASSWORD',	'ville');

// DATABASE
define('DB_NAME', 		'lessn');
define('DB_USERNAME', 	'ville');
define('DB_PASSWORD', 	'ville');
define('DB_PREFIX', 	'lessn_'); // Lessn More can share a database by prefixing table names
define('DB_DRIVER',		'mysql'); // mysql, pgsql, sqlite (sqlite not fully tested)
define('DB_SERVER', 	'localhost'); // You may able to leave as-is

// CHAMILO DATABASE
define('CHAMILO_DB_NAME', 		''); // Put chamilo database name, usually "chamilo"
define('CHAMILO_DB_USERNAME', 	''); // Put Chamilo database user name
define('CHAMILO_DB_PASSWORD', 	''); // Put Chamilo database password
define('CHAMILO_DB_DRIVER',		'mysql'); // mysql, pgsql, sqlite (sqlite not fully tested)
define('CHAMILO_DB_SERVER', 	'localhost'); // You may able to leave as-is

// Enable statistics?
define('RECORD_URL_STATS', true);

define('COOKIE_SALT', 	'1a5al-sSXqO[]P83Rfoo');
define('API_SALT',		'XqO)-O4K2595JMEOQ');

// Your timezone. Because your server isn't necessarily on your time
// Use a timezone string from here: https://secure.php.net/manual/timezones.php
// define('TIMEZONE', 'America/Winnipeg');

// How should short URL slugs be generated?
// 'base36'       - Used by the original Lessn. [0-9a-z]
// 'mixed-smart'  - RECOMMENDED! Mixed case, except homoglyphs.
//                - [0-9a-zA-Z] except homoglyphs [oO0lI1]
// 'base62'       - Mixed case. [0-9a-zA-Z]
// 'smart'        - Like the original Lessn, but excludes homoglyphs.
//                - [0-9a-z] except [o0l1]
// Best practices are documented at <https://alanhogan.com/tips/rel-shortlink-for-short-urls#service-homoglyphs>
define('AUTO_SLUG_METHOD', 'base36'); //CHANGE to a *smart method!

// Lessn was defined to return URLs like /0, /1, /2, /3 and so on,
// but many people want 'unguessable' short URLs like /u7b3rx.
// This option switches to such a method when an integer greater than 1 is set.
// To retain the original behavior of sequential, short slugs, set to false.
define('RANDOM_SLUG_LENGTH', 7);

// String with any characters you would like to manually exclude from future
// auto-generated URL slugs. false if not.
// Note if you pick the 'smart' or 'mixed-smart' AUTO_SLUG_METHOD
// then there is little point to this.
define('ADDITIONAL_HOMOGLYPHS_TO_AVOID', false); //e.g. 'i'


// Are there any characters, words, or phrases you want banned?
// If so, set them in banned_words.php and set this to true.
define('USE_BANNED_WORD_LIST', true);

// Allow banned words in custom URLs, or just auto-generated?
define('ALLOW_BANNED_WORDS_IN_CUSTOM_URLS', true); //true only supported option at this time
define('ALLOW_HOMOGLYPHS_IN_CUSTOM_URLS', true); //true only supported option at this time


// URL to hit if someone visits your site without a short url, set to null for just a blank page
define('HOMEPAGE_URL', NULL); //e.g. 'http://example.com'
// If an slug is not found occurns, e.g. http://doma.in/this-slug-doesn't-exist
define('ERROR_404_URL', NULL); //e.g. 'http://example.com/404'
// If an slug was deleted (marked 'gone')
define('GONE_410_URL', NULL); //e.g. 'http://example.com/gone'

define('APP_NAME', 'Lessn More');

// For debuggers, developers, and the curious
define('LOG_MODE', false); // Not recommended, a bit slower.

// Default (false) will make generated shortlinks use the current
// server protocol (http or https).
// You can hardcode a value of 'http' or 'https' (no colon or slashes).
define('PROTOCOL_OVERRIDE', false);

define('DISPLAY_ERRORS', true);
