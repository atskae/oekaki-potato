<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.8 - Last modified 2011-09-05
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


// Init
$hsel = ' selected="selected"';


// Input
// $pageno
$searchop  = w_gpc('searchop');
$searchstr = w_gpc('searchstr');
$order     = w_gpc('order');
$mypages   = w_gpc('pages', 'i+');
$active_login_time = ACTIVE_LOGIN_TIME_DAYS; // Last login in days to be considered active


// Secure results with search
$searchop_allow = array(
	'usrname'  => 'word_username',
	'name'     => 'word_name',
	'email'    => 'word_email',
	'age'      => 'word_age',
	'gender'   => 'word_gender',
	'location' => 'word_location',
	'piccount' => 'common_picposts',
	'commcount' => 'common_compost',
	'joindate'  => 'common_jdate',
	'lastlogin' => 'common_lastlogin',
	'rank'      => 'adminrnk',
	'usrflags'  => 'word_flags',
	'language'  => 'word_language',
	'templatesel' => 'word_template'
	//'group_id'  => 'word_group_id'
	//'timezone'  => 'word_timezone'
);
if (!array_key_exists($searchop, $searchop_allow)) {
	$searchop = '';
}
if ($order != 'ASC' && $order != 'DESC') {
	$order = '';
}
$email_search_clause = '';
if ($searchop == 'email') {
	if (empty($OekakiU)) {
		// Hide e-mail from non-members
		$searchop = '';
	} elseif (!$flags['mod']) {
		// Hide e-mail if requested by member
		$email_search_clause = "AND `email_show`='1'";
	}
}



// Verify search
if (empty($pageno)) {
	$pageno = 0;
}
if ($mypages < 5 || $mypages > 100) {
	$mypages = $cfg['menu_pages'];
}
if (!$cfg['menu_pages']) {
	$mypages = 25;
}


require 'header.php';


// Search query
$active_members = 0;
if (!empty($searchop)) {
	$searchqry = "SELECT * FROM {$db_mem}oekaki WHERE `{$searchop}` != '' {$email_search_clause} AND `{$searchop}` LIKE '%{$searchstr}%' ORDER BY `{$searchop}` {$order} LIMIT ".($pageno * $mypages).', '.$mypages;
	$searchqry2 = "SELECT COUNT(usrname) FROM {$db_mem}oekaki WHERE `{$searchop}` != '' AND  `{$searchop}` LIKE '%{$searchstr}%'";

	// Special cases:
	if ($searchop == 'joindate' || $searchop == 'lastlogin') {
		// Add user name to search
		trim($searchstr) != ''
			? $where_clause = "WHERE `usrname` != '' AND `usrname` LIKE '%{$searchstr}%'"
			: $where_clause = '';

		$searchqry = "SELECT * FROM {$db_mem}oekaki {$where_clause} ORDER BY `{$searchop}` {$order} LIMIT ".($pageno * $mypages).', '.$mypages;
		$searchqry2 = "SELECT COUNT(usrname) FROM {$db_mem}oekaki {$where_clause}";
	}
	if ($searchop == 'age') {
		// Reverse ORDER so sorting will be by age, not date
		$age_order = 'DESC';
		if ($order == 'DESC') {
			$age_order = 'ASC';
		}
		$searchqry = "SELECT * FROM {$db_mem}oekaki WHERE `age` != '' ORDER BY `age` {$age_order} LIMIT ".($pageno * $mypages).', '.$mypages;
		$searchqry2 = "SELECT COUNT(usrname) FROM {$db_mem}oekaki WHERE `age` != ''";
	}
} else {
	$searchqry = "SELECT * FROM {$db_mem}oekaki ORDER BY `usrname` ASC LIMIT ".($pageno * $mypages).', '.$mypages;
	$searchqry2 = "SELECT COUNT(usrname) FROM {$db_mem}oekaki";

	// Get active members
	if ($cfg['kill_user'] < $active_login_time) {
		$active_search = db_query("SELECT COUNT(usrname) FROM {$db_mem}oekaki WHERE (DATE_ADD(lastlogin, INTERVAL {$active_login_time} DAY) >= NOW())");
		$active_members = (int) db_result($active_search, 0);
	}
}


$search = db_query($searchqry);
$search2 = db_query($searchqry2);
$searchrows = db_num_rows($search);
$totalrows = db_result($search2, 0);
$pages = ceil($totalrows / $mypages);


?>
<div id="contentmain">
	<form name="form1" method="get" action="memberlist.php">

		<div class="infotable">
			<table style="width: 100%">
			<tr>
			<td>
				<?php
$pages_link = 'memberlist.php?pageno={page_link}';

if ($searchop) $pages_link .= '&amp;searchop='.urlencode($searchop);
if ($order) $pages_link .= '&amp;order='.urlencode($order);
if ($searchstr) $pages_link .= '&amp;searchstr='.urlencode($searchstr);
if ($mypages) $pages_link .= '&amp;pages='.$mypages;

