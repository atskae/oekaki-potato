<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2017 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.5 - Last modified 2017-09-26
*/


/*
NOTES:

	When setting/unsetting cookies, we need to use an IIS-safe redirect, as IIS will not set cookies if an HTML 'Location:' directive is specified.

	Cookies are sent as the HTTP GET method, and thus don't [aren't supposed to] work with POST.  If not using w_gpc, be sure to test POST before using coookies, or your input may be empty!

*/


$no_template = true;
$no_online   = true;
define('BOOT', 1);
require 'boot.php';


// Input
// $pageno


// Some quick sanity checks.
if (strlen(w_gpc('comment')) > MAX_COMMENT_BYTES) {
	report_err('Your comment is too long.  Limit is '.strval(MAX_COMMENT_BYTES).' bytes.');
}
if (strlen(w_gpc('name')) > 100) {
	report_err('Your name is too long');
}
if (strlen(w_gpc('title')) > 100) {
	report_err('Your picture title is too long');
}


// Redirect with page number and other CGI
// This is sent as an HTTP header, so don't use HTML encoding
$pic_redirect = '';
if ($pageno) {
	// Always needs a pageno
	$pic_redirect = '?pageno='.$pageno;
	if ($sort) {
		$pic_redirect .= '&sort='.$sort;
	}
	if (!empty($artist)) {
		$pic_redirect .= '&artist='.urlencode($artist);
	}
	if (!empty($a_match)) {
		$pic_redirect .= '&a_match='.urlencode($a_match);
	}
}


// ==================================================================
// Functions specific to this file
//
function check_php_code($text) {
	global $dbconn, $flags;

	if ($flags['owner']) {
		return true;
	}

	if (defined('ENABLE_PHP_RESOURCE') && ENABLE_PHP_RESOURCE == 1) {
		if ($flags['sadmin']) {
			return true;
		}
	}

	if (strpos($text, '<?') !== false) {
		report_err('PHP code has been disabled in the notice, banner, and rules.');
	}
	return true;
}

function get_user_language($user) {
	global $cfg;
	global $dbconn, $db_mem, $db_p;
	global $language;

	$user = db_escape($user);
	$result = db_query("SELECT language FROM {$db_mem}oekaki WHERE usrname='{$user}'");
	if ($result) {
		$my_lang = db_result($result);
		if ($my_lang != $language && file_exists('language/'.$my_lang.'.php')) {
			return $my_lang;
		}
	}
	return false;
}


// ==================================================================
// Process login
//

// Login (from header.php)
if ($action == 'login') {
	$username = w_gpc('username');
	$pass     = w_gpc('pass');

	$result = db_query("SELECT ID, usrname, usrpass, usrflags FROM {$db_mem}oekaki WHERE usrname='$username'");

	if ($result) {
		if (db_num_rows($result) < 1) {
			report_err( t('functions_err4'));
		}

		$row = db_fetch_array($result);

		// Check if passwords are correct and that the user is a non-pending member
		if (w_password_verify($pass, $row['usrpass']) && !strstr($row['usrflags'], 'P')) {
			w_set_cookie('OekakiID', $row['ID']);
			w_set_cookie('OekakiPass', $row['usrpass']);
			w_unset_cookie('OekakiLang');

			// Set IP address
			$result = db_query("UPDATE `{$db_mem}oekaki` SET `IP`='{$address}' WHERE `usrname`='$username'");

			// LEGACY (2015-02-17): Update weak password hash with newer one
			// LEGACY (2011-03-27): Update broken UTF8 password if detected
			if (
				(PASSWORD_STRENGTH > 0 && strlen($row['usrpass']) == 13)
				||
				(PASSWORD_STRENGTH == 0 && w_test_broken_utf_hash($pass, $row['usrpass']))
			) {
				$new_pass = w_password_hash($pass);
				$result = db_query("UPDATE `{$db_mem}oekaki` SET `usrpass`='".db_escape($new_pass)."' WHERE `usrname`='$username'");
				if ($result) {
					$row['usrpass'] = $new_pass;
				}
			}

			// IIS-safe redirect
			all_done('index.php;in');
		} else {
			report_err( t('functions_err13'));
		}
	} else {
		html_exit('Login failed! If this message persists, contact the admin and/or verify database installation.');
	}
}

// Logoff (from header.php)
if ($mode == 'logoff') {
	// Kill all cookies
	w_unset_cookie('OekakiID');
	w_unset_cookie('OekakiPass');

	// Take user off online list
	$online = db_query("DELETE FROM {$db_mem}oekakionline WHERE onlineusr='$OekakiU'");

	// IIS-safe redirect
	all_done('index.php;out');
}



// ==================================================================
// Begin functions.php
//

// Mass OPMail
if ($action == 'massmail') {
	$send_to = w_gpc('send_to');
	$body    = w_gpc('body');
	$subject = w_gpc('subject');

	if ($flags['owner']) {
		// To whom do we send the messages?
		$sql_where = "WHERE usrflags != 'P'";

		// If we narrow the mass mail to a certain group, we must also update the log
		if ($send_to != 'G') {
			$send_log = $send_to;

			if ($send_to == 'A') {
				$sql_where = "WHERE rank >= ".RANK_MOD;
				$send_log = 'type_admin';
			} elseif ($send_to == 'S') {
				$sql_where = "WHERE rank >= ".RANK_SADMIN;
				$send_log = 'type_sadmin';
			} elseif ($send_to == 'Active') {
				// Active members
				$sql_where = "WHERE (DATE_ADD(lastlogin, INTERVAL ".ACTIVE_LOGIN_TIME_DAYS." DAY) > NOW())";
				$send_log = 'type_active';
			} else {
				$sql_where = "WHERE usrflags LIKE '%{$send_to}%'";
			}

			w_log(WLOG_MASS_MAIL, log_esc($subject), $send_log);
		}

		if (!empty($send_to) && !empty($OekakiU)) {
			if (empty($subject)) {
				report_err( t('functions_err1'));
			}
			$result = db_query("SELECT usrname FROM {$db_mem}oekaki ".$sql_where);
			if ($result) {
				while ($row = db_fetch_array($result)) {
					$result3 = db_query("INSERT INTO {$db_mem}oekakimailbox SET sender='{$OekakiU}', reciever='".db_escape($row['usrname'])."', subject='{$subject}', body='{$body}', senddate=NOW()");
				}
			}
		}
		all_done('mailbox.php');
	} else {
		report_err( t('functions_err2'));
	}
}



// Lost Password Send
if ($action == 'pretrieve') {
	$username = w_gpc('username');

	$result = db_query("SELECT usrpass, email FROM {$db_mem}oekaki WHERE usrname='{$username}'");
	$numrows = db_num_rows($result);

	if ($numrows != 0) {
		$row = db_fetch_array($result);

		// Get user language
		$my_lang = get_user_language($username);
		if ($my_lang !== false) {
			include 'language/'.$my_lang.'.php';
		}

		$mail_subject = t('precover_title', $cfg['op_title']);
		// 1username, 2title, 3URL, 4script, 6IP
		$mail = t(
					// Position #5 must be blank for IP to display correctly
					'pw_recover_email_message',
					$username,
					$cfg['op_title'],
					$cfg['op_url'],
					$cfg['op_url'].'/chngpass.php?vcode='.urlencode($row['usrpass']).'&username='.urlencode($username),
					'',
					$address
				);

		// Get $OekakiID language
		if ($my_lang != $language) {
			include 'language/'.$language.'.php';
		}

		w_log(WLOG_PASS_RECOVER, '$l_sent_to`'.log_esc($row['email']).'`', $username);

		if ($cfg['cut_email'] < 2) {
			$mail_sent = w_mail($row['email'], $mail_subject, $mail);
		}

		if (!$mail_sent) {
			report_err( t('no_email_pass_reset'));
		}
		all_done();
	} else {
		report_err( t('functions_err4'));
	}
}



// Password Reset
if ($action == 'pass_reset') {
	$usrname2  = w_gpc('usrname2');
	$new_pass  = trim(w_gpc('new_pass'));
	$new_pass2 = trim(w_gpc('new_pass2'));

	if ($flags['owner']) {
		if (empty($new_pass) || empty($usrname2)) {
			report_err( t('pass_ver_failed'));
		}
		if ($new_pass != $new_pass2) {
			report_err( t('pass_ver_failed'));
		}
		if ($bad = badChars(stripslashes($new_pass))) {
			report_err( t('pass_invalid_chars', make_list($bad, ', ', '"')) );
		}

		// I am the Crypt Keyper!
		$crypt_pass = w_password_hash($new_pass);
		$result = db_query("UPDATE {$db_mem}oekaki SET usrpass='$crypt_pass' WHERE usrname='$usrname2'");

		if (!$result) {
			report_err( t('db_err'));
		}

		w_log(WLOG_PASS_RESET, '$l_reset_admin', $usrname2);
	} else {
		report_err( t('retpwderr'));
	}

	all_done();
}



