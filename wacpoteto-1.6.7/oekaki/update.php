<?php
/*
Wacintaki Poteto Update Script
http://www.NineChime.com/products/
Version 1.6.7 - Last modified 2019-02-05

NOTES:
	As of 1.5.0, only the db version matters, and it is updated EVERY time any cfg/db change is made.

	As of 1.5.4, the new db layer is used for ALL queries regardless of dbconn file age.  Remember to global the database variables if you need to write a new database config file.  For safety, always global "$dbconn, $db_p, $db_mem;" to each function.

	As of 1.5.6, a new database charset and collation are required.  The functions to convert the database are in a separate updater, "update_rc.php"

CHECKLIST:
	Change installer db version in SQL, all versions in updater, db version stamp, and boot init.
*/


// Set bootstrap flag and fake init, so we can include files
define('BOOT', 1);
$glob = array(
	'debug'   => false,
	'wactest' => false
);


// Config
$cfg_update = array();
$cfg_update['debug']   = false;   // Prevents deletion of work files
$cfg_update['status']  = 0;       // Returned by functions (0=Okay, 1=Warning, 2=Stop/salvage)
$cfg_update['version'] = '1.5.6'; // Current Wacintaki config version number (in database)


// Admin rank numbers
define('RANK_MOD',    4);
define('RANK_ADMIN',  5);
define('RANK_SADMIN', 7);
define('RANK_OWNER',  9);


// Init
error_reporting(E_ALL ^ E_NOTICE);
include 'common.php';

if (!@include 'config.php') {
	header('Content-Type: text/plain');
	exit('STOP: Cannot read config.php file.');
}
include 'language/'.$cfg['language'].'.php';

print_header();


// Make db connection using new db_layer
define('PARSE_DBCONN_MANUALLY', 1);
$db_info = @parse_config_file('dbconn.php');
if ($db_info !== false) {
	$dbhost = $db_info['dbhost'];
	$dbuser = $db_info['dbuser'];
	$dbpass = $db_info['dbpass'];
	$dbname = $db_info['dbname'];
	$OekakiPoteto_Prefix = $db_info['OekakiPoteto_Prefix'];
	$OekakiPoteto_MemberPrefix = $db_info['OekakiPoteto_MemberPrefix'];

	require 'db_layer.php';
	db_open();
} else {
	p_line( t('up_cant_read_for_db', 'dbconn.php'));
	print_footer();
	w_exit();
}


// Begin
if (!isset($_POST['stage']) || $_POST['stage'] < 1) {

	// Make sure main files are writable
	if (! is_writable('dbconn.php')) {
		p_line( t('up_file_locked', 'dbconn.php'));
		$cfg_update['status'] = 2;
	}
	if (! is_writable('config.php')) {
		p_line( t('up_file_locked', 'config.php'));
		$cfg_update['status'] = 2;
	}

	if ($cfg_update['status'] == 2) {
		print_footer();
		w_exit();
	}


	// Get the current version numbers
	// NOTE: cascading detect
	$old_cfg_version = '';
	$old_db_version  = '';
	{
		// Poteto 4.x
		if (empty($CFG_version)) {
			// (Assumed pre-5.0 release)
			$old_cfg_version = 'op-4.0.0';
		}

		// Poteto 5.x
		if (isset($jpgcompression) && empty($CFG_version)) {
			$old_cfg_version = 'op-5.0.0';
		}

		// Wac1.0
		if (isset($requireart) && empty($CFG_version)) {
			$old_cfg_version = 'wac-1.0.0';
		}

		// wac-1.1.0 - 1.1.5
		if (isset($CFG_version) && !isset($requireart)) {
			$old_cfg_version = $CFG_version;

			$result = db_query("SELECT miscstring FROM {$db_p}oekakimisc WHERE miscname='dbversion'");
			if ($result) {
				$old_db_version = db_result($result, 0);
			}
		}

		// wac-1.2.0 +
		if (isset($cfg['version'])) {
			$old_cfg_version = $cfg['version'];

			$result = db_query("SELECT miscstring FROM {$db_p}oekakimisc WHERE miscname='dbversion'");
			if ($result && db_num_rows($result) > 0) {
				$old_db_version = db_result($result, 0);
			}
		}

		// wac-1.5.0 +
		if ($old_cfg_version === '1.3.0') {
			// As of 1.5.0, the cfg version is invalid.
			// The database marker is what's important for the updater.
			$old_cfg_version = $old_db_version;
		}
	}

	print_update_type($old_cfg_version, $old_db_version);
} // End no stage



// Collect update options; print control panel options
// OBSOLETE
//if (w_gpc('stage') == '1') {
//	print_control_panel();
//}



// Collect control panel options; perform the update
if (w_gpc('stage') == '2') {

	// Wax Poteto update only
	if ($_POST['from_version'] === '5.6.0') {
		if (! wax560_to_wac130() ) {
			print_verify( t('up_to_fail', '1.3.0'));
			print_footer();
			w_exit();
		}

		$_POST['from_version'] = '1.3.0';
	}


	// Main Wacintaki update
	// Choose a starting point, and drop through all cases (no breaks)
	//
	// Error message cascaded to next case if there's an error
	switch ($_POST['from_version']) {
		case 'op-5.0.0':
			if (! op500_to_wac100() ) {
				print_verify( t('up_to_fail', '1.0.0'));
				break;
			}
		case 'wac-1.0.0':
			if (! wac100_to_wac110(false) ) {
				print_verify( t('up_to_fail', '1.1.0'));
				break;
			}
		case 'wac-1.1.0':
			if (! wac110_to_wac111() ) {
				print_verify( t('up_to_fail', '1.1.1'));
				break;
			}
		case 'wac-1.1.1':
			if (! wac111_to_wac120() ) {
				print_verify( t('up_to_fail', '1.2.0'));
				break;
			}
		case '1.2.0':
			if (! wac120_to_wac130() ) {
				print_verify( t('up_to_fail', '1.3.0'));
				break;
			}
		case '1.3.0':
			if (! wac130_to_wac1312() ) {
				print_verify( t('up_to_fail', '1.3.12'));
				break;
			}
		case '1.3.12':
			if (! wac1312_to_wac142() ) {
				print_verify( t('up_to_fail', '1.4.2'));
				break;
			}
		case '1.4.2':
			if (! wac142_to_wac143() ) {
				print_verify( t('up_to_fail', '1.4.3'));
				break;
			}
		case '1.4.3':
			if (! wac143_to_wac150() ) {
				print_verify( t('up_to_fail', '1.5.0'));
				break;
			}
		case '1.5.0':
			if (! wac150_to_wac155() ) {
				print_verify( t('up_to_fail', '1.5.5'));
				break;
			}
		case '1.5.5':
			if (! wac155_to_wac156() ) {
				print_verify( t('up_to_fail', '1.5.6'));
				break;
			}

			// Latest version.  Dropout
			print_OK();
			break;
		case $cfg_update['version']:
			if (! wac155_to_wac156() ) {
				print_verify( t('up_ver_fail', $cfg_update['version']));
			}

			// Latest version.  Dropout
			print_OK();
			break;
		default:
			p_line( t('up_no_up_num', w_html_chars($_POST['from_version']), w_html_chars($_POST['to_version'])));
			p_line( t('up_no_up_sum', $old_versions['cfg'], $old_versions['db']));
			p_line( t('up_no_cont'));
			p_line( t('up_nc_short'));
	} // End switch()

} // End Stage 2



// Finis
print_footer();
w_exit();



// ==================================================================
// General Functions
//
function print_header() {
	global $cfg_update;
	global $charset, $metatag_language;

	if (!empty($charset)) {
		header("Content-Type: text/html; charset={$charset}");
	}
	if (!empty($metatag_language)) {
		header("Content-Language: {$metatag_language}");
	}

?>
<!DOCTYPE html PUBLIC
	"-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}" />
	<meta http-equiv="Content-Language" content="{$metatag_language}" />

	<title><?php tt('up_header_title', $cfg_update['version']);?></title>

	<style type="text/css" title="default">
		h1, h4 {
			font-family: sans-serif;
		}
		p {
			margin-left: 2em;
		}
	</style>
</head>
<body>


<h1><?php tt('up_header_title', $cfg_update['version']);?></h1>
<hr />


<?php
}


function print_footer() {
	echo "\n\n</body>\n</html>";
}


function print_OK() {
	global $cfg_update;

?>
	<p>
		<?php tt('up_mult_warn');?> 
	</p>
	<p>
		<?php tt('up_click_final');?> 
	</p>

	<form action="index.php" method="post">
<?php
	if (!$cfg_update['debug']) {
		echo'		<input name="action" type="hidden" value="secure" />'."\n";
	}
?>
		<input name="submit" type="submit" value="Secure updater and go to the BBS" />
	</form>

<?php
}


function print_verify($error) {
	global $cfg_update;

	if (!empty($error)) {
		p_line($error);
	}

	if ($cfg_update['status'] == 1) {
		// Warning returned
?>
	<p>
		<?php tt('up_warn_rerun');?> 
	</p>
<?php
	} elseif ($cfg_update['status'] == 2) {
		// Critical error
?>
	<p>
		<?php tt('up_stop_sum');?> 
	</p>
	<p>
		<?php tt('up_nc_short');?> 
	</p>

<?php
	}
}


