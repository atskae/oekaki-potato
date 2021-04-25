<?php // Include only
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2012 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.13 - Last modified 2012-10-07
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


// Private?
if ($cfg['private'] == 'yes' && empty($OekakiU)) {
	all_done();
}

// Disabled?
if ($cfg['use_chat'] == 'no') {
	report_err( t('charommdisable'), true);
}

if ($cfg['guest_post'] == 'no' && !$flags['member']) {
	report_err( t('memaccesschat'), true);
}


// Input
// $pageno


// Init
$refresh = 1;


if (w_gpc('send') == 'Send') {
	$comment = w_gpc('comment');
	$name    = w_gpc('name');
	$email   = w_gpc('email', 'email');
	$url     = w_gpc('url', 'url');

	if (strlen($comment) > 200) {
		$comment = substr($comment, 0, 200);
	}

	// Delete old entries (10 slots)
	$result5 = db_query("SELECT COUNT(ChatID) FROM {$db_mem}oekakichat");
	if (intval(db_result($result5, 0)) > $cfg['chat_max'] && $cfg['chat_max'] > 10) {
		$result = db_query("SELECT ChatID FROM {$db_mem}oekakichat ORDER BY ChatID LIMIT 1");
		if ($result) {
			$chat_del = (int) db_result($result, 0);
			$chat_del += 10;
			db_query("DELETE FROM {$db_mem}oekakichat WHERE ChatID < $chat_del");
		}
	}

	if (!empty($comment)) {
		if (!empty($OekakiU)) {
			$result = db_query("INSERT INTO {$db_mem}oekakichat SET usrname='$OekakiU', comment='$comment', posttime=NOW(), hostname='$hostname', IP='$address'");
		} else {
			if (!empty($name)) {
				$result = db_query("INSERT INTO {$db_mem}oekakichat SET usrname='Guest', comment='$comment', posttime=NOW(), hostname='$hostname', email='$email', url='$url', IP='$address', postname='$name'");
			}

			// Set or reset cookies
			if (!isset($_COOKIE['guestName']) || w_gpc('guestName') != $name) {
				w_set_cookie('guestName',  $name);
			}
			if (!empty($email) && (!isset($_COOKIE['guestEmail']) || w_gpc('guestEmail') != $email)) {
				w_set_cookie('guestEmail', $email);
			}
			if (!empty($url) && (!isset($_COOKIE['guestURL']) || w_gpc('guestURL') != $url)) {
				w_set_cookie('guestURL',   $url);
			}
		}
	}
}


// Get latest chat posts
$result5 = db_query("SELECT ChatID FROM {$db_mem}oekakichat");
$pages = ceil(db_num_rows($result5) / $cfg['chat_pages']);

$rsql = "SELECT * FROM {$db_mem}oekakichat ORDER BY posttime DESC LIMIT ".($pageno * $cfg['chat_pages']).', '.$cfg['chat_pages'];
$result5 = db_query($rsql);
$rownum = db_num_rows($result5);


// Get count of users online
$orownum = 0;
$onlinerow = 0;
$oresult = db_query("SELECT * FROM {$db_mem}oekakichat WHERE (DATE_ADD(posttime, INTERVAL 1 MINUTE) > NOW() )");
if ($oresult) {
	$orownum = db_num_rows($oresult);
}
$online = db_query("SELECT onlineusr, locale FROM {$db_mem}oekakionline WHERE locale='o_chatbox'");
if ($online) {
	$onlinerow = db_num_rows($online);
}



// Start HTML
send_html_headers();
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
	<meta http-equiv="Content-Language" content="<?php echo $metatag_language;?>" />
<?php if ($pageno < 1){ ?>
	<meta http-equiv="Refresh" content="15;url=chat.php" />
<?php } ?>

	<title><?php echo $cfg['op_title'];?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo $cssinclude;?>" title="<?php echo $template_name;?>" />

	<script type="text/javascript" src="Poteto.js">
	</script>
</head>
<body>

	<div class="pinfotable">
		<?php
