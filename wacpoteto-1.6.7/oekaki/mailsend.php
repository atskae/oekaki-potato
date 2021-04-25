<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6 - Last modified 2011-01-13
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

//Input
$sendto = w_gpc('sendto');
$MID    = w_gpc('MID', 'i');


if ($cfg['use_mailbox'] != 'yes') {
	report_err( t('mailerrmsg1'));
}
if (!empty($OekakiU)) {
	if (!$flags['member']) {
		report_err( t('mailerrmsg2'));
	}
} else {
	report_err( t('mailerrmsg3'));
}


if ($mode == 'reply') {
	$result = db_query("SELECT * FROM {$db_mem}oekakimailbox WHERE MID='$MID'");
	$row = db_fetch_array($result);
	if ($row['reciever'] != $OekakiU) {
		report_err( t('mailerrmsg4'));
	}
}


include 'header.php';


if ($mode == 'reply') {
	$result = db_query("SELECT * FROM {$db_mem}oekakimailbox WHERE MID='$MID'");
	$row = db_fetch_array($result);
} else {
	$row = array();
	$row['subject'] = '';
}

?>
<div id="contentmain">
	<form name="form1" method="post" action="functions.php">
		<input type="hidden" name="action" value="mailsend" />

		<div class="infotable">
			<?php tt('mailbox_label');?> 
			[<a href="mailbox.php"><?php tt('word_inbox');?></a>]
			[<a href="mailout.php"><?php tt('word_outbox');?></a>]
		</div>
		<br />


		<!-- Send -->
		<h1 class="header">
			<?php tt('sendm_title');?> 
		</h1>

		<div class="infotable">
			<p>
				<strong><?php tt('send_to_label');?></strong> <em><?php tt('sendm_recip');?></em>
			</p>
			<p class="subtext">
				<input name="reciever" type="text" class="txtinput" id="to" style="width:600px;" value="<?php echo $sendto;?>" size="40" maxlength="255" />
			</p>

			<p>
				<strong><?php tt('subject_label');?></strong> <em><?php tt('mail_subdesc');?></em>
			</p>
			<p class="subtext">
<?php
// Don't add 'RE:' if it's there
($mode == 'reply' && strtolower(substr($row['subject'], 0, 3)) != 're:')
	? $add_re = 'RE: '
	: $add_re = '' ;
?>
				<input name="subject" type="text" class="txtinput" id="subject" style="width:600px;" value="<?php echo $add_re.$row['subject']; ?>" size="40" maxlength="255" />
			</p>

			<p>
				<strong><?php tt('message_label');?></strong> <em><?php tt('mail_bodydesc');?></em>
			</p>
			<p class="subtext">
				<?php tt('common_niftytoo', 'niftyusage.php" onclick="openWindow(\'niftyusage.php\', 500, 300); return false');?> 
			</p>
			<p>
				<textarea name="body" cols="40" rows="10" class="multiline" id="body" style="width:600px;"><?php if ($mode == 'reply') echo "\n\n".t('orgmessage').": \n".$row['body'];?></textarea>
			</p>

<?php
			if ($mode == 'reply') {
?>
				<input type="hidden" name="MID" value="<?php echo $MID;?>" />
				<input type="hidden" name="reply" value="1" />
<?php
			} else {
?>
				<input type="hidden" name="reply" value="0" />
<?php
			}
?>

			<p class="subtext">
				<input name="Send" type="submit" id="Send" value="<?php tt('word_send');?>" class="submit" />
			</p>
		</div>
	</form>
</div>


<?php

include 'footer.php';