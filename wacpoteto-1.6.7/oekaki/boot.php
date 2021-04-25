<?php // Include only
if (!defined('BOOT')) exit('No bootstrap.  Exiting.');

/*
Wacintaki Poteto - Copyright 2005-2019 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.7 - Last modified 2019-02-05

Requires:
	Preset $OekakiID and $OekakiPass if they are not cookies.
	$OekakiU still supported as alternate to $OekakiID.

Control variables:
	$no_template, $no_online, $quiet_mode
*/


// Control variables
if (!isset($no_template)) {
	// Avoids building (or using) templates.
	$no_template = false;
}
if (!isset($no_online)) {
	// Disables updates to online table.
	$no_online = false;
}
if (!isset($quiet_mode)) {
	// Disables all buffer output so the paint applets will redirect properly.
	// Always flush the buffer and use w_log() instead.
	$quiet_mode = false;
}


// ============================================================================
// Globals init
//

// Evil globals still supported for mods
$version       = '1.6.7';
$dbconn        = false;
$db_mem        = '';
$db_p          = '';
//$OekakiID    = ''; // Do not set/test here
//$OekakiPass  = ''; // Do not set/test here
$OekakiU       = '';
$mode          = '';
$action        = '';
$start_time    = '';
$language      = '';
$template_name = '';
$cssinclude    = ''; // Path for HTML style tag
$address       = 'invalid';
$hostname      = '';
$smilies_group = '';
$p_pic = '';
$t_pic = '';
$r_pic = '';
$p_prefix = '';
$t_prefix = '';
$r_prefix = '';

$cfg = array(
	// Reset later, do not define any values here!
);

$glob = array(
	'debug'                 => false,
	'wactest'               => false,
	'db_version_number'     => '',
	'db_version_required'   => '1.5.6', // Version marker in database tables
	'maintenance_boot'      => false,
	'maintenance_update'    => false,
	'cookie_life'           => 2419200, // Seconds
	'cookie_path'           => '',
	'cookie_domain'         => ''
);

// $cgi[] array?
$pageno              = 0;
$sort                = 0;
$artist              = '';
$a_match             = '';
$t_match             = '';

$user = array(
	'no_viewer'        => 0,
	'screensize'       => 800,
	'picview'          => 0, // 0=auto, 1=horizontal, 2=vertical
	'thumbview'        => 2,
	'pic_viewer_norm'  => 'rel="lytebox[norm]"',
	'pic_viewer_adult' => 'rel="lytebox[adult]"'
);

$flags = array(
	// Permissions only.  Really should be in $user[], but needed here for legacy support.
	'all'    => '',
	'member' => 0,  // Is a registered member
	'G'      => 0,  // Is a registered member (old flag)
	'P'      => 0,  // Is pending approval
	'mod'    => 0,  // Is a moderator
	'admin'  => 0,  // Is an admin
	'sadmin' => 0,  // Is a super admin
	'owner'  => 0,  // Is owner
	'rank'   => 0,  // (0-9)
	'D'      => 0,  // Draw access
	'M'      => 0,  // Anim access
	'I'      => 0,  // Immunity
	'U'      => 0,  // Upload access
	'X'      => 0   // Adult access
);

$datef = array(
	// Dates redefined in language file.
	'post_header' => 'l, F jS Y, g:i A',
	'admin_edit'  => 'F j, Y, g:i a',
	'chat'        => 'H:i',
	'birthdate'   => 'Y/n/j',
	'birthmonth'  => 'Y/n',
	'birthyear'   => 'Y',

	// Drop-down menu in registration / edit profile.
	// "Y" and "M" and "D" in any order.  All 3 letters required.
	'age_menu'    => 'MDY'
);


// ===================================================================
// Startup
//

if (version_compare(PHP_VERSION, '5.2.0') < 0) {
	header('Content-type: text/plain');
	exit('Sorry, Wacintaki requires PHP 5.2.x or higher with MySQLi support.');
}

// Platform
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	define('PLATFORM_OS_WIN', 1);
} else {
	define('PLATFORM_OS_WIN', 0);
}