echo quick_page_numbers(0, $pageno, $pages, 4, $pages_link)."\n";
?>
			</td>

			<td align="right">
<?php if (!empty($searchop)) { ?>
				<?php tt('match_search', $totalrows);?> 
<?php } else { ?>
				<?php tt('member_stats', $totalrows, $active_members, $active_login_time);?> 
<?php } ?>
			</td>
			</tr>
			</table>
		</div>
		<br />


<?php if ($pageno == 0) { ?>
		<div class="header">
			<?php tt('word_administrators');?> 
		</div>
		<div class="infotable">
			<table class="infomain">
			<tr>
			<td><strong><?php tt('word_username');?></strong></td>
			<td><strong><?php tt('word_status');?></strong></td>
			<td><strong><?php tt('word_website');?></strong></td>
			<td><strong><?php tt('word_email');?></strong></td>
			<td><strong><?php tt('word_pictures');?></strong></td>
			<td><strong><?php tt('word_comments');?></strong></td>
			</tr>

<?php // Owner
			$admin_sql = db_query("SELECT * FROM {$db_mem}oekaki WHERE `rank`=".RANK_OWNER." ORDER BY `usrname` ASC");
			$admin_rows = db_num_rows($admin_sql);
			for ($i = 0; $i < $admin_rows; $i++) {
				$row = db_fetch_array($admin_sql);
?>
			<tr>
			<td>
				<a onclick="openWindow('profile.php?user=<?php echo urlencode($row['usrname']);?>', 400, 500); return false;" href="profile.php?user=<?php echo urlencode($row['usrname']);?>"><?php echo w_html_chars($row['usrname']);?></a>
			</td>
			<td><?php tt('type_owner');?></td>
<?php if (strlen($row['url']) > 8) { ?>
			<td><a href="<?php echo w_html_chars($row['url']);?>"><?php tt('ml_web_link');?></a></td>
<?php } else { ?>
			<td><?php tt('ml_no_link');?></td>
<?php }
if (!empty($OekakiU)) {
	$e_temp = email_code($row['email'], true);
	?>
			<td><a href="mailto:<?php echo $e_temp;?>?subject=Oekaki"><?php echo $e_temp;?></a></td>
<?php } else { ?>
			<td><?php echo email_code($row['email'], false); ?></td>
<?php } ?>
			<td><a href="index.php?artist=<?php echo urlencode($row['usrname']); ?>"><?php echo $row['piccount']?></a></td>
			<td><?php echo $row['commcount']?></td>
			</tr>
<?php
			}
?>

			<tr><td colspan="6">&nbsp;</td></tr>

<?php // Other Admins
			$admin_sql = db_query("SELECT * FROM {$db_mem}oekaki WHERE (`rank` >= ".RANK_MOD.") AND (`rank` < ".RANK_OWNER.") ORDER BY `usrname` ASC");
			$admin_rows = db_num_rows($admin_sql);
			for ($i = 0; $i < $admin_rows; $i++) {
				$row = db_fetch_array($admin_sql);
				if ($row['rank'] < RANK_OWNER) {
?>
			<tr>
			<td>
				<a onclick="openWindow('profile.php?user=<?php echo urlencode($row['usrname']);?>', 400, 500); return false" href="profile.php?user=<?php echo urlencode($row['usrname']);?>"><?php echo w_html_chars($row['usrname']);?></a>
			</td>
			<td><?php
				switch ($row['rank']) {
					case RANK_SADMIN:
						echo t('type_sadmin');
						break;
					case RANK_ADMIN:
						echo t('type_admin');
						break;
					default:
						echo 'Moderator';
						break;
				}
			?></td>
<?php if (strlen($row['url']) > 8) { ?>
			<td><a href="<?php echo $row['url']?>"><?php tt('ml_web_link');?></a></td>
<?php } else { ?>
			<td><?php tt('ml_no_link');?></td>
<?php }
if (!empty($OekakiU)) {
	$e_temp = email_code($row['email'], true);
	?>
			<td><a href="mailto:<?php echo $e_temp;?>?subject=Oekaki"><?php echo $e_temp;?></a></td>
<?php } else { ?>
			<td><?php echo email_code($row['email'], false);?></td>
<?php } ?>
			<td><a href="index.php?artist=<?php echo urlencode($row['usrname']); ?>"><?php echo $row['piccount']?></a></td>
			<td><?php echo $row['commcount']?></td>
			</tr>
<?php
				}
			}
?>
			</table>
		</div>
		<br />


<?php } ?>
		<div class="header">
			<div style="text-align: left; padding: 5px;">
				<strong><?php tt('sortby');?></strong>
				<select name="searchop" class="multiline">
