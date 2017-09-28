<?php
// If install.php was deleted, don't allow access!
defined('OKAY_TO_MIGRATE') OR die('No direct access allowed.');


class Estimate_Creation_Timestamp extends Migration
{
	function up()
	{
		Migrator::message('inform',
			'Previously shortened URLs will be given estimates based on ' .
			'when they were first used. ' .
			'¯\_(ツ)_/¯ We can’t do better than that without magic!',
			true // escape
		);

		$estimation_query_suffix = "FROM `".DB_PREFIX."urls` AS `u` INNER JOIN `".DB_PREFIX."url_stats` AS `us` ON u.id = us.url_id AND u.created_on IS NULL";

		$estimation_count_query = "SELECT COUNT(*) $estimation_query_suffix";

		$count_stmt = $this->db->prepare($estimation_count_query);
		$count_stmt->execute();
		$todoCount = $count_stmt->fetchColumn();

		Migrator::message('inform',
		 	'We have ' . $todoCount .
			' redirection(s) we can assign estimated dates.',
			true, // escape
			false // nl2br
		);

		$estimation_data_query = "SELECT u.id, us.created_on $estimation_query_suffix";
		$work_stmt = $this->db->prepare($estimation_data_query);
		$work_stmt->execute();

		$estimated = 0;
		$failed = 0;

		// Give ourselves 2 minutes to do the first 100
		set_time_limit(120);

		while($row = $work_stmt->fetch()) {
			$upd = $this->db->prepare('UPDATE '.DB_PREFIX.'urls SET created_on = ? WHERE id = ? AND created_on IS NULL');
			$upd->bindValue(1, $row['created_on']);
			$upd->bindValue(2, $row['id']);
			$upd->execute();

			if ($upd->rowCount() > 0) {
				$estimated++;
			} else {
				$failed++;
			}

			if ($estimated % 100 === 0) {
				Migrator::message('inform',
				 	'Update: ' .
					$estimated . ' dates estimated, ' .
					$failed . ' failures.',
					true, // escape
					false // nl2br
				);

				// Let script run another 2 minutes
				set_time_limit(120);
			}
		}


		if ($estimated > 0) {
			Migrator::message('success',
			 	'We estimated ' . $estimated .
				' creation dates based on their first recorded usage.',
				true, // escape
				false // nl2br
			);
		}

		if ($failed > 0) {
			Migrator::message('failure',
			 	'We failed to estimate ' . $failed .
				' creation dates based on their first recorded usage.',
				true, // escape
				false // nl2br
			);

			throw new Exception('Failures occurred');
		}
	}

	function down()
	{
		// Can't.
	}
}