function print_update_type($detected_version, $detected_db_version = '') {
	global $cfg_update;

	// OekakiPoteto update
	if ($detected_version === 'op-5.0.0') {
?>
<form name="form1" action="update.php" method="post">
	<h4><?php tt('up_v_detected', 'OekakiPoteto v5.x');?></h4>

	<p>
		<?php tt('up_no_op_tpl');?> 
	</p>

	<p><?php tt('up_next_start');?></p>

	<p>
		<input name="from_version" type="hidden" value="op-5.0.0" />
		<input name="to_version" type="hidden" value="<?php echo $cfg_update['version'];?>" />

		<input name="stage" type="hidden" value="2" />
		<input name="submit" type="submit" value="<?php tt('up_word_next');?>" />
	</p>
</form>
<?php
	return 1;
	}


	// Wax Poteto v5.6.x update
	if ($detected_version === '5.6.0') {
?>
<form name="form1" action="update.php" method="post">
	<h4><?php tt('up_v_detected', 'Wax Poteto v5.6.x');?></h4>

	<p><?php tt('up_next_start');?></p>

	<p>
		<input name="from_version" type="hidden" value="5.6.0" />
		<input name="to_version" type="hidden" value="<?php echo $cfg_update['version'];?>" />

		<input name="stage" type="hidden" value="2" />
		<input name="submit" type="submit" value="<?php tt('up_word_next');?>" />
	</p>
</form>
<?php
	return 1;
	}


	// Wacintaki update
	// We have to check both the config file and db versions, and return the higher of the two
	//
	// Verification
	if ($detected_db_version == $cfg_update['version']) {
?>
<form name="form1" action="update.php" method="post">
	<h4><?php tt('up_v_detected', $detected_db_version);?></h4>
	<p>
		<?php tt('up_latest_ver');?> 
	</p>

	<p><?php tt('up_next_ver');?></p>
	<p>
		<input name="from_version" type="hidden" value="<?php echo $detected_version;?>" />
		<input name="to_version" type="hidden" value="<?php echo $cfg_update['version'];?>" />

		<input name="stage" type="hidden" value="2" />
		<input name="submit" type="submit" value="<?php tt('up_word_next');?>" />
	</p>
</form>
<?php
		return 1;
	}

	// Make sure current BBS is not more recent than updater
	$split        = version_split($detected_version);
	$db_split     = version_split($detected_db_version);
	$test_current = version_split($cfg_update['version']);
	if ($split['ver'] <= $test_current['ver']
		|| $db_split['ver'] <= $test_current['ver'])
	{
		// Print the highest version detected
		// If cfg and db are out of sync, they will be fixed with the verification (below)
		$print_ver = ($split['ver'] > $db_split['ver'])
			? $detected_version
			: $detected_db_version;
?>
<form name="form1" action="update.php" method="post">
	<h4><?php tt('up_v_detected', $print_ver);?></h4>

	<p><?php tt('up_next_start');?></p>

	<p>
		<input name="from_version" type="hidden" value="<?php echo $print_ver;?>" />
		<input name="to_version" type="hidden" value="<?php echo $cfg_update['version'];?>" />

		<input name="stage" type="hidden" value="2" />
		<input name="submit" type="submit" value="<?php tt('up_word_next');?>" />
	</p>
</form>
<?php
		return 1;
	}

	// Unknown version
?>
	<h4><?php tt('up_unknown_v');?></h4>

	<h4><?php tt('up_unknown_v_sum', $detected_version, $detected_db_version);?></h4>

	<p><?php tt('up_v_spread_sum', $cfg_update['version']);?></p>

	<p><?php tt('up_no_cont');?></p>

<?php
	return 0;
}


// ==================================================================
// Update Functions
//
function op500_to_wac100() {
	global $cfg_update;
	global $dbconn, $db_p, $db_mem;

	p_line( t('up_from_to', 'OekakiPoteto 5.1.0a', 'Wacintaki 1.0.0'));

	/* We need to do the following:
		1) Add new values to config file:
			$BBSauthor        = '';
			$OPPrefix         = '';
			$requireart       = 'no';
			$templatepath     = 'templates';
			$resourcepath     = 'resource';
			$perpage          = '25';
			$CFG_previewImg   = 'preview.png';
			$CFG_previewTitle = 'Canvas Preview';
			$xMin             = '50';
			$yMin             = '50';
			$aCanvas          = '250000'; (Unused for the moment)
			$useChat          = 'yes'; (Default for Poteto 5.1.0a)

		2) Remove values from config file:
			$apostrophes

		3) Update templates
			Ensure default is set, and change all user accounts to the default template.

		4) Verify that the chosen language file is available in Wacintaki Poteto.

		5) Remove files if they exist:
			announce.php
			arecover.php
			button.gif
			editnews.php
			flagchk.php
			MgLine.zip
			news.php
			noteBBSa.php
			oekakiBBSa.php
			README.txt
			redirect.php
			tdesign.php
			tedit.php
			tedita.php
			template.php
			test.php
			tnew.php
			tnewa.php
			versionchk.php
	*/


	require 'config.php';


	$okurl  = strip_path($okurl);
	$OPpics = strip_path($OPpics);
	if (!isset($templatepath) || empty($templatepath)) {
		$templatepath = 'templates';
	}
	if (!isset($resourcepath) || empty($resourcepath)) {
		$resourcepath = 'resource';
	}
	$templatepath = strip_path($templatepath);
	$resourcepath = strip_path($resourcepath);
	if ($useChat != 'no') {
		$useChat  = 'yes';
	}
	if (!isset($OPPrefix)) {
		$OPPrefix = '';
	}
	$template = 'Classic';


	$main_configuration = <<<EOF
<?php // Include only

	\$BBStitle  = '';
	\$BBSauthor = '';
	\$BBSadult  = 'no';
	\$eaddr     = '$eaddr';
	\$okurl     = '$okurl';

	\$saltenc    = '$saltenc';
	\$OPpics     = '$OPpics';
	\$archivedir = '$archivedir';
	\$OPPrefix   = '$OPPrefix';

	\$killuser   = '$killuser';
	\$approval   = '$approval';
	\$requireart = 'no';
	\$guestpost  = '$guestpost';
	\$drawaccess = '$drawaccess';
	\$animationaccess = '$animationaccess';

	\$language     = '$language';
	\$template     = '$template';
	\$templatepath = '$templatepath';
	\$resourcepath = '$resourcepath';

	\$pstore  = '$pstore';
	\$ppage   = '$ppage';
	\$perpage = '25';

	\$CFG_previewImg   = 'preview.png';
	\$CFG_previewTitle = 'Canvas Preview';
	\$xDefault = '$xDefault';
	\$yDefault = '$yDefault';
	\$xCanvas  = '$xCanvas';
	\$yCanvas  = '$yCanvas';
	\$xMin     = '50';
	\$yMin     = '50';
	\$aCanvas  = '250000';
	\$anisize  = '$anisize';

	\$useChat  = '$useChat';
	\$chatMax  = '$chatMax';
	\$chatDisp = '$chatDisp';

	\$jpgcompression  = '$jpgcompression';
	\$jpgcompressqual = '$jpgcompressqual';

?>
EOF;


	// Write config file
	$fp = @fopen('config.php', 'w');
	if ($fp) {
		fwrite($fp, $main_configuration);
		fclose($fp);
	} else {
		p_line( t('up_cant_write_file', 'config.php'));
		return 0;
	}


	// Update templates/languages
	{
		$newtemplate = 'Default';

		// Template
		$result = db_query("ALTER TABLE {$db_mem}oekaki CHANGE templatesel templatesel VARCHAR(255) DEFAULT '".$newtemplate."'");
		sql_check($result);

		$result = db_query("UPDATE {$db_mem}oekaki SET templatesel='".$newtemplate."'");
		sql_check($result);


		// Language
		if (file_exists('language/'.$language.'.php')) {
			$result = db_query("UPDATE {$db_mem}oekaki SET language='".$language."'");
		} else {
			$result = db_query("UPDATE {$db_mem}oekaki SET language='english'");
		}
		sql_check($result);
	}


	// Verify CHMOD
	if (!check_write($OPpics, 755)) {
		p_line( t('up_folder_locked', $OPpics));
		$cfg_update['status'] = 1;
	}

	if (!check_write('templates', 755)) {
		p_line( t('up_folder_locked', 'templates'));
		$cfg_update['status'] = 1;
	}


	// Remove files if they exist
	delete_obsolete_files(true);


	// Complete!
	if ($cfg_update['status'] == 0) {
		p_line( t('up_fin_to', 'Wacintaki 1.0.0'));

		return 1;
	} else {
		return 0;
	}
}
// END op500_to_wac100



