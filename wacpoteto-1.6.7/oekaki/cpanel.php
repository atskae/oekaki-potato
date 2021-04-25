<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.0 - Last modified 2015-02-12
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


if (!$flags['owner']) {
	report_err( t('noaccesscp'));
}


// User submits information
if (isset($_POST['action'])) {
	// Verify new picstore value
	if (w_gpc('pic_store', 'i') < $cfg['pic_store']) {
		if (w_gpc('pic_store_confirm') != 'confirm') {
			report_err( t('pic_store_change_confirm'));
		}
	}

	// Get rid of trailing slashes
	$bbs_url         = strip_path(w_gpc('op_url'));
	$bbs_preview_img = strip_path(w_gpc('preview_img'));
	$bbs_pr0n_img    = strip_path(w_gpc('xxx_img'));
	$avatar_folder   = strip_path(w_gpc('avatar_folder'));

	// Fix email
	$bbs_email = trim(w_gpc('op_email'));

	// Fix HTML entries, like the BBS title.
	$bbs_title  = w_gpc('op_title', 'html');
	$bbs_author = w_gpc('op_author', 'html');
	$bbs_preview_title = w_gpc('preview_title', 'html');

	// Other verification
	$bbs_language = w_gpc('language');
	$bbs_template = w_gpc('template');

	$reg_draw   = 'no';
	$reg_anim   = 'no';
	$reg_upload = 'no';
	if (w_gpc('reg_draw')   == 'D') $reg_draw = 'yes';
	if (w_gpc('reg_anim')   == 'A') $reg_anim = 'yes';
	if (w_gpc('reg_upload') == 'U') $reg_upload = 'yes';

	// Verify min/max values
	{
		$verify     = array('pic_store', 'pic_pages', 'menu_pages', 'def_x', 'def_y', 'canvas_x', 'canvas_y', 'min_x', 'min_y', 'canvas_a', 'max_anim', 'thumb_bytes', 'thumb_t', 'thumb_r', 'max_pic', 'avatar_x', 'avatar_y', 'safety_storetime', 'cut_email', 'safety_max');
		$verify_min = array(50,           1,           5,            50,      50,      50,         50,         50,      50,      2500,       1000,       25000,         50,        50,        100000,    5,          5,          1,                  0,           1);
		$verify_max = array(500000,       50,          500,          10000,   10000,   10000,      10000,      10000,   10000,   100000000,  5000000,    15000000,      500,       1000,      15000000,  150,        150,        10000,              2,           10);

		for ($i = 0; $i < count($verify); $i++) {
			if ($_POST[$verify[$i]] < $verify_min[$i]) $_POST[$verify[$i]] = $verify_min[$i];
			if ($_POST[$verify[$i]] > $verify_max[$i]) $_POST[$verify[$i]] = $verify_max[$i];
		}
	}


	// Create a configuration file with specified settings
	$main_configuration = <<<EOF
<?php // Include only
	\$cfg = array();
	\$cfg['version'] = '1.5.5';

	\$cfg['op_title']  = '{$bbs_title}';
	\$cfg['op_author'] = '{$bbs_author}';
	\$cfg['op_adult']  = '{$_POST['op_adult']}';
	\$cfg['op_email']  = '{$bbs_email}';
	\$cfg['op_url']    = '{$bbs_url}';
	\$cfg['cut_email'] = '{$_POST['cut_email']}';

	\$cfg['salt']    = '{$cfg['salt']}';
	\$cfg['op_pics'] = '{$cfg['op_pics']}';
	\$cfg['op_pre']  = '{$cfg['op_pre']}';

	\$cfg['kill_user']     = '{$_POST['kill_user']}';
	\$cfg['kill_reg']      = '15';
	\$cfg['draw_immune']   = '{$_POST['draw_immune']}';
	\$cfg['private']       = '{$_POST['private']}';
	\$cfg['approval']      = '{$_POST['approval']}';
	\$cfg['require_art']   = '{$_POST['require_art']}';
	\$cfg['guest_post']    = '{$_POST['guest_post']}';
	\$cfg['guest_draw']    = 'no'; //WIP
	\$cfg['guest_adult']   = '{$_POST['guest_adult']}';
	\$cfg['humanity_post'] = '{$_POST['humanity_post']}';
	\$cfg['reg_draw']      = '$reg_draw';
	\$cfg['reg_anim']      = '$reg_anim';
	\$cfg['reg_upload']    = '$reg_upload';
	\$cfg['reg_rules']     = '{$_POST['reg_rules']}';
	\$cfg['censoring']     = 'no';

	\$cfg['language'] = '{$bbs_language}';
	\$cfg['template'] = '{$bbs_template}';
	\$cfg['tpl_path'] = '{$cfg['tpl_path']}';
	\$cfg['res_path'] = '{$cfg['res_path']}';

	\$cfg['pic_store']  = '{$_POST['pic_store']}';
	\$cfg['pic_pages']  = '{$_POST['pic_pages']}';
	\$cfg['menu_pages'] = '{$_POST['menu_pages']}';
	\$cfg['pic_limit']      = '2';  //WIP
	\$cfg['pic_limit_time'] = '24'; //WIP
	\$cfg['use_viewer'] = '{$_POST['use_viewer']}';

	\$cfg['porn_img']      = '{$bbs_pr0n_img}';
	\$cfg['preview_img']   = '{$bbs_preview_img}';
	\$cfg['preview_title'] = '{$bbs_preview_title}';
	\$cfg['def_x']    = '{$_POST['def_x']}';
	\$cfg['def_y']    = '{$_POST['def_y']}';
	\$cfg['canvas_x'] = '{$_POST['canvas_x']}';
	\$cfg['canvas_y'] = '{$_POST['canvas_y']}';
	\$cfg['min_x']    = '{$_POST['min_x']}';
	\$cfg['min_y']    = '{$_POST['min_y']}';
	\$cfg['canvas_a'] = '{$_POST['canvas_a']}';
	\$cfg['max_anim'] = '{$_POST['max_anim']}';
	\$cfg['max_pic']  = '{$_POST['max_pic']}';

	\$cfg['use_thumb']   = '{$_POST['use_thumb']}';
	\$cfg['force_thumb'] = '{$_POST['force_thumb']}';
	\$cfg['thumb_bytes'] = '{$_POST['thumb_bytes']}';
	\$cfg['thumb_t']     = '{$_POST['thumb_t']}';
	\$cfg['thumb_r']     = '{$_POST['thumb_r']}';
	\$cfg['thumb_jpg']   = '75';

	\$cfg['use_mailbox'] = '{$_POST['use_mailbox']}';

	\$cfg['use_chat']   = '{$_POST['use_chat']}';
	\$cfg['chat_max']   = '{$_POST['chat_max']}';
	\$cfg['chat_pages'] = '{$_POST['chat_pages']}';

	\$cfg['smilies'] = '{$_POST['smilies']}';

	\$cfg['use_avatars']   = '{$_POST['use_avatars']}';
	\$cfg['use_c_avatars'] = '{$_POST['use_c_avatars']}';
	\$cfg['avatar_folder'] = '{$avatar_folder}';
	\$cfg['avatar_x'] = '{$_POST['avatar_x']}';
	\$cfg['avatar_y'] = '{$_POST['avatar_y']}';

	\$cfg['public_retouch']   = '{$_POST['public_retouch']}';
	\$cfg['safety_saves']     = '{$_POST['safety_saves']}';
	\$cfg['safety_max']       = '{$_POST['safety_max']}';
	\$cfg['safety_storetime'] = '{$_POST['safety_storetime']}';
	\$cfg['self_bump']        = '{$_POST['self_bump']}';

	\$cfg['latest_pic_file'] = '{$_POST['latest_pic_file']}';
EOF;


	// Write the config file
	if ($fp = fopen('config.php', 'w')) {
		fwrite($fp, $main_configuration);
		fclose($fp);
	} else {
		report_err( t('cpanel_cfg_err'));
	}

	// Update templates if needed
	if ($_POST['t_reset'] == 'yes') {
		$result = db_query("UPDATE {$db_mem}oekaki SET templatesel=''");
	}


	// Log cpanel changes
	w_log(WLOG_CPANEL, '$l_c_update');


	// Cleanup picture slots
	@set_time_limit(300);
	include_once 'paintsave.php';
	$cfg['pic_store'] = w_gpc('pic_store', 'i+'); // Reset before cleaning
	clean_picture_slots(500);


	// Update thumbnails
	$cfg['thumb_t'] = $_POST['thumb_t'];
	$cfg['thumb_r'] = $_POST['thumb_r'];
	$use_thumb = 1;

	if ($_POST['use_thumb'] == 0) {
		// Remove all thumbs
		$result = db_query("SELECT PIC_ID FROM {$db_p}oekakidta");

		while ($row = db_fetch_row($result)) {
			fix_thumbnail($row[0], false);
		}
	} elseif ($_POST['use_thumb'] != 0 && $_POST['rebuildThumb'] == 1) {
		// Rebuild all archived pictures
		$result = db_query("SELECT PIC_ID FROM {$db_p}oekakidta WHERE archive='1'");

		while ($row = db_fetch_row($result)) {
			fix_thumbnail($row[0]);
		}
	} elseif ($_POST['use_thumb'] != 0 && $_POST['rebuildThumb'] == 2) {
		// Rebuild all thumbs
		$result = db_query("SELECT PIC_ID FROM {$db_p}oekakidta");

		while ($row = db_fetch_row($result)) {
			if ((get_microtime() - $start_time) > 250)
				break;

			fix_thumbnail($row[0]);
		}
	} elseif ($_POST['use_thumb'] != $cfg['use_thumb'] || $_POST['thumb_t'] != $cfg['thumb_t'] || $_POST['thumb_r'] != $cfg['thumb_r']) {
		// Options changed -- rebuild last {$_POST['pic_pages']} pictures
		$number = w_gpc('pic_pages', 'i');
		$result = db_query("SELECT PIC_ID FROM {$db_p}oekakidta ORDER BY postdate DESC LIMIT 0, {$number}");
		$num_rows = db_num_rows($result);

		for ($i = 0; $i < $num_rows; $i++) {
			fix_thumbnail(db_result($result, $i));
		}
	}

	all_done();
}


