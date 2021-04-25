<?php // Include only
if (!defined('BOOT')) exit('No bootstrap.  Exiting.');

/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2016 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.4x - Last modified 2016-08-16
*/


// ===================================================================
// Misc
//

// Microtime
if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
	function get_microtime() {
		return microtime(true);
	}
} else {
	function get_microtime() {
		list($usec, $sec) = explode(" ", microtime());
		return (float) $usec + (float) $sec;
	}
}

function diff_microtime($start_time) {
	return sprintf('%.4f', get_microtime() - $start_time);
}


// ===================================================================
// GPC
//

// Wrapper for HTML encoding.
function w_html_chars($in) {
	return htmlspecialchars($in);
}

function w_toalpha($in) {
	// Note underscore
	return preg_replace("/[^a-zA-Z0-9\s_]/", '', $in);
}

// Get GPC value and filter it
// Complex, because PHP arrays and magic quotes are a PITA
// All methods are slashed except 'raw'
// filter (''=>escaped, 'raw'=>plain, 'a'=>alphanumeric, 'i'=>int, 'i+'=>positive_int', 'html'=>html_encoded, 'url'=>url, 'email'=>email)
function w_gpc($key, $filter = '') {
	global $cfg;
	$value = false;

	// Get GPC in order of importance
	// There is no significant cross of GET/POST/COOKIE names
	if (isset($_GET[$key]))
		$value = $_GET[$key];
	if (isset($_POST[$key]))
		$value = $_POST[$key];
	if (isset($_COOKIE[$key]))
		$value = $_COOKIE[$key];

	// Some web objects (like checkboxes) will not be set if value is blank.  Make sure ints are returned properly!
	if ($value === false) {
		if ($filter == 'i') {
			return 0;
		} else {
			return null;
		}
	}

	// Severe filter (alphanumeric, w/underscore)
	if ($filter == 'a') {
		$value = is_array($value)
			? array_map('w_toalpha', $value)
			: w_toalpha($value);

		return $value;
	}


	// UTF-8 validation (converts ISO-8859-1)
	if (isset($GLOBALS['cfg_db']['set_charset'])
		&& $GLOBALS['cfg_db']['set_charset'] == 'utf8')
	{
		if (!check_utf8_valid($value)) {
			$value = utf8_encode($value);
		}
	}


	// Handle magic quotes
	if (get_magic_quotes_gpc()) {
		// Remove slashes for raw
		if ($filter == 'raw') {
			$value = is_array($value)
				? array_map('stripslashes', $value)
				: stripslashes($value);
		}
	} else {
		// Add slashes for all except raw and i
		if ($filter != 'raw' && $filter != 'i' && $filter != 'i+') {
			$value = is_array($value)
				? array_map('addslashes', $value)
				: addslashes($value);
		}
	}


	// Filter
	$op = '';
	$plus = false;
	switch ($filter) {
		case 'email':
			$op = 'w_validate_email';
			break;
		case 'html':
			$op = 'w_html_chars';
			break;
		case 'i':
			$op = 'intval';
			break;
		case 'i+':
			$op = 'intval';
			$plus = true;
			break;
		case 'raw':
			break;
		case 'url':
			$op = 'w_validate_url';
			break;
		default:
			$op = $filter;
			break;
	}
	if ($filter == 'html') {
		// Remove slashes only from double quotes
		if (is_array($value)) {
			for ($i = 0; $i < count($value); $i++) {
				$value[$i] = str_replace('\\"', '"', $value[$i]);
			}
		} else {
			$value = str_replace('\\"', '"', $value);
		}
	}

	// Main filter
	if (!empty($op)) {
		$value = (is_array($value))
			? array_map($op, $value)
			: $op($value);
	}

	// Positive ints only?
	if ($plus) {
		$value = (is_array($value))
			? array_map('abs', $value)
			: abs($value);
	}

	return $value;
}


function w_set_cookie($name, $value, $exp = false) {
	// Always call after hacks/cfg
	global $glob;

	if ($exp === false) {
		$exp = time() + $glob['cookie_life'];
	} else {
		// Session cookie
		$exp = 0;
	}

	return setcookie($name, $value, $exp, $glob['cookie_path'], $glob['cookie_domain']);
}


function w_unset_cookie($name) {
	// Always call after hacks/cfg
	global $glob;

	return setcookie($name, '', mktime(12, 0, 0, 1, 1, 1970), $glob['cookie_path'], $glob['cookie_domain']);
}


// ===================================================================
// Language
//
function t($res, $args = null) {
	global $lang;

	if (!isset($lang[$res]) || empty($lang[$res])) {
		// Don't use HTML here
		return '?LANG='.$res.'?';
	}

	// Parse only if brackets found
	if ($args === null || strpos($lang[$res], '{') === false) {
		return $lang[$res];
	}

	// Init
	$my_lang = $lang[$res];
	if (!isset($lang['cfg_zero_plural'])) {
		$lang['cfg_zero_plural'] = 1;
	}

	// Parse arguments for substitutions
	if (is_array($args)) {
		$num_args = count($args);
	} else {
		$args = func_get_args();
		$num_args = func_num_args();
	}

	// Parse substitutions
	$tr_array = array();
	for ($i = 1; $i < $num_args; $i++) {
		// Substitutions
		$tr_array['{'.$i.'}'] = $args[$i];
	}
	$my_lang = strtr($my_lang, $tr_array);

	if (strpos($my_lang, '{') === false) {
		return $my_lang;
	}


	// Parse plurals
	for ($j = 2; $j > 0; $j--) {
		// Do this twice, to handle embedded substitutions
		$tr_array = array();
		for ($i = 1; $i < $num_args; $i++) {
			if (strpos($my_lang, '{'.$i.'?') !== false) {
				$sing = '';
				$plur = '';
				//$pattern = '!\{'.$i.'\?(.*?)\}!';
				$pattern = '!\{'.$i.'\?([^\{].*?)\}!';
				$matches = array();
				preg_match_all($pattern, $my_lang, $matches);

				foreach ($matches[1] as $match) {
					list ($sing, $plur) = explode(':', $match);

					if (intval($args[$i]) == 0 && $lang['cfg_zero_plural'] != 1) {
						// Singular zero
						$tr_array['{'.$i.'?'.$match.'}'] = $sing;
					} elseif (intval($args[$i]) == 1) {
						// Singular normal
						$tr_array['{'.$i.'?'.$match.'}'] = $sing;
					} else {
						// Plural
						$tr_array['{'.$i.'?'.$match.'}'] = $plur;
					}
				}
			}
		}
		$my_lang = strtr($my_lang, $tr_array);
	}

	return $my_lang;
}

