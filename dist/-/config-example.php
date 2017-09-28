<?php

// DATABASE ---------------------------------------------------------

define('DB_NAME', 'lessn');

define('DB_USERNAME', 'lessn');

define('DB_PASSWORD', 'please set a good password here');

// Lessn More can share a database by prefixing table names
define('DB_PREFIX', 'lessn_');

// 'mysql' fully supported, or try 'pgsql' or 'sqlite'.
// This is the driver string as defined in PHP PDO.
define('DB_DRIVER', 'mysql');

define('DB_SERVER', 'localhost');



// SALTS ------------------------------------------------------------

define('COOKIE_SALT', '1a5al-sSXqO[]P83Rfoo');
define('API_SALT', 'XqO)-O4K2595JMEOQ');



// GENERAL OPTIONS --------------------------------------------------

define('RECORD_URL_STATS', true);

// Your timezone. Because your server isn't necessarily on your time
// Use a timezone string from here: http://www.php.net/manual/en/timezones.php
// define('TIMEZONE', 'America/Winnipeg');

// How should short URL slugs be generated?
// 'base36'       - Used by the original Lessn. [0-9a-z]
// 'mixed-smart'  - RECOMMENDED! Mixed case, except homoglyphs.
//                - [0-9a-zA-Z] except homoglyphs [oO0lI1]
// 'base62'       - Mixed case. [0-9a-zA-Z]
// 'smart'        - Like the original Lessn, but excludes homoglyphs.
//                - [0-9a-z] except [o0l1]
// Best practices are documented at <https://alanhogan.com/tips/rel-shortlink-for-short-urls#service-homoglyphs>
define('AUTO_SLUG_METHOD', 'mixed-smart');

// Lessn was defined to return URLs like /0, /1, /2, /3 and so on,
// which is what we call 'incremental mode',
// but many people want 'unguessable' short URLs like /u7b3rx.
// To use such a method, choose an integer greater than 1.
// To retain the original 'incremental' behavior of sequential,
// as-short-as-possible short URLs, set this to false.
define('RANDOM_SLUG_LENGTH', 7);

// String with any characters you would like to manually exclude from future
// auto-generated URL slugs. false if not.
// Note if you pick the 'smart' or 'mixed-smart' AUTO_SLUG_METHOD
// then there is little point to this.
define('ADDITIONAL_HOMOGLYPHS_TO_AVOID', false); //e.g. 'i9'

// You can swear as little or as much as you like, but set this to 'true'
// to avoid Lessn More cursing in auto-generated URLs.
define('USE_BANNED_WORD_LIST', true);

// Allow banned words in custom URLs, or just auto-generated?
define('ALLOW_BANNED_WORDS_IN_CUSTOM_URLS', true); //true only supported option at this time

define('ALLOW_HOMOGLYPHS_IN_CUSTOM_URLS', true); //true only supported option at this time


// URL to hit if someone visits your site without a short url, set to null for just a blank page
define('HOMEPAGE_URL', NULL); //e.g. 'http://example.com'

// Redirection to take when a slug is not found,
// e.g. http://doma.in/this-slug-doesn't-exist
define('ERROR_404_URL', NULL); //e.g. 'http://example.com/404'

// Redirection to take when a slug was soft-deleted (marked 'gone')
define('GONE_410_URL', NULL); //e.g. 'http://example.com/gone'

// Default (false) will make generated shortlinks use the current
// server protocol (http or https).
// You can hardcode a value of 'http' or 'https' (no colon or slashes).
define('PROTOCOL_OVERRIDE', false);


// ADVANCED OPTIONS -------------------------------------------------

define('APP_NAME', 'Lessn More');

// For debuggers, developers, and the curious
define('LOG_MODE', false); // Not recommended, a bit slower.
