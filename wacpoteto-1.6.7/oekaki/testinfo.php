<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.2 - Last modified 2015-05-09

WARNING:
Do NOT put sensitive information into this script!
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


// Diagnosics backdoor.
if (!$flags['owner']) {
	if (!isset($cfg['salt']) || w_gpc('pass') != $cfg['salt']) {
		report_err( t('testvar1'));
	}
}
if (isset($_GET['php'])) {
	phpinfo();
	w_exit();
}


// Get member
$member_info = array();


// Get DB
$db_info = array();
$limit_quanity = 6;
{
	$result = db_query("SELECT miscstring FROM {$db_p}oekakimisc WHERE miscname='dbversion'");
	$db_info['d_db_version'] = db_result($result, 0);

	$result = db_query("SELECT COUNT(ID_2) FROM {$db_p}oekakidta");
	$db_info['d_total_pics'] = t('d_pics_vs_max', db_result($result), $cfg['pic_store']);

	$result = db_query("SELECT COUNT(ID_2) FROM {$db_p}oekakidta WHERE archive=1");
	$db_info['d_archives'] = db_result($result);

	$result = db_query("SELECT COUNT(ID_2) FROM {$db_p}oekakidta WHERE postlock=0");
	$db_info['d_wip_recov'] = db_result($result);

	$result = db_query("SELECT miscvalue FROM {$db_p}oekakimisc WHERE miscname='piccount'");
	$db_info['d_cur_picno'] = db_result($result);
}


// Get Locks
{
	$lock_info = array(
		"./" => 0,
		"config.php" => 0,
		"dbconn.php" => 0,
		"./{$cfg['op_pics']}/"       => 0,
		"./{$cfg['tpl_path']}/"      => 0,
		"./{$cfg['avatar_folder']}/" => 0,
//		"./announce.php" => 0,
		"./{$cfg['res_path']}/"      => 0,
		"./{$cfg['res_path']}/banner.php" => 0,
		"./{$cfg['res_path']}/hosts.txt"  => 0,
		"./{$cfg['res_path']}/ips.txt"    => 0,
		"./{$cfg['res_path']}/notice.php" => 0,
		"./{$cfg['res_path']}/rules.php"  => 0
	);

	foreach ($lock_info as $name => $value) {
		if (file_exists($name)) {
			if (!is_writable($name)) {
				$lock_info[$name] = 1;
			}
		} else {
			$lock_info[$name] = 2;
		}
	}
}


include 'header.php';


?>
<div id="contentmain">
	<h1 class="header">
		<?php tt('dbinfo');?> 
	</h1>
	<div class="infotable">
		<table class="infomain">
<?php foreach ($db_info as $name => $value) { ?>
		<tr>
		<td class="infoask">
			<?php tt($name); ?>
		</td>
		<td class="infoenter">
			<?php echo $value; ?> 
		</td>
		</tr>
<?php }
unset($db_info);

$result = db_query("SELECT miscvalue FROM {$db_p}oekakimisc WHERE miscname='db_utf8'");
?>
		<tr>
		<td class="infoask">
			Database encoding state:
		</td>
		<td class="infoenter">
			db_utf8 = <?php echo db_result($result); ?> 
		</td>
		</tr>
		<tr>
		<td colspan="2">&nbsp;</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_sql_direct'); ?> 
		</td>
		<td class="infoenter">
			<?php
if (file_exists('sqltest.php')) {
	tt('d_sql_avail', 'sqltest.php');
} else {
	tt('word_na');
}
?>
		</td>
		</tr>
		</table>
	</div>
	<br />


	<h1 class="header">
		<?php tt('d_word_config');?> 
	</h1>
	<div class="infotable">
		<table class="infomain">
		<tr>
		<td class="infoask">
			<?php tt('d_php_info');?> 
		</td>
		<td class="infoenter">
			<?php tt('d_php_ver_num', phpversion(), $_SERVER['PHP_SELF'].'?php=1');?> 
		</td>
		</tr>

		<tr>
		<td colspan="2">&nbsp;</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('configver');?> 
		</td>
		<td class="infoenter">
			<?php echo $cfg['version'] ?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('install_salt');?> (salt):
		</td>
		<td class="infoenter">
			<?php echo $cfg['salt'] ?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('word_contact');?> 
		</td>
		<td class="infoenter">
			<?php echo $cfg['op_email'] ?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('pathtoop');?> 
		</td>
		<td class="infoenter">
			<?php echo $cfg['op_url'] ?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('cookiepath');?> 
		</td>
		<td class="infoenter">
			<?php ne_echo($glob['cookie_path'], t('cookie_empty')); ?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('cookie_domain');?> 
		</td>
		<td class="infoenter">
			<?php ne_echo($glob['cookie_domain'], t('cookie_empty')); ?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('cookielife');?> 
		</td>
		<td class="infoenter">
			<?php tt('seconds_approx_days', $glob['cookie_life'], intval($glob['cookie_life'] / 86400));?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_pub_images');?> 
		</td>
		<td class="infoenter">
			<?php
