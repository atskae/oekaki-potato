<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2019 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.7 - Last modified 2019-02-05
*/


// Set bootstrap flag and fake init, so we can include files
define('BOOT', 1);
$glob = array(
	'debug'   => false,
	'wactest' => false
);


define('PASSWORD_STRENGTH', 2);
$cfg = array();


// Init
error_reporting(E_ALL);


// Custom installer functions
function make_new_file($path, $data = '') {
	if (file_exists($path)) {
		return true;
	}
	$fp = @fopen($path, 'w');
	if (!$fp) {
		web_line('Could not create file &ldquo;'.$path.'&rdquo');
		return false;
	}
	if (!empty($data)) {
		fwrite($fp, $data, strlen($data));
	}
	fclose($fp);
	return true;
}

// db_error() helper function
function sql_check($result, $extra = '') {
	if (!$result) {
		echo db_error();
		if (!empty($extra)) {
			echo ' ['.$extra.']';
		}
		echo "<br />\n";
		return 0;
	}
	return 1;
}


include 'common.php';


// Language determination
{
	$language = 'english';
	$lang_name_cache = array(
		'chinese' => '繁體中文 / Chinese Traditional',
		'chinese_s' => '简体中文 / Chinese Simplified',
		'english' => 'English',
		'german' => 'Deutsch / German',
		'spanish' => 'español / Spanish',
	);
	$lang_http_accept_cache = array(
		'zh' => 'chinese',
		'en' => 'english',
		'de' => 'german',
		'es' => 'spanish',
	);

	// Get language from HTTP
	$temp = get_http_accept_lang();
	foreach ($lang_http_accept_cache as $accept => $serve) {
		if (strpos($accept, $temp) !== false) {
			$language = $serve;
		}
	}

	// Get language from CGI
	if (isset($_REQUEST['langs'])) {
		$language = w_gpc('langs');
	}
	$url_language = urlencode($language); // For GET method

	if (file_exists('language/'.$language.'.php')) {
		include 'language/'.$language.'.php';
	} else {
		include 'language/english.php';
	}
}


// Init
$mode   = w_gpc('mode');
$action = w_gpc('action');



/* START HTML */
if (!empty($charset)) {
	header("Content-type: text/html; charset={$charset}");
}
if (!empty($metatag_language)) {
	header("Content-language: {$metatag_language}");
}
?>
<!DOCTYPE HTML PUBLIC
	"-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title><?php tt('install_title');?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>">
<?php if (!empty($metatag_language)) { ?>
	<meta http-equiv="Content-language" content="<?php echo $metatag_language;?>">
<?php } ?>

	<link rel="stylesheet" type="text/css" href="templates/Install.css" title="Installer">
</head>
<body>

<?php



// Startup tests
$errs = array();
if (!is_writable('.')) {
	$errs[] = "You need to CHMOD your main directory so it is writable (use 777 if you are not sure what to use).";
}
if (!is_writable('./templates')) {
	$errs[] = "You need to CHMOD the templates directory so it is writable (use 777 if you are not sure what to use).";
}
if (file_exists('./resource') && (!is_writable('./resource'))) {
	$errs[] = "The resource folder is present but not writable.  CHMOD it so it is writable (use 777 if you are not sure what to use).";
}
if (file_exists('./pictures') && (!is_writable('./pictures'))) {
	$errs[] = "The pictures folder is present but not writable.  CHMOD it so it is writable (use 777 if you are not sure what to use).";
}
if (file_exists('./avatars') && (!is_writable('./avatars'))) {
	$errs[] = "The avatars folder is present but not writable.  CHMOD it so it is writable (use 777 if you are not sure what to use).";
}
if (file_exists('config.php') && (!is_writable('config.php'))) {
	$errs[] = "The config file is not writable.  CHMOD it as defined in &ldquo;readme.html&rdquo; or delete the file if you are re-installing the board.";
}
if (file_exists('dbconn.php') && (!is_writable('dbconn.php'))) {
	$errs[] = "The database config file is not writable.  CHMOD it as defined in &ldquo;readme.html&rdquo; or delete the file if you are re-installing the board.";
}
$errs_count = count($errs);
if ($errs_count > 0) {
	for ($i = 0; $i < $errs_count; $i++) {
		echo ("<p>{$errs[$i]}</p>\n\n");
	}
echo <<<EOF
</body>
</html>
EOF;

	exit;
}


// Confirmation for delete database
if ($mode === 'del') {
	if (file_not_empty('dbconn.php') && file_not_empty('config.php')) {
		include 'dbconn.php';
		include 'config.php';

		if (empty($cfg['op_title'])) {
			$cfg['op_title'] = '[ Unnamed, Data Prefix="'.$db_p.'", Member Prefix="'.$db_mem.'" ]';
		}

?>
	<h1 class="title">
		<?php tt('delete_dbase');?> 
	</h1>

	<hr />


	<h2 class="header">
		<?php tt('uninstall_prompt');?> 
	</h2>

	<div class="infotable">
		<div class="infonote">
			<p class="infonote">
				<?php tt('sure_remove_dbase');?> &ldquo;<?php echo $cfg['op_title'];?>&rdquo;. <?php tt('all_delete');?> 
			</p>
			<p class="infonote"><?php tt('delete_oneboard');?></p>
			<p class="infonote"><?php tt('sharing_dbase');?></p>
			<p class="infonote"><?php tt('remove_board');?></p>
		</div>
		<br />

		<form name="delete_database" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="infonote">
			<input name="action" type="hidden" value="db_delete" />
			<input name="langs" type="hidden" value="<?php echo $language;?>" />

			<input name="db_prefix" type="checkbox" /><?php tt('delepostcomm');?><br />
			<input name="db_members" type="checkbox" /><?php tt('delememp');?><br />
			<br />
			<input name="submit" type="submit" value="<?php tt('word_delete');?>" class="submit" />
		</form>
	</div>
</body>
</html>
<?php

		exit;

	} else {

?>
	<h2 class="header">
		<?php tt('uninserror');?> 
	</h2>

	<div class="infotable">
		<div class="infonote">
			<p class="infonote"><?php tt('uninsmsg');?></p>
		</div>
	</div>
</body>
</html>
<?php

		exit;
	}
}


