<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.0x - Last modified 2009-09-13 (x:2011-01-13)
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

// Input
$usrname2 = w_gpc('usrname2');
$h_usrname2 = w_html_chars($usrname2);


// Owner only
if (!$flags['owner']) {
	report_err( t('retpwderr'));
}


include 'header.php';

?>
<div id="contentmain">

<?php
if (!isset($_GET['muser'])) {
	?>
	<form name="form1" method="get" action="newpass.php">

		<!-- Permissions -->
		<div class="header">
			<?php tt('respwd');?> 
		</div>

		<div class="infotable">
			<table>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_username');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="usrname2" class="txtinput">
<?php
	$result2 = db_query("SELECT usrname, usrpass, usrflags FROM {$db_mem}oekaki ORDER BY usrname ASC");
	$rownum = db_num_rows($result2);

	$i = 0;
	while ($i < $rownum) {
		$row = db_fetch_array($result2);
		$h_usr = w_html_chars($row['usrname']);

		if (strstr($row['usrflags'], 'P')) {
?>
					<option value="<?php echo $h_usr;?>"><?php echo $h_usr;?> (<?php tt('word_pending');?>)</option>
<?php
		} else {
?>
					<option value="<?php echo $h_usr;?>"><?php echo $h_usr;?></option>
<?php
		}
		$i++;
	}
?>
				</select>

				<p>
					<input type="submit" name="muser" value="<?php tt('word_submit');?>" class="submit" />
				</p>
			</td>
			</tr>
			</table>
		</div>
	</form>
<?php
}

if (isset($_GET['muser'])) {
	$result = db_query("SELECT usrpass, usrflags FROM {$db_mem}oekaki WHERE usrname='$usrname2'");
	$row = db_fetch_array($result);
?>
	<form name="form2" method="post" action="functions.php">
		<input name="action" type="hidden" value="pass_reset" />

		<h1 class="header">
			<?php tt('respwd');?> [ <?php echo $h_usrname2;?> ]
		</h1>

		<div class="infotable">
			<div class="infotable" style="text-align: center;">
				<?php tt('newpassmsg1');?> 
			</div>
			<br />
			<hr />

			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('validpwdb');?> 
			</td>
			<td class="infoenter" valign="top">
<?php
				$test = trim($row['usrpass']);
				if (empty($test)) {
					echo '<strong>'.t('word_no').'</strong>';
				} else {
					echo '<strong>'.t('word_yes').'</strong> ('.$test.')';
				}
?>

			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('chngpass_newpwd');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="new_pass" type="password" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_repassword');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="new_pass2" type="password" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>


			<tr>
			<td class="infoask">&nbsp;</td>
			<td class="infoenter" valign="top">
				<input type="hidden" name="OekakiU" value="<?php echo w_html_chars($OekakiU);?>" />
				<input type="hidden" name="OekakiPass" value="<?php echo $OekakiPass;?>" />
				<input type="hidden" name="usrname2" value="<?php echo $h_usrname2;?>" />

				<p>
					<input type="submit" name="muser2" value="<?php tt('word_submit');?>" class="submit" />
				</p>
			</td>
			</tr>
			</table>
		</div>
	</form>
<?php
}
?>
</div>


<?php

include 'footer.php';