// Get config and hacks
if (!@include 'config.php') {
	$glob['maintenance_boot'] = true;
} else {
	// ===================================================================
	// Hacks! (requires config, call before startup/common.php)
	// Included here since boot.php is used on EVERY page, and hacks may be security/user related
	//
	if (file_exists($cfg['res_path'].'/'.'hacks.php')) {
		define('HACK_CHECK', 1); // Version control
		include $cfg['res_path'].'/'.'hacks.php';
	}

	if (!defined('MIN_AGE_ADULT')) {
		define('MIN_AGE_ADULT', 18);
	}

	if (!defined('ACTIVE_LOGIN_TIME_DAYS')) {
		define('ACTIVE_LOGIN_TIME_DAYS', 180);
	}

	if (!defined('MAX_COMMENT_BYTES')) {
		define('MAX_COMMENT_BYTES', 10000);
	}

	if (isset($debug) && $debug) {
		$glob['debug'] = true;
		unset($debug);
	}

	if (isset($wactest) && $wactest) {
		$glob['wactest'] = true;
		unset($wactest);
	}

	if (isset($maintenance_mode) && $maintenance_mode) {
		$glob['maintenance_boot'] = true;
		unset($maintenance_mode);
	}

	if (isset($cookie_path) && !empty($cookie_path)) {
		$glob['cookie_path'] = $cookie_path;
		unset($cookie_path);
	}

	if (isset($cookie_domain) && !empty($cookie_domain)) {
		$glob['cookie_domain'] = $cookie_domain;
		unset($cookie_domain);
	}

	// Disables global hack for error.php and works like < 1.6.0.
	// My next project will use a registry.  Trust me.
	define('X_DISABLE_REPORT_ERR_HACK', false);
}


// E_ALL can cause date() errors on servers without locale or timezones set correctly.
if ($glob['debug']) {
	error_reporting(E_ALL | E_STRICT);
} else {
	error_reporting(E_ALL ^ E_NOTICE);
}


// Get common (core) functions
require 'common.php';


// Get database
if (!file_exists('dbconn.php')) {
	exit("<html>\n<body>\n<p>Welcome to Wacintaki Poteto!</p>\n<p><a href=\"install.php\">Click here to run the installer</a>.</p>\n</body>\n</html>");
} else {
	require 'db_layer.php';
}

$dbconn = db_open(true);
if (!$dbconn) {
	if ($quiet_mode) {
		exit;
	} else {
		exit("<html>\n<body>\n<p><strong>Wacintaki v{$version}:</strong> Could not connect to the MySQL database.</p>\n</body>\n</html>");
	}
} else {
	$result = db_query("SELECT miscstring FROM {$db_p}oekakimisc WHERE miscname='dbversion'");
	$glob['db_version_number'] = db_result($result);
}

// Test for update
if ($glob['db_version_number'] != $glob['db_version_required']) {
	$glob['maintenance_boot']   = true;
	$glob['maintenance_update'] = true;
}


// ===================================================================
// GPC
//

$mode    = w_gpc('mode', 'a');
$action  = w_gpc('action', 'a');
$pageno  = w_gpc('pageno', 'i+');
$sort    = w_gpc('sort', 'i+');
$artist  = w_gpc('artist');
$a_match = w_gpc('a_match');
$t_match = w_gpc('t_match');


// Login validation
// If not set elsewhere (ex: by applets), we import cookies here
if (!isset($OekakiID)) {
	$OekakiID = '';
	if (isset($_COOKIE['OekakiID'])) {
		$OekakiID = $_COOKIE['OekakiID'];
	} elseif(isset($_REQUEST['OekakiID'])) {
		$OekakiID = $_REQUEST['OekakiID'];
	}
}
if (!isset($OekakiPass)) {
	$OekakiPass = '';
	if (isset($_COOKIE['OekakiPass'])) {
		$OekakiPass = $_COOKIE['OekakiPass'];
	} elseif(isset($_REQUEST['OekakiPass'])) {
		$OekakiPass = $_REQUEST['OekakiPass'];
	}
}

$OekakiID   = w_toalpha($OekakiID);
$OekakiPass = addslashes(str_replace('\\', '', $OekakiPass));


// ===================================================================
// Init
//

// Benchmark
$start_time = get_microtime();

