Lessn More 2.0
==============

**NOTE: This branch (master) does NOT have all the changes listed below implemented. NOT A STABLE RELEASE.**

Homepage: <http://lessnmore.net>  
Source/Fork: <http://github.com/alanhogan/lessmore>

Lessn More is a personal url shortener.

Features:

*	The ability to use custom short URLs (slugs), unlike Lessn
*	A bookmarklet that even supports custom short URLs
*	An API that supports the same commands as the web interface
*	Different auto-shorten modes (optional mixed case),
*	The ability to avoid lookalike characters, and 
*	An optional "banned word list" to prevent auto-generating offensive URLs.
*	Support for more shortened URLs than Lessn
*	The ability to add multiple slugs that point to the same long URL, unlike Lessn

Attention to detail:

*	Adding a new slug for a URL already in the database will become the "canonical"
	short URL, and will be returned if you ask Lessn More (either by API or not)
	for a short URL to the original resource
*	Amazingly, Lessn More lets you change the character set you want to use
	to generate short URLs on-the-fly, and yet the insertion algorithm
	is fast. (Not as fast as Lessn 1.0, by necessity, since Lessn 1.0 did not allow
	custom short URLs; but the worst-case insertion time after
	upgrade or a switch of insertion algorithms is on the order of O(log(n)) where
	<var>n</var> is the number of redirections in your database, and the common case is
	on the order of O(1) (constant time).)

An easy migration script will upgrade your database 
from an existing Lessn migration.


Requirements
-------------

* PHP 5.1+
* PHP's PDO
* MySQL, PostgreSQL, or SQLite
* mod_rewrite or similar rewrite system (see .htaccess)

<!-- 
  This document is written in Markdown,
  readable as text or convertible to HTML.
  Syntax: http://bit.ly/mkdnsyntax
  Converter & cheatsheet: http://tinyurl.com/mkdndingus 
  Editor w/ instant preview: http://tinyurl.com/mkdnwmd
  (TextMate: ⌃⌥⌘-P to preview as web page)
-->

History
-------

### v1.0

Lessn was the original personal URL shortening service,
written by Shaun Inman <http://shauninman.com/>. It required PHP, MySQL, and mod_rewrite.

### v1.1

Buttered URLs is a fork of Lessn by Jeremy Knope <http://github.com/jfro>.
Buttered URLs added logging, custom URLs, migration mechanism, and support for more database types.

### v2.0

Lessn More is a fork of Buttered URLs by Alan Hogan <http://github.com/alanhogan>.
Lessn More increased the robustness and flexibility of the insertion algorithm,
prevented slug conflicts, updated the bookmarklets, added multiple auto-shorten modes,
banned word lists, and enhanced security.


Legal
-----

Lessn is offered as-is, sans support and without warranty.
Copyright © 2009-10 Shaun Inman and contributors.


Installation
------------

Installation instructions are different depending on if you are upgrading or doing a fresh install.

### Fresh Install ###

**ONLY** follow these instructions if you are not upgrading!

1. Open /-/config.php in a plaintext editor and
	create a Lessn username and password then enter your
	database connection details.
	You may also choose other settings such as
	authentication salts and a default home page.

2. For the shortest URLs possible, upload the contents of this
	directory to your domain's root public folder.
	(For short urls when that is not possible, 
	upload the entire directory to your server  
	and rename to a single character or so. 
	Example: http://doma.in/x/)

3. Visit http://doma.in/-/ to log in & start using Lessn More!
	Be sure to grab the bookmarklets. (The required database table is created 
	automatically the first time you visit Lessn).

**NOTE:** If your Lessn'd urls aren't working you probably didn't
	upload the .htaccess file. Enable "Show invisible files" 
	in your FTP application. It's also possible that your host doesn't like
	the <IfModule>; try taking it out (this happens on 1and1).

### Upgrading ###

If you are upgrading from a previous version of Lessn or ButteredURLs:

#### Upgrading from Lessn 1.0.0 or 1.0.1

1. Using a tool like PhpMyAdmin or the MySQL CLI change the 
   checksum index type to INDEX (from UNIQUE).
2.	Continue below with "ALL VERSIONS"

#### ALL VERSIONS: Upgrading to ButteredURLs 2.0

1.	Note some old redirections so you can manually check they still work after upgrading (they should, but hey, it's important).
1.	Manually merge your old configs into the new config file.
	There will be new options you will want to make
	decisions about.
1.	Upload all lessn/BU files, excluding config.php, or making sure to use the new one.
1.	Go to http://doma.in/x/install.php?start=N where 
	N is 2 if upgrading from Lessen 1.0, or    
	N is 4 if upgrading from ButteredURLs 1.1.
1.	Test some old known working redirections
1.	Delete install.php.
1.	Grab the new bookmarklets with custom short URL support!

**Congratulations.** You are running the latest version of Lessn More.
