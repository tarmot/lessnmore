<?php
// If install.php was deleted, don't allow access!
defined('OKAY_TO_MIGRATE') OR die('No direct access allowed.');


class Add_Creation_Timestamp extends Migration
{
	function up()
	{
		Migrator::message('inform',
		 	'Adding a column so that the date & time of shortened URLs will be logged.',
			true // escape
		);
		$this->addColumn(DB_PREFIX.'urls', 'created_on', 'datetime', array('null' => true));

		Migrator::message('success',
		 	'Column added. New shortenings will have their date & time logged.',
			true, // escape
			false // nl2br
		);
	}

	function down()
	{
		// Drop column. Not currently supported by QueryTools
	}
}
