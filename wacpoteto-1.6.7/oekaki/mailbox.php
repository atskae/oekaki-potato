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
$readmail = db_query("SELECT COUNT(MID) FROM {$db_mem}oekakimailbox WHERE reciever='$OekakiU'");
$mailrows = db_result($readmail, 0);
$pages = ceil($mailrows / $cfg['menu_pages']);

$readmail = db_query("SELECT COUNT(MID) FROM {$db_mem}oekakimailbox WHERE reciever='$OekakiU' AND (mstatus=".MAIL_UNREAD." OR mstatus=".MAIL_NEW.")");
$mail_unread = db_result($readmail, 0);

$result = db_query("SELECT * FROM {$db_mem}oekakimailbox WHERE reciever='$OekakiU' ORDER BY MID DESC LIMIT ".($pageno * $cfg['menu_pages']).', '.$cfg['menu_pages']);


?>
<div id="contentmain">

	<div class="infotable">
		<?php tt('mailbox_label');?> 
		<strong>[<?php tt('word_inbox');?>]</strong>
		[<a href="mailout.php"><?php tt('word_outbox');?></a>]
		[<a href="mailsend.php"><?php tt('word_write');?></a>]
		<br />
		<br />
		<table style="width: 100%;">
		<tr>
		<td align="left">
			<?php echo quick_page_numbers(0, $pageno, $pages, 4, 'mailbox.php?pageno={page_link}')."\n"; ?>
		</td>
		<td align="right">
			<?php tt('mail_count', $mailrows, $mail_unread);?> 
		</td>
		</tr>
		</table>
	</div>
	<br />


	<!-- Mailbox -->
	<h1 class="header">
		<?php tt('word_inbox');?> 
	</h1>

	<form name="check_form" class="infotable" action="functions.php" method="post">
		<table class="infomain">
		<tr>
		<td>
			<strong>Select</strong>
		</td>
		<td>
			<strong><?php tt('word_from');?></strong>
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
while ($row = db_fetch_array($result)) {
?>

		<tr>
		<td>
			<input type="checkbox" name="MD[]" value="<?php echo $row['MID'];?>" />
		</td>
		<td>
<?php if ($row['sender'] == $OekakiU) { ?>
			<?php tt('word_yourself', (strtolower($user['gender']) == 'female') ? 2 : 1); ?> 
<?php } else { ?>
			<a onclick="openWindow('profile.php?user=<?php echo w_html_chars($row['sender']);?>', 400, 500); return false" href="profile.php?user=<?php echo urlencode($row['sender']);?>"><?php echo w_html_chars($row['sender']);?></a>
<?php } ?>
		</td>
		<td>
<?php if ($row['mstatus'] == MAIL_UNREAD) { ?>
			<strong><a href="mailread.php?MID=<?php echo $row['MID']?>"><?php echo w_html_chars($row['subject']);?></a></strong>
<?php } else { ?>
			<a href="mailread.php?MID=<?php echo $row['MID']?>"><?php echo w_html_chars($row['subject']);?></a>
<?php } ?>
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

$result = db_query("UPDATE {$db_mem}oekakimailbox SET mstatus=".MAIL_UNREAD." WHERE reciever='$OekakiU' AND mstatus=".MAIL_NEW);
?>
		</table>

		<hr style="display: block" />
		<br />

<?php if ($mailrows > 0) { ?>
		<input type="hidden" name="action" value="mail_check_delete" />
		<input type="button" name="toggle" value="<?php tt('revselect');?>" onclick="toggle_checks('MD[]', this.form);" class="submit" />
		<input type="submit" name="submit" value="<?php tt('delselect');?>" class="submit" style="margin-left: 15pt;" />
<?php } ?>
		<br />
		<br />
	</form>
</div>


<?php

include 'footer.php';