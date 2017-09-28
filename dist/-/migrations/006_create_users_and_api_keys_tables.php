<?php
// If install.php was deleted, don't allow access!
defined('OKAY_TO_MIGRATE') OR die('No direct access allowed.');

require_once("User.php");
require_once("random_compat/random.php");

class Create_Users_And_API_Keys_Tables extends Migration
{
	function up()
	{
		Migrator::message('inform',
			'This migration means usernames and passwords live ' .
			'in the database now, <em>not</em> in <code>config.php</code>. ' .
			'Your passwords will now be hashed with bcrypt (or the ' .
			'<code>password_hash()</code> default in your version of PHP).' .
			'<br><br>' .
			'You should change your password after logging in to benefit ' .
			'from this increased security. ' .
			'<br><br>' .
			'Oh, and now you can add more users, ' .
			'so you can let peole shrink URLs without giving them ' .
			'admin permissions. ' .
			'<br><br>' .
			'You can also create API keys with reduced permissions.￼￼￼',
			false, // escape
			false // nl2br
		);

		$u = $this->createTable(DB_PREFIX.'users');
		$u->column('id', 'serial', array('primary_key' => true, 'null' => false));
		$u->column('username', 'string', array('null' => false, 'size' => 127));
		$u->column('password_hash', 'string', array('null' => false, 'size' => 255));
		$u->column('created_on', 'datetime', array('null' => false));
		$u->column('priv_level', 'integer', array('null' => false));
		$u->column('password_needs_changed', 'bool', array('default' => false));
		$u->save();

		$this->createIndex(DB_PREFIX.'users',
			'username',
			'index_username',
			true // unique
		);

		$ak = $this->createTable(DB_PREFIX.'api_keys');
		$ak->column('id', 'serial', array('primary_key' => true, 'null' => false));
		$ak->column('user_id', 'integer', array('null' => false));
		$ak->column('secret', 'string', array('null' => false, 'size' => 32));
		$ak->column('created_on', 'datetime', array('null' => false));
		$ak->column('priv_level', 'integer', array('null' => false));
		$ak->save();

		$this->createIndex(DB_PREFIX.'api_keys',
			'secret',
			'index_api_key_secret',
			true // unique
		);

		Migrator::message('success',
			'Users & API Keys tables created.',
			true, // escape
			false // nl2br
		);

		// insert dummy row for hackers getting the first user in the table
		User::create(
			'dummy',
			false,
			User::PRIV_LEVEL_NONE
		);

		$admin_id;

		if (defined('USERNAME') && defined('PASSWORD') && defined('API_SALT')) {
			// TODO:
			// Insert existing admin row to users.
			// Add API key and associate with admin user.
			$admin_id = User::create(
				USERNAME,
				PASSWORD,
				User::PRIV_LEVEL_ADMIN
			);

			$api_key = md5(USERNAME.PASSWORD.API_SALT);

			if ($admin_id !== false) {
				Migrator::message('success',
				 	'Your username and password have been migrated to the database and hashed with a modern algorithm. You should delete USERNAME, PASSWORD, and API_SALT from your `config.php`!',
					true, // escape
					false // nl2br
				);

				$key_insert_stmt = $this->db->prepare('INSERT INTO '.DB_PREFIX.'api_keys (user_id, secret, priv_level, created_on) VALUES(?, ?, ?, NOW())');
				$key_insert_stmt->bindValue(1, $admin_id, PDO::PARAM_INT);
				$key_insert_stmt->bindValue(2, $api_key);
				$key_insert_stmt->bindValue(3, User::PRIV_LEVEL_EDITOR, PDO::PARAM_INT);
				$api_key_insert_result = $key_insert_stmt->execute();

				if ($api_key_insert_result) {
					Migrator::message('success',
					 	'Your API key has been successfully migrated to the database. Consider replacing it for increased security.',
						true, // escape
						true // nl2br
					);
				} else {
					Migrator::message('failure',
					 	'Your API key was not successfully migrated to the database. You can create one later.',
						true, // escape
						true // nl2br
					);
				}
			} else {
				Migrator::message('failure',
					"We failed to migrate your username and password into a database-backed user account."
				);
			}
		} else {
			$fresh_password = '';
			$password_alphabet =
				'0123456789' .
				'abcdefghijklmnopqrstuvwxyz' .
				'ABCDEFGHIJKLMNOPQRSTUVWXYZ' .
				'-_{}[]()!@#%^&*+=\\/?.,';
			for ($i=0; $i < 14; $i++) {
				$fresh_password .=
					$password_alphabet[random_int(0, strlen($password_alphabet))];
			}
			$admin_id = User::create(
				'admin',
				$fresh_password,
				User::PRIV_LEVEL_ADMIN
			);
			if ($admin_id !== false) {
				Migrator::message('success',
				 	"We have created a user account for you.
					Please carefully save this information.

					Username: admin
					Password: $fresh_password",
					true, // escape
					true // nl2br
				);

				// No need to create an API key.
				// They probably did not have one yet and can create it whenever.
			} else {
				Migrator::message('failure',
					"We failed to create a user account for you."
				);
			}
		}
	}

	function down()
	{
		$this->db->prepare('DROP TABLE '.DB_PREFIX.'users;')->execute();
		$this->db->prepare('DROP TABLE '.DB_PREFIX.'api_keys;')->execute();
	}
}
