<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2012 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.13 - Last modified 2012-10-16
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

if (!$flags['admin']) {
	report_err( t('err_modflags'));
}


// Input
$usrname2 = w_gpc('usrname2');
$h_usrname2 = w_html_chars($usrname2);
$modify = w_gpc('modify');


// Redirect to profile editor if needed
if ($modify == 'profile') {
	header('Location: editprofile.php?username='.urlencode($usrname2));
	w_exit();
}


require 'header.php';


?>
<div id="contentmain">

<?php
if (!isset($_GET['muser'])) {
	// Get all non-pending members with lesser permissions, or equal
	// permissions if owner
	if ($flags['rank'] == RANK_OWNER) {
		$flags['rank']++;
	}

	$result2 = db_query("SELECT usrname, usrflags FROM {$db_mem}oekaki WHERE (usrflags != 'P' AND rank < {$flags['rank']}) OR (usrname = '$OekakiU') ORDER BY usrname ASC");
	$rownum = db_num_rows($result2);
?>
	<form name="form1" method="get" action="modflags.php">

		<!-- Permissions -->
		<div class="header">
			<?php tt('niftytoo_permissions');?> 
		</div>

		<div class="infotable">
			<table>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('niftytoo_permissions');?>:
			</td>
			<td class="infoenter" valign="top">
				<select name="modify" class="txtinput">
					<option value="profile" selected="selected"><?php tt('word_profile');?></option>
					<option value="flags"><?php tt('word_flags');?></option>
				</select>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_username');?>:
			</td>
			<td class="infoenter" valign="top">
				<select name="usrname2" class="txtinput">
<?php
	$i = 0;
	while ($i < $rownum) {
		$row = db_fetch_array($result2);
?>
					<option value="<?php echo w_html_chars($row['usrname']);?>"><?php echo w_html_chars($row['usrname']);?></option>
<?php
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
	$result1 = db_query("SELECT usrflags, rank FROM {$db_mem}oekaki where usrname='$usrname2'");
	$their_info = db_fetch_array($result1);

	if (!$flags['owner']) {
		if ($OekakiU != $usrname2 && $their_info['rank'] >= $flags['rank']) {
?>
	<br />
	<h1 class="header">
		<?php tt('word_error');?> 
	</h1>

	<div class="infotable">
		<div align="center">
			<?php tt('err_modflags');?> 
			<br />
		</div>
	</div>
</div>
<br />


<?php
			include 'footer.php';
			w_exit();
		}
	}

?>
	<form name="form2" method="post" action="functions.php">
		<input name="action" type="hidden" value="modflags" />

		<h1 class="header">
			<?php tt('niftytoo_permissions');?> [ <?php echo $h_usrname2;?> ]
		</h1>

		<div class="infotable">
			<div class="infotable" style="text-align: center;">
<?php

// Get the count of owners on the board
$number_of_owners = 1;
$result3 = db_query("SELECT COUNT(*) FROM {$db_mem}oekaki WHERE rank=".RANK_OWNER);
if ($result3) {
	$number_of_owners += intval(db_result($result3)) - 1;
}

if ($flags['sadmin'] && !($flags['owner'] && $number_of_owners == 1 && $OekakiU == $usrname2)) {
	if ($usrname2 == $OekakiU) {
		echo "<strong>".t('warn_modflags')."</strong><br />\n";
	}
?>
				<?php tt('adminrnk');?> 
				<select name="rank">
					<option value="0"<?php if ($their_info['rank'] == 0) {echo ' selected="selected">'.t('rank_none').' ('.t('word_current').')';} else {echo '>'.t('rank_none');} ?></option>
					<option value="<?php echo RANK_MOD ?>"<?php if ($their_info['rank'] == RANK_MOD) { echo ' selected="selected">'.t('word_moderator').' ('.t('word_current').')';} else {echo '>Moderator';}?></option>
					<option value="<?php echo RANK_ADMIN ?>"<?php if ($their_info['rank'] == RANK_ADMIN) { echo ' selected="selected">'.t('type_admin').' ('.t('word_current').')';} else { echo '>'.t('type_admin');} ?></option>
<?php
	if ($flags['owner'] || $usrname2 == $OekakiU) {
?>
					<option value="<?php echo RANK_SADMIN; ?>"<?php if ($their_info['rank'] == RANK_SADMIN) { echo ' selected="selected">'.t('type_sadmin').' ('.t('word_current').')';} else { echo '>'.t('type_sadmin');} ?></option>
<?php
	}
	if ($flags['owner']) {
?>
					<option value="<?php echo RANK_OWNER; ?>"<?php if ($their_info['rank'] == RANK_OWNER) { echo ' selected="selected">'.t('type_owner').' ('.t('word_current').')';} else { echo '>'.t('type_owner');} ?></option>
<?php
	}
?>
				</select>
				<br />
				<br />
<?php } ?>
<?php if ($flags['admin']) { ?>
				<input type="checkbox" name="draw_user" value="D" <?php if (strstr($their_info['usrflags'],'D')) { echo 'checked="checked" '; } ?>/><?php tt('type_daccess');?> 
				<input type="checkbox" name="anim_user" value="M" <?php if (strstr($their_info['usrflags'],'M')) { echo 'checked="checked" '; } ?>/><?php tt('type_aaccess');?> 
				<input type="checkbox" name="upload_user" value="U" <?php if (strstr($their_info['usrflags'],'U')) { echo 'checked="checked" '; } ?>/><?php tt('type_uaccess');?><br />
				<input type="checkbox" name="immunity" value="I" <?php if (strstr($their_info['usrflags'],'I')) { echo 'checked="checked" '; } ?>/><?php tt('type_immunity');?> 
				<input type="checkbox" name="general" value="G" <?php if (strstr($their_info['usrflags'], 'G')) { echo 'checked="checked" '; } ?>/>&nbsp;<?php tt('type_guser');?> 
<?php } ?>
			</div>
			<br />
			<hr />

			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<strong><?php tt('type_sadmin');?></strong>
			</td>
			<td class="infoenter" valign="top">
				<?php tt('type_sabil');?> 
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<strong><?php tt('type_admin');?></strong>
			</td>
			<td class="infoenter" valign="top">
				<?php tt('type_aabil');?> 
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<strong><?php tt('word_moderator');?></strong>
			</td>
			<td class="infoenter" valign="top">
				<?php tt('type_mabil');?> 
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<strong><?php tt('type_daccess');?></strong>
			</td>
			<td class="infoenter" valign="top">
				<?php tt('type_gdaccess');?> 
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<strong><?php tt('type_aaccess');?></strong>
			</td>
			<td class="infoenter" valign="top">
				<?php tt('type_gaaccess');?> 
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<strong><?php tt('type_uaccess');?></strong>
			</td>
			<td class="infoenter" valign="top">
				<?php tt('type_guaccess');?> 
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<strong><?php tt('type_immunity');?></strong>
			</td>
			<td class="infoenter" valign="top">
				<?php tt('type_userkill');?> 
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<strong><?php tt('type_guser');?></strong>
			</td>
			<td class="infoenter" valign="top">
				<?php tt('type_general');?> 
			</td>
			</tr>

			<tr>
			<td class="infoask">&nbsp;</td>
			<td class="infoenter" valign="top">
				<input type="hidden" name="usrname2" value="<?php echo $h_usrname2;?>" />
				<input type="hidden" name="adult_user" value="<?php if (strstr($their_info['usrflags'], 'X')) { echo 'X'; } ?>" />

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