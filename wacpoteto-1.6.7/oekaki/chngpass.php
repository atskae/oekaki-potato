<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2019 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.0 - Last modified 2009-09-13
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

// Input
$usrname = w_gpc('username');
$vcode   = w_gpc('vcode');
$h_usrname = w_html_chars($usrname);

// Get real password
if (!empty($usrname)) {
	$result = db_query("SELECT usrpass, rank FROM {$db_mem}oekaki WHERE usrname='$usrname'");
	$real_rows = db_fetch_array($result);
} else {
	report_err( t('invuname'));
}

if ($real_rows['usrpass'] != $vcode || empty($vcode)) {
	report_err( t('invercode'));
}


include 'header.php';

?>
<div id="contentmain">
	<form name="form1" method="post" action="functions.php">
		<input name="action" type="hidden" value="pchange" />

		<h1 class="header">
			<?php tt('chngpass_title');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask">&nbsp;</td>
			<td class="infoenter" valign="top">
				<?php tt('chngpass_disclaimer');?> 
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('chngpass_newpwd');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="newpass" type="password" class="txtinput" id="newpass" style="width: 50%;" />
				<input name="username" type="hidden" id="username" value="<?php echo $h_usrname;?>" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('eprofile_repass');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="retype" type="password" class="txtinput" id="retype" style="width: 50%;" />
				<input name="vcode" type="hidden" id="vcode" value="<?php echo $vcode;?>" />

				<p>
					<input name="register" type="submit" value="<?php tt('word_submit');?>" class="submit" />
				</p>
			</td>
			</tr>
			</table>
		</div>
	</form>
</div>


<?php

include 'footer.php';