// Lost Password Change
if ($action == 'pchange') {
	$username = w_gpc('username');
	$vcode    = w_gpc('vcode');
	$newpass  = w_gpc('newpass');
	$retype   = w_gpc('retype');

	$result = db_query("SELECT * FROM {$db_mem}oekaki WHERE usrname='$username'");
	$numrows = db_num_rows($result);

	if ($numrows != 0) {
		$row = db_fetch_array($result);

		// Compare the existing password against the database
		if ($row['usrpass'] == $vcode) {
			$test_pass = trim($newpass);
			if (empty($test_pass)) {
				report_err( t('pass_emtpy') );
			}

			// Compare the new password with the old
			if ($newpass == $retype) {
				if ($bad = badChars(stripslashes($newpass))) {
					report_err( t('pass_invalid_chars', make_list($bad, ', ', '"')) );
				}

				// Change the password
				$newpass = w_password_hash($newpass);
				$result = db_query("UPDATE {$db_mem}oekaki SET usrpass='$newpass' WHERE usrname='$username'");

				if (!empty($OekakiU)) {
					// Logout
					w_unset_cookie('OekakiID');
					w_unset_cookie('OekakiPass');

					// Take user off online list
					$online = db_query("DELETE FROM {$db_mem}oekakionline WHERE onlineusr='$OekakiU'");
				}

				w_log(WLOG_PASS_RESET, '$l_reset_mem', $username);

				// IIS-safe redirect
				all_done('index.php;out');
			} else {
				report_err( t('functions_err5'));
			}
		} else {
			report_err( t('functions_err6'));
		}
	} else {
		report_err( t('functions_err4'));
	}
}



// Mailbox Send
if ($action == 'mailsend') {
	$body     = w_gpc('body');
	$subject  = w_gpc('subject');
	$reciever = w_gpc('reciever');
	$MID      = w_gpc('MID', 'i');
	$reply    = w_gpc('reply', 'i');

	if (!empty($OekakiU) && $flags['G']) {
		if (empty($subject)) {
			report_err( t('functions_err1'));
		}

		$result = db_query("SELECT usrname FROM {$db_mem}oekaki WHERE usrname='{$reciever}'");
		$numrows = db_num_rows($result);

		if ($numrows != 0) {
			$row = db_fetch_array($result);

			if ($reply) {
				$result = db_query("UPDATE {$db_mem}oekakimailbox SET mstatus=".MAIL_REPLIED." WHERE MID='{$MID}'");
			}
			$result = db_query("INSERT INTO {$db_mem}oekakimailbox SET sender='{$OekakiU}', reciever='".db_escape($row['usrname'])."', subject='{$subject}', body='{$body}', senddate=NOW()");

			all_done('mailbox.php');
		} else {
			report_err( t('functions_err9'));
		}
	} else {
		report_err( t('functions_err10'));
	}
}



// Mailbox Check Delete
if ($action == 'mail_check_delete') {
	if (isset($_POST['MD'])) {
		foreach ($_POST['MD'] as $mid) {
			$del = (int) $mid;

			$result = db_query("DELETE FROM {$db_mem}oekakimailbox WHERE MID='{$del}' AND reciever='{$OekakiU}'");
		}
	}

	all_done('mailbox.php');
}



// Mailbox Delete
// (mailread.php)
if ($mode == 'maildelete') {
	$MID = w_gpc('MID', 'i');

	$result = db_query("SELECT * FROM {$db_mem}oekakimailbox WHERE MID='{$MID}'");
	$row = db_fetch_array($result);

	if ($row['reciever'] != $OekakiU) {
		report_err( t('functions_err11'));
	} else {
		$result = db_query("DELETE FROM {$db_mem}oekakimailbox WHERE MID='{$MID}'");
		all_done('mailbox.php');
	}
}



// User delete
if ($action == 'deluser') {
	$usrname2  = w_gpc('usrname2', 'i'); // ID, not name!
	$confirm   = w_gpc('confirm', 'i');
	$no_reason = w_gpc('no_reason', 'i');
	$reason    = w_gpc('reason');

	if ($flags['sadmin']) {
		// Get e-mail of admin that deleted user
		$result2 = db_query("SELECT email FROM {$db_mem}oekaki WHERE usrname='$OekakiU'");
		$row = db_fetch_array($result2);

		if ($confirm) {
			// Pic delete routine
			include 'paintsave.php';

			// Initiate delete
			$result = db_query("SELECT ID, usrname, email, avatar FROM {$db_mem}oekaki WHERE ID='{$usrname2}'");
			$row2 = db_fetch_array($result);
			$row2['usrname'] = db_escape($row2['usrname']);

			// Remove images
			$result = db_query("SELECT PIC_ID FROM {$db_p}oekakidta WHERE usrname='{$row2['usrname']}'");
			while ($result && ($kill_me = db_fetch_array($result))) {
				kill_picture_slot($kill_me['PIC_ID']);
			}

			// Remove avatar
			if (!empty($row2['avatar'])) {
				@unlink($cfg['avatar_folder'].'/'.$row2['avatar']);
			}

			// Goodbye
			$result = db_query("DELETE FROM {$db_mem}oekakionline WHERE onlineusr='{$row2['usrname']}'");
			$result = db_query("DELETE FROM {$db_p}oekakidta WHERE usrname='{$row2['usrname']}'");
			$result = db_query("DELETE FROM {$db_p}oekakicmt WHERE usrname='{$row2['usrname']}'");
			$result = db_query("DELETE FROM {$db_mem}oekakichat WHERE usrname='{$row2['usrname']}'");
			$result = db_query("DELETE FROM {$db_mem}oekakimailbox WHERE reciever='{$row2['usrname']}'");
			$result = db_query("DELETE FROM {$db_mem}oekaki WHERE ID='{$row2['ID']}'");

			$reason ? $log_reason = '$l_reason_yes': $log_reason = '$l_reason_no';
			w_log(WLOG_DELETE_USER, $log_reason, stripslashes($row2['usrname']));

			// Get user language
			$my_lang = get_user_language(stripslashes($row2['usrname']));
			if ($my_lang !== false) {
				include 'language/'.$my_lang.'.php';
			}

			// Send e-mail
			if (!$no_reason) {
				$mail_subject = t('mandel_title', $cfg['op_title']);
				// 1user, 2title, 3URL, 4admin, 5email, 6reason
				$mail = t(
							'act_delete_email_message',
							$row2['usrname'],
							$cfg['op_title'],
							$cfg['op_url'],
							$OekakiU,
							$row['email'],
							$reason
						);
				if ($cfg['cut_email'] < 2) {
					$mail_sent = w_mail($row2['email'], $mail_subject, $mail);
				}

				// Get $OekakiID language
				if ($my_lang != $language) {
					include 'language/'.$language.'.php';
				}

				if (!$mail_sent) {
					report_err( t('no_email_kill_notify', '[email]'.$row2['email'].'[/email]'));
				}
			}
		}
	} else {
		report_err( t('functions_err12'));
	}
	all_done('delusr.php');
}



// Registration Verification
if ($mode == 'Verify') {
	$username = w_gpc('username');
	$vcode    = w_gpc('vcode');

	if ($cfg['approval'] == 'yes') {
		all_done();
	}

	// Get user info
	$result = db_query("SELECT ID, usrname, usrpass, usrflags, email FROM {$db_mem}oekaki WHERE usrname='{$username}'");
	$row = db_fetch_array($result);

	if ($vcode == $row['usrpass'] && $row['usrflags'] == 'P') {
		// Get user language
		$my_lang = get_user_language($username);
		if ($my_lang !== false) {
			include 'language/'.$my_lang.'.php';
		}

		$theflags = 'G';
		$permissions = t('type_guser')."\n";
	
		if ($cfg['reg_draw'] == 'yes') {
			$theflags .= 'D';
			$permissions .= t('type_daccess')."\n";
		}
		if ($cfg['reg_anim'] == 'yes') {
			$theflags .= 'M';
			$permissions .= t('type_aaccess')."\n";
		}
		if ($cfg['reg_upload'] == 'yes') {
			$theflags .= 'U';
			$permissions .= t('type_uaccess')."\n";
		}

		// Insert user with automatic acceptance and draw permissions
		$result = db_query("UPDATE {$db_mem}oekaki SET usrflags='{$theflags}' WHERE usrname='{$username}'");

		// Set cookies
		w_set_cookie('OekakiID', $row['ID']);
		w_set_cookie('OekakiPass', $row['usrpass']);

		// Send e-mail
		$mail_subject = t('autoreg_title', $cfg['op_title']);
		// 1user, 2title, 3URL, 4perms
		$mail = t(
					'auto_accept_email_message',
					$row['usrname'],
					$cfg['op_title'],
					$cfg['op_url'],
					$permissions
				);
		if ($cfg['cut_email'] < 2) {
			$mail_sent = w_mail($row['email'], $mail_subject, $mail);
		}

		// Get $OekakiID language
		//if ($my_lang != $language) {
		//	include 'language/'.$language.'.php';
		//}

		// IIS-safe redirect
		all_done('editprofile.php;in');
	} else {
		report_err( t('functions_err14'));
	}
}



