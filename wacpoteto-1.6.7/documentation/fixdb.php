<?php
/*
Wacintaki Poteto Config Editor
Copyright 2011 Marc "Waccoon" Leveille
Version 1.5.6 - Last modified 2/28/2011

Last verified 2/28/2011
	+ Wacintaki = 1.5.0 to 1.5.6
	+ Wax       = 8.5.2

Tests/edits database config file
*/

error_reporting (E_ALL ^ E_NOTICE);

function p_line($line) {
	echo '<p>'.$line."</p>\n";
}

function web_line($line = '') {
	echo $line."<br />\n";
}


// ------------------------------------------------------------------


// Test for data
if (isset ($_GET['del'])) {
	@unlink ($_SERVER['PHP_SELF']);

	if (file_exists ($_SERVER['PHP_SELF'])) {
		web_line ('Could not delete file.  Delete manually via FTP');
	} else {
		web_line ('Site secured');
	}
	exit();
}


if (isset ($_POST['write'])) {
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

	// No spaces after ">"!
?>
EOF;

	$fp = @fopen ('dbconn.php', 'w');
	if ($fp) {
		fwrite ($fp, $db_configuration);
		fclose ($fp);
		web_line('Database config file written.  <a href="'.$_SERVER['PHP_SELF'].'">Click here to test</a>.');
	} else {
		web_line('Database config file is locked -- file not written.');
	}

	exit();
}


// ------------------------------------------------------------------


echo <<<EOF
<html>
<body>

EOF;

// Import and evaluate dbconn.php file
{
	$eval_source = @file_get_contents ('dbconn.php');

	if (strpos ($eval_source, 'or die')) {
		web_line ("Config file version is too old.  Please update the board, first");
		exit();
	}

	require ('dblayer.php');
	$dbconn = db_open();

	if ($dbconn) {
		web_line('Database connection OK!');
	} else {
		web_line('Connection failed!');
		web_line(db_error());
	}
	web_line();
}


// Output editor
echo <<<EOF

	<hr />

	<p>Submit this form to update the database</p>

	<form name="form1" method="post" action="{$_SERVER['PHP_SELF']}">
		<input type="hidden" name="write" value="1" />

		<table class="infomain">
		<tr>
		<td class="infoask" valign="top">
			Host (usually &ldquo;localhost&rdquo;):
		</td>
		<td class="infoenter" valign="top">
			<input type="text" name="dbhost" size="40" value="{$dbhost}" />
		</td>

		<tr>
		<td class="infoask" valign="top">
			Database Name:
		</td>
		<td class="infoenter" valign="top">
			<input type="text" name="dbname" size="40" value="{$dbname}" />
		</td>
		</tr>

		<tr>
		<td class="infoask" valign="top">
			Database Username:
		</td>
		<td>
			<input type="text" name="dbuser" size="40" value="{$dbuser}" />
		<td class="infoenter" valign="top">
		</tr>

		<tr>
		<td class="infoask" valign="top">
			Database password:
		</td>
		<td class="infoenter" valign="top">
			<input type="text" name="dbpass" value="{$dbpass}" size="40" />
		</tr>

		<tr>
		<td class="infoask" valign="top">
			Main Table Prefix:
		</td>
		<td class="infoenter" valign="top">
			<input type="text" name="dbprefix" size="40" value="{$OekakiPoteto_Prefix}" />
		</td>
		</tr>

		<tr>
		<td class="infoask" valign="top">
			Member Table Prefix:
		</td>
		<td class="infoenter" valign="top">
			<input name="OekakiPoteto_MemberPrefix" type="text" id="OekakiPoteto_MemberPrefix" value="{$OekakiPoteto_MemberPrefix}" size="40" />
		</td>
		</tr>
		</table>

		<input type="submit" name="submit" value="submit" />
	</form>
</body>
<html>
EOF;


?>