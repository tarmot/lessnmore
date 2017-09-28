<?php

class User
{
  CONST PRIV_LEVEL_NONE = 0;
  CONST PRIV_LEVEL_SHRINK = 10;
  CONST PRIV_LEVEL_STATS = 20;
  CONST PRIV_LEVEL_EDITOR = 30;
  CONST PRIV_LEVEL_ADMIN = 100;

  public static function create(
    $username,
    $password,
    $priv_level
  ) {
    global $db;
    $stmt = $db->prepare('INSERT INTO '.DB_PREFIX.'users (username, password_hash, priv_level, created_on, password_needs_changed) VALUES(?, ?, ?, NOW(), ?)');
  	$stmt->bindValue(1, $username);
    // Support for dummy users with no privs, to throw off some hackers
  	$stmt->bindValue(2, (
      $password === false
      ? 'impossible hash'
      : password_hash($password, PASSWORD_DEFAULT)
    ));
  	$stmt->bindValue(3, $priv_level);
  	$stmt->bindValue(4, true);

  	if($stmt->execute())
  		return $db->lastInsertId();
  	else
  		return $stmt->errorCode().': '.$stmt->errorInfo();
  }

  public static function log_in($username, $password) {
    global $db;

  }

  function __construct($dbFields)
  {
    $this->id = $dbFields->id;
    $this->username = $dbFields->username;
    $this->created_on = $dbFields->created_on;
    $this->priv_level = $dbFields->priv_level;
    $this->password_hash = $dbFields->password_hash;
    $this->password_needs_changed = $dbFields->password_needs_changed;
  }

  public function password_is($password) {
    return password_verify($password, $this->password_hash);
  }

  public function can_shrink() {
    return $this->priv_level >= User::PRIV_LEVEL_SHRINK;
  }

  public function can_view_stats() {
    return $this->priv_level >= User::PRIV_LEVEL_STATS;
  }

  public function can_edit_urls() {
    return $this->priv_level >= User::PRIV_LEVEL_RW;
  }

  public function can_administrate() {
    return $this->priv_level >= User::PRIV_LEVEL_ADMIN;
  }
}