// Registration (from register.php)
if ($action == 'register') {
	$username = w_gpc('username');
	$email    = w_gpc('email', 'email');
	$age      = decode_birthday($_POST['age_year'], $_POST['age_month'], $_POST['age_day']);
	$pass     = w_gpc('pass');
	$pass2    = w_gpc('pass2');
	$artURL   = w_gpc('artURL', 'url');
	$comment  = w_gpc('comments');
	$r_language = w_gpc('r_language');

	if (empty($username)) {
		all_done();
	}

	if ($email === null || strlen($email) < 3) {
		report_err( t('email_req_err'));
	}

	// Humanity test
	$x_test = w_gpc('x_test', 'i');
	$y_test = w_gpc('y_test', 'i');
	$e_test = w_gpc('e_test', 'i');
	if ($x_test + $y_test != $e_test || $e_test < 2) {
		tt('humanity_test_failed');
		w_exit();
	}

	// Age verification?
	if ($cfg['op_adult'] == 'yes') {
		if (intval($_POST['age_year']) < 1900 || intval($_POST['age_year']) > 3000) {
			report_err( t('submit_valid_age'));
		}

		if (get_age($age) < MIN_AGE_ADULT) {
			report_err( t('age_not_accepted'));
		}
	}

	// Prevent database from vomiting
	if ($bad = badChars(stripslashes($username))) {
		report_err( t('name_invalid_chars', make_list($bad, ', ', '"')) );
	}
	$trimpass = trim($pass);
	if (empty($trimpass)) {
		report_err( t('pass_emtpy'));
	}
	if ($bad = badChars(stripslashes($pass))) {
		report_err( t('pass_invalid_chars', make_list($bad, ', ', '"')) );
	}
	if ($cfg['require_art'] == 'yes') {
		if (empty($artURL) || strlen($artURL) < 8 || strstr($artURL, '&lt;')) {
			// ('<' filters out IMG tags, etc)
			report_err( t('valid_url_req'));
		}
	}
	if ($cfg['op_adult'] == 'yes' && empty($age)) {
		report_err( t('must_declare_age'));
	}
	if (strlen($r_language) > 50) {
		$r_language = '';
	}

	// Check if a member already exists
	$result = db_query("SELECT usrname, email FROM {$db_mem}oekaki WHERE usrname='$username'");
	if ($result && db_num_rows($result) > 0) {
		report_err('Member name already exists!');
	}

	// Check for duplicate e-mail
	//$result = db_query("SELECT usrname, email FROM {$db_mem}oekaki WHERE email='$email'");
	//$row = db_fetch_array($result);
	//if ($username == $row['usrname']) {
	//	report_err( t('functions_err15'));
	//}


	// Check if the passwords match
	if ($pass == $pass2) {
		$userpass1 = w_password_hash($pass);

		// Add the user as pending
		$result = db_query("INSERT INTO {$db_mem}oekaki SET usrname='{$username}', email='{$email}', usrpass='{$userpass1}', age='{$age}', usrflags='P', comment='{$comment}', url='{$artURL}', joindate=NOW(), language='{$r_language}', templatesel='', avatar='', IP='{$address}', lastlogin=NOW()");

		$mail_sent = false;
		if ($cfg['approval'] == 'yes' && $cfg['cut_email'] < 2) {
			// Send an email to the admin for review
			$mail_subject = t('reg_rev_title', $cfg['op_title']);
			// 1user, 2title, 3script
			$mail = t(
						'email_new_user_application',
						$username,
						$cfg['op_title'],
						$cfg['op_url'].'/addusr.php'
					);
			if ($cfg['cut_email'] < 2) {
				w_mail($cfg['op_email'], $mail_subject, $mail);
			}

			all_done('register.php?rules=2');
		}

		if ($cfg['approval'] == 'no') {
			// Send an email for verification
			$mail_subject = t('verreg_title', $cfg['op_title']);
			// 1user, 2title, 3URL, 4script
			$mail = t(
						'ver_email_message',
						$username,
						$cfg['op_title'],
						$cfg['op_url'],
						$cfg['op_url'].'/functions.php?mode=Verify&vcode='.urlencode($userpass1).'&username='.urlencode($username)
					);
			if ($cfg['cut_email'] < 2) {
				$mail_sent = w_mail($email, $mail_subject, $mail);
			}
		}


		if (!$mail_sent) {
			if ($cfg['approval'] == 'force') {
				// Auto-approve
				$theflags = 'G';
				if ($cfg['reg_draw'] == 'yes')
					$theflags .= 'D';
				if ($cfg['reg_anim'] == 'yes')
					$theflags .= 'M';
				if ($cfg['reg_upload'] == 'yes')
					$theflags .= 'U';

				$result = db_query("UPDATE {$db_mem}oekaki SET usrflags='{$theflags}' WHERE usrname='{$username}'");

				// Registered automatically, so get UID and set cookies
				$result = db_query("SELECT ID FROM {$db_mem}oekaki WHERE usrname='{$username}'");
				if ($uid = db_result($result)) {
					w_set_cookie('OekakiID', $uid);
					w_set_cookie('OekakiPass', $userpass1);
				}

				all_done('register.php?rules=2;regin');
			} else {
				// D'OH!
				report_err( t('email_wait_approval'));
			}
		}
	} else {
		report_err( t('functions_err5'));
	}

	all_done('register.php?rules=2');
}



// User Flag Modification (from modflags.php)
if ($action == 'modflags') {
	$usrname2 = w_gpc('usrname2');
	$new_rank = w_gpc('rank', 'i');

	if (!$flags['admin']) {
		report_err( t('func_no_flag_access'));
	}

	$result = db_query("SELECT usrname, usrflags, usrpass, rank FROM {$db_mem}oekaki WHERE usrname='$usrname2'");
	$their_info = db_fetch_array($result);


	if (!$flags['owner']) {
		if ($OekakiU != $usrname2 && $their_info['rank'] >= $flags['rank']) {
			// Can't change rank of equals or superiors
			report_err( t('err_modflags'));
		}

		if ($new_rank >= $flags['rank']) {
			// Don't let admins upgrade their own rank, or promote others to equal rank
			$new_rank = $their_info['rank'];
		}
	} elseif ($OekakiU == $usrname2) {
		// Don't let owners lose own rank
		// NOTE: owners may change other owners' ranks, for maintenance reasons
		$new_rank = $their_info['rank'];
	}

	// Admin flag overrides
	if ($new_rank >= RANK_ADMIN) {
		$_POST['upload_user'] = 'U';
		$_POST['immunity'] = 'I';
	}

	$new_flags =
		w_gpc('general').
		w_gpc('draw_user').
		w_gpc('anim_user').
		w_gpc('upload_user').
		w_gpc('adult_user').
		w_gpc('immunity');

	// Update DB
	$result = db_query("UPDATE {$db_mem}oekaki SET usrflags='$new_flags' WHERE usrname='$usrname2'");
	if (!$result) {
		report_err( t('db_err'));
	}

	if ($new_rank != $their_info['rank']) {
		$result2 = db_query("UPDATE {$db_mem}oekaki SET rank='$new_rank' WHERE usrname='$usrname2'");
		if (!$result2) {
			report_err( t('db_err'));
		}
	}

	all_done('modflags.php');
}



