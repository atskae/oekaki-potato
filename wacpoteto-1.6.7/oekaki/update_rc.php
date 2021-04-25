<?php
/*
Wacintaki Poteto - Copyright 2011-2019 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.7 - Last modified 2019-02-05
*/

/*
	Notes:

	If using multiple boards on one database, it is only necessary to run this script once.

	The database will have a UTF-8 complaince marker added to the miscscring table.
	{$db_p}oekakimisc.db_utf8 = (1=parital conversion, 2=converted, 3=installed)

	This script will try to convert a 1.5.5 Wacintaki database to UTF-8.  This will update all binary ISO-8859-1 and binary Big5 to binary UTF-8 (since this is all in binary, I call this stage a recode rather than a conversion).  After this recode, the script will then try to change the database charset from latin1 to utf8, which will allow the proper use of mysql[i]_set_charset(), as well as set the proper collation (alphabetical sorting).

	Unfortunately, the recode takes some time, and the script may time out.  There are also problems with resetting the PHP time limit.  You cannot extend the time limit on some servers, and set_time_limit() does not return a value.  The PHP docs say streaming operations don't affect max execution time, but the web server (Apache) may enforce its own limit.  Furthermore, register_shutdown_function() does not work to save dirty records, because all stream operations are disabled (files and database connections).  There is no way around these issues when working with shared servers.  Note that safe mode is not an issue, since the oekaki will not work with safe mode enabled, anyway.

	With these points in mind, this stript will try to convert as much of the database as possible within the script time limit, and keep a list of "dirty" records as it goes, so nothing is double encoded.  Shortly before the known timeout, the dirty records will be written to disk, and shutdown() will verify that the file has been flushed and that a link to restart the recode will be printed.  If the database gets stuck (if we're working with a remote SQL server) and we can't terminate cleanly before shutdown() is called, we may end up with some double-encoded records, but nothing too major.  Hopefully, after running one or more times, the whole database will be recoded.

	I've tried to make the script as quick and efficient as possible without driving myself nuts, so there's a lot of multidimentional arrays and node walking going on.  It's not terribly complicated, but don't be surprised if you get lost.

	I'm using 'utf8_general_ci' not 'utf8_bin' because we need case insensitive collation.  Also, using 'general' makes it easier to search for usernames.  People who don't know how to type in international characters can still search for names by using a similar letter.  This does not affect which names people may choose, but will prevent people from registering a name or adding e-mails that are similar but not exactly the same as those in the database.  I'll leave it to admins to decide how to handle name disputes.
*/


// Set bootstrap flag and fake init, so we can include files
define('BOOT', 1);
$glob = array(
	'debug'   => false,
	'wactest' => false
);


require 'common.php';
require 'config.php';
require 'language/'.$cfg['language'].'.php';


// Config
$rc = array();
$rc['debug']   = false;
$rc['start']   = get_microtime();
$rc['timeout'] = @ini_get('max_execution_time');
$rc['timeout_buffer'] = 4; // Seconds
$rc['scanned_tables'] = 0;
$rc['scanned_rows']   = 0;
$rc['updated_rows']   = 0;
$rc['dirty_filename']   = 'update_rc_tables.tmp.php';
$rc['rows_filename']    = 'update_rc_rows.tmp.php';
$rc['inserts_filename'] = 'update_rc_inserts.tmp.php';
$rc['lock_filename']    = 'update_rc_lock.tmp.php';
$rc['lastrow_filename'] = 'update_rc_lastrow.tmp.php';

// Source charset to convert to UTF-8.
// Leave blank if unknown (default).
$rc['recode_charset'] = '';

// Holds dirty info, written to disk
$rc_dirty = array();
$rc_dirty_rows = array();