function wac100_to_wac110($force) {
	global $cfg_update;
	global $dbconn, $db_p, $db_mem;

	p_line( t('up_from_to', 'Wacintaki 1.0.0', '1.1.0'));

	/* We need to do the following:
		1) Add new values to config file:
			$CFG_version     = 'wac-1.1.0';
			$killreg         = '-1'; //WIP
			$guestdraw       = 'no'; //WIP
			$guestadult      = 'yes';
			$picLimit        = '2';  //WIP
			$picLimitTime    = '24'; //WIP
			$CFG_pornImg     = 'pr0n.png';
			$useThumb        = '2';
			$forceThumb      = 'no';
			$thumbBytes      = '100000';
			$thumbT          = '120';
			$thumbR          = '250';
			$thumbJPG        = '75'; //WIP
			$jpgcompression  = 'no'; //WIP
			$jpgcompressqual = '85'; //WIP
			$useMailbox      = 'no';
			$useSuggest      = 'no'; //WIP
			$suggestMax      = '50'; //WIP
			$suggestApprove  = 'no'; //WIP

		2) Add new values to database (finally):
			oekaki: thumbview
			oekaki: screensize
			oekakidta: threadlock
			oekakidta: px
			oekakidta: py
			oekakidta: ptype
			oekakidta: ttype
			oekakidta: rtype
			oekakidta: usethumb
			oekakimisc: miscstring

		3) Remove files if they exist:
			compress.php
	*/


	require 'config.php';


	// Update config file
	{
		if (extension_loaded('gd')) {
			$thumbnail_detect = 2;
		} else {
			$thumbnail_detect = 0;
		}

		$BBStitle  = addslashes(stripslashes($BBStitle));
		$BBSauthor = addslashes(stripslashes($BBSauthor));
		$CFG_previewTitle = addslashes(stripslashes($CFG_previewTitle));

		$main_configuration = <<<EOF
<?php // Include only

	\$CFG_version = 'wac-1.1.0';

	\$BBStitle  = '$BBStitle';
	\$BBSauthor = '$BBSauthor';
	\$BBSadult  = '$BBSadult';
	\$eaddr     = '$eaddr';
	\$okurl     = '$okurl';

	\$saltenc    = '$saltenc';
	\$OPpics     = '$OPpics';
	\$archivedir = '$archivedir';
	\$OPPrefix   = '$OPPrefix';

	\$killuser   = '$killuser';
	\$killreg    = '-1'; //WIP
	\$approval   = '$approval';
	\$requireart = '$requireart';
	\$guestpost  = '$guestpost';
	\$guestdraw  = 'no'; //WIP
	\$guestadult = 'yes';
	\$drawaccess = '$drawaccess';
	\$animationaccess = '$animationaccess';

	\$language     = '$language';
	\$template     = '$template';
	\$templatepath = '$templatepath';
	\$resourcepath = '$resourcepath';

	\$pstore   = '$pstore';
	\$ppage    = '$ppage';
	\$perpage  = '$perpage';
	\$picLimit     = '2';  //WIP
	\$picLimitTime = '24'; //WIP

	\$CFG_pornImg      = 'pr0n.png';
	\$CFG_previewImg   = '$CFG_previewImg';
	\$CFG_previewTitle = '$CFG_previewTitle';
	\$xDefault = '$xDefault';
	\$yDefault = '$yDefault';
	\$xCanvas  = '$xCanvas';
	\$yCanvas  = '$yCanvas';
	\$xMin     = '$xMin';
	\$yMin     = '$yMin';
	\$aCanvas  = '$aCanvas';
	\$anisize  = '$anisize';

	\$useThumb   = '$thumbnail_detect';
	\$forceThumb = 'no';
	\$thumbBytes = '100000';
	\$thumbT     = '120';
	\$thumbR     = '250';
	\$thumbJPG   = '75'; //WIP
	\$jpgcompression   = 'no'; //WIP
	\$jpgcompressqual  = '85'; //WIP

	\$useMailbox = 'yes';

	\$useChat  = '$useChat';
	\$chatMax  = '$chatMax';
	\$chatDisp = '$chatDisp';

	\$useSuggest     = 'no'; //WIP
	\$suggestMax     = '50'; //WIP
	\$suggestApprove = 'no'; //WIP
?>
EOF;

		// Write the config file
		if ($fp = fopen('config.php', 'w')) {
			fwrite($fp, $main_configuration);
			fclose($fp);
		} else {
			p_line( t('up_cant_write_file', 'config.php'));
			$cfg_update['status'] = 2;
			return 0;
		}
	}

	{
		// Ability to lock threads.
		$result = db_query('ALTER TABLE '.$db_p.'oekakidta ADD threadlock TINYINT NOT NULL DEFAULT 0');
		sql_check($result);

		// Picture sizes
		$result = db_query('ALTER TABLE '.$db_p.'oekakidta ADD px MEDIUMINT DEFAULT 0');
		sql_check($result);
		$result = db_query('ALTER TABLE '.$db_p.'oekakidta ADD py MEDIUMINT DEFAULT 0');
		sql_check($result);

		// Yes, VARCHAR for datatype (file extension) is better than INT as it allows for file uploads, not just pictures.  It also makes things easier for future mods.
		$result = db_query('ALTER TABLE '.$db_p.'oekakidta ADD ptype VARCHAR(4)');
		sql_check($result);
		$result = db_query('ALTER TABLE '.$db_p.'oekakidta ADD ttype VARCHAR(4)');
		sql_check($result);
		$result = db_query('ALTER TABLE '.$db_p.'oekakidta ADD rtype VARCHAR(4)');
		sql_check($result);

		// Records if thumbnail is available (built), unavailable, or should not be used (dimmentions or filesize too small, etc).
		$result = db_query('ALTER TABLE '.$db_p.'oekakidta ADD usethumb TINYINT NOT NULL DEFAULT 0');
		sql_check($result);

		// Thumbnail mode for each user
		$result = db_query('ALTER TABLE '.$db_mem.'oekaki ADD thumbview TINYINT NOT NULL DEFAULT 0');
		sql_check($result);

		// Screen size for each user
		$result = db_query('ALTER TABLE '.$db_mem.'oekaki ADD screensize MEDIUMINT NOT NULL DEFAULT 800');
		sql_check($result);

		// Version control
		$result = db_query('ALTER TABLE {$db_p}oekakimisc ADD miscstring VARCHAR(14)');
		sql_check($result);

		// Done with DB update
		$result = db_query("INSERT INTO {$db_p}oekakimisc VALUES (2, 'dbversion', 0, 'wac-1.1.0')");
		sql_check($result);
	}

	// Set DB values
	{
		// Sets view mode for each user to automatic (0)
		$result = db_query('UPDATE '.$db_mem.'oekaki SET picview=0');
		sql_check($result);
	}

	// Build ptypes
	{
		/*
		It's possible the source image can be a JPEG or PNG but there's no way to tell which is the primarty image unless we search the filesystem for both.  This is partly my fault thanks to my upload script which allows people to upload JPEGs if $jpgcompression is enabled (normally, Poteto can only use PNGs).

		If a PNG exists, we always use that, but if a JPEG exists exclusively, we use that instead.  Rather than bang the filesys, we'll read the directory and do lots of string searching to find out what's available.  There's probably a better way using stacks, but I'm stupid.  :)
		*/

		// Read pic folder.  Collect names of PNGs and JPEGs
		$handle = opendir($OPpics);
		$prefix_len = strlen($OPPrefix) + 1;
		while ($file = readdir($handle)){
			if (substr($file, -4) === '.png' || substr($file, -4) === '.jpg') {
				// Image file.  If not a thumbnail, queue it.
				$thumbtest = substr($file, 0, $prefix_len);
				if ($thumbtest != $OPPrefix.'t' && $thumbtest != $OPPrefix.'r') {
					$files[] = $file;
				}
			}
		}
		closedir($handle);

		// Collect database entries and let's sort things out
		$result = db_query('SELECT ID_2, PIC_ID, px, py, ptype FROM '.$db_p.'oekakidta');
		$total_rows = db_num_rows($result);

		echo "<p>\n";

		for ($i = 0; $i < $total_rows; $i++) {
			$row = db_fetch_array($result);

			// If ptype is occupied, we do nothing unless a force is needed.
			// Rather than juggle filetypes, assume valid extension is > 2 char.
			if (strlen($row['ptype']) < 2 || $force === true) {

				// Get the pic's datatype
				// Use PNG if avaiable, otherwise, use JPEG ("thumbnail" in Poteto)
				$my_pre = $OPPrefix.$row['PIC_ID'].'.';
				$found_img = false;

				if (in_array($my_pre.'png', $files)) {
					// Found PNG!
					$row['ptype'] = 'png';
					$found_img = true;
				} elseif (in_array($my_pre.'jpg', $files)) {
					// No PNG.  Found JPG!
					$row['ptype'] = 'jpg';
					$found_img = true;
				}

				// Verify.  Check ptype first to avoid filesys slowdown
				$image = $OPpics.'/'.$OPPrefix.$row['PIC_ID'].'.'.$row['ptype'];
				if ($found_img) {
					// Assume image may be corrupt
					$sizes = @GetImageSize ($image);
					if (!$sizes) {
						$sizes = array($xDefault, $yDefault);
					}

					// OK, we've got all our info.  Insert it into DB
					if ($cfg_update['debug']) {
						web_line("PIC: {$row['PIC_ID']}, <strong>X: {$sizes[0]}, Y: {$sizes[1]}</strong>, TYPE: {$row['ptype']}");
					} else {
						$result2 = db_query("UPDATE {$db_p}oekakidta SET px={$sizes[0]}, py={$sizes[1]}, ptype='{$row['ptype']}' WHERE PIC_ID='{$row['PIC_ID']}'");
						sql_check($result2);
					}
				} else {
					// D'OH!  Picture missing!
					web_line( t('up_missing_img', $OPPrefix.$row['PIC_ID'].'.???', $row['ID_2']));
				}
			}
		}
		echo "</p>\n";
	}
	// End build ptypes


	// Remove obsolete templates
	if ($handle = opendir('templates')) {
		while (false !== ($file = readdir($handle))) {
			if (substr($file,-4) === '.css'){
				if ($file !== 'Default.css' && $file !== 'Install.css') {
					@unlink('templates/'.$file);
				}
			}
		}
		closedir($handle);
	}


	// Complete!
	if ($cfg_update['status'] == 0 || $force === true) {
		p_line( t('up_fin_to', 'Wacintaki 1.1.0'));

		return 1;
	} else {
		return 0;
	}
}
// END wac100_to_wac110



function wac110_to_wac111() {
	global $cfg_update;
	global $dbconn, $db_p, $db_mem;

	p_line( t('up_from_to', 'Wacintaki 1.1.0', '1.1.1'));

	/* We need to do the following:
		1) Add new values to config file:
			$uploadaccess = 'no';
			$max_upload = '500000';

		2) Give all admins upload flag.  Not strictly needed, but proper.
	*/


	require 'config.php';


	// Update upload flags.  Errors are no big deal.
	$result = db_query("SELECT usrname, usrflags FROM {$db_mem}oekaki");
	if ($result) {
		$rows = db_num_rows($result);
		$i = 0;

		while ($i < $rows) {
			$user_admin = 0;
			$row = db_fetch_array($result);
			$flags = str_replace('U', '', $row['usrflags']);
			if (strstr($row['usrflags'], 'O') || strstr($row['usrflags'], 'S') || strstr($row['usrflags'], 'A')) {
				$user_admin = 1;
			}

			if ($_POST['upload_grant'] === 'yes' && strstr($row['usrflags'], 'D')) {
				$flags .= 'U';
				$result2 = db_query("UPDATE {$db_mem}oekaki SET usrflags='$flags' WHERE usrname='{$row['usrname']}'");

				if (!$result2) {
					p_line("Can't give user \"{$row['usrname']}\" upload flag (may ignore for all users, including admins).");
				}
			} elseif ($user_admin) {
				$flags .= 'U';
				$result2 = db_query("UPDATE {$db_mem}oekaki SET usrflags='$flags' WHERE usrname='{$row['usrname']}'");

				if (!$result2) {
					p_line("Can't give admin \"{$row['usrname']}\" upload flag (ignore).");
				}
			} elseif ($_POST['upload_grant'] === 'strip' && $user_admin == 0) {
				$result2 = db_query("UPDATE {$db_mem}oekaki SET usrflags='$flags' WHERE usrname='{$row['usrname']}'");

				if (!$result2) {
					p_line("<strong>NOTE:</strong>  can't remove upload flag for \"{$row['usrname']}\"");
				}
			}
			$i++;
		}
	}



	// Update config file
	if (empty($_POST['upload_flag'])) {
		$_POST['upload_flag'] = 'no';
	}

	$BBStitle  = addslashes(stripslashes($BBStitle));
	$BBSauthor = addslashes(stripslashes($BBSauthor));
	$CFG_previewTitle = addslashes(stripslashes($CFG_previewTitle));

	{
		if (extension_loaded('gd')) {
			$thumbnail_detect = 2;
		} else {
			$thumbnail_detect = 0;
		}

		$main_configuration = <<<EOF
<?php // Include only

	\$CFG_version = 'wac-1.1.1';

	\$BBStitle  = '$BBStitle';
	\$BBSauthor = '$BBSauthor';
	\$BBSadult  = '$BBSadult';
	\$eaddr     = '$eaddr';
	\$okurl     = '$okurl';

	\$saltenc    = '$saltenc';
	\$OPpics     = '$OPpics';
	\$archivedir = '$archivedir';
	\$OPPrefix   = '$OPPrefix';

	\$killuser   = '$killuser';
	\$killreg    = '-1'; //WIP
	\$approval   = '$approval';
	\$requireart = '$requireart';
	\$guestpost  = '$guestpost';
	\$guestdraw  = 'no'; //WIP
	\$guestadult = 'yes';
	\$drawaccess      = '$drawaccess';
	\$animationaccess = '$animationaccess';
	\$uploadaccess    = '{$_POST['upload_flag']}';

	\$language     = '$language';
	\$template     = '$template';
	\$templatepath = '$templatepath';
	\$resourcepath = '$resourcepath';

	\$pstore   = '$pstore';
	\$ppage    = '$ppage';
	\$perpage  = '$perpage';
	\$picLimit     = '2';  //WIP
	\$picLimitTime = '24'; //WIP

	\$CFG_pornImg      = 'pr0n.png';
	\$CFG_previewImg   = '$CFG_previewImg';
	\$CFG_previewTitle = '$CFG_previewTitle';
	\$xDefault   = '$xDefault';
	\$yDefault   = '$yDefault';
	\$xCanvas    = '$xCanvas';
	\$yCanvas    = '$yCanvas';
	\$xMin       = '$xMin';
	\$yMin       = '$yMin';
	\$aCanvas    = '$aCanvas';
	\$anisize    = '$anisize';
	\$max_upload = '500000';

	\$useThumb   = '$thumbnail_detect';
	\$forceThumb = 'no';
	\$thumbBytes = '100000';
	\$thumbT     = '120';
	\$thumbR     = '250';
	\$thumbJPG   = '75'; //WIP
	\$jpgcompression   = 'no'; //WIP
	\$jpgcompressqual  = '85'; //WIP

	\$useMailbox = 'yes';

	\$useChat  = '$useChat';
	\$chatMax  = '$chatMax';
	\$chatDisp = '$chatDisp';

	\$useSuggest     = 'no'; //WIP
	\$suggestMax     = '50'; //WIP
	\$suggestApprove = 'no'; //WIP
?>
EOF;


		// Write the config file
		if ($fp = fopen('config.php', 'w')) {
			fwrite($fp, $main_configuration);
			fclose($fp);
		} else {
			p_line( t('up_cant_write_file', 'config.php'));
			$cfg_update['status'] = 2;
			return 0;
		}
	}


	// Complete!
	if ($cfg_update['status'] == 0) {
		p_line( t('up_fin_to', 'Wacintaki 1.1.1'));

		return 1;
	} else {
		return 0;
	}
}
// END wac110_to_wac111



