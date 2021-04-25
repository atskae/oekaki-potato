<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2013 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.14 - Last modified 2013-06-04
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


// No guests!
if (empty($OekakiU)) {
	report_err( t('err_edprof'));
}


// Admin editing someone else's profile?
$username = w_gpc('username');
if (empty($username)) {
	$username = $OekakiU;
}
if ($username != $OekakiU && $flags['admin']) {
	// Admin edit other profile
	$result = db_query("SELECT * FROM {$db_mem}oekaki WHERE usrname='$username'");
	if ($result) {
		if (db_num_rows($result)) {
			$current_user = db_fetch_array($result);
		} else {
			report_err('functions_err4');
		}
	} else {
		// It's serious to have SQL bomb here
		w_log_sql('editprofile:'.__LINE__);
		report_err('db_err');
	}
} else {
	// Edit own profile
	$username = $OekakiU;
	$current_user = $user; // Array
}

// Now we have $current_user.  Don't forget the flags!
$current_flags = parse_flags($current_user['usrflags'], $current_user['rank']);


// Fix url
if (trim($current_user['url'] == '')) {
	$current_user['url'] = 'http://';
}


include 'header.php';


?>
<div id="contentmain">
	<form name="form1" method="post" action="functions.php">
		<input name="action" type="hidden" value="editprofile" />

		<!-- Edit Profile-->
		<h1 class="header">
			<?php tt('eprofile_title');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('realnameopt');?> 
			</td>
			<td class="infoenter" valign="top">
				<input id="name" name="name" type="text" value="<?php echo w_html_chars($current_user['name']);?>" class="txtinput" size="40" onblur="checkName('name');" />

				<p class="subtext">
					<?php tt('realname');?> 
				</p>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_email');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="email" type="text" value="<?php echo w_html_chars($current_user['email']);?>" class="txtinput" size="40" onblur="MM_validateForm('email','','RisEmail');return document.MM_returnValue" />

				<p class="subtext">
					<?php tt('msg_regmail');?> 
				</p>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('email_show');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="email_show" class="multiline">
					<option value="1"<?php if ($current_user['email_show']=='1') echo ' selected="selected"';?>><?php tt('word_yes');?></option>
					<option value="0"<?php if ($current_user['email_show']=='0') echo ' selected="selected"';?>><?php tt('word_no');?></option>
				</select>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_birthday');?> 
			</td>
			<td class="infoenter" valign="top">
<?php
				$the_date = $current_user['age'];
				$year = ''; $month = ''; $day = '';

				if (strpos($the_date, '-') !== false) {
					list ($year, $month, $day) = explode('-', $the_date);
					if ($day == '??') $day = '';
					if ($month == '??') $month = '';
				}

