<?php
/*
Wacintaki Poteto Picture Datatype Repair
Copyright 2009 Marc "Waccoon" Leveille
Version 1.5.0 - Last modified 10/25/2009

Last verified 12/26/2010
	+ Wacintaki = 1.5.0 to 1.5.5
	- Wax       = all versions

Fixes broken file postfixes with Wacinaki 1.5+
(Pictures showing up as "OP_50." instead of "OP_50.png", etc.
*/


function p_line($line) {
	echo '<p>'.$line."</p>\n";
}

function web_line($line = '') {
	echo $line."<br />\n";
}

function sql_check($result, $extra = '') {
	if (!$result) {
		echo db_error();
		if (!empty ($extra)) {
			echo ' ['.$extra.']';
		}
		echo "<br />\n";
		return 0;
	}
	return 1;
}

// Init
$debug = 0; // 1 to disable database updates
$override = 1; // 1 forces rebuild, 0 skips non-empty slots


// Header
echo <<<EOF
<html>
<head>
	<title>Datatype fix</title>
</head>
<body>

EOF;


// Start fix
if ($_GET['set'] == '1') {
	require ('db_layer.php');
	$dbconn = db_open();

	require ('config.php');

	// Resolve 1.1 vs 1.2 config file compatibility
	if (isset ($cfg['op_pics'])) {
		// v1.2
		$OPpics = $cfg['op_pics'];
		$OPPrefix = $cfg['op_pre'];
	}

	web_line('Set pic properties:');
	/*
		It's possible the source image can be a JPEG or PNG but there's no way to tell which is the primarty image unless we search the filesystem for both.  This is partly my fault thanks to my upload script which allows people to upload JPEGs if $jpgcompression is enabled.

		If a PNG exists, we always use that, but if a JPEG exists exclusively, we use that instead.  Rather than bang the filesys, we'll read the directory and do lots of string searching to find out what's available.  There's probably a better way, but I'm stupid.  :)
	*/

	$path = $OPpics.'/'.$OPPrefix;

	// Read the dir
	$handle = opendir ($OPpics);
	while ($file = readdir ($handle)) {
		$extension = strtolower (substr ($file, -3));
		if ($extension == 'png' || $extension == 'jpg' || $extension == 'gif') {
			$files[] = $file;
		}
	}
	closedir ($handle);


	// Collect database entries and let's sort things out
	$result = db_query('SELECT PIC_ID, px, py, ptype FROM '.$OekakiPoteto_Prefix.'oekakidta');
	$total_rows = db_num_rows ($result);

	// For each DB entry, check filesys
	for ($i = 0; $i < $total_rows; $i++) {
		$row = db_fetch_array ($result);

		// If ptype is occupied and $override is 0, we do nothing.
		if (empty ($row['ptype']) || $override == 1) {
			// Use a PNG by default.  If file doesn't exist, DB is not updated.
			$row['ptype'] = 'png';

			// Check for GIF or JPEG to ensure orphaned PNG files don't cause issues.
			$gif_check = $OPPrefix.$row['PIC_ID'].'.gif';
			$jpg_check = $OPPrefix.$row['PIC_ID'].'.jpg';

			if (in_array ($gif_check, $files)) {
				// Found GIF.  Use it.
				$row['ptype'] = 'gif';
			} elseif (in_array ($jpg_check, $files)) {
				// Found JPEG.  Use it.
				$row['ptype'] = 'jpg';
			}


			$image = $path.$row['PIC_ID'].'.'.$row['ptype'];
			if (file_exists ($image)) {
				$sizes = GetImageSize ($image);

				// OK, we've got all our info.  Insert it into DB
				echo ("<p>\n");
				web_line("PIC: {$row['PIC_ID']}, <strong>X: {$sizes[0]}, Y: {$sizes[1]}</strong>, TYPE: {$row['ptype']}");
				if ($debug != 1) {
					$result2 = db_query('UPDATE '.$OekakiPoteto_Prefix."oekakidta SET px={$sizes[0]}, py={$sizes[1]}, ptype='{$row['ptype']}' WHERE PIC_ID='{$row['PIC_ID']}'");
					sql_check($result2);
				} else {
					web_line("&nbsp;&nbsp;&nbsp;&ldquo;{$image}&rdquo;");
					web_line("&nbsp;&nbsp;&nbsp;&ldquo;UPDATE {$OekakiPoteto_Prefix}oekakidta SET px={$sizes[0]}, py={$sizes[1]}, ptype='{$row['ptype']}' WHERE PIC_ID='{$row['PIC_ID']}'&rdquo;");
				}
				echo ("</p>\n");
			} else {
				// D'OH!  Picture missing!
				p_line("<strong>MISSING PICTURE: {$row['PIC_ID']}, TYPE: {$row['ptype']}</strong>");
			}
		}
	}
	web_line('Finished pic properties.');
	web_line('Done');
	echo "</body>\n</html>";

	db_close();
	exit ();
}


// Print interface
echo <<<EOF
<p>
	This script will attempt to repair datatypes if they become corrupted or if the update from OekakiPoteto does not complete properly.
</p>
<p>
	<a href="{$SERVER_['PHP_SELF']}?set=1">Click here to repair</a>
</p>
</body>
</html>

EOF;

exit ();
?>