$pages_link = 'chat.php?pageno={page_link}';
echo quick_page_numbers(0, $pageno, $pages, 4, $pages_link)."\n";
?>
	</div>
	<br />


	<div class="pheader">
		<?php tt('chat_msgbox', 'niftyusage.php" onclick="openWindow(\'niftyusage.php\', 500, 300); return false;');?> 
	</div>

	<div class="pinfotable">

		<!-- Chatbox -->
		<table style="width: 100%">
		<tr>
		<td width="75%" valign="top">
			<!-- Conversation -->
			<table width="100%">
			<tr>
			<td class="chatinfo" colspan="3">
				<strong><?php tt('chat_conversation');?></strong>
			</td>
			</tr>
<?php

// Get the rows.  We have to reverse the order.
$row = array();
for ($i = 0; $i < $rownum ; $i++) {
	$row[] = db_fetch_array($result5);
	$row[$i]['comment'] = trim($row[$i]['comment']);
}

// Start printing chat comments
for ($i = $rownum - 1; $i >= 0 ; $i--) {

	// '/me' stinks (gets rid of brackets)
	$perform_action = false;
	if (substr($row[$i]['comment'], 0, 3) == '/me') {
		$row[$i]['comment'] = ltrim(substr($row[$i]['comment'], 3));
		$perform_action = true;
	}

	?>
			<tr>
			<td class="pchatdialog" valign="top">
<?php
	// Get time
	$my_time = strtotime($row[$i]['posttime']);
	if (time() - $my_time < 86400) {
	?>
				<strong><?php echo date($datef['chat'], $my_time);?>-&nbsp;</strong>
<?php
	} else {
		echo '&nbsp;';
	}
	?>
			</td>
			<td align="right" valign="top">
<?php
	if ($row[$i]['usrname'] == 'Guest') {
		// Admins can see IP/host of guests
		$my_link = w_html_chars($row[$i]['postname']);
		if ($flags['admin']) {
			$my_host_link = '';
			if (!empty($row[$i]['hostname']) && $row[$i]['hostname'] != 'invalid') {
				$my_host_link = ' / '.$row[$i]['hostname'];
			}
			$my_link = "<acronym title=\"{$row[$i]['IP']}{$my_host_link}\">{$my_link}</acronym>";
		}
		if (!empty($row[$i]['email'])) {
			$my_link = "<a href=\"mailto:".urlencode($row[$i]['email'])."\">{$my_link}</a>";
		}
		echo '				#'.$my_link.'';
	} else {
	?>
				<a href="profile.php?user=<?php echo urlencode($row[$i]['usrname']);?>" onclick="openWindow('profile.php?user=<?php echo urlencode($row[$i]['usrname']);?>', 400, 600); return false;"><?php echo w_html_chars($row[$i]['usrname']);?></a><?php
	}

	// Now print the comment
	if (!$perform_action) {
		echo '&gt;';
	}
	echo '</td><td width="100%">'.nifty2_convert($row[$i]['comment'])."\n";
	?>
			</td>
			</tr>
<?php
}
?>
			</table>
			<!-- /Conversation -->
		</td>


		<td width="25%" valign="top" style="padding-left: 1%;">
			<!-- Registered -->
			<table width="100%">
			<tr>
			<td class="chatinfo">
				<strong><?php tt('chat_online', $onlinerow);?></strong>
			</td>
			</tr>

			<tr>
			<td class="pchatdialog">
<?php
while ($row = db_fetch_array($online)) {
?>
			<a href="profile.php?user=<?php echo urlencode($row['onlineusr']);?>" onclick="openWindow('profile.php?user=<?php echo urlencode($row['onlineusr']);?>', 300, 400); return false;"><?php echo w_html_chars($row['onlineusr']);?></a><br /><?php
}
?>
			</td>
			</tr>
			</table>
		</td>
		</tr>
		</table>


	</div>
	<!-- /Chatbox -->

	<div id="bottom"></div>

</body>
</html>
<?php

w_exit();