<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.0 - Last modified 2015-02-10
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


// Private?
if ($cfg['private'] == 'yes' && empty($OekakiU)) {
	all_done();
}

// Input - Careful, "user" is a reserved variable
$name = w_gpc('user');


// Init
$chat = 0;


// SQL preparation
$count = db_query("SELECT COUNT(ID_2) FROM {$db_p}oekakidta WHERE usrname='$name'");
$total_pics = db_result($count, 0);

$result2 = db_query("SELECT * FROM {$db_p}oekakidta WHERE usrname='$name' ORDER BY postdate DESC LIMIT 10");
$rownum = db_num_rows($result2);

$result = db_query("SELECT * FROM {$db_mem}oekaki WHERE usrname='$name'");
$row = db_fetch_array($result);


// Get gender of member, to help with translation
$name_gender = 1; // Male or unknown
if (strtolower($row['gender']) == 'female') {
	$name_gender = 2;
}


// Get most recent IP & host
$the_IP = '';
$the_host = '';
if ($flags['mod']) {
	$post_result = db_query("SELECT IP, hostname, UNIX_TIMESTAMP(postdate) AS postdate FROM {$db_p}oekakidta WHERE usrname='$name' ORDER BY postdate DESC LIMIT 1");
	if ($post_result) {
		$post_row = db_fetch_array($post_result);
	}

	$com_result = db_query("SELECT IP, hostname, UNIX_TIMESTAMP(postdate) as postdate FROM {$db_p}oekakicmt WHERE usrname='$name' ORDER BY postdate DESC LIMIT 1");
	if ($com_result) {
		$com_row = db_fetch_array($com_result);
	}

	if ($post_result || $com_result) {
		if ( (int) $com_row['postdate'] > (int) $post_row['postdate']) {
			$the_IP = t('ip_by_comment', $com_row['IP']);
			if (!empty($com_row['hostname']) || $com_row['hostname'] != 'invalid') {
				$the_host = $com_row['hostname'];
			}
		} else {
			$the_IP = t('ip_by_picture', $post_row['IP']);
			if (!empty($post_row['hostname']) || $post_row['hostname'] != 'invalid') {
				$the_host = $post_row['hostname'];
			}
		}
	}
}


// Get profile flags
$their_flags = parse_flags($row['usrflags'], $row['rank']);



// Start HTML
send_html_headers();
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
	<meta http-equiv="Content-Language" content="<?php echo $metatag_language;?>" />

	<title><?php echo $cfg['op_title'];?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo $cssinclude;?>" title="<?php echo $template_name;?>" />

<?php if (! (isset($cfg['use_viewer']) && $cfg['use_viewer'] == 0)) { ?>
	<script type="text/javascript" language="javascript" src="lytebox/lytebox.js"></script>
	<link rel="stylesheet" type="text/css" href="lytebox/lytebox.css" media="screen" />
<?php } ?>
</head>

<body>


<!-- Profile -->
<div class="pheader">
	<?php
	tt('profile_title', w_html_chars($name), $name_gender);?> 
</div>

<div>
	<table class="pinfotable" style="width: 100%;">