// GZip/ZLib compression for HTML
$glob['use_compress'] = true;
if (defined('DISABLE_HTML_COMPRESSION') && DISABLE_HTML_COMPRESSION
	|| !get_compress_okay())
{
	$glob['use_compress'] = false;
}

// User defaults
$language = $cfg['language'];
$template_name = $cfg['template'];

// Cache control
//
// NOTE: we can't rely on the database to provide signed ints, so "forever"
// dates should be reasonably in the future.  Do NOT use the cache for long-
// term storage, so anything more than a few years is not reasonable.
define('CACHE_EXPIRE',       864000);    // 15 days (default)
define('CACHE_EXPIRE_LONG',  15768000);  // 6 months
define('CACHE_EXPIRE_NEVER', 157680000); // Ought to be enough for anybody
define('CACHE_EXPIRE_NOW',   0);

// Admin rank numbers
define('RANK_MOD',    4);
define('RANK_ADMIN',  5);
define('RANK_SADMIN', 7);
define('RANK_OWNER',  9);

// Mailbox
define('MAIL_NEW',     0);
define('MAIL_UNREAD',  1);
define('MAIL_READ',    2);
define('MAIL_REPLIED', 3);

// Password (0=DEC, 1=DEC Extended, 2=Blowfish)
define('PASSWORD_STRENGTH', 2);

// Picture and thumbnail logic
$p_pic = $cfg['op_pics'].'/'.$cfg['op_pre'];
$t_pic = $cfg['op_pics'].'/'.$cfg['op_pre'].'t';
$r_pic = $cfg['op_pics'].'/'.$cfg['op_pre'].'r';
$p_prefix = $cfg['op_pre'];
$t_prefix = $cfg['op_pre'].'t';
$r_prefix = $cfg['op_pre'].'r';


// ===================================================================
// Maintenance login (before processing registration)
//
if ($glob['maintenance_boot'] && w_gpc('action') == 'login') {
	$username  = w_gpc('username');
	$pass      = w_gpc('pass');

	$result = db_query("SELECT ID, usrname, usrpass, usrflags FROM {$db_mem}oekaki WHERE usrname='$username' AND rank=".RANK_OWNER);

	if ($result) {
		include 'language/'.$language.'.php';

		if (db_num_rows($result) < 1) {
			html_exit('<p>'.t('testvar1')."</p>\n");
		}

		$row = db_fetch_array($result);

		if ($glob['maintenance_update'] && w_password_verify($pass, $row['usrpass'])) {
			html_exit('<p>'
				.t('please_update', 'update.php')."</p>\n<p>"
				.t('please_update_cur', $glob['db_version_number'])."</p>\n<p>"
				.t('please_update_new', $glob['db_version_required'])."</p>\n"
			);
		}

		if (w_password_verify($pass, $row['usrpass'])) {
			w_set_cookie('OekakiID', $row['ID']);
			w_set_cookie('OekakiPass', $row['usrpass']);

			// IIS-safe redirect
			all_done('index.php;in');
		} else {
			html_exit('<p>'.t('testvar1')."</p>\n");
		}
	} else {
		html_exit('<p>'.t('db_err')."</p>\n");
	}
}