// Add/Remove User (from addusr.php)
if ($action == 'adduser') {
	$usrname2      = w_gpc('usrname2');
	$draw_access   = w_gpc('reg_draw', 'i');
	$anim_access   = w_gpc('reg_anim', 'i');
	$upload_access = w_gpc('reg_upload', 'i');
	$accept        = w_gpc('accept', 'i');
	$reason        = w_gpc('reason');

	// Admins only
	if (!$flags['admin']) {
		report_err( t('functions_err17'));
	}

	// Get admin e-mail
	$result = db_query("SELECT email FROM {$db_mem}oekaki WHERE usrname='{$OekakiU}'");
	$adminEmail = db_result($result, 0);

	// Get user e-mail
	$result = db_query("SELECT email FROM {$db_mem}oekaki WHERE usrname='{$usrname2}'");
	$their_email = db_result($result, 0);

	if ($accept) {
		w_log(WLOG_REG, '$l_accept_f`'.$theflags.'`', $usrname2);

		// Get user language
		$my_lang = get_user_language($usrname2);
		if ($my_lang !== false) {
			include 'language/'.$my_lang.'.php';
		}

		$theflags = 'G';
		$permissions = t('type_guser')."\n";
	
		if ($draw_access == 1) {
			$theflags .= 'D';
			$permissions .= t('common_drawacc')."\n";
		}
		if ($anim_access == 1) {
			$theflags .= 'M';
			$permissions .= t('common_aniacc')."\n";
		}
		if ($upload_access == 1) {
			$theflags .= 'U';
			$permissions .= t('type_uaccess')."\n";
		}

		$result2 = db_query("UPDATE {$db_mem}oekaki SET usrflags='{$theflags}' WHERE usrname='{$usrname2}'");

		if (!empty($their_email) && strpos($their_email, '@')) {
			// Send e-mail
			$mail_subject = t('autoreg_title', $cfg['op_title']);
			// 1user, 2title, 3URL, 4admin, 5email, 6comments, 7perms
			$mail = t(
						'admin_accept_email_message',
						$usrname2,
						$cfg['op_title'],
						$cfg['op_url'],
						$OekakiU,
						$adminEmail,
						$reason,
						$permissions
					);
			$mail_sent = false;
			if ($cfg['cut_email'] < 2) {
				$mail_sent = w_mail($their_email, $mail_subject, $mail);

				if (!$mail_sent) {
					// Get $OekakiID language
					if ($my_lang != $language) {
						include 'language/'.$language.'.php';
					}

					report_err( t('func_update_no_mail'));
				}
			}

			// Get $OekakiID language
			//if ($my_lang != $language) {
			//	include 'language/'.$language.'.php';
			//}

			all_done('addusr.php');
		} else {
			if (!$mail_sent) {
				report_err( t('func_update_no_mail'));
			}
		}
	} else {
		$result2 = db_query("DELETE FROM {$db_mem}oekaki WHERE usrname='$usrname2'");

		if (!empty($their_email) && strpos($their_email, '@')) {
			// Get user language
			$my_lang = get_user_language($usrname2);
			if ($my_lang !== false) {
				include 'language/'.$my_lang.'.php';
			}

			// Send e-mail
			$mail_subject = t('autoreg_title', $cfg['op_title']);
			// 1user, 2title, 3URL, 4admin, 5email, 6reason
			$mail = t(
						'reg_reject_email_message',
						$usrname2,
						$cfg['op_title'],
						$cfg['op_url'],
						$OekakiU,
						$adminEmail,
						$reason
					);
			$mail_sent = false;
			if ($cfg['cut_email'] < 2) {
				$mail_sent = w_mail($their_email, $mail_subject, $mail);
			}

			// Get $OekakiID language
			if ($my_lang != $language) {
				include 'language/'.$language.'.php';
			}

			if (!$mail_sent) {
				report_err( t('func_reject_no_mail', '[email]'.$their_email.'[/email]'));
			}

			all_done('addusr.php');
		} else {
			report_err( t('func_update_no_mail'));
		}
	}
}



// Profile Edit
if ($action == 'editprofile') {
	$name       = w_gpc('name');
	$email      = w_gpc('email', 'email');
	$email_show = w_gpc('email_show', 'i');
	$age        = decode_birthday($_POST['age_year'], $_POST['age_month'], $_POST['age_day']);
	$gender     = w_gpc('gender');
	$location   = w_gpc('location');
	$url        = w_gpc('url', 'url');
	$urltitle   = w_gpc('urltitle');
	$aim        = w_gpc('aim');
	$icq        = w_gpc('icq');
	$msn        = w_gpc('msn');
	$yahoo      = w_gpc('yahoo');
	$ircserver  = w_gpc('ircserver');
	$ircnick    = w_gpc('ircnick');
	$ircchan    = w_gpc('ircchan');
	$language2  = w_gpc('language2');
	$ctemplate  = w_gpc('ctemplate');
	$picview    = w_gpc('picview', 'i');
	$adult      = w_gpc('adult');
	$comment    = w_gpc('comment');
	$smilies_show = w_gpc('smilies_show', 'i');
	$no_viewer  = w_gpc('no_viewer', 'i');
	$username2  = w_gpc('username2');
	$oldpass    = w_gpc('oldpass');
	$passwd     = w_gpc('passwd');
	$passwdnew  = w_gpc('passwdnew');
	$thumbview  = w_gpc('thumbview', 'i');
	$screensize = w_gpc('screensize', 'i');

	// Admin change?
	$my_admin_flag = false;
	if ($OekakiU != $username2 && $flags['admin']) {
		$my_admin_flag = true;
	}
	// Edit which profile?
	$my_oekakiu = $OekakiU;
	if ($my_admin_flag) {
		$my_oekakiu = $username2; // Already slashed
	}


	// Test username
	$test = trim($username2);
	if (empty($test) || $OekakiU != $username2) {
		if (!$my_admin_flag) {
			// Profile mismatch
			all_done();
		}
	}

	// Test e-mail
	if ($email === null || strlen($email) < 3) {
		report_err( t('email_req_err'));
	}

	// Age verification?
	if (!$flags['owner'] && $cfg['op_adult'] == 'yes') {
		if (intval($_POST['age_year']) < 1900 || intval($_POST['age_year']) > 3000) {
			report_err( t('func_bad_age'));
		}

		if (get_age($age) < MIN_AGE_ADULT) {
			report_err( t('func_bad_age'));
		}
	}

	// Add adult flag
	{
		$my_flags = array();

		$result = db_query("SELECT usrflags, rank FROM {$db_mem}oekaki WHERE usrname='{$my_oekakiu}'");
		if ($result && db_num_rows($result)) {
			$row = db_fetch_array($result);
			$my_flags = parse_flags($row['usrflags'], $row['rank']);
		}

		$trueflags = str_replace('X', '', $my_flags['all']);
		if ($adult == 'X' && get_age($age) >= MIN_AGE_ADULT) {
			$trueflags .= 'X';
		}
	}

	// Censoring (prototype)
	if ($cfg['censoring'] == 'yes') {
		include_once 'censor.php';

		$comment = censor($comment);
	}

	// XSS filter (template and language)
	if ($bad = badChars($language2, 'file')) {
		$language2 = $cfg['language'];
	}
	if ($bad = badChars($ctemplate, 'file')) {
		$ctemplate = $cfg['template'];
	}

	// Add info to database
	$result = db_query("UPDATE {$db_mem}oekaki SET name='{$name}', url='{$url}', comment='{$comment}', email='{$email}', email_show={$email_show}, aim='{$aim}', icq='{$icq}', urltitle='{$urltitle}', MSN='{$msn}', yahoo='{$yahoo}', IRCserver='{$ircserver}', IRCnick='{$ircnick}', usrflags='{$trueflags}', location='{$location}', templatesel='{$ctemplate}', IRCchan='{$ircchan}', age='{$age}', gender='{$gender}', smilies_show={$smilies_show}, no_viewer={$no_viewer}, picview='{$picview}', thumbview='{$thumbview}', screensize='{$screensize}', language='{$language2}' WHERE usrname='{$my_oekakiu}'");

	// Update log.  Use $username2, as it is not slashed
	if ($my_admin_flag) {
		w_log(WLOG_EDIT_PROFILE, '$l_prof_up', $username2);
	}

	// Update passwords (do this last!)
	if (!empty($passwd) && !empty($passwdnew)) {
		$trim_passwd = trim($passwd);
		if (!empty($trim_passwd) && w_password_verify($oldpass, $OekakiPass)) {
			if ($passwd == $passwdnew) {
				$passenc = w_password_hash($passwd);
				$result = db_query("UPDATE {$db_mem}oekaki SET usrpass='{$passenc}' WHERE usrname='{$my_oekakiu}'");

				// Update log before $OekakiU is unset
				// Use $username2, as it is not slashed
				if ($my_admin_flag) {
					w_log(WLOG_PASS_RESET, '$l_reset_admin', $OekakiU);
				} else {
					w_log(WLOG_PASS_RESET, '$l_reset_mem', $username2);
				}

				// Logout
				if (!$my_admin_flag) {
					w_unset_cookie('OekakiID');
					w_unset_cookie('OekakiPass');
				}
			} else {
				report_err( t('functions_err5'));
			}
		}
	}

	all_done();
}



