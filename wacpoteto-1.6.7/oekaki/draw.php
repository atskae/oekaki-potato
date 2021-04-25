<?php
/*
Wacintaki Poteto - Copyright 2004-2016 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.4 - Last modified 2016-08-13
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


// Get custom cookie for draw settings
if (isset($_COOKIE['drawcfg'])) {
	$drawcfg = array();
	$drawtmp = preg_replace("/[^a-zA-Z0-9s_=;]/", '', w_gpc('drawcfg', 'raw'));
	$pairs = explode(';', $drawtmp);
	for ($i = count($pairs); --$i >= 0;) {
		$keyvals = explode('=', $pairs[$i]);
		$drawcfg[$keyvals[0]] = $keyvals[1];
	}
}
if (!isset($drawcfg)) {
	$drawcfg = array('app'=>0, 'anim'=>0, 'close'=>1);
}


// Pictures folder locked?
if (!is_writable($cfg['op_pics'])) {
	report_err( t('picfolocked'));
}

// Allow use of Java applets?
$use_java = true;
if (defined('APPLET_DISABLE_JAVA') && APPLET_DISABLE_JAVA) {
	$use_java = false;
}


// Check GET: draw info
if (isset($_REQUEST['applet'])) {
	$app   = w_gpc('applet');
	$xsize = w_gpc('canvasx', 'i');
	$ysize = w_gpc('canvasy', 'i');

	$useapp = '';
	$datatype = 0;
	$app_params = '';

	switch ($app) {
		case 'paintbbs':
			$useapp = 'paintBBS.php';
			$drawcfg['app'] = 0;
			break;
		case 'notebbs':
			$useapp = 'noteBBS.php';
			$drawcfg['app'] = 4;
			break;
		case 'oekakibbs':
			$useapp = 'oekakiBBS.php';
			$datatype = 1;
			$drawcfg['app'] = 3;
			break;
		case 'shi':
			$useapp = 'shiBBS.php';
			$datatype = 2;
			$drawcfg['app'] = 5;
			break;
		case 'shipro':
			$useapp = 'shiBBS.php';
			$datatype = 3;
			$app_params = 'tools=pro&cursor=cursor&';
			$drawcfg['app'] = 1;
			break;
		case 'chibipaint':
			$useapp = 'chibipaint.php';
			$datatype = 4;
			$drawcfg['app'] = 2;
			break;
		case 'chickenpaint':
			$useapp = 'chickenpaint.php';
			$datatype = 5;
			$drawcfg['app'] = 6;
			break;
		default:
			all_done();
	}

	if ($xsize < $cfg['canvas_x']) $xsize == $cfg['min_x'];
	if ($ysize < $cfg['canvas_y']) $ysize == $cfg['min_y'];
	if ($xsize > $cfg['canvas_x']) $xsize == $cfg['canvas_x'];
	if ($ysize > $cfg['canvas_y']) $ysize == $cfg['canvas_y'];

	$extra_cgi = '';
	if (w_gpc('anim') == 'on') {
		$extra_cgi .= '&anim=1';
		$drawcfg['anim'] = 1;
	} else {
		$drawcfg['anim'] = 0;
	}
	if (w_gpc('noclose') == 'on') {
		$extra_cgi .= '&noclose=1';
		$drawcfg['close'] = 1;
	} else {
		$drawcfg['close'] = 0;
	}
	if ($datatype > 0) {
		$extra_cgi .= '&datatype='.$datatype;
	}

	// Set custom cookie for draw settings
	if (w_gpc('saveset')) {
		$drawtmp = array();
		foreach ($drawcfg as $key => $val) {
			$drawtmp[] = $key.'='.$val;
		}
		$drawtmp = implode(';', $drawtmp);
		w_set_cookie('drawcfg', $drawtmp);
	}

	all_done($useapp.'?'.$app_params.'xcord='.$xsize.'&ycord='.$ysize.$extra_cgi);
}





/* START HTML */
include 'header.php';


// Check for unfinished pictures
$result = db_query("SELECT COUNT(PIC_ID) FROM {$db_p}oekakidta WHERE postlock='0' AND usrname='$OekakiU'");
$num_rows = (int) db_result($result, 0);

$too_many = false;
if ($num_rows >= $cfg['safety_max']) {
	$too_many = true;
}

if ($too_many) {
	// Unfinished pictures interface
?>
<div id="contentmain">

	<!-- Unfinished -->
	<h1 class="header">
		<?php tt('icomppic');?> 
	</h1>

	<div class="infotable" style="text-align: center">
		<p>
			<?php tt('unfinished_warning', $num_rows);?> 
		</p>

		<p>
			<a href="recover.php"><?php tt('clickrecoverpic');?></a>
		</p>
	</div>
</div>


<?php
	include 'footer.php';
	exit;

}


?>
<div id="contentmain">