function wac111_to_wac120() {
	global $cfg_update;
	global $dbconn, $db_p, $db_mem;

	p_line( t('up_from_to', 'Wacintaki 1.1.1', '1.2.0'));

	/* We need to do the following, in order of safety:
		1) Dump op_oekakikill

		2) Add password field to op_oekakidta (not used yet)

		3) Reset all default templates in op_oekaki

		4) Add and set member ranks to op_oekaki

		5) Change config file variables to keyed array

		6) Update admin flags to use ranks
	*/


	include 'config.php';


	// Update config file if not updated already
	if (!isset($cfg['version']) && isset($CFG_version)) {
		$BBStitle  = addslashes(stripslashes ($BBStitle));
		$BBSauthor = addslashes(stripslashes ($BBSauthor));
		$CFG_previewTitle = addslashes(stripslashes ($CFG_previewTitle));

		$main_configuration = <<<EOF
<?php // Include only
	\$cfg = array();
	\$cfg['version'] = '1.2.0';

	\$cfg['op_title']  = '{$BBStitle}';
	\$cfg['op_author'] = '{$BBSauthor}';
	\$cfg['op_adult']  = '{$BBSadult}';
	\$cfg['op_email']  = '{$eaddr}';
	\$cfg['op_url']    = '{$okurl}';

	\$cfg['salt']    = '{$saltenc}';
	\$cfg['op_pics'] = '{$OPpics}';
	\$cfg['op_pre']  = '{$OPPrefix}';

	\$cfg['kill_user']   = '{$killuser}';
	\$cfg['kill_reg']    = '15';
	\$cfg['approval']    = '{$approval}';
	\$cfg['require_art'] = '{$requireart}';
	\$cfg['guest_post']  = '{$guestpost}';
	\$cfg['guest_draw']  = '{$guestdraw}'; //WIP
	\$cfg['guest_adult'] = '{$guestadult}';
	\$cfg['reg_draw']    = '{$drawaccess}';
	\$cfg['reg_anim']    = '{$animationaccess}';
	\$cfg['reg_upload']  = '{$uploadaccess}';
	\$cfg['reg_rules']   = 'no';

	\$cfg['language'] = '{$language}';
	\$cfg['template'] = '{$template}';
	\$cfg['tpl_path'] = '{$templatepath}';
	\$cfg['res_path'] = '{$resourcepath}';

	\$cfg['pic_store']  = '{$pstore}';
	\$cfg['pic_pages']  = '{$ppage}';
	\$cfg['menu_pages'] = '{$perpage}';
	\$cfg['pic_limit']      = '{$picLimit}'; //WIP
	\$cfg['pic_limit_time'] = '{$picLimitTime}'; //WIP

	\$cfg['porn_img']      = '{$CFG_pornImg}';
	\$cfg['preview_img']   = '{$CFG_previewImg}';
	\$cfg['preview_title'] = '{$CFG_previewTitle}';
	\$cfg['def_x']    = '{$xDefault}';
	\$cfg['def_y']    = '{$yDefault}';
	\$cfg['canvas_x'] = '{$xCanvas}';
	\$cfg['canvas_y'] = '{$yCanvas}';
	\$cfg['min_x']    = '{$xMin}';
	\$cfg['min_y']    = '{$yMin}';
	\$cfg['canvas_a'] = '{$aCanvas}';
	\$cfg['max_anim'] = '{$anisize}';
	\$cfg['max_pic']  = '{$max_upload}';

	\$cfg['use_thumb']   = '{$useThumb}';
	\$cfg['force_thumb'] = '{$forceThumb}';
	\$cfg['thumb_bytes'] = '{$thumbBytes}';
	\$cfg['thumb_t']     = '{$thumbT}';
	\$cfg['thumb_r']     = '{$thumbR}';
	\$cfg['thumb_jpg']   = '{$thumbJPG}';

	\$cfg['use_mailbox'] = '{$useMailbox}';

	\$cfg['use_chat']   = '{$useChat}';
	\$cfg['chat_max']   = '{$chatMax}';
	\$cfg['chat_pages'] = '{$chatDisp}';

	\$cfg['smilies'] = 'yes';
?>
EOF;


		// Write the config file
		if ($fp = fopen('config.php', 'w')) {
			fwrite($fp, $main_configuration);
			fclose($fp);
		} else {
			p_line( t('up_cant_write_file', 'config.php'));
			$cfg_update['status'] = 2;
			return 0;
		}
	}


	// Update database
	{
		// Dump oekakikill (ignore errors)
		$result = db_query("DROP TABLE {$db_mem}oekakikill");

		// Add password field
		$result = db_query("ALTER TABLE {$db_p}oekakidta ADD password text");

		// Flush default templates if using old config file
		if (isset($template) && !empty($template)) {
			$result = db_query("UPDATE {$db_mem}oekaki SET templatesel='' WHERE templatesel='$template'");
		}

		// Add ranks if they don't exist
		$result = db_query("SELECT * FROM {$db_mem}oekaki");
		$junk = db_fetch_array($result);
		if (!isset($junk['rank'])) {
			$result = db_query("ALTER TABLE {$db_mem}oekaki ADD rank TINYINT NOT NULL DEFAULT 0");

			if (!$result) {
				$cfg_update['status'] = 2;
				p_line( t('up_no_add_rank', w_html_chars( db_error() )));
				return 0;
			}
		}

		// Set ranks
		$result_names = db_query("SELECT usrname, usrflags FROM {$db_mem}oekaki");
		$all_rows = db_num_rows($result_names);
		for ($i = 0; $i < $all_rows; $i++) {
			$rank = 0;

			$row = db_fetch_array($result_names);
			if (strstr($row['usrflags'], 'A'))
				$rank = RANK_ADMIN;
			if (strstr($row['usrflags'], 'S'))
				$rank = RANK_SADMIN;
			if (strstr($row['usrflags'], 'O'))
				$rank = RANK_OWNER;
			if ($rank > 0) {
				$result = db_query("UPDATE {$db_mem}oekaki SET rank=$rank WHERE usrname='{$row['usrname']}'");

				if (!$result) {
					$cfg_update['status'] = 2;
					p_line( t('up_no_set_rank', w_html_chars($row['usrname']), w_html_chars(db_error() )));
					return 0;
				}
			}
		}

		// Done!  Update the DB version
		$result = db_query("UPDATE {$db_p}oekakimisc SET miscstring='1.2.0' WHERE miscname='dbversion'");
	}


	// Update the admin flags so only ranks are used
	$result_names = db_query("SELECT usrname, usrflags FROM {$db_mem}oekaki");
	$all_rows = db_num_rows($result_names);
	for ($i = 0; $i < $all_rows; $i++) {
		$row = db_fetch_array($result_names);

		if (strstr($row['usrflags'], 'A')) {
			$new_flags = str_replace(array('O', 'S', 'A'), '', $row['usrflags']);

			$result = db_query("UPDATE {$db_mem}oekaki SET usrflags='$new_flags' WHERE usrname='{$row['usrname']}'");
		}
	}


	// Check for rules, banner
	if (!file_exists('resource/rules.php')) {
		$fp = @fopen('resource/rules.php', 'w');
		if ($fp) {
			fwrite($fp, "<p>\n".t('defrulz')."\n</p>");
			fclose($fp);
		}
	}
	if (!file_exists('resource/banner.php')) {
		$fp = @fopen('resource/rules.php', 'w');
		fclose($fp);
	}


	// Complete!
	if ($cfg_update['status'] == 0) {
		p_line( t('up_fin_to', 'Wacintaki 1.2.0'));

		return 1;
	} else {
		return 0;
	}
}
// END wac111_to_wac120



