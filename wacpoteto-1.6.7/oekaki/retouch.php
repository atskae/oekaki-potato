<?php
/*
Wacintaki Poteto - Copyright 2004-2016 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.4 - Last modified 2016-07-28
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';
include 'paintsave.php';


// Pictures folder locked?
if (!is_writable($cfg['op_pics'])) {
	report_err( t('picfolocked'));
}


// Form submit
if ($action == 'edit_with_pass') {
	$slot = w_gpc('edit', 'i+');
	$password = w_gpc('pw');

	// Fetch picture info
	$result = db_query("SELECT * FROM {$db_p}oekakidta WHERE PIC_ID={$slot}");
	if (!$result) {
		report_err(t('coulntfetipic').' #'.$slot);
	}
	$row = db_fetch_array($result);

	// Limit password to 12 chars
	$app_pass = '';
	if (!empty($password)) {
		$password = stripslashes($password);
		$password = substr($password, 0, 12);
		$password = w_transmission_hash($password);
		$app_pass = '&pw='.urlencode($password);
	}

	// Set up applet
	$useapp = '';
	$app_params = '';

	switch ( (int) $row['datatype']) {
		case 0:
			$useapp = 'noteBBS.php';
			break;
		case 1:
			$useapp = 'oekakiBBS.php';
			break;
		case 2:
			$useapp = 'shiBBS.php';
			break;
		case 3:
			$useapp = 'shiBBS.php';
			$datatype = 3;
			$app_params = '&tools=pro&cursor=cursor';
			break;
		case 4:
			$useapp = 'chibipaint.php';
			break;
		case 5:
			$useapp = 'chickenpaint.php';
			break;
		default:
			all_done();
	}

	all_done($useapp.'?edit='.$slot.$app_params.$app_pass);
}


// Verify
if (!isset($_GET['edit'])) {
	report_err( t('noeditno'));
}
$edit = w_gpc('edit', 'i+');


// Get info on picture
$result = db_query("SELECT PIC_ID, password FROM {$db_p}oekakidta WHERE PIC_ID='$edit'");
if (!$result) {
	report_err(t('err_readpic'), $edit);
}

$row = db_fetch_array($result);


/* START HTML */
include 'header.php';


// Print modded draw interface
?>
<div id="contentmain">

	<!-- Retouch -->
	<h1 class="header">
		<?php tt('word_retouch');?> 
	</h1>

	<div class="infotable" style="text-align: center;">
		<form action="retouch.php" method="post">
			<input name="edit" value="<?php echo $edit;?>" type="hidden" />

<?php if ($row['password'] == 'public') { ?>
			<p><?php tt('picavailab');?></p>
			<p><?php tt('retouchmsg2');?></p>
			<p><?php tt('retouchmsg3');?></p>
<?php } else { ?>
			<p><?php tt('retouchmsg4');?></p>
			<p><?php tt('retouchmsg5');?>!</p>

			<p>
				<?php tt('install_password');?> 
				<input name="pw" type="text" class="txtinput" size="20" />
			</p>
<?php } ?>

			<br />
			<p class="subtext">
				<input type="hidden" name="action" value="edit_with_pass" />
				<input type="submit" name="Submit" value="<?php tt('word_continue');?>" class="submit" />
			</p>
		</form>
	</div>
</div>


<?php

include 'footer.php';