<?php
// Print WIP notice if there is at least one, but still under limit
if ($num_rows > 0) {
	// Unfinished pictures interface
?>
	<!-- Unfinished -->
	<h1 class="header">
		<?php tt('word_reminder');?> 
	</h1>

	<div class="infotable" style="text-align: center">
		<p>
			<?php tt('unfinished_notice', $num_rows);?> 
		</p>

		<p>
			<a href="recover.php"><?php tt('clickrecoverpic');?></a>
		</p>
	</div>
	<br />


<?php
}


// Print draw interface
?>
	<!-- Draw -->
	<h1 class="header">
		<?php tt('word_draw');?> 
	</h1>

	<div class="infotable">
		<table cellpadding="0" cellspacing="0" border="0">
		<tr>
		<td style="vertical-align: top;">
			<form action="draw.php" method="get">
				<h4 style="margin-top: 0;"><?php tt('word_applet');?></h4>
				<p class="subtext">
<?php $ch = 'checked="checked" ';?>
<?php if (file_exists('chickenpaint/')) { ?>
					<input type="radio" name="applet" value="chickenpaint" <?php if($drawcfg['app']==0) echo $ch;?>/>ChickenPaint (HTML5)<br />
<?php } ?>
<?php if ($use_java && file_exists('spainter_all.jar')) { ?>
					<input type="radio" name="applet" value="shi" <?php if($drawcfg['app']==6) echo $ch;?>/>Shi-Painter<br />
					<input type="radio" name="applet" value="shipro" <?php if($drawcfg['app']==1) echo $ch;?>/>Shi-Painter Pro<br />
<?php } ?>
<?php if ($use_java && file_exists('chibipaint.jar')) { ?>
					<input type="radio" name="applet" value="chibipaint" <?php if($drawcfg['app']==2) echo $ch;?>/>Chibi Paint <?php tt('draw_no_anim');?><br />
<?php } ?>
<?php if ($use_java && file_exists('oekakibbs.jar')) { ?>
					<input type="radio" name="applet" value="oekakibbs" <?php if($drawcfg['app']==3) echo $ch;?>/>OekakiBBS<br />
<?php } ?>
<?php if ($use_java && file_exists('PaintBBS.jar')) { ?>
					<input type="radio" name="applet" value="notebbs" <?php if($drawcfg['app']==4) echo $ch;?>/>PaintBBS<?php tt('withpalet');?><br />
					<input type="radio" name="applet" value="paintbbs" <?php if($drawcfg['app']==5) echo $ch;?>/>PaintBBS<br />
<?php } ?>
				</p>

				<h4><?php tt('draw_canvas_min_max', $cfg['min_x'], $cfg['min_y'], $cfg['canvas_x'], $cfg['canvas_y']); ?></h4>
				<p class="subtext">
					<input id="previewWidth" class="txtinput" onblur="updatePreview(<?php echo $cfg['min_x']?>, <?php echo $cfg['min_y']?>, <?php echo $cfg['canvas_x']?>, <?php echo $cfg['canvas_y']?>);" name="canvasx" value="<?php echo $cfg['def_x']?>" size="4" />
					&times;
					<input id="previewHeight" class="txtinput" onblur="updatePreview(<?php echo $cfg['min_x']?>, <?php echo $cfg['min_y']?>, <?php echo $cfg['canvas_x']?>, <?php echo $cfg['canvas_y']?>);" name="canvasy" value="<?php echo $cfg['def_y']?>" size="4" />
				</p>

<?php if ($flags['M']) { ?>
				<h4><input type="checkbox" name="anim" <?php if($drawcfg['anim']==1) echo $ch;?>/> <?php tt('draw_anims_layers');?></h4>
				<p class="subtext">
					<?php tt('draw_combine_layers');?> 
				</p>

<?php } ?>
				<h4><input type="checkbox" name="noclose" <?php if($drawcfg['close']==1) echo $ch;?>/> <?php tt('draw_close_confirm');?></h4>
				<p class="subtext">
					<?php tt('draw_help_loss_pics');?> 
				</p>

				<h4><input type="checkbox" name="saveset" <?php if(w_gpc('drawcfg')) echo $ch;?>/> <?php tt('draw_remember_settings');?></h4>

				<p class="subtext">
					<input type="submit" name="Submit" value="<?php tt('word_submit');?>" class="submit" />
				</p>
			</form>
		</td>
<?php if ($cfg['preview_img']) { ?>
		<td style="padding-left: 30pt; vertical-align: top;">
			<div style="text-align: center;">
				<img id="previewImage" src="<?php echo $cfg['res_path'].'/'.$cfg['preview_img']?>" width="<?php echo $cfg['def_x']?>" height="<?php echo $cfg['def_y']?>" alt="[Canvas Preview]" title="<?php echo $cfg['preview_title']?>" /><br />
				<?php tt('canvasprev');?> 
			</div>
		</td>
<?php } ?>
		</tr>
		</table>
		<br />
	</div>
</div>


<?php

include 'footer.php';