function wac120_to_wac130() {
	global $cfg_update;
	global $dbconn, $db_p, $db_mem;

	p_line( t('up_from_to', 'Wacintaki 1.2.0', '1.3.0'));

	/* We need to do the following, in order of safety or processing time:
		1) Reset member picture counts if less than 0 (bug in 1.2.0 upload script)

		2) Create avatars folder

		3) Update database to be MySQL 5 compliant (correct default values)

		4) Add avatar field to op_oekaki, origart to op_oekakidta

		5) Update db version stamp to 1.3.0

		6) Update main piccount to lowest non-conflict value (new sorting system)

		7) Update config file to 1.3.0, and change version stamp

		8) Clean up orphaned picture/temp files leftover from Wac 1.2.x
	*/


	require 'config.php';


	// Fix member picture count
	$result = db_query("UPDATE {$db_mem}oekaki SET piccount=0 WHERE piccount < 0");

	// Add avatars folder
	if (!file_exists('avatars')) {
		if (! @mkdir('avatars', 0755)) {
			$cfg_update['status'] = 2;
			p_line( t('up_cant_make_folder', 'avatars'));
			return 0;
		}
	}
	if (!check_write('avatars', 755)) {
		$cfg_update['status'] = 1;
		p_line( t('up_folder_locked', 'avatars'));
	}


	// Update config file if not updated already
	if ($cfg['version'] === '1.2.0') {
		$BBStitle  = addslashes($cfg['op_title']);
		$BBSauthor = addslashes($cfg['op_author']);
		$CFG_previewTitle = addslashes($cfg['preview_title']);

		$main_configuration = <<<EOF
<?php // Include only
	\$cfg = array();
	\$cfg['version'] = '1.3.0';

	\$cfg['op_title']  = '{$BBStitle}';
	\$cfg['op_author'] = '{$BBSauthor}';
	\$cfg['op_adult']  = '{$cfg['op_adult']}';
	\$cfg['op_email']  = '{$cfg['op_email']}';
	\$cfg['op_url']    = '{$cfg['op_url']}';

	\$cfg['salt']    = '{$cfg['salt']}';
	\$cfg['op_pics'] = '{$cfg['op_pics']}';
	\$cfg['op_pre']  = '{$cfg['op_pre']}';

	\$cfg['kill_user']   = '{$cfg['kill_user']}';
	\$cfg['kill_reg']    = '{$cfg['kill_reg']}';
	\$cfg['approval']    = '{$cfg['approval']}';
	\$cfg['draw_immune'] = 'no';
	\$cfg['require_art'] = '{$cfg['require_art']}';
	\$cfg['guest_post']  = '{$cfg['guest_post']}';
	\$cfg['guest_draw']  = '{$cfg['guest_draw']}'; //WIP
	\$cfg['guest_adult'] = '{$cfg['guest_adult']}';
	\$cfg['reg_draw']    = '{$cfg['reg_draw']}';
	\$cfg['reg_anim']    = '{$cfg['reg_anim']}';
	\$cfg['reg_upload']  = '{$cfg['reg_upload']}';
	\$cfg['reg_rules']   = '{$cfg['reg_rules']}';

	\$cfg['language'] = '{$cfg['language']}';
	\$cfg['template'] = '{$cfg['template']}';
	\$cfg['tpl_path'] = '{$cfg['tpl_path']}';
	\$cfg['res_path'] = '{$cfg['res_path']}';

	\$cfg['pic_store']  = '{$cfg['pic_store']}';
	\$cfg['pic_pages']  = '{$cfg['pic_pages']}';
	\$cfg['menu_pages'] = '{$cfg['menu_pages']}';
	\$cfg['pic_limit']      = '{$cfg['pic_limit']}'; //WIP
	\$cfg['pic_limit_time'] = '{$cfg['pic_limit_time']}'; //WIP

	\$cfg['porn_img']      = '{$cfg['porn_img']}';
	\$cfg['preview_img']   = '{$cfg['preview_img']}';
	\$cfg['preview_title'] = '{$CFG_previewTitle}';
	\$cfg['def_x']    = '{$cfg['def_x']}';
	\$cfg['def_y']    = '{$cfg['def_y']}';
	\$cfg['canvas_x'] = '{$cfg['canvas_x']}';
	\$cfg['canvas_y'] = '{$cfg['canvas_y']}';
	\$cfg['min_x']    = '{$cfg['min_x']}';
	\$cfg['min_y']    = '{$cfg['min_y']}';
	\$cfg['canvas_a'] = '{$cfg['canvas_a']}';
	\$cfg['max_anim'] = '{$cfg['max_anim']}';
	\$cfg['max_pic']  = '{$cfg['max_pic']}';

	\$cfg['use_thumb']   = '{$cfg['use_thumb']}';
	\$cfg['force_thumb'] = '{$cfg['force_thumb']}';
	\$cfg['thumb_bytes'] = '{$cfg['thumb_bytes']}';
	\$cfg['thumb_t']     = '{$cfg['thumb_t']}';
	\$cfg['thumb_r']     = '{$cfg['thumb_r']}';
	\$cfg['thumb_jpg']   = '{$cfg['thumb_jpg']}';

	\$cfg['use_mailbox'] = '{$cfg['use_mailbox']}';

	\$cfg['use_chat']   = '{$cfg['use_chat']}';
	\$cfg['chat_max']   = '{$cfg['chat_max']}';
	\$cfg['chat_pages'] = '{$cfg['chat_pages']}';

	\$cfg['smilies'] = 'yes';

	\$cfg['use_avatars']   = 'yes';
	\$cfg['use_c_avatars'] = 'no';
	\$cfg['avatar_folder'] = 'avatars';
	\$cfg['avatar_x'] = '50';
	\$cfg['avatar_y'] = '50';

	\$cfg['public_retouch']   = 'yes';
	\$cfg['safety_storetime'] = '10';
	\$cfg['safety_saves']     = 'yes';
	\$cfg['self_bump']        = 'yes';
?>
EOF;


		// Write the config file
		if ($fp = fopen('config.php', 'w')) {
			fwrite($fp, $main_configuration);
			fclose($fp);
		} else {
			$cfg_update['status'] = 2;
			p_line( t('up_cant_write_file', 'config.php'));
			return 0;
		}
	}


	// Update database
	$result = db_query("SELECT miscstring FROM {$db_p}oekakimisc WHERE miscname='dbversion'");
	if ($result && db_result($result, 0) === '1.2.0') {

		// Update default values for MySQL 5
		// MySQL 5 now enforces the rule that any field with a "NOT NULL"
		//   clause *must* have a default value

		$q   = array();
		$q[] = "{$db_mem}oekaki CHANGE templatesel templatesel varchar(255) NOT NULL DEFAULT ''";
		$q[] = "{$db_mem}oekakichat CHANGE comment comment TINYtext";
		$q[] = "{$db_mem}oekakichat CHANGE hostname hostname text";
		$q[] = "{$db_mem}oekakichat CHANGE `IP` `IP` text";
		$q[] = "{$db_mem}oekakionline CHANGE onlineIP onlineIP text";
		$q[] = "{$db_mem}oekakionline CHANGE onlineboard onlineboard text";
		$q[] = "{$db_mem}oekakimailbox CHANGE subject subject text";
		$q[] = "{$db_mem}oekakimailbox CHANGE body body LONGtext";
		$q[] = "{$db_mem}oekakichatmod CHANGE comment comment TINYtext";
		$q[] = "{$db_mem}oekakichatmod CHANGE hostname hostname text";
		$q[] = "{$db_mem}oekakichatmod CHANGE `IP` `IP` text";
		$q[] = "{$db_mem}oekakichatmodusers CHANGE usrname usrname VARCHAR(255) NOT NULL DEFAULT ''";

		$q[] = "{$db_p}oekakicmt CHANGE comment comment text";
		$q[] = "{$db_p}oekakicmt CHANGE hostname hostname text";
		$q[] = "{$db_p}oekakicmt CHANGE `IP` `IP` text";
		$q[] = "{$db_p}oekakidta CHANGE comment comment text";
		$q[] = "{$db_p}oekakidta CHANGE hostname hostname text";
		$q[] = "{$db_p}oekakidta CHANGE `IP` `IP` text";
		$q[] = "{$db_p}oekakidta CHANGE password password text";

		foreach ($q as $query) {
			$result = db_query('ALTER TABLE '.$query);
			sql_check($result, 'MySQL5 Fix');
		}


		// Add avatar field
		$result = db_query("ALTER TABLE {$db_mem}oekaki ADD avatar VARCHAR(128) NOT NULL DEFAULT ''");

		// Add origart field
		$result = db_query("ALTER TABLE {$db_p}oekakidta ADD origart VARCHAR(255) NOT NULL DEFAULT ''");

		// Done!  Update the DB version
		$result = db_query("UPDATE {$db_p}oekakimisc SET miscstring='1.3.0' WHERE miscname='dbversion'");
	}


	// Update piccount for new sorting system (about time)
	$new_piccount = 0;
	$result = db_query("SELECT MAX(PIC_ID) FROM {$db_p}oekakidta");
	if ($result)
		$new_piccount = (int) db_result($result, 0);

	if ($new_piccount == 0)
		$new_piccount = $cfg['pic_store'];

	$result = db_query("UPDATE {$db_p}oekakimisc SET miscvalue={$new_piccount} WHERE miscname='piccount'");
	if (!$result) {
		$cfg_update['status'] = 2;
		p_line( t('up_no_piccount', w_html_chars(db_error() )));
		return 0;
	}


	// Clean orphaned images, if any.  Discard return code
	clean_images($cfg_update['debug']);


	// Complete!
	if ($cfg_update['status'] == 0) {
		p_line( t('up_fin_to', 'Wacintaki 1.3.0'));

		return 1;
	} else {
		return 0;
	}
}
// END wac120_to_wac130