<?php // Print drop-down serach menu
	foreach ($searchop_allow as $key => $val) {
?>
					<option value="<?php echo $key;?>"<?php if ($searchop == $key) echo $hsel; ?>><?php tt($val);?></option>
<?php
	}
?>
				</select>

				<strong><?php tt('word_order');?></strong>
				<select name="order" class="multiline">
					<option value="ASC"<?php if ($order == 'ASC') { echo $hsel; } ?>><?php tt('word_ascending');?></option>
					<option value="DESC"<?php if ($order == 'DESC') { echo $hsel; } ?>><?php tt('word_descending');?></option>
				</select>

				<strong><?php tt('perpage');?></strong>
				<select name="pages" class="multiline">
<?php if ($mypages == $cfg['menu_pages']) { ?>
					<option value="<?php echo $cfg['menu_pages']?>" selected="selected"><?php echo $cfg['menu_pages']?> (<?php tt('word_default');?>)</option>
<?php } else { ?>
					<option value="<?php echo $mypages;?>" selected="selected"><?php echo $mypages;?> (Selected)</option>
<?php } ?>
					<option value="10">10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select>

				<strong><?php tt('word_keywords');?></strong>
				<input type="text" name="searchstr" class="txtinput"<?php if (!empty($searchstr)) { echo ' value="'.w_html_chars($searchstr).'"'; } ?> />

				<input type="submit" name="search" value="<?php tt('word_search');?>" class="submit" />
			</div>
		</div>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td><strong><?php tt('word_username');?></strong></td>
			<td><strong><?php tt('word_website');?></strong></td>
<?php if (empty($OekakiU)) { ?>
			<td><strong><?php tt('word_email');?> <?php tt('plzlogin');?></strong></td>
<?php } else { ?>
			<td><strong><?php tt('word_email');?></strong></td>
<?php } ?>
			<td><strong><?php tt('word_pictures');?></strong></td>
			<td><strong><?php tt('word_comments');?></strong></td>
<?php
if ($searchop != '' && $searchop != 'usrname' && $searchop != 'email' && $searchop != 'piccount' && $searchop != 'commcount') {
?>
			<td>
				<strong><?php tt($searchop_allow[$searchop]);?></strong>
			</td>
<?php } ?>
			</tr>

<?php
for ($i = 0; $i < $searchrows; $i++) {
	$row = db_fetch_array($search);
	?>
			<tr>
			<td>
				<a onclick="openWindow('profile.php?user=<?php echo urlencode($row['usrname']);?>', 400, 500); return false;" href="profile.php?user=<?php echo urlencode($row['usrname']);?>"><?php echo w_html_chars($row['usrname']);?></a><?php if (strstr($row['usrflags'], 'P')) { echo ' <small>('.t('word_pending').')</small>'."\n"; } ?> 
			</td>
			<td>
<?php if (strlen($row['url']) > 8 && strstr($row['url'], '://')) { ?>
				<a href="<?php echo $row['url'];?>"><?php tt('ml_web_link');?></a>
<?php } else { ?>
				<?php tt('ml_no_link');?> 
<?php } ?>
			</td>
			<td><?php
			// Only registered members can see e-mails
			if (empty($OekakiU)) {
				tt('ml_no_email');
			} else {
				$e_click   = email_code($row['email'], true);
				$e_display = email_code($row['email'], false);
				echo "\n\t\t\t\t";

				// Obscure e-mail to non-admins?
				if (!$row['email_show']) {
					echo "(".t('word_na').") ";
				}
				if ($flags['mod']) {
					echo "<a href=\"mailto:{$e_click}\">{$e_click}</a>";
				} elseif ($row['email_show']) {
					echo "<a href=\"mailto:{$e_click}\">{$e_display}</a>";
				}

				echo "\n\t\t\t";
			}
			?></td>
			<td><?php
if ($row['piccount'] > 0) {
	echo "\n";
?>
				<a href="index.php?artist=<?php echo urlencode($row['usrname']);?>"><?php echo $row['piccount'];?></a>
<?php
	echo "\n\t\t\t";
} else {
	echo '0';
}
			?></td>
			<td><?php echo $row['commcount'];?></td>
<?php
	if (!empty($searchop) && $searchop != 'usrname' && $searchop != 'email' && $searchop != 'piccount' && $searchop != 'commcount') {
		if ($searchop == 'age') {
			$the_date = $row['age'];
			$the_age  = get_age($the_date);

			if (strpos($the_date, '-') === false) {
				// No birthday
				$the_b_day = '';
			} else {
				$the_b_day = ' <small>('.$the_date.')</small>';
			}

			echo '			<td>'.$the_age.$the_b_day."</td>\n";
		} else {
			echo '			<td>'.w_html_chars($row[$searchop])."</td>\n";
		}
	}
	echo '			</tr>'."\n";
}
?>
			</table>
		</div>
	</form>
</div>


<?php

include 'footer.php';