<?php
	if ($flags['admin']) {
		// Admin controls
?>
	<tr>
	<td class="pinfoask" style="width: 40%;" valign="top">
		<?php tt('word_admin');?> 
	</td>
	<td class="pinfoenter" valign="top">
		[<a href="editprofile.php?username=<?php echo urlencode($row['usrname']); ?>" target="_blank"><?php tt('eprofile_title');?></a>]<br />
		[<a href="modflags.php?usrname2=<?php echo urlencode($row['usrname']); ?>&amp;muser=Submit" target="_blank"><?php tt('common_mflags');?></a>]<br />
<?php
		if ($flags['owner']) {
?>
		[<a href="delusr.php?usrname2=<?php echo urlencode($row['ID']); ?>&amp;pmember=Submit" target="_blank"><?php tt('common_delusr');?></a>]<br />
<?php
		}
?>
	</td>
	</tr>

<?php
	}
	if ($flags['mod']) {
?>
	<tr>
	<td class="pinfoask" style="width: 40%;" valign="top">
		<?php tt('reciphost');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo t('ip_by_login', $row['IP']).'<br />'.$the_IP.'<br />'.$the_host."\n"; ?>
	</td>
	</tr>
	<tr>
	<td colspan="2">
		<hr style="display: block" />
	</td>
	</tr>


<?php
	}
	if ($cfg['use_avatars'] && $row['avatar'] || $cfg['use_mailbox'] == 'yes') {
?>
	<tr>
	<td colspan="2" style="text-align: center;">
<?php if ($cfg['use_avatars'] && $row['avatar']) { ?>
		<img src="<?php echo $cfg['avatar_folder'].'/'.$row['avatar'];?>" alt="avatar" /><br />
<?php } ?>
<?php if ($cfg['use_mailbox'] == 'yes') { ?>
		(<a href="mailsend.php?sendto=<?php echo urlencode($name)?>" target="_blank"><?php tt('sendmailbox');?></a>)
<?php } ?>
	</td>
	</tr>
	<tr>
	<td colspan="2">
		<hr style="display: block" />
	</td>
	</tr>



<?php } ?>
	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_name');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo w_html_chars($row['name']);?> 
	</td>
	</tr>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_email');?> 
	</td>
	<td class="pinfoenter" valign="top">
<?php
	// E-mail
	echo "\t\t";
	if (!empty($OekakiU)) {
		$e_click   = email_code($row['email'], true);
		$e_display = email_code($row['email'], false);

		// Obscure e-mail from non-admins?
		if (!$row['email_show']) {
			echo "(".t('word_na').") ";
		}

		// Add default subject line for admin e-mail addresses
		$subject = '';
		if ($their_flags['mod']) {
			$subject = '?subject=Oekaki';
		}

		if ($flags['mod']) {
			echo "<a href=\"mailto:{$e_click}{$subject}\">{$e_click}</a>";
		} elseif ($row['email_show']) {
			echo "<a href=\"mailto:{$e_click}{$subject}\">{$e_display}</a>";
		}
	} else {
		echo t('plzlogin');
	}
	echo "\n";
?>
	</td>
	</tr>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_age');?> 
	</td>
	<td class="pinfoenter" valign="top">
<?php
		$the_date = $row['age'];
		$the_age  = get_age($the_date);

		if (strpos($the_date, '-') === false) {
			// No birthday
			$the_b_day = '';
		} else {
			$the_b_day = ' <small>('.$the_date.')</small>';
		}

		echo $the_age.$the_b_day."\n";
?>
	</td>
	</tr>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_gender');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo w_html_chars($row['gender']);?> 
	</td>
	</tr>
<?php if ($flags['member']) { ?>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_location');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo w_html_chars($row['location']);?> 
	</td>
	</tr>
<?php } ?>

	<tr><td colspan="2">&nbsp;</td></tr>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_joined', $name_gender);?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo $row['joindate']?> 
	</td>
	</tr>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('common_lastlogin', $name_gender);?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo $row['lastlogin']?> 
	</td>
	</tr>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('common_picposts');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo $row['piccount']?> 
	</td>
	</tr>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('common_compost');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo $row['commcount']?> 
	</td>
	</tr>

	<tr>
	<td colspan="2">
		&nbsp;
	</td>
	</tr>

<?php
//Check for valid URL + Title
$my_url   = '';
$my_title = t('url_none');

if (strlen($row['url']) > 9) {
	$my_url = '<a href="'.w_html_chars($row['url']).'">';
}
if (!empty($row['urltitle'])) {
	$my_title = w_html_chars($row['urltitle']);
} elseif (!empty($my_url)) {
	$my_title = t('url_substitute');
}
if (!empty($my_url)) {
	$my_url .= $my_title.'</a>';
} else {
	$my_url .= $my_title;
}
?>
	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_url');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo $my_url; ?> 
	</td>
	</tr>

	<tr>
	<td colspan="2">
		&nbsp;
	</td>
	</tr>

