<?php
/*
Wacintaki Poteto - Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6 - Last modified 2011-01-09
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


if (!$flags['admin']) {
	report_err( t('erreditrul'));
}


include 'header.php';


// Get rules
$myfile = $cfg['res_path'].'/rules.php';
if (!is_writable($myfile)) {
	report_err( t('fislockvred', $myfile));
}

if (file_exists($cfg['res_path'].'/rules.php')) {
	$rules_contents = file_get_contents($cfg['res_path'].'/rules.php');
} else {
	$rules_contents = '';
}


?>
<div id="contentmain">
	<form name="form1" method="post" action="functions.php">
		<input name="action" type="hidden" value="editrules" />

		<h1 class="header">
			<?php tt('editrul');?> 
		</h1>

		<div class="infotable" style="text-align: center;">
			<p>
				<?php tt('htmlphpallow');?> 
			</p>

			<p>
				<textarea name="rulesedit" cols="80" rows="20" class="multiline"><?php echo w_html_chars($rules_contents) ?></textarea>
			</p>
		</div>
		<br />


		<h1 class="header">
			<?php tt('submitchange');?> 
		</h1>

		<div class="infotable" style="text-align: center;">
			<p>
				<input type="submit" name="rulessub" value="<?php tt('word_edit');?>" class="submit" />
			</p>
		</div>
	</form>
</div>


<?php

include 'footer.php';