function wax560_to_wac130() {
	global $cfg_update;
	global $dbconn, $db_p, $db_mem;

	p_line( t('up_from_to', 'Wax Poteto 5.6.0', 'Wacintaki 1.3.0'));

	/* Wax Poteto 5.6.0 now uses the same DB/config as Wac 1.3.x
		1) Verify that the database has a version marker (5.6.0 oversight)

		2) Verify that the database is at version 1.3.0

		3) Update config file to say '1.3.0' instead of '5.6.0'

		4) Remove old Wax files
	*/


	require 'config.php';


	// Check DB version marker
	$result = db_query("SELECT * FROM {$db_p}oekakimisc");
	$rows = (int) db_num_rows($result);
	if ($result && $rows > 0) {
		if ($rows < 2) {
			// No miscstring for older than 5.6.1!  Add it.
			$result = db_query("ALTER TABLE {$db_p}oekakimisc ADD miscstring VARCHAR(14)");
			$result = db_query("INSERT INTO {$db_p}oekakimisc VALUES (2, 'dbversion', 0, '1.3.0')");
		} else {
			// Make sure DB is 1.3.0 (both update and verify)
			$result = db_query("SELECT miscstring FROM {$db_p}oekakimisc WHERE miscname='dbversion'");
			if (db_result($result, 0) != '1.3.0') {
				$cfg_update['status'] = 2;
				p_line( t('up_wac_no_130'));
				return 0;
			}
		}
	} else {
		$cfg_update['status'] = 2;
		p_line( t('up_no_set_db_utf', w_html_chars(db_error() )));
		return 0;
	}


	// Update config file
	$BBStitle = addslashes($cfg['op_title']);

	$main_configuration = <<<EOF
<?php // Include only
	\$cfg = array();
	\$cfg['version'] = '1.3.0';

	\$cfg['op_title']  = '{$BBStitle}';
	\$cfg['op_author'] = '';
	\$cfg['op_adult']  = '{$cfg['op_adult']}';
	\$cfg['op_email']  = '{$cfg['op_email']}';
	\$cfg['op_url']    = '{$cfg['op_url']}';

	\$cfg['salt']    = '{$cfg['salt']}';
	\$cfg['op_pics'] = '{$cfg['op_pics']}';
	\$cfg['op_pre']  = '';

	\$cfg['kill_user']   = '{$cfg['kill_user']}';
	\$cfg['kill_reg']    = '{$cfg['kill_reg']}';
	\$cfg['approval']    = '{$cfg['approval']}';
	\$cfg['draw_immune'] = 'no';
	\$cfg['require_art'] = '{$cfg['require_art']}';
	\$cfg['guest_post']  = '{$cfg['guest_post']}';
	\$cfg['guest_draw']  = '{$cfg['guest_draw']}'; //WIP
	\$cfg['guest_adult'] = 'no';
	\$cfg['reg_draw']    = '{$cfg['reg_draw']}';
	\$cfg['reg_anim']    = '{$cfg['reg_anim']}';
	\$cfg['reg_upload']  = '{$cfg['reg_upload']}';
	\$cfg['reg_rules']   = 'no';

	\$cfg['language'] = 'english';
	\$cfg['template'] = '{$cfg['template']}';
	\$cfg['tpl_path'] = 'templates';
	\$cfg['res_path'] = 'resource';

	\$cfg['pic_store']  = '{$cfg['pic_store']}';
	\$cfg['pic_pages']  = '{$cfg['pic_pages']}';
	\$cfg['menu_pages'] = '{$cfg['menu_pages']}';
	\$cfg['pic_limit']      = '{$cfg['pic_limit']}'; //WIP
	\$cfg['pic_limit_time'] = '{$cfg['pic_limit_time']}'; //WIP

	\$cfg['porn_img']      = '{$cfg['porn_img']}';
	\$cfg['preview_img']   = '{$cfg['preview_img']}';
	\$cfg['preview_title'] = 'Preview';
	\$cfg['def_x']    = '{$cfg['def_x']}';
	\$cfg['def_y']    = '{$cfg['def_y']}';
	\$cfg['canvas_x'] = '{$cfg['canvas_x']}';
	\$cfg['canvas_y'] = '{$cfg['canvas_y']}';
	\$cfg['min_x']    = '{$cfg['min_x']}';
	\$cfg['min_y']    = '{$cfg['min_y']}';
	\$cfg['canvas_a'] = '{$cfg['canvas_a']}';
	\$cfg['max_anim'] = '{$cfg['max_anim']}';
	\$cfg['max_pic']  = '{$cfg['max_pic']}';

	\$cfg['use_thumb']   = '{$cfg['use_thumb']}';
	\$cfg['force_thumb'] = '{$cfg['force_thumb']}';
	\$cfg['thumb_bytes'] = '{$cfg['thumb_bytes']}';
	\$cfg['thumb_t']     = '{$cfg['thumb_t']}';
	\$cfg['thumb_r']     = '{$cfg['thumb_r']}';
	\$cfg['thumb_jpg']   = '{$cfg['thumb_jpg']}';

	\$cfg['use_mailbox'] = '{$cfg['use_mailbox']}';

	\$cfg['use_chat']   = '{$cfg['use_chat']}';
	\$cfg['chat_max']   = '{$cfg['chat_max']}';
	\$cfg['chat_pages'] = '{$cfg['chat_pages']}';

	\$cfg['smilies'] = '{$cfg['smilies']}';

	\$cfg['use_avatars']   = '{$cfg['use_avatars']}';
	\$cfg['use_c_avatars'] = '{$cfg['use_c_avatars']}';
	\$cfg['avatar_folder'] = '{$cfg['avatar_folder']}';
	\$cfg['avatar_x'] = '50';
	\$cfg['avatar_y'] = '50';

	\$cfg['public_retouch']   = 'yes';
	\$cfg['safety_storetime'] = '10';
	\$cfg['safety_saves']     = 'yes';
	\$cfg['self_bump']        = 'yes';
?>
EOF;


	// Write the config file
	if ($fp = fopen('config.php', 'w')) {
		fwrite($fp, $main_configuration);
		fclose($fp);
	} else {
		$cfg_update['status'] = 2;
		p_line( t('up_cant_write_file', 'config.php'));
		return 0;
	}

	// Notice to move resource files from root to /resource
	p_line( t('up_move_res'));

	// Complete!
	p_line( t('up_fin_to', 'Wacintaki 1.3.0'));
	return 1;
}
// END wax560_to_wac130



function wac130_to_wac1312() {
	global $cfg_update;
	global $dbconn, $db_p, $db_mem;

	p_line( t('up_from_to', 'Wacintaki 1.3.0', '1.3.12'));

	/* We need to do the following, in order of safety or processing time:
		1) Add database fields to op_oekaki: group_id, email_show, smilies_show, timezone, links

		2) Add database op_oekakilog: board, category, value

		3) Update db version stamp to 1.3.12

		4) Fix member picture count: reset if < 0
	*/


	require 'config.php';


	// Check DB version marker (flaw with Wax Poteto updater)
	$result = db_query("SELECT * FROM {$db_p}oekakimisc");
	$rows = (int) db_num_rows($result);
	if ($result && $rows > 0) {
		if ($rows < 2) {
			// No miscstring!  Rare bug when updating some Wax 5.6.0 boards to Wacintaki 1.3.0.
			$result = db_query("ALTER TABLE {$db_p}oekakimisc ADD miscstring VARCHAR(14)");
			$result = db_query("INSERT INTO {$db_p}oekakimisc VALUES (2, 'dbversion', 0, '1.3.0')");
		}
	} else {
		$cfg_update['status'] = 2;
		p_line( t('up_no_set_db_utf', w_html_chars(db_error() )));
		return 0;
	}


	// Update database
	$result = db_query("SELECT miscstring FROM {$db_p}oekakimisc WHERE miscname='dbversion'");
	if (db_result($result, 0) !== '1.3.12') {
		$q   = array();
		$q[] = "ALTER TABLE {$db_mem}oekaki ADD `group_id` MEDIUMINT DEFAULT 0";
		$q[] = "ALTER TABLE {$db_mem}oekaki ADD `email_show` TINYINT(3) DEFAULT 1";
		$q[] = "ALTER TABLE {$db_mem}oekaki ADD `smilies_show` TINYINT(3) DEFAULT 1";
		$q[] = "ALTER TABLE {$db_mem}oekaki ADD `timezone` FLOAT NOT NULL DEFAULT 0";
		$q[] = "ALTER TABLE {$db_mem}oekaki ADD `links` TEXT";

		$q[] = "CREATE TABLE IF NOT EXISTS {$db_mem}oekakilog (`ID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, `category` VARCHAR(255) NOT NULL DEFAULT '', `member` VARCHAR(255) NOT NULL DEFAULT '', `affected` VARCHAR(255), `value` TEXT, `board` VARCHAR(255) NOT NULL DEFAULT '', `date` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00', PRIMARY KEY (ID)) TYPE=MyISAM";

		foreach ($q as $query) {
			$result = db_query($query);
			sql_check($result);
		}

		// Done!  Update the DB version
		$result = db_query("UPDATE {$db_p}oekakimisc SET miscstring='1.3.12' WHERE miscname='dbversion'");
	}

	// Fix member picture count(bug causing this finally fixed in 1.3.12)
	$result = db_query("UPDATE {$db_mem}oekaki SET piccount=0 WHERE piccount < 0");

	// Complete!
	p_line( t('up_fin_to', 'Wacintaki 1.3.12'));
	return 1;
}
// END wac130_to_wac1312



function wac1312_to_wac142() {
	global $cfg_update;
	global $dbconn, $db_p, $db_mem;

	p_line( t('up_from_to', 'Wacintaki 1.3.12+', '1.4.2'));

	/* We need to do the following, in order of safety or processing time:
		1) Add no_viewer column to member table

		3) Update db version stamp to 1.4.2
	*/


	require 'config.php';


	// Check DB version marker (flaw with Wax Poteto updater)
	$result = db_query("SELECT * FROM {$db_p}oekakimisc");
	$rows = (int) db_num_rows($result);
	if ($result && $rows > 0) {
		if ($rows < 2) {
			// No miscstring!  Rare bug when updating some Wax 5.6.0 boards to Wacintaki 1.3.0.
			$result = db_query("ALTER TABLE {$db_p}oekakimisc ADD miscstring VARCHAR(14)");
			$result = db_query("INSERT INTO {$db_p}oekakimisc VALUES (2, 'dbversion', 0, '1.3.12')");
		}
	} else {
		$cfg_update['status'] = 2;
		p_line( t('up_no_set_db_utf', w_html_chars(db_error() )));
		return 0;
	}


	// Update database
	$result = db_query("SELECT miscstring FROM {$db_p}oekakimisc WHERE miscname='dbversion'");
	if (db_result($result, 0) !== '1.4.2') {
		$result = db_query("ALTER TABLE {$db_mem}oekaki ADD `no_viewer` TINYINT(3) DEFAULT 0");
		sql_check($result);

		// Done!  Update the DB version
		$result = db_query("UPDATE {$db_p}oekakimisc SET miscstring='1.4.2' WHERE miscname='dbversion'");
	}

	// Complete!
	p_line( t('up_fin_to', 'Wacintaki 1.4.2'));

	return 1;
}
// END wac1312_to_wac142



function wac142_to_wac143() {
	global $cfg_update;
	global $dbconn, $db_p, $db_mem;

	p_line( t('up_from_to', 'Wacintaki 1.4.2', '1.4.3'));

	/* We need to do the following, in order of safety or processing time:
		1) Add IP column to member table

		2) Update db version stamp to 1.4.3
	*/


	require 'config.php';


	// Update database
	$result = db_query("SELECT miscstring FROM {$db_p}oekakimisc WHERE miscname='dbversion'");
	if (db_result($result, 0) !== '1.4.3') {
		$result = db_query("ALTER TABLE {$db_mem}oekaki ADD `IP` TEXT DEFAULT NULL");
		sql_check($result);

		// Done!  Update the DB version
		$result = db_query("UPDATE {$db_p}oekakimisc SET miscstring='1.4.3' WHERE miscname='dbversion'");
	}

	// Complete!
	p_line( t('up_fin_to', 'Wacintaki 1.4.3'));

	return 1;
}
// END wac142_to_wac143



