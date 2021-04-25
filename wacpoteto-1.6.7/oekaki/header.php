<?php // Include only
if (!defined('BOOT')) exit('No bootstrap.  Exiting.');

/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.0 - Last modified 2015-02-12
*/


// --------------
// PRIVATE HEADER
// --------------
if (empty($OekakiU) && $cfg['private'] == 'yes') {
	$my_allowed = array('register.php', 'lostpass.php', 'error.php', 'showrules', 'index.php');

	if (!in_array(basename($_SERVER['PHP_SELF']), $my_allowed)) {
		all_done();
	}

	send_html_headers();
	?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
	<meta http-equiv="Content-Language" content="<?php echo $metatag_language;?>" />

	<title><?php echo $cfg['op_title'];?></title>

	<meta name="Robots" content="nofollow" />
<?php if ($cfg['op_adult'] == 'yes') { ?>
	<meta name="Rating" content="mature" />
<?php } ?>
	<meta name="Copyright" content="<?php echo date('Y').' '.$cfg['op_author'];?>" />
	<meta name="Author" content="<?php echo $cfg['op_author'];?>" />
	<meta name="Generator" content="www.EditPlus.com" />

	<script type="text/javascript" src="Poteto.js">
	</script>

	<link rel="stylesheet" type="text/css" href="<?php echo $cssinclude;?>" title="<?php echo $template_name;?>" />
<?php if (strpos($language, 'chinese') !== false) { ?>

	<style type="text/css">* {font-weight: normal;}</style>
<?php } ?>
</head>

<body>


<?php
	if (file_not_empty($cfg['res_path'].'/banner.php')) {
		if (strpos($_SERVER['PHP_SELF'], 'BBS') === false) {
			echo "<div id=\"banner\">\n";
			include $cfg['res_path'].'/banner.php';
			echo "\n</div>\n";
		}
	}
	?>


<table id="menubar" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="right">
		<a href="register.php"><?php tt('word_register');?></a>
		| <a href="lostpass.php"><?php tt('header_rpass');?></a>
	</td>
	</tr>
</table>


<table id="options" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left">
<?php
	if (!defined('DISABLE_GUEST_LANG') || !DISABLE_GUEST_LANG) {
	?>
	<form method="get" action="index.php" style="margin: 0;">
		<?php
		echo '<a href="./" style="text-decoration: underline;">'.t('word_view')."</a>\n";
		header_print_language_select();
	?>
	</form>
<?php
	} else {
		echo "\t&nbsp;\n";
	}
?>
	</td>
	</tr>
</table>


<hr />


<div id="adminbar">
	<form name="form_login" action="functions.php" method="post" style="margin: 0; text-align: center;">
		<input name="action" type="hidden" value="login" />

		<?php tt('word_name');?> 
		<input name="username" type="text" class="multiline" />

		<?php tt('install_password');?> 
		<input name="pass" type="password" class="multiline" />
		<input name="login" type="submit" value="<?php tt('install_login');?>" class="submit" />
	</form>
</div>


<hr />
<br />


<?php

	if (basename($_SERVER['PHP_SELF']) == 'index.php') {
		?>

<br />
<br />
<div id="contentmain">

	<h1 class="header">
		<?php tt('header_private_oekaki'); ?> 
	</h1>
	<div class="infotable" style="text-align: center;">
		<?php tt('private_please_login'); ?> 
	</div>

</div>
<br />
<br />


<?php

	include 'footer.php';
	w_exit();
	}
}
// END PRIVATE HEADER