// The columns in each table that must be converted
// Used only for building $rc_dirty.
$sql_convert = array(
	'oekaki' => array(
		'ID',
		'usrname',
		'email',
		'name',
		'comment',
		'url',
		'urltitle',
		'location'
	),
	'oekakichat' => array(
		'ChatID',
		'usrname',
		'comment',
		'email',
		'url',
		'postname'
	),
	'oekakicmt' => array(
		'ID_3',
		'usrname',
		'comment',
		'email',
		'url',
		'postname'
	),
	'oekakidta' => array(
		'ID_2',
		'usrname',
		'comment',
		'email',
		'url',
		'title',
		'password',
		'origart'
	),
	'oekakilog' => array(
		'ID',
		'board',
		'member',
		'affected',
		'value'
	),
	'oekakimailbox' => array(
		'MID',
		'sender',
		'reciever', //[sic]
		'subject',
		'body'
	)
);


// ============================================================================
// INIT
//

// Make db connection using new db_layer
// Must set manual parse.  Only prefixes needed after opening database.
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
}


print_header();
if ($rc['debug']) {
	p_line('<strong>DEBUG: debug mode enabled</strong>');
}


// Has the database alreay been recoded?
$result = db_query("SELECT miscvalue FROM {$db_p}oekakimisc WHERE miscname='db_utf8'");
if ($result) {
	$my_marker = (int) db_result($result);
	if ($my_marker > 1) {
		cleanup_tmp_files();

		p_line( t('upr_already_utf'));
		print_footer ('<a href="update.php">'.t('upr_click_run').'</a>');
		w_exit();
	}
}


// Check for IconV to handle Big5 recoding
if ($cfg['language'] === 'chinese') {
	$rc['recode_charset'] = 'big5';

	if (!extension_loaded('iconv')) {
		// IconV is a must.  Almost all servers have it enabled, so this is a rare case.
		p_line( t('upr_iconv_mia'));
		p_line( t('upr_nc_shortest', 'http://www.ninechime.com/forum/'));

		print_footer();
		w_exit();
	}
}


// Make sure we can create temp files.  Do not erase them by accident!
if (!file_exists($rc['dirty_filename'])) {
	create_tmp_file($rc['dirty_filename']);

	if (!file_exists($rc['rows_filename'])) {
		create_tmp_file($rc['rows_filename']);
	}
	if (!file_exists($rc['inserts_filename'])) {
		create_tmp_file($rc['inserts_filename']);
	}
	if (!file_exists($rc['lastrow_filename'])) {
		create_tmp_file($rc['lastrow_filename']);
	}
	if (!file_exists($rc['lock_filename'])) {
		create_tmp_file($rc['lock_filename']);
	}
} else {
	require $rc['dirty_filename'];
	require $rc['rows_filename'];
}


// ============================================================================
// MAIN
//

// Intro
if (!isset($_GET['do'])) {
	p_line( t('upr_conv_w_8bit'));
	p_line( t('upr_xself_mult_warn'));
	p_line( t('upr_no_damage'));

	if (!empty($rc['recode_charset'])) {
		p_line( t('upr_char_det_conv', w_html_chars($rc['recode_charset'])));
		p_line( t('upr_utf_rec_pass'));
	}
	p_line('<a href="'.$_SERVER['PHP_SELF'].'?do=1">'.t('upr_click_steps', 1, 3).'</a>');

	echo "\n<hr />\n\n";

	p_line( t('upr_nc_visit_bypass', 'http://www.ninechime.com/forum/', $_SERVER['PHP_SELF'].'?do=99'));

	print_footer();
	w_exit();
}


// Build array to prepare recode
if ($_GET['do'] == 1) {
	clear_online_list();

	p_line( t('upr_build_res_wait'));

	if (!build_db_dirty_array()) {
		print_footer( t('upr_no_make_temp'));
		w_exit();
	}

	require $rc['dirty_filename'];

	if (!build_db_dirty_rows()) {
		print_footer( t('upr_no_make_temp'));
		w_exit();
	}

	if ($rc['scanned_rows'] == 0) {
		// Nothing to recode!  Set the charset and collation
		set_table_charset_all();

		cleanup_tmp_files();

		p_line( t('upr_done_up'));
		print_footer('<a href="update.php">'.t('upr_click_run').'</a>');
		w_exit();
	}

	p_line( t('upr_found_tbls', $rc['scanned_tables']));
	p_line( t('upr_found_rows', $rc['scanned_rows']));

	p_line('<a onclick="this.style.display=\'none\';document.getElementById(\'switchme\').style.display=\'block\';" href="'.$_SERVER['PHP_SELF'].'?do=2">'.t('upr_click_steps', 2, 3).'</a>');

	echo ('<p id="switchme" style="display: none;">'.t('upr_plz_wait').'</p>'."\n");

	print_footer();
	w_exit();
}


