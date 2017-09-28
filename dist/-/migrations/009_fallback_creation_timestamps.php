<?php
// If install.php was deleted, don't allow access!
defined('OKAY_TO_MIGRATE') OR die('No direct access allowed.');


class Fallback_Creation_Timestamps extends Migration
{
	function up()
	{
		Migrator::message('inform',
		 	'Short URLS that appear to have never been used ' .
			'(which could be all of your URLs if you recently upgraded from ' .
			'Lessn 1.0, which did not collect usage statistics) ' .
			'will be given estimated creation timestamps ' .
			'based on the idea that any URLs that come after them in ' .
			'the database were probably created later, and if literally ' .
			'none of them have timestamps, then today’s date will be used as ' .
			'the ultimate fallback.',
			true // escape
		);

		$estimated = 0;
		$failed = 0;
		$oldest;

		// SKIP if there are no URLs.
		$work_to_be_done_query = "SELECT COUNT(*)
			FROM `".DB_PREFIX."urls` AS `u`
			WHERE u.created_on IS NULL";

		$wtbds = $this->db->prepare($work_to_be_done_query);
		$wtbds->execute();
		$wtbdCount = $wtbds->fetchColumn();

		if ($wtbdCount == 0) {
			Migrator::message('inform',
				'No URLs are missing creation timestamps.',
				true // escape
			);
		} else {
			Migrator::message('inform',
				'We will use our simplistic fallback timestamp estimation technique on ' .
				$wtbdCount . ' URLs.',
				true // escape
			);

			// PRELIMINARY PHASE. Make sure the latest URL has a created_on value.

			$lastURLStmt = $this->db->prepare("SELECT *
				FROM ".DB_PREFIX."urls
				ORDER BY id DESC
				LIMIT 1");
			$lastURLStmt->execute();
			$lastURL = $lastURLStmt->fetch();
			if ($lastURL['created_on']) {
				Migrator::message('inform',
					'The newest URL in the database appears to be from ' .
					$lastURL['created_on'] . '.',
					true // escape
				);
				$oldest = $lastURL['created_on'];
			} else {
				$upd = $this->db->prepare('UPDATE '.DB_PREFIX.'urls SET created_on = NOW() WHERE id = ? AND created_on IS NULL');
				$upd->bindValue(1, $lastURL['id']);
				$upd->execute();

				if ($upd->rowCount() > 0) {
					$estimated++;
					$oldest = date("Y-m-d H:i:s e");
				} else {
					Migrator::message('failure',
					 	'Could not set the base case for the most recent URL.', true, true
					);
					throw new Exception('Could not set base case');
				}
			}

			// NEXT: Recursive phase
			// Find biggest ID of a URL that has no created_on value, then find its newer sibling

			$next_url_to_estimate_q = "SELECT id
				FROM ".DB_PREFIX."urls AS `u`
				WHERE u.created_on IS NULL
				ORDER BY id DESC";
			$nextStmt = $this->db->prepare($next_url_to_estimate_q);
			$nextStmt->execute();
			while (($id = $nextStmt->fetchColumn()) !== false) {
				set_time_limit(30);
				$bigSiblingStmt = $this->db->prepare("SELECT created_on
					FROM ".DB_PREFIX."urls AS `u`
					WHERE u.id > ?
					AND created_on IS NOT NULL
					ORDER BY id ASC");
				$bigSiblingStmt->bindValue(1, $id);
				$bigSiblingStmt->execute();
				$created_on = $bigSiblingStmt->fetchColumn();

				if (!$created_on) {
					throw new Exception('The base case is a lie!');
				}

				$upd = $this->db->prepare('UPDATE '.DB_PREFIX.'urls SET created_on = ? WHERE id = ? AND created_on IS NULL');
				$upd->bindValue(1, $created_on);
				$upd->bindValue(2, $id);
				$upd->execute();

				if ($upd->rowCount() > 0) {
					$estimated++;
					$oldest = $created_on;
				} else {
					$failed++;
					throw new Exception(
						'Could not update URL creation date to match its big sibling’s'
					);
				}
				if ($estimated % 100 === 0) {
					Migrator::message('inform',
						'Update: ' .
						$estimated . ' dates estimated, ' .
						$failed . ' failures.',
						true, // escape
						false // nl2br
					);
				}
			}

			if ($estimated > 0) {
				Migrator::message('success',
					'That seemed to work. We just estimated the creation dates of ' .
					 ((int) $estimated). ' unused redirections. ' .
					 "\n" .
					 'Your oldest redirection has an estimated creation date of ' . $oldest,
					true, // escape
					true // nl2br
				);
			}

			if ($failed > 0) {
				Migrator::message('failure',
				 	'We failed to estimate ' . $failed .
					' creation dates.',
					true, // escape
					false // nl2br
				);

				throw new Exception('Failures occurred');
			}
		}

		// reset to a sane value for remaining migrations
		set_time_limit(5 * 60);
	}

	function down()
	{
		// Can't.
	}
}
