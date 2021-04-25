<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6 - Last modified 2011-01-24
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


// Admin+ only
if (!$flags['admin']) {
	report_err( t('credibandu'));
}


// Read ban list
$myfile = $cfg['res_path'].'/hosts.txt';
if (file_exists($myfile)) {
	if (!is_writable($myfile)) {
		report_err( t('fislockvred', $myfile));
	}
	$contents = file_get_contents($cfg['res_path'].'/hosts.txt');
}

$myfile = $cfg['res_path'].'/ips.txt';
if (file_exists($myfile)) {
	if (!is_writable($myfile)) {
		report_err( t('fislockvred', $myfile));
	}
	$contents2 = file_get_contents($cfg['res_path'].'/ips.txt');
}


include 'header.php';

?>
<div id="contentmain">
	<form name="form1" method="post" action="functions.php">
		<input name="action" type="hidden" value="banedit" />

		<h1 class="header">
			<?php tt('banip_editiplist');?> 
		</h1>

		<div class="infotable" style="text-align: center;">
			<p>
				<?php tt('banip_editiplistsub');?> 
			</p>
			<p>
				<?php tt('banip_editiplistsub2');?> 
			</p>

			<p>
				<textarea name="ipban" cols="40" rows="5" class="multiline"><?php echo w_html_chars($contents2);?></textarea>
			</p>
		</div>
		<br />



<?php if (defined('ENABLE_DNS_HOST_LOOKUP') && ENABLE_DNS_HOST_LOOKUP) { ?>
		<h1 class="header">
			<?php tt('banip_edithostlist');?> 
		</h1>

		<div class="infotable" style="text-align: center;">
			<p>
				<?php tt('banip_edithostlistsub');?> 
			</p>
			<p>
				<?php tt('banip_edithostlistsub2');?> 
			</p>

			<p>
				<textarea name="hostban" cols="40" rows="5" class="multiline"><?php echo w_html_chars($contents);?></textarea>
			</p>
		</div>
		<br />



<?php } ?>
		<h1 class="header">
			<?php tt('submitchange');?> 
		</h1>

		<div class="infotable" style="text-align: center;">
			<p>
				<input name="banlist" type="submit" value="<?php tt('word_edit');?>" class="submit" />
			</p>
		</div>
	</form>
</div>


<?php

include 'footer.php';