function tt($res, $args = null) {
	if ($args === null) {
		echo t($res);
		return;
	}

	$pass_args = func_get_args();
	echo t($res, $pass_args);
}

function t_exists($res) {
	global $lang;

	if (strlen($res) > 100) {
		return false;
	}

	if (isset($lang[$res])) {
		return true;
	}
	return false;
}

function get_http_accept_lang() {
	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$al = str_replace(' ', '', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

		preg_match_all('!([a-z]{1,8}(-[a-z]{1,8})?)(;q=(1|0\.[0-9]+))?!i', $al, $matches);

		$hash = array_combine($matches[1], $matches[4]);
		if ($hash === false) {
			return '';
		}

		foreach ($hash as $key => $val) {
			if (empty($val)) {
				$hash[$key] = '1.0';
			}
		}

		arsort($hash, SORT_NUMERIC);
		return strtolower(key($hash));
	}

	return '';
}


// ===================================================================
// SQL
//

// Deprecated since 1.5.0
function w_db_close() {
	return db_close();
}


// ===================================================================
// Cache
//

function get_cache($name) {
	global $db_p;

	$name = db_escape($name);

	$result = db_query("SELECT * FROM {$db_p}oekakicache WHERE name='{$name}'");
	if ($result && db_num_rows($result) > 0) {
		$row = db_fetch_array($result);
		if ($row['expires'] > time()) {
			return unserialize($row['data']);
		}
	}

	return false;
}


function set_cache($name, &$data, $delta = CACHE_EXPIRE) {
	global $db_p;

	$delta = (int) $delta;
	$time = time();
	$expires = $time + $delta;

	if (empty($name)) {
		trigger_error('set_cache(): Empty name &quot;'.$name.'&quot;', E_USER_ERROR);
	}
	if ($expires < $time) {
		trigger_error('set_cache(): Invalid time '.$delta, E_USER_ERROR);
	}

	$name = db_escape($name);

	if (!empty($data)) {
		$result = db_query("SELECT * FROM {$db_p}oekakicache WHERE name='{$name}'");
		if ($result && db_num_rows($result) > 0) {
			$result2 = db_query("UPDATE {$db_p}oekakicache SET built={$time}, expires={$expires}, data='".db_escape(serialize($data))."' WHERE name='{$name}'");
			return $result2 ? true : false;
		} else {
			$result2 = db_query("INSERT INTO {$db_p}oekakicache SET name='{$name}', built={$time}, expires={$expires}, data='".db_escape(serialize($data))."'");
			return $result2 ? true : false;
		}
	}

	return false;
}


function mod_cache($name, $delta) {
	global $db_p;

	if ($delta <= CACHE_EXPIRE_NOW) {
		return del_cache($name);
	}

	$name = db_escape($name);
	$expires = time() + $delta;

	$result = db_query("SELECT * FROM {$db_p}oekakicache WHERE name='{$name}'");
	if ($result && db_num_rows($result) > 0) {
		$result = db_query("UPDATE {$db_p}oekakicache SET expires={$expires} WHERE name='{$name}'");
		return $result ? true : false;
	}

	return false;
}


function del_cache($name) {
	global $db_p;

	if (empty($name)) {
		trigger_error('del_cache(): Empty cache name', E_USER_ERROR);
	}

	$name = db_escape($name);

	$result = db_query("DELETE FROM {$db_p}oekakicache WHERE name='{$name}'");
	return $result ? true : false;
}


function trim_cache() {
	global $db_p;

	$result = db_query("DELETE FROM {$db_p}oekakicache WHERE expires < ".time());
	return $result ? true : false;
}


// Keeps log of latest picture
function latest_refresh() {
	global $cfg;

	$latest = latest_collect_info();
	if ($latest === false || !is_array($latest) || count($latest) < 1) {
		return false;
	}

	if (!isset($cfg['latest_pic_file']) || $cfg['latest_pic_file'] != 'yes') {
		return true;
	}

	$latest_log = '';
	foreach ($latest as $key => $val) {
		$latest_log .= $key .':'.$val."\n";
	}

	if ($fp = fopen($cfg['res_path'].'/'.'latest_pic.txt', 'w')) {
		fwrite($fp, $latest_log);
		fclose($fp);
	} else {
		return false;
	}

	return true;
}