function wac143_to_wac150() {
	global $cfg_update;
	global $dbconn, $db_p, $db_mem;
	global $dbhost, $dbuser, $dbpass, $dbname;

	p_line( t('up_from_to', 'Wacintaki 1.4.3', '1.5.0'));

	/* We need to do the following, in order of safety or processing time:
		1) Update database config file (dbconn.php) to 1.5.0
	*/

	$dbhost_alt = addslashes($dbhost);
	$dbuser_alt = addslashes($dbuser);
	$dbpass_alt = addslashes($dbpass);
	$dbname_alt = addslashes($dbname);
	$db_p_alt   = addslashes($db_p);
	$db_mem_alt = addslashes($db_mem);

$db_configuration = <<<EOF
<?php // Include only
	\$cfg_db = array();
	\$cfg_db['version'] = '1.5.0';

	\$dbhost = '{$dbhost_alt}';
	\$dbuser = '{$dbuser_alt}';
	\$dbpass = '{$dbpass_alt}';
	\$dbname = '{$dbname_alt}';

	\$OekakiPoteto_Prefix = '{$db_p_alt}';
	\$OekakiPoteto_MemberPrefix = '{$db_mem_alt}';
EOF;


	// Write the config file
	if ($fp = fopen('dbconn.php', 'w')) {
		fwrite($fp, $db_configuration);
		fclose($fp);
	} else {
		$cfg_update['status'] = 2;
		p_line( t('up_cant_write_file', 'dbconn.php'));
		return 0;
	}

	// Done!  Always update the DB version
	$result = db_query("UPDATE {$db_p}oekakimisc SET miscstring='1.5.0' WHERE miscname='dbversion'");

	// Delete
	delete_obsolete_files(true);

	// Complete!
	p_line( t('up_fin_to', 'Wacintaki 1.5.0'));

	return 1;
}
// END wac143_to_wac150



function wac150_to_wac155() {
	global $cfg_update;
	global $dbconn, $db_p, $db_mem;

	p_line( t('up_from_to', 'Wacintaki 1.5.0', '1.5.5'));

	/* We need to do the following, in order of safety or processing time:
		1) Update config file to 1.5.5
	*/


	require 'config.php';


	// Update config file
	$BBStitle  = addslashes($cfg['op_title']);
	$BBSauthor = addslashes($cfg['op_author']);
	$preview_t = addslashes($cfg['preview_title']);

	// Declare any missing values
	if (!isset($cfg['humanity_post'])) {
		$cfg['humanity_post'] = 'yes';
	}
	if (!isset($cfg['use_viewer'])) {
		$cfg['use_viewer'] = '1';
	}
	$cfg['cut_email'] = '0';
	$cfg['safety_max'] = '2';


	// Process hacks file
	if (file_exists($cfg['res_path'].'/hacks.php')) {
		$hacks = file_get_contents($cfg['res_path'].'/hacks.php');

		// Find CUT_EMAIL
		if (preg_match('!\'CUT_EMAIL\',\s*([0-9]+)\);!', $hacks, $match)) {
			$cfg['cut_email'] = "{$match[1]}";
		}

		// Find ALLOW_ADDITIONAL_WIPS
		if (preg_match('!\'ALLOW_ADDITIONAL_WIPS\',\s*([0-9]+)\);!', $hacks, $match)) {
			$match[1] = 1 + intval($match[1]);
			$cfg['safety_max'] = "{$match[1]}";
		}
	}


	$main_configuration = <<<EOF
<?php // Include only
	\$cfg = array();
	\$cfg['version'] = '1.5.5';

	\$cfg['op_title']  = '{$BBStitle}';
	\$cfg['op_author'] = '{$BBSauthor}';
	\$cfg['op_adult']  = '{$cfg['op_adult']}';
	\$cfg['op_email']  = '{$cfg['op_email']}';
	\$cfg['op_url']    = '{$cfg['op_url']}';
	\$cfg['cut_email'] = '0';

	\$cfg['salt']    = '{$cfg['salt']}';
	\$cfg['op_pics'] = '{$cfg['op_pics']}';
	\$cfg['op_pre']  = '{$cfg['op_pre']}';

	\$cfg['kill_user']     = '{$cfg['kill_user']}';
	\$cfg['kill_reg']      = '{$cfg['kill_reg']}';
	\$cfg['draw_immune']   = '{$cfg['draw_immune']}';
	\$cfg['private']       = 'no';
	\$cfg['approval']      = '{$cfg['approval']}';
	\$cfg['require_art']   = '{$cfg['require_art']}';
	\$cfg['guest_post']    = '{$cfg['guest_post']}';
	\$cfg['guest_draw']    = '{$cfg['guest_draw']}'; //WIP
	\$cfg['guest_adult']   = '{$cfg['guest_adult']}';
	\$cfg['humanity_post'] = '{$cfg['humanity_post']}';
	\$cfg['reg_draw']      = '{$cfg['reg_draw']}';
	\$cfg['reg_anim']      = '{$cfg['reg_anim']}';
	\$cfg['reg_upload']    = '{$cfg['reg_upload']}';
	\$cfg['reg_rules']     = '{$cfg['reg_rules']}';
	\$cfg['censoring']     = 'no';

	\$cfg['language'] = '{$cfg['language']}';
	\$cfg['template'] = '{$cfg['template']}';
	\$cfg['tpl_path'] = '{$cfg['tpl_path']}';
	\$cfg['res_path'] = '{$cfg['res_path']}';

	\$cfg['pic_store']  = '{$cfg['pic_store']}';
	\$cfg['pic_pages']  = '{$cfg['pic_pages']}';
	\$cfg['menu_pages'] = '{$cfg['menu_pages']}';
	\$cfg['pic_limit']      = '{$cfg['pic_limit']}'; //WIP
	\$cfg['pic_limit_time'] = '{$cfg['pic_limit_time']}'; //WIP
	\$cfg['use_viewer'] = '{$cfg['use_viewer']}';

	\$cfg['porn_img']      = '{$cfg['porn_img']}';
	\$cfg['preview_img']   = '{$cfg['preview_img']}';
	\$cfg['preview_title'] = '{$preview_t}';
	\$cfg['def_x']    = '{$cfg['def_x']}';
	\$cfg['def_y']    = '{$cfg['def_y']}';
	\$cfg['canvas_x'] = '{$cfg['canvas_x']}';
	\$cfg['canvas_y'] = '{$cfg['canvas_y']}';
	\$cfg['min_x']    = '{$cfg['min_x']}';
	\$cfg['min_y']    = '{$cfg['min_y']}';
	\$cfg['canvas_a'] = '{$cfg['canvas_a']}';
	\$cfg['max_anim'] = '{$cfg['max_anim']}';
	\$cfg['max_pic']  = '{$cfg['max_pic']}';

	\$cfg['use_thumb']   = '{$cfg['use_thumb']}';
	\$cfg['force_thumb'] = '{$cfg['force_thumb']}';
	\$cfg['thumb_bytes'] = '{$cfg['thumb_bytes']}';
	\$cfg['thumb_t']     = '{$cfg['thumb_t']}';
	\$cfg['thumb_r']     = '{$cfg['thumb_r']}';
	\$cfg['thumb_jpg']   = '{$cfg['thumb_jpg']}';

	\$cfg['use_mailbox'] = '{$cfg['use_mailbox']}';

	\$cfg['use_chat']   = '{$cfg['use_chat']}';
	\$cfg['chat_max']   = '{$cfg['chat_max']}';
	\$cfg['chat_pages'] = '{$cfg['chat_pages']}';

	\$cfg['smilies'] = '{$cfg['smilies']}';

	\$cfg['use_avatars']   = '{$cfg['use_avatars']}';
	\$cfg['use_c_avatars'] = '{$cfg['use_c_avatars']}';
	\$cfg['avatar_folder'] = '{$cfg['avatar_folder']}';
	\$cfg['avatar_x'] = '{$cfg['avatar_x']}';
	\$cfg['avatar_y'] = '{$cfg['avatar_y']}';

	\$cfg['public_retouch']   = '{$cfg['public_retouch']}';
	\$cfg['safety_saves']     = '{$cfg['safety_saves']}';
	\$cfg['safety_max']       = '2';
	\$cfg['safety_storetime'] = '{$cfg['safety_storetime']}';
	\$cfg['self_bump']        = '{$cfg['self_bump']}';
EOF;


	// Write the config file
	if ($fp = fopen('config.php', 'w')) {
		fwrite($fp, $main_configuration);
		fclose($fp);
	} else {
		p_line( t('up_cant_write_file', 'config.php'));
		$cfg_update['status'] = 2;
		return 0;
	}

	// Done!  Always update the DB version
	$result = db_query("UPDATE {$db_p}oekakimisc SET miscstring='1.5.5' WHERE miscname='dbversion'");

	// Delete
	delete_obsolete_files(true);

	// Complete!
	p_line( t('up_fin_to', 'Wacintaki 1.5.5'));

	return 1;
}
// END wac150_to_wac155