for ($i = 0; $i < 3; $i++) {
	if ($datef['age_menu'][$i] == 'D') {
?>
				<?php tt('abbr_day');?> <input name="age_day" type="text" value="<?php echo $day;?>" size="3" class="txtinput" />

<?php
	} elseif ($datef['age_menu'][$i] == 'Y') {
?>
				<?php tt('abbr_year');?> <input name="age_year" type="text" value="<?php echo $year;?>" size="6" class="txtinput" />

<?php
	} else {
?>
				<?php tt('abbr_month');?> 
				<select name="age_month" class="multiline">
					<option value="0">---</option>
					<option value="1"<?php if ($month == '01') echo ' selected="selected"'; ?>><?php tt('month_jan');?></option>
					<option value="2"<?php if ($month == '02') echo ' selected="selected"'; ?>><?php tt('month_feb');?></option>
					<option value="3"<?php if ($month == '03') echo ' selected="selected"'; ?>><?php tt('month_mar');?></option>
					<option value="4"<?php if ($month == '04') echo ' selected="selected"'; ?>><?php tt('month_apr');?></option>
					<option value="5"<?php if ($month == '05') echo ' selected="selected"'; ?>><?php tt('month_may');?></option>
					<option value="6"<?php if ($month == '06') echo ' selected="selected"'; ?>><?php tt('month_jun');?></option>
					<option value="7"<?php if ($month == '07') echo ' selected="selected"'; ?>><?php tt('month_jul');?></option>
					<option value="8"<?php if ($month == '08') echo ' selected="selected"'; ?>><?php tt('month_aug');?></option>
					<option value="9"<?php if ($month == '09') echo ' selected="selected"'; ?>><?php tt('month_sep');?></option>
					<option value="10"<?php if ($month == '10') echo ' selected="selected"'; ?>><?php tt('month_oct');?></option>
					<option value="11"<?php if ($month == '11') echo ' selected="selected"'; ?>><?php tt('month_nov');?></option>
					<option value="12"<?php if ($month == '12') echo ' selected="selected"'; ?>><?php tt('month_dec');?></option>
				</select>

<?php
	}
}
?>

				<p class="subtext">
					<?php tt('bdaysavmg');?> 
				</p>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_gender');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="gender" id="gender" class="multiline">
					<option><?php tt('word_na');?></option>
					<option value="Male"<?php if (strtolower($current_user['gender']) == 'male') echo ' selected="selected"';?>><?php tt('word_male');?></option>
					<option value="Female"<?php if (strtolower($current_user['gender']) == 'female') echo ' selected="selected"';?>><?php tt('word_female');?></option>
				</select>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_location');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="location" type="text" value="<?php echo w_html_chars($current_user['location']);?>" class="txtinput" size="40" />
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_website');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="url" type="text" value="<?php echo w_html_chars($current_user['url']);?>" class="txtinput" size="40" />

				<p class="subtext">
					<?php tt('common_http');?> 
				</p>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('websitetitle');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="urltitle" type="text" value="<?php echo w_html_chars($current_user['urltitle']);?>" class="txtinput" size="40" />

				<p class="subtext">
					<?php tt('editprofmsg2');?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />

<?php if ($cfg['use_avatars'] == 'yes') { ?>

		<!-- Avatar -->
		<h1 class="header">
			<?php tt('word_avatar');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('curavatar');?><br />
			</td>
			<td class="infoenter" valign="top">
<?php if ($current_user['avatar']) { ?>
				<img src="<?php echo $cfg['avatar_folder'].'/'.$current_user['avatar'];?>" alt="avatar" />
<?php } else { ?>
				<?php tt('avatar_none');?> 
<?php } ?>
				<br />
				<br />
				<input name="avatar" type="button" value="<?php tt('chgavatar');?>" class="submit" onclick="openWindow('edit_avatar.php?username=<?php echo urlencode($username);?>', 500, 400); return false;" />
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>
			</table>
		</div>
		<br />

<?php } ?>

		<!-- Chat Info -->
		<h1 class="header">
			<?php tt('onlineprese');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_aolinstantmessenger');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="aim" type="text" value="<?php echo w_html_chars($current_user['aim']);?>" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_icq');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="icq" type="text" value="<?php echo w_html_chars($current_user['icq']);?>" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_microsoftmessenger');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="msn" type="text" value="<?php echo w_html_chars($current_user['MSN']);?>" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_yahoomessenger');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="yahoo" type="text" value="<?php echo w_html_chars($current_user['Yahoo']);?>" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_ircserver');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="ircserver" type="text" value="<?php echo w_html_chars($current_user['IRCServer']);?>" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_ircnickname');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="ircnick" type="text" value="<?php echo w_html_chars($current_user['IRCNick']);?>" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_ircchannel');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="ircchan" type="text" class="txtinput" value="<?php echo w_html_chars($current_user['IRCchan']);?>" />
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Language -->
		<h1 class="header">
			<?php tt('eprofile_langset');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_language');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="language2" id="select" class="multiline">
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

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_translator');?> 
			</td>
			<td class="infoenter" valign="top">
				<?php tt('cfg_translator');?> 
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_comments');?> 
			</td>
			<td class="infoenter" valign="top">
				<?php tt('cfg_comments');?> 
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Template -->
		<h1 class="header">
			<?php tt('word_template');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('eprofile_curtemp');?> 
			</td>
			<td class="infoenter" valign="top">
				<?php echo $template_name; //(From header.php)?> 
			</td>
			</tr>

			<tr><td>&nbsp;</td></tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('eprofile_curtempd');?> 
			</td>
			<td class="infoenter" valign="top">
