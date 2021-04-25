<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.3 - Last modified 2015-08-21
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


include 'header.php';


// Input
$rules = w_gpc('rules', 'i');


// -----------
// Print rules
//
if (empty($rules) && $cfg['reg_rules'] != 'no') {
	?>
<!-- Begin Rules -->
<div id="contentmain">

	<br />
	<div class="infotable" style="margin: 0 50px 0 50px; text-align: center;">
		<?php tt('plzreadrulereg');?> 
	</div>
	<br />
	<br />

	<!-- Rules -->
	<h1 class="header">
		<?php tt('word_rules');?> 
	</h1>
	<div class="infotable">
<?php
	include $cfg['res_path'].'/rules.php';
?> 
	</div>
	<br />
	<br />

	<div class="infotable" style="margin: 0 50px 0 50px; text-align: center;">
		<?php tt('agreerulz');?>, <a href="<?php echo $_SERVER['PHP_SELF'].'?rules=1';?>"><?php tt('clickheregister');?></a>.
	</div>
	<br />
</div>
<!-- End Rules -->


<?php
	include 'footer.php';
	exit;
}


// ------------------
// Print confirmation
//
if ($rules == 2) {
	?>
<div id="contentmain">

	<!-- Confirmation -->
	<h1 class="header">
		<?php tt('regisubmit');?> 
	</h1>
	<div class="infotable">
		<div align="center">
<?php if ($cfg['approval'] != 'force') { ?>
			<?php tt('urgistra', $cfg['op_title']);?><br />
			<br />
<?php }
if ($cfg['approval'] == 'yes') { ?>
			<?php tt('aprovemsgyes');?><br />
<?php } elseif ($cfg['approval'] == 'no') { ?>
			<?php tt('aprovemsgno');?><br />
<?php } elseif ($cfg['approval'] == 'force') { ?>
			<?php tt('urgistra_approved', $cfg['op_title']);?><br />
<?php } ?>
			<br />
			<a href="index.php"><?php tt('returnbbs');?></a>
		</div>
	</div>
</div>


<?php
	include 'footer.php';
	exit;
}


// ------------------
// Print registration
//
?>
<div id="contentmain">

	<!-- Notes -->
	<h1 class="header">
		<?php tt('nbregister');?> 
	</h1>

	<div class="infotable">
		<p class="indent">
			<strong><?php tt('registertwice');?></strong>  <?php tt('regmsg1');?> 
		</p>

		<p class="indent">
			<?php tt('regmsg2');?> 
		</p>

		<p class="indent">
			<?php tt('regmsg3');?> 
		</p>

<?php if ($cfg['approval'] == 'yes') { ?>
		<p class="indent">
			<?php tt('regmsg4');?> 
		</p>

<?php } else { ?>
		<p class="indent">
			<?php tt('register_sub8');?> 
		</p>

		<p class="indent">
			<?php tt('regmsg5');?> 
		</p>

<?php } ?>
	</div>

	<br />

	<form name="form1" method="post" action="functions.php">
		<input name="action" type="hidden" value="register" />

		<!-- Registration -->
		<h1 class="header">
			<?php tt('register_title');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_username');?> 
			</td>
			<td class="infoenter" valign="top">
				<input id="username" name="username" type="text" class="txtinput" style="width: 100%;" onblur="checkName('username');" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_password');?> 
			</td>
			<td class="infoenter" valign="top">
				<input id="passwd" name="pass" type="password" size="30" class="txtinput" />
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('install_repassword');?> 
			</td>
			<td class="infoenter" valign="top">
				<input id="passwdnew" name="pass2" type="password" size="30" class="txtinput" onblur="checkPass('passwd', 'passwdnew');" />
			</td>
			</tr>

			<tr>
			<td colspan="2">
				&nbsp;
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_email');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="email" type="text" class="txtinput" style="width: 100%;" onblur="MM_validateForm('email','','RisEmail');return document.MM_returnValue" />
<?php if ($cfg['approval'] != 'yes') { ?>

				<p class="subtext">
					<strong><?php tt('register_sub2');?></strong>
				</p>

<?php } ?>
				<p class="subtext">
					<?php tt('regmsg6');?> 
				</p>
			</td>
			</tr>

			<tr>
			<td colspan="2">
				&nbsp;
			</td>
			</tr>

