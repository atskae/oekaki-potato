<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2012 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6x - Last modified 2011-03-17 (x:2012-01-06)
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

if (!$flags['admin']) {
	report_err( t('nocredeu'));
}


// Init
$reg_purge_display_num = 5;


// Input
$usrname2 = w_gpc('usrname2');
$h_usrname2 = w_html_chars($usrname2);


// Purge registrations
if ($flags['admin'] && $action == 'reg_check_delete') {
	if (w_gpc('confirm', 'i') == '1') {
		$result = db_query("DELETE FROM {$db_mem}oekaki WHERE usrflags='P'");
		all_done('addusr.php');
	}

	// Confirm
	require 'header.php';
?>
<div id="contentmain">
	<h1 class="header">
		<?php tt('delselect');?> 
	</h1>

	<div class="infotable">
		<form name="check_form" method="post" action="addusr.php">
			<input type="hidden" name="action" value="reg_check_delete" />
			<input type="hidden" name="confirm" value="1" />
			<div style="text-align: center;">
				<?php tt('sure_purge_regs', w_gpc('reg_count', 'i') );?><br />
				<br />
				<input type="submit" name="submit" value="Purge All Registrations" class="submit" /><br />
				<br />
				<br />
				<a href="addusr.php"><?php tt('returnbbs');?></a>
			</div>
			<br />
		</form>
	</div>
</div>


<?php
	include 'footer.php';
	w_exit();
}


// Kill registration (anti-spam?)
if (w_gpc('kill', 'i') && !empty($usrname2)) {
	// Make sure the account is pending, to prevent issues with spoofed URLs
	$result2 = db_query("SELECT usrname, usrflags FROM {$db_mem}oekaki WHERE usrname='$usrname2'");
	$rownum = db_num_rows($result2);
	if ($rownum) {
		$row = db_fetch_array($result2);
		if ($row['usrflags'] == 'P') {
			$result = db_query("DELETE FROM {$db_mem}oekaki WHERE usrname='$usrname2'");
		} else {
			report_err("Delete: '{$h_usrname2}' is not pending!");
		}
	} else {
		report_err("Delete: member '{$h_usrname2}' not found.");
	}

	all_done('addusr.php');
}


require 'header.php';


$result2 = db_query("SELECT usrname, url, email, joindate, age FROM {$db_mem}oekaki WHERE usrflags='P' ORDER BY joindate DESC");
$rownum = db_num_rows($result2);

?>
<div id="contentmain">
<?php

