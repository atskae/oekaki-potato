<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2016 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.4 - Last modified 2016-07-28
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';

// Input
// $pageno


if (empty($OekakiU)) {
	report_err( t('loginerr'));
}


// Admins can recover pics graphically
$selfrecover = "AND usrname='$OekakiU'";

if ($flags['admin']) {
	$selfrecover = '';
}


include 'header.php';


$result5 = db_query("SELECT ID_2 FROM {$db_p}oekakidta WHERE postlock=0 ".$selfrecover);
$pages = ceil(db_num_rows($result5) / 10);

$result2 = db_query("SELECT * FROM {$db_p}oekakidta WHERE postlock=0 {$selfrecover} ORDER BY postdate DESC LIMIT ".($pageno * $cfg['pic_pages']).', '.$cfg['pic_pages']);
$rownum = db_num_rows($result2);


?>
<div id="contentmain">
	<div class="infotable">
		<?php echo quick_page_numbers(0, $pageno, $pages, 4, 'recover.php?pageno={page_link}')."\n"; ?>
	</div>
	<br />


	<!-- Recovery -->
	<h1 class="header">
		<?php tt('picrecover_title');?> 
	</h1>

	<div class="infotable">
<?php if ($rownum > 0) { ?>
		<table class="infomain">
<?php }

$i = 0;
while ($i < $rownum) {
	$row = db_fetch_array($result2);
	$broken = false;
?>
		<tr>
		<td>
			#<?php echo $row['PIC_ID'];?> 
		</td>
		<td>
<?php
if (file_exists($p_pic.$row['PIC_ID'].'.'.$row['ptype'])) {
	if (!empty($row['ttype'])) { ?>
			<a href="<?php echo $p_pic.$row['PIC_ID'].'.'.$row['ptype']; ?>"><img src="<?php echo $t_pic.$row['PIC_ID'].'.'.$row['ttype']; ?>" class="imghover" width="100" alt="thumb" /></a>
<?php
	} else {
?>
			<a href="<?php echo $p_pic.$row['PIC_ID'].'.'.$row['ptype']; ?>"><img src="<?php echo $p_pic.$row['PIC_ID'].'.'.$row['ptype']; ?>" class="imghover" width="100" alt="pic" /></a>
<?php
	}
} else {
			$broken = true;
?>
			<?php tt('brokenimage');?> 
<?php
}
?>
		</td>
		<td>
<?php if ($row['animation']) {
	$viewani_size = array(75 , 175);

	if ($row['datatype'] == 1) {
		$viewani_size = array(100, 185);
	}
?>
			<a href="viewani.php?xcord=<?php echo $row['px']; ?>&amp;ycord=<?php echo $row['py']; ?>&amp;recno=<?php echo $row['ID_2']; ?>" onclick="openWindow('viewani.php?xcord=<?php echo $row['px']; ?>&amp;ycord=<?php echo $row['py']; ?>&amp;recno=<?php echo $row['ID_2']; ?>', '<?php echo $row['px'] + $viewani_size[0]; ?>', '<?php echo $row['py'] + $viewani_size[1];?>'); return false"><?php tt('word_animations');?></a><br />
<?php } else { ?>
			<?php tt('noanim');?><br />
<?php }
if ($row['edittime'] < 60) { ?>
			<?php tt('recover_sec', $row['edittime']);?> 
<?php } else { ?>
			<?php tt('recover_min', ceil($row['edittime'] / 60));?> 
<?php } ?>
		</td>
		<td>
			<?php
				// Get time remaining
				$result9 = db_query("SELECT UNIX_TIMESTAMP(postdate) AS `remaining` FROM `{$db_p}oekakidta` WHERE `PIC_ID`={$row['PIC_ID']}");
				$my_timestamp = db_result($result9);

				echo date($datef['admin_edit'], $my_timestamp)."<br />\n";

				$my_remaining = $cfg['safety_storetime'] - ceil( (time() - $my_timestamp) / 86400);
				tt('recovery_days_remaining', $my_remaining);
			?>
		</td>
		<td>
<?php
	if ($row['usrname'] != $OekakiU) {
		if ($broken !== true) {
?>
			<a href="editpic.php?picno=<?php echo $row['PIC_ID'];?>"><?php tt('recover_for', w_html_chars($row['usrname']));?></a>
<?php
		}
?>
		</td>
		<td>
<?php
		if ($broken !== true) {
?>
			<a href="delconf.php?mode=delr&amp;picno=<?php echo $row['PIC_ID'];?>"><?php tt('word_delete');?></a>
<?php
		}
?>
<?php
	} else {
		// Get applet type
		switch ($row['datatype']) {
			case '0':
				$use_app = 'noteBBS.php?';
				break;
			case '1':
				$use_app = 'oekakiBBS.php?';
				break;
			case '2':
				$use_app = 'shiBBS.php?';
				break;
			case '3':
				$use_app = 'shiBBS.php?tools=pro&amp;';
				break;
			case '4':
				$use_app = 'chibipaint.php?';
				break;
			case '5':
				$use_app = 'chickenpaint.php?';
				break;
		}

		if ($broken !== true) {
?>
			<a href="<?php echo $use_app;?>edit=<?php echo $row['PIC_ID'];?>&amp;anim=<?php echo $row['animation'];?>"><?php tt('word_retouch');?></a>
<?php
		}
?>
		</td>
		<td>
<?php
		if ($broken !== true) {
?>
			<a href="editpic.php?picno=<?php echo $row['PIC_ID'];?>"><?php tt('postnow');?></a>
<?php
		}
?>
		</td>
		<td>
			<a href="delconf.php?mode=del&amp;return=recover&amp;picno=<?php echo $row['PIC_ID'];?>"><?php tt('word_delete');?></a>
<?php
	}
?>
		</td>
		</tr>
<?php
	$i++;
};

if ($rownum > 0) { ?>
		</table>
<?php } ?>
	</div>
</div>


<?php

include 'footer.php';