include 'header.php';

?>
<div id="contentmain">
	<form name="form1" method="post" action="cpanel.php">
		<input type="hidden" name="action" value="submit" />

		<h1 class="header">
			<?php tt('install_dispreg');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_opurl');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="op_url" type="text" value="<?php echo $cfg['op_url']?>" size="40" class="txtinput" />
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_email');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="op_email" type="text" value="<?php echo $cfg['op_email']?>" size="40" class="txtinput" />

				<p class="subtext">
					<?php tt('install_emailsub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_send_emails'); ?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="cut_email" class="multiline">
					<option value="0"<?php if ($cfg['cut_email']==0) echo ' selected="selected"';?>><?php tt('word_yes');?></option>
					<option value="1"<?php if ($cfg['cut_email']==1) echo ' selected="selected"';?>><?php tt('cpanel_emails_minimal');?></option>
					<option value="2"<?php if ($cfg['cut_email']==2) echo ' selected="selected"';?>><?php tt('word_no');?></option>
				</select>
				<p class="subtext">
					<?php tt('adjust_emails_sent_sub1'); ?> 
				</p>
				<p class="subtext">
					<?php tt('adjust_emails_sent_sub2', '75%'); ?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<h1 class="header">
			<?php tt('word_storage');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_picstore');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="pic_store" type="text" value="<?php echo $cfg['pic_store']?>" size="8" class="txtinput" />

				<p class="subtext">
					<input type="checkbox" name="pic_store_confirm" value="confirm" /> <?php tt('cpanel_check_box_confirm');?><br />
					<?php tt('cpmsg1');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('allowppicture');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="public_retouch" class="multiline">