function latest_collect_info() {
	global $cfg, $dbconn, $db_mem, $db_p;
	global $p_prefix, $t_prefix;

	// Get latest pic info
	$result = db_query("SELECT *, UNIX_TIMESTAMP(postdate) AS timestamp FROM {$db_p}oekakidta WHERE postlock=1 ORDER BY postdate DESC LIMIT 1");
	if (!$result) {
		return false;
	}
	$picinfo = db_fetch_array($result);
	db_free_result($result);

	// Get artist info
	$name = db_escape($picinfo['usrname']);
	$result2 = db_query("SELECT * FROM {$db_mem}oekaki WHERE usrname='{$name}'");
	$usrinfo = db_fetch_array($result2);
	db_free_result($result2);


	// Set all the info you want in the latest pic log here
	$latest = array();
	$latest['oekaki']    = latest_strip($cfg['op_title']);
	$latest['url']       = $cfg['op_url'];
	if ($cfg['op_adult'] == 'yes') {
		$latest['adult_board'] = 1;
	} else {
		$latest['adult_board'] = 0;
	}

	// Picture info
	$latest['title']     = latest_nifty($picinfo['title']);
	$latest['date']      = date('Y-m-d', (int) $picinfo['timestamp']);
	$latest['timestamp'] = $picinfo['timestamp'];

	// Image info
	$latest['img_base']  = $cfg['op_url'].'/'.$cfg['op_pics'].'/';
	$latest['img']       = $p_prefix.$picinfo['PIC_ID'].'.'.$picinfo['ptype'];

	if (empty($picinfo['ttype'])) {
		// Wacintaki does not always create T thumbnails
		$latest['img_t'] = $latest['img'];
	} else {
		$latest['img_t'] = $t_prefix.$picinfo['PIC_ID'].'.'.$picinfo['ttype'];
	}

	if ($picinfo['adult'] == 1) {
		$latest['adult_img'] = 1;
	} else {
		$latest['adult_img'] = 0;
	}

	// Artist info
	$latest['artist'] = latest_strip($picinfo['usrname']);
	if (strpos($cfg['avatar_folder'], '..') === false) {
		$latest['av_base'] = $cfg['op_url'].'/'.$cfg['avatar_folder'].'/';
	} else {
		// Count backticks
		$count = 0;
		$tail = preg_replace('!(\.\./)!', '', $cfg['avatar_folder'], -1, $count);

		// Process backticks
		for ($i = 0; $i < $count; $i++) {
			$start = preg_replace('!(/[^/]+)$!', '', $cfg['op_url']);
		}

		$latest['av_base'] = $start.'/'.$tail.'/';
	}
	$latest['av'] = $usrinfo['avatar'];


	set_cache('latest_pic_hash', $latest);

	return $latest;
}

function latest_nifty($in) {
	$in = nifty2_convert($in);
	$in = strip_tags($in);

	return latest_strip($in);
}

function latest_strip($in) {
	$in = str_replace(':', ';', $in);
	return w_html_chars($in);
}


// ===================================================================
// Error reporting
//

// TODO: Why does this cause date() to fail?
// <b>Notice</b>:  <strong>[header.php > :251] 2048</strong> in <b>/home/ninechim/public_html/coonikaki/common.php</b> on line <b>300</b><br />
//
//set_error_handler('w_trigger_error');
//function w_trigger_error($message, $level = E_USER_NOTICE) {
//	$backtrace = debug_backtrace();
//	$info = pathinfo ($backtrace[1]['file']);
//
//	trigger_error("<strong>[{$info['basename']} > {$info['function']}:{$backtrace[1]['line']}] ".$message.'</strong>', E_USER_NOTICE);
//}


// Blank location sends to index.php
// "${loc};in" logs in, "${loc};out" logs out
// Presense of semicolon dictates redirect, so "in" and "out" are not only tags
function all_done($location = 'index.php') {
	global $charset, $metatag_language;

	$url       = $location;
	$r_seconds = 3;

	// Hack to fix IIS cookie issue
	if (strpos($location, ';') !== false) {
		list ($url, $stat) = explode(';', $location);

		if (isset($_SERVER['SERVER_SOFTWARE'])
			&& strpos(strtoupper($_SERVER['SERVER_SOFTWARE']), 'IIS/') !== false) {
			if ($stat == 'in') {
				$mess = t('common_login');
			} elseif ($stat == 'out') {
				$mess = t('common_logout');
			} else {
				$mess = t('common_loginupd');
			}

			if (!empty($charset)) {
				header("Content-Type: text/html; charset={$charset}");
			}
			if (!empty($metatag_language)) {
				header("Content-Language: {$metatag_language}");
			}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>">
	<meta http-equiv="Content-Language" content="<?php echo $metatag_language;?>">
	<meta http-equiv="Refresh" content="<?php echo $r_seconds.';url='.$url;?>">
	<title>Redirect</title>
</head>
<body>
	<p style="text-align: center; padding: 15px; border: 2px black solid;">
		<strong><?php echo $mess;?></strong><br />
		<br />
		<a href="<?php echo $url;?>"><?php tt('common_redirect', $r_seconds);?></a>
	</p>
</body>
</html>
<?php
			w_exit();
		}
	}

	header('Location: '.$url);
	w_exit();
}


function report_err($error = '', $small_header = false) {
	// This function now contains a hack to make all globals accessable
	// to header.php, error.php, and footer.php, rather than using a HTTP
	// redirect.  This solves a number of usability isues.  Memory usage
	// should be negligable since the PHP memory management engine uses
	// pointers internally to manage duplicate variables, and copies by
	// reference only if the values are changed.
	//
	// The hack *may* cause odd results with custom headers, so this is
	// experimental.  If you mod Wacintaki and get errors when calling
	// report_err(), try setting the 'X_DISABLE_REPORT_ERR_HACK' definition
	// in boot.php to "true".
	//
	// Also, for obvious reasons, never use report_err() in header.php,
	// error.php, or footer.php.  This has always been true.


	// Disables global hack for error.php and works like < 1.6.0.
	if (!defined('X_DISABLE_REPORT_ERR_HACK') || X_DISABLE_REPORT_ERR_HACK === true) {
		if (empty($error)) {
			$error = t('common_error');
		}
		if ($small_header) {
			$small_header = '&s=1';
		}

		header('Location: error.php?error='.urlencode($error).$small_header);
		w_exit();
	}

	// Attempt the global extract.  I could probably use extract(), but
	// automatic stuff gives me the willies.
	foreach ($GLOBALS as $key => $value) {
		if ($key{0} !== '_'
			&& $key !== 'GLOBALS'
			&& $key !== 'lang'
			&& $key !== 'HTTP_RAW_POST_DATA')
		{
			$$key = $value;
		}
	}

	$s = $small_header;
	include 'error.php';
	w_exit('Malformed error.php');
}


function w_exit($message = '') {
	db_close();

	if (!empty($message)) {
		echo $message;
	}

	if (isset($GLOBALS['zlib']) && $GLOBALS['zlib']) {
		ob_end_flush();
	}

	exit();
}


