<?php
// If install.php was deleted, don't allow access!
defined('OKAY_TO_MIGRATE') OR die('No direct access allowed.');


class Add_URL_Ownership extends Migration
{
	function up()
	{
		Migrator::message('inform',
		 	'Adding a column so that the owner of short URLs is recorded.',
			true // escape
		);
		$this->addColumn(DB_PREFIX.'urls', 'user_id', 'integer', array('null' => true));

		Migrator::message('success',
		 	'Column added. Newly shortened URLs will have ownership recorded.',
			true, // escape
			false // nl2br
		);
	}

	function down()
	{
		// Drop column. Not currently supported by QueryTools
	}
}
