<?php // Include only
if (!defined('BOOT')) exit('No bootstrap.  Exiting.');

/*
Wacintaki Poteto - Copyright 2014-2018 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.6 - Last modified 2018-03-07

Notes:
	The mysql_* functions have been removed as of 1.6.0, as PHP will soon drop support for this API.

	OO style is not used because all DB calls in Wacintaki are procedural style, and I would like to continue supporting the various mods people have made.  Some mods require the globals, so a proper cleanup isn't that easy.  I've started importing some stuff from the OO database layer I use with my gallery.
*/


// Config must be global for backwards compatibility
if (!defined('PARSE_DBCONN_MANUALLY') || !PARSE_DBCONN_MANUALLY) {
	require 'dbconn.php';
}

// HD Timer for Windows.  Microsecond accuracy only needed for SQL benchmarks.
// For legacy support reasons, you must modify this condition manually, as
// PHP < 5.3 does not support namespaces, which are required by HRTimer.
if (false && PLATFORM_OS_WIN && extension_loaded('HRTime')) {
	define('HR_TIMER', true);
	function get_hrtime_stopwatch() {
		//return new HRTime\StopWatch;
	}
} else {
	define('HR_TIMER', false);
}


// Remap global prefixes
$db_p   = $OekakiPoteto_Prefix;
$db_mem = $OekakiPoteto_MemberPrefix;
$db_op  = $db_p; // Deprecated
$db_pre = $db_p; // Deprecated

// Init
$glob['db_saved_queries']     = array();
$glob['db_num_saved_queries'] = 0;
$glob['db_time_queries']      = 0.0;
$glob['db_max_saved_queries'] = 200;

if (!isset($quiet_mode)) {
	$quiet_mode = false;
}


function db_open($force = false) {
	global $cfg_db, $glob;
	global $dbhost, $dbuser, $dbpass, $dbname;

	$GLOBALS['dbconn'] = @mysqli_connect($dbhost, $dbuser, $dbpass);
	if (!$GLOBALS['dbconn']) {
		$glob['db_saved_queries'][] = array('db_open()', '0', db_backtrace(), mysqli_connect_error() );
		if ($force === false && !$GLOBALS['quiet_mode']) {
			header('Content-type: text/plain');
			exit('Could not connect to the SQL database.  Database: '.htmlspecialchars(mysqli_connect_error() ));
		} else {
			return false;
		}
	}

	if (!db_select($GLOBALS['dbconn'], $dbname)) {
		db_close();
		$glob['db_saved_queries'][] = array('db_select()', '0', db_backtrace(), @mysqli_error($GLOBALS['dbconn']) );

		if ($force === false && !$GLOBALS['quiet_mode']) {
			header('Content-type: text/plain');
			exit('Database name "'.$dbname.'" is invalid.');
		} else {
			return false;
		}
	}

	// Use UTF-8 protocol with DB?
	// Will cause errors if DB tables were created with a different charset.
	// The updater will set 'set_charset' if the DB was converted correctly.
	if (isset($cfg_db['set_charset'])) {
		db_set_charset($cfg_db['set_charset']);
	} else {
		db_set_charset('latin1');
	}

	return $GLOBALS['dbconn'];
}


function db_select($dbconn, $dbname) {
	return mysqli_select_db($dbconn, $dbname);
}


function db_query($sql) {
	global $dbconn, $glob;

	if (HR_TIMER) {
		$c = get_hrtime_stopwatch();
		$c->start();
	} else {
		$q_start = get_microtime();
	}

	$result = mysqli_query($dbconn, $sql);
	$glob['db_num_saved_queries']++;

	if (HR_TIMER) {
		$c->stop();
		$q_time = $c->getLastElapsedTime();
	} else {
		$q_time = get_microtime() - $q_start;
	}

	if ($glob['db_num_saved_queries'] > $glob['db_max_saved_queries']) {
		if (array_shift($glob['db_saved_queries']) !== false) {
			$glob['db_num_saved_queries']--;
		}
	}

	if ($result) {
		$glob['db_saved_queries'][] = array($sql, sprintf('%.5f', $q_time), '', '');

		$glob['db_time_queries'] += $q_time;

		return $result;
	}

	$glob['db_saved_queries'][] = array($sql, '0', db_backtrace(), db_error() );

	if ($glob['debug']) {
		db_print_error();
	}

	return false;
}


