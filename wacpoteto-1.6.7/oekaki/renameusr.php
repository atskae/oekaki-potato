<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2018 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.6 - Last modified 2018-05-30
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

// Input
$oldname = w_gpc('oldname');
$newname = w_gpc('newname');
// $action


// SuperAdmin+ only
if (!$flags['sadmin']) {
	report_err( t('err_no_rename_usr'));
}


$rename_error_message = '';

if (isset($_POST['oldname'])) {
	$result = db_query("SELECT usrname FROM {$OekakiPoteto_MemberPrefix}oekaki WHERE usrname='{$oldname}'");

	if (!$result || db_num_rows($result) < 1) {
		$rename_error_message = t('rename_old_nonexist');
		$oldname = '';
		$action = '';
	}
}

$newname_okay = false;
if (!empty($newname)) {
	$result = db_query("SELECT usrname FROM {$OekakiPoteto_MemberPrefix}oekaki WHERE usrname='{$newname}'");

	if ($result && db_num_rows($result) > 0) {
		$rename_error_message = t('rename_new_exists');
		$action = '';
	}
} else {
	$action = '';
}

if ($action == 'perform' && !empty($oldname)) {
	$result = db_query("SELECT ID, usrname, rank FROM {$OekakiPoteto_MemberPrefix}oekaki WHERE usrname='{$oldname}'");

	$row = db_fetch_array($result);
	if ($row['rank'] > $flags['rank'] && $row['ID'] != $OekakiID) {
		$rename_error_message = t('rename_no_rank');
		$oldname = '';
		$action = '';
	} else {
		if (w_rename_member($oldname, $newname)) {
			w_log(WLOG_EDIT_PROFILE, '$l_renamed_mem', $oldname.' > '.$newname);
			all_done('log.php');
		} else {
			report_err('Database: rename failed');
		}
	}
}



include 'header.php';

?>
<div id="contentmain">
<?php

if (empty($action)) {
	?>
	<form id="form10" method="post" action="renameusr.php">
		<input type="hidden" name="action" value="confirm" />

		<h1 class="header">
			<?php tt('rename_user_title');?> 
		</h1>

		<div class="infotable">
			<div class="infotable" style="text-align: center;">
				<?php tt('rename_user_disclaimer');?> 
			</div>
			<br />
			<hr />

			<table class="infomain">
<?php if (!empty($rename_error_message)) { ?>
			<tr>
			<td class="infoask" valign="top">
				&nbsp;
			</td>
			<td class="infoenter" valign="top">
				<?php echo $rename_error_message;?> 
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

<?php } ?>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('old_username');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="oldname" class="multiline">
<?php
	// Get all members with less than $OekakiU rank
	$result2 = db_query("SELECT ID, usrflags, usrname FROM {$db_mem}oekaki WHERE rank < {$flags['rank']} OR ID=".intval($OekakiID)." ORDER BY usrname ASC");
	$rownum = db_num_rows($result2);

	$i = 0;
	while ($i < $rownum) {
		$row = db_fetch_array($result2);
		?>
					<option value="<?php echo w_html_chars($row['usrname']);?>"<?php if ($row['usrname'] == $oldname) echo ' selected="selected"';?>><?php echo w_html_chars($row['usrname']);?></option>
<?php
		$i++;
	}
?>
				</select>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('new_username');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="newname" type="text"<?php if (!empty($newname)) echo 'value="'.w_html_chars($newname).'"';?> size="40" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask">&nbsp;</td>
			<td class="infoenter" valign="top">
				<input name="rename_sub" type="submit" class="submit" value="<?php tt('word_submit');?>" />
			</td>
			</tr>
			</table>
		</div>
	</form>
<?php }


if ($action == 'confirm') {
	?>
	<form id="form11" method="post" action="renameusr.php">
		<input type="hidden" name="action" value="perform" />
		<input type="hidden" name="oldname" value="<?php echo w_html_chars($oldname);?>" />
		<input type="hidden" name="newname" value="<?php echo w_html_chars($newname);?>" />

		<h1 class="header">
			<?php tt('rename_user_title');?> 
		</h1>

		<div class="infotable">
			<div class="infotable" style="text-align: center;">
				<?php tt('confirm_rename_ok');?> 
			</div>
			<br />
			<hr />

			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('old_username');?> 
			</td>
			<td class="infoenter" valign="top">
				<?php echo w_html_chars($oldname);?> 
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('new_username');?> 
			</td>
			<td class="infoenter" valign="top">
				<?php echo w_html_chars($newname);?> 
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask">&nbsp;</td>
			<td class="infoenter" valign="top">
				<input name="rename_sub" type="submit" class="submit" value="<?php tt('word_rename');?>" />
			</td>
			</tr>
			</table>
		</div>
	</form>
<?php }


echo "</div>\n\n";
include 'footer.php';



/* Functions */

function w_rename_member($oldname, $newname, $memberlist_only = false) {
	global $db_mem, $db_pre;

	$result = db_query("DELETE FROM {$db_mem}oekakionline WHERE onlineusr='{$oldname}'");

	$result = db_query("UPDATE {$db_mem}oekaki SET usrname='{$newname}' WHERE usrname='{$oldname}'");
	if (!$result) {
		return false;
	}
	$result = db_query("UPDATE {$db_mem}oekakichat SET usrname='{$newname} WHERE usrname='{$oldname}'");
	$result = db_query("UPDATE {$db_mem}oekakimailbox SET reciever='{$newname}' WHERE reciever='{$oldname}'");
	$result = db_query("UPDATE {$db_mem}oekakimailbox SET sender='{$newname}' WHERE sender='{$oldname}'");

	if ($memberlist_only === false) {
		$result = db_query("UPDATE {$db_p}oekakidta SET usrname='{$newname}' WHERE usrname='{$oldname}'");
		$result = db_query("UPDATE {$db_p}oekakicmt SET usrname='{$newname}' WHERE usrname='{$oldname}'");
	}

	return true;
}