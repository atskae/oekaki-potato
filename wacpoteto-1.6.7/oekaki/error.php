<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.0 - Last modified 2015-02-15
*/


// Load bootstrap
if (!defined('X_DISABLE_REPORT_ERR_HACK') || X_DISABLE_REPORT_ERR_HACK === true) {
	// Disables global hack for error.php and works like < 1.6.0.
	define('BOOT', 1);
	require 'boot.php';

	// Input
	$s = w_gpc('s', 'i');
	$error = w_gpc('error', 'raw');
}


// Type of header?
if ($s) {
	// Start HTML
	send_html_headers();

echo <<<EOF
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}" />
	<meta http-equiv="Content-Language" content="{$metatag_language}" />
	<title>Wacintaki</title>

	<link rel="stylesheet" type="text/css" href="{$cssinclude}" title="{$template_name}" />
</head>

<body>
<br />


EOF;
} else {
	include 'header.php';
}


?>
<div id="contentmain">
	<br />
	<h1 class="header">
		<?php tt('word_error');?> 
	</h1>

	<div class="infotable">
		<div align="center">
			<?php echo nifty2_convert($error)."\n"; ?>
			<br />
			<br />
<?php
if ($s) {
	echo '<a onclick="window.close();" href="#">'.t('common_window')."</a>\n";
} else {
	echo t('error_goback')."\n";
}
?>
		</div>
	</div>
	<br />
</div>


<?php

if ($s) {
	db_close();
	echo "</body>\n</html>\n";
} else {
	include 'footer.php';
}