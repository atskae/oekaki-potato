<?php
/*
OekakiBBS parse code by Marcello (http://www.cellosoft.com). Modified by RanmaGuy. Used with permission. This file is not to be used with any other script other than OekakiPoteto.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.2 - Last modified 2015-04-26
*/


/*
NOTES:

OekakiBBS dosn't transmit CGI headers, so we read GET data instead, as explained in oekakiBBS.php.

Special error reporting is needed, because errors are return codes sent to the applet, not the web browser.  If an empty HTTP 200 is transmit to the applet, OekakiBBS will assume the transmission completed fine (!), but if there is text, the applet will display the message and not forward to the comment screen.
*/


function print_ok() {
	global $dbconn;
	if (!empty($dbconn)) {
		db_close();
	}

	header('Content-Type: text/plain');
	exit();
}
function quiet_exit($exit_err) {
	global $dbconn;
	if (!empty($dbconn)) {
		db_close();
	}

	header('Content-Type: text/plain');
	exit($exit_err);
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
if (isset($_REQUEST['uid']) && isset($_REQUEST['vcode'])) {
	// Preferred
	$OekakiID   = $_REQUEST['uid'];
	$OekakiPass = addslashes($_REQUEST['vcode']);
} elseif (isset($_REQUEST['OekakiPass'])) {
	// Backup
	$OekakiPass = $_REQUEST['OekakiPass'];
	if (isset($_REQUEST['OekakiID'])) {
		$OekakiID = $_REQUEST['OekakiID'];
	}
}


// Load bootstrap, quiet mode
$quiet_mode  = true;
$no_template = true;
$no_online   = true;
define('BOOT', 1);
require 'boot.php';

if (empty($OekakiID) && empty($OekakiU)) {
	// OekakiBBS is really fussy with its redirects.
	// For safety, I use ASCII, but I'm not sure if that's required.
	quiet_exit('Login could not be verified!  Take a screenshot of your picture.');
}
require 'paintsave.php';


// Clean input
//$mode    = {boot.php};
$edittimes = w_gpc('edittimes', 'i');
$edit      = w_gpc('edit', 'i+');
$import    = w_gpc('import', 'i+');
$user_pass = w_gpc('pw');


// Process POST
$start  = strpos($raw_buffer, 'Content-type:');
$middle = 0;
$end    = 0;

while ($start) {
	// Foreach Content-type, get type
	$end    = strpos($raw_buffer, 'Content-type:', $start + 1);
	$middle = strpos($raw_buffer, "\r", $start);
	$type   = substr($raw_buffer, $start + 13, $middle - $start - 13);
	$middle = strpos($raw_buffer, "\r", $middle + 1);

	if ($end === false) {
		$end = null;
		$data = substr($raw_buffer, $middle + 2);
	} else {
		$data = substr($raw_buffer, $middle + 2, $end - $middle - 2);
	}

	$start = $end;

	// 'image/0'=PNG, 'image/1'=JPEG
	if ($type == 'image/0' || $type == 'image/1') {
		$picturedata = $data;
	}
	if ($type == 'animation/') {
		$animdata = $data;
	}

	unset($data);
}

// Check image
if (empty($picturedata)) {
	quiet_exit( t('err_nodata'));
}
// Is animation too big?
if (strlen($animdata) > $cfg['max_anim']) {
	$mode = 'norm';
	$animdata = null;
}


// Insert new slot into database
if ($mode == 'norm' || $mode == 'anim') {
	$resno = get_new_picnumber();
	if ($resno == 0) {
		w_log(WLOG_FAIL, 'getoekakibbs: $l_no_picid');
		quiet_exit( t('err_picts'));
	}

	// Cleanup slots
	$result = clean_picture_slots();

	$thetime = time() - $edittimes;

	// Insert new slot
	$sqlres = "INSERT INTO {$db_p}oekakidta SET usrname='$OekakiU', postdate=NOW(), hostname='$hostname', comment='', PIC_ID='$resno', IP='$address', datatype=1, edittime='$thetime', password=''";
	if ($mode == 'anim') {
		$sqlres .= ', animation=1';
	} else {
		$sqlres .= ', animation=0';
	}
	$result = db_query($sqlres);

	if (!$result) {
		w_log_sql('getoekakibbs: $l_app_no_insert`'.$resno.'`');
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
	if ($result3 && db_result($result3, 0) != 1) {
		$result3 = db_query("UPDATE {$db_p}oekakidta SET postdate=NOW() WHERE PIC_ID='$resno'");
	}
}


// Write files
//
// Save image/anim data.  Filetype is automatic!
if (!save_image($picturedata, $resno)) {
	w_log(WLOG_FAIL, 'getoekakibbs: $l_app_no_save`'.$resno.'`');
	quiet_exit( t('err_saveimg'));
}
if (!empty($animdata)) {
	save_anim($animdata, $resno, 'oeb');
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