// Build inserts file and change the character set
if ($_GET['do'] == 2) {
	// Create a lock file
	$lock = 0;
	if (file_exists($rc['lock_filename'])) {
		include $rc['lock_filename'];
		if ($lock + $rc['timeout'] > time() ) {
			$time_left = abs(time() - ($lock + $rc['timeout']));
			p_line( t('upr_dbl_click', $time_left));

			if (!$rc['debug']) {
				print_footer('<a href="'.$_SERVER['PHP_SELF'].'?do=2">'.t('upr_click_steps', 1, 3).'</a>');
				w_exit();
			}
		}
	}

	$data = '<'."?php\n\nif (!defined('BOOT')) { exit('No Bootstrap'); }\n\n\$lock = ".strval(time()).";\n\n?".'>';
	create_tmp_file($rc['lock_filename'], $data);

	p_line( t('upr_build_res_wait'));

	// Black box time!
	if (!start_db_recode($rc['recode_charset'])) {
		shutdown('fail');
	}

	@unlink($rc['lock_filename']);

	p_line( t('upr_step_ready_num', 2, 3));

	p_line('<a onclick="this.style.display=\'none\';document.getElementById(\'switchme\').style.display=\'block\';" href="'.$_SERVER['PHP_SELF'].'?do=3">'.t('upr_click_steps', 3, 3).'</a>');

	echo ('<p id="switchme" style="display: none;">'.t('upr_plz_wait').'</p>'."\n");

	print_footer();
	w_exit();
}


// Recode the inserts and add them to the database
if ($_GET['do'] == 3) {

	// Black box time!
	if (!import_db_recode($rc['recode_charset'])) {
		shutdown('fail');
	}

	cleanup_tmp_files();

	p_line( t('upr_done_up'));
	print_footer('<a href="update.php">'.t('upr_click_run').'</a>');
	w_exit();
}

// Override
if ($_GET['do'] == 99) {
	// Set the charset and collation
	if (!$rc['debug']) {
		if (set_table_charset_all() ) {
			set_db_utf8_misc_marker(2);

			cleanup_tmp_files();

			p_line( t('upr_done_up'));
		} else {
			p_line( t('upr_if_err_nc', 'http://www.ninechime.com/forum/'));
		}
	}

	print_footer('<a href="update.php">'.t('upr_click_run').'</a>');
	w_exit();
}

// Bad input?
print_footer('Unknown mode.  <a href="'.$_SERVER['PHP_SELF'].'">Click here to start over.</a>');
w_exit();



// ============================================================================
// FUNCTIONS
//