// ===================================================================
// Process Registration
//
if (!empty($OekakiPass) && !empty($OekakiID) ) {
	$result = db_query("SELECT * FROM {$db_mem}oekaki WHERE ID='$OekakiID'");

	if (!$result) {
		// It's serious to have SQL bomb here
		w_log_sql('boot:'.__LINE__);

		if (!$quiet_mode) {
			// Not quiet mode?  DIE!
			header('Content-type: text/plain');
			w_exit('Login failed due to a server error.  If the problem persists, ask the admin to check the oekaki log.');
		}
	}

	// Fetch user info and set if valid, non-pending, and logged in.
	if (db_num_rows($result)) {
		$user_temp = db_fetch_array($result);

		if ($OekakiPass == $user_temp['usrpass']) {
			if (strstr($user_temp['usrflags'], 'G') && !strstr($user_temp['usrflags'], 'P')) {
				$user = array_merge($user, $user_temp);
				$flags['member'] = 1;
			}
		}

		unset($user_temp);
	}


	// If SQL works and registration checks out
	if ($flags['member'] == 1) {
		$OekakiID = $user['ID'];
		$OekakiU  = $user['usrname'];

		// Get flags
		$flags = array_merge($flags, parse_flags($user['usrflags'], $user['rank']));

		// Get language, template
		// Blank for either means use default
		if (!empty($user['language'])) {
			$language = $user['language'];
		}
		if (!empty($user['templatesel'])) {
			$template_name = $user['templatesel'];
		}

		// Update last login
		if (!$glob['maintenance_boot']) {
			$result = db_query("UPDATE {$db_mem}oekaki SET lastlogin=NOW() WHERE usrname='$OekakiU'");
		}
	} else {
		// Nuke 'em
		$OekakiID = '';
		$OekakiU  = '';
		$OekakiPass = '';

		if (!$quiet_mode) {
			w_unset_cookie('OekakiID');
			w_unset_cookie('OekakiPass');
		}
	}

	// Set misc. defaults
	if ($cfg['use_viewer'] == 0) {
		$user['no_viewer'] = 1;
	}
}


// ===================================================================
// Language
//

// Get cache; rebuild if needed
$lang['names'] = get_cache('lang_names');
if (empty($lang['names'])) {
	build_lang_names_cache();
	$lang['names'] = get_cache('lang_names');
} else {
	// Check for language file update
	if ($flags['owner']) {
		// In liu of a modular language/template system where packages have to be
		// installed, we'll check the cache every time the owner logs in.
		$result5 = db_query("SELECT built FROM {$db_p}oekakicache WHERE name='lang_names'");

		if (filemtime('language/'.$language.'.php') > db_result($result5)) {
			// File updated.  Rebuild cache.
			build_lang_names_cache();
			$lang['names'] = get_cache('lang_names');
		}
	}
}
$lang['http_accept'] = get_cache('lang_http_accept');


// Check for guest language
if (!defined('DISABLE_GUEST_LANG') || !DISABLE_GUEST_LANG) {
	$guest_lang = trim(w_gpc('guest_lang')); // $_GET
	$OekakiLang = trim(w_gpc('OekakiLang')); // $_COOKIE
	if (empty($OekakiU)) {
		if (!empty($guest_lang)) {
			// Caution: filter input
			if (!badChars($guest_lang, 'file')) {
				if ($guest_lang != $OekakiLang) {
					// Set new language
					w_set_cookie('OekakiLang', $guest_lang);
					$OekakiLang = $guest_lang;
				}
				$language = $guest_lang;
			}
		}

		if (!empty($OekakiLang)) {
			if (!badChars($OekakiLang, 'file')) {
				if (file_exists('language/'.$OekakiLang.'.php')) {
					$language = $OekakiLang;
				}
			}
		} else {
			$temp = get_http_accept_lang();

			// Rough match
			foreach ($lang['http_accept'] as $accept => $serve) {
				$base = $accept;
				if (strpos($accept, '-') !== false) {
					list ($base, $dialect) = explode('-', $accept);
				}
				if (!empty($base) && strpos($temp, $base) !== false) {
					$language = $serve;
				}
			}

			// Exact/better match
			foreach ($lang['http_accept'] as $accept => $serve) {
				if ($accept == $temp) {
					$language = $serve;
				}
			}
		}
	}
}

// Needed in quiet mode for error reporting
if ($language != 'english' && file_exists('language/'.$language.'.php')) {
	include 'language/'.$language.'.php';
} else {
	include 'language/english.php';
}

// Verify language $datef['age_menu'] has all 3 letters
if (strlen($datef['age_menu']) != 3
	|| strpos($datef['age_menu'], 'Y') === false
	|| strpos($datef['age_menu'], 'M') === false
	|| strpos($datef['age_menu'], 'D') === false)
{
	$datef['age_menu'] = 'MDY';
}