<?php
	// Members only
	if ($flags['member']) {

if (!empty($row['aim'])) {
	$chat++; ?>
	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_aolinstantmessenger');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<a href="aim:AddBuddy?ScreenName=<?php echo urlencode($row['aim']);?>&amp;groupname=OekakiPoteto"><?php echo w_html_chars($row['aim']);?></a>
	</td>
	</tr>

<?php } ?>
<?php if (!empty($row['icq'])) {
	$chat++; ?>
	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_icq');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo w_html_chars($row['icq']);?> 
	</td>
	</tr>

<?php } ?>
<?php if (!empty($row['MSN'])) {
	$chat++; ?>
	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_microsoftmessenger');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo w_html_chars($row['MSN']);?> 
	</td>
	</tr>

<?php } ?>
<?php if (!empty($row['Yahoo'])) {
	$chat++; ?>
	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_yahoomessenger');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo w_html_chars($row['Yahoo']);?> 
	</td>
	</tr>

<?php }

} // Members only

if ($chat) { ?>
	<tr>
	<td colspan="2">
		&nbsp;
	</td>
	</tr>

<?php } ?>
	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_template');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php ne_echo($row['templatesel'], t('template_default'))."\n"; ?> 
	</td>
	</tr>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_language');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php ne_echo($row['language'], t('language_default'))."\n"; ?> 
	</td>
	</tr>

	<tr>
	<td colspan="2">
		&nbsp;
	</td>
	</tr>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_comments', $name_gender);?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo nifty2_convert($row['comment']); ?> 
	</td>
	</tr>

	<tr>
	<td colspan="2">
		&nbsp;
	</td>
	</tr>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_special');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php
		if ($their_flags['owner']) {
			echo t('word_owner').'<br />';
		} elseif ($their_flags['sadmin']) {
			echo t('type_sadmin').'<br />';
		} elseif ($their_flags['admin']) {
			echo t('type_admin').'<br />';
		} elseif ($their_flags['mod']) {
			echo 'Moderator<br />';
		} elseif ($their_flags['G']) {
			echo t('type_guser');
		}
		if (strstr($row['usrflags'], 'D')) {
			echo '<br />'.t('type_daccess');
		}
		if (strstr($row['usrflags'], 'M')) {
			echo '<br />'.t('type_aaccess');
		}
		if (strstr($row['usrflags'], 'U')) {
			echo '<br />'.t('type_uaccess');
		}
		if (strstr($row['usrflags'], 'I')) {
			echo '<br />'.t('type_immunity');
		}
		if (strstr($row['usrflags'], 'X')) {
			echo '<br />'.t('type_adultview', MIN_AGE_ADULT);
		}
		?>
	</td>
	</tr>
	</table>
</div>
<hr />
<br />


<?php if ($flags['member']) {
	// Members only

if (strlen($row['IRCServer']) > 7) { ?>
<!-- IRC -->
<div class="pheader">
	<?php tt('eprofile_irctitle');?> 
</div>

<div>
	<table class="pinfotable" style="width: 100%;">
	<tr>
	<td class="pinfoask" style="width: 40%;" valign="top">
		<?php tt('word_ircserver');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo w_html_chars($row['IRCServer']);?> 
	</td>
	</tr>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_ircnickname');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo w_html_chars($row['IRCNick']);?> 
	</td>
	</tr>

	<tr>
	<td class="pinfoask" valign="top">
		<?php tt('word_ircchannel');?> 
	</td>
	<td class="pinfoenter" valign="top">
		<?php echo w_html_chars($row['IRCchan']);?> 
	</td>
	</tr>
	</table>
</div>
<hr />
<br />
<?php }

} // Members only

?>
<!-- Latest -->
<div class="pheader">
	<?php tt('profile_latest');?> 