// Edit Ban List
if ($action == 'banedit') {
	$ipban   = trim(w_gpc('ipban', 'raw'));
	$hostban = trim(w_gpc('hostban', 'raw'));

	if ($flags['admin']) {
		$fail = 0;
		$hostfail = '';
		$ipfail = '';

		// Test if hosts are disabled.  If so, ignore.
		if (isset($_REQUEST['hostban'])) {
			if ($fp = @fopen($cfg['res_path'].'/hosts.txt', 'w')) {
				fwrite($fp, $hostban);
				fclose($fp);
			} else {
				$fail++;
				$hostfail = 'hosts.txt';
			}
		}

		// IPs
		if ($fp2 = @fopen($cfg['res_path'].'/ips.txt', 'w')) {
			fwrite($fp2, $ipban);
			fclose($fp2);
		} else {
			$fail++;
			$ipfail = 'ips.txt';
		}

		if ($fail == 1) {
			report_err('Could not write to '.$hostfail.$ipfail.' file');
		} elseif ($fail == 2) {
			report_err('Could not write to files: '.$hostfail.' and '.$ipfail);
		}

		w_log(WLOG_BAN, '$l_ban_up');
	}
	all_done();
}



// Edit notice and banner
if ($action == 'editnotice') {
	// NOTE: import as raw, so PHP/JS/other code isn't damaged.
	// Leave it to the oekaki owner to handle HTML encoding.
	$banneredit = trim(w_gpc('banneredit', 'raw'));
	$newsedit   = trim(w_gpc('newsedit', 'raw'));

	if ($flags['admin']) {
		if (!$flags['owner']) {
			check_php_code($newsedit);
			check_php_code($banneredit);
		}

		// Write banner
		$fp = @fopen($cfg['res_path'].'/banner.php', 'w');
		if ($fp) {
			fwrite($fp, $banneredit);
			fclose($fp);
		}

		// Write notice
		$fp2 = @fopen($cfg['res_path'].'/notice.php', 'w');
		if ($fp2) {
			fwrite($fp2, $newsedit);
			fclose($fp2);

			w_log(WLOG_NOTICE, '$l_banner_notice_up');
		}
	}
	all_done();
}



// Edit rules
if ($action == 'editrules') {
	// NOTE: import as raw, so PHP/JS/other code isn't damaged.
	// Leave it to the oekaki owner to handle HTML encoding.
	$rulesedit = trim(w_gpc('rulesedit', 'raw'));

	if ($flags['admin']) {
		if (!$flags['owner']) {
			check_php_code($rulesedit);
		}

		// Write rules
		$fp = @fopen($cfg['res_path'].'/rules.php', 'w');
		if ($fp) {
			if (!empty($rulesedit)) {
				fwrite($fp, $rulesedit);
			} else {
				// Get default language
				if ($language != $cfg['language']) {
					include 'language/'.$cfg['language'].'.php';
				}

				fwrite($fp, "<p>\n".t('defrulz')."\n</p>\n");

				// Restore $OekakiU language
				//if ($language != $cfg['language']) {
				//	include 'language/'.$language.'.php';
				//}
			}
			fclose($fp);

			w_log(WLOG_NOTICE, '$l_rules_up');
		}
	}
	all_done('showrules.php');
}



// Upload picture
if ($action == 'upload') {
	$title         = w_gpc('title');
	$comment       = w_gpc('comment');
	$time_invested = w_gpc('edittime');
	$datatype      = w_gpc('dtype', 'i');
	$adult         = w_gpc('adult', 'i');

	if ( !($flags['mod'] || $flags['U']) ) {
		report_err( t('err_upload'));
	}

	require 'paintsave.php';

	// Seconds hack
	if (strstr(strtolower($time_invested), 's')) {
		$time_invested = (int) $time_invested;
	} else {
		$time_invested = intval($time_invested * 60);
	}
	if ($time_invested < 1 || $time_invested > 1000000)
		$time_invested = 0;


	// Verify upload name
	if (!empty($_POST['usrname']) && $flags['mod']) {
		$upload_name = w_gpc('usrname');
	} else {
		$upload_name = $OekakiU;
	}

	// Censoring (prototype)
	if ($cfg['censoring'] == 'yes') {
		include_once 'censor.php';

		$comment = censor($comment);
	}


	// Size cap.  Just an anti-spammer cap for now
	if ($_FILES['picture']['size'] > $cfg['max_pic']) {
		report_err( t('err_imagelar_bytes', $cfg['max_pic']));
	}


	// Collect picture
	if ($_FILES['picture']['error'] > 0 || empty($_FILES['picture']['tmp_name'])) {
		if ($_FILES['picture']['error'] == UPLOAD_ERR_NO_FILE) {
			report_err( t('func_no_img_data'));
		}
		report_err( t('func_up_err'));
	}
	$buffer = file_get_contents($_FILES['picture']['tmp_name']);


	// Collect animation if it exists
	$have_animation = 0;
	if ($_FILES['animation']['tmp_name']) {
		if ($_FILES['animation']['error'] == 0) {
			$anim_ext = strtolower(substr($_FILES['animation']['name'], -3));
			if (($anim_ext == 'oeb' || $anim_ext == 'pch' || $anim_ext == 'chi') && ($_FILES['animation']['size'] <= $cfg['max_anim'])) {
				$buffer2 = file_get_contents($_FILES['animation']['tmp_name']);
				$have_animation = 1;
			} else {
				$have_animation = 0;
				$anim_fail = 1;
			}
		}
	}


	// Check filetype
	// We'll have to assume anim data is valid, since pch has no headers
	$type = get_filetype($buffer);

	if ($type == 'bmp') {
		report_err('Picture is a Microsoft BMP file, which is not supported.');
	} elseif ($type == 'jp2') {
		report_err('JPEG2000 is not yet supported, as no supported oekaki applets can read it.');
	} elseif ($type == 'webp') {
		report_err('WebP is not yet supported.');
	} elseif ($type == 'psd') {
		report_err('Picture is a Photoshop file, which is not supported.');
	} elseif ($type == 'zip') {
		report_err('Archives of pictures (ZIP/JAR files) are not supported.');
	} elseif ($type != 'png' && $type != 'jpg' && $type != 'gif') {
		report_err('Picture is an unsupported filetype.');
	}


	// Check dimentions for sanity
	$p_sizes = @GetImageSize($buffer);
	if ($p_sizes !== false) {
		if ($p_sizes[0] > 3300 || $p_sizes[1] > 3300) {
			report_err( t('err_imagelar', 3300, 3300));
		}
	}

	// Pic checks out, so reserve a slot
	$resno = get_new_picnumber();
	if ($resno == 0) {
		report_err(__FILE__.': '.t('f_no_picid'));
	}


	// Set uploaded flag
	$upload_clause = '';
	if (w_gpc('uploadby', 'i') || !$flags['mod']) {
		$upload_clause = ", editedby='{$OekakiU}', uploaded=1";
	}


	// Cleanup slots
	$result = clean_picture_slots();

	// Insert new slot
	$tries = 5;
	while ($tries > 0) {
		$result = db_query("INSERT INTO {$db_p}oekakidta SET usrname='{$upload_name}', postdate=NOW(), comment='$comment', hostname='{$hostname}', PIC_ID='{$resno}', IP='{$address}', title='{$title}', adult='{$adult}', datatype='{$datatype}', edittime='{$time_invested}', animation='{$have_animation}', postlock='1', password=''".$upload_clause);

		if ($result) {
			$tries = 0;
		} else {
			$tries--;
			sleep(1);
		}
	}
	if (!$result) {
		w_log_sql('$l_f_no_insert`'.$p_prefix.$resno.'.'.$type.'`');
		report_err( t('db_err'));
	}

	// Insert OK.  Add member piccount
	$result = db_query("UPDATE {$db_mem}oekaki SET piccount=(piccount + 1) WHERE usrname='{$OekakiU}'");


	// Write file(s)
	// Filetype is automatic!
	if (!save_image($buffer, $resno)) {
		w_log(WLOG_FAIL, '$l_f_no_save`'.$resno.'`');
	}
	if ($have_animation) {
		if (!save_anim($buffer2, $resno, $anim_ext)) {
			w_log(WLOG_FAIL, '$l_f_no_anim`'.$resno.'`');
		}
	}

	all_done();
}



