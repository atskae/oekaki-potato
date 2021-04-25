<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.0x - Last modified 2009-09-13 (x:2011-01-23)
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

// Input
$usrname2 = w_gpc('usrname2');


// SuperAdmin+ only
if (!$flags['sadmin']) {
	report_err( t('errdelusr'));
}


include 'header.php';

?>
<div id="contentmain">
<?php
if (!isset($_GET['pmember'])) {
	?>
	<form name="form1" method="get" action="delusr.php">

		<h1 class="header">
			<?php tt('deluser_title');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_username');?>
			</td>
			<td class="infoenter" valign="top">
				<select name="usrname2" class="multiline">
<?php
	// Get all members with less than $OekakiU rank
	$result2 = db_query("SELECT ID, usrflags, usrname FROM {$db_mem}oekaki WHERE rank < {$flags['rank']} ORDER BY usrname ASC");
	$rownum = db_num_rows($result2);

	$i = 0;
	while ($i < $rownum) {
		$row = db_fetch_array($result2);
		?>
					<option value="<?php echo $row['ID']?>"><?php echo w_html_chars($row['usrname']);?></option>
<?php
		$i++;
	}
?>
				</select>

				<p>
					<input type="submit" name="pmember" value="<?php tt('word_submit');?>" class="submit" />
				</p>
			</td>
			</tr>
			</table>
		</div>
	</form>
<?php
} else {
	$result = db_query("SELECT ID, usrname, comment, url, email FROM {$db_mem}oekaki WHERE ID='$usrname2'");
	$row = db_fetch_array($result);

	?>
	<form name="form1" method="post" action="functions.php">
		<input name="action" type="hidden" value="deluser" />

		<h1 class="header">
			<?php tt('deluser_title');?>
		</h1>

		<div class="infotable">
			<div class="infotable" style="text-align: center;">
				<?php tt('deluser_disclaimer');?>
			</div>
			<br />
			<hr />

			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_username');?>:
			</td>
			<td class="infoenter" valign="top">
				<?php echo w_html_chars($row['usrname']);?>
				<input name="usrname2" type="hidden" value="<?php echo $row['ID']?>" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_email');?>:
			</td>
			<td  class="infoenter" valign="top">
				<?php echo $row['email']?>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_delete');?>?
			</td>
			<td class="infoenter" valign="top">
				<select name="confirm" class="multiline">
					<option value="1"><?php tt('word_yes');?></option>
					<option value="0" selected="selected"><?php tt('word_no');?></option>
				</select>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('sendreason');?>?
			</td>
			<td class="infoenter" valign="top">
				<select name="no_reason" class="multiline">
					<option value="0" selected="selected"><?php tt('word_yes');?></option>
					<option value="1"><?php tt('word_no');?></option>
				</select>
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_reason');?> (<?php tt('deluser_mreason');?>):
			</td>
			<td class="infoenter" valign="top">
				<textarea name="reason" cols="30" rows="5" class="multiline"></textarea>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>


			<tr>
			<td class="infoask">&nbsp;</td>
			<td class="infoenter" valign="top">
				<input name="memdel" type="submit" class="submit" id="memdel" value="<?php tt('word_delete');?>" />
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