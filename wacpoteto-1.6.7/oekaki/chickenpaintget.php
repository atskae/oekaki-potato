<?php
/*
Animation code by Marcello (http://www.cellosoft.com). Modified by RanmaGuy. Used with permission. This file is not to be used with any other script other than OekakiPoteto.

Wacintaki Poteto modifications Copyright 2004-2016 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.4 - Last modified 2016-07-29
*/


/*
NOTES:

Do not HTML encode any data in the ChickenPaint config file.  Anything within script elements must be left alone.

ChickenPaint does not support animation data, but the layers file is still useful so we'll save it as an animation, anyway.
*/


function print_ok() {
	global $dbconn;
	if (!empty($dbconn)) {
		db_close();
	}

	header('Content-Type: text/plain');
	exit("CHIBIOK\n");
}
function quiet_exit($exit_err) {
	global $dbconn;
	if (!empty($dbconn)) {
		db_close();
	}

	header('Content-Type: text/plain; charset=utf-8');
	exit("CHIBIERROR {$exit_err}\n");
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
	quiet_exit( t('err_loginvs'));
}
require 'paintsave.php';


// Clean input
//$mode    = {boot.php};
$edittimes = w_gpc('edittimes', 'i');
$edit      = w_gpc('edit', 'i+');
$import    = w_gpc('import', 'i+');
$user_pass = w_gpc('pw');



// ChibiPaint data
$buffer        = null;
$buffer_anim   = null;
$buffer_layers = null;
$got_chi_file  = false;

// Image
if (!isset($_FILES['picture'])) {
	quiet_exit('No picture data was received. Try again.');
}
if ($_FILES['picture']['error'] > 0 || empty($_FILES['picture']['tmp_name'])) {
	if ($_FILES['picture']['error'] == UPLOAD_ERR_NO_FILE) {
		quiet_exit('No picture data was received. Try again.');
	}
	quiet_exit('An error occured while uploading. Try again.');
}
if ($_FILES['picture']['size'] > $cfg['max_pic']) {
	quiet_exit('Picture too large!  Max size is '.$cfg['max_pic'].' bytes.');
}
$buffer = file_get_contents($_FILES['picture']['tmp_name']);


// Animation/layers
// Uses move_uploaded_file() later in this script.
if (isset($_FILES['chibifile'])) {
	if ($_FILES['chibifile']['error'] == 0) {
		if ($_FILES['chibifile']['size'] <= $cfg['max_anim']) {
			$got_chi_file = true;
		}
	}
}



// Insert new slot into database
if ($mode == 'norm' || $mode == 'anim') {
	$resno = get_new_picnumber();
	if ($resno == 0) {
		w_log(WLOG_FAIL, 'chickenpaintget: $l_no_picid');
		quiet_exit( t('err_picts'));
	}

	// Cleanup slots
	$result = clean_picture_slots();

	$thetime = time() - $edittimes;

	// Insert new slot
	$sqlres = "INSERT INTO {$db_p}oekakidta SET usrname='$OekakiU', postdate=NOW(), hostname='$hostname', comment='', PIC_ID='$resno', IP='$address', datatype=5, edittime='$thetime', password=''";
	if ($mode == 'anim' || $got_chi_file) {
		$sqlres .= ', animation=1';
	} else {
		$sqlres .= ', animation=0';
	}
	$result = db_query($sqlres);

	if (!$result) {
		w_log_sql('chickenpaintget: $l_app_no_insert`'.$resno.'`');
		quiet_exit( t('err_saveimg'));
	}
}


// Update edited slot to database
if ($mode == 'edit') {
	// Verify edit, otherwise invalidate it
	$resno = verify_edit($edit, $user_pass);

	$edittime2 = time() - $edittimes;
	$result2 = db_query("UPDATE {$db_p}oekakidta SET edittime=(edittime + $edittime2) WHERE PIC_ID='$resno'");

	// Make sure animation file is updated/removed as neccesary
	// Chibi Paint is unique from the other applets in that animations (layers)
	//   can be invalidated by the applet, not just by the oekaki
	if ($got_chi_file) {
		// Add animation flag
		$result3 = db_query("UPDATE {$db_p}oekakidta SET animation=1 WHERE PIC_ID='$resno'");
	} else {
		// Remove flag.  File deleted later
		$result3 = db_query("UPDATE {$db_p}oekakidta SET animation=0 WHERE PIC_ID='$resno'");
	}

	// Check WIP status
	$result3 = db_query("SELECT postlock FROM {$db_p}oekakidta WHERE PIC_ID='$resno'");
	if ($result3 && db_result($result3, 0) != 1) {
		$result3 = db_query("UPDATE {$db_p}oekakidta SET postdate=NOW() WHERE PIC_ID='$resno'");
	}

}


// Write files
//
// Save image/anim data.  Filetype is automatic!
if (!save_image($buffer, $resno)) {
	w_log(WLOG_FAIL, 'chickenpaintget: $l_app_no_save`'.$resno.'`');
	quiet_exit( t('err_saveimg'));
}
if ($got_chi_file === true) {
	move_uploaded_file($_FILES['chibifile']['tmp_name'], $p_pic.$resno.'.chi');
} else {
	if (file_exists($p_pic.$resno.'.chi')) {
		@unlink($p_pic.$resno.'.chi');
	}
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