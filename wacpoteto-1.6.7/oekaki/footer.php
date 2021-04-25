<?php // Include only
if (!defined('BOOT')) exit('No bootstrap.  Exiting.');

/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.0 - Last modified 2015-03-01
*/


?>
<br />
<hr />


<div id="footer">
	<?php tt('f_bbs_credits', 'Wacintaki '.$version, 'Waccoon');?><br />
	<?php tt('f_applet_credits');?><br />
<?php if ($language != 'english') {
	echo "\t".t('footer_translation')."<br />\n";
} ?>
	<?php tt('footer_load_time', diff_microtime($start_time));?> 
</div>


<?php if ($glob['debug']) {
	echo "<br />\n";
	include 'db_debug_table.php';
	echo "\n\n";
} ?>
</body>
</html>
<?php

w_exit();