<?php if ($cfg['public_retouch'] != 'no') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>
				<p class="subtext">
					<?php tt('ppmsgrtouch');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('allowsafesave');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="safety_saves" class="multiline">
<?php if ($cfg['safety_saves'] != 'no') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>
				<p class="subtext">
					<?php tt('safesaveexp');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('header_safety_max');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="safety_max" type="text" value="<?php echo $cfg['safety_max'];?>" size="8" class="txtinput" />

				<p class="subtext">
					<?php tt('safety_max_sub', '2');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('savestorage');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="safety_storetime" type="text" value="<?php echo $cfg['safety_storetime'];?>" size="8" class="txtinput" /> <?php tt('word_days');?>

				<p class="subtext">
					<?php tt('safetydays');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php //tt('cp_enable_late_pic');?> 
				Enable latest picture log
			</td>
			<td class="infoenter" valign="top">
				<select name="latest_pic_file" class="multiline">
<?php if (!isset($cfg['latest_pic_file']) || $cfg['latest_pic_file'] != 'yes') { ?>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
					<option value="yes"><?php tt('word_yes');?></option>
<?php } else { ?>
					<option value="no"><?php tt('word_no');?></option>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
<?php } ?>
				</select>
				<p class="subtext">
					<?php //tt('cp_late_pic_sub');?> 
					Writes files into the resource folder that may be included into blogs or web pages to track updates to the oekaki.
				</p>
			</td>
			</tr>
			</table>

			<input name="op_pre" type="hidden" value="<?php echo $cfg['op_pre']?>" />
		</div>
		<br />



		<h1 class="header">
			<?php tt('install_reg');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_rapproval');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="approval" class="multiline">
