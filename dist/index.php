<?php

include('-/config.php');
include('-/db.php');

$token = (isset($_GET['token']) ? $_GET['token'] : '');

$show_stats = (isset($_GET['stats']) OR strrpos($token, '/stats') !== false);
if (RECORD_URL_STATS OR $show_stats) {
	include('-/stats.php');
}


/*
*	CSF Pin code check before redirecting
*/
$code = (isset($_REQUEST['code']) ? $_REQUEST['code'] : '');
$pin_code = (isset($_COOKIE['pin_code']) ? $_COOKIE['pin_code'] : '');
$success = false;

// Check if pin code exists in Chamilo database and the course is not yet deleted and the teacher is still in the course
function check_code ($code) {
	global $db_chamilo;
	$stmt_pin_code = $db_chamilo->prepare('SELECT g.id FROM plugin_student_group g, course_rel_user cru WHERE '
	.(CHAMILO_DB_DRIVER === 'sqlite' ? '' : 'BINARY')
	.' g.id = :pin_code '
	.'AND '
	.(CHAMILO_DB_DRIVER === 'sqlite' ? '' : 'BINARY')
	.' g.teacher_id=cru.user_id '
	.'AND '
	.(CHAMILO_DB_DRIVER === 'sqlite' ? '' : 'BINARY')
	.' g.course_id=cru.c_id '
	.'AND '
	.(CHAMILO_DB_DRIVER === 'sqlite' ? '' : 'BINARY')
	.' g.group_name NOT LIKE "%DELETED%"');
	$stmt_pin_code->execute(array('pin_code'=>$code));
	return $stmt_pin_code->fetch(PDO::FETCH_ASSOC);
}


/*
*	DEVELOPERS:
*	Note the following possible redir_type values:
*	-	'auto' - Automatically assigned slug. 301 redirect on access.
*	-	'custom' - Manually set slug. 302 redirect on access.
*	-	'alias' - Its 'url' is really just another slug. Do a recursive lookup to redirect on access.
*	-	'gone' - Access results in a 410; should never change
*/

// Redirect lookup
while($token != '') // Loop so we can handle aliases
{
	// Look up slug, after removing mistaken URL additions
	$token = rtrim(urldecode($token), ')>]}.,-;!\'"');

	$stmt = $db->prepare('SELECT * FROM '.DB_PREFIX.'urls WHERE '
	.(DB_DRIVER === 'sqlite' ? '' : 'BINARY')
	.' custom_url = '
	.(DB_DRIVER === 'sqlite' ? '' : 'BINARY')
	.' :slug AND custom_url = :slug LIMIT 1');
	$stmt->execute(array('slug'=>$token));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);


	if ($stmt AND $row)
	{
		// CSF Mod
		// If link is protected by pin code from Cha
		if ($row['protected']) {
			while (!$success) {
				// If new pin code is given, check the code
				if ($code) {
					$row_pin_code = check_code($code);
					if ($row_pin_code) {
						setcookie('pin_code', $code, time() + (86400 * 60), '/'); // cookie expires after 60 days
						$pin_code = $code;
						$success = true;
					} else {
						// clear wrong code
						$code = '';
					}
				}
			
				// If code cookie is set
				else if ($pin_code) {
					$row_pin_code = check_code($pin_code);
					// If cookie is correct
					if ($row_pin_code) {
						$success = true;
					} else {
						// delete wrong cookie and clear pin_code
						setcookie('pin_code', '', time() - 3600);
						$pin_code = '';
					}
				} else {
					// Ask pin code and load page again
					echo('<script>'
					.'var response = prompt("Pin Code");'
					.'if (response == null) {' 
						.'window.location = "'.ERROR_404_URL.'";' //define error_404_url in config.php
					.'} else {'
						.'window.location = "'.$_SERVER['PATH_INFO'].'?code=" + response;'
					.'}'
					.'</script>');
					exit;
				}
			}
		}

		if(RECORD_URL_STATS)
			record_stats($db, $row['id'], $pin_code); // CSF logging system with pin code
			//record_stats($db, $row['id']);
		if($row['redir_type'] == 'gone') {
			header($_SERVER['SERVER_PROTOCOL'].' 410 Gone');
			die('The redirection in question no longer exists.');
		} elseif($row['redir_type'] == 'alias') {
			// Handle aliases, and watch out for infinite loops
			if($row['url'] != $token)
			{
				$token = $row['url'];
				continue;
			}
			else {
				// Incorrectly configured. "Should never happen"
				$token = '';
				break;
			}
		} else {
			// Handle standard redirections, both custom and auto-assigned

			//header($_SERVER['SERVER_PROTOCOL'].' 301 Moved Permanently');
			header($_SERVER['SERVER_PROTOCOL'].' 302 Found'); // CSF mod
			header('Location:'.$row['url']);
			exit;			
		}
		//Unreachable, thanks to "else"
	}
	else
	{
		// 404!
		// no redirect
		if (defined('ERROR_404_URL') && ERROR_404_URL !== NULL){
			header("Location: ".ERROR_404_URL);
			die;
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
			header('Status:404');
			die;
		}
	}
}

if(defined('HOMEPAGE_URL') && HOMEPAGE_URL)
	header("Location: ".HOMEPAGE_URL);
exit;