function check_timeout($lazy = false) {
	global $rc;

	$my_timeout_buffer = $rc['timeout_buffer'];

	if ($lazy) {
		$my_timeout_buffer = 0;
	}

	$time_elapsed = ceil(get_microtime() - $rc['start']);
	$time_allowed = $rc['timeout'] - $my_timeout_buffer;

	if ($time_elapsed + 1 > $time_allowed) {
		// Script timeout
		return false;
	}

	// Continue working
	return true;
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


function print_header() {
	header('Content-type: text/html; charset=utf-8');

?>
<!DOCTYPE html PUBLIC
	"-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8;" />

	<title><?php tt('upr_header_title');?></title>

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


<h1><?php tt('upr_header_title');?></h1>
<hr />


<?php
}


function print_footer($in = '') {
	if (!empty($in)) {
		echo '<p>'.$in.'</p>';
	}
	echo "\n\n</body>\n</html>";
}


function create_tmp_file($in, &$data = '') {
	$fp = @fopen($in, 'w');
	if ($fp) {
		if (!empty($data)) {
			fwrite($fp, $data, strlen($data));
		}
		fclose($fp);
		@chmod ($in, 0664); // So it can be deleted via FTP
		return true;
	}
	print_footer( t('upr_no_make_temp'));
	w_exit();
}


function cleanup_tmp_files() {
	global $rc;

	if ($rc['debug']) {
		p_line('DEBUG: cleanup_tmp_files()');
	} else {
		if (file_exists($rc['dirty_filename'])) {
			@unlink ($rc['dirty_filename']);
		}
		if (file_exists($rc['rows_filename'])) {
			@unlink ($rc['rows_filename']);
		}
		if (file_exists($rc['inserts_filename'])) {
			@unlink ($rc['inserts_filename']);
		}
		if (file_exists($rc['lock_filename'])) {
			@unlink ($rc['lock_filename']);
		}
		if (file_exists($rc['lastrow_filename'])) {
			@unlink ($rc['lastrow_filename']);
		}
	}
}


function shutdown($type = 'good') {
	global $rc, $rc_dirty, $rc_dirty_rows;

	if ($type === 'good') {
		cleanup_tmp_files();

		p_line( t('upr_done_up'));
		print_footer('<a href="update.php">'.t('upr_click_run').'</a>');
		w_exit();
	}

	if ($type === 'fail') {
		print_footer( t('upr_nc_visit_bypass', 'http://www.ninechime.com/forum/', $_SERVER['PHP_SELF'].'?do=99'));
		w_exit();
	}

	if ($type === 'timeout') {
		// We ran out of time.  Write out our dirty column log.
		$rows_dirty = '<'."?php\n\nif (!defined('BOOT')) { exit('No Bootstrap'); }\n\n\$rc_dirty_rows = ";
		$rows_dirty .= var_export($rc_dirty_rows, true);
		$rows_dirty .= ";\n\n?".'>';

		p_line( t('upr_time_partial'));
		p_line( t('upr_rows_partial', $rc['updated_rows']));
		p_line('<a href="'.$_SERVER['PHP_SELF'].'?do=2">'.t('upr_click_resume').'</a>');

		$tries = 3;
		for ($i = 0; $i < $tries; $i++) {
			$fp = @fopen($rc['rows_filename'], 'w');
			if ($fp) {
				fwrite($fp, $rows_dirty);
				fclose($fp);
				$i = $tries;
			} else {
				sleep(1);
			}
		}

		print_footer();
		w_exit();
	}

	if ($type === 'timeout_import') {
		p_line( t('upr_time_norm'));
		print_footer('<a href="'.$_SERVER['PHP_SELF'].'?do=3">'.t('upr_click_resume').'</a>');
		w_exit();
	}

	print_footer('DEBUG: No mode for shutdown('.$type.')');
	w_exit();
}


function clear_online_list() {
	global $rc, $db_mem, $db_p;

	$result = db_query("DELETE FROM {$db_mem}oekakionline");
}


function build_db_dirty_array() {
	// Builds a complete list of columns for all tables that need recoding.
	// Note that the first column is the primary key.  It is used for updates, and is not recoded.

	global $rc;
	global $sql_convert;
	$dirty = array();

	// Commented out, because we need to use the PHP default, not
	// necessarily latin1.
	// db_set_charset('latin1');

	// Tables
	$result = db_query('SHOW TABLES');
	if (!$result) {
		print_footer( t('upr_sql_bad_tbls', w_html_chars(db_error() )));
		w_exit();
	}
	$num_tables = db_num_rows($result);
	$rc['scanned_tables'] = $num_tables;

	if ($num_tables < 1) {
		print_footer( t('upr_sql_no_tbls', ''));
		w_exit();
	}

	$my_tables = array();
	for ($i = 0; $i < $num_tables; $i++) {
		$my_tables[] = db_result($result, $i);
	}


	// Columns
	foreach ($my_tables as $my_table) {
		$result = db_query('SHOW COLUMNS FROM '.$my_table);
		if (!$result) {
			print_footer( t('upr_bad_col', $my_table, w_html_chars(db_error() )));
			w_exit();
		}
		$num_columns = db_num_rows($result);

		if ($num_columns < 1) {
			print_footer( t('upr_no_cols', $my_table));
			w_exit();
		}

		$my_columns = array();
		for ($i = 0; $i < $num_columns; $i++) {
			$my_columns[$i] = db_result($result, $i);
		}
	}

	// Build dirty list
	foreach ($sql_convert as $match_table => $match_column) {
		for ($i = 0; $i < $num_tables; $i++) {
			// Hazy match, right to left
			if (substr($my_tables[$i], -strlen($match_table)) == $match_table) {
				// We have a table match!  Copy the columns and set each to dirty
				foreach ($match_column as $temp) {
					// array('table' => array(offset => 'column'))
					$dirty[$my_tables[$i]][] = $temp;
				}
				break;
			}
		}
	}

	$out = '<'."?php\n\nif (!defined('BOOT')) { exit('No Bootstrap'); }\n\n\$rc_dirty = ";
	$out .= var_export($dirty, true);
	$out .= ";\n\n?".'>';

	$fp = @fopen($rc['dirty_filename'], 'w');
	if ($fp) {
		fwrite($fp, $out);
		fclose($fp);
		return true;
	}

	return false;
}


function build_db_dirty_rows() {
	// Builds resource file that keeps track of which rows and
	// columns to recode, and which have yet to be done.
	// Assigns which columns to be done by using a MySQL REGEXP,
	// where 1=recode, 0=okay

	global $rc;
	global $rc_dirty;
	$dirty = array();

	// Commented out, because we need to use the PHP default, not
	// necessarily latin1.
	// db_set_charset('latin1');

	foreach ($rc_dirty as $table_name => $column_array) {
		$num_columns = count($column_array);
		$c =& $column_array;

		// Create our REGEXP statement for each table
		$sql_row_regex = 'SELECT '.$c[0];
		for ($i = 1; $i < $num_columns; $i++) {
			// MySQL does not support character codes, so
			// regex all chars outside NUL-DEL (\x00-\x7F)
			$sql_row_regex .= ', '.$c[$i].' REGEXP \'[^[.NUL.]-[.DEL.]]\'';
		}
		$sql_row_regex .= ' FROM '.$table_name;

		// Get the total rows and run the REGEXP
		$result = db_query("SELECT COUNT({$c[0]}) FROM ".$table_name);
		$num_table_rows = db_result($result);

		//p_line($sql_row_regex);
		//p_line($num_table_rows.' Rows for table '.$table_name);

		$result2 = db_query($sql_row_regex);
		$num_row_hits = db_num_rows($result2);

		//p_line($num_row_hits.' hits');

		// This might take a few seconds... or longer
		for ($i = 0; $i < $num_row_hits; $i++) {
			$result_row_regex = db_fetch_row($result2);
			//p_line(implode (', ', $result_row_regex));

			// Check that there is something in the row
			$hit = false;
			for ($j = 1; $j < $num_columns; $j++) {
				if ($result_row_regex[$j] === null) {
					// Clear all NULLs
					$result_row_regex[$j] = '0';
				} elseif (intval($result_row_regex[$j]) == 1) {
					// We have something here!  Add it
					$hit = true;
				}
			}
			if ($hit) {
				// array('table' => array('column' => x, y, z...))
				$dirty[$table_name][] = $result_row_regex;
				//$dirty[] = array($table_name => $result_row_regex);
				$rc['scanned_rows']++;
			}
		}
	}

	// We should now have an array of all rows with stuff to recode
	$out = '<'."?php\n\nif (!defined('BOOT')) { exit('No Bootstrap'); }\n\n\$rc_dirty_rows = ";
	$out .= var_export($dirty, true);
	$out .= ";\n\n?".'>';

	$fp = @fopen($rc['rows_filename'], 'w');
	if ($fp) {
		fwrite($fp, $out);
		fclose($fp);
		return true;
	}

	return false;
}


function start_db_recode($type = '') {
	global $rc, $rc_dirty, $rc_dirty_rows;
	global $sql_convert;

	// empty($type) => just convert to utf-8
	// $type === 'iso-8859-1' or 'big5' => source encoding for iconv
	//
	// If anything goes seriously wrong, print an error, and return FALSE
	// If we run out of time, call shutdown('timeout');

	// Commented out, because we need to use the PHP default, not
	// necessarily latin1.
	// db_set_charset('latin1');

	$start_time = get_microtime();

	if ($rc['debug']) {
		p_line('DEBUG: start_db_recode(\''.w_html_chars($type).'\')');
	}


	// Create our dump resource file
	$row_number = 1;
	$fp = fopen($rc['inserts_filename'], 'wb');
	if (!$fp) {
		p_line("ERROR: RECODE: Unable to open inserts temp file.");
		return false;
	}
	fwrite($fp, '<'."?php if (!defined('BOOT')) { exit('No Bootstrap'); }\n");

	$mask_search  = array('?'.'>', "\t", "\n", "\r");
	$mask_replace = array('\?\>', '\t', '\n', '\r');

	// Start figuring out columns to update
	foreach ($rc_dirty_rows as $table => $my_rows) {
		$row_count = count($my_rows);
		for ($ri = 0; $ri < $row_count; $ri++) {
			$my_row = $my_rows[$ri];

			//
			// Figure out which columns[s] to update
			// Always get first column because it's the primary key
			//
			// $my_where   = WHERE clause to fetch correct db row
			// $my_row     = scanned from dirty rows
			// $my_columns = each db key we need to fetch/update
			//
			$column_count = count($my_row);
			$my_columns = array();
			$my_where = array($rc_dirty[$table][0], $my_row[0]);
			for ($k = 1; $k < $column_count; $k++) {
				if ($my_row[$k]) {
					$my_columns[] = $rc_dirty[$table][$k];
				}
			}

			// We have all our info.  Fetch the row.
			$sql_fetch = 'SELECT '
				.implode(', ', $my_columns)
				." FROM {$table} WHERE {$my_where[0]}='{$my_where[1]}'";

			$result = db_query($sql_fetch);
			if ($result) {
				$row_to_convert = db_fetch_array($result);
				db_free_result($result);
			}


			if ($rc['debug']) {
				p_line('DEBUG: TIME('.diff_microtime($start_time).'): SQL: '.w_html_chars($sql_fetch));
			}


			// Update row.
			// # \ ROW \ TABLE \ WHERE \ =WHERE \ (KEY \ =KEY \ ) ...
			$sql_update = "//\t".$row_number."\t".$table."\t".$my_where[0]."\t".$my_where[1];
			foreach ($row_to_convert as $key => $value) {
				// Fix for usernames passed through oekaki applet headers
				if ($key === 'usrname') {
					$value = str_replace(array("\n", "\r"), '', $value);
				}

				// Encode each column value
				$value = str_replace($mask_search, $mask_replace, $value);
				$sql_update .= "\t".$key."\t".$value;
			}

			fwrite($fp, $sql_update."\n");
			$row_number++;
			$rc['updated_rows']++;


			// Eliminate the converted row (or skip if it barfed)
			array_shift($rc_dirty_rows[$table]);


			// Check our time limit, and we're done!
			if (!check_timeout()) {
				fclose($fp);
				shutdown('timeout');
			}

		} // -ri
	} // -foreach


	// Done building our temp files
	fclose($fp);


	// Start with the recode
	if (check_timeout(true)) {
		set_table_charset_all();
		set_db_utf8_misc_marker(1);
	}


	p_line( t('upr_rows_exp_time', $rc['updated_rows'], diff_microtime($start_time)));

	return true;
}


function import_db_recode($type) {
	global $rc;

	db_set_charset('utf8');

	if (empty($type)) {
		$type = 'utf8';
	}

	// Seek and skip
	$start_at_row = 0;
	if (file_exists($rc['lastrow_filename'])) {
		$start_at_row = (int) file_get_contents($rc['lastrow_filename']);
	}

	if ($rc['debug']) {
		p_line('DEBUG: $start_at_row='.$start_at_row);
	}

	$start_time = get_microtime();
	$rc['updated_rows'] = 0;

	$fp = @fopen($rc['inserts_filename'], 'rb');
	if (!$fp) {
		p_line('Cannot open imports temp file.');
		return false;
	}

	// Throwaway
	$fline = fgets($fp);

	while (true) {
		$fline = fgets($fp);

		if ($fline === false || strlen($fline) == 0) {
			break;
		}
		$parts = explode("\t", $fline);
		$part_count = count($parts);

		// Get query parts
		$row_number = (int) $parts[1];
		$table      = $parts[2];
		$where_key  = $parts[3];
		$where_val  = $parts[4];

		// Get data parts
		$keyvals = array();
		$mask_search  = array('\t', '\n', '\r');
		$mask_replace = array("\t", "\n", "\r");
		for ($i = 5; $i < $part_count; $i += 2) {
			$keyvals[$parts[$i]] = str_replace($mask_search, $mask_replace, $parts[$i + 1]);
		}

		// Check timeout
		if (!check_timeout()) {
			fclose($fp);

			$fp2 = fopen($rc['lastrow_filename'], 'w');
			fwrite($fp2, $row_number);
			fclose($fp2);

			shutdown('timeout_import');
		}

		// Are we resuming an import?
		if ($start_at_row <= $row_number) {
			// Figure out what kind of encoding we have
			if ($type === 'utf8') {
				foreach ($keyvals as $key => $val) {
					if (check_utf8_valid($val)) {
						// S'okay
						if ($rc['debug']) {
							$keyvals[$key] = '<b>** UTF-8 ** '.($keyvals[$key]).' ** UTF-8 **</b>';
						} else {
							unset($keyvals[$key]);
						}
					} else {
						$keyvals[$key] = utf8_encode($keyvals[$key]);
					}
				}
			} elseif ($type === 'big5') {
				foreach ($keyvals as $key => $val) {
					$keyvals[$key] = big5_to_utf8 ($keyvals[$key]);
				}
			} elseif (!empty($type)) {
				foreach ($keyvals as $key => $val) {
					$keyvals[$key] = iconv ($type, 'UTF-8//IGNORE', $keyvals[$key]);
				}
			}

			// Did any columns get converted?
			if (count($keyvals) > 0) {
				// Start building query
				$sql_query = 'UPDATE '.$table.' SET ';
				foreach ($keyvals as $key => $val) {
					$sql_query .= $key.'=\''.db_escape($val).'\', ';
				}

				// Hack off trailing ', '
				$sql_query = substr($sql_query, 0, -2);

				$sql_query .= ' WHERE '.$where_key.'=\''.$where_val.'\'';

				if ($rc['debug']) {
					p_line($row_number.': '.$sql_query);
				} else {
					// Send the query
					$result = db_query($sql_query);
					db_free_result($result);
					$rc['updated_rows']++;
				}
			}

		} // Resume check
	}

	fclose($fp);

	if ($rc['debug']) {
		p_line('DEBUG: import_db_recode(): set_db_utf8_misc_marker(2)');
	} else {
		set_db_utf8_misc_marker(2);
	}

	p_line( t('upr_rows_imp_time', $rc['updated_rows'], diff_microtime($start_time)));

	return true;
}


function set_table_charset_all($table_charset = 'utf8', $collation = 'utf8_general_ci') {
	global $rc;

	//
	// Convert to new character set and collation
	//
	// 'utf8_bin'        = binary sorting (exact, numerical, case sensitive)
	// 'utf8_general_ci' = lazy (Ä = A), some language considerations
	// 'utf8_unicode_ci' = accurate, but slower, and makes searches difficult
	//
	$errors = 0;
	$result = db_query('SHOW TABLES');
	if ($result) {
		$num_tables = db_num_rows($result);
		if ($num_tables > 0) {
			$my_tables = array();
			for ($i = 0; $i < $num_tables; $i++) {
				$my_tables[] = db_result($result, $i);
			}
			db_free_result($result);

			foreach ($my_tables as $table) {
				$result2 = db_query("ALTER TABLE {$table} CONVERT TO CHARACTER SET {$table_charset} COLLATE {$collation}");

				if (!$result2) {
					$errors++;
					p_line(db_error());
				}

				// Check our time limit.
				// Shouldn't be a problem, so we'll use the lazy timeout.
				if (!check_timeout(true)) {
					shutdown('timeout');
				}
			}
		}
	}

	if ($errors > 0) {
		return false;
	} else {
		// Mark tables as fully UTF-8
		if ($rc['debug']) {
			p_line('DEBUG: set_db_utf8_misc_marker(2)');
		} else {
			set_db_utf8_misc_marker(2);
		}
	}

	return true;
}


function set_db_utf8_misc_marker($status = 1) {
	global $rc;

	// Sets the utf-8 marker for each oekakimisc table.
	// 1 = UTF-8 charset and collation only
	// 2 = Successful recode of entire database

	$status = (int) $status;

	// Tables
	$result = db_query('SHOW TABLES');
	if (!$result) {
		print_footer( t('upr_sql_bad_tbls'), ' set_db_utf8_misc_marker():', w_html_chars(db_error() ));
		w_exit();
	}
	$num_tables = db_num_rows($result);

	if ($num_tables < 1) {
		print_footer( t('upr_sql_no_tbls', ' set_db_utf8_misc_marker():'));
		w_exit();
	}

	$my_tables = array();
	for ($i = 0; $i < $num_tables; $i++) {
		$my_tables[] = db_result($result, $i);
	}

	foreach ($my_tables as $table) {
		if (strpos($table, 'oekakimisc') !== false) {
			// We have an oekakimisc table.  Check the marker.
			$result2 = db_query("SELECT miscvalue FROM {$table} WHERE miscname='db_utf8'");
			if ($result2 && db_affected_rows($result) < 1) {
				// Add marker
				if (!$rc['debug']) {
					$result3 = db_query("INSERT INTO {$table} SET miscname='db_utf8', miscvalue=".strval($status));
					if (!$result3) {
						p_line( t('upr_utf_no_ins', strval($status), w_html_chars(db_error() )));
						print_footer( t('up_nc_short'));
					}
				} else {
					p_line('DEBUG: set_db_utf8_misc_marker('.strval($status).'): table=\''.w_html_chars($table).'\'');
				}
			} else {
				// Update marker
				$result3 = db_query("UPDATE {$table} SET miscvalue=".strval($status)." WHERE miscname='db_utf8'");
			}
		}
	}

	return true;
}


function big5_to_utf8(&$in, $use_byte_method = false) {
	// Converts Chinese Traditional words to UTF-8

	// Two methods are available.  The string method and
	// the bytescan method.  Bytescan translates each character
	// individually, rather than as a string.  It's about 100
	// times slower on my PC, but produces better results
	// with *broken* encoding.  Try the string method first.

	// Translit will replace impossible characters with approximate
	// matches when converting to a less versatile charset.
	//
	$use_translit = false;

	// Ignore will delete corrupt chars with the string method, or
	// replace chars with '?' when using the bytescan method.  Use
	// is highly recommended with the string method, as iconv will
	// tend to delete huge blocks of text after a corrupt character.
	//
	$use_ignore = true;

	// =====

	$ig = ($use_ignore) ? '' : '//IGNORE' ;
	$tl = ($use_translit) ? '' : '//TRANSLIT' ;

	if (!$use_byte_method) {
		return iconv('BIG5', 'UTF-8'.$ig.$tl, $in);
	}

	// I downloaded the following handy function from somewhere
	// (author unknown).  It will replace any malformed bytes
	// with question marks, making it more obvious how long the
	// phrase was, and possibly helping a reader guess the
	// intent of the original text.
	//
	// If using both //IGNORE and //TRANSLIT, try using them
	// in that order.  Reversing the order seems to cause iconv
	// to ignore the IGNORE.
	//  -- Wac

	$out = '';
	$length = strlen($in);

	for ($i = 0; $i < $length; $i++) {
		$byte = ord(substr($in, $i, 1));
		if ($byte < 129) {
			$out .= substr($in, $i ,1);
		} elseif ($byte > 128 && $byte < 255) {
			$new_word = iconv('BIG5', 'UTF-8'.$ig.$tl, substr($in, $i ,2));
			$out .= (empty($new_word)) ? '?' : $new_word;
			$i++;
		}
	}

	return $out;
}