<?php if ($cfg['approval'] == 'yes') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
					<option value="force"><?php tt('forceactivate');?></option>
<?php } elseif ($cfg['approval'] == 'no') { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
					<option value="force"><?php tt('forceactivate');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
					<option value="force" selected="selected"><?php tt('forceactivate');?></option>
<?php } ?>
				</select>

				<p class="subtext">
					<?php tt('activateyes');?><br />
					<?php tt('activeno');?><br />
					<strong><?php tt('activateforced');?></strong>
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('defaultpermis');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="reg_draw" type="checkbox" value="D" <?php if ($cfg['reg_draw'] == 'yes') {echo 'checked="checked" ';} ?>/><?php tt('type_daccess');?><br />
				<input name="reg_anim" type="checkbox" value="A" <?php if ($cfg['reg_anim'] == 'yes') {echo 'checked="checked" ';} ?>/><?php tt('type_aaccess');?><br />
				<input name="reg_upload" type="checkbox" value="U" <?php if ($cfg['reg_upload'] == 'yes') {echo 'checked="checked" ';} ?>/><?php tt('type_uaccess');?>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Require Art -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('requireartsub');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="require_art" class="multiline">
<?php if ($cfg['require_art'] == 'yes') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>

				<p class="subtext">
					<?php tt('requireartsubyes');?><br />
					<?php tt('requireartsubno');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Private oekaki -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('header_private_oekaki');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="private" class="multiline">
