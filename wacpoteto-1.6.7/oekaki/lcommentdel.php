<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.0x - Last modified 2009-09-13 (x:2011-01-11)
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


// Input
// $pageno


include 'header.php';


$result5 = db_query("SELECT ID_3 FROM {$db_p}oekakicmt WHERE usrname='$OekakiU'");
$pages = ceil(db_num_rows($result5) / 20);

$result2 = db_query("SELECT * FROM {$db_p}oekakicmt WHERE usrname='$OekakiU' ORDER BY postdate DESC LIMIT ".($pageno * 20).', 20');
$rownum = db_num_rows($result2);


?>
<div id="contentmain">
	<div class="infotable">
		<?php echo quick_page_numbers(0, $pageno, $pages, 0, 'lcommentdel.php?pageno={page_link}')."\n"; ?>
	</div>
	<br />



	<h1 class="header">
		<?php tt('lcommdb_title');?> 
	</h1>

	<div class="infotable">
		<table class="infomain">
<?php
$i = 0;
while ($i < $rownum) {
	$row = db_fetch_array($result2);
	?>
		<tr>
		<td valign="top">
			<?php echo $row['PIC_ID'];?>&nbsp;-
		</td>
		<td valign="top">
			<a href="editcomm.php?id_3=<?php echo $row['ID_3'];?>"><?php tt('word_edit');?></a>
		</td>
		<td valign="top">
			<?php echo $row['postdate'];?> 
		</td>
		<td>
			<strong><?php tt('word_comment');?>:</strong> <?php echo nifty2_convert($row['comment']);?>
		</td>
		<td valign="top">
			<a href="functions.php?mode=udelcmt&amp;cmtno=<?php echo $row['ID_3'];?>"><?php tt('word_delete');?></a>
		</td>
		</tr>
<?php
	$i++;
}
?>
		</table>
	</div>
</div>


<?php

include 'footer.php';