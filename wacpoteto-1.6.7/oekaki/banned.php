<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6 - Last modified 2011-01-05
*/


// Load bootstrap
// Note that since the user is banned, we must DISABLE the banscript!
$OekakiID = '';
$OekakiU  = '';
$OekakiPass = '';
$no_online = true;
define('BOOT', 1);
define('BAN_KILL', 1);
require 'boot.php';


db_close();


// Start HTML
send_html_headers();
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
	<meta http-equiv="Content-Language" content="<?php echo $metatag_language;?>" />

	<title><?php echo $cfg['op_title'];?></title>
</head>

<body>
<div align="center">
	<p align="center">
		<?php echo t('banned')."\n";?> 
	</p>
	<p align="center">
		<?php echo t('type_admin').": <strong>".(str_replace('@', ' &middot; ', $cfg['op_email']))."</strong>\n";?> 
	</p>
</div>
</body>
</html>