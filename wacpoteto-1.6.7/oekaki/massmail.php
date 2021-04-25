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


if ($cfg['use_mailbox'] != 'yes') {
	report_err( t('mailerrmsg1'));
}
if (!empty($OekakiU)) {
	if (!$flags['owner']) {
		report_err( t('mailerrmsg5'));
	}
} else {
	report_err( t('mailerrmsg3'));
}


require 'header.php';


// How many users?
$result = db_query("SELECT COUNT(ID) FROM {$db_mem}oekaki WHERE usrflags !='P'");
$num_users = (int) db_result($result, 0);

// How many super admins?
$result = db_query("SELECT COUNT(ID) FROM {$db_mem}oekaki WHERE rank=".RANK_SADMIN);
$num_sadmins = (int) db_result($result, 0);

// How many admins?
$result = db_query("SELECT COUNT(ID) FROM {$db_mem}oekaki WHERE rank >= ".RANK_MOD);
$num_admins = (int) db_result($result, 0);

// How many active members?
$active_members = 0;
if ($cfg['kill_user'] < ACTIVE_LOGIN_TIME_DAYS) {
	$active_search = db_query("SELECT COUNT(usrname) FROM {$db_mem}oekaki WHERE (DATE_ADD(lastlogin, INTERVAL ".ACTIVE_LOGIN_TIME_DAYS." DAY) >= NOW())");
	$active_members = (int) db_result($active_search, 0);
}


?>
<div id="contentmain">
	<form name="form1" method="post" action="functions.php">
		<input name="action" type="hidden" value="massmail" />

		<div class="infotable">
			<?php tt('mailbox_label');?> 
			[<a href="mailbox.php"><?php tt('word_inbox');?></a>]
			[<a href="mailout.php"><?php tt('word_outbox');?></a>]
		</div>
		<br />


		<!-- Massmail -->
		<h1 class="header">
			<?php tt('massm_smassm');?> 
		</h1>

		<div class="infotable">
			<p class="infotable">
				<?php tt('massmailmsg1');?><br />
				<br />
				<?php tt('massmailmsg2');?><br />
				<br />
				<?php tt('mmail_reg_list', ($num_users - $num_admins), $num_admins);?><br />
				<br />
				<?php tt('mmail_active_list', $active_members, ACTIVE_LOGIN_TIME_DAYS);?> 
			</p>

			<p>
				<strong><?php tt('send_to_label');?></strong>
			</p>
			<p class="subtext">
				<select name="send_to" class="txtinput">
					<option value="G"><?php tt('mmail_to_everyone', $num_users);?></option>
<?php if ($active_members > 1 && $num_users > 15) { ?>
					<option value="Active"><?php tt('mmail_to_active', $active_members);?></option>
<?php } ?>
					<option value="A" selected="selected"><?php tt('mmail_to_admins_mods', $num_admins);?></option>
					<option value="S"><?php tt('mmail_to_superadmins');?></option>
					<option value="D"><?php tt('mmail_to_draw_flag');?></option>
					<option value="U"><?php tt('mmail_to_upload_flag');?></option>
					<option value="X"><?php tt('mmail_to_adult_flag');?></option>
					<option value="I"><?php tt('mmail_to_immune_flag');?></option>
				</select>
			</p>

			<p>
				<strong><?php tt('subject_label');?></strong>
				<em><?php tt('mail_subdesc');?></em>
			</p>
			<p class="subtext">
				<input name="subject" type="text" class="txtinput" style="width: 100%" size="40" maxlength="255" />
			</p>

			<p>
				<strong><?php tt('message_label');?></strong>
				<em><?php tt('mail_bodydesc');?></em>
			</p>
			<p class="subtext">
				<?php tt('common_niftytoo', 'niftyusage.php" onclick="openWindow(\'niftyusage.php\', 500, 300); return false');?> 
			</p>

			<p>
				<textarea name="body" cols="40" rows="10" class="multiline" style="width: 100%;"></textarea>
			</p>

			<p>
				<input name="mass" type="submit" value="<?php tt('word_send');?>" class="submit" />
			</p>
		</div>
	</form>
</div>


<?php

include 'footer.php';