<?php
	// Get template info
	$tcontrol = 'parse';
	include $template_file.'.php';

	if ($tnotes) {
		echo nifty2_convert($tnotes);
	} else {
		echo '(No details)';
	}
?>

			</td>
			</tr>

			<tr><td>&nbsp;</td></tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('eprofile_templsel');?> 
			</td>
			<td class="infoenter" valign="top">
<?php
// Count templates.  If only one, don't use selector.
$name = '';
$dir_names = array();
$dir_names_count = 0;
if ($handle = opendir('templates')) {
	while (false !== ($file = readdir($handle))) {
		$name = substr($file, 0, -4);
		if (substr($file, -4) == '.php') {
			$dir_names[] = $name;
			$dir_names_count++;
		}
	}
}
closedir($handle);

if ($dir_names_count < 2) {
	echo "\t\t\t\t\t".t('default_only')."\n";
} else {
?>
				<select name="ctemplate" id="ctemplate" class="multiline">
					<option value=""<?php if (empty($current_user['templatesel'])) echo ' selected="selected"';?>><?php tt('template_default');?></option>
<?php
	for ($i = 0; $i < $dir_names_count; $i++) {
		if ($dir_names[$i] == $current_user['templatesel']) {
?>
					<option value="<?php echo $dir_names[$i];?>" selected="selected"><?php echo $dir_names[$i];?></option>
<?php
		} else {
?>
					<option value="<?php echo $dir_names[$i];?>"><?php echo $dir_names[$i];?></option>
<?php
		}
	}
?>
					</select>
<?php
}
?>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Prefs -->
		<h1 class="header">
			<?php tt('eprofile_compref');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_comments');?> 
			</td>
			<td class="infoenter" valign="top">
				<textarea name="comment" cols="40" rows="5" class="multiline"><?php echo w_html_chars($current_user['comment']);?></textarea>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('profile_use_lightbox');?> 
			</td>
			<td class="infoenter" valign="top">
<?php if ($cfg['use_viewer'] == '1') { ?>
				<select name="no_viewer" class="multiline">
					<option value="0"<?php if ($current_user['no_viewer']=='0') echo ' selected="selected"';?>><?php tt('word_yes');?></option>
					<option value="1"<?php if ($current_user['no_viewer']=='1') echo ' selected="selected"';?>><?php tt('word_no');?></option>
				</select>

				<p class="subtext">
					<?php tt('profile_lightbox_sub');?> 
				</p>
<?php } else { ?>
				<input name="no_viewer" type="hidden" value="0" />
				<?php tt('msg_cantchange');?> 
<?php } ?>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
<?php
if ($current_user['smilies_show']=='1') {
	if (file_exists('./smilies/smile.gif')) {
		echo '				<img src="./smilies/smile.gif" alt=":)" />'."\n";
	}
} else {
	if (file_exists('./smilies/sad.gif')) {
		echo '				<img src="./smilies/sad.gif" alt=":(" />'."\n";
	}
}
?>
				<?php tt('smilies_show');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="smilies_show" class="multiline">
					<option value="1"<?php if ($current_user['smilies_show']=='1') echo ' selected="selected"';?>><?php tt('word_yes');?></option>
					<option value="0"<?php if ($current_user['smilies_show']=='0') echo ' selected="selected"';?>><?php tt('word_no');?></option>
				</select>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('eprofile_picview');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="picview" id="select2" class="multiline">
					<option value="0"<?php if ($current_user['picview']=='0') echo ' selected="selected"';?>><?php tt('picview_automatic');?></option>
					<option value="1"<?php if ($current_user['picview']=='1') echo ' selected="selected"';?>><?php tt('picview_horizontal');?></option>
					<option value="2"<?php if ($current_user['picview']=='2') echo ' selected="selected"';?>><?php tt('picview_vertical');?></option>
				</select>

				<p class="subtext">
					<?php tt('msg_automatic');?> 
				</p>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

