<?php

if (isset($_POST['password'])) {
  echo "<p>Your password hash is <code>".
    htmlentities(password_hash($password, PASSWORD_BCRYPT), ENT_QUOTES, 'UTF-8').
    "</code></p>";
} else {
  ?>
  <form action="?" method="POST" accept-charset="utf-8">
    <label>
      <div>Password</div>
      <input name="password" type="password" value="" />
    </label>

    <div>
      <input type="submit" name="submit" value="Generate Secure Password Hash" />
    </div>
  </form>
  <?php
}
