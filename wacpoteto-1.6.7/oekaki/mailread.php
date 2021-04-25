<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.0 - Last modified 2015-02-11
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

// Input
$MID = w_gpc('MID', 'i');



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


$result = db_query("SELECT * FROM {$db_mem}oekakimailbox WHERE MID='$MID'");
$row = db_fetch_array($result);


if ($row['reciever'] != $OekakiU && $row['sender'] != $OekakiU) {
	report_err( t('mailerrmsg4'));
}

if ($row['reciever'] == $OekakiU) {
	$result = db_query("UPDATE {$db_mem}oekakimailbox SET mstatus=".MAIL_READ." WHERE MID='$MID'");
}


include 'header.php';


$result = db_query("SELECT * FROM {$db_mem}oekakimailbox WHERE MID='$MID'");
$row = db_fetch_array($result);


?>
<div id="contentmain">
	<div class="infotable">
		<?php tt('mailbox_label');?> 
		[<a href="mailbox.php"><?php tt('word_inbox');?></a>]
		[<a href="mailout.php"><?php tt('word_outbox');?></a>]
		[<a href="mailsend.php?sendto=<?php echo urlencode($row['sender']);?>&amp;MID=<?php echo $row['MID']?>&amp;mode=reply"><?php tt('word_reply');?></a>]
		[<a href="functions.php?mode=maildelete&amp;MID=<?php echo $row['MID']?>"><?php tt('word_delete');?></a>]
	</div>
	<br />


	<!-- Read -->
	<h1 class="header">
		<?php tt('readm_title');?> 
	</h1>

	<div class="infotable">
		<p>
			<strong><?php tt('from_label');?></strong>
			<?php
			$my_link = '#" onclick="openWindow(\'profile.php?user='.urlencode($row['sender']).'\', 400, 500); return false;';
			tt('mail_sender_datetime', $my_link, w_html_chars($row['sender']), $row['senddate']); ?> 
		</p>

		<p>
			<strong><?php tt('subject_label');?></strong>
			<?php echo w_html_chars($row['subject']);?> 
		</p>

		<div class="infotable">
			<p>
				<?php echo nifty2_convert($row['body']);?> 
			</p>
		</div>
		<br />

	</div>
</div>


<?php

include 'footer.php';