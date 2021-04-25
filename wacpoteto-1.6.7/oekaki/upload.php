<?php
/*
Wacintaki Poteto - Copyright 2004-2018 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.6x - Last modified 2018-05-21
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


if ( !($flags['mod'] || $flags['U'])) {
	report_err( t('err_upload'));
}

// Java applets allowed?
$java_exists = true;
$oeb_exists = false;
$chi_exists = false;
if (defined('APPLET_DISABLE_JAVA') && APPLET_DISABLE_JAVA) {
	$java_exists = false;
} else {
	if (file_exists('oekakibbs.jar')) {
		$oeb_exists = true;
	}
	if (file_exists('chibipaint.jar')) {
		$chi_exists = true;
	}
}

include 'header.php';


// Check for unfinished pictures
$result = db_query("SELECT COUNT(PIC_ID) FROM {$db_p}oekakidta WHERE postlock='0' AND usrname='$OekakiU'");
$num_rows = (int) db_result($result, 0);

$too_many = false;
if ($num_rows >= $cfg['safety_max']) {
	$too_many = true;
}

if ($too_many) {
	// Unfinished pictures interface
?>
<div id="contentmain">

	<!-- Unfinished -->
	<h1 class="header">
		<?php tt('icomppic');?> 
	</h1>

	<div class="infotable" style="text-align: center">
		<p>
			<?php tt('unfinished_warning', $num_rows); ?> 
		</p>

		<p>
			<a href="recover.php"><?php tt('clickrecoverpic');?></a>
		</p>
	</div>
</div>


<?php
	include 'footer.php';
	exit;
}


// ----
// Main
//
?>
<div id="contentmain">

<?php
// Print WIP notice if there is at least one, but still under limit
if ($num_rows > 0) {
	// Unfinished pictures interface
?>
	<!-- Unfinished -->
	<h1 class="header">
		<?php tt('word_reminder');?> 
	</h1>

	<div class="infotable" style="text-align: center">
		<p>
			<?php tt('unfinished_notice', $num_rows);?> 
		</p>

		<p>
			<a href="recover.php"><?php tt('clickrecoverpic');?></a>
		</p>
	</div>
	<br />


<?php
}
?>
	<form name="form1" enctype="multipart/form-data" method="post" action="functions.php">
		<input name="action" type="hidden" value="upload" />

		<h1 class="header">
			<?php tt('upload_title');?> 
		</h1>

		<div class="infotable">
			<table>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('pictoupload');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="picture" class="txtinput" type="file" size="60" />

				<p class="subtext">
					<?php tt('upldvalidtyp');?><br />
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


<?php if ($flags['M']) { ?>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('animatoupd');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="animation" class="txtinput" type="file" size="60" />

				<p class="subtext">
					<strong><?php tt('uploadmsg1');?></strong>
<?php if ($oeb_exists) { ?>
					<?php tt('uploadmsg2');?> 
<?php } else { ?>
					<?php tt('uploadmsg3');?> 
<?php } ?>
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


<?php } ?>
<?php if ($flags['mod']) { ?>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_owner');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="usrname" class="multiline">
<?php

$result2 = db_query("SELECT usrname FROM {$db_mem}oekaki WHERE usrflags!='P' ORDER BY usrname ASC");

while ($row = db_fetch_array($result2)) {
?>
					<option value="<?php echo w_html_chars($row['usrname']);?>"<?php if(addslashes($row['usrname']) == $OekakiU) echo ' selected="selected"';?>><?php echo w_html_chars($row['usrname'])?></option>
<?php
}
?>
				</select>
			</td>
			</tr>


<?php } ?>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('appletype');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="dtype" class="multiline">
					<option value="5">ChickenPaint</option>
<?php if ($java_exists) { ?>
					<option value="2">ShiPainter</option>
					<option value="3">ShiPainter Pro</option>
<?php if ($chi_exists) { ?>
					<option value="4">Chibi Paint</option>
<?php } ?>
<?php if ($oeb_exists) { ?>
					<option value="1">OekakiBBS</option>
<?php } ?>
					<option value="0">PaintBBS</option>
<?php } ?>
				</select>
			</td>
			</tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('timeinvest');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="edittime" type="text" value="0" size="4" class="txtinput" />

				<p class="subtext">
					<?php tt('uploadmsg4');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask">
				<?php tt('comment_adult', MIN_AGE_ADULT);?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="adult" class="multiline">
					<option value="0" selected="selected"><?php tt('word_no');?></option>
					<option value="1"><?php tt('word_yes');?></option>
				</select>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask">
				<?php tt('comment_pictitle');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="title" type="text" value="" size="40" class="txtinput" />
			</td>
			</tr>


			<tr>
			<td class="infoask">
				<?php tt('word_comments');?> 
			</td>
			<td class="infoenter" valign="top">
				<textarea name="comment" cols="40" rows="5" class="multiline"></textarea>
			</td>
			</tr>


<?php if ($flags['mod']) { ?>
			<tr>
			<td class="infoask">
				<?php tt('admin_uploaded_by');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="uploadby" class="multiline">
					<option value="1" selected="selected"><?php tt('word_yes');?></option>
					<option value="0"><?php tt('word_no');?></option>
				</select>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


<?php } ?>
			<tr>
			<td class="infoask">&nbsp;</td>
			<td class="infoenter" valign="top">
				<input name="upload" type="submit" value="<?php tt('word_upload');?>" class="submit" />
			</td>
			</tr>
			</table>
		</div>
	</form>
</div>


<?php

include 'footer.php';