if (empty($usrname2)) {

?>
	<!-- Select user -->
	<h1 class="header">
		<?php tt('addusr_vpending');?> 
	</h1>

	<div class="infotable">
<?php if ($cfg['approval'] != 'yes') { ?>
	<p class="infonote">
		<?php tt('admnote');?> 
	</p>

<?php } ?>
		<table class="infomain">
		<tr>
		<td><strong><?php tt('word_username');?></strong></td>
		<td><strong><?php tt('word_email');?></strong></td>
		<td><strong><?php tt('word_url');?></strong></td>
		<td><strong><?php tt('word_age');?></strong></td>
		<td><strong><?php tt('registered_on', $rownum);?></strong></td>
		</tr>
<?php
for ($i = 0; $i < $rownum; $i++) {
	$row = db_fetch_array($result2);
	?>

		<tr>
		<td>
			<a href="addusr.php?usrname2=<?php echo urlencode($row['usrname']) ?>"><?php echo w_html_chars($row['usrname']);?></a>
		</td>

		<td>
<?php
if (empty($row['email'])) {
	echo '			<strong>(N/A)</strong>'."\n";
} elseif ($row['email'] != '' && !strstr($row['email'], '@')) {
	echo '			<strong>('.t('word_invalid').')</strong> ('.w_html_chars($row['email']).")\n";
} else {
	echo '			'.w_html_chars($row['email'])."\n";
}
?>
		</td>

		<td>
			<?php
if (strlen($row['url']) < 8) {
	echo t('word_no')."\n";
} else {
	echo '<a href="'.w_html_chars($row['url']).'">'.t('word_yes').'</a>'."\n";
}
?>
		</td>

		<td>
<?php
	$the_date = $row['age'];
	$the_age  = get_age($the_date);

	if (strpos($the_date, '-') === false) {
		// No birthday
		$the_b_day = '';
	} else {
		$the_b_day = ' <small>('.$the_date.')</small>';
	}

	echo $the_age.$the_b_day."\n";
?>
		</td>

		<td>
			<?php echo w_html_chars($row['joindate']);?> 
		</td>
		</tr>
<?php } ?>
		</table>
<?php if ($flags['admin'] && $rownum >= $reg_purge_display_num) { ?>

		<hr style="display: block" />
		<br />

		<form name="check_form" method="post" action="addusr.php">
			<input type="hidden" name="action" value="reg_check_delete" />
			<input type="hidden" name="reg_count" value="<?php echo $rownum;?>" />
			<input type="submit" name="submit" value="Purge All Registrations" class="submit" style="margin-left: 15pt;" />
		</form>
		<br />
<?php } ?>
	</div>
<?php

} else {
	$result = db_query('SELECT usrname, comment, url, email, IP FROM '.$db_mem."oekaki WHERE usrname='$usrname2'");
	$row = db_fetch_array($result);

?>
	<form name="form1" method="post" action="functions.php">
		<input name="action" type="hidden" value="adduser" />

		<!-- Details -->
		<h1 class="header">
			<?php tt('addusr_vpendingdet');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_username');?> 
			</td>
			<td class="infoenter" valign="top">
				<?php echo $h_usrname2;?> 
				<input type="hidden" name="usrname2" value="<?php echo $h_usrname2;?>" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_email');?> 
			</td>
			<td class="infoenter" valign="top">
				<?php echo w_html_chars($row['email']);?> 
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_comment');?> 
			</td>
			<td class="infoenter" valign="top">
				<?php echo w_html_chars($row['comment']);?> 
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('addusr_arturl');?> 
			</td>
			<td class="infoenter" valign="top">
				<a href="<?php echo w_html_chars($row['url']);?>" target="_blank"><?php echo w_html_chars($row['url']);?></a>
			</td>
			</tr>

			<tr>
			<td class="infoask" colspan="2">&nbsp;</td>
			</tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('reciphost');?> 
			</td>
			<td class="infoenter" valign="top">
				<?php
	$newhost = '';
	if (! (defined('DISABLE_DNS_HOST_LOOKUP') && DISABLE_DNS_HOST_LOOKUP)) {
		$newhost = ' ('.@gethostbyaddr ($address).')';
	}

	echo ($row['IP'].$newhost);
?>
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('anti_spam');?> 
			</td>
			<td class="infoenter" valign="top">
				<a href="addusr.php?kill=1&amp;usrname2=<?php echo urlencode($usrname2);?>"><?php tt('anti_spam_delete');?></a>
			</td>
			</tr>


			<tr>
			<td class="infoask" colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('common_drawacc');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="reg_draw" class="multiline">
<?php if ($cfg['reg_draw'] == 'yes') { ?>
					<option value="1" selected="selected"><?php tt('word_enable');?></option>
					<option value="0"><?php tt('word_disable');?></option>
<?php } else { ?>
					<option value="0" selected="selected"><?php tt('word_disable');?></option>
					<option value="1"><?php tt('word_enable');?></option>
<?php } ?>
				</select>
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('common_aniacc');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="reg_anim" class="multiline">
<?php if ($cfg['reg_anim'] == 'yes') { ?>
					<option value="1" selected="selected"><?php tt('word_enable');?></option>
					<option value="0"><?php tt('word_disable');?></option>
<?php } else { ?>
					<option value="0" selected="selected"><?php tt('word_disable');?></option>
					<option value="1"><?php tt('word_enable');?></option>
<?php } ?>
				</select>
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('type_uaccess');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="reg_upload" class="multiline">
<?php if ($cfg['reg_upload'] == 'yes') { ?>
					<option value="1" selected="selected"><?php tt('word_enable');?></option>
					<option value="0"><?php tt('word_disable');?></option>
<?php } else { ?>
					<option value="0" selected="selected"><?php tt('word_disable');?></option>
					<option value="1"><?php tt('word_enable');?></option>
<?php } ?>
				</select>
			</td>
			</tr>


			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_action');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="accept" class="multiline">
					<option value="1" selected="selected"><?php tt('word_accept');?></option>
					<option value="0"><?php tt('word_reject');?></option>
				</select>
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('addusr_comment');?>:
			</td>
			<td class="infoenter" valign="top">
				<textarea name="reason" cols="30" rows="5" class="multiline"></textarea>
			</td>
			</tr>

			<tr>
			<td class="infoask">&nbsp;</td>
			<td class="infoenter" valign="top">
				<br />
				<input type="submit" name="pmember2" value="<?php tt('word_submit');?>" class="submit" />
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