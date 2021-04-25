<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6 - Last modified 2011-01-24
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

// Input
// $pageno


if (!$flags['admin']) {
	report_err( t('errdelpic'));
}


include 'header.php';


$result5 = db_query("SELECT ID_2 FROM {$db_p}oekakidta WHERE postlock=1");
$pages = ceil(db_num_rows($result5) / 10);

$result2 = db_query("SELECT * FROM {$db_p}oekakidta WHERE postlock=1 ORDER BY postdate DESC LIMIT ".($pageno * 10).', 10');
$rownum = db_num_rows($result2);


?>
<div id="contentmain">
	<div class="infotable">
		<?php echo quick_page_numbers(0, $pageno, $pages, 4, 'delpics.php?pageno={page_link}')."\n"; ?>
	</div>
	<br />


	<h1 class="header">
		<?php tt('gpicdb_title');?> 
	</h1>

	<div class="infotable">
		<table class="infomain">
<?php
$i = 0;
while ($i < $rownum) {
	$row = db_fetch_array($result2);
	?>
		<tr>
		<td>
			<?php echo $row['PIC_ID'];?>&nbsp;-
		</td>
		<td valign="top" style="text-align: center;">
<?php if (!empty($row['ttype'])) { ?>
			<img src="<?php echo $t_pic.$row['PIC_ID'].'.'.$row['ttype']; ?>" class="imghover" width="100" alt="thumb" />
<?php } else { ?>
			<img src="<?php echo $p_pic.$row['PIC_ID'].'.'.$row['ptype']; ?>" class="imghover" width="100" alt="pic" />
<?php } ?>
		</td>
		<td>
			<?php echo w_html_chars($row['usrname']);?> 
		</td>
		<td>
			<?php echo $row['IP'];?> 
<?php if (!empty($row['hostname']) || $row['hostname'] != 'invalid') { ?>
		<br /><?php echo $row['hostname'];?> 
<?php } ?>
		</td>
		<td>
			<?php echo $row['postdate'];?> 
		</td>
		<td>
			<?php
if (trim($row['title']) == '' || $row['title'] == 'No Title') {
	tt('no_pic_title');
} else {
	echo nifty2_convert($row['title']);
}
?> 
		</td>
		<td>
			<?php echo nifty2_convert($row['comment']);?> 
		</td>
		<td>
			<a href="delconf.php?mode=dela&amp;picno=<?php echo $row['PIC_ID'];?>"><?php tt('word_delete');?></a>
		</td>
		</tr>
<?php
	$i++;
};
?>
		</table>
	</div>
</div>


<?php

include 'footer.php';