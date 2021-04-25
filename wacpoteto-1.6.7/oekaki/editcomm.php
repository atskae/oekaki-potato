<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6 - Last modified 2011-01-09
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

// Input
$id_3 = w_gpc('id_3', 'i+');
if (!$id_3) {
	report_err( t('err_comment'));
}
$resno = w_gpc('resno', 'i+');
// $pageno


$commresult = db_query("SELECT usrname, comment FROM {$db_p}oekakicmt WHERE ID_3=$id_3");
$commrow = db_fetch_array($commresult);

if ($commrow['usrname'] != $OekakiU && !$flags['mod']) {
	report_err( t('err_ecomment'));
}


include 'header.php';


?>
<div id="contentmain">
	<form name="form1" method="post" action="functions.php">
		<input name="action" type="hidden" value="editcomment" />
		<input name="picno" type="hidden" value="<?php echo $resno;?>" />
		<input name="pageno" type="hidden" value="<?php echo $pageno;?>" />

		<h1 class="header">
			<?php tt('ecomm_title', 'niftyusage.php" onclick="openWindow(\'niftyusage.php\', 500, 300); return false;');?> 
		</h1>
	
		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_comments');?> 
			</td>
			<td class="infoenter" valign="top">
				<textarea name="comment2" cols="60" rows="8" class="multiline"><?php echo w_html_chars($commrow['comment']);?></textarea>
			</td>
			</tr>

<?php if ($user['rank'] != RANK_MOD) {?>
			<tr>
			<td class="infoask">
<?php if ($OekakiU != $commrow['usrname'] && $flags['admin']) { ?>
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
				<input name="idno" type="hidden" value="<?php echo $id_3;?>" />
				<input name="commedit" type="submit" value="<?php tt('word_edit');?>" class="submit" />
			</td>
			</tr>
			</table>
		</div>
	</form>
</div>


<?php

include 'footer.php';