<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.0 - Last modified 2015-02-10
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

// Input
$picno = w_gpc('picno', 'i+');


// Get picture info
$result = db_query("SELECT ID_2, usrname, title, comment, ptype, rtype, ttype, adult, usethumb, px, password FROM {$db_p}oekakidta WHERE PIC_ID=$picno");
if (!$result || db_num_rows($result) < 1) {
	report_err( t('delconf_pic_err', $picno));
}
$row = db_fetch_array($result);


//Admins can recover pics graphically
$adminedit = 0;
if ($row['usrname'] != $OekakiU) {
	if (!$flags['mod']) {
		report_err( t('err_editpic'));
	} else {
		$adminedit = 1;
	}
}


include 'header.php';


?>
<div id="contentmain">
	<div class="infotable">
<?php

// Code taken from comment.php
$resno = $picno;

if ($row['px'] < $user['screensize']) {
	if (THUMB_COMMENT != 1) {
		// hacks.php
		$row['usethumb'] = 0;
	}
}

$master_image = $p_pic.$resno.'.'.$row['ptype'];
if (!empty($row['rtype']) && $row['usethumb'] == 1) {
	$thumbnail_image = $r_pic.$resno.'.'.$row['rtype'];
	$img_class_type = 'imgthumb';
} elseif (!empty($row['ttype']) && $row['usethumb'] == 1) {
	$thumbnail_image = $t_pic.$resno.'.'.$row['ttype'];
	$img_class_type = 'imgthumb';
} else {
	$thumbnail_image = $master_image;
	$img_class_type = 'imghover';
}

// Insert the picture + thumbnail
$adult_block_mode = 0;
if (!$flags['X'] && $row['adult'] == 1) {
	if ($cfg['guest_adult'] == 'yes' || $flags['mod']) {
		// block image only (worksafe)
		$adult_block_mode = 1;
	} else {
		// block image and link
		$adult_block_mode = 2;
	}
}

?>
		<p style="text-align: center;">
<?php
if ($adult_block_mode == 0) {
echo <<<EOF
			<a href="$master_image"><img src="$thumbnail_image" class="$img_class_type" alt="Pic #{$row['ID_2']}" title="Pic #{$row['ID_2']}" /></a><br />

EOF;
} elseif ($adult_block_mode == 1) {
echo <<<EOF
			<a href="$master_image"><img src="{$cfg['res_path']}/{$cfg['porn_img']}" class="$img_class_type" alt="Pic #{$row['ID_2']}" title="Pic #{$row['ID_2']}" /></a><br />

EOF;
} else {
echo <<<EOF
			<img src="{$cfg['res_path']}/{$cfg['porn_img']}" alt="Pic #{$row['ID_2']}" title="Pic #{$row['ID_2']}" /><br />

EOF;
}

?>
		</p>

		<form name="form1" method="post" action="functions.php">
			<input name="action" type="hidden" value="picedit" />

			<h1 class="header">
				<?php tt('erpic_title', 'niftyusage.php" onclick="openWindow(\'niftyusage.php\', 500, 300); return false;');?> 
			</h1>

			<div class="infotable">
				<table class="infomain">
				<tr>
				<td class="infoask" valign="top">
					<?php tt('install_title2');?> 
				</td>
				<td class="infoenter" valign="top">
					<input name="title2" type="text" value="<?php echo w_html_chars($row['title']);?>" class="txtinput" />
				</td>
				</tr>

				<tr>
				<td class="infoask" valign="top">
					<?php tt('word_comments');?> 
				</td>
				<td class="infoenter" valign="top">
					<textarea name="comment2" cols="40" rows="5" class="multiline"><?php echo w_html_chars($row['comment']);?></textarea>
				</td>
				</tr>

				<tr>
				<td colspan="2">&nbsp;</td>
				</tr>


				<tr>
				<td class="infoask" valign="top">
					<?php tt('install_password');?> 
				</td>
				<td class="infoenter" valign="top">
					<input name="password" type="text" value="<?php echo w_html_chars($row['password']);?>" />

					<p class="subtext">
						<?php tt('editpicmsg');?> 
					</p>
				</td>
				</tr>

				<tr>
				<td class="infoask" valign="top">
					<?php tt('comment_adult', MIN_AGE_ADULT);?> 
				</td>
				<td class="infoenter" valign="top">
					<select name="adult" class="multiline">
<?php if ($row['adult'] == '1') { ?>
						<option value="1" selected="selected"><?php tt('word_yes');?></option>
						<option value="0"><?php tt('word_no');?></option>
<?php } else { ?>
						<option value="0" selected="selected"><?php tt('word_no');?></option>
						<option value="1"><?php tt('word_yes');?></option>
<?php } ?>
					</select>
				</td>
				</tr>

<?php if ($user['rank'] != RANK_MOD) {?>
				<tr>
				<td colspan="2">&nbsp;</td>
				</tr>

				<tr>
				<td class="infoask">
<?php if ($OekakiU != $row['usrname'] && $flags['mod']) { ?>
					<?php tt('print_edited_by_admin', w_html_chars($OekakiU));?> 
<?php } else { ?>
					<?php tt('print_edited_on');?> 
<?php } ?>
				</td>
				<td class="infoenter" valign="top">
					<select name="editon" class="multiline">
						<option value="1" selected="selected"><?php tt('word_yes');?></option>
						<option value="0"><?php tt('word_no');?></option>
					</select>
				</td>
				</tr>

<?php } ?>
				<tr>
				<td class="infoask">&nbsp;</td>
				<td class="infoenter" valign="top">
					<br />
					<input name="picno" type="hidden" value="<?php echo $picno;?>" />
					<input name="picedit" type="submit" value="<?php tt('word_edit');?>" class="submit" />
				</td>
				</tr>
				</table>
			</div>
		</form>
	</div>
</div>


<?php

include 'footer.php';