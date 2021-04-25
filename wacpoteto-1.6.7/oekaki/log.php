<?php
/*
Wacintaki Poteto - Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6x - Last modified 2011-02-06 (x:2015-02-12)

WARNING:
Log may contain SQL error messages, so for security reasons, do not allow access to anyone without admin access (as is possible with 'testinfo.php').
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


// Init
$bad_categories = array(WLOG_FAIL, WLOG_SQL_FAIL, WLOG_SECURITY, WLOG_BAN, WLOG_DELETE_USER);


// Input
// $pageno
$mypages = 50;


if (!$flags['admin']) {
	report_err( t('no_log_access'));
}


include 'header.php';


// Process log
$my_board = db_escape($cfg['op_title']);

$sql_rows = "SELECT * FROM {$db_mem}oekakilog WHERE `board`='{$my_board}' ORDER BY `ID` DESC LIMIT ".($pageno * $mypages).', '.$mypages;

$sql_entries = "SELECT COUNT(`ID`) FROM {$db_mem}oekakilog WHERE `board`='{$my_board}'";


// Run search
$result_rows    = db_query($sql_rows);
$result_entries = db_query($sql_entries);
$rows_searched  = db_num_rows($result_rows);
$rows_total     = (int) db_result($result_entries, 0);
$pages = ceil($rows_total / $mypages);


?>
<div id="contentmain">
	<form name="form1" method="get" action="memberlist.php">

		<div class="infotable">
			<table style="width: 100%">
			<tr>
			<td>
				<?php
$pages_link = 'log.php?pageno={page_link}';

if ($mypages) $pages_link .= '&amp;pages='.$mypages;

echo quick_page_numbers(0, $pageno, $pages, 4, $pages_link)."\n";
?>
			</td>

			<td align="right">
				<em><?php tt('log_entries', $rows_total);?></em>
			</td>
			</tr>
			</table>
		</div>
		<br />


		<div class="infotable">
			<table class="infomain">
			<tr>
			<td><strong><?php tt('word_category');?></strong></td>
			<td><strong><?php tt('word_comment');?></strong></td>
			<td><strong><?php tt('word_username');?></strong></td>
			<td><strong><?php tt('log_peer');?></strong></td>
			<td><strong><?php tt('word_date');?></strong></td>
			</tr>

<?php
for ($i = 0; $i < $rows_searched; $i++) {
	$row = db_fetch_array($result_rows); ?>
			<tr>
			<td>
<?php if (in_array($row['category'], $bad_categories)) { ?>
				<span style="color: white; background-color: black;"><strong><?php echo w_html_chars( t('l_'.$row['category']));?></strong></span>
<?php } else { ?>
				<?php echo w_html_chars( t('l_'.$row['category']));?> 
<?php } ?>
			</td>
			<td>
				<?php echo w_html_chars(log_decode($row['value']));?> 
			</td>
			<td>
<?php if (!empty($row['member']) && substr($row['member'], 0, 1) != '(') { ?>
				<a onclick="openWindow('profile.php?user=<?php echo urlencode($row['member']);?>', 400, 500); return false;" href="profile.php?user=<?php echo urlencode($row['member']);?>"><?php echo w_html_chars($row['member']);?></a>
<?php } else { ?>
				<?php echo w_html_chars($row['member']);?> 
<?php } ?>
			</td>
			<td>
<?php if (empty($row['affected'])) { ?>
				<?php tt('word_na');?>
<?php } elseif ($row['member'] == $row['affected']) { ?>
				<?php tt('log_self', (strtolower($user['gender']) == 'female') ? 2 : 1); ?> 
<?php } elseif ($row['affected'] == 'Guest') { ?>
				Guest
<?php } elseif (t_exists($row['affected'])) { ?>
				<?php tt($row['affected']); ?> 
<?php } else { ?>
				<a onclick="openWindow('profile.php?user=<?php echo urlencode($row['affected']);?>', 400, 500); return false;" href="profile.php?user=<?php echo urlencode($row['affected']);?>"><?php echo w_html_chars($row['affected']);?></a>
<?php } // END IF ?>
			</td>
			<td>
				<?php echo $row['date'];?> 
			</td>
			</tr>
<?php } // END LOOP ?>
			</table>
		</div>
	</form>
</div>


<?php

include 'footer.php';