function wac155_to_wac156() {
	global $cfg_update;
	global $dbconn, $dbuser, $dbpass, $dbhost, $dbname, $db_p, $db_mem;

	p_line( t('up_from_to', 'Wacintaki 1.5.5', '1.5.6'));

	/* We need to do the following, in order of safety or processing time:
		1) Redirect to update_rc.php
		2) Set db_set_charset() to utf8
		3) Update dbconn file to 1.5.6
	*/


	// Check UTF-8 compliance marker
	// 1=parital conversion, 2=converted, 3=installed
	$result = db_query("SELECT miscvalue FROM {$db_p}oekakimisc WHERE miscname='db_utf8'");
	if ($result) {
		if (db_num_rows($result) < 1 || db_result($result) < 1) {
			p_line( t('up_change_sum'));
			p_line('<a href="update_rc.php">'.t('up_click_start_conv').'</a>');

			print_footer();
			w_exit();
		}
	} else {
		$cfg_update['status'] = 2;
		p_line( t('up_no_dbutf_marker'));
		return 0;
	}


	require 'config.php';

	$dbhost_alt = addslashes($dbhost);
	$dbuser_alt = addslashes($dbuser);
	$dbpass_alt = addslashes($dbpass);
	$dbname_alt = addslashes($dbname);
	$db_p_alt   = addslashes($db_p);
	$db_mem_alt = addslashes($db_mem);

$db_configuration = <<<EOF
<?php // Include only
	\$cfg_db = array();
	\$cfg_db['version'] = '1.5.6';

	// Use MySQL recognized charset here
	\$cfg_db['set_charset'] = 'utf8';

	\$dbhost = '{$dbhost_alt}';
	\$dbuser = '{$dbuser_alt}';
	\$dbpass = '{$dbpass_alt}';
	\$dbname = '{$dbname_alt}';

	\$OekakiPoteto_Prefix = '{$db_p_alt}';
	\$OekakiPoteto_MemberPrefix = '{$db_mem_alt}';
EOF;

	// Write the config file
	if ($fp = fopen('dbconn.php', 'w')) {
		fwrite($fp, $db_configuration);
		fclose($fp);
	} else {
		$cfg_update['status'] = 2;
		p_line( t('up_cant_write_file', 'dbconn.php'));
		return 0;
	}

	// Add new columns (edited, editedby)
	$result = db_query("SELECT miscstring FROM {$db_p}oekakimisc WHERE miscname='dbversion'");
	if (db_result($result, 0) !== '1.5.6') {
		$fail = 0;

		$result = db_query("ALTER TABLE {$db_p}oekakidta ADD `edited` DATETIME");
		$fail += sql_check($result, 'oekakidta.edited');

		$result = db_query("ALTER TABLE {$db_p}oekakidta ADD `editedby` VARCHAR(255)");
		$fail += sql_check($result, 'oekakidta.editedby');

		$result = db_query("ALTER TABLE {$db_p}oekakidta ADD `uploaded` TINYINT NOT NULL DEFAULT 0");
		$fail += sql_check($result, 'oekakidta.uploaded');

		$result = db_query("ALTER TABLE {$db_p}oekakicmt ADD `edited` DATETIME");
		$fail += sql_check($result, 'oekakicmt.edited');

		$result = db_query("ALTER TABLE {$db_p}oekakicmt ADD `editedby` VARCHAR(255)");
		$fail += sql_check($result, 'oekakicmt.editedby');

		$result = db_query("CREATE TABLE IF NOT EXISTS `{$db_p}oekakicache` (
			`name` VARCHAR(50) NOT NULL,
			`built` INT(10) UNSIGNED NOT NULL DEFAULT 0,
			`expires` INT UNSIGNED NOT NULL DEFAULT 0,
			`data` TEXT,
			PRIMARY KEY (`name`),
			UNIQUE KEY `name` (`name`)
			) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		$fail += sql_check($result, 'cache');

		if ($fail) {
			$cfg_update['status'] = 2;
			return 0;
		}


		// Done!  Update the DB version
		$result = db_query("UPDATE {$db_p}oekakimisc SET miscstring='1.5.6' WHERE miscname='dbversion'");


		// Recode the resource files
		$my_files = array('banner.php', 'notice.php', 'rules.php');
		foreach ($my_files as $my_file) {
			$my_file = $cfg['res_path'].'/'.$my_file;
			if (file_not_empty($my_file)) {
				$content = @file_get_contents($my_file);
				if ($content && !check_utf8_valid ($content)) {

					// Got our file, and it's not UTF-8.  How to recode?
					if ($cfg['language'] === 'chinese') {
						// BIG5
						if (extension_loaded('iconv')) {
							$content = iconv ('big5', 'UTF-8//IGNORE', $content);
							@file_put_contents($my_file, $content);
						}
					} else {
						// ISO-8859-{hopefully 1}
						$content = utf8_encode($content);
						@file_put_contents($my_file, $content);
					}
				}
			}
		}
	}


	// Delete
	delete_obsolete_files();

	// Complete!
	p_line( t('up_fin_to', 'Wacintaki 1.5.6'));

	return 1;
}
// END wac155_to_wac156



// -------------------------------------------------------------------
// Functions
// -------------------------------------------------------------------


// db_error() helper function
//
// Returns 1 on error, 0 on success
// Use $error += sql_check() to count errors
function sql_check($result, $extra = '') {
	if (!$result) {
		$error = db_error();
		if (!empty($extra)) {
			$error .=' ['.$extra.']';
		}
		p_line($error);
		return 1;
	}
	return 0;
}


// Forces file to be writable
// Returns FALSE bad input or missing file, 0 failure, 1 success
// INPUT: $filename, int CHMOD
function check_write($in, $mod = 755) {
	if ($mod == 0 || empty($in)) {
		// Bad input
		return false;
	}
	$mod = sprintf("%04d", $mod);

	if (!file_exists($in)) {
		return false;
	}

	if (!is_writable($in)) {
		if (! @chmod($in, $mod)) {
			return 0;
		}
	}
	return 1;
}


// get_dir_list: collects all files w/specific file extension(s)
// returns array(), FALSE
function get_dir_list($dir, $extensions = '') {
	$files = array();

	if (empty($extensions)) {
		return scandir($dir);
	}

	$dh = opendir($dir);
	if (!$dh) return false;

	while (false !== ($filename = readdir($dh))) {
		if (empty($extensions)) {
			$files[] = $filename;
		} else {
			foreach ($extensions as $ext) {
				if (strpos($filename, '.'.$ext) !== false) {
					$files[] = $filename;
				}
			}
		}
	}

	closedir($dh);
	return $files;
}


// clean_images():
// returns TRUE, or string error
function clean_images($debug = false) {
	global $cfg;
	global $dbconn, $db_p, $db_mem;
	global $cfg_update;

	$pre = $cfg['op_pre'];
	$extensions = array('png', 'gif', 'jpg', 'pch', 'oeb', 'chi', 'tmp');
	$files      = array(); // Filenames
	$good_files = array(); // Flags for good files (TRUE/FALSE)
	$deleted_files = 0;


	// Get list of files, by matching types
	$files = get_dir_list($cfg['op_pics'], $extensions);

	if (!$files) return 'No matching files';

	// Set all file flags to bad
	// NOTE: $file_count is used later and is always an array
	$file_count = count($files);
	for ($i = 0; $i < $file_count; $i++) {
		$good_files[$i] = false;
	}

	if ($debug) {
		echo "<p>\n";
		web_line('Started cleaning of orphaned files.');
	}

	// Get each row from DB, and check what's present
	$result = db_query("SELECT ID_2, PIC_ID, ptype, ttype, rtype, animation, datatype FROM {$db_p}oekakidta");
	if (!$result) return 'DB ('.db_error().')';

	$total_rows = db_num_rows($result);

	if ($debug) {
		web_line($file_count.' total files.');
		web_line($total_rows.' total rows in db.');
	}

	for ($i = 0; $i < $total_rows; $i++) {
		$row = db_fetch_array($result);

		// Build list of files to check
		$pic = array();
		if (!empty($row['ptype']))
			$pic[] = $pre.$row['PIC_ID'].'.'.$row['ptype'];
		if (!empty($row['ttype']))
			$pic[] = $pre.'t'.$row['PIC_ID'].'.'.$row['ttype'];
		if (!empty($row['rtype']))
			$pic[] = $pre.'r'.$row['PIC_ID'].'.'.$row['rtype'];
		if ($row['animation']) {
			if ($row['datatype'] == 0 || $row['datatype'] == 2 || $row['datatype'] == 3) {
				$pic[] = $pre.$row['PIC_ID'].'.pch';
			}
			if ($row['datatype'] == 1) {
				$pic[] = $pre.$row['PIC_ID'].'.oeb';
			}
		}

		// Search for files
		foreach ($pic as $item) {
			// Each database item ($item)
			for ($j = 0; $j < $file_count; $j++) {
				// Each cached file ($files)
				if (strtolower($files[$j]) == strtolower($item)) {
					// Found file!
					$good_files[$j] = true;
					break;
				}
			}
		}
	}

	// Delete files without a good flag
	for ($i = 0; $i < $file_count; $i++) {
		if ($good_files[$i] === false) {
			// Bad file
			if ($debug) {
				web_line($files[$i].': DELETE');
			} else {
				@unlink($cfg['op_pics'].'/'.$files[$i]);
			}
			$deleted_files++;
		}
	}

	if ($debug) {
		web_line('Finished cleaning orphaned files.');
		echo "</p>\n";
	}
	if ($deleted_files > 0 || $debug) {
		p_line( t('up_cleaned_sum', $deleted_files));
	}

	return true;
}

function delete_obsolete_files($everything = false) {
	$list = array(
		'viewanis.php', 'viewanio.php', 'wax_login.php', 'update_rc.php', 'getfirefox.gif'
	);
	$list2 = array(
		'announce.php', 'arecover.php', 'button.gif', 'compress.php', 'editbanner.php', 'editnews.php', 'flagchk.php', 'ldelpics.php', 'MgLine.zip', 'news.php', 'noteBBSa.php', 'oekakiBBSa.php', 'online.php', 'PCHViewer.jar', 'README.txt', 'redirect.php', 'res.zip', 'tdesign.php', 'tedit.php', 'tedita.php', 'template.php', 'test.php', 'tnew.php', 'tnewa.php', 'tt.zip', 'versionchk.php', 'version.php'
	);

	if ($everything) {
		$list = array_merge($list, $list2);
	}

	foreach ($list as $item) {
		if (file_exists($item)) {
			@unlink($item);
		}
	}

}

function parse_config_file($file) {
	// Returns keyed array of all key/val pairs in a config file.
	// Regex whitelist method, works on servers with "eval" disabled.

	// Normal vars
	$pattern_var1_val2 = 
		'!^\W*\$([A-Za-z0-9_]+)\s*=\s*[\'|"](.+)[\'|"];!';

	// Keyed vars
	$pattern_var1_key2_val3 =
		'!^\W*\$([A-Za-z0-9_]+)\[[\'|"]([A-Za-z0-9_]+)[\'|"]\]\s*=\s*[\'|"](.+)[\'|"];!';

	$parsed = array();
	if (file_exists($file) && filesize($file) > 0) {
		$handle = @fopen($file, 'r');
		if (!$handle) {
			trigger_error("parse_config_file: cannot open {$file} for reading", E_USER_NOTICE);
			return false;
		}

		while (!feof($handle)) {
			$buffer = trim(fgets($handle, 4096));

			if (preg_match($pattern_var1_val2, $buffer, $match)) {
				// Normal variable
				$parsed[$match[1]] = $match[2];
			}
			elseif (preg_match($pattern_var1_key2_val3, $buffer, $match)) {
				// Keyed variable
				$parsed[$match[1]][$match[2]] = $match[3];
			}
		}

		fclose($handle);
	} else {
		return false;
	}

	if (!empty($parsed)) {
		return $parsed;
	} else {
		return false;
	}
}

function get_sql_columns($table) {
	global $db_mem, $db_p;

	if (empty($table) || strpos($table, $db_mem) === false || strpos($table, $db_p) === false) {
		db_close();
		trigger_error('get_sql_columns: table prefix missing in '.$table, E_USER_ERROR);
	}

	$columns = array();
	$result = db_query('SHOW COLUMNS FROM '.$table);
	while ($row = db_fetch_array($result)) {
		$columns[] = $row['Field'];
	}

	db_free_result($result);
	return $columns;
}