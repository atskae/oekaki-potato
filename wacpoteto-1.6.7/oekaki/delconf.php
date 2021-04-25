<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6x - Last modified 2011-01-22 (x:2015-01-27)
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

// Input
$picno      = w_gpc('picno', 'i+');
$cmtno      = w_gpc('cmtno', 'i+');
$page       = w_gpc('page', 'i+');
$post       = w_gpc('post', 'i+');
$returnpage = w_gpc('return', 'a');
// $mode
// $a_match


// Delete picture
if ($picno > 0) {
	$result = db_query("SELECT ptype FROM {$db_p}oekakidta WHERE PIC_ID='{$picno}'");
	if (!$result || db_num_rows($result) == 0) {
		report_err( t('delconf_pic_err', $picno));
	}
	$ext = db_result($result, 0);

	include 'header.php';

?>
<div id="contentmain">
	<form action="functions.php" method="post" name="form1">
		<input name="action" type="hidden" value="<?php echo $mode;?>" />
		<input name="return" type="hidden" value="<?php echo $returnpage;?>" />
		<input type="hidden" name="picno" value="<?php echo $picno;?>" />
<?php if (!empty($a_match)) { ?>
		<input name="a_match" type="hidden" value="<?php echo w_html_chars($a_match);?>" />
<?php } ?>

		<h1 class="header">
			<?php tt('common_deletepic');?> 
		</h1>

		<div class="infotable">
			<p style="text-align: center;">
				<img src="<?php echo $p_pic.$picno.'.'.$ext; ?>" alt="Pic #<?php echo $picno;?>" />
			</p>

			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('common_picno');?> 
			</td>
			<td class="infoenter" valign="top">
				<?php echo $picno;?> 
			</td>
			</tr>

<?php if ($mode == 'dela' && $flags['admin']) { ?>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('sreasonmail');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="givereason">
					<option value="1" selected="selected"><?php tt('word_yes');?></option>
					<option value="0"><?php tt('word_no');?></option>
				</select>
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_reason');?> 
			</td>
			<td class="infoenter" valign="top">
				<textarea name="reason" cols="30" rows="5" class="multiline"></textarea>
			</td>
			</tr>
<?php } ?>

			<tr>
			<td class="infoask">&nbsp;</td>
			<td class="infoenter" valign="top">
				<br />
				<input name="blah" type="submit" class="submit" id="blah" value="<?php tt('word_delete');?>" />
			</td>
			</tr>
			</table>
		</div>
	</form>
</div>
<?php
	include 'footer.php';
	w_exit();
}


// Delete comment
if ($cmtno > 0) {
	$result = db_query("SELECT usrname, comment, postdate FROM {$db_p}oekakicmt WHERE ID_3='{$cmtno}'");
	if (!$result || db_num_rows($result) == 0) {
		report_err('Unable to read comment #'.$picno);
	}
	$outerrow = db_fetch_array($result);

	include 'header.php';
?>
<div id="contentmain">
	<h1 class="header">
		<?php tt('header_dcomm');?> 
	</h1>

	<div class="infotable">
		<div class="commentheader">
			<strong>
				<?php echo w_html_chars($outerrow['usrname']);?> 
			</strong>
			<span class="commentinfo">@ <?php echo date($datef['post_header'], strtotime($outerrow['postdate'])); ?></span>
		</div>
		<div class="commentdata">
<?php if (!empty($outerrow['comment'])) { ?>
			<?php echo nifty2_convert($outerrow['comment']); ?> 
<?php } else { ?>
			<small><?php tt('no_comment');?></small>
<?php } ?>
		</div>

		<p style="text-align: center;">
<?php // set page and post
	$extra = '';
	if ($page) {
		$extra .= "&amp;page={$page}";
	}
	if ($post) {
		$extra .= "&amp;post={$post}";
	}
?>
			<a href="functions.php?mode=udelcmt&amp;cmtno=<?php echo $cmtno.$extra;?>"><?php tt('word_delete');?></a>
		</p>
		<p style="text-align: center;">
			<a href="index.php" onclick="history.back(1); return false;"><?php tt('returnbbs');?></a>
		</p>
	</div>
</div>
<?php
	include 'footer.php';
	w_exit();
}


// Fall-through
report_err('No picture or comment specified.');