/*
	w_log() error logging

	Usage(NOTICE):
		w_log(WLOG_CONST, log_esc($message), $affected);
		w_log(WLOG_CONST, '$l_translation', $affected);
	Usage(STOP):
		w_log(WLOG_CONST, "{$file}:__LINE__: {$text}", $affected);

	Message may contain translations.  Grave accent '`' must be escaped:
		untranslated = 'plain text'
		untranslated = log_esc($user_input)
		translation  = '$l_translatable plain text'
		translation  = '$l_translatable `sub1`sub2`...`'
*/
define('WLOG_MISC', 'misc');
define('WLOG_FAIL', 'fail');         // All errors not related to SQL
define('WLOG_SQL_FAIL', 'sqlfail');  // All SQL errors
define('WLOG_MAINT', 'maintenance'); // Self/cron jobs
define('WLOG_SECURITY', 'security'); // Permission/security/filtering issues
define('WLOG_BANNER', 'banner');
define('WLOG_RULES', 'rules');
define('WLOG_NOTICE', 'notice');
define('WLOG_CPANEL', 'cpanel');
define('WLOG_THUMB_OVERRIDE', 'thumbchange');
define('WLOG_THUMB_REBUILD', 'thumbrebuild');
define('WLOG_MASS_MAIL', 'massmail');
define('WLOG_BAN', 'ban');
define('WLOG_DELETE_USER', 'kill');
define('WLOG_REG', 'registration');
define('WLOG_APPROVE', 'approve');
define('WLOG_EDIT_PROFILE', 'editprofile');
define('WLOG_FLAGS', 'flags');
define('WLOG_PASS_RECOVER', 'passrecover');
define('WLOG_PASS_RESET', 'passreset');
define('WLOG_ARCHIVE', 'archive');
define('WLOG_BUMP', 'bump');
define('WLOG_RECOVER', 'recover');
define('WLOG_DELETE', 'delete');
define('WLOG_LOCK_THREAD', 'lockthread');
define('WLOG_ADULT', 'adult');
define('WLOG_ADMIN_WIP', 'adminwip');
define('WLOG_EDIT_PIC', 'editpicture');
define('WLOG_EDIT_COMM', 'editcomment');
function w_log($category, $value, $affected = '') {
	global $db_p, $db_mem;
	global $OekakiU;
	global $dbconn;
	global $address; // IP address
	global $cfg;

	$log_max_items = 500;

	// Trim log if needed
	$result = db_query("SELECT COUNT(ID) FROM {$db_mem}oekakilog");

	if (intval(db_result($result, 0)) > $log_max_items) {
		$result = db_query("SELECT ID FROM {$db_mem}oekakilog ORDER BY ID LIMIT 1");
		if ($result) {
			$log_del = intval(db_result($result, 0)) + 4;
			db_query("DELETE FROM {$db_mem}oekakilog WHERE ID < $log_del");
		}
	}

	// Fix category if needed
	if (empty($category)) {
		$category = WLOG_MISC;
	}

	// Determine instigator
	if ($category == WLOG_MAINT) {
		$member = '(System)';
	} else {
		$member = '(IP: '.$address.')';
		if (!empty($OekakiU)) {
			$member = addslashes($OekakiU);
		}
	}

	// Add to log
	$board    = db_escape($cfg['op_title']);
	$category = db_escape($category);
	$value    = db_escape($value);
	$affected = db_escape($affected);

	$query = "INSERT INTO {$db_mem}oekakilog SET category='{$category}', value='{$value}', member='{$member}', affected='{$affected}', board='{$board}', date=NOW()";
	$result = db_query($query);

	if ($result) {
		return true;
	}
	if ($category === WLOG_SQL_FAIL) {
		// This SQL error can't be logged to SQL (connection error), so put it
		// in the syslog instead.  This is a worst-case handler, as there are
		// no special sanity checks to limit log length.  $value is always
		// plaintext.
		@error_log($value);
	}
	return false;
}


/*
	w_log() wrapper, used for SQL messages
	Usage:
		w_log_sql('{$file}:'.__LINE__.": ".$text, $member_optional);
		w_log_sql('{$file}: '.$text, $member_optional);
*/
function w_log_sql($value, $affected = '') {
	global $OekakiU;

	if (empty($affected)) {
		$affected = $OekakiU;
	}

	return w_log(WLOG_SQL_FAIL, $value.': ('.db_error().')', $affected);
}


function log_esc($in) {
	$find = array('$', '`');
	$repl = array('&#36;', '&#96;');

	return str_replace($find, $repl, $in);
}


function log_decode($in) {
	$hash = '&bd5azcte97v;';
	$find = array('&#36;', '&#96;');
	$repl = array('$', '`');

	if (strpos($in, '$') === false) {
		// No translation
		return str_replace($find, $repl, $in);
	}

	// Parse $ (only one is allowed)
	$matches = array();
	preg_match('!(\$\w+)!', $in, $matches);

	$my_t = $matches[1];
	$in = str_replace($my_t, $hash, $in);
	$my_t = str_replace('$', '', $my_t);

	// Parse `x`y`...`
	$subs = array();
	$start = strpos($in, '`');
	if ($start !== false) {
		// Find subs and remove them
		$subs = substr($in, $start, strrpos($in, '`') - $start);

		// Note extra grave.  explode() will create an extra empty
		// array element if there is a trailing delimiter
		$in = str_replace($subs.'`', '', $in);

		// Substitution
		$subs = explode('`', $subs);

		// Fix escapes
		$subs = str_replace($find, $repl, $subs);
	}

	if (count($subs) > 0) {
		$translation = t($my_t, $subs);
	} else {
		$translation = t($my_t);
	}
	
	return str_replace($hash, $translation, $in);
}


// ===================================================================
// Untainting
//
function badChars($text, $type = 'db') {
	// Code from PHP.net

	if ($type == 'db')
		$invalid = array('\'', '"', '$', '#', '&', '+', '=', '*', '/', '\\', ':', ';', '<', '>');
	if ($type == 'file')
		$invalid = array('\'', '"', '$', '#', '?', '=', '*', '/', '\\', ':', '|', '<', '>');

	$garb = array();

	foreach ($invalid as $k) {
		while (($j = strpos($text, $k)) !== false) {
			$garb[] = $text{$j};
			$text = substr($text, 0, $j).substr($text, $j + 1);
		}
	}
	$garb = array_unique($garb);

	return $garb;
}


