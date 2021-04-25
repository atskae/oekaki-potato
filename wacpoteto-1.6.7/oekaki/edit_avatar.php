<?php
/*
Wacintaki Poteto - Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6 - Last modified 2011-03-07
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


/*
	Custom config for correct avatar save path.

	It's necessary to use a local path to save avatars, but this does not work
	correctly with sub domains.  A bit of backtick trickery is required to make
	this work in such situations.

	Put a custom path here, or leave blank to use default.  It must be a local
	path to the main domain folder, so many backticks may be required, such as
	"../../domain/avatars".  Check the directory structure on your server
	before messing around with this!

	// Example = "./../main/avatars/"
*/
$my_main_avatar_path = ""; // Put path here.


// Config:
$max_width  = $cfg['avatar_x'];
$max_height = $cfg['avatar_y'];
$good_upload = false;


if ($cfg['use_avatars'] != 'yes') {
	report_err( t('avatardisable'));
}
// Must have *active* account
if (!$flags['member']) {
	report_err( t('loginerr'));
}


// Admin editing someone else's avatar?
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
			report_err( t('mem_not_found', $username));
		}
	} else {
		// It's serious to have SQL bomb here
		w_log_sql('edit_avatar:'.__LINE__);
		report_err( t('prof_not_ret', $username));
	}
} else {
	// Edit own profile
	$username = $OekakiU;
	$current_user = $user;
}


// Upload avatar
if (w_gpc('action') == 'do') {
	require 'paintsave.php';

	// Use default, or strip trailing slash of custom path
	if (!isset($my_main_avatar_path) || empty($my_main_avatar_path)) {
		$my_main_avatar_path = $cfg['avatar_folder'];
	} else {
		$my_main_avatar_path = preg_replace('/\/+$/', '', $my_main_avatar_path);
	}

	$old_filename = $current_user['avatar'];
	$old_filepath = $my_main_avatar_path.'/'.$current_user['avatar'];

	if (w_gpc('del')) {
		// Delete Avatar?
		if (!empty($current_user['avatar']) && file_exists($old_filepath)) {
			@unlink($old_filepath);
		}

		$result = db_query("UPDATE {$db_mem}oekaki SET avatar='' WHERE usrname='$username'");

		$current_user['avatar'] = '';
	} elseif (!empty($_FILES['avatar']['tmp_name'])) {
		// If file OK, get it
		if ($_FILES['avatar']['error'] != 0 || empty($_FILES['avatar']['tmp_name'])) {
			report_err( t('err_fileupl'), true);
		}

		// Get file
		$buffer = file_get_contents($_FILES['avatar']['tmp_name']);

		// Filetype
		$type = get_filetype($buffer);
		if ($type != 'png' && $type != 'jpg' && $type != 'gif') {
			report_err( t('unsuppic'), true);
		}

		// Get filesize
		$max_size = $cfg['avatar_x'] * $cfg['avatar_y'] * 3;
		if (strlen($buffer) > $max_size) {
			report_err( t('filetoolar', $max_size), true);
		}

		// Image properties
		$imagesize = @GetImageSize($_FILES['avatar']['tmp_name']);
		if (!$imagesize) {
			report_err( t('err_imagesize'), true);
		}
		if ($imagesize[0] > $max_width || $imagesize[1] > $max_height) {
			report_err( t('err_imagelar', $max_width, $max_height), true);
		}

		// Set filename
		// For browser caching reasons, set a pseudo-random name
		$filename = md5($username + strval(rand() )).'.'.$type;
		$filepath = $my_main_avatar_path.'/'.$filename;

		// Remove old avatar (WinPHP cache safe)
		if ($old_filename != $filename) {
			@unlink($old_filepath);
		}

		// Save new one
		file_unlock($filepath);
		if ($fp = fopen($filepath, 'wb')) {
			fwrite($fp, $buffer, strlen($buffer));
			fclose($fp);
			w_group_readable($filepath);

			// Add to DB
			$result = db_query("UPDATE {$db_mem}oekaki SET avatar='$filename' WHERE usrname='$username'");

			$good_upload = true;
		} else {
			report_err( t('err_fileupl'), true);
		}

		$current_user['avatar'] = $filename;
	}
}


/* START HTML */
send_html_headers();
?>
<head>
	<meta http-equiv="content-Type" content="text/html; charset=<?php echo $charset;?>" />
	<meta http-equiv="content-Language" content="<?php echo $metatag_language;?>" />

	<title><?php echo $cfg['op_title'];?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo $cssinclude;?>" title="<?php echo $template_name;?>" />
</head>

<body>


<div class="pheader">
	<?php tt('avatarupl');?> 
</div>
<div class="pinfotable">
	<p style="text-align: center;">
<?php if ($good_upload) { ?>
		<?php tt('avatarupdate');?> 
<?php } else { ?>
		<?php tt('avatarform');?><br />
		<?php tt('notlarg', $max_width, $max_height);?><br />
<?php if ($cfg['use_c_avatars'] != 'yes') { ?>
		<?php tt('avatarshpi');?> 
<?php } ?>
<?php } ?>
	</p>
</div>
<br />

<div>
	<table class="pinfotable" style="width: 100%;">
<?php if ($flags['admin'] && $OekakiU != $username) { ?>
	<tr>
	<td class="pinfoask" style="width: 40%;" valign="top">
		<?php tt('word_username');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo w_html_chars($username);?> 
	</td>
	</tr>

	<tr><td colspan="2">&nbsp;</td></tr>

<?php } ?>
	<tr>
	<td class="pinfoask" style="width: 40%;" valign="top">
		<?php tt('word_avatars');?> 
	</td>
	<td class="pinfoenter" valign="top">
<?php if (!empty($current_user['avatar'])) { ?>
		<img src="<?php echo $cfg['avatar_folder'].'/'.$current_user['avatar'];?>" alt="avatar" /><br />
<?php } else {?>
		<?php tt('noavatar');?> 
<?php } ?>
	</td>
	</tr>

	<tr><td colspan="2">&nbsp;</td></tr>

	<tr>
	<td class="pinfoask" style="width: 40%;" valign="top">
		<?php tt('chgavatar');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
			<input name="action" type="hidden" value="do" />
			<input name="username" type="hidden" value="<?php echo w_html_chars($username);?>" />
			<input name="avatar" type="file" size="30" class="txtinput" /><br />
			<br />
<?php if (!empty($current_user['avatar'])) { ?>
			<input name="del" type="checkbox" /> <?php tt('delavatar');?><br />
			<br />
<?php } ?>
			<input name="submit" type="submit" value="<?php tt('word_submit');?>" class="submit" />
		</form>
	</td>
	</tr>
	</table>
</div>

<p style="text-align: center;">
	<a onclick="window.close(); return false;" href="#"><?php tt('common_window');?></a>
</p>

</body>
</html>
<?php

w_exit();