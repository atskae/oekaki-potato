<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2018 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.5 - Last modified 2018-01-23
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


include 'header.php';


function faq_get_gender(&$in) {
	// If any males in array, return 1, else 2
	$gender_flag = 2;
	foreach ($in as $item) {
		if (strtolower($item['gender']) == 'male') {
			$gender_flag = 1;
			break;
		}
	}
	return $gender_flag;
}


// Get the owner(s)
$my_owners = array();
$my_owners_num = 0;
$result = db_query("SELECT email, usrname, gender, usrflags FROM {$db_mem}oekaki WHERE rank = ".RANK_OWNER." ORDER BY usrname ASC");
if ($result) {
	$my_owners_num = db_num_rows($result);
	if ($my_owners_num > 0) {
		while ($row = db_fetch_array($result)) {
			$my_owners[] = $row;
		}
	}
	$my_owners_gender = faq_get_gender($my_owners);
}


// Admins and Superadmins
$my_admins = array();
$my_admins_num = 0;
$result = db_query("SELECT email, usrname, gender, usrflags FROM {$db_mem}oekaki WHERE rank >= ".RANK_ADMIN." AND rank <= ".RANK_SADMIN." ORDER BY usrname ASC");
if ($result) {
	$my_admins_num = db_num_rows($result);
	if ($my_admins_num > 0) {
		while ($row = db_fetch_array($result)) {
			$my_admins[] = $row;
		}
	}
	$my_admins_gender = faq_get_gender($my_admins);
}


// Moderators
$my_mods = array();
$my_mods_num = 0;
$result = db_query("SELECT email, usrname, gender, usrflags FROM {$db_mem}oekaki WHERE rank = ".RANK_MOD." ORDER BY usrname ASC");
if ($result) {
	$my_mods_num = db_num_rows($result);
	if ($my_mods_num > 0) {
		while ($row = db_fetch_array($result)) {
			$my_mods[] = $row;
		}
	}
	$my_mods_gender = faq_get_gender($my_mods);
}


?>

<div id="contentmain">
	<h1 class="header">
		<?php tt('faq_title');?> 
	</h1>
	
	<div class="infotable">
		<p>
			<?php tt('faq_curver', $version);?> 
		</p>

		<p>
			<?php
			if ($cfg['kill_user'] > 0) {
				echo t('faq_autoset', $cfg['kill_user'])."\n";
			} else {
				echo t('faq_noset')."\n";
			}
?>
		</p>

		<p>
			<a href="http://www.java.com"><img src="getjava.gif" class="imghover" alt="[Java]" title="<?php tt('faq_dl_java');?>" /></a>
			<a href="http://www.cellosoft.com/sketchstudio/"><img src="jtablet.jpg" class="imghover" alt="[JTablet]" title="<?php tt('faq_dl_jtablet');?>" /></a>
		</p>

		<hr style="display: block;" />


		<!-- TOC -->
		<h4><?php tt('faq_toc');?></h4>
		<ul>
<?php
			if (is_array($lang['faq_question'])) {
				$i = 0;
				while ($i < count($lang['faq_question'])) {
				?>
			<li><a href="#faq_<?php echo "$i";?>"><?php echo $lang['faq_question'][$i];?></a></li>
<?php
					$i++;
				}
			}
			?>
		</ul>
		<ul>
<?php
	if ($my_owners_num > 0) { ?>
			<li><a href="#q1"><?php tt('faq_questionA', $my_owners_num, $my_owners_gender);?></a></li>
<?php
	}
	if ($my_admins_num > 0) { ?>
			<li><a href="#q2"><?php tt('faq_questionB', $my_admins_num, $my_admins_gender);?></a></li>
<?php
	}
	if ($my_mods_num > 0) { ?>
			<li><a href="#q3"><?php tt('faq_questionC', $my_mods_num, $my_mods_gender);?></a></li>
<?php
	}
?>
		</ul>

		<hr style="display: block;" />


		<!-- FAQ Q&A -->
<?php
		if (is_array($lang['faq_question'])) {
			$i = 0;
			while ($i < count($lang['faq_question'])) {
			?>
			<h4 id="faq_<?php echo "$i";?>"><?php echo $lang['faq_question'][$i];?></h4>
			<blockquote>
				<?php echo $lang['faq_answer'][$i];?>
			</blockquote>

<?php
				$i++;
			}
		}
		?>

		<hr style="display: block;" />


<?php
if ($my_owners_num > 0) {
?>
		<!-- FAQ Owner -->
		<h4 id="q1"><?php tt('faq_questionA', $my_owners_num, $my_owners_gender);?></h4>
		<blockquote>
<?php
	foreach ($my_owners as $my_out) {
?>
			~ <a onclick="openWindow('profile.php?user=<?php echo urlencode($my_out['usrname']);?>', 300, 400); return false" href="profile.php?user=<?php echo urlencode($my_out['usrname']);?>"><?php echo w_html_chars($my_out['usrname']);?></a><br />
<?php } ?>
		</blockquote>


<?php }

if ($my_admins_num > 0) {
?>
		<!-- FAQ Admins -->
		<h4 id="q2"><?php tt('faq_questionB', $my_admins_num, $my_admins_gender);?></h4>
		<blockquote>
<?php
	foreach ($my_admins as $my_out) {
?>
			~ <a onclick="openWindow('profile.php?user=<?php echo urlencode($my_out['usrname']);?>', 300, 400); return false" href="profile.php?user=<?php echo urlencode($my_out['usrname']);?>"><?php echo w_html_chars($my_out['usrname']);?></a><br />
<?php } ?>
		</blockquote>


<?php }

if ($my_mods_num > 0) {
?>
		<!-- FAQ Mods -->
		<h4 id="q3"><?php tt('faq_questionC', $my_mods_num, $my_mods_gender);?></h4>
		<blockquote>
<?php
	foreach ($my_mods as $my_out) {
?>
			~ <a onclick="openWindow('profile.php?user=<?php echo urlencode($my_out['usrname']);?>', 300, 400); return false" href="profile.php?user=<?php echo urlencode($my_out['usrname']);?>"><?php echo w_html_chars($my_out['usrname']);?></a><br />
<?php } ?>
		</blockquote>


<?php } ?>
		<hr style="display: block;" />

		<p style="text-align: center;">
			<a href="http://www.NineChime.com/products/"><img src="madewac.gif" class="imghover" alt="[Get Wacintaki!]" title="Wacintaki and other stuff" /></a>
			<a href="http://suteki.nu"><img src="Poteto.gif" class="imghover" alt="[Get an OekakiPoteto for your site FREE!]" title="Powered by OekakiPoteto! &copy; RanmaGuy (Theo Chakkapark) and Marcello" width="200" height="40" /></a>
			<a href="http://cellosoft.com/2draw/?in=24"><img src="2Draw.gif" class="imghover" width="88" height="31" alt="[Visit 2draw by Marcello]" title="Visit 2draw by Marcello" /></a>
		</p>

		<p style="text-align: center;">
			<a href="index.php"><?php tt('returnbbs');?></a>
		</p>

	</div>
</div>


<?php

include 'footer.php';