function db_result($result, $offset = 0, $field = 0) {
	global $dbconn, $glob;

	/*
		Note: $field is obsolete, as mysqli does not support this.
	*/

	$data = null;
	$array = null;

	if (@mysqli_data_seek($result, $offset)) {
		$array = mysqli_fetch_array($result, MYSQLI_NUM);
		$data = $array[0];
	}

	if ($data === false) {
		if ($glob['debug']) {
			db_print_error();
		} else {
			db_trigger();
		}
	}

	return $data;
}


function db_fetch_array($result, $type = null) {
	if (defined('MYSQLI_ASSOC') && empty($type)) {
		$type = MYSQLI_ASSOC;
	}

	/*
		Notes:

		- Unlike the native mysqli functions, this function always
		returns associative arrays by default.

		- Due to the shifting internal pointer in mysqli_fetch_array(),
		we cannot test for "FALSE" here.  Do not mask errors with "@".
	*/
	return mysqli_fetch_array($result, $type);
}


function db_fetch_row($result) {
	/*
		Note: due to the shifting internal pointer in mysqli_fetch_array(),
		we cannot test for "FALSE" here.  Do not mask errors with "@".
	*/
	return mysqli_fetch_row($result);
}


function db_num_rows($result) {
	return mysqli_num_rows($result);
}


function db_affected_rows() {
	global $dbconn;

	return mysqli_affected_rows($dbconn);
}


function db_insert_id() {
	global $dbconn;

	return mysqli_insert_id($dbconn);
}


function db_free_result($result) {
	global $dbconn;

	return @mysqli_free_result($result);
}


function db_escape($in) {
	global $dbconn;

	if (is_array($in)) {
		return array_map('mysqli_real_escape_string', array_fill(0, count($in), $dbconn), $in);
	} else {
		return mysqli_real_escape_string($dbconn, $in);
	}
}


function db_get_num_queries() {
	global $glob;
	return $glob['db_num_saved_queries'];
}


function db_get_saved_queries() {
	global $glob;

	return $glob['db_saved_queries'];
}


function db_get_saved_query($offset = -1) {
	global $glob;

	$max_offset = db_get_num_queries() - 1;
	if ($offset > $max_offset || $offset < 0) {
		$offset = $max_offset;
	}
	return $glob['db_saved_queries'][$offset];
}


function db_get_time_queries() {
	global $glob;

	return $glob['db_time_queries'];
}


function db_get_charset() {
	global $dbconn;

	return mysqli_character_set_name($dbconn);
}


function db_set_charset($charset = 'utf8') {
	global $dbconn;

	// MySQL is weird, as usual
	if ($charset === 'utf-8') {
		$charset = 'utf8';
	}
	if ($charset === 'iso-8859-1') {
		$charset = 'latin1';
	}

	return mysqli_set_charset($dbconn, $charset);
}


function db_error_array() {
	global $dbconn, $glob;

	if (empty($glob['db_saved_queries'])) {
		return false;
	}

	$errs = end($glob['db_saved_queries']);

	return array(
		'sql'   => $errs[0],
		'trace' => $errs[2],
		'num'   => @mysqli_errno($dbconn),
		'msg'   => $errs[3]
	);
}


function db_error() {
	global $dbconn, $glob;

	if (empty($glob['db_saved_queries'])) {
		return false;
	}

	return @mysqli_error($dbconn);
}


function db_print_error($query = false) {
	$errs = db_error_array();
	if (empty($errs) || $GLOBALS['quiet_mode']) {
		return;
	}

	echo "<strong>".$errs['trace']." &gt; ".$errs['msg']."</strong><br />\n";
	if ($query == true) {
		echo "<strong>".$errs['sql']."</strong><br />\n";
	}
}


function db_backtrace() {
	// Gets backtrace to figure out line number for bad db_*() call.

	if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
	} else {
		$backtrace = debug_backtrace();
	}
	$info = pathinfo($backtrace[1]['file']);

	return $info['basename'].'('.strval($backtrace[1]['line']).')::'.$backtrace[1]['function'];
}


function db_trigger() {
	global $glob;

	$marker = db_backtrace();

	trigger_error($marker, E_USER_WARNING);
	if ($glob['debug']) {
		echo '<b>DB: '.db_error().'</b><br />';
	}

	return;
}


function db_close($unused = 0) {
	// Don't global $dbconn here.  PHP may throw an undeclared variable error.

	$success = false;
	if (isset($GLOBALS['dbconn']) && $GLOBALS['dbconn']) {
		$success = @mysqli_close($GLOBALS['dbconn']);
	} else {
		return true;
	}

	if ($success) {
		$GLOBALS['dbconn'] = false;
		return true;
	}

	return false;
}