// ===================================================================
// Maintenance mode
//
if ($glob['maintenance_boot']) {
	// Check for owner login
	if (!$flags['owner']) {
		$my_email = '<a href="mailto:'.$cfg['op_email'].'">'.email_code($cfg['op_email']).'</a>';

		html_quick();

		echo "\n<h2>".t('boot_down_maint')."</h2>\n";
		echo '<p>'.t('boot_maint_exp', $cfg['op_title'], $my_email)."</p>\n";

?>

<h2><?php tt('header_diag'); ?></h2>
<div id="adminbar">
	<form name="form_login_boot" action="index.php" method="post">
		<input name="action" type="hidden" value="login" />

		<?php tt('word_name');?> 
		<input name="username" type="text" class="multiline" />

		<?php tt('install_password');?> 
		<input name="pass" type="password" class="multiline" />
		<input name="login" type="submit" value="<?php tt('install_login');?>" class="submit" />
	</form>
</div>
<?php

		w_exit("\n</body>\n</html>");
	}

	// Update magic
	if ($glob['maintenance_update']) {
		html_exit('<p>'
			.t('please_update', 'update.php')."</p>\n<p>"
			.t('please_update_cur', $glob['db_version_number'])."</p>\n<p>"
			.t('please_update_new', $glob['db_version_required'])."</p>\n"
		);
	}

	// Uncaught condition.  Check for broken stuff.
	if (!isset($cfg['version'])) {
		html_exit('<p>'.t('err_wrconfig')."</p>\n");
	}
}


// ===================================================================
// Ban list
// BAN_OVERRIDE is for hacks, BAN_KILL is internal use only
//
// Get IP and hostname
if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
	$address = $_SERVER['REMOTE_ADDR'];
	if (defined('ENABLE_DNS_HOST_LOOKUP') && ENABLE_DNS_HOST_LOOKUP) {
		$hostname = @gethostbyaddr($address);
	} else {
		$hostname = '';
	}
}

if (! (defined('BAN_OVERRIDE') && BAN_OVERRIDE)) {
	if (! (defined('BAN_KILL') && BAN_KILL)) {
		// !hacks.php
		require 'banscript.php';
	}
}

// Track IP after loading ban list
if (!empty($OekakiU) && !empty($OekakiPass)) {
	$result = db_query("UPDATE `{$db_mem}oekaki` SET `IP`='{$address}' WHERE `usrname`='$OekakiU'");
}


// ===================================================================
// Template
//

if (!$quiet_mode) {

	// Import niftytoo
	if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
		require 'niftytoo.php';
	} else {
		require 'niftytoo_legacy.php';
	}

	// Build and set template
	//
	$template_file = $cfg['tpl_path'].'/'.$template_name;
	$cssinclude = $template_file.'.css';

	if (!$no_template) {
		// If regular member, use built CSS
		// If admin, compare template w/CSS and rebuild if needed

		$my_css_exists = false;
		$my_template_build = false;

		if (file_exists($template_file.'.css')) {
			$my_css_exists = true;
		}

		if ($my_css_exists) {
			if ($flags['admin']) {
				if (file_exists($template_file.'.php')) {
					if (filemtime($template_file.'.php') > filemtime($template_file.'.css')) {
						// Template is newer!  Rebuild CSS.
						$my_template_build = true;
					}
				}
			}
		} else {
			// No CSS.  New template?
			$my_template_build = true;
		}

		if ($my_template_build) {
			if (file_exists($template_file.'.php')) {
				if (!is_writable($cfg['tpl_path'])) {
					html_exit( t('boot_folder_locked', 'Templates', 'Readme.txt') );
				}

				// Build CSS
				$tcontrol = 'build';
				ob_start();
				include $template_file.'.php';
				$cssdata = ob_get_contents();
				if (!empty($cssdata) && $handle = @fopen($template_file.'.css', 'w')) {
					fwrite($handle, $cssdata);
					fclose($handle);
				} else {
					// CSS problem! Use default
					$cssinclude = $cfg['tpl_path'].'/'.$template_name.'.css';

					if (!file_exists($cssinclude)) {
						// Mega problem!
						$cssinclude = $cfg['tpl_path'].'/Default.css';
						$template_name = 'Failsafe';
					}
				}
				ob_clean();
				unset($tcontrol);
			} else {
				/*
				Let's hope that a broken template returns a 404, so admins will
				look for what's wrong.  Don't throw w_exit(), so admins can still
				actually moderate if the template is b0rked!
				*/
			}
		}
	} // End template build


	// Set layout, thumbnail
	if ($flags['member']) {
		if ($cfg['force_thumb'] == 'yes') {
			$user['thumbview'] = $cfg['use_thumb'];
		}

		// Account "Screenshot" disables thumbnails
		if (strtolower($OekakiU) == 'screenshot') {
			$user['thumbview'] = 0;
		}
	} else {
		$user['thumbview'] = $cfg['use_thumb'];
	}
} // End quiet mode


