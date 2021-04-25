<?php // Include only
if (!defined('BOOT')) exit('No bootstrap.  Exiting.');

/*
Wacintaki Poteto - Copyright 2012 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.12 - Last modified 2012-05-01

Todo:
	Scheduler, obviously
*/


// User killing
if ($cfg['kill_user'] > 0) {
	// Kill users if there is a result
	$usrkill = db_query("SELECT usrname, email, avatar, UNIX_TIMESTAMP(lastlogin) AS lastlogin FROM {$db_mem}oekaki WHERE (DATE_ADD(lastlogin, INTERVAL {$cfg['kill_user']} DAY) <= NOW()) AND (usrflags NOT LIKE '%I%') LIMIT 0, 3");

	while ($uk_row = db_fetch_array($usrkill)) {
		$usrdel = db_query("DELETE FROM {$db_mem}oekakionline WHERE onlineusr='{$uk_row['usrname']}'");

		// Archived?
		$kill_arc_check = db_query("SELECT COUNT(ID_2) FROM {$db_p}oekakidta WHERE usrname='{$uk_row['usrname']}' AND archive=1");

		if ($kill_arc_check && db_result($kill_arc_check) < 1) {
			// Not Archived.  Kill!
			if (! (defined('NO_KILL_ARTISTS') && NO_KILL_ARTISTS)) {
				include_once 'paintsave.php';

				// Remove images
				$result = db_query("SELECT PIC_ID FROM {$db_p}oekakidta WHERE usrname='{$uk_row['usrname']}'");
				while ($result && ($kill_me = db_fetch_array($result))) {
					kill_picture_slot($kill_me['PIC_ID']);
				}
				// Remove avatar
				if (!empty($uk_row['avatar'])) {
					@unlink($cfg['avatar_folder'].'/'.$uk_row['avatar']);
				}
			}
			$usrdel = db_query("DELETE FROM {$db_p}oekakicmt WHERE usrname='{$uk_row['usrname']}'");
			$usrdel = db_query("DELETE FROM {$db_mem}oekakichat WHERE usrname='{$uk_row['usrname']}'");
			$result = db_query("DELETE FROM {$db_mem}oekakimailbox WHERE reciever='{$uk_row['usrname']}'");

			// Prevent a deluge of e-mails on one day if user killing has just been turned on
			$my_days = ceil((time() - intval($uk_row['lastlogin'])) / 86400);

			if ($cfg['cut_email'] < 1 && $my_days < 365) {
				$mail_subject = $cfg['op_title'].' - '.t('kill_title');
				$mail = t('kill_email_message', $uk_row['usrname'], $cfg['op_title'], $cfg['kill_user'], $cfg['op_url']);

				w_mail($uk_row['email'], $mail_subject, $mail);
			}

			$usrdel = db_query("DELETE FROM {$db_mem}oekaki WHERE usrname='{$uk_row['usrname']}'");
		} else {
			// Give archived artist immunity
			$usrdel = db_query("SELECT usrflags FROM {$db_mem}oekaki WHERE usrname='{$uk_row['usrname']}'");

			if ($usrdel) {
				$temp_flags = db_result($usrdel);
				str_replace('I', '', $temp_flags);
				$temp_flags .= 'I';
				$usrdel = db_query("UPDATE {$db_mem}oekaki SET usrflags='{$temp_flags}' WHERE usrname='{$uk_row['usrname']}'");
			}
		}
	}
}


// Reg killing
if ($cfg['kill_reg'] > 0 && $cfg['approval'] == 'no') {
	// Kill users if there is a result
	$usrkill = db_query("SELECT ID, usrname, email FROM {$db_mem}oekaki WHERE (usrflags='P') AND (DATE_ADD(joindate, INTERVAL {$cfg['kill_reg']} DAY) <= NOW()) LIMIT 0, 3");

	while ($uk_row = db_fetch_array($usrkill)) {
		$mail_subject = $cfg['op_title'].' - '.t('regexpir');
		$mail = t('reg_expire_email_message', $uk_row['usrname'], $cfg['op_title'], $cfg['kill_reg'], $cfg['op_url']);

		$usrdel = db_query("DELETE FROM {$db_mem}oekaki WHERE ID='{$uk_row['ID']}'");

		if ($usrdel && $cfg['cut_email'] < 1) {
			w_mail($uk_row['email'], $mail_subject, $mail);
		}
	}
}


// Safety Save cleanup
if ($cfg['safety_saves'] == 'yes') {
	clean_safety_saves();
}


// Cache management
// trim_cache();