<?php if ($cfg['op_adult'] == 'yes') { ?>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('agestatement', MIN_AGE_ADULT);?> 
			</td>
			<td class="infoenter" valign="top">
<?php
for ($i = 0; $i < 3; $i++) {
	if ($datef['age_menu'][$i] == 'D') {
?>
				<?php tt('abbr_day');?> <input name="age_day" type="text" size="3" class="txtinput" />

<?php
	} elseif ($datef['age_menu'][$i] == 'Y') {
?>
				<?php tt('abbr_year');?> <input name="age_year" type="text" size="6" class="txtinput" />

<?php
	} else {
?>
				<?php tt('abbr_month');?> 
				<select name="age_month" class="txtinput">
					<option value="0">---</option>
					<option value="1"><?php tt('month_jan');?></option>
					<option value="2"><?php tt('month_feb');?></option>
					<option value="3"><?php tt('month_mar');?></option>
					<option value="4"><?php tt('month_apr');?></option>
					<option value="5"><?php tt('month_may');?></option>
					<option value="6"><?php tt('month_jun');?></option>
					<option value="7"><?php tt('month_jul');?></option>
					<option value="8"><?php tt('month_aug');?></option>
					<option value="9"><?php tt('month_sep');?></option>
					<option value="10"><?php tt('month_oct');?></option>
					<option value="11"><?php tt('month_nov');?></option>
					<option value="12"><?php tt('month_dec');?></option>
				</select>

<?php
	}
}
?>
				<p class="subtext">
					<?php tt('adultonlymsg');?> 
				</p>
			</td>
			</tr>

			<tr>
			<td colspan="2">
				&nbsp;
			</td>
			</tr>

<?php } ?>

			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_comments');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="comments" type="text" maxlength="80" class="txtinput" style="width: 100%;" />

				<p class="subtext">
					<em><?php tt('register_sub3');?></em>
				</p></td>
			</tr>

			<tr>
			<td colspan="2">
				&nbsp;
			</td>
			</tr>

			<tr>
			<td class="infoask" valign="top">
				<?php if ($cfg['require_art'] == 'no') { tt('reg_arturl_optional'); } else { tt('reg_arturl_required'); } ?> 
			</td>
			<td class="infoenter" valign="top">
<?php if ($cfg['require_art'] == 'yes') {
	?>
				<input id="artURL" name="artURL" type="text" value="http://" class="txtinput" style="width: 100%;" onblur="checkURL('artURL');" />

				<p class="subtext">
					<?php tt('register_sub4');?>  <strong><?php tt('register_sub5');?></strong>
				</p>
<?php } else {?>
				<input id="artURL" name="artURL" type="text" value="http://" class="txtinput" style="width: 100%;" />

				<p class="subtext">
					<?php tt('nbwebpage');?> 
				</p>
<?php } ?>
			</td>
			</tr>

			<tr>
			<td colspan="2">
				&nbsp;
			</td>
			</tr>

<?php
// Humanity test
$start = rand(1, 10);
$end = rand(1, 10);
$sum = $start + $end;

$mixup = array($sum, $sum+2, $sum+4, $sum+6, $sum+8);
shuffle($mixup);

// Make sure correct value is not in 1st position, else swap with 3rd
if ($mixup[0] == $sum) {
	$dummy    = $mixup[0];
	$mixup[0] = $mixup[3];
	$mixup[3] = $dummy;
}

?>
			<tr>
			<td class="infoask" valign="top">
				<?php tt('humanity_question_3_part', $start, '+', $end);?> 
			</td>
			<td class="infoenter" valign="top">
				<input type="hidden" name="x_test" value="<?php echo $start;?>" />
				<select name="e_test" class="txtinput">
<?php
$answer_flag = 0;
foreach ($mixup as $out) { ?>
					<option value="<?php echo $out;?>"<?php if ($out != $sum && $answer_flag == 0) { echo ' selected="selected"'; $answer_flag = 1;} ?>><?php echo $out;?></option>
<?php } ?>
				</select>
				<input type="hidden" name="y_test" value="<?php echo $end;?>" />
				<input type="hidden" name="r_language" value="<?php echo $language;?>" />

				<p class="subtext">
					<em><?php tt('humanity_notify_sub');?></em>
				</p>
			</td>
			</tr>
			</table>
		</div>
		<br />



		<!-- Submit -->
		<h1 class="header">
			<?php tt('subregist');?> 
		</h1>

		<div class="infotable">
			<p style="text-align: center;">
				<input name="register" type="submit" value="<?php tt('word_submit');?>" class="submit" onclick="MM_validateForm('email','','RisEmail');return document.MM_returnValue" />
			</p>
		</div>
	</form>
</div>


<?php

include 'footer.php';