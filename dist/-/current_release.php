<?php

// This file checks https://lessnmore.net/current.txt
// to determine the current version of Lessn More.

$cache_filename = 'current_release.txt';
$cache_lifetime = 60 * 60;

if (file_exists($cache_filename) && time() - $cache_lifetime < filemtime($cache_filename)) {
  $version = file_get_contents($cache_filename);
} else {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://lessnmore.net/current.txt');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // MITM possible.
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
  $version = trim(curl_exec($ch));
  curl_close($ch);

  // we should get back something that at least starts with a digit or more,
  // then a period, then another digit. Not a sophisticated check, just
  // hoping to properly error out if we get a nonsensical response
  if (!$version || !preg_match('/^\d+\./', $version)) {
    header('HTTP/1.1 502 Bad Gateway');
    exit('Did not get a good response from upstream');
  }

  // Update cache
  $fp = fopen($cache_filename, 'w');
  fwrite($fp, $version);
  fclose($fp);
}

// We can't 100% trust that the response is truly from https://lessnmore.net,
// so there is no guarantee this is a valid or trustworthy number. but echo it!
header('HTTP/1.1 200 OK');
header('Content-Type: text/plain');
echo $version;