<?php if ($cfg['force_thumb'] != 'yes') { ?>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('thumbmode');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="thumbview" class="multiline">
					<option value="0"<?php if ($current_user['thumbview']=='0') echo ' selected="selected"';?>><?php tt('word_default');?></option>
					<option value="1"<?php if ($current_user['thumbview']=='1') echo ' selected="selected"';?>><?php tt('word_layout');?></option>
					<option value="2"<?php if ($current_user['thumbview']=='2') echo ' selected="selected"';?>><?php tt('word_scaled');?></option>
					<option value="3"<?php if ($current_user['thumbview']=='3') echo ' selected="selected"';?>><?php tt('word_uniformity');?></option>
				</select>

				<p class="subtext">
					<?php tt('msgdefrec');?> 
				</p>
			</td>
			</tr>
<?php } else { ?>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('thumbmode');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="thumbview" type="hidden" value="0" />
				<?php tt('msg_cantchange');?> 
			</td>
			</tr>
<?php } ?>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('screensize');?> 
			</td>
			<td class="infoenter" valign="top">
				<select name="screensize" class="multiline">
					<option value="640"<?php if ($current_user['screensize']==640) echo ' selected="selected"';?>>640&times;480</option>
					<option value="800"<?php if ($current_user['screensize']==800) echo ' selected="selected"';?>>800&times;600</option>
					<option value="1024"<?php if ($current_user['screensize']==1024) echo ' selected="selected"';?>>1024&times;768</option>
					<option value="1280"<?php if ($current_user['screensize']==1280) echo ' selected="selected"';?>><?php tt('orhigher', '1280&times;1024');?></option>
				</select>

				<p class="subtext">
					<?php tt('screensizemsg');?> 
				</p>
			</td>
			</tr>

			<tr>
			<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('eprofile_adult', MIN_AGE_ADULT);?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="adult" type="checkbox" value="X"<?php if ($current_flags['X']) echo ' checked="checked"';?> /><?php tt('word_enable');?> 

				<p class="subtext">
					<?php tt('eprofile_adultsub', MIN_AGE_ADULT);?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Change Pass -->
		<h1 class="header">
			<?php tt('eprofile_chngpass');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">

			<!-- Fix Firefox autocomplete bug w/IRC Channel field -->
			<tr style="display: none !important">
			<td class="infoask" valign="top">
				<?php tt('word_username');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="firefox_fix" type="text" value="<?php w_html_chars($OekakiU);?>" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('eprofile_oldpass');?> 
			</td>
			<td class="infoenter" valign="top">
				<input id="oldpass" name="oldpass" type="password" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('chngpass_newpwd');?> 
			</td>
			<td class="infoenter" valign="top">
				<input id="passwd" name="passwd" type="password" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('eprofile_repass');?> 
			</td>
			<td class="infoenter" valign="top">
				<input id="passwdnew" name="passwdnew" type="password" class="txtinput" onblur="checkPass('passwd', 'passwdnew');" />

				<p class="subtext">
					<?php tt('eprofile_pdisc');?> 
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Submit -->
		<h1 class="header">
			<?php tt('submitchange');?> 
		</h1>

		<div class="infotable">
			<p style="text-align: center;">
				<input name="username2" type="hidden" value="<?php echo w_html_chars($username); ?>" />

				<input name="eprofile" type="submit" class="submit" onclick="MM_validateForm('email','','RisEmail'); return document.MM_returnValue" value="<?php tt('word_edit');?>" />
			</p>
		</div>
	</form>
</div>


<?php

include 'footer.php';