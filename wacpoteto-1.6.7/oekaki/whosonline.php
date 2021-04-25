<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.0 - Last modified 2015-02-11
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

$online_refresh_seconds = 60;


// Private?
if ($cfg['private'] == 'yes' && empty($OekakiU)) {
	all_done();
}

$result2 = db_query("SELECT * FROM {$db_mem}oekakionline ORDER BY onlineusr ASC");



// Start HTML
send_html_headers();
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
	<meta http-equiv="Content-Language" content="<?php echo $metatag_language;?>" />
	<meta http-equiv="Refresh" content="<?php echo $online_refresh_seconds;?>;url=whosonline.php" />

	<title><?php echo $cfg['op_title'];?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo $cssinclude;?>" title="<?php echo $template_name;?>" />

	<script type="text/javascript" src="Poteto.js">
	</script>
</head>


<body>


<!-- Online -->
<div class="pheader">
	<?php tt('online_title');?> 
</div>
<div>
	<table class="pinfotable" style="width: 100%;">
	<tr>
	<td>
		<b><?php tt('word_username');?></b>
	</td>
<?php if (!empty($glob['cookie_path'])) { ?>
	<td>
		<b><?php tt('word_board');?></b>
	</td>
<?php } ?>
	<td>
		<b><?php tt('word_location');?></b>
	</td>
	</tr>

<?php while ($row = db_fetch_array($result2)) { ?>
	<tr>
	<td>
		<a onclick="openWindow('profile.php?user=<?php echo urlencode($row['onlineusr']);?>', 300, 400); return false" href="profile.php?user=<?php echo urlencode($row['onlineusr']);?>"><?php echo w_html_chars($row['onlineusr']);?></a>
	</td>
<?php if (!empty($glob['cookie_path'])) { ?>
	<td>
		<?php echo $row['onlineboard'];?> 
	</td>
<?php } ?>
	<td>
		<?php tt($row['locale']);?> 
	</td>
	</tr>
<?php } ?>
	</table>
</div>
<br />

<div class="pinfotable" style="text-align: center;">
		<?php tt('onlinelistmsg', $online_refresh_seconds);?><br />
		<a onclick="window.close();" href="#"><?php tt('common_window');?></a>
</div>


</body>
</html>
<?php

w_exit();