function w_validate_email($in) {
	// Returns strings only, so use with db calls or w_gpc()
	// Addresses only!  Does not support "mailto:" protocol (which is a URL)

	// IMPORTANT: We must trim \n from addresses for PHP's mail() call,
	// or else there is a spam zombie vulnerability (may allow "cc:").
	// The regex takes care of white space
	$in = trim($in);

	// '/^([*+!.&#$|\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})$/i'
	$pattern = <<<EOF
/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
EOF;
	if (preg_match($pattern, $in)) {
		return $in;
	} else {
		return null;
	}
}


function w_validate_url($in) {
	// Returns strings only, so use with db calls or w_gpc()

	// Just for the heck of it
	$in = trim($in);

	// Regex pattern by Benjamin Gray @ regexlib.com
	// Modified by Marc Leveille for PHP5 and to include AIM protocol
	// $1=full, $2=protocol, $3=host, $9=query w/slash
	// Use PHP's parse_url(), not this function, to split parts
	$pattern = <<<EOF
!^((https?|ftp|aim)\://((\[?(\d{1,3}\.){3}\d{1,3}\]?)|(([-a-zA-Z0-9]+\.)+[a-zA-Z]{2,4}))(\:\d+)?(/[-a-zA-Z0-9._?,'+&amp;%$#=~\\\]+)*/?)$!
EOF;

	if (preg_match($pattern, $in)) {
		return $in;
	} else {
		return null;
	}
}


// Prepare path input (UNIX/URI only)
function strip_path($in) {
	// Strip trailing slash(es)
	$in = preg_replace('/\/+$/', '', $in);

	// Strip root, but not backtick
	$in = preg_replace('/^(\.\/)+/', '', $in);

	return trim($in);
}


function fix_range($low, $high, $number) {
	// Clip to a given range (no arrays. I'm lazy)
	// Don't confuse with PHP range() function!
	if ($number < $low)
		return $low;
	if ($number > $high)
		return $high;
	return $number;
}


function check_utf8_valid(&$in) {
	// Returns TRUE if the string is valid UTF-8 and FALSE otherwise.
	// Referenced from http://w3.org/International/questions/qa-forms-utf-8.html

	// Todo: make a proper fix for the backtrack limit problem.
	// For now, limit PREG to strings less than 6K or PHP may crash.

	if (strlen($in) < 6000) {
		return preg_match('%^(?:
			  [\x09\x0A\x0D\x20-\x7E]            # ASCII
			| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
			|  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
			| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
			|  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
			|  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
			| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
			|  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
			)*$%xs', $in);
	} else {
		return true;
	}
}


// ===================================================================
// File, stream, PHP wrappers
//
// Checks for empty file
function file_not_empty($in) {
	if (file_exists($in) && filesize($in) > 0) {
		return 1;
	} else {
		return 0;
	}
}


function file_unlock($in, $force = false) {
	/*
		Wrapper for unlink().  $force bypasses wrapper.

		UNIX:  Helps to prevent permission errors with unwritable files.  These files may usually be deleted, even if they cannot be opened for writing.

		WIN32:  PHP has a problem with its file virtualization layer which causes slow deletes.  Attempting to fopen() a deleted file within a few seconds of deleting it will return a permission denied error.
	*/

	if (file_exists($in)) {
		if (!$force && PLATFORM_OS_WIN) {
			// OK, this is Windows, and PHP doesn't like it
			$temp = dirname($in).'/'.'junk_'.rand(0, 32768).'.tmp';

			if (!@rename($in, $temp)) {
				// This sucks, but is rare
				if (!@unlink($in)) {
					return false;
				}
			} else {
				if (!@unlink($temp)) {
					return false;
				}
			}
		} else {
			// UNIX, or brute force
			if (!@unlink($in)) {
				return false;
			}
		}
	}
	return true;
}


function w_group_readable($file, $new_chmod = '0644') {
	if (defined('GROUP_READABLE') && GROUP_READABLE) {
		if (file_exists($file)) {
			if (@chmod($file, octdec($new_chmod))) {
				return true;
			}
		}
		return false;
	}
}


function get_filetype($data, $type = '') {
	// Get types in approx. order of popularity
	// If no match, use file ext as type

	if (substr($data, 0, 8) == "\x89PNG\x0D\x0A\x1A\x0A") {
		if (strpos($data, 'IEND') !== false) {
			// Not truncated
			$type = 'png';
		}
	} elseif (substr($data, 0, 2) == "\xFF\xD8") {
		// (JFIF)
		$type = 'jpg';
	} elseif (substr($data, 0, 3) == 'GIF') {
		$type = 'gif';
	} elseif (substr($data, 0, 2) == 'BM') {
		$type = 'bmp';
	} elseif (substr($data, 0, 23) == "\x00\x00\x00\x0CjP\x20\x20\x0D\x0A\x87\x0A\x00\x00\x00\x14ftypjp2") {
		$type = 'jp2';
	} elseif (substr($data, 0, 4) == 'RIFF' && substr($data, 8, 4) == 'WEBP') {
		$type = 'webp';
	} elseif (substr($data, 0, 4) == '8BPS') {
		// Note: 8-bit and 16-bit images have same header
		$type = 'psd';
	} elseif (substr($data, 0, 2) == 'PK') {
		$type = 'zip';
	} elseif (substr($data, 0, 3) == 'FWS') {
		$type = 'swf';
	} elseif (substr($data, 0, 3) == 'CWS') {
		$type = 'swf';
	}

	return $type;
}


function clean_safety_saves() {
	global $cfg;
	global $dbconn, $db_p;

	// Check for old safety saves
	$wip_kill = db_query("SELECT PIC_ID, usrname FROM {$db_p}oekakidta WHERE postlock=0 AND (DATE_ADD(postdate, INTERVAL {$cfg['safety_storetime']} DAY) <= NOW()) LIMIT 0, 5");

	$total = db_num_rows($wip_kill);

	if ($total) {
		include_once 'paintsave.php';
		while ($result = db_fetch_array($wip_kill)) {
			kill_picture_slot($result['PIC_ID']);
			w_log(WLOG_MAINT, 'WIP expire: '.$result['PIC_ID'], $result['usrname']);
		}
	}
}


require 'mail_utf8.php';
function w_mail($email, $subject, $message) {
	global $cfg;

	$from = $cfg['op_title'].' <'.$cfg['op_email'].'>';

	$out = utf8_mail($from, $email, $subject, $message);

	if (defined('DISABLE_EMAIL_CONFIRMATION') && DISABLE_EMAIL_CONFIRMATION == 1) {
		return true;
	}
	return $out;
}


// ===================================================================
// Entropy/Passwords
//
function w_transmission_hash($key) {
	// Non-cryptographic hasher.  Use for nonces, guest passports, etc.
	// Do not use for passwords.

	return crypt($key, w_get_random_salt(2, true));
}


function w_transmission_verify($key, $hash) {
	// Non-cryptographic hash test.
	// Do not use with passwords.

	return $hash === crypt($key, $hash);
}


function w_password_hash($pass) {
	// Using a good hash type has is a bit troublesome, as "good" hashing has
	// only been added to PHP within recent years, and some systems will use
	// different hashing types depending on the sysadmin's preferences.
	// Wacintaki < 1.6.0 used only baseline DES crypt() hashes with a
	// fixed 2-character salt, due to the poor way salts were set in the
	// installer.  Using an invalid salt (of the wrong form/length) can cause
	// a number of compatibility issues or make PHP complain.
	//
	// Wacintaki now tries to make the best hash it can while fitting within
	// a 60-character hash length.  Default type and cost are tuned for shared
	// hosts, typical for Wacintaki installs, which means low CPU usage.

	$my_cost = 4;

	if (PASSWORD_STRENGTH >= 2) {
		// Blowfish

		if (function_exists('password_hash')) {
			return password_hash($pass, PASSWORD_BCRYPT, array('cost' => $my_cost));
		}

		elseif (version_compare(PHP_VERSION, '5.3.7') >= 0) {
			// Compatible with above hasher.  5.3.7 only, as I don't want to
			// deal with older PHP with the Blowfish $2a$ bug.

			$salt  = '$2y$'.sprintf('%02d', $my_cost).'$';
			$salt .= w_get_random_salt(22);

			return crypt($pass, $salt);
		}
	}

	if (PASSWORD_STRENGTH >= 1) {
		// Extended DES, about 3x faster than Blowfish
		return crypt($pass, '_WP..'.w_get_random_salt(4, true));
	}

	// DES, the old way, about 6x faster than Blowfish
	return crypt($pass, w_get_random_salt(2, true));
}


function w_password_verify($pass, $hash) {
	// This wrapper may eventually include a legacy parameter to enable/disable
	// validation or expiration of old hashes.  Be very careful not to drop
	// support for old passwords too quickly, as oekaki boards are not
	// frequently visited these days and member e-mails may not be recent
	// enough for a person to do an unassisted password recovery.
	//
	// See w_password_hash() comments for more info.

	if (function_exists('password_verify')) {
		if (password_verify($pass, $hash)) {
			return true;
		}
	} else {
		if ($hash === crypt($pass, $hash)) {
			return true;
		}
	}

	// Old password before latin1 -> utf8 charset conversion.
	return $hash == crypt(utf8_decode($pass), $hash);
}


function w_test_broken_utf_hash($pass, $hash) {
	// Detects passwords that were broken by the latin-1 -> utf8 charset
	// conversion in Wacintaki 1.5.6.  When found, they should be fixed!

	return $hash == crypt(utf8_decode($pass), $hash);
}


function w_get_random_salt($salt_length, $fast = false) {
	// Based on work by Anthony Ferrara (ircmaxell)
	// https://github.com/ircmaxell/password_compat
	//
	// Windows is shockingly bad at secure random data, so pray your web server
	// isn't the same OS as my dev box.  It's trivial to adapt this code as a
	// generic random generator, but do some research before using it.

	$buffer      = '';
	$buffer_full = false;
	$raw_bytes   = ceil($salt_length * 3 / 4);

	// Prevent a catastrophe
	if ($salt_length < 1) {
		trigger_error('Zero salt chars requested', E_USER_ERROR);
	}
	if ($salt_length > 256) {
		trigger_error('Exceeded salt generation limit of 683 chars (4096 bits)', E_USER_ERROR);
	}
	if ($salt_length < 7) {
		// Important, as small "scratch" salts may be needed for things like
		// guest cookies and nonces.  No need to hit urandom for trival stuff.
		$fast = true;
	}

	// Get some chaos
	if (!$fast) {
		if (function_exists('mcrypt_create_iv') && version_compare(PHP_VERSION, '5.3.0') >= 0) {
			$buffer = mcrypt_create_iv($raw_bytes, MCRYPT_DEV_URANDOM);
			if ($buffer) {
				$buffer_full = true;
			}
		}

		if (!$buffer_full && function_exists('openssl_random_pseudo_bytes')) {
			$buffer = openssl_random_pseudo_bytes($raw_bytes);
			if ($buffer) {
				$buffer_full = true;
			}
		}

		if (!$buffer_full && !PLATFORM_OS_WIN) {
			if (@is_readable('/dev/urandom')) {
				$fp = fopen('/dev/urandom', 'rb');
				if ($fp) {
					$buffer = @fread($fp, $raw_bytes);
					@fclose($fp);
				}
				if ($buffer) {
					$buffer_full = true;
				}
			}
		}
	}

	// Get some worse chaos
	if (!$buffer_full) {
		// Packing is much, much faster than "chr(mt_rand(0, 255))"
		//
		// We need 22 base64 chars (132 bits) to get a 128 bit salt, and to
		// generate 22 base64 chars we need to make two 128 bit packs and
		// throw away the trailing bits.
		//
		// Bit ratio of 8:6 (~1.333) * 16 bytes per pack
		//   = ~21.333 base64 chars per pack.
		//
		// or: "ceil(132 / 128 * $salt_length / 22)".
		//
		$packs = ceil($salt_length / 21.33);
		for ($i = 0; $i < $packs; $i++) {
			$buffer .= pack('N4', mt_rand(), mt_rand(), mt_rand(), mt_rand());
		}
		$buffer_full = true;
	}

	// crypt() salt and base64 charsets are identical except for "+"
	$buffer = str_replace('+', '.', base64_encode($buffer));

	// Allows for 16-bit character sets
	if (function_exists('mb_substr')) {
		return mb_substr($buffer, 0, $salt_length, '8bit');
	} else {
		return substr($buffer, 0, $salt_length);
	}
}


// ===================================================================
// Encoders/Decoders
//

function parse_flags($usrflags, $rank = 0) {
	// For legacy reasons, keep the full flags declaration here, even though
	// boot uses array_merge().

	$flags = array(
		'all'    => $usrflags,
		'member' => 0,
		'G'      => 0,
		'P'      => 0,
		'mod'    => 0,
		'admin'  => 0,
		'sadmin' => 0,
		'owner'  => 0,
		'rank'   => 0,
		'D'      => 0,
		'M'      => 0,
		'I'      => 0,
		'U'      => 0,
		'X'      => 0
	);

	// Assign each flag character as a key in $flags array
	$len = strlen($usrflags);
	for ($i = 0; $i < $len; $i++) {
		$chr = $usrflags[$i];
		$flags[$chr] = $chr;
	}

	// Fix membership
	if ($flags['G']) {
		$flags['member'] = 1;
	}

	// Get admin flags
	$flags['rank'] = (int) $rank;
	if ($flags['rank'] >= RANK_MOD)
		$flags['mod']    = 1;
	if ($flags['rank'] >= RANK_ADMIN) {
		$flags['A'] = 1;
		$flags['admin']  = 1;
	}
	if ($flags['rank'] >= RANK_SADMIN) {
		$flags['S'] = 1;
		$flags['sadmin'] = 1;
	}
	if ($flags['rank'] >= RANK_OWNER) {
		$flags['O'] = 1;
		$flags['owner']  = 1;
	}

	return $flags;
}


function get_age($in) {
	// Check if an age already.
	// Age has no dashes.

	if (empty($in)) {
		return '';
	}
	if (strpos($in, '-') === false) {
		return (int) $in;
	}

	// Age from birthday
	// Thanks to Jean-Marc on the LiquidPulse.net forum
	list ($year, $month, $day) = explode('-', $in);
	$z = -5; // Timezone

	$z = time() + $z * 3600;
	$yeardiff  = gmdate('Y', $z) - (int) $year;
	$monthdiff = gmdate('m', $z) - (int) $month;
	$daydiff   = gmdate('j', $z) - (int) $day;

	if (($monthdiff < 0) || (($monthdiff == 0) && ($daydiff < 0) )) {
		$age = $yeardiff - 1;
	} else {
		$age = $yeardiff;
	}

	return $age;
}


function decode_birthday($year, $month, $day) {
	$year  = (int) $year;
	$month = (int) $month;
	$day   = (int) $day;

	if ($year < 1900) {
		return '';
	}

	if ($month < 1 || $month > 12) {
		$month = '??';
	} else {
		$month = sprintf('%02d', $month);
	}
	if ($day < 1 || $day > 31) {
		$day = '??';
	} else {
		$day = sprintf('%02d', $day);
	}

	return $year.'-'.$month.'-'.$day;
}


function age_to_date($in) {
	if (!isset($GLOBALS['datef'])) {
		$datef = array();
		$datef['birthdate'] = 'Y/n/j';
		$datef['birthmonth'] = 'Y/n';
		$datef['birthyear'] = 'Y';
	} else {
		global $datef;
	}

	if (empty($in)) {
		return '';
	}
	if (strpos($in, '-') === false) {
		// Old "age" format will not work at all.
		return '';
	}

	list ($year, $month, $day) = explode('-', $in);
	$my_filter = $datef['birthdate'];
	if ($day == '??') {
		$month = (int) $month;
		$day = 15;
		$my_filter = $datef['birthmonth'];
	}
	if ($month == '??') {
		$month = 6;
		$my_filter = $datef['birthyear'];
	}

	return date($my_filter, strtotime($year.'/'.$month.'/'.intval($day)));
}


// Determine best "image_size" param for oekaki applets.
function applet_image_size_param($size_x, $size_y, $ratio = .35) {
	/*
	Input: canvas size (x, y), ratio (optional)
	Output: max filesize, in K

	A lot of testing with actual oekakis showed me that for images larger than 10K, average PNG compression is between .20 to .25, while average JPEG is .12 (low quality) to .25 (medium quality).  It's reasonable to say that if a PNG is getting a worse compression ratio than this, it is the wrong format, and JPEG should be used.  We'll use .35 as the ratio, since the "original" images should still be treated as archive quality.  This is intended to reduce server space usage -- let the thumbnail generator worry about page-view bandwidth.
	*/

	$base_size = 20; // Minimum return, in K
	$area = $size_x * $size_y * 3;
	$max_file_size = round(($area * $ratio) / 1024);

	return $max_file_size > $base_size ? $max_file_size : $base_size;
}


// Replace '@' in e-mails
function email_code($in, $clickable = true) {
	// '&middot;' == Win32 '&#149;'
	// '&#064;' == ASCII '@'
	// '&bull' == '&#8226';

	$at_symbol = ($clickable) ? '&#064;' : ' &bull; ';
	return str_replace('@', $at_symbol, $in);
}


// ===================================================================
// Web printing functions
//
function p_line($line) {
	echo '<p>'.$line."</p>\n";
}


function web_line($line = '') {
	echo $line."<br />\n";
}


// Version number processing
function version_split($input) {
	// Returns keyed array of "usable" numbers
	// INPUT: 'modname-x.y.z', 'x.y.z', 'modname-x.y', 'x.y' patterns
	/* OUTPUT:
		'mod'=>'modname'
		'ver'=>"%03d%03d%03d" (5.12.14 = 005012014),
		'major'=>major num
		'minor'=>minor num
		'rev'=>revision num (build)
	*/

	if ($input == 'Wac1.0') {
		// 'Wac1.0' fix
		return array('mod'=>'wac', 'ver'=>1000000, 'major'=>1, 'minor'=>0, 'rev'=>0 );
	}

	// Note reverse search, so dashes can be in mod name
	$split = strrpos($input, '-');

	if ($split) {
		// If there's a dash, get the mod name
		$ver_name = substr($input, 0, $split);
		$ver_num  = substr($input, $split + 1);
		$small = explode('.', $ver_num);
	} else {
		$small = explode('.', $input);
	}

	if (count($small) > 1) {
		// Major & minor required, revision optional
		if (!defined($small[2])) {
			$small[2] = 0;
		}
		$ver_num = ($small[0] * 1000000) + ($small[1] * 1000) + intval($small[2]);

		return array('mod'=>$ver_name, 'ver'=>$ver_num, 'major'=>$small[0], 'minor'=>$small[1], 'rev'=>$small[2] );
	} else {
		// Not a valid number!
		return 0;
	}
}


// Returns a string from an array, with seperators and delimiters.
function make_list($array, $sep, $delimiter = '') {
	if (is_scalar($array)) {
		return $delimiter.$array.$delimiter;
	}

	return $delimiter.implode($delimiter.$sep.$delimiter, $array).$delimiter;
}


// "Not Empty" Echo.  Prints nothing, or alternate $if_empty, if $string is empty.
function ne_echo($string, $if_empty, $format = '') {
	$string = trim($string);
	if (!empty($string)) {
		if (!empty($format)) {
			echo str_replace('{s}', $string, $format);
		} else {
			echo $string;
		}
	} else {
		echo $if_empty;
	}
	return;
}


// Deluxe page numbers
// Takes a keyed array of paramaters ($in) and returns an HTML-formatted block of page numbers
function full_page_numbers($first, $current, $total, $display, $link, $in) {
	/*
	$first = First page number.  Some BBSes use 0 as the first page, others 1.
	$display = Number of pages to show per side of the current page.  0 = use default.

	$in = array:
	prev_enabled  => Linked "Prev", parsed for '{page}'
	prev_disabled => Unlinked "Prev"
	next_enabled  => Linked "Next", parsed for '{page}'
	next_disabled => Unlinked "Next"
	button_sep    => Seperates Prev/Next from numbers
	link_format   => Link format (for classes, etc), parsed for '{page}'
	active_format => Span for current [active] number, parsed for '{page}'
	elipses       => Seperator for first/last numbers and all others
	page_sep      => Seperator between numbers
	*/

	// Fixes offset issue with some BBSes
	function offset_replace($offset, $number, $string) {
		$old = array('{page_link}', '{page}');
		$new = array($number + $offset, $number);
		return str_replace($old, $new, $string);
	}

	// Set up
	$output = '';
	if ($display < 1) {
		$display = 4;
	}
	if ($first == 0) {
		$offset = -1;
		$first += 1;
		$current += 1;
	} else {
		$offset = 0;
	}
	$page_link = str_replace('{link}', $link, $in['link_format']);
	$in['prev_enabled'] = str_replace('{link}', $link, $in['prev_enabled']);
	$in['next_enabled'] = str_replace('{link}', $link, $in['next_enabled']);

	// Get our extremes
	$list_start = 1;
	$list_end   = $total;
	if ($current > $display + 1) {
		$list_start = $current - $display;
	}
	if ($total - $current > $display) {
		$list_end = $current + $display;
	}

	// Print start block
	if ($current > 1) {
		$output .= offset_replace($offset, $current - 1, $in['prev_enabled']);
	} else {
		$output .= $in['prev_disabled'];
	}
	$output .= $in['button_sep'];

	// Print start number?
	if ($list_start > 1) {
		$output .= offset_replace($offset, 1, $page_link);
		$output .= $in['elipses'];
	}

	// Print all numbers
	for ($i = $list_start; $i <= $list_end; $i++) {
		if ($i == $current) {
			$output .= offset_replace($offset, $i, $in['active_format']);
		} else {
			$output .= offset_replace($offset, $i, $page_link);
		}
		if ($i != $list_end) $output .= $in['page_sep'];
	}

	// Print end number?
	if ($list_end < $total) {
		$output .= $in['elipses'];
		$output .= offset_replace($offset, $total, $page_link);
	}

	// Print end block
	$output .= $in['button_sep'];
	if ($current < $total) {
		$output .= offset_replace($offset, $current + 1, $in['next_enabled']);
	} else {
		$output .= $in['next_disabled'];
	}

	return $output;
}


// Simple page numbers (no images)
// Easy, format-free wrapper for full_page_numbers()
function quick_page_numbers($first, $current, $total, $display, $link) {
	if (function_exists('t')) {
		$my_prev = t('page_prev');
		$my_next = t('page_next');
		$my_elip = t('page_ellipsis');
		$my_mid  = t('page_middot');
	} else {
		$my_prev = '&lt;&lt;PREV';
		$my_next = 'NEXT&gt;&gt;';
		$my_elip = '&hellip;';
		$my_mid  = '&middot;';
	}

	$page_settings = array(
		'prev_enabled'  => '<a href="{link}">'.$my_prev.'</a>',
		'prev_disabled' => $my_prev,
		'next_enabled'  => '<a href="{link}">'.$my_next.'</a>',
		'next_disabled' => $my_next,
		'button_sep'    => ' '.$my_mid.' ',
		'link_format'   => '<a href="{link}">[{page}]</a>',
		'active_format' => '<b>[{page}]</b>',
		'elipses'       => ' '.$my_elip.' ',
		'page_sep'      => ' '
	);

	return full_page_numbers($first, $current, $total, $display, $link, $page_settings);
}