// ===================================================================
// Secure installer/updater
//
if (!$quiet_mode) {
	// Check installer, updater.  For effiency and to resolve conflicts
	// with non-installed boards, check for admin cookies only.

	if ($action == 'secure') {
		html_quick();

		$my_fail = 0;
		if (file_exists('install.php')) {
			if (@unlink('install.php')) {
				echo t('boot_inst_rm')."<br />\n";
			} else {
				echo t('boot_inst_rm_fail')."<br />\n";
				$my_fail++;
			}
		}
		if (file_exists('update.php')) {
			if (file_exists('update_rc.php')) {
				@unlink('update_rc.php');
			}
			if (@unlink('update.php')) {
				echo t('boot_update_rm')."<br />\n";
			} else {
				echo t('boot_update_rm_fail')."<br />\n";
				$my_fail++;
			}
		}
		if ($my_fail) {
			echo '<p>'.t('boot_remove_ftp').'</p>';
		} else {
			echo '<p><a href="index.php">'.t('boot_goto_index').'</a></p>';
		}
		w_exit("</body>\n</html>");
	}
	if ($flags['admin']) {
		if (file_exists('install.php') || file_exists('update.php')) {
			html_exit(
				'<p>'
				.t('boot_still_exist_sub1')."</p>\n<p>"
				.t('boot_still_exist_sub2', 'index.php?action=secure')."</p>\n<p>"
				.t('boot_still_exist_sub3')."</p>"
			);
		}
	}
} // End quiet mode


// ===================================================================
// Online list
//
if (!$quiet_mode) {
	// One of the few times the $lang var is used directly
	// Useful for debugging, so almost all scripts are included

	if (!$no_online) {
		if (!empty($_SERVER['PHP_SELF'])) {
			// Fixes servers using PHP as a runtime and not as a module
			$my_script = $_SERVER['PHP_SELF'];
		} else {
			$my_script = $SCRIPT_NAME;
		}
		$my_script = strtolower(basename($my_script));


		$loc = 'unknown';
		if ($my_pos = strrpos($my_script, '.')) {
			// Strip extension
			$loc = w_toalpha( substr( $my_script, 0, $my_pos));
		}

		// Fix special cases
		if (substr($loc, 0, 4) == 'mail') {
			$loc = 'mail';
		}
		if (substr($loc, 0, 4) == 'chat') {
			$loc = 'chatbox';
		}
		if (isset($_REQUEST['a_match']) || isset($_REQUEST['artist'])) {
			$loc = 'index_match';
		}


		// Maintenance (avoid on scripts that auto-refresh)
		if ($loc != 'chat' && $loc != 'online') {
			$online = db_query('DELETE FROM '.$db_mem.'oekakionline WHERE (DATE_ADD(onlinetime, INTERVAL 15 MINUTE) < NOW())');

			$online = db_query('DELETE FROM '.$db_mem.'oekakionline WHERE (DATE_ADD(onlinetime, INTERVAL 1 MINUTE) < NOW()) AND locale=\'chat\'');
		}

		if ($flags['member'] && !empty($OekakiU) && $loc != 'whosonline' && $loc != 'error') {
			$result = db_query("SELECT onlineusr FROM {$db_mem}oekakionline WHERE onlineusr='{$OekakiU}'");

			if (db_num_rows($result)) {
				$online = db_query("UPDATE {$db_mem}oekakionline SET onlinetime=NOW(), locale='o_{$loc}', onlineboard='".db_escape($cfg['op_title'])."' WHERE onlineusr='{$OekakiU}'");
			} else {
				$online = db_query("INSERT INTO {$db_mem}oekakionline SET onlineusr='{$OekakiU}', onlinetime=NOW(), onlineIP='{$address}', locale='o_{$loc}', onlineboard='".db_escape($cfg['op_title'])."'");
			}
		}

		unset($my_script);
		unset($loc);
	}
} // End quiet mode


