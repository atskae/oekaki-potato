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
// $pageno


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


include 'header.php';


// Get totals
$readmail = db_query("SELECT COUNT(MID) FROM {$db_mem}oekakimailbox WHERE sender='$OekakiU'");
$mailrows = db_result($readmail, 0);
$pages = ceil($mailrows / $cfg['menu_pages']);

$result = db_query("SELECT * FROM {$db_mem}oekakimailbox WHERE sender='$OekakiU' ORDER BY MID DESC LIMIT ".($pageno * $cfg['menu_pages']).', '.$cfg['menu_pages']);


?>
<div id="contentmain">

	<div class="infotable">
		<?php tt('mailbox_label');?> 
		[<a href="mailbox.php"><?php tt('word_inbox');?></a>]
		<strong>[<?php tt('word_outbox');?>]</strong>
		[<a href="mailsend.php"><?php tt('word_write');?></a>]
		<br />
		<br />
		<?php echo quick_page_numbers(0, $pageno, $pages, 4, 'mailout.php?pageno={page_link}')."\n"; ?>
	</div>
	<br />


	<!-- Outbox -->
	<h1 class="header">
		<?php tt('word_outbox');?>
	</h1>

	<div class="infotable">
		<table class="infomain">
		<tr>
		<td>
			<strong><?php tt('word_to');?></strong>
		</td>
		<td>
			<strong><?php tt('word_subject');?></strong>
		</td>
		<td>
			<strong><?php tt('word_date');?></strong>
		</td>
		<td>
			<strong><?php tt('word_status');?></strong>
		</td>
		</tr>
<?php

while ($row = db_fetch_array($result)) {?>

		<tr>
		<td>
<?php if ($row['reciever'] == $OekakiU) { ?>
			<?php tt('word_yourself', (strtolower($user['gender']) == 'female') ? 2 : 1); ?> 
<?php } else { ?>
			<a onclick="openWindow('profile.php?user=<?php echo urlencode($row['reciever']);?>', 400, 500); return false" href="profile.php?user=<?php echo urlencode($row['reciever']);?>"> <?php echo w_html_chars($row['reciever']);?></a>
<?php } ?>
		</td>
		<td>
			<a href="mailread.php?MID=<?php echo $row['MID']?>"> <?php echo w_html_chars($row['subject']);?></a>
		</td>
		<td>
			<?php echo $row['senddate'];?> 
		</td>
		<td>
			<?php
	switch ($row['mstatus']) {
		case MAIL_NEW:
			echo t('word_new');
			break;
		case MAIL_UNREAD:
			echo t('word_unread');
			break;
		case MAIL_READ:
			echo t('word_read');
			break;
		case MAIL_REPLIED:
			echo t('word_replied');
			break;
		default:
			echo t('word_new');
	}
?> 
		</td>
		</tr>
<?php
}
?>
		</table>
	</div>
</div>


<?php

include 'footer.php';