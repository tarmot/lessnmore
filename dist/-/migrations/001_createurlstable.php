<?php
// If install.php was deleted, don't allow access!
defined('OKAY_TO_MIGRATE') OR die('No direct access allowed.'); 


class CreateURLsTable extends Migration
{
	function up()
	{
		$t = $this->createTable(DB_PREFIX.'urls');
		$t->column('id', 'serial', array('primary_key' => true));
		$t->column('url', 'text', array('null' => false));
		$t->column('checksum', 'bigint', array('null' => false, 'unsigned' => true));
		$t->column('protected', 'bool', array('null' => false, 'default' => 0)); // CSF mod column to know if short link is pin code protected
		$t->save();
		
		$this->createIndex(DB_PREFIX.'urls', 'checksum', 'checksum_index');
	}
	
	function down()
	{
		
	}
}
