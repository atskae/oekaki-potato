<?php
/*
Wacintaki Poteto Picture Datatype Repair
Copyright 2009 Marc "Waccoon" Leveille
Version 1.5.0 - Last modified 10/25/2009

Last verified 12/28/2010
	+ Wacintaki = all to 1.5.5
	+ Wax       = all to 8.5.2
	+ OP        = all versions

This script will reclaim ownership of the config files, which is
usually required if you copy the config files to the server via FTP.

It's a UNIX security group thing.

Don't forget to delete this file when you are done.
*/


function web_line($in = '') {
	echo $in."<br />\n";
}

function file_replace($in, $out) {
	if (file_exists ($out)) {
		if (!unlink ($out)) {
			return FALSE;
		}
	}
	return (rename ($in, $out)) ? TRUE : FALSE;
}

// PHP < 4.3 fix
if (!function_exists ('file_get_contents')) {
	function file_get_contents($file) {
		if (file_exists ($file)) {
			$size = @filesize ($file);
			if ($size < 1) {
				// No data
				return '';
			}
			$fp = @fopen ($file, 'rb');
			if ($fp) {
				$contents = fread ($fp, $size);
				fclose ($fp);
				return $contents;
			}
			trigger_error ("file_get_contents('{$file}'): cannot open for reading", E_USER_NOTICE);
		}
		return FALSE;
	}
}

function config_replace($in) {
	if (!file_exists ($in)) {
		web_line('SKIPPED:  '.$in.' (does not exist)');
		return TRUE;
	}

	if (!is_writable ($in)) {
		// DANGER:
		// Temp file MUST end in '.php' to prevent security issues!
		$temp = substr ($in, 0, -4).'_old.php';

		// Trash old files
		if (filesize ($in) > 0) {
			if (! ($cfg = file_get_contents ($in))) {
				web_line('<strong>FAIL</strong>:  Cannot read '.$in);
				return FALSE;
			}
		} else {
			$cfg = '';
		}

		// Rename.
		if (! (file_replace($in, $temp))) {
			web_line('<strong>FAIL</strong>:  Cannot delete temp file '.$temp);
			return FALSE;
		}

		// Write, or try to recover
		// file_put_contents() is PHP5 only
		$fp = fopen ($in, 'wb');
		if ($fp) {
			if (strlen ($cfg) > 0) {
				fwrite ($fp, $cfg, strlen ($cfg));
			}
			fclose ($fp);
		} else {
			web_line ('<strong>FAIL</strong>: Cannot write new '.$in);
			file_replace($temp, $in);
			return FALSE;
		}

		// Cleanup
		// Try to avoid Win32 PHP caching issue
		web_line('RECLAIM:  '.$in.' reclaimed.  Should now be writable.');
		if (! (@unlink ($temp))) {
			web_line('<strong>NOTE</strong>:  Temp file &ldquo;'.$temp.'&rdquo; cannot be removed.  Remove it via FTP.');
		}
	} else {
		// Already writable.  Do nothing
		web_line('OK: '.$in);
	}

	// File is [now] writable.
	return TRUE;
}


// =====
// Trash old files
//
$file_OK = 0;

// Get BBS type
if (file_exists ('resource')) {
	// Wacintaki
	$files = array ('dbconn.php', 'config.php', 'resource/ips.txt', 'resource/hosts.txt', 'resource/banner.php', 'resource/notice.php', 'resource/rules.php');
} else {
	// OP / Wax
	$files = array ('dbconn.php', 'config.php', 'ips.txt', 'hosts.txt', 'announce.php', 'banner.php', 'notice.php');
}

foreach ($files as $file) {
	if (config_replace($file)) {
		$file_OK++;
	}
}
web_line();

if (count ($files) == $file_OK) {
	web_line ("Success!  All BBS files should now be writable.");
} else {
	web_line ("Failed!  If you're using Windows, wait 15 seconds and run this file again.<br />\nIf using UNIX/Linux, check that there are no temp files and the original config files are named properly.");
}

exit ();

?>