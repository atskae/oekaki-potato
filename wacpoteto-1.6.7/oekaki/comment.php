<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2018 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.7 - Last modified 2018-10-26
*/


/*
	--------
	INPUTS:

	$GET_['mode']
		'res_msg' = pic, 'add' = comment.
	$_GET['resno']
		Add comment to this PIC_ID.
	$_GET['edit']
		Retouched picture.
	!$_GET['resno'] && !$_POST['edit']
		Picture is a new post.  Compute $resno.
	$_GET['import']
		Source picture from retouch.  Get info.

	------
	VARS:

	$pub === TRUE
		Picture is public.
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

// Input
$guestName  = w_gpc('guestName');
$guestEmail = w_gpc('guestEmail', 'email');
$guestURL   = w_gpc('guestURL');
$resno  = w_gpc('resno', 'i+');
$edit   = w_gpc('edit', 'i+');
$import = w_gpc('import', 'i+');
$pw     = w_gpc('pw');
// $pageno
// $sort
// $artist
// $a_match


// Init
if (empty($guestURL)) {
	$guestURL = 'http://';
}

// No guests?
if (empty($OekakiU) && $cfg['guest_post'] != 'yes') {
	all_done();
}

// No mode?
if (empty($mode)) {
	all_done();
}

// Yet another smilies hack
{
	// Disable
	$breakpoint = 0;

	// Break after "n" smilies are printed
	// $breakpoint = 3;

	// Break into "n" number of lines
	// $breakpoint = ceil($q / 2);
}


// ----------
// WIP notice
//
if (w_gpc('wipok')) {
	include 'header.php';

	?>
<div id="contentmain">

	<!-- WIP saved -->
	<h1 class="header">
		<?php tt('safetysave');?> 
	</h1>
	<div class="infotable" style="text-align: center;">
		<?php tt('safesavemsg3');?><br />
		<br />
		<?php tt('safesavemsg2', $cfg['safety_max'], $cfg['safety_storetime']);?><br />
		<br />
		<?php tt('safesavemsg5', $cfg['safety_storetime']);?><br />
		<br />
		<a href="index.php"><?php tt('returnbbs');?></a>
	</div>
</div>


<?php
	include 'footer.php';
	w_exit();
}


// Check for retouch
$pub = false;
if ($import) {
	// Retouch of some sort.  We'll need info from the original.

	if ($edit) {
		// Edit.
		$resno = $edit;
	}

	$result = db_query("SELECT usrname, title, adult, comment, postlock, password, origart FROM {$db_p}oekakidta WHERE PIC_ID='$import'");
	if ($result && db_num_rows($result) > 0) {
		$import_info = db_fetch_array($result);
	} else {
		report_err( t('err_readpic', $import));
	}

	// Is the picture public?
	if ($import_info['password'] == 'public') {
		$pub = true;
	}
}
if (!$resno) {
	// If no resource set yet, get one!
	$result = db_query("SELECT PIC_ID FROM {$db_p}oekakidta WHERE usrname='$OekakiU' ORDER BY postdate DESC");

	if ($result && db_num_rows($result) > 0) {
		$resno = (int) db_result($result, 0);
	} else {
		report_err( t('err_lookrecpic'));
	}
}


// Get resno
$result = db_query("SELECT ID_2, usrname, title, ptype, rtype, ttype, adult, usethumb, px, password FROM {$db_p}oekakidta WHERE PIC_ID='$resno'");
if (!$result || db_num_rows($result) < 1) {
	report_err('Unable to read picture #'.$resno);
}
$row = db_fetch_array($result);

// Check ownership
if ($mode == 'res_msg' && !$flags['owner'] && $OekakiU != $row['usrname'] && empty($row['password'])) {
	report_err( t('err_editpic'));
}

include 'header.php';


// --------------
// Normal comment
//
?>
<div id="contentmain">
	<div class="infotable">
<?php

if ($row['px'] < $user['screensize']) {
	if (THUMB_COMMENT != 1) {
		// hacks.php
		$row['usethumb'] = 0;
	}
}

$master_image = $p_pic.$resno.'.'.$row['ptype'];
if (!empty($row['rtype']) && $row['usethumb'] == 1) {
	$thumbnail_image = $r_pic.$resno.'.'.$row['rtype'];
	$img_class_type = 'imgthumb';
} elseif (!empty($row['ttype']) && $row['usethumb'] == 1) {
	$thumbnail_image = $t_pic.$resno.'.'.$row['ttype'];
	$img_class_type = 'imgthumb';
} else {
	$thumbnail_image = $master_image;
	$img_class_type = 'imghover';
}

// Insert the picture + thumbnail
$adult_block_mode = 0;
if (!$flags['X'] && $row['adult'] == 1) {
	if ($cfg['guest_adult'] == 'yes' || ($flags['mod'])) {
		// block image only (worksafe)
		$adult_block_mode = 1;
	} else {
		// block image and link
		$adult_block_mode = 2;
	}
}

?>
	<p style="text-align: center;">
<?php
if ($adult_block_mode == 0) {
echo <<<EOF
		<a href="$master_image"><img src="$thumbnail_image" class="$img_class_type" alt="Pic #{$row['ID_2']}" title="Pic #{$row['ID_2']}" /></a><br />

EOF;
} elseif ($adult_block_mode == 1) {
echo <<<EOF
		<a href="$master_image"><img src="{$cfg['res_path']}/{$cfg['porn_img']}" class="$img_class_type" alt="Pic #{$row['ID_2']}" title="Pic #{$row['ID_2']}" /></a><br />

EOF;
} else {
echo <<<EOF
		<img src="{$cfg['res_path']}/{$cfg['porn_img']}" alt="Pic #{$row['ID_2']}" title="Pic #{$row['ID_2']}" /><br />

EOF;
}

if ($edit) {
?>
		<?php tt('refreshnote');?> 
<?php } ?>
	</p>

	<form name="formComment" method="post" action="functions.php">
		<input name="action" type="hidden" value="<?php echo $mode;?>" />
		<input name="picno" type="hidden" value="<?php echo $resno;?>" />
		<input name="pageno" type="hidden" value="<?php echo $pageno;?>" />
<?php if ($sort) { ?>
		<input name="sort" type="hidden" value="<?php echo $sort;?>" />
<?php }
if (!empty($artist)) { ?>
		<input name="artist" type="hidden" value="<?php echo w_html_chars($artist);?>" />
<?php }
if (!empty($a_match)) { ?>
		<input name="a_match" type="hidden" value="<?php echo w_html_chars($a_match);?>" />
<?php }

	if (empty($OekakiU)) {
		// Guest info
?>
		<h1 class="header">
			<?php tt('comment_add');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_name');?>
			</td>
			<td class="infoenter" valign="top">
				<input name="name" type="text" value="<?php echo w_html_chars($guestName);?>" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_email');?>
			</td>
			<td class="infoenter" valign="top">
				<input name="email" type="text" value="<?php echo w_html_chars($guestEmail);?>" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_url');?>
			</td>
			<td class="infoenter" valign="top">
				<input name="url" type="text" value="<?php echo w_html_chars($guestURL);?>" class="txtinput" />
				<?php tt('common_http');?> 
			</td>
			</tr>
<?php
		// Humanity test for guests
		if ($cfg['humanity_post'] != 'no') {
			if (isset($_COOKIE['guestPass']) && isset($_COOKIE['guestName'])
				&& w_transmission_verify($_COOKIE['guestName'], $_COOKIE['guestPass']))
			{
				// Cookie passed, skip test
			} else {
				// Cookie failed, print test
				$start = rand(1, 10);
				$end = rand(1, 10);
				$sum = $start + $end;

				$mixup = array($sum, $sum+2, $sum+4, $sum+6, $sum+8);
				shuffle($mixup);

				// Make sure correct value is not in 1st position, else swap with 3rd
				if ($mixup[0] == $sum) {
					$dummy    = $mixup[0];
					$mixup[0] = $mixup[3];
					$mixup[3] = $dummy;
				}

?>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('humanity_question_3_part', $start, '+', $end);?> 
			</td>
			<td class="infoenter" valign="top">
				<input type="hidden" name="x_test" value="<?php echo $start;?>" />
				<select name="e_test" class="txtinput">
<?php
$answer_flag = 0;
foreach ($mixup as $out) { ?>
					<option value="<?php echo $out;?>"<?php if ($out != $sum && $answer_flag == 0) { echo ' selected="selected"'; $answer_flag = 1;} ?>><?php echo $out;?></option>
<?php } ?>
				</select>
				<input type="hidden" name="y_test" value="<?php echo $end;?>" />
			</td>
			</tr>
<?php
			}
		}
?>
			</table>
		</div>
		<br />
<?php
	}

	if ($mode == 'res_msg') {
?>
		<h1 class="header">
			<?php tt('picprop');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask">
				<?php tt('install_title2');?> 
			</td>
			<td class="infoenter" valign="top">
<?php if ($edit) { ?>
				<input name="title" type="text" value="<?php echo w_html_chars($import_info['title']);?>" size="40" class="txtinput" />
<?php } else { ?>
				<input name="title" type="text" value="" size="40" class="txtinput" />
<?php } ?>
			</td>
			</tr>

			<tr>
			<td class="infoask">
				<?php tt('comment_adult', MIN_AGE_ADULT);?> 
			</td>
			<td class="infoenter">
				<select name="adult" class="multiline">
<?php if ($edit && $import_info['adult'] == 1) { ?>
					<option value="0"><?php tt('word_no');?></option>
					<option value="1" selected="selected"><?php tt('word_yes');?></option>
<?php } else { ?>
					<option value="0" selected="selected"><?php tt('word_no');?></option>
					<option value="1"><?php tt('word_yes');?></option>
<?php } ?>
				</select>
			</td>
			</tr>

			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

<?php if ($cfg['safety_saves'] == 'yes') { ?>
			<tr>
			<td class="infoask">
				<?php tt('safetysave');?> 
			</td>
			<td class="infoenter">
				<select name="wip" class="multiline">
					<option value="0" selected="selected"><?php tt('safesaveopt1');?></option>
					<option value="1"><?php tt('safesaveopt2');?></option>
				</select>

				<p class="subtext">
					<?php tt('sagesaveopt3', $cfg['safety_storetime']);?> 
				</p>
			</td>
			</tr>

			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>

<?php } ?>
<?php
if ($cfg['self_bump'] == 'yes' && $edit) {
	if (isset($import_info['postlock']) && $import_info['postlock']) {
		// Allow members to bump retouched pictures ?>
			<tr>
			<td class="infoask">
				<?php tt('bumppic');?> 
			</td>
			<td class="infoenter">
				<select name="bump" class="multiline">
					<option value="1" selected="selected"><?php tt('word_yes');?></option>
					<option value="0"><?php tt('word_no');?></option>
				</select>

				<p class="subtext">
					<?php tt('bumppicexp');?> 
				</p>
			</td>
			</tr>

<?php
	}
}
?>
			<tr>
			<td class="infoask">
				<?php tt('sharepic');?> 
			</td>
			<td class="infoenter">
				<select id="share" name="share" class="multiline" onchange="display_block_when('set_pass', 'share', 1);">
					<option value="0"<?php
						if (!$edit || empty($import_info['password']))
						echo ' selected="selected"';
						?>><?php tt('word_no');?></option>
					<option value="1"<?php
						if (!$pub && !empty($import_info['password']))
						echo ' selected="selected"';
						?>><?php tt('pwdprotect');?></option>
<?php if ($cfg['public_retouch'] == 'yes') { ?>
					<option value="2"<?php
						if ($pub)
						echo ' selected="selected"';
						?>><?php tt('picpublic');?></option>
<?php } ?>
				</select>

				<p id="set_pass" class="subtext" style="display: <?php if (!$pub && !empty($import_info['password'])) { echo 'block'; } else { echo 'none'; }?>">
<?php
	$print_pass = '';
	if (isset($import_info['usrname']) && $import_info['usrname'] == $OekakiU) {
		$print_pass = $import_info['password'];
	}

	if (!empty($import_info['password']) && $import_info['usrname'] != $OekakiU) {
		// Encrypted password needed from applet
		require 'paintsave.php';
		if (w_transmission_verify($import_info['password'], $pw)) {
			$print_pass = $import_info['password'];
?>
					<input name="old_pass_hash" type="hidden" value="<?php echo w_html_chars($pw);?>" />
<?php
		}
	}
?>
					<?php tt('install_password');?>  <input name="share_pass" type="text" value="<?php echo w_html_chars($print_pass);?>" size="12" class="txtinput" />
				</p>
			</td>
			</tr>

			</table>
		</div>
		<br />
<?php
	}

?>
		<h1 class="header">
			<?php tt('header_comments_niftytoo', 'niftyusage.php" onclick="openWindow(\'niftyusage.php\', 500, 300); return false;');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoenter" valign="top">
				<div style="text-align: center;">
<?php
// Print smilies
$q = count($smilies_group);
if (defined('MAX_SMILIES') && MAX_SMILIES < $q) {
	$q = MAX_SMILIES;
}

if ($cfg['smilies'] == 'yes' && $q > 0) {
	$i = 0;
	foreach($smilies_group as $s_code => $s_img) {
		if ($i >= $q) {
			break;
		}
		$i++;
?>
					<img onclick="insertText('comment', ' <?php echo $s_code;?> ');" onmouseover="if (style.cursor) style.cursor='hand';" src="./smilies/<?php echo $s_img;?>" alt="<?php echo $s_code;?>" />
<?php
		if ($breakpoint > 0) {
			if (($i % $breakpoint) == $breakpoint - 1) {
				echo "<br />\n";
			}
		}
	}
?>
					<br />
<?php } ?>


					<textarea id="comment" name="comment" cols="60" rows="8" class="multiline"><?php if ($edit) echo w_html_chars($import_info['comment']); ?></textarea>
					<br />
					<br />

					<input name="uts" type="hidden" value="<?php echo time(); ?>" />
					<input name="post" type="submit" value="<?php tt('word_submit');?>" class="submit" />
				</div>
			</td>
			</tr>
			</table>
		</div>
	</form>


<?php
// Comments.  Be careful with $edit variable!
if (!$edit) {
	// First comment
	$first_result = db_query("SELECT * FROM {$db_p}oekakidta WHERE PIC_ID='$resno'");
	$outerrow = db_fetch_array($first_result);

	// All other comments
	$result2 = db_query("SELECT * FROM {$db_p}oekakicmt WHERE PIC_ID='$resno' ORDER BY postdate ASC");
	$rownum2 = db_num_rows($result2);

?>
	<br />
	<br />

	<!-- Comments -->
	<div class="infotable">
		<div class="commentheader">
			<strong>
				<a onclick="openWindow('profile.php?user=<?php echo urlencode($outerrow['usrname']);?>', 400, 500); return false" href="profile.php?user=<?php echo urlencode($outerrow['usrname']);?>"><?php echo w_html_chars($outerrow['usrname']);?></a>
			</strong>
			<span class="commentinfo">@ <?php echo date($datef['post_header'], strtotime($outerrow['postdate'])); ?></span>
		</div>
		<div class="commentdata">
<?php
	// Avatar
	$avatar = '';
	$ava_sql = '';
	if ($cfg['use_avatars'] == 'yes' && strtolower($OekakiU) != 'screenshot') {
		$ava_sql = db_query("SELECT avatar FROM {$db_mem}oekaki WHERE usrname='{$outerrow['usrname']}'");
		if ($ava_sql && db_num_rows($ava_sql) > 0)
			$avatar = db_result($ava_sql, 0);
	}
	if (!empty($avatar)) {
?>
			<table>
			<tr>
			<td valign="top">
				<img src="<?php echo $cfg['avatar_folder'].'/'.$avatar;?>" alt="avatar" /><br />
			</td>
			<td valign="top" width="100%">
<?php
	}
	if (!empty($outerrow['comment'])) {
		?>
			<?php echo nifty2_convert($outerrow['comment']); ?> 
<?php
	} else {
?>
			<small><?php tt('no_comment');?></small>
<?php
	}
	if (!empty($outerrow['origart'])) {
		echo "<br />\n<br />\n<strong>".t('originalby', w_html_chars($outerrow['origart']))."</strong>\n";
	}
	if (!empty($avatar)) {
?>
			</td>
			</tr>
			</table>
<?php
		}
?>
		</div>

<?php
		$i2 = 0;
		while ($i2 < $rownum2) {
			// Get DB results, one row at a time.
			$innerrow = db_fetch_array($result2);
?>
		<div class="commentheader">
<?php
			if ($innerrow['usrname'] == 'Guest'){
				// Only reg members can see e-mails
				// E-mails are set here only if a guest, so don't bother with 'email_show'
				if (!empty($OekakiU) && !empty($innerrow['email'])) {
?>
			<b><i><a href="mailto:<?php echo email_code($innerrow['email'], true); ?>"><?php echo w_html_chars($innerrow['postname']);?></a></i></b>
<?php
				} else {
?>
			<span class="nolink"><i><?php echo w_html_chars($innerrow['postname']);?></i></span>
<?php
				}
			} else {
?>
			<b><a onclick="openWindow('profile.php?user=<?php echo urlencode($innerrow['usrname']);?>', 400, 500); return false" href="profile.php?user=<?php echo urlencode($innerrow['usrname']);?>"><?php echo w_html_chars($innerrow['usrname']);?></a></b>
<?php
			}
			// Print homepage only if available
			if (strlen($innerrow['url']) > 8) {
?>
		- <a href="<?php echo w_html_chars($innerrow['url']);?>">[<?php tt('word_homepage');?>]</a>
<?php
			}
?>
			<span class="commentinfo">@ <?php echo date($datef['post_header'], strtotime($innerrow['postdate'])); ?></span>
		</div>
<?php
		// Avatar
		$avatar = '';
		$ava_sql = '';
		if ($cfg['use_c_avatars'] == 'yes' && $cfg['use_avatars'] == 'yes' && $innerrow['usrname'] != 'Guest' && strtolower	($OekakiU) != 'screenshot') {
			$ava_sql = db_query("SELECT avatar FROM {$db_mem}oekaki WHERE usrname='{$innerrow['usrname']}'");
			if ($ava_sql && db_num_rows($ava_sql) > 0)
				$avatar = db_result($ava_sql, 0);
		}
		if (!empty($avatar)) {
?>
		<div class="commentdata"<?php if (FULL_AVATARS != 1) echo ' style="padding-top: 0; padding-bottom: 0; margin-top: 0; margin-bottom: 0;"';?>>
			<table>
			<tr>
			<td valign="top">
				<img src="<?php echo $cfg['avatar_folder'].'/'.$avatar;?>" alt="avatar" /><br />
			</td>
			<td valign="top" width="100%">
<?php
		} else {
?>
		<div class="commentdata">
<?php
		}
		if (!empty($innerrow['comment'])) {
?>
			<?php echo nifty2_convert($innerrow['comment']); ?> 
<?php
		} else {
?>
			<small><?php tt('no_comment');?></small>
<?php
		}
		if (!empty($avatar)) {
?>
			</td>
			</tr>
			</table>
<?php
		}
?>
		</div>

<?php
			$i2++;
		} // endwhile inner
?>
		</div>
		<!-- /Comments -->
<?php
	} // End comment count
?>
	</div>
</div>


<?php

include 'footer.php';