<?php if ($cfg['private'] != 'no') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>

				<p class="subtext">
					<?php tt('private_oekaki_sub'); ?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- User kill -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_adel');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="kill_user" type="text" value="<?php echo $cfg['kill_user']?>" size="5" class="txtinput" />
				<?php tt('install_adelsub2');?> 

				<p class="subtext">
					<?php tt('install_adelsub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('autoimune');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="draw_immune" class="multiline">
<?php if ($cfg['draw_immune'] != 'no') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>

				<p class="subtext">
					<?php tt('autoimune_exp');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('showrulereg');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="reg_rules" class="multiline">
<?php if ($cfg['reg_rules'] == 'yes') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>

				<p class="subtext">
					<?php tt('showruleregexp');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_gallow');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="guest_post" class="multiline">
<?php if ($cfg['guest_post'] == 'yes') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>

				<p class="subtext">
					<?php tt('install_gallowsub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Humanity test -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('cpanel_humanity_infoask');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="humanity_post" class="multiline">
<?php if ($cfg['humanity_post'] == 'yes') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>

				<p class="subtext">
					<?php tt('cpanel_humanity_sub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Self Bump -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('bumpretouch');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="self_bump" class="multiline">
<?php if ($cfg['self_bump'] != 'no') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<h1 class="header">
			<?php tt('install_dispgen');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">


			<!-- BBS Title -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_title2');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="op_title" type="text" value="<?php echo $cfg['op_title'];?>" size="40" class="txtinput" />

				<p class="subtext">
					<?php tt('install_title2sub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- BBS Author -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('authorname');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="op_author" type="text" value="<?php echo $cfg['op_author']?>" size="40" class="txtinput" />

				<p class="subtext">
					<?php tt('bbsowner');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('adultrbbs', MIN_AGE_ADULT);?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="op_adult" class="multiline">
<?php if ($cfg['op_adult'] == 'yes') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>
				<br />

				<p class="subtext">
					<?php tt('adultrbbsdesc', MIN_AGE_ADULT);?><br />
					<?php tt('adultrbbsnote', MIN_AGE_ADULT);?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('cpanel_use_lightbox');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="use_viewer" class="multiline">
<?php if ($cfg['use_viewer'] == '1') { ?>
					<option value="1" selected="selected"><?php tt('word_yes');?></option>
					<option value="0"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="1"><?php tt('word_yes');?></option>
					<option value="0" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>

				<p class="subtext">
					<?php tt('cpanel_lightbox_sub');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('allowpron', MIN_AGE_ADULT);?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="guest_adult" class="multiline">
<?php if ($cfg['guest_adult'] == 'yes') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>

				<p class="subtext">
					<?php tt('allowpronyes', MIN_AGE_ADULT);?><br />
					<?php tt('allowpronno', MIN_AGE_ADULT);?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Default Language -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('cpanel_deflang');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="language" id="select" class="multiline">
<?php

if (empty($current_user['language'])) {
	$current_user['language'] = $cfg['language'];
}

$lang_names = array();
if ($handle = opendir('language')) {
	while (false !== ($file = readdir($handle))) {
		$name = substr($file, 0, -4);
		if (substr($file, -4) == '.php' && $file != 'index.php') {
			$lang_names[] = $name;
		}
	}
	closedir($handle);
}
sort($lang_names);

foreach ($lang_names as $name) {
	$name2 = $name;
	if (isset($lang['names'][$name])) {
		$name2 = $lang['names'][$name];
	}
	if ($name == $language) {
		echo '					<option value="'.$name.'" selected="selected">'.$name2.'</option>'."\n";
	} else {
		echo '					<option value="'.$name.'">'.$name2.'</option>'."\n";
	}
}
?>
				</select>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Default Template -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_deftem');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="template" class="multiline">
<?php if ($handle = opendir('templates')) {
	while (false !== ($file = readdir($handle))) {
		if (substr($file, -4) == '.php' && $file != 'index.php') {
			if (substr($file, 0, -4) == $cfg['template']) { ?>
					<option value="<?php echo substr($file, 0, -4)?>" selected="selected"><?php echo substr($file, 0, -4)?></option>
<?php
			} else {
?>
					<option value="<?php echo substr($file, 0, -4)?>"><?php echo substr($file, 0, -4)?></option>
<?php
			}
		}
	}
	closedir($handle);
} ?>
				</select>

				<p class="subtext">
					<?php tt('install_deftemsub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Reset templates -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_resettemplate');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="t_reset" class="multiline">
					<option value="no" selected="selected"><?php tt('word_no');?></option>
					<option value="yes"><?php tt('word_yes');?></option>
				</select>

				<p class="subtext">
					<?php tt('install_resettemplatesub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Pictures per index page -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('maxpiconind');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="pic_pages" type="text" value="<?php echo $cfg['pic_pages']?>" size="8" class="txtinput" />

				<p class="subtext">
					<?php tt('maxpicinddesc');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Pictures per other page -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('menuentries');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="menu_pages" type="text" value="<?php echo $cfg['menu_pages']?>" size="8" class="txtinput" />

				<p class="subtext">
					<?php tt('menuentriesdesc');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Smilies -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('usesmilies');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="smilies" class="multiline">
<?php if ($cfg['smilies'] != 'no') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>
				<p class="subtext">
					<?php tt('usesmiliedesc');?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Avatars -->
		<h1 class="header">
			<?php tt('word_avatars');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<!-- Enable -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('enableavata');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="use_avatars" class="multiline">
<?php if ($cfg['use_avatars'] != 'no') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('allowavatar');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="use_c_avatars" class="multiline">
<?php if ($cfg['use_c_avatars'] != 'yes') { ?>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
					<option value="yes"><?php tt('word_yes');?></option>
<?php } else { ?>
					<option value="no"><?php tt('word_no');?></option>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
<?php } ?>
				</select>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('AvatarStore');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="avatar_folder" type="text" class="txtinput" size="60" value="<?php echo $cfg['avatar_folder'].'/';?>" />

				<p class="subtext">
					<?php tt('changemulti');?><br />
					<?php tt('cpmsg2');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<tr>
			<td class="infoask" valign="top">
				<?php tt('maxavatar');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="avatar_x" type="text" value="<?php echo $cfg['avatar_x']?>" size="6" class="txtinput" />
				&times;
				<input name="avatar_y" type="text" value="<?php echo $cfg['avatar_y']?>" size="6" class="txtinput" />

				<p class="subtext">
					<?php tt('maxavatardesc');?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Applets -->
		<h1 class="header">
			<?php tt('install_appletset');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<!-- Canvas Defaults Width -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('cavasize');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="def_x" type="text" value="<?php echo $cfg['def_x']?>" size="6" class="txtinput" />
				&times;
				<input name="def_y" type="text" value="<?php echo $cfg['def_y']?>" size="6" class="txtinput" />

				<p class="subtext">
					<?php tt('defcanvasize');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Canvas Min -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('mincanvasize');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="min_x" type="text" value="<?php echo $cfg['min_x']?>" size="6" class="txtinput" />
				&times;
				<input name="min_y" type="text" value="<?php echo $cfg['min_y']?>" size="6" class="txtinput" />

				<p class="subtext">
					<?php tt('mincanvasizedesc');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Canvas Max -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('maxcanvasize');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="canvas_x" type="text" value="<?php echo $cfg['canvas_x']?>" size="6" class="txtinput" />
				&times;
				<input name="canvas_y" type="text" value="<?php echo $cfg['canvas_y']?>" size="6" class="txtinput" />

				<p class="subtext">
					<?php tt('maxcanvasizedesc');?>.<br />
					<?php tt('maxcanvasizedesc2');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Max Anim -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_animax');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="max_anim" type="text" value="<?php echo $cfg['max_anim']?>" size="40" class="txtinput" />
				<?php tt('install_bytes');?> 

				<p class="subtext">
					<?php tt('install_animaxsub');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Max Upload -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('maxfilesize');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="max_pic" type="text" value="<?php echo $cfg['max_pic']?>" size="40" class="txtinput" />
				<?php tt('install_bytes');?> 

				<p class="subtext">
					<?php tt('maxupfileexp');?><br />
					<?php tt('maxupfileexp2');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">
				&nbsp;
				<input name="canvas_a" type="hidden" value="<?php echo $cfg['canvas_a']?>" />
			</td></tr>


			<!-- Preview Image-->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('canvasprev');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="preview_img" type="text" value="<?php echo $cfg['preview_img']?>" size="40" class="txtinput" />

				<p class="subtext">
					<?php tt('cpmsg3');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Preview Title -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('pviewtitle');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="preview_title" type="text" value="<?php echo $cfg['preview_title']?>" size="40" class="txtinput" />

				<p class="subtext">
					<?php tt('titleprevwi');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Pr0n Image -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('pron');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="xxx_img" type="text" value="<?php echo $cfg['porn_img']?>" size="40" class="txtinput" />

				<p class="subtext">
					<?php tt('prondesc');?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Mailbox -->
		<h1 class="header">
			<?php tt('install_mailbox');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">

			<!-- Enable Mailbox -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('enable_mailbox');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="use_mailbox" class="multiline">
<?php if ($cfg['use_mailbox'] == 'yes') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Chat -->
		<h1 class="header">
			<?php tt('install_dispchat');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">

			<!-- Enable Chat -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('enablechat');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="use_chat" class="multiline">
<?php if ($cfg['use_chat'] == 'yes') { ?>
					<option value="no"><?php tt('chat_no_set_reccom');?></option>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
<?php } else { ?>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
					<option value="yes"><?php tt('word_yes');?></option>
<?php } ?>
				</select>

				<p class="subtext">
					<?php tt('chatnote');?> 
				</p>
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Chat Total Lines -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_displinesmax');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="chat_max" type="text" value="<?php echo $cfg['chat_max']?>" size="8" class="txtinput" />
			</td>
			</tr>

			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Chat Lines Per Page -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_displines');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="chat_pages" type="text" value="<?php echo $cfg['chat_pages']?>" size="8" class="txtinput" />
			</td>
			</tr>

			</table>
		</div>
		<br />



		<!-- Compression Settings -->
		<h1 class="header">
			<?php tt('compress_title');?> 
		</h1>

		<div class="infotable">
			<p class="infonote">
<?php if (!extension_loaded('gd')) { ?>
				<?php tt('err_nogdlib');?><br />
				<br />
<?php } ?>
				<?php tt('thumbmodes');?><br />
				<br />
				<?php tt('thumbmodesexp');?><br />
				<br />
				<?php tt('thumbmodesexp2');?> 
			</p>

			<table class="infomain">
			<!-- Layout mode -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('defthumbmode');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="use_thumb" class="multiline">
					<option value="0"<?php if ($cfg['use_thumb']==0) echo ' selected="selected"';?>><?php tt('cp_no_thumbs');?></option>
					<option value="1"<?php if ($cfg['use_thumb']==1) echo ' selected="selected"';?>><?php tt('word_layout');?></option>
					<option value="2"<?php if ($cfg['use_thumb']==2) echo ' selected="selected"';?>><?php tt('word_defscale');?></option>
					<option value="3"<?php if ($cfg['use_thumb']==3) echo ' selected="selected"';?>><?php tt('word_uniformity');?></option>
				</select>

				<p class="subtext">
					<?php tt('optiontip');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Force Layout -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('forcedefthumb');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="force_thumb" class="multiline">
<?php if ($cfg['force_thumb'] == 'yes') { ?>
					<option value="yes" selected="selected"><?php tt('word_yes');?></option>
					<option value="no"><?php tt('word_no');?></option>
<?php } else { ?>
					<option value="yes"><?php tt('word_yes');?></option>
					<option value="no" selected="selected"><?php tt('word_no');?></option>
<?php } ?>
				</select>

				<p class="subtext">
					<?php tt('forcethumbdesc');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Small Thumbnail Size -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('smallthumb');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="thumb_t" type="text" value="<?php echo $cfg['thumb_t']?>" size="8" class="txtinput" />

				<p class="subtext">
					<?php tt('smallthumbdesc');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Large Thumbnail Size -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('largethumb');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="thumb_r" type="text" value="<?php echo $cfg['thumb_r']?>" size="8" class="txtinput" />

				<p class="subtext">
					<?php tt('largethumbdesc');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Thumbnail Bytes -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('thumbnailfilesize');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="thumb_bytes" type="text" value="<?php echo $cfg['thumb_bytes']?>" size="10" class="txtinput" />
				<?php tt('install_bytes');?> 

				<p class="subtext">
					<?php tt('thumbsizedesc');?> 
				</p>
			</td>
			</tr>


			<tr><td colspan="2">&nbsp;</td></tr>


			<!-- Thumbnail Rebuild -->
			<tr>
			<td class="infoask" valign="top">
				<?php tt('rebuthumb');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="rebuildThumb" class="multiline">
					<option value="0" selected="selected"><?php tt('pgone');?></option>
					<option value="1"><?php tt('archipon');?></option>
					<option value="2"><?php tt('allthumb');?></option>
				</select>

				<p class="subtext">
					<?php tt('rebuthumbnote');?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Submit Changes -->
		<h1 class="header">
			<?php tt('submitchange');?> 
		</h1>

		<div class="infotable">
			<p style="text-align: center">
				<input type="submit" name="Submit" value="<?php tt('word_submit');?>" class="submit" />
			</p>
		</div>
	</form>
</div>


<?php

include 'footer.php';