// First post normal pic
if ($action == 'res_msg') {
	$picno         = w_gpc('picno', 'i+');
	$name          = w_gpc('name'); // Guest
	$email         = w_gpc('email', 'email'); // Guest
	$url           = w_gpc('url', 'url'); // Guest
	$adult         = w_gpc('adult', 'i');
	$title         = w_gpc('title');
	$comment       = w_gpc('comment');
	$share         = w_gpc('share', 'i');
	$share_pass    = trim(w_gpc('share_pass'));
	$old_pass_hash = trim(w_gpc('old_pass_hash'));
	$wip           = w_gpc('wip', 'i');

	// Must always have login and picno
	if (empty($OekakiU) || empty($picno)) {
		all_done();
	}

	// Censoring (prototype)
	if ($cfg['censoring'] == 'yes') {
		include_once 'censor.php';

		$comment = censor($comment);
	}

	$result = db_query("SELECT usrname, password, postlock FROM {$db_p}oekakidta WHERE PIC_ID=$picno");
	if (!$result) {
		report_err( t('db_err_pic_recovery', '[local=recover.php]', '[/local]') );
	}
	$old_pic_info = db_fetch_array($result);

	// Check all passwords
	if ($OekakiU == $old_pic_info['usrname'] || w_transmission_verify($old_pic_info['password'], $old_pass_hash)) {

		// Special case for public pictures.  Cannot edit other's public pictures
		if ($OekakiU != $old_pic_info['usrname'] && $old_pic_info['password'] == 'public') {
			report_err('Direct public retouch not allowed (possible bug!)');
		}

		// Check immunity flag for addition
		if ($cfg['draw_immune'] == 'yes' && !$flags['I']) {
			if ($OekakiU == $old_pic_info['usrname']) {
				// This needs to be as failsafe as possible
				$give_i = db_query("SELECT usrflags FROM {$db_mem}oekaki WHERE usrname='{$OekakiU}'");

				if ($give_i) {
					$temp_flags = db_result($give_i, 0);
					str_replace('I', '', $temp_flags);
					$temp_flags .= 'I';
					$give_i = db_query("UPDATE {$db_mem}oekaki SET usrflags='{$temp_flags}' WHERE usrname='{$OekakiU}'");
				}
			}
		}

		//
		// Share code
		// Sharing based exclusively on format of password
		switch ($share) {
			case 2:
				$share_pass = 'public';
				break;
			case 1:
				// Limit password to 12 chars
				// Safe clip, just in case autoslashes is on
				$share_pass = stripslashes($share_pass);
				$share_pass = db_escape(substr($share_pass, 0, 12));
				break;
			case 0:
				$share_pass = '';
		}
		if ($cfg['public_retouch'] != 'yes')
			$share_pass = '';

		//
		// WIP code.
		// Should pic be posted?
		//
		$wip ? $lock_stat = 0 : $lock_stat = 1;

		//
		// Bump code
		//
		$bump_me = ' '; // Use spacer!
		if ($old_pic_info['postlock'] == 0) {
			// WIP pictures should always be bumped on edit/post
			$bump_me .= ', postdate=NOW()';
		}
		if ($cfg['self_bump'] == 'yes' && w_gpc('bump', 'i')) {
			// Self-bump
			$bump_me .= ', postdate=NOW()';
			w_log(WLOG_BUMP, '$l_f_bump_retouch`'.$picno.'`');
		}

		//
		// Update DB
		$result = db_query("UPDATE {$db_p}oekakidta SET comment='$comment', hostname='$hostname', IP='$address', title='{$title}', adult='$adult', postlock='{$lock_stat}', password='{$share_pass}'{$bump_me} WHERE PIC_ID='{$picno}'");

		// If not a safety save, inc pic count
		if ($lock_stat) {
			$result = db_query("UPDATE {$db_mem}oekaki SET piccount=(piccount + 1) WHERE usrname='$OekakiU'");
		}

		// Update picture cache
		latest_refresh();

		// Report safety save
		if ($wip) {
			all_done('comment.php?wipok=1');
		} else {
			all_done();
		}
	} else {
		report_err( t('functions_err18'));
	}
}



// Comment Post
if ($action == 'add') {
	$picno   = w_gpc('picno', 'i+');
	$pageno  = w_gpc('pageno', 'i');
	$comment = w_gpc('comment');
	$uts     = w_gpc('uts', 'i');
	$name  = w_gpc('name');
	$email = w_gpc('email', 'email');
	$url   = w_gpc('url', 'url');

	// Check locked post
	if (!$flags['mod']) {
		$result = db_query("SELECT threadlock FROM {$db_p}oekakidta WHERE PIC_ID='$picno'");
		if ($result && db_num_rows($result) > 0) {
			if (db_result($result, 0) == 1) {
				report_err( t('no_post_locked'));
			}
		} else {
			header('HTTP/1.0 404 Not Found');
			header('Status: 404 Not Found');
			w_exit('Cannot find picture #'.$picno);
		}
	}


	// Guest post
	if (empty($OekakiU)) {
		if ($cfg['guest_post'] != 'yes')
			all_done();

		// Referral test
		$cur_time = time();
		if ($uts < $cur_time - 36000 || $uts > $cur_time + 3600) {
			// PHP needs HTTP header *and* status
			header('HTTP/1.0 404 Not Found');
			header('Status: 404 Not Found');
			w_exit();
		}

		// Humanity test
		if ($cfg['humanity_post'] != 'no') {
			if (isset($_COOKIE['guestPass']) && isset($_COOKIE['guestName'])
				&& w_transmission_verify($_COOKIE['guestName'], $_COOKIE['guestPass']))
			{
				// Cookie passed, skip test
			} else {
				$x_test = w_gpc('x_test', 'i');
				$y_test = w_gpc('y_test', 'i');
				$e_test = w_gpc('e_test', 'i');
				if ($x_test + $y_test != $e_test || $e_test < 2) {
					report_err( t('humanity_test_failed'));
				}
			}
		}

		// Run lame anti-spam test
		$comment2 = strtolower($comment);
		if (strpos($comment2, '&lt;a href=') !== false) {
			header('Content-Type: text/plain');
			tt('no_html_alt');
			w_exit();
		}

		// Count number of links (default is 2)
		$max_guest_links = 2;
		if (defined('MAX_GUEST_LINKS')) {
			$max_guest_links = MAX_GUEST_LINKS;
		}
		if ($max_guest_links < 1) {
			report_err( t('no_guest_links'));
		}
		$count = count(explode("://", $comment2));
		if ($count >= $max_guest_links * 2) {
			report_err( t('guest_num_links', $max_guest_links));
		}
		$count = count(explode("[url", $comment2));
		if ($count >= $max_guest_links * 2) {
			report_err( t('guest_num_links', $max_guest_links));
		}
	}


	// Censoring (prototype)
	if ($cfg['censoring'] == 'yes') {
		include_once 'censor.php';

		$comment = censor($comment);
	}

	// Add comment
	if (!empty($OekakiU)) {
		$result = db_query("INSERT INTO {$db_p}oekakicmt SET usrname='{$OekakiU}', comment='{$comment}', hostname='{$hostname}', postdate=NOW(), PIC_ID='{$picno}', IP='{$address}'");
		if ($result) {
			$result2 = db_query("UPDATE {$db_p}oekakidta SET lastcmt=NOW() WHERE PIC_ID='{$picno}'");
			$result = db_query("UPDATE {$db_mem}oekaki SET commcount=(commcount + 1) WHERE usrname='{$OekakiU}'");
		} else {
			report_err( t('db_err'));
		}
	} else {
		// Validate $name (used only for guest posts)
		if (empty($name)) {
			all_done('index.php'.$pic_redirect.'#pic'.$picno);
		}
		if ($bad = badChars(stripslashes($name))) {
			report_err( t('name_invalid_chars', make_list($bad, ', ', '"')) );
		}

		// Validate URL
		if (strlen($url) < 8) {
			$url = '';
		}

		$result = db_query("INSERT INTO {$db_p}oekakicmt SET usrname='Guest', postname='{$name}', comment='{$comment}', hostname='{$hostname}', email='{$email}', url='{$url}', postdate=NOW(), PIC_ID='{$picno}', IP='{$address}'");

		$human = w_transmission_hash($name);
		w_set_cookie('guestName',  $name);
		w_set_cookie('guestEmail', $email);
		w_set_cookie('guestURL',   $url);
		w_set_cookie('guestPass',  $human);

		$result2 = db_query("UPDATE {$db_p}oekakidta SET lastcmt=NOW() WHERE PIC_ID='$picno'");
	}

	all_done('index.php'.$pic_redirect.'#pic'.$picno);
}