// Delete database
//
if ($action === 'db_delete') {
	include 'db_layer.php';
	$dbconn = db_open();

	include 'config.php';
	$delete_flag = 0;

?>
	<h1 class="title">
		<?php tt('delete_dbase');?> 
	</h1>

	<hr />


	<h2 class="header">
		<?php tt('unistatus');?> 
	</h2>

	<div class="infotable">
		<p>
<?php

	if (!empty($_POST['db_prefix']) && !$glob['debug']) {
		$result = db_query("DROP TABLE IF EXISTS {$db_p}oekakicmt");
		sql_check($result);
		$result = db_query("DROP TABLE IF EXISTS {$db_p}oekakidta");
		sql_check($result);
		$result = db_query("DROP TABLE IF EXISTS {$db_p}oekakimisc");
		sql_check($result);

		$delete_flag = 1;
	}

	if (!empty($_POST['db_members']) && !$glob['debug']) {
		$result = db_query("DROP TABLE IF EXISTS {$db_mem}oekaki");
		sql_check($result);
		$result = db_query("DROP TABLE IF EXISTS {$db_mem}oekakichat");
		sql_check($result);
		$result = db_query("DROP TABLE IF EXISTS {$db_mem}oekakichatmod");
		sql_check($result);
		$result = db_query("DROP TABLE IF EXISTS {$db_mem}oekakichatmodusers");
		sql_check($result);
		$result = db_query("DROP TABLE IF EXISTS {$db_mem}oekakionline");
		sql_check($result);
		$result = db_query("DROP TABLE IF EXISTS {$db_mem}oekakikill");
		sql_check($result);
		$result = db_query("DROP TABLE IF EXISTS {$db_mem}oekakimailbox");
		sql_check($result);

		$delete_flag = 1;
	}

	db_close();
?>
		</p>

<?php if ($delete_flag == 1) { ?>
		<p>
			<?php tt('install_dbremove');?> 
		</p>
<?php } else { ?>
		<p>
			<?php tt('notedbchange');?> 
		</p>
<?php } ?>

		<p>
			<a href="<?php echo $_SERVER['PHP_SELF'];?>?langs=<?php echo $url_language;?>"><?php tt('returninst');?></a>
		</p>
	</div>

</body>
</html>
<?php
	exit;
}


