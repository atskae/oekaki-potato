<?php
/*
Animation code by Marcello (http://www.cellosoft.com). Modified by RanmaGuy. Used with permission. This file is not to be used with any other script other than OekakiPoteto.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.2 - Last modified 2015-04-26
*/


/*
NOTES:

Cookies don't [aren't supposed to] work with POST, so we get $OekakiU from the applet and load boot.php later.  All variables end up in keyed array $params.  We process that first before adding the bootstrap.  Note that everything comes from CGI, so addslashes() as needed!

Special error reporting is needed, because errors are return codes sent to the applet, not the web browser.
*/


function print_ok() {
	global $dbconn;
	if (!empty($dbconn)) {
		db_close();
	}

	header('Content-Type: text/plain');
	exit('ok');
}
function quiet_exit($exit_err) {
	global $dbconn;
	if (!empty($dbconn)) {
		db_close();
	}

	header('Content-Type: text/plain');
	exit("error\n".$exit_err);
}


// Get raw POST data
if (!isset($HTTP_RAW_POST_DATA) || empty($HTTP_RAW_POST_DATA)) {
	$raw_buffer = file_get_contents('php://input');
} else {
	$raw_buffer =& $HTTP_RAW_POST_DATA;
}
if (empty($raw_buffer)) {
	quiet_exit('No image data was received. Take a screenshot to be safe, and try again.');
}


// Parse login info
// Boot.php handles all filtering
$params = array();
if (substr($raw_buffer, 0, 1) == 'S') {
	// Header
	$image['h_length'] = (int) substr($raw_buffer, 1, 8);
	$image['h_data']   = substr($raw_buffer, 9, $image['h_length']);

	$pairs = explode(';', $image['h_data']);
	for ($i = count($pairs); --$i >= 0;) {
		$keyvals = explode('=', $pairs[$i]);
		$params[$keyvals[0]] = $keyvals[1];
	}

	// Login is sent in the parameters (strings are UTF-8 encoded)
	$OekakiID   = addslashes($params['uid']);
	$OekakiPass = addslashes($params['vcode']);
} else {
	// Failure, but try to get the cookie so we can log stuff properly.
	if (isset($_REQUEST['OekakiPass'])) {
		// Backup
		$OekakiPass = $_REQUEST['OekakiPass'];
		if (isset($_REQUEST['OekakiID'])) {
			$OekakiID = $_REQUEST['OekakiID'];
		}
	}
}


// Load bootstrap, quiet mode
$quiet_mode  = true;
$no_template = true;
$no_online   = true;
define('BOOT', 1);
require 'boot.php';

if (empty($OekakiID)) {
	quiet_exit( t('err_loginvs'));
}
require 'paintsave.php';


// Clean input
function get_param($in) {
	global $params;
	return isset($params[$in]) ? $params[$in] : '';
}
$mode      =       get_param('mode');
$edittimes = (int) get_param('edittimes');
$edit      = (int) get_param('edit');
$import    = (int) get_param('import');
$user_pass =       get_param('pw');
$datatype  = (int) get_param('datatype');

if (!$datatype) {
	$datatype = '2';
}


// ShiBBS data
if (substr($raw_buffer, 0, 1) == 'S') {
	// Image data
	$image['o_length'] = (int) substr($raw_buffer, $image['h_length'] + 9, 8);
	$image['o_data']   = substr($raw_buffer, $image['h_length'] + 19, $image['o_length']);

	// Animation
	$image['t_length'] = (int) substr($raw_buffer, $image['h_length'] + $image['o_length'] + 19, 8);
	$image['t_data']   = substr($raw_buffer, $image['h_length'] + $image['o_length'] + 27, $image['t_length']);

	// Thumbnail
	$image['t2_length'] = (int) substr($raw_buffer, $image['h_length'] + $image['o_length'] + $image['t_length'] + 27, 8);
	$image['t2_data']   = substr($raw_buffer, $image['h_length'] + $image['o_length'] + $image['t_length'] + 35, $image['t2_length']);
}


// Check image
if (empty($image['o_data'])) {
	quiet_exit( t('err_nodata'));
}
// Is animation too big?
if ($image['t_length'] > $cfg['max_anim']) {
	$image['t_length'] = 0;
	$image['t_data'] = null;
} else {
	// Hack for weird \n -> \r\n issue when applet uploads animations
	// (probably a due to a "smart" server)
	if (strpos($image['t_data'], "\x0D\x0Acount_lines") !== false) {
		$image['t_data'] = str_replace("\x0D\x0A", "\x0A", $image['t_data']);
	}
}


// Insert new slot into database
if ($mode == 'norm' || $mode == 'anim') {
	$resno = get_new_picnumber();
	if ($resno == 0) {
		w_log(WLOG_FAIL, 'shiget: $l_no_picid');
		quiet_exit( t('err_picts'));
	}

	// Cleanup slots
	$result = clean_picture_slots();

	$thetime = time() - $edittimes;

	// Insert new slot
	$sqlres = "INSERT INTO {$db_p}oekakidta SET usrname='$OekakiU', postdate=NOW(), hostname='$hostname', comment='', PIC_ID='$resno', IP='$address', datatype=$datatype, edittime='$thetime', password=''";
	if ($mode == 'anim') {
		$sqlres .= ', animation=1';
	} else {
		$sqlres .= ', animation=0';
	}
	$result = db_query($sqlres);

	if (!$result) {
		w_log_sql('shiget: $l_app_no_insert`'.$resno.'`');
		quiet_exit( t('err_saveimg'));
	}
}


// Update edited slot to database
if ($mode == 'edit') {
	// Verify edit, otherwise invalidate it
	$resno = verify_edit($edit, $user_pass);

	$edittime2 = time() - $edittimes;
	$result2 = db_query("UPDATE {$db_p}oekakidta SET edittime=(edittime + $edittime2) WHERE PIC_ID='$resno'");

	// Check WIP status
	$result3 = db_query("SELECT postlock FROM {$db_p}oekakidta WHERE PIC_ID='$resno'");
	if ($result3 && (db_result($result3, 0) != 1)) {
		$result3 = db_query("UPDATE {$db_p}oekakidta SET postdate=NOW() WHERE PIC_ID='$resno'");
	}
}


// Write files
//
// Save image/anim data.  Filetype is automatic!
if (!save_image($image['o_data'], $resno)) {
	w_log(WLOG_FAIL, 'shiget: $l_app_no_save`'.$resno.'`');
	quiet_exit( t('err_saveimg'));
}
if (!empty($image['t_data'])) {
	save_anim($image['t_data'], $resno, 'pch');
}


// Credit original artist, if required
if ($import) {
	$result = db_query("SELECT usrname, origart FROM {$db_p}oekakidta WHERE PIC_ID='{$import}'");

	if ($result && db_num_rows($result) > 0) {
		$import_check = db_fetch_array($result);
		$import_check['usrname'] = addslashes($import_check['usrname']);
		$import_check['origart'] = addslashes($import_check['origart']);

		if ($import_check['usrname'] != $OekakiU) {
			// Not artist!  We'll need to update 'origart'.

			if ($import_check['origart'] != $OekakiU) {
				// Not original artist, either.  Update.
				$result2 = db_query("UPDATE {$db_p}oekakidta SET origart='{$import_check['usrname']}' WHERE PIC_ID='{$resno}'");
			} else {
				// Retouched by original artist.  Clear 'origart' if set
				$result2 = db_query("UPDATE {$db_p}oekakidta SET origart='' WHERE PIC_ID='{$resno}'");
			}
		}
	}
}


print_ok();