// Delete Comment
if ($mode == 'udelcmt' || $mode == 'delcmt') {
	// Note: don't bother with commcount.  The count remains if comments are flushed.
	$cmtno = w_gpc('cmtno', 'i+');
	$page  = w_gpc('page', 'i+');
	$post  = w_gpc('post', 'i+');

	// Get poster
	$result = db_query("SELECT usrname FROM {$db_p}oekakicmt WHERE ID_3='{$cmtno}'");
	if ($result) {
		$name = db_result($result, 0);

		w_log(WLOG_DELETE, '$l_mod_comm`'.$cmtno.'`', $name);

		$extra = 'index.php';
		// Set redirect
		if ($page) {
			$extra .= "?pageno={$page}";
		}
		if ($post) {
			$extra .= '#pic'.$post;
		}

		// Admin only
		if ($name != $OekakiU) {
			if ($flags['mod']) {
				$result4 = db_query("DELETE FROM {$db_p}oekakicmt WHERE ID_3='{$cmtno}'");
				all_done($extra);
			} else {
				report_err( t('functions_err19'));
			}
		}

		// User
		if (!empty($OekakiU)) {
			$result4 = db_query("DELETE FROM {$db_p}oekakicmt WHERE ID_3='{$cmtno}'");
			all_done($extra);
		}
	}
}



// User Pic Delete
if ($action == 'del') {
	$picno   = w_gpc('picno', 'i+');
	$return  = w_gpc('return');
	$a_match = w_gpc('a_match');

	if ($return == 'a_match') {
		$returnpage = 'index.php?a_match='.urlencode($a_match);
	} elseif ($return == 'recover') {
		$returnpage = 'recover.php';
	} else {
		$returnpage = 'index.php';
	}

	$result = db_query("SELECT usrname, postlock FROM {$db_p}oekakidta WHERE PIC_ID='$picno'");
	$row = db_fetch_array($result);

	if ($row['usrname'] == $OekakiU || $flags['admin']) {
		include 'paintsave.php';

		// Is this the newest picture?
		$latest_picno = 0;
		$result3 = db_query("SELECT PIC_ID FROM {$db_p}oekakidta WHERE postlock=1 ORDER BY postdate DESC LIMIT 1");
		if ($result3) {
			$latest_picno = db_result($result3);
		}

		kill_picture_slot($picno);

		// Reduce user piccount if not a WIP
		if ($row['postlock'] > 0) {
			$result4 = db_query("UPDATE {$db_mem}oekaki SET piccount=(piccount - 1) WHERE usrname='".db_escape($row['usrname'])."'");
		}

		w_log(WLOG_DELETE, '$l_mod_pic`'.$picno.'`', $row['usrname']);

		if ($latest_picno == $picno) {
			// Update picture cache
			latest_refresh();
		}

		all_done($returnpage);
	} else {
		report_err( t('functions_err20'));
	}
}



// Admin Picture Delete (from delconf.php)
if ($action == 'dela') {
	$picno  = w_gpc('picno', 'i+');
	$reason = w_gpc('reason');
	$return = w_gpc('return');
	$givereason = w_gpc('givereason', 'i');

	if ($flags['admin']) {
		include 'paintsave.php';

		$result2 = db_query("SELECT usrname, ptype, postlock FROM {$db_p}oekakidta WHERE PIC_ID='$picno'");
		$row = db_fetch_array($result2);

		// Get user language
		$my_lang = get_user_language($row['usrname']);
		if ($my_lang !== false) {
			include 'language/'.$my_lang.'.php';
		}

		// Send reason?
		if ($givereason) {
			if (empty($reason)) {
				$reason = t('picdel_admin_noreason');
			}
			$reason2 = t(
				'picdel_admin_note',
				$cfg['op_url'].'/'.$p_pic.$picno.'.'.$row['ptype'],
				$OekakiU,
				$reason
			);

			$result = db_query("INSERT INTO {$db_mem}oekakimailbox SET sender='{$OekakiU}', reciever='{$row['usrname']}', subject='".t('picdel_title')."', body='{$reason2}', senddate=NOW()");
		}

		// Get $OekakiID language
		if ($my_lang != $language) {
			include 'language/'.$language.'.php';
		}

		// Is this the newest picture?
		$latest_picno = 0;
		$result3 = db_query("SELECT PIC_ID FROM {$db_p}oekakidta WHERE postlock=1 ORDER BY postdate DESC LIMIT 1");
		if ($result3) {
			$latest_picno = db_result($result3);
		}

		kill_picture_slot($picno);

		// Reduce user piccount if not a WIP
		if ($row['postlock'] > 0) {
			$result4 = db_query("UPDATE {$db_mem}oekaki SET piccount=(piccount - 1) WHERE usrname='".db_escape($row['usrname'])."'");
		}

		if ($latest_picno == $picno) {
			// Update picture cache
			latest_refresh();
		}

		w_log(WLOG_DELETE, '$l_mod_pic`'.$picno.'`', $row['usrname']);
	}

	if ($return == 'delpics') {
		all_done('delpics.php');
	}

	all_done();
}



// Recover Pic Delete
if ($action == 'delr') {
	$picno = w_gpc('picno', 'i+');

	$result = db_query("SELECT usrname FROM {$db_p}oekakidta WHERE PIC_ID='$picno'");
	if ($result) {
		$usrname = db_result($result, 0);

		if ($flags['admin'] || $OekakiU == $usrname) {
			require 'paintsave.php';
			kill_picture_slot($picno);

			// Note: do not reduce piccount for WIP

			// Log if admin edit
			if ($flags['admin'] && $OekakiU != $usrname) {
				w_log(WLOG_DELETE, '$l_mod_wip`'.$picno.'`', $usrname);
			}

			all_done('recover.php');
		} else {
			report_err( t('functions_err20'));
		}
	}
	all_done();
}



// Edit Comment
if ($action == 'editcomment') {
	$comment2 = w_gpc('comment2');
	$picno    = w_gpc('picno', 'i+');
	$idno     = w_gpc('idno', 'i+');
	$editon   = w_gpc('editon', 'i');

	// Censoring (prototype)
	if ($cfg['censoring'] == 'yes') {
		include_once 'censor.php';

		$comment2 = censor($comment2);
	}

	// Comment owner
	$result = db_query("SELECT usrname FROM {$db_p}oekakicmt WHERE ID_3='$idno'");
	if ($result) {
		$usrname = db_result($result, 0);

		if ($usrname != $OekakiU) {
			if ($flags['mod']) {
				if (!$flags['admin'] || ($flags['admin'] && $editon)) {
					$result2 = db_query("UPDATE {$db_p}oekakicmt SET edited=NOW(), editedby='$OekakiU' WHERE ID_3='{$idno}'");
				} else {
					$result2 = db_query("UPDATE {$db_p}oekakicmt SET edited=NULL, editedby=NULL WHERE ID_3='{$idno}'");
				}

				// Log mod edits
				w_log(WLOG_EDIT_COMM, '$l_mod_comm`'.$idno.'`', $usrname);
			} else {
				report_err( t('functions_err21'));
			}
		} elseif ($editon) {
			$result3 = db_query("UPDATE {$db_p}oekakicmt SET edited=NOW() WHERE ID_3='{$idno}'");
		} else {
			$result2 = db_query("UPDATE {$db_p}oekakicmt SET edited=NULL, editedby=NULL WHERE ID_3='{$idno}'");
		}
		$result4 = db_query("UPDATE {$db_p}oekakicmt SET comment='{$comment2}' WHERE ID_3='{$idno}'");

		all_done('index.php'.$pic_redirect.'#pic'.$picno);
	}
}