//
// User submits information
if ($action === 'process') {
	// Check all posts
	foreach ($_POST as $key=>$value) {
		$_POST[$key] = trim(w_gpc($key));
	}

?>
	<h1 class="title">
		<?php tt('wacinstall');?> 
	</h1>

	<hr />


	<h2 class="header">
		<?php tt('instalprog');?> 
	</h2>

	<div class="infotable">
		<p>
<?php

	// Verify input
	$OekakiPoteto_Prefix = w_gpc('dbprefix', 'a');
	$OekakiPoteto_MemberPrefix = w_gpc('OekakiPoteto_MemberPrefix', 'a');
	$pass    = $_POST['pass'];
	$pass2   = $_POST['pass2'];
	$usrname = $_POST['usrname'];
	$salt    = w_get_random_salt(2, true);

	$input_fail = 0;
	if (empty($_POST['dbhost']) || empty($_POST['dbuser']) || empty($_POST['dbname'])) {
		web_line( t('err_dbs'));
		$input_fail = 1;
	}
	if (empty($_POST['dbpass'])) {
		web_line( t('note_pwd'));
	}
	if (empty($usrname)) {
		web_line( t('err_adminname'));
		$input_fail = 1;
	}
	if (empty($pass) || empty($pass2)) {
		web_line( t('err_adminpwd'));
		$input_fail = 1;
	}
	if ($pass != $pass2) {
		web_line( t('err_admpwsmtch'));
		$input_fail = 1;
	}

	if ($input_fail == 1) {
echo <<<EOF
		</p>
	</div>
</body>
</html>
EOF;
		exit;
	}
	web_line();



	// Create a database connection file from POST data.
$db_configuration = <<<EOF
<?php // Include only
	\$cfg_db = array();
	\$cfg_db['version'] = '1.5.6';

	// Use MySQL recognized charset here
	\$cfg_db['set_charset'] = 'utf8';

	\$dbhost = '{$_POST['dbhost']}';
	\$dbuser = '{$_POST['dbuser']}';
	\$dbpass = '{$_POST['dbpass']}';
	\$dbname = '{$_POST['dbname']}';

	\$OekakiPoteto_Prefix = '{$_POST['dbprefix']}';
	\$OekakiPoteto_MemberPrefix = '{$_POST['OekakiPoteto_MemberPrefix']}';
EOF;

	// Write the db file
	if (!$glob['debug']) {
		$fp = @fopen('dbconn.php', 'w');
		if ($fp) {
			fwrite($fp, $db_configuration);
			fclose($fp);
			web_line( t('msg_dbsefile'));
		} else {
			web_line( t('err_permis'));
echo <<<EOF
		</p>
	</div>
</body>
</html>
EOF;

			exit;
		}
	}



	// Test database connection before going any further
	include 'db_layer.php';
	$dbconn = db_open();



	// Create main config file from POST data.

	// Get rid of trailing slashes
	$op_url        = strip_path(w_gpc('op_url'));
	$op_pics       = strip_path(w_gpc('op_pics'));
	$avatar_folder = strip_path(w_gpc('avatar_folder'));
	if (empty($op_pics)) {
		$op_pics = 'pictures';
	}
	if (empty($avatar_folder)) {
		$avatar_folder = 'avatars';
	}
	$op_pre = strip_path(w_gpc('op_pre'));

	// Fix e-mails
	$op_email    = trim(w_gpc('op_email'));
	$admin_email = trim(w_gpc('admin_email'));
	if (empty($admin_email)) {
		$admin_email = $op_email;
	}

	// Other verification
	$bbs_language = $language;
	$bbs_template = addslashes(w_gpc('template'));
	$op_title     = trim(w_gpc('op_title'));
	$op_author    = trim(w_gpc('op_author'));

	$reg_draw   = 'no';
	$reg_anim   = 'no';
	$reg_upload = 'no';
	if (w_gpc('reg_draw')   === 'D') $reg_draw = 'yes';
	if (w_gpc('reg_anim')   === 'A') $reg_anim = 'yes';
	if (w_gpc('reg_upload') === 'U') $reg_upload = 'yes';

	// Verify min/max values
	$verify     = array('pic_store', 'pic_pages', 'menu_pages', 'def_x', 'def_y', 'canvas_x', 'canvas_y', 'min_x', 'min_y', 'canvas_a', 'max_anim', 'thumb_bytes', 'thumb_t', 'thumb_r', 'max_pic', 'avatar_x', 'avatar_y', 'safety_storetime', 'cut_email', 'safety_max');
	$verify_min = array(50,           1,           5,            50,      50,      50,         50,         50,      50,      2500,       1000,       25000,         50,        50,        100000,    5,          5,          1,                  0,           1);
	$verify_max = array(500000,       50,          500,          10000,   10000,   10000,      10000,      10000,   10000,   100000000,  5000000,    15000000,      500,       1000,      15000000,  150,        150,        10000,              2,           10);

	for ($i = 0; $i < count($verify); $i++) {
		// Some values are not imported from POST
		if (!isset($_POST[$verify[$i]])) {
			$_POST[$verify[$i]] = $verify_min[$i];
		}
		if ($_POST[$verify[$i]] < $verify_min[$i]) $_POST[$verify[$i]] = $verify_min[$i];
		if ($_POST[$verify[$i]] > $verify_max[$i]) $_POST[$verify[$i]] = $verify_max[$i];
	}


	// Create a configuration file with specified settings
	$main_configuration = <<<EOF
<?php // Include only
	\$cfg = array();
	\$cfg['version'] = '1.5.5';

	\$cfg['op_title']  = '{$op_title}';
	\$cfg['op_author'] = '{$op_author}';
	\$cfg['op_adult']  = '{$_POST['op_adult']}';
	\$cfg['op_email']  = '{$op_email}';
	\$cfg['op_url']    = '{$op_url}';
	\$cfg['cut_email'] = '{$_POST['cut_email']}';

	\$cfg['salt']    = '{$salt}';
	\$cfg['op_pics'] = '{$op_pics}';
	\$cfg['op_pre']  = '{$op_pre}';

	\$cfg['kill_user']     = '-1';
	\$cfg['kill_reg']      = '15';
	\$cfg['draw_immune']   = 'no';
	\$cfg['private']       = 'no';
	\$cfg['approval']      = '{$_POST['approval']}';
	\$cfg['require_art']   = '{$_POST['require_art']}';
	\$cfg['guest_post']    = '{$_POST['guest_post']}';
	\$cfg['guest_draw']    = 'no'; //WIP
	\$cfg['guest_adult']   = '{$_POST['guest_adult']}';
	\$cfg['humanity_post'] = 'yes';
	\$cfg['reg_draw']      = '{$reg_draw}';
	\$cfg['reg_anim']      = '{$reg_anim}';
	\$cfg['reg_upload']    = '{$reg_upload}';
	\$cfg['reg_rules']     = 'no';
	\$cfg['censoring']     = 'no';

	\$cfg['language'] = '{$bbs_language}';
	\$cfg['template'] = '{$bbs_template}';
	\$cfg['tpl_path'] = 'templates';
	\$cfg['res_path'] = 'resource';

	\$cfg['pic_store']  = '{$_POST['pic_store']}';
	\$cfg['pic_pages']  = '10';
	\$cfg['menu_pages'] = '25';
	\$cfg['pic_limit']      = '2';  //WIP
	\$cfg['pic_limit_time'] = '24'; //WIP
	\$cfg['use_viewer'] = '1';

	\$cfg['porn_img']      = 'pr0n.png';
	\$cfg['preview_img']   = 'preview.png';
	\$cfg['preview_title'] = 'Canvas Preview';
	\$cfg['def_x']    = '300';
	\$cfg['def_y']    = '300';
	\$cfg['canvas_x'] = '500';
	\$cfg['canvas_y'] = '500';
	\$cfg['min_x']    = '50';
	\$cfg['min_y']    = '50';
	\$cfg['canvas_a'] = '250000';
	\$cfg['max_anim'] = '{$_POST['max_anim']}';
	\$cfg['max_pic']  = '{$_POST['max_pic']}';

	\$cfg['use_thumb']   = '{$_POST['use_thumb']}';
	\$cfg['force_thumb'] = '{$_POST['force_thumb']}';
	\$cfg['thumb_bytes'] = '{$_POST['thumb_bytes']}';
	\$cfg['thumb_t']     = '{$_POST['thumb_t']}';
	\$cfg['thumb_r']     = '{$_POST['thumb_r']}';
	\$cfg['thumb_jpg']   = '75';

	\$cfg['use_mailbox'] = 'yes';

	\$cfg['use_chat']   = 'no';
	\$cfg['chat_max']   = '40';
	\$cfg['chat_pages'] = '15';

	\$cfg['smilies'] = 'yes';

	\$cfg['use_avatars']   = '{$_POST['use_avatars']}';
	\$cfg['use_c_avatars'] = '{$_POST['use_c_avatars']}';
	\$cfg['avatar_folder'] = '{$avatar_folder}';
	\$cfg['avatar_x'] = '{$_POST['avatar_x']}';
	\$cfg['avatar_y'] = '{$_POST['avatar_y']}';

	\$cfg['public_retouch']   = 'yes';
	\$cfg['safety_saves']     = 'yes';
	\$cfg['safety_max']       = '2';
	\$cfg['safety_storetime'] = '30';
	\$cfg['self_bump']        = 'yes';

	\$cfg['latest_pic_file'] = 'no';
EOF;


	if (!$glob['debug']) {
		$fp = @fopen('config.php', 'w');
		if ($fp) {
			fwrite($fp, $main_configuration);
			fclose($fp);
			web_line( t('wrconfig'));
		} else {
?>
			<?php tt('err_wrconfig');?> 
		</p>
	</div>
</body>
</html>
<?php

			exit;
		}
	}

	web_line();


	/* Create specified directories with their respective permissions */
	if (! file_exists($op_pics)) {
		if (! @mkdir($op_pics, 0755)) {
			web_line( t('err_cfolder').'<em>'.$op_pics.'</em>');
		}
	}
	if (! is_writable($op_pics)) {
		web_line( t('err_folder').' <em>'.$op_pics.'</em> '.t('err_folderlocked'));
	}
	if (! file_exists($avatar_folder)) {
		if (! @mkdir($avatar_folder, 0755)) {
			web_line( t('err_cfolder').' <em>'.$avatar_folder.'</em>');
		}
	}
	if (! is_writable($avatar_folder)) {
		web_line( t('err_folder').' <em>'.$avatar_folder.'</em> '.t('err_folderlocked'));
	}


	/* Create base resource files */
	$new_files = array(
		'banner.php' => '',
		'hosts.txt' => '',
		'ips.txt' => '',
		'notice.php' => '',
		'rules.php' => "<p>\n".t('defrulz')."\n</p>"
	);
	$nf_error = 0;
	if (!$glob['debug']) {
		foreach ($new_files as $file_name => $file_data) {
			$status = make_new_file('resource/'.$file_name, $file_data);
			if (!$status)
				$nf_error++;
		}
	}
	if ($nf_error) {
?>
			<?php tt('err_fcreate');?> 
		</p>
	</div>
</body>
</html>
<?php

		exit;
	}

	web_line( t('write_basefile'));
	web_line();


	/* Insert the fields into the database */
	web_line( t('startsetdb'));

	include 'config.php';
	$db_p = $OekakiPoteto_Prefix;
	$db_mem = $OekakiPoteto_MemberPrefix;

	$passenc = w_password_hash($pass);

	if (!$glob['debug']) {
		$sql = "CREATE TABLE IF NOT EXISTS `{$db_mem}oekaki` (
			`ID` INT(11) NOT NULL AUTO_INCREMENT,
			`usrname` VARCHAR(255) NOT NULL DEFAULT '',
			`usrpass` TEXT,
			`usrflags` VARCHAR(10) DEFAULT 'G',
			`email` VARCHAR(255) DEFAULT NULL,
			`name` VARCHAR(25) DEFAULT NULL,
			`comment` TEXT,
			`url` TEXT,
			`aim` VARCHAR(30) DEFAULT NULL,
			`icq` VARCHAR(20) DEFAULT NULL,
			`urltitle` TEXT,
			`joindate` DATE NOT NULL DEFAULT '2000-01-01',
			`lastlogin` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
			`MSN` TEXT,
			`Yahoo` TEXT,
			`IRCNick` TEXT,
			`IRCServer` TEXT,
			`location` TEXT,
			`piccount` INT(5) NOT NULL DEFAULT 0,
			`IRCchan` TEXT,
			`templatesel` VARCHAR(255) NOT NULL DEFAULT '',
			`commcount` INT(11) NOT NULL DEFAULT 0,
			`age` TEXT,
			`gender` TEXT,
			`picview` TINYINT(11) NOT NULL DEFAULT '0',
			`language` VARCHAR(32) DEFAULT '',
			`thumbview` TINYINT NOT NULL DEFAULT 0,
			`screensize` MEDIUMINT NOT NULL DEFAULT 800,
			`rank` TINYINT NOT NULL DEFAULT 0,
			`avatar` VARCHAR(128) NOT NULL DEFAULT '',
			`group_id` MEDIUMINT DEFAULT 0,
			`email_show` TINYINT(3) DEFAULT 1,
			`smilies_show` TINYINT(3) DEFAULT 1,
			`timezone` FLOAT NOT NULL DEFAULT 0,
			`links` TEXT,
			`no_viewer` TINYINT(3) DEFAULT 0,
			`IP` TEXT DEFAULT NULL,
			PRIMARY KEY (ID),
			UNIQUE KEY usrname (usrname)
			) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci";
		$result = db_query($sql);
		sql_check($result, 'members');

		// Set up admin account.  Add if it doesn't exist, and allow more than
		// one owner if installing multiple boards.
		$result = db_query("SELECT COUNT(*) FROM `{$db_mem}oekaki` WHERE usrname='{$usrname}'");
		if (!$result || db_result($result) < 1) {
			$sql_usrname     = db_escape(stripslashes($usrname));
			$sql_admin_email = db_escape(stripslashes($admin_email));
			$sqlstr = "INSERT INTO `{$db_mem}oekaki` (usrname, usrpass, usrflags, email, name, comment, joindate, language, rank) VALUES ('$sql_usrname', '$passenc', 'GDMIU', '{$sql_admin_email}', 'Administrator', 'Administrator Account', CURDATE(), 'english', 9)";
			$result = db_query($sqlstr);
			sql_check($result, 'admin');
		}

		$sqlstr = db_query("CREATE TABLE IF NOT EXISTS `{$db_p}oekakicache` (
			`name` VARCHAR(50) NOT NULL,
			`built` INT(10) UNSIGNED NOT NULL DEFAULT 0,
			`expires` INT UNSIGNED NOT NULL DEFAULT 0,
			`data` TEXT,
			PRIMARY KEY (`name`),
			UNIQUE KEY `name` (`name`)
			) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		sql_check($result, 'cache');

		$result = db_query("CREATE TABLE IF NOT EXISTS `{$db_mem}oekakichat` (
			`ChatID` INT(11) NOT NULL AUTO_INCREMENT,
			`usrname` VARCHAR(255) NOT NULL DEFAULT '',
			`comment` TEXT,
			`posttime` TIMESTAMP NOT NULL,
			`hostname` TEXT,
			`email` VARCHAR(255) DEFAULT NULL,
			`url` TEXT,
			`IP` TEXT,
			`postname` VARCHAR(50) NOT NULL DEFAULT '',
			PRIMARY KEY (ChatID)
			) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		sql_check($result, 'chat');

		$result = db_query("CREATE TABLE IF NOT EXISTS `{$db_p}oekakicmt` (
			`ID_3` INT(11) NOT NULL AUTO_INCREMENT,
			`PIC_ID` VARCHAR(11) NOT NULL DEFAULT '',
			`usrname` VARCHAR(255) NOT NULL DEFAULT '',
			`comment` TEXT,
			`postdate` DATETIME DEFAULT NULL,
			`hostname` TEXT,
			`email` VARCHAR(255) DEFAULT NULL,
			`url` TEXT,
			`IP` TEXT,
			`postname` VARCHAR(50) NOT NULL DEFAULT '',
			`edited` DATETIME,
			`editedby` VARCHAR(255),
			PRIMARY KEY (ID_3),
			KEY PIC_ID (PIC_ID)
			) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		sql_check($result, 'comment');

		$sql = "CREATE TABLE IF NOT EXISTS `{$db_p}oekakidta` (
			`ID_2` INT(11) NOT NULL AUTO_INCREMENT,
			`PIC_ID` INT(11) NOT NULL DEFAULT 0,
			`usrname` VARCHAR(255) NOT NULL DEFAULT '',
			`comment` TEXT,
			`postdate` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
			`hostname` TEXT,
			`email` VARCHAR(255) DEFAULT NULL,
			`url` TEXT,
			`IP` TEXT,
			`title` VARCHAR(255) DEFAULT NULL,
			`lastcmt` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
			`adult` TINYINT(2) NOT NULL DEFAULT 0,
			`edittime` INT(20) unsigned DEFAULT NULL,
			`animation` TINYINT(2) DEFAULT 0,
			`datatype` TINYINT(2) DEFAULT 0,
			`archive` TINYINT(2) NOT NULL DEFAULT 0,
			`postlock` TINYINT(4) NOT NULL DEFAULT 0,
			`threadlock` TINYINT NOT NULL DEFAULT 0,
			`px` MEDIUMINT DEFAULT 0,
			`py` MEDIUMINT DEFAULT 0,
			`ptype` VARCHAR(4),
			`ttype` VARCHAR(4),
			`rtype` VARCHAR(4),
			`usethumb` TINYINT NOT NULL DEFAULT 0,
			`password` TEXT,
			`origart` VARCHAR(255) NOT NULL DEFAULT '',
			`edited` DATETIME,
			`editedby` VARCHAR(255),
			`uploaded` TINYINT NOT NULL DEFAULT 0,
			PRIMARY KEY (ID_2),
			UNIQUE KEY PIC_ID (PIC_ID)
			) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci";
		$result = db_query($sql);
		sql_check($result, 'data');

		$result = db_query("CREATE TABLE IF NOT EXISTS `{$db_mem}oekakionline` (
			`OID` BIGINT(255) NOT NULL AUTO_INCREMENT,
			`onlineusr` VARCHAR(100) NOT NULL DEFAULT '',
			`onlinetime` TIMESTAMP NOT NULL,
			`onlineIP` TEXT,
			`locale` VARCHAR(30) DEFAULT NULL,
			`onlineboard` TEXT,
			PRIMARY KEY (OID),
			UNIQUE KEY onlineusr (onlineusr)
			) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		sql_check($result, 'online');

		$result = db_query("CREATE TABLE IF NOT EXISTS {$db_mem}oekakilog (
			`ID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`category` VARCHAR(255) NOT NULL DEFAULT '',
			`member` VARCHAR(255) NOT NULL DEFAULT '',
			`affected` VARCHAR(255),
			`value` TEXT,
			`board` VARCHAR(255) NOT NULL DEFAULT '',
			`date` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
			PRIMARY KEY (ID)
			) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		sql_check($result, 'log');

		$result = db_query("CREATE TABLE IF NOT EXISTS `{$db_mem}oekakimailbox` (
			`MID` INT(11) NOT NULL AUTO_INCREMENT,
			`sender` VARCHAR(255) NOT NULL DEFAULT '',
			`reciever` VARCHAR(255) NOT NULL DEFAULT '',
			`subject` TEXT,
			`body` LONGTEXT,
			`mstatus` smallINT(10) NOT NULL DEFAULT '0',
			`senddate` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00',
			PRIMARY KEY (MID),
			KEY sender (sender),
			KEY reciever (reciever)
			) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"); 
		sql_check($result, 'mailbox');

		$result = db_query("CREATE TABLE IF NOT EXISTS `{$db_p}oekakimisc` (
			`MiscID` INT(11) NOT NULL AUTO_INCREMENT,
			`miscname` VARCHAR(255) NOT NULL DEFAULT '',
			`miscvalue` BIGINT(255) DEFAULT '0' NOT NULL,
			`miscstring` VARCHAR(14),
			PRIMARY KEY (MiscID),
			KEY miscname (miscname)
			) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		sql_check($result, 'misc');

		/* OBSOLETE
		$result = db_query("CREATE TABLE IF NOT EXISTS `{$db_mem}oekakichatmod` (
			`usrname` VARCHAR(255) NOT NULL DEFAULT '',
			`command` VARCHAR(32) NOT NULL DEFAULT 'MESSAGE',
			`comment` TEXT,
			`posttime` TIMESTAMP NOT NULL,
			`hostname` TEXT,
			`email` VARCHAR(255) DEFAULT NULL,
			`url` TEXT,
			`IP` TEXT,
			`postname` VARCHAR(50) NOT NULL DEFAULT '',
			PRIMARY KEY (posttime)
			) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		sql_check($result, 'chat_mod');
		*/

		/* OBSOLETE
		$result = db_query("CREATE TABLE IF NOT EXISTS `{$db_mem}oekakichatmodusers` (
			`usrname` VARCHAR(255) NOT NULL,
			`lastmessage` TIMESTAMP NOT NULL,
			`afk` ENUM('no','yes') NOT NULL DEFAULT 'no',
			PRIMARY KEY (usrname)
			) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		sql_check($result, 'chat_mod_users');
		*/

		$result = db_query("INSERT INTO `{$db_p}oekakimisc` VALUES (1, 'piccount', 0, NULL)");
		sql_check($result, 'piccount');

		$result = db_query("INSERT INTO `{$db_p}oekakimisc` VALUES (2, 'dbversion', 0, '1.5.6')");
		sql_check($result, 'dbversion');

		// UTF-8 comliance marker.  1=parital conversion, 2=converted, 3=installed
		$result = db_query("INSERT INTO `{$db_p}oekakimisc` VALUES (3, 'db_utf8', 3, '')");
		sql_check($result, 'db_utf8');
	}

	db_close();

	web_line( t('finishsetdb'));

?>
		</p>
		<hr />

		<div class="infotable">
			<p><?php tt('noanyerrors');?></p>
			<p><?php tt('anotherboarderr');?></p>
			<p><?php tt('clickbuttonfinal');?></p>
		</div>
		<br />

		<form name="secure_installer" action="index.php" method="post">
			<input name="action" type="hidden" value="secure" class="txtinput" />
			<input name="langs" type="hidden" value="<?php echo $language;?>" class="txtinput" />
<?php if (!$glob['debug']) { ?>
			<input name="submit" type="submit" value="<?php tt('secinst');?>" class="submit" />
<?php } else { ?>
			<input name="submit" type="button" value="<?php tt('secinst');?>" class="submit" />
<?php } ?>
		</form>
	</div>

</body>
</html>
<?php

	exit;
}



// ===================================================================
// No mode specified.  Output installer.
//

// Guess URL for BBS
$script_path = $_SERVER['PHP_SELF'];
if (isset($_SERVER['PATH_INFO'])) {
	// PHP4
	$script_path = $_SERVER['PATH_INFO'];
}
if (isset($_SERVER['ORIG_PATH_INFO'])) {
	// PHP5
	$script_path = $_SERVER['ORIG_PATH_INFO'];
}
$url_guess = $_SERVER['SERVER_NAME'].str_replace('\\', '/', dirname ($script_path));

// Test for correct folder permissions
	if (! (is_writable('resource') && is_writable('templates'))) {
		// Nope.
?>
	<h2 class="header">
		<?php tt('err_install');?> 
	</h2>
	<div class="infotable">
		<p>
			<?php tt('err_temp_resource');?> 
		</p>
	</div>

	</body>
	</html>
<?php
		exit;
	}


// Initialize language
$lang_names = array();
if ($handle = opendir('language')) {
	while (false !== ($file = readdir($handle))) {
		$name = substr($file, 0, -4);
		if (substr($file, -4) === '.php' && $file != 'index.php') {
			$lang_names[] = $name;
		}
	}
	closedir($handle);
}
sort($lang_names);

$langopts = '';
foreach ($lang_names as $name) {
	$name2 = $name;
	if (isset($lang_name_cache[$name])) {
		$name2 = $lang_name_cache[$name];
	}
	if ($name == $language) {
		$langopts .= '					<option value="'.$name.'" selected="selected">'.$name2.'</option>'."\n";
	} else {
		$langopts .= '					<option value="'.$name.'">'.$name2.'</option>'."\n";
	}
}
// End language



// Begin main install page
?>

<div id="banner">
	<h1 style="margin: 0 0 0 .25em; padding: 5px; "><?php tt('wac_inst');?></h1>
</div>

<div id="menubar">
	&nbsp;
</div>

<div id="options">
	<form name="set_language" method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<div style="text-align: center;">
				<?php tt('word_language');?> 

				<select name="langs" onchange="this.form.submit();" class="multiline">
<?php echo $langopts;?>
				</select>

				<input name="go" value="<?php tt('word_submit'); ?>" type="submit" class="submit" />
		</div>
	</form>
</div>

<div id="adminbar">
	&nbsp;
</div>

<hr />
<br />


<div id="contentmain">
	<form name="main_installer_form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<input type="hidden" name="action" value="process" />
		<input name="langs" type="hidden" value="<?php echo $language;?>" />

		<h2 class="header">
			<?php tt('inst_note');?> 
		</h2>

		<div class="infotable">
			<div class="infotable">
				<p><?php tt('assist_install');?></p>
				<p><?php tt('assist_install2');?></p>
			</div>
		</div>
		<br />


		<h2 class="header">
			<?php tt('install_information');?> 
		</h2>

		<div class="infotable">
			<div class="infotable">
				<p><?php tt('mysqldb_wact');?></p>
			</div>
			<br />

			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<span class="req"><?php tt('install_dbhostname');?></span>
			</td>
			<td class="infoenter" valign="top">
				<input type="text" name="dbhost" size="40" value="localhost" class="txtinput" />
			</td>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<span class="req"><?php tt('install_dbname');?></span>
			</td>
			<td class="infoenter" valign="top">
				<input type="text" name="dbname" size="40" class="txtinput" />
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<span class="req"><?php tt('install_dbusername');?></span>
			</td>
			<td>
				<input type="text" name="dbuser" size="40" class="txtinput" />
			<td class="infoenter" valign="top">
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<span class="req"><?php tt('install_dbpass');?></span>
			</td>
			<td class="infoenter" valign="top">
				<input type="password" name="dbpass" size="40" class="txtinput" />
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('dbtablepref');?> 
			</td>
			<td class="infoenter" valign="top">
				<input type="text" name="dbprefix" size="40" value="op_" class="txtinput" />

				<p class="subtext">
					<?php tt('multiboardpref');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('memberpref');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="OekakiPoteto_MemberPrefix" type="text" value="op_" size="40" class="txtinput" />

				<p class="subtext">
					<?php tt('instalmulti');?> 
				</p>
			</td>
			</tr>
			</table>
			<br />

			<div class="infotable">
				<p><?php tt('uninstexist', $_SERVER['PHP_SELF'].'?mode=del&amp;langs='.$language);?></p>
			</div>
		</div>
		<br />



		<h2 class="header">
			<?php tt('install_admininfo');?> 
		</h2>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<span class="req"><?php tt('install_login');?></span>
			</td>
			<td class="infoenter" valign="top">
				<input type="text" name="usrname" size="40" class="txtinput" />
			</td>
			</tr>


			<tr>
			<td class="infoask" valign="top">
				<span class="req"><?php tt('install_password');?></span>
			</td>
			<td class="infoenter" valign="top">
				<input type="password" name="pass" size="40" class="txtinput" />
			</td>
			</tr>


			<tr>
			<td class="infoask" valign="top">
				<span class="req"><?php tt('eprofile_repass');?></span>
			</td>
			<td class="infoenter" valign="top">
				<input type="password" name="pass2" size="40" class="txtinput" />
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
				<td class="infoask" valign="top">
				<?php tt('emaildesc');?> 
			</td>
			<td class="infoenter" valign="top">
				<input type="text" name="admin_email" size="40" class="txtinput" />
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
				<td class="infoask" valign="top">
				<?php tt('able_send_email');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="cut_email" class="multiline">
					<option value="0" selected="selected"><?php tt('option_dont_know');?></option>
					<option value="0"><?php tt('word_yes');?></option>
					<option value="2"><?php tt('word_no');?></option>
				</select>
			</td>
			</tr>
			</table>
		</div>
		<br />


		<h2 class="header">
			<?php tt('install_general');?> 
		</h2>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<span class="req"><?php tt('install_opurl');?></span>
			</td>
			<td class="infoenter" valign="top">
				<input name="op_url" type="text" value="http://<?php echo $url_guess;?>" size="40" class="txtinput" />
				<p class="subtext">
					<?php tt('guessregis');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<span class="req"><?php tt('install_email');?></span>
			</td>
			<td class="infoenter" valign="top">
				<input name="op_email" type="text" size="40" class="txtinput" />

				<p class="subtext">
					<?php tt('install_emailsub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_title2');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="op_title" type="text" value="Wacintaki" size="40" class="txtinput" />

				<p class="subtext">
					<?php tt('install_title2sub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('authorname');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="op_author" type="text" size="40" class="txtinput" />

				<p class="subtext">
					<?php tt('bbsowner');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('picpref');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="op_pre" type="text" value="OP_" size="40" class="txtinput" />

				<p class="subtext">
				<?php tt('picprefexp');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_picdir');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="op_pics" type="text" value="pictures" size="40" class="txtinput" />

				<p class="subtext">
					<?php tt('install_picdirsub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_picstore');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="pic_store" type="text" value="200" size="40" class="txtinput" />

				<p class="subtext">
					<?php tt('install_picstoresub');?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<h2 class="header">
			<?php tt('install_reg');?> 
		</h2>

		<div class="infotable">
			<table>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_gallow');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="guest_post" class="multiline">
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
				</select>

				<p class="subtext">
					<?php tt('install_gallowsub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Require Art -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('requireartsub');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="require_art" class="multiline">
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
				</select>

				<p class="subtext">
					<?php tt('requireartsubyes');?><br />
					<?php tt('requireartsubno');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_rapproval');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="approval" class="multiline">
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
					<option value="force"><?php tt('forceactivate');?></option>
				</select>

				<p class="subtext">
					<?php tt('activateyes');?><br />
					<?php tt('activeno');?><br />
					<b><?php tt('activateforced');?></b>
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('defaultpermis');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="reg_draw" type="checkbox" value="D" checked="checked" /><?php tt('type_daccess');?><br />
				<input name="reg_anim" type="checkbox" value="A" checked="checked" /><?php tt('type_aaccess');?><br />
				<input name="reg_upload" type="checkbox" value="U" /><?php tt('type_uaccess');?> 
			</td>
			</tr>
			</table>
		</div>
		<br />



		<h2 class="header">
			<?php tt('install_dispgen');?> 
		</h2>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('adultrbbs', 18);?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="op_adult" class="multiline">
					<option value="no" selected="selected"><?php tt('word_no');?></option>
					<option value="yes"><?php tt('word_yes');?></option>
				</select>
				<br />

				<p class="subtext">
					<?php tt('adultrbbsdesc', 18);?><br />
					<?php tt('adultrbbsnote', 18);?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('allowpron', 18);?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="guest_adult" class="multiline">
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
				</select>

				<p class="subtext">
					<?php tt('allowpronyes', 18);?><br />
					<?php tt('allowpronno', 18);?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_deftem');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="template" id="template" class="multiline">
<?php

if ($handle = opendir('templates')) {
	while (false !== ($file = readdir($handle))) {
		if (substr($file, -4) === '.php') {
			$my_base = substr($file, 0, -4);
			if (strtolower($my_base) === 'banana') {
?>
				<option value="<?php echo $my_base;?>" selected="selected"><?php echo $my_base; ?></option>
<?php
			} else {
?>
				<option value="<?php echo $my_base;?>"><?php echo $my_base;?></option>
<?php
			}
		}
	}
	closedir($handle);
}

?>
				</select>

				<p class="subtext">
					<?php tt('install_deftemsub');?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Avatars -->
		<h2 class="header">
			<?php tt('word_avatars');?> 
		</h2>

		<div class="infotable">
			<table class="infomain">
			<!-- Enable -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('enableavata');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="use_avatars" class="multiline">
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
				</select>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('allowavatar');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="use_c_avatars" class="multiline">
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
				</select>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('AvatarStore');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="avatar_folder" type="text" size="60" value="avatars" class="txtinput" />

				<p class="subtext">
					<?php tt('changemulti');?><br />
					<?php tt('changemultidesc');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('maxavatar');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="avatar_x" type="text" value="50" size="6" class="txtinput" />
				&times;
				<input name="avatar_y" type="text" value="50" size="6" class="txtinput" />

				<p class="subtext">
					<?php tt('maxavatardesc');?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<h2 class="header">
			<?php tt('install_appletset');?> 
		</h2>

		<div class="infotable">
			<table class="infomain">

			<!-- Max Anim -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_animax');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="max_anim" type="text" value="500000" size="40" class="txtinput" />
				<?php tt('install_bytes');?> 

				<p class="subtext">
					<?php tt('install_animaxsub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Max Upload -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('maxfilesize');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="max_pic" type="text" value="500000" size="40" class="txtinput" />
				<?php tt('install_bytes');?> 

				<p class="subtext">
					<?php tt('maxupfileexp');?><br />
					<?php tt('maxupfileexp2');?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Compression Settings -->
		<h2 class="header">
			<?php tt('compress_title');?> 
		</h2>

		<div class="infotable">
			<div class="infotable">
<?php

if (!extension_loaded('gd')) {
?>
				<p><?php tt('err_nogdlib');?></p>
<?php
}

?>
				<p><?php tt('thumbmodes');?></p>
				<p><?php tt('thumbmodesexp');?></p>
				<p><?php tt('thumbmodesexp2');?></p>
			</div>
			<br />

			<table class="infomain">
			<!-- Layout mode -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('defthumbmode');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="use_thumb" class="multiline">
					<option value="0"><?php tt('word_none');?></option>
					<option value="1"><?php tt('word_layout');?></option>
					<option value="2" selected="selected"><?php tt('word_defscale');?></option>
					<option value="3"><?php tt('word_uniformity');?></option>
				</select>

				<p class="subtext">
					<?php tt('optiontip');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Force Layout -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('forcedefthumb');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="force_thumb" class="multiline">
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
				</select>

				<p class="subtext">
					<?php tt('forcethumbdesc');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Small Thumbnail Size -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('smallthumb');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="thumb_t" type="text" value="120" size="10" class="txtinput" />

				<p class="subtext">
					<?php tt('smallthumbdesc');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Large Thumbnail Size -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('largethumb');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="thumb_r" type="text" value="250" size="10" class="txtinput" />

				<p class="subtext">
					<?php tt('largethumbdesc');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Thumbnail Bytes -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('thumbnailfilesize');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="thumb_bytes" type="text" value="100000" size="10" class="txtinput" />
				<?php tt('install_bytes');?> 

				<p class="subtext">
					<?php tt('thumbsizedesc');?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />


		<h2 class="header">
			<?php tt('finalinstal');?> 
		</h2>

		<div class="infotable">
			<p>
				<strong><?php tt('install_TOS');?></strong>
			</p>

			<p style="text-align: center;">
				<input type="submit" name="Submit" value="<?php tt('word_submit');?>" class="submit" />
			</p>
		</div>
	</form>
</div>
<br />
<br />


<div id="footer" style="text-align: center;">
	<?php tt('install_byline');?><br />
	<?php tt('install_byline2', 'Marc Leveille');?> 
<?php
	if ($language != 'english') {
		echo "		<br />\n		".t('footer_translation')."<br />\n";
	}
?>
</div>


</body>
</html>
<?php

exit();