</div>

<div>
	<table class="pinfotable" style="width: 100%;">
<?php

// If no pics...
if ($total_pics == 0) {
	?>
	<tr>
	<td class="pinfoask" style="text-align: center;" valign="top">
		<?php tt('no_pictures');?> 
	</td>
	</tr>
<?php
} else {
	?>
	<tr>
	<td class="pinfoask" style="text-align: center;" valign="top">
		<a href="index.php?a_match=<?php echo urlencode($name);?>" target="_blank"><?php tt('browseallpost', $total_pics);?></a>
	</td>
	</tr>
<?php
}


// Otherwise, print out the list
$i = 0;
while ($i < $rownum) {
	$row = db_fetch_array($result2);

	// Get title
	$row['title'] = trim($row['title']);
	$title = $row['title'];
	if (empty($row['title'])) {
		$title = t('no_pic_title');
	}

	// Lightbox support
	$lightbox = '';
	if (!$user['no_viewer']) {
		if ($outerrow['adult'] == 1) {
			$lightbox = $user['pic_viewer_adult'];
		} else {
			$lightbox = $user['pic_viewer_norm'];
		}
	}
	if (empty($row['title'])) {
		$lightbox_title = 'title="Artist: '.w_html_chars($row['usrname']).'"';
	} else {
		$lightbox_title = 'title="&quot;'.w_html_chars($row['title']).'&quot; by '.w_html_chars($row['usrname']).'"';
	}

	// Get thumbnail
	$link = "<a href=\"{$p_pic}{$row['PIC_ID']}.{$row['ptype']}\" {$lightbox} {$lightbox_title} target=\"_blank\">";
	$link2 = "</a>";
	if (!empty($row['ttype'])) {
		$thumbnail = "<img src=\"{$t_pic}{$row['PIC_ID']}.{$row['ttype']}\" class=\"imghover\" alt=\"\" />";
	} else {
		// Get longest side of picture
		if ($row['py'] > $row['px']) {
			$long_mode = 1;
		} else {
			$long_mode = 0;
		}

		// Scale it
		$using_thumbnail = 1;
		if ($long_mode == 1) {
			// Vertical
			$scale_size = 'height="'.$cfg['thumb_t'].'"';
		} else {
			// Horizontal
			$scale_size = 'width="'.$cfg['thumb_t'].'"';
		}

		$thumbnail = "<img src=\"{$p_pic}{$row['PIC_ID']}.{$row['ptype']}\" $scale_size class=\"imghover\" alt=\"\" />";
	}

	// Set the adult mode
	if (!$flags['X'] && $row['adult'] == 1) {
		if ($cfg['guest_adult'] == 'yes' || $flags['mod']) {
			// block image only (worksafe)
			$thumbnail = "<img src=\"{$cfg['res_path']}/{$cfg['porn_img']}\" height=\"{$cfg['thumb_t']}\" class=\"imghover\" alt=\"\" />";
		} else {
			// block image and link
			$thumbnail = "<img src=\"{$cfg['res_path']}/{$cfg['porn_img']}\" height=\"{$cfg['thumb_t']}\" class=\"imghover\" alt=\"\" />";
			$link = '';
			$link2 = '';
		}
	}

	// OK, print it
	?>
	<tr>
	<td class="pinfoask" style="text-align: center;" valign="top">
		<hr style="display: block" />
		<?php echo $link.$thumbnail.$link2."\n" ?><br />
		<?php echo w_html_chars($title); ?><br />
<?php if (!empty($row['origart'])) { ?>
		<?php tt('originalby', w_html_chars($row['origart']));?><br />
<?php } ?>
		<?php echo $row['postdate'];?> 
	</td>
	</tr>
<?php
	$i++;
}

?>
	</table>
</div>

<p style="text-align: center;">
	<a onclick="window.close(); return false;" href="#"><?php tt('common_window');?></a>
</p>

</body>
</html>
<?php

w_exit();