// Edit Main Picture Comment
if ($action == 'picedit') {
	$title2    = w_gpc('title2');
	$comment2  = w_gpc('comment2');
	$adult     = w_gpc('adult', 'i');
	$picno     = w_gpc('picno', 'i+');
	$adminedit = w_gpc('adminedit');
	$password  = trim(w_gpc('password'));
	$editon    = w_gpc('editon', 'i');

	if (!empty($password)) {
		// Limit password to 12 chars
		// Safe clip, just in case autoslashes is on
		$password = stripslashes($password);
		$password = db_escape(substr($password, 0, 12));
	}

	// Censoring (prototype)
	if ($cfg['censoring'] == 'yes') {
		include_once 'censor.php';

		$comment2 = censor($comment2);
	}

	$result = db_query("SELECT usrname FROM {$db_p}oekakidta WHERE PIC_ID={$picno}");
	if ($result) {
		$usrname = db_result($result, 0);

		if ($usrname != $OekakiU) {
			if ($flags['mod']) {
				if (!$flags['admin'] || ($flags['admin'] && $editon)) {
					$result2 = db_query("UPDATE {$db_p}oekakidta SET edited=NOW(), editedby='$OekakiU' WHERE PIC_ID={$picno}");
				} else {
					$result2 = db_query("UPDATE {$db_p}oekakidta SET edited=NULL, editedby=NULL WHERE PIC_ID={$picno}");
				}

				// Log mod edits
				w_log(WLOG_EDIT_PIC, '$l_mod_pic`'.$picno.'`', $usrname);
			} else {
				report_err( t('functions_err21'));
			}
		} elseif ($editon) {
			$result3 = db_query("UPDATE {$db_p}oekakidta SET edited=NOW() WHERE PIC_ID={$picno}");
		} else {
			$result3 = db_query("UPDATE {$db_p}oekakidta SET edited=NULL, editedby=NULL WHERE PIC_ID={$picno}");
		}
		$result4 = db_query("UPDATE {$db_p}oekakidta SET title='{$title2}', comment='{$comment2}', adult='{$adult}', postlock=1, password='{$password}' WHERE PIC_ID={$picno}");

		// Update picture cache
		latest_refresh();
	}

	all_done();
}



// Archive picture
if ($mode == 'archive') {
	$picid = w_gpc('picid', 'i');

	if ($flags['admin']) {
		// Get archive flag
		$result = db_query("SELECT archive, usrname FROM {$db_p}oekakidta WHERE PIC_ID=$picid");

		if ($result) {
			$row = db_fetch_array($result);

			// Toggle archive status
			$archive = (int) $row['archive'];
			$archive ? $archive = 0 : $archive = 1;

			$result = db_query("UPDATE {$db_p}oekakidta SET archive=$archive WHERE PIC_ID=$picid");
		}

		all_done('index.php'.$pic_redirect.'#pic'.$picid);
	} else {
		report_err( t('functions_err3'));
	}
}



// Lock thread
if ($mode == 'lock' || $mode == 'unlock') {
	$picno = w_gpc('picno', 'i+');

	if ($flags['mod']) {
		$result = db_query("SELECT threadlock, usrname FROM {$db_p}oekakidta WHERE PIC_ID='$picno'");

		if ($result) {
			$row = db_fetch_array($result);

			// Toggle lock status
			$lock = (int) $row['threadlock'];
			$lock ? $lock = 0 : $lock = 1;

			$result2 = db_query("UPDATE {$db_p}oekakidta SET threadlock='$lock' WHERE PIC_ID='$picno'");

			// Reversed already!
			$lock ? $l_status = '$l_f_lock' : $l_status = '$l_f_unlock';
			w_log(WLOG_LOCK_THREAD, "{$l_status}`{$picno}`", $row['usrname']);
		}

		all_done('index.php'.$pic_redirect.'#pic'.$picno);
	} else {
		report_err( t('functions_err22'));
	}
}



// Block adult thread
if ($mode == 'block' || $mode == 'unblock') {
	$picno = w_gpc('picno', 'i+');

	// Get pic info
	$result = db_query("SELECT usrname, adult FROM {$db_p}oekakidta WHERE PIC_ID='$picno'");
	$pic = db_fetch_array($result);

	if ($flags['mod'] || $pic['usrname'] == $OekakiU) {
		// Toggle adult status
		$block = (int) $pic['adult'];
		$block ? $block = 0 : $block = 1;

		$result2 = db_query("UPDATE {$db_p}oekakidta SET adult='$block' WHERE PIC_ID='$picno'");

		// It may be policy to adult pictures, so log all events
		// Reversed already!
		$block ? $b_status = '$l_f_adult' : $b_status = '$l_f_unadult';
		w_log(WLOG_ADULT, "{$b_status}`{$picno}`", $pic['usrname']);

		all_done('index.php'.$pic_redirect.'#pic'.$picno);
	} else {
		report_err( t('mod_change_adult'));
	}
}



// WIP picture
if ($mode == 'wip_pic') {
	$picno  = w_gpc('picno', 'i+');

	if ($flags['mod']) {
		$result = db_query("SELECT usrname, postlock FROM {$db_p}oekakidta WHERE PIC_ID='$picno'");

		if ($result) {
			$pic_info = db_fetch_array($result);

			// Get user language
			$my_lang = get_user_language($pic_info['usrname']);
			if ($my_lang !== false) {
				include 'language/'.$my_lang.'.php';
			}


			// Toggle WIP status, and bump date
			intval($pic_info['postlock']) ? $wip = 0 : $wip = 1;
			$result2 = db_query("UPDATE {$db_p}oekakidta SET postlock='$wip', postdate=NOW() WHERE PIC_ID='$picno'");

			// Decrease piccount
			$result2 = db_query("UPDATE {$db_mem}oekaki SET piccount=(piccount-1) WHERE usrname='{$pic_info['usrname']}'");

			// Send mailbox message
			$subject = t('to_wip_admin_title');
			$message = t('to_wip_admin_note', $OekakiU, $cfg['safety_storetime']);
			$result3 = db_query("INSERT INTO {$db_mem}oekakimailbox SET sender='$OekakiU', reciever='{$pic_info['usrname']}', subject='$subject', body='$message', senddate=NOW()");

			// Get $OekakiID language
			if ($my_lang != $language) {
				include 'language/'.$language.'.php';
			}

			if ($wip == 1) {
				// UnWIP
				w_log(WLOG_ADMIN_WIP, '$l_mod_pic`'.$picno.'`', $pic_info['usrname']);
			} else {
				w_log(WLOG_ADMIN_WIP, '$l_mod_wip`'.$picno.'`', $pic_info['usrname']);
			}

			// Update picture cache if this is the newest picture.
			$result3 = db_query("SELECT PIC_ID FROM {$db_p}oekakidta WHERE postlock=1 ORDER BY postdate DESC LIMIT 1");
			if ($result3) {
				if ($picno == db_result($result3)) {
					latest_refresh();
				}
			}
		}

		all_done('index.php'.$pic_redirect.'#pic'.$picno);
	} else {
		report_err( t('mod_change_wip'));
	}
}



// Picture Bump
if ($mode == 'bump') {
	$resno = w_gpc('resno', 'i+');

	include 'paintsave.php';

	if ($flags['mod']) {
		$status = bump_post($resno);
	}

	// Don't log or refresh latest pic cache here.  bump_post() does that.

	all_done();
}



/* Force Thumbnail
	$GET_['resno'] = slot
	$GET_['type'] = type (PNG, JPEG)
*/
if ($mode == 'make_thumb') {
	$picno = w_gpc('picno', 'i+');

	require 'paintsave.php';

	// Hack for NineChime
	$thumb_type = 'jpg';
	if (w_gpc('type') == 'png')
		$thumb_type = 'png';

	if ($flags['mod']) {
		// Get status.
		$result3 = db_query("SELECT usethumb, ptype, ttype, rtype FROM {$db_p}oekakidta WHERE PIC_ID='$picno'");
		$status = db_fetch_array($result3);

		// Purge old
		if (!empty($status['rtype'])) {
			$result3 = db_query("UPDATE {$db_p}oekakidta SET usethumb=0, rtype='' WHERE PIC_ID='$picno'");
			file_unlock($r_pic.$picno.'.'.$status['rtype']);
		}
		if (!empty($status['ttype'])) {
			$result3 = db_query("UPDATE {$db_p}oekakidta SET ttype='' WHERE PIC_ID='$picno'");
			file_unlock($t_pic.$picno.'.'.$status['ttype']);
		}

		// If "R" thumbnails didn't exist, make them
		if (empty($status['rtype'])) {
			save_thumbnail($picno, $status['ptype'], true, $thumb_type);
		}

		all_done('index.php'.$pic_redirect.'#pic'.$picno);
	} else {
		report_err( t('mod_only_func'));
	}
}



/*
	Uncaught condition

	Most of the time, this is due to the server's anti-spam or content filters flagging the CGI data and flushing it completely.  Very annoying, because there is no POST data to log -- not even a mode/action.
*/
html_exit( t('func_no_mode') );