if ($cfg['public_retouch'] == 'yes') {
	tt('word_yes');
} else {
	tt('word_no');
}
?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('safetysaves');?> 
		</td>
		<td class="infoenter">
			<?php
$safe = (int) $cfg['safety_storetime'];
if ($cfg['safety_saves'] == 'yes') {
	tt('d_yes_days', $safe);
} else {
	tt('d_no_days', $safe);
}
?> 
		</td>
		</tr>
		</table>
	</div>
	<br />


<?php
	// Get pictures folder info
	$picinfo = getImageDirStats($cfg['op_pics']);
?>
	<h1 class="header">
		<?php tt('d_pics_folder');?> 
	</h1>
	<div class="infotable">
<?php if (!empty($picinfo['err'])) { ?>
		<table class="infomain">
		<tr>
		<td class="infoask">
			<?php tt('d_notice');?> 
		</td>
		<td class="infoenter">
			<?php echo $picinfo['err']; ?> 
		</td>
		</tr>
		</table>
<?php } else { ?>
		<table class="infomain">
		<tr>
		<td class="infoask">
			<?php tt('d_folder');?> 
		</td>
		<td class="infoenter">
			<?php echo $cfg['op_pics'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_total_files');?> 
		</td>
		<td class="infoenter">
			<?php echo $picinfo['total'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_space_used');?> 
		</td>
		<td class="infoenter">
			<?php echo $picinfo['total_size'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_avg_filesize');?> 
		</td>
		<td class="infoenter">
			<?php echo $picinfo['avg_size'];?> 
		</td>
		</tr>

		<tr>
		<td colspan="2">&nbsp;</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_images_label');?> 
		</td>
		<td class="infoenter">
			<?php tt('d_img_and_percent', $picinfo['img'], trim(getTotalPer($picinfo['img'], $picinfo['total'])));?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_anim_label');?> 
		</td>
		<td class="infoenter">
			<?php tt('d_anim_and_percent', $picinfo['anim'], trim(getTotalPer($picinfo['anim'], $picinfo['total'])));?> 
		</td>
		</tr>

		<tr>
		<td colspan="2">&nbsp;</td>
		</tr>

		<tr>
		<td class="infoask">
			.PNG:
		</td>
		<td class="infoenter">
			<?php echo $picinfo['png'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			.JPG:
		</td>
		<td class="infoenter">
			<?php echo $picinfo['jpg'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			.GIF:
		</td>
		<td class="infoenter">
			<?php echo $picinfo['gif'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			.WEBP:
		</td>
		<td class="infoenter">
			<?php echo $picinfo['webp'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			.PCH:
		</td>
		<td class="infoenter">
			<?php echo $picinfo['pch'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			.OEB:
		</td>
		<td class="infoenter">
			<?php echo $picinfo['oeb'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			.CHI:
		</td>
		<td class="infoenter">
			<?php echo $picinfo['chi'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_other_types');?> 
		</td>
		<td class="infoenter">
			<?php echo $picinfo['other'];?> 
		</td>
		</tr>
		</table>
<?php } ?>
	</div>
	<br />


<?php
	// Get avatars folder info
	$picinfo = getImageDirStats($cfg['avatar_folder']);
?>
	<h1 class="header">
		<?php tt('word_avatars');?> 
	</h1>
	<div class="infotable">
<?php if (!empty($picinfo['err'])) { ?>
		<table class="infomain">
		<tr>
		<td class="infoask">
			<?php tt('d_notice');?> 
		</td>
		<td class="infoenter">
			<?php echo $picinfo['err']; ?> 
		</td>
		</tr>
		</table>
<?php } else { ?>
		<table class="infomain">
		<tr>
		<td class="infoask">
			<?php tt('d_folder');?> 
		</td>
		<td class="infoenter">
			<?php echo $cfg['avatar_folder'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_total_files');?> 
		</td>
		<td class="infoenter">
			<?php echo $picinfo['total'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_space_used');?> 
		</td>
		<td class="infoenter">
			<?php echo $picinfo['total_size'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_avg_filesize');?> 
		</td>
		<td class="infoenter">
			<?php echo $picinfo['avg_size'];?> 
		</td>
		</tr>

		<tr>
		<td colspan="2">&nbsp;</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_images_label');?> 
		</td>
		<td class="infoenter">
			<?php tt('d_img_and_percent', $picinfo['img'], trim(getTotalPer($picinfo['img'], $picinfo['total'])));?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_anim_label');?> 
		</td>
		<td class="infoenter">
			<?php tt('d_anim_and_percent', $picinfo['anim'], trim(getTotalPer($picinfo['anim'], $picinfo['total'])));?> 
		</td>
		</tr>

		<tr>
		<td colspan="2">&nbsp;</td>
		</tr>

		<tr>
		<td class="infoask">
			.PNG:
		</td>
		<td class="infoenter">
			<?php echo $picinfo['png'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			.JPG:
		</td>
		<td class="infoenter">
			<?php echo $picinfo['jpg'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			.GIF:
		</td>
		<td class="infoenter">
			<?php echo $picinfo['gif'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			.WEBP:
		</td>
		<td class="infoenter">
			<?php echo $picinfo['webp'];?> 
		</td>
		</tr>

		<tr>
		<td class="infoask">
			<?php tt('d_other_types');?> 
		</td>
		<td class="infoenter">
			<?php echo $picinfo['other'];?> 
		</td>
		</tr>
		</table>
<?php } ?>
	</div>
	<br />


	<h1 class="header">
		<?php tt('word_locks');?>
	</h1>
	<div class="infotable">
		<table class="infomain">
<?php foreach ($lock_info as $name => $value) { ?>
		<tr>
		<td class="infoask">
			<?php echo $name; ?> 
		</td>
		<td class="infoenter">
			<?php
			if ($value == 0) {
				tt('word_okay');
			} elseif ($value == 1) {
				tt('word_locked');
			} else {
				tt('word_missing');
			}
			?> 
		</td>
		</tr>
<?php }
unset($lock_info);
?>

		</table>
	</div>
	<br />


</div>


<?php

include 'footer.php';



//
// FUNCTIONS
//

function getTotalPer($in, $total) {
	if ($total == 0) {
		return $in;
	}

	$out = ((float) $in / (float) $total) * 100.0;
	return sprintf('%5.1f', $out);
}

function getImageDirStats($dir) {
	$f_total = 0;
	$f_kilo  = 0;
	$f_img   = 0;
	$f_anim  = 0;
	$f_other = 0;
	$f_png   = 0;
	$f_gif   = 0;
	$f_jpg   = 0;
	$f_webp  = 0;
	$f_pch   = 0;
	$f_oeb   = 0;
	$f_chi   = 0;
	$err = '';

	if (! ($handle = @opendir($dir))) {
		$err = t('d_no_read_dir');
	} else { 
		while (false !== ($file = readdir($handle))) {
			$dot = strpos($file, '.');

			// Don't include hidden files or text/code files
			if ($file != 'index.php' && $dot !== false && $dot != 0) {
				$f_total++;
				$size = filesize($dir.'/'.$file);
				if ($size > 0) {
					// PHP doesn't support long (!), so use KB, so we don't overflow
					$f_kilo += intval($size / 1024);
				}


				$ext = substr($file, -4);
				switch ($ext) {
					case '.gif':
						$f_gif++;
						$f_img++;
						break;

					case '.jpg':
						$f_jpg++;
						$f_img++;
						break;

					case '.png':
						$f_png++;
						$f_img++;
						break;

					case 'webp':
						$f_webp++;
						$f_img++;
						break;

					case '.pch':
						$f_pch++;
						$f_anim++;
						break;

					case '.oeb':
						$f_oeb++;
						$f_anim++;
						break;

					case '.chi':
						$f_chi++;
						$f_anim++;
						break;

					default:
						$f_other++;
						break;
				}
			}
		}
		closedir($handle);


		// Div by zero
		if ($f_total < 1) {
			$err = t('d_folder_empty');
		} else {
			$total_size = '';
			$avg_size   = '';

			// Totals
			$total_size .= $f_kilo.'KB';
			$t_megabytes = (float) $f_kilo / 1024.0;
			if ($t_megabytes > 1.0) {
				$total_size .= ' ('.sprintf('%01.1f', $t_megabytes).'MB)';
			}

			// Averages
			$avg_size .= intval($f_kilo / $f_total).'KB';
		}
	}

	return array(
		'total'      => $f_total,
		'total_size' => $total_size,
		'avg_size'   => $avg_size,
		'kilo'  => $f_kilo,
		'img'   => $f_img,
		'anim'  => $f_anim,
		'other' => $f_other,
		'png'   => $f_png,
		'gif'   => $f_gif,
		'jpg'   => $f_jpg,
		'webp'  => $f_webp,
		'pch'   => $f_pch,
		'oeb'   => $f_oeb,
		'chi'   => $f_chi,
		'err'   => $err
	);
}