// -----------
// MAIN HEADER
// -----------
//
// Collect user info
if (!empty($OekakiU) || $cfg['private'] != 'yes') {
	// Online
	$whoonline  = db_query("SELECT COUNT(onlineusr) FROM {$db_mem}oekakionline");
	$onlinerows = db_result($whoonline);

	// Chat
	if ($cfg['use_chat'] == 'yes') {
		$whoonline  = db_query("SELECT COUNT(onlineusr) FROM {$db_mem}oekakionline WHERE locale='chat'");
		$chatonline = db_result($whoonline);
	}

	// Pending users
	$pending_users = '';
	if ($flags['admin']) {
		$pending_count = 0;
		$result = db_query("SELECT COUNT(usrname) FROM {$db_mem}oekaki WHERE usrflags='P'");
		if ($result) {
			$pending_count = (int) db_result($result);
		}

		if ($cfg['approval'] == 'yes' && $pending_count > 0) {
			$pending_users = '&nbsp;<span class="newmail">('.$pending_count.')</span>';
		} else {
			$pending_users = '&nbsp;('.$pending_count.')';
		}
	}

	// Mailbox
	if ($cfg['use_mailbox'] == 'yes') {
		$readmail = db_query("SELECT COUNT(MID) FROM {$db_mem}oekakimailbox WHERE reciever='$OekakiU' AND mstatus=".MAIL_NEW);
		$mailrows = db_result($readmail);

		if ($mailrows > 0) {
			$mailrows = '<span class="newmail">'.$mailrows.'</span>';
		}
	}



	// Start building menus.  To add menu items, just put them in here
	$left_menu = array();
	$right_menu = array();
	// $left_menu[] = '<a href="index.php">'.t('word_homepage').'</a>';
	$left_menu[] = '<a href="faq.php">'.t('word_faq').'</a>';
	$left_menu[] = '<a href="memberlist.php">'.t('word_memberlist').'</a>';
	$left_menu[] = t('header_online', 'whosonline.php" onclick="openWindow(\'whosonline.php\', 450, 400); return false;', $onlinerows);

	if (!empty($OekakiU)) {
		// Logged in
		if ($cfg['use_chat'] == 'yes') {
			$left_menu[] = t('header_chat', 'chatbox.php" onclick="openWindow(\'chatbox.php\', 700, 500); return false;', $chatonline);
		}

		if ($flags['D']) {
			$right_menu[] = '<a href="draw.php">'.t('header_draw').'</a>';
		}
		if ($flags['mod'] || $flags['U']) {
			$right_menu[] = '<a href="upload.php">'.t('word_upload').'</a>';
		}
		if ($cfg['use_mailbox'] == 'yes') {
			$right_menu[] = t('header_mailbox', 'mailbox.php', $mailrows);
		}

		if ($glob['maintenance_boot']) {
			$right_menu[] = t('word_logoff');
		} else {
			$right_menu[] = '<a href="functions.php?mode=logoff">'.t('word_logoff').'</a>';
		}
	} else {
		// Logged out
		$right_menu[] = '<a href="register.php">'.t('word_register').'</a>';
		$right_menu[] = '<a href="lostpass.php">'.t('header_rpass').'</a>';
	}

	$left_menu[] = '<a href="showrules.php">'.t('header_rules').'</a>';

	if ($glob['maintenance_boot']) {
		$left_menu[] = '<blink>'.t('header_maint').'</blink>';
	}


	// Start HTML
	send_html_headers();
	?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
	<meta http-equiv="Content-Language" content="<?php echo $metatag_language;?>" />

	<title><?php echo $cfg['op_title'];?></title>

	<meta name="Description" content="Wacintaki Oekaki: draw pictures online!" />
	<meta name="Keywords" content="Wacintaki, oekaki, sketch, paint, drawing, draw, PaintBBS, Shipainter" />
	<meta name="Robots" content="nofollow" />
<?php if ($cfg['op_adult'] == 'yes') { ?>
	<meta name="Rating" content="mature" />
<?php } ?>
	<meta name="Copyright" content="<?php echo date('Y').' '.$cfg['op_author'];?>" />
	<meta name="Author" content="<?php echo $cfg['op_author'];?>" />
	<meta name="Generator" content="www.EditPlus.com" />

	<script type="text/javascript" src="Poteto.js">
	</script>

	<link rel="stylesheet" type="text/css" href="<?php echo $cssinclude;?>" title="<?php echo $template_name;?>" />

<?php if (! (isset($cfg['use_viewer']) && $cfg['use_viewer'] == 0)) { ?>
	<script type="text/javascript" language="javascript" src="lytebox/lytebox.js"></script>
	<link rel="stylesheet" type="text/css" href="lytebox/lytebox.css" media="screen" />
<?php } ?>
<?php if (strpos($language, 'chinese') !== false) { ?>

	<style type="text/css">* {font-weight: normal;}</style>
<?php } ?>
</head>

<body>


<!-- Banner -->
<?php
	if (file_not_empty($cfg['res_path'].'/banner.php')) {
		if (strpos($_SERVER['PHP_SELF'], 'BBS') === false) {
			echo "<div id=\"banner\">\n";
			include $cfg['res_path'].'/banner.php';
			echo "\n</div>\n";
		}
	}
	?>
<!-- /Banner -->


<!-- Menubar -->
<table id="menubar" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left">
		<?php echo make_list($left_menu, "\n\t\t | ")."\n"; ?> 
	</td>

	<td align="right">
		<?php echo make_list($right_menu, "\n\t\t | ")."\n"; ?> 
	</td>
	</tr>
</table>
<!-- /Menubar -->


<!-- Options -->
<table id="options" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left">
		<form method="get" action="index.php" style="margin: 0;">
			<a href="./" style="text-decoration: underline;"><?php tt('word_view');?></a>
			<select onchange="this.form.submit();" name="sort" title="View Mode" class="multiline">
				<option value="0" <?php if ($sort==0) echo('selected="selected"') ?>><?php tt('word_drawings');?></option>
				<option value="2" <?php if ($sort==2) echo('selected="selected"') ?>><?php tt('word_animations');?></option>
				<option value="4" <?php if ($sort==4) echo('selected="selected"') ?>><?php tt('word_archives');?></option>
<?php if ($cfg['public_retouch'] == 'yes') { ?>
				<option value="8" <?php if ($sort==8) echo('selected="selected"') ?>><?php tt('publicimg');?></option>
<?php } ?>
				<option value="1" <?php if ($sort==1) echo('selected="selected"') ?>><?php tt('drawbycomm');?></option>
				<option value="3" <?php if ($sort==3) echo('selected="selected"') ?>><?php tt('animbycomm');?></option>
				<option value="5" <?php if ($sort==5) echo('selected="selected"') ?>><?php tt('archbycomm');?></option>
			</select>
			<input name="pageno" type="hidden" value="0" />
<?php
	if (!defined('DISABLE_GUEST_LANG') || !DISABLE_GUEST_LANG) {
		header_print_language_select();
	}
	?>
		</form>
	</td>

	<td align="right">
		<form method="get" action="index.php" style="margin: 0;">
			<?php tt('word_search');?> 
			<input name="artist" type="text" <?php if(!empty($artist)) { echo('value="'.w_html_chars($artist).'" '); };?>class="multiline" title="Enter name of artist, or a keyword to search" />
			<input name="sortartist2" type="submit" value="<?php tt('word_go');?>" id="sortartist2" class="submit" />
			<input name="pageno" type="hidden" value="0" />
		</form>
	</td>
	</tr>
</table>
<!-- /Options -->


<hr />


<!-- Adminbar -->
<div id="adminbar">
<?php
	//If user show admin bar, else, login
	if (!empty($OekakiU)) {

		// If normal user ...
		$temp = array();
		if ($flags['member']) {
			echo '	<strong>'.t('myoekaki').'</strong>:'."\n";

			$temp[] = '<a href="editprofile.php">'.t('eprofile_title').'</a>';
			if ($flags['D'])
				$temp[] = '<a href="index.php?a_match='.urlencode($OekakiU).'">'.t('header_epics').'</a>';
			if ($flags['D'])
				$temp[] = '<a href="recover.php">'.t('header_rpics').'</a>';
		}
		echo make_list($temp, "\n\t/ ")."\n";


		// If admin (Global) ...
		if ($flags['admin']) {
	?>
	| <strong><?php tt('word_admin');?></strong>:
	<a href="delpics.php"><?php tt('header_dpics');?></a>
	/ <a href="delcomments.php"><?php tt('header_dcomm');?></a>
	/ <a href="addusr.php"><?php tt('header_vpending');?></a><?php echo $pending_users;?> 
	/ <a href="modflags.php"><?php tt('niftytoo_permissions');?></a>
	/ <a href="editnotice.php"><?php tt('header_ebanner');?></a>
	/ <a href="editrules.php"><?php tt('editrul');?></a>
	/ <a href="banlist.php"><?php tt('header_banlist');?></a>
	/ <a href="log.php"><?php tt('word_log');?></a>
<?php
		}

		// If owner ...
		if ($flags['owner']) {
		?>
	<br />
	<strong><?php tt('type_owner');?></strong>:
	<a href="cpanel.php"><?php tt('header_cpanel');?></a>
	/ <a href="testinfo.php"><?php tt('header_diag');?></a>
	/ <a href="massmail.php"><?php tt('header_sendall');?></a>
	/ <a href="newpass.php"><?php tt('respwd');?></a>
<?php
		}

		// If owner or superadmin ...
		if ($flags['sadmin']) {
		?>
	/ <a href="delusr.php"><?php tt('common_delusr');?></a>
	/ <a href="renameusr.php"><?php tt('header_rename');?></a>
<?php
		}
	} else {
		// If !$OekakiU
		?>
	<form name="form_login" action="functions.php" method="post" style="margin: 0; text-align: center;">
		<input name="action" type="hidden" value="login" />

		<?php tt('word_name');?> 
		<input name="username" type="text" class="multiline" />

		<?php tt('install_password');?> 
		<input name="pass" type="password" class="multiline" />
		<input name="login" type="submit" value="<?php tt('install_login');?>" class="submit" />
	</form>
<?php
	}
	?>
</div>
<!-- /Adminbar -->


<hr />
<br />


<?php
}
// END HEADER



// -----

function header_print_language_select() {
	global $OekakiU, $language;
	global $lang;

	if (empty($OekakiU)) {
		// Get languages for guests
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

		if (count($lang_names) > 1) {
?>

			<select onchange="this.form.submit();" name="guest_lang" title="<?php tt('word_language');?>" class="multiline">
<?php
			foreach ($lang_names as $name) {
				$name2 = $name;
				if (isset($lang['names'][$name])) {
					$name2 = $lang['names'][$name];
				}
				if ($name == $language) {
					echo '				<option value="'.$name.'" selected="selected">'.$name2.'</option>'."\n";
				} else {
					echo '				<option value="'.$name.'">'.$name2.'</option>'."\n";
				}
			}
			echo '			</select>'."\n";
		}
	}

	return true;
}