// Regular maintenance
// For now, we'll only run this during normal browsing, not on a scheduler
if (!$quiet_mode && empty($mode) && empty($action)) {
	require 'maint.php';
}


// ============================================================================
// FUNCTIONS
//

// HTML DOCTYPE and HEAD reference
// Defined here (and not common.php), so test changes are tied to version number
function send_html_headers($override = '') {
	global $charset, $metatag_language, $glob;

	if ($glob['use_compress']) {
		if (ob_get_length() > 0) {
			@ob_flush();
		}
		if (!headers_sent() && ob_start('ob_gzhandler')) {
			$GLOBALS['zlib'] = true;
		}
	}

	if (!empty($charset)) {
		header("Content-Type: text/html; charset={$charset}");
	}
	if (!empty($metatag_language)) {
		header("Content-Language: {$metatag_language}");
	}

	if ($override === 'html5') {
	?>
<!DOCTYPE html>
<html lang="<?php echo $metatag_language;?>">
<?php
	} elseif ($override === 'html') {
	?>
<!DOCTYPE html PUBLIC
	"-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php
	} else {
	?>
<!DOCTYPE html PUBLIC
	"-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	}
}


function html_quick() {
	global $charset, $metatag_language;
	send_html_headers();

echo <<<EOF
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}" />
	<meta http-equiv="Content-Language" content="{$metatag_language}" />
	<title>Wacintaki</title>
</head>

<body>

EOF;
}


function html_exit($text) {
	html_quick();
	echo '<div>'.$text.'</div>';
	echo "\n</body>\n</html>";
	w_exit();
}


function get_compress_okay() {
	global $glob;

	if (!$glob['use_compress']
		|| !extension_loaded('zlib')
		|| @ini_get('zlib.output_compression')
		|| @ini_get('zlib.output_compression') === false // Detection blocked?
		|| @ini_get('output_handler') == 'ob_gzhandler')
	{
		return false;
	}

	$agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	if (empty($agent)) {
		// Running as CGI
		return false;
	}

	$agent_gzip = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';
	if (strpos(strtolower($agent_gzip), 'gzip') === false) {
		// Either Mosaic or archaic.  ;)
		return false;
	}

	// Some versions of IE can't handle gzip correctly
	if (strpos($agent, 'MSIE ') !== false) {
		if (strpos($agent, 'Maxthon') === false
			&& strpos($agent, 'MyIE2') === false)
		{
			if (preg_match("/MSIE ([0-9]{1,}[\.0-9]{0,})/", $agent, $matches)
				&& floatval($matches[1]) < 7.0)
			{
				return false;
			}
		}
	}
	return true;
}


function build_lang_names_cache() {
	$lang_names = array();
	if ($handle = opendir('language')) {
		while (false !== ($file = readdir($handle))) {
			$name = substr($file, 0, -4);
			if (substr($file, -4) == '.php' && $file != 'index.php') {
				$lang_names[] = $name;
			}
		}
		closedir($handle);
	}
	sort($lang_names);

	$lang_name_cache = array();
	$lang_http_accept_cache = array();
	foreach ($lang_names as $name) {
		@include 'language/'.$name.'.php';

		// 'german' => 'Deutsch / German'
		if ($name == 'english') {
			$lang_name_cache[$name] = $lang['cfg_language'];
		} else {
			$lang_name_cache[$name] = $lang['cfg_language'].' / '.$lang['cfg_lang_eng'];
		}

		// 'de' => 'german'
		$metatag_language = strtolower($metatag_language);
		$lang_http_accept_cache[$metatag_language] = $name;

		unset($lang);
	}

	$GLOBALS['lang']['names'] = $lang_name_cache;
	$GLOBALS['lang']['http_accept'] = $lang_http_accept_cache;

	if (!set_cache('lang_names', $lang_name_cache, CACHE_EXPIRE_NEVER)) {
		return false;
	}
	if (!set_cache('lang_http_accept', $lang_http_accept_cache, CACHE_EXPIRE_NEVER)) {
		return false;
	}

	return true;
}