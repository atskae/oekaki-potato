<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bast?a-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2018 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.6 - Last modified 2018-05-20
*/


/*
NOTES:
	if (image_jpeg === true):
	Compress level of 1-2 is archival, 4 starts showing artifacts.  3-4 recommended.  0 not recommended due to lossless JPEG (use PNG instead).  Anything over 10 not recommended except for thumbnails.  Max value may be 100 if you hate your artists.  :)

	if (image_jpeg === false):
	PaintBBS employs lossy PNG!  Level should be 0 if image_jpeg is false.  PaintBBS doesn't murder PNGs like ShiPainter, but still, lossy PNG is not pretty.

	if (new pic) {
		Send nothing to comment.php.
	} elseif (public edit by new artist) {
		Send import to comment.php.
	} else {
		send edit and import.
	}
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';
require 'paintsave.php';

// Input
$xcord = w_gpc('xcord', 'i');
$ycord = w_gpc('ycord', 'i');
$anim  = w_gpc('anim', 'i');
$edit  = w_gpc('edit', 'i+');
$user_pass = w_gpc('pw');

// Init
$app_head     = ''; // Passed to save tool
$app_head_pw  = ''; // Added to $app_head
$app_url_exit = ''; // Params for index (comment)
$import       = ''; // Canvas number to import.  Passed to save tool
$app_import   = ''; // Canvas/anim filename to import (based on $anim)
$anim_type    = 'pch'; // Extension for animation


// Check Draw access
if (!$flags['D']) {
	report_err( t('err_drawaccess'));
}

// Check anim access
// If retouch, anim flag is ignored
if (!$flags['M']) {
	$anim = 0;
}


if ($edit) {
	// Get pic info
	$result = db_query("SELECT PIC_ID, usrname, animation, password, ptype FROM {$db_p}oekakidta WHERE PIC_ID='$edit'");
	$row = db_fetch_array($result);

	// Verify anim
	if ($row['animation'] == 1) {
		$anim = 1;
	} else {
		$anim = 0;
	}

	// Set up import
	$import = $edit;
	if ($anim) {
		$app_import = $p_pic.$import.'.'.$anim_type;
	} else {
		$app_import = $p_pic.$import.'.'.$row['ptype'];
	}

	// Choose pass/public retouch option
	if ($row['usrname'] != $OekakiU) {
		if (w_transmission_verify($row['password'], $user_pass)) {
			// Password retouch
			// Due to technical/admin issues, only in-place edits are supported
			$app_head_pw = $user_pass;
		} elseif ($row['password'] == 'public') {
			if ($cfg['public_retouch'] == 'yes') {
				// Public, treat as new pic, but import canvas/anim
				$edit = null;
			} else {
				// pw != public, and public disabled
				report_err( t('pubretouchdis'));
			}
		} else {
			// Invalid!
			report_err( t('errrtpwd'));
		}
	}
	$size = GetImageSize($p_pic.$row['PIC_ID'].'.'.$row['ptype']);
	$xcord = $size[0];
	$ycord = $size[1];
} else {
	// Check for unfinished pictures
	$result = db_query("SELECT COUNT(PIC_ID) FROM {$db_p}oekakidta WHERE postlock=0 AND usrname='$OekakiU'");
	$num_rows = (int) db_result($result, 0);

	if ($cfg['safety_max'] > 1) {
		if ($num_rows >= $cfg['safety_max']) {
			report_err( t('munfinishpic'));
		}
	} else {
		if ($num_rows > 0) {
			report_err( t('aunfinishpic'));
		}
	}
}


// Verify that canvas sizes fit min/max
$xcord = fix_range($cfg['min_x'], $cfg['canvas_x'], $xcord);
$ycord = fix_range($cfg['min_y'], $cfg['canvas_y'], $ycord);


// Set applet area
{
	/* A bug in the Mac version of Java 1.5 requires hard-coded values, rather than CSS.  So, we set to a reasonable default, and grow the applet to fit the canvas. */

	// Typical viewable width of 800x600 browser is 740px.
	$xhack = 740; // Applet x
	$yhack = 550; // Applet y
	$xoverhead = 100 ; // Applet border/palette width
	$yoverhead = 35;

	if (DISABLE_APPLET_AREA_HACK) {
		$xhack = '100%';
		// $yhack = '90%'; // HTML quirks mode only
	} else {
		if ($xcord > ($xhack - $xoverhead))
			$xhack = $xcord + $xoverhead;
	}
	if ($ycord > ($yhack - $yoverhead))
		$yhack = $ycord + $yoverhead;
}


// Get filesize cutoff.  See notes above about compress_level
// APPLET_NO_JPEG is in hacks.php
$image_jpeg_value = (APPLET_NO_JPEG != 0) ? 'false' : 'true';
$image_compress = (APPLET_NO_JPEG != 0) ? '0' : '4';
$image_size_value = applet_image_size_param($xcord, $ycord);


// Set up applet header
if ($edit) {
	$app_head_mode = 'edit';
} elseif ($anim) {
	$app_head_mode = 'anim';
} else {
	$app_head_mode = 'norm';
}

$app_head = "mode={$app_head_mode};edittimes=".time().";uid={$OekakiID};vcode=".$OekakiPass;
if ($edit) {
	$app_head .= ";edit={$edit}";
}
if ($import) {
	$app_head .= ";import={$import}";
}
if (!empty($app_head_pw)) {
	$app_head .= ';pw='.$app_head_pw;
}

// Set up applet exit
$app_url_exit = 'comment.php?mode=res_msg';
if ($import && !$edit) {
	$app_url_exit .= "&import={$import}";
} elseif ($import) {
	$app_url_exit .= "&import={$import}&edit={$edit}";
}
if (!empty($app_head_pw)) {
	$app_url_exit .= '&pw='.$app_head_pw;
}


// No footer
// db_close();



// Start HTML
send_html_headers();
?>
<head>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo $charset;?>" />
	<meta http-equiv="content-language" content="<?php echo $metatag_language;?>" />

	<title><?php echo $cfg['op_title'];?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo $cssinclude;?>" title="<?php echo $template_name;?>" />

	<script type="text/javascript" src="Poteto.js">
	</script>
	<script type="text/javascript" src="noteBBS.js">
	</script>
<?php if (w_gpc('noclose')) { ?>
	<script type="text/javascript">
		noBadClose();
	</script>
<?php } ?>
</head>

<body>


<div style="text-align: center;">
	<applet id="paintbbs"
		code="pbbs.PaintBBS.class"
		archive="PaintBBS.jar"
		width="<?php echo $xhack;?>"
		height="<?php echo $yhack;?>">

		<!-- param name="permissions" value="sandbox" / -->

		<!-- Disable Java DirectX support -->
		<param name="java_arguments" value="-Dsun.java2d.noddraw=true -Dsun.java2d.d3d=false" />

		<param name="image_width" value="<?php echo $xcord;?>" />
		<param name="image_height" value="<?php echo $ycord;?>" />

		<param name="image_jpeg" value="<?php echo $image_jpeg_value;?>" />
		<param name="image_size" value="<?php echo $image_size_value;?>" />
		<param name="compress_level" value="<?php echo $image_compress;?>" />

<?php if ($import) { ?>
		<param name="image_canvas" value="<?php echo $app_import;?>" />
<?php } ?>
<?php if ($anim) { ?>
		<param name="thumbnail_type" value="animation" />
<?php } ?>
		<param name="url_save" value="paintbbsget.php" />
		<param name="url_exit" value="<?php echo w_html_chars($app_url_exit);?>" />
		<param name="url_target" value="_self" />

		<param name="undo" value="60" />
		<param name="undo_in_mg" value="12" />

		<param name="poo" value="false" />
		<param name="send_header" value="<?php echo w_html_chars($app_head)?>" />
		<param name="send_header_image_type" value="false" />

		<param name="image_bkcolor" value="#ffffff" />
		<param name="color_text" value="#505078" />
		<param name="color_bk" value="#9999bb" />
		<param name="color_bk2" value="#8888aa" />
		<param name="color_icon" value="#ccccff" />

		<param name="tool_advance" value="true" />
		<param name="send_advance" value="true" />
		<param name="send_language" value="utf8" />

		<?php tt('paint_need_java_link', 'http://www.java.com');?> 
	</applet>
</div>


<p style="text-align: center;">
	<input onclick="maximize_app('paintbbs');" type="button" value="<?php tt('resizeapplet');?>" class="submit" />
</p>


<p style="text-align: center;">
	<input onclick="size_app('paintbbs');" type="button" value="<?php tt('resize_to_this');?>" class="submit" />
	X:<input id="button_x" type="text" size="3" value="<?php echo $xhack;?>" class="txtinput" />
	Y:<input id="button_y" type="text" size="3" value="<?php echo $yhack;?>" class="txtinput" />
</p>


<!-- Controls -->
<div class="pheader">
	<?php tt('appcontrol');?>
</div>

<table class="pinfotable" style="width: 100%; text-align: center;">
	<tr>
	<td style="width: 50%;">
		<p>
			<strong><?php tt('plznote');?></strong>
		</p>
		<ul>
			<li><?php tt('canvchgdest');?></li>
			<li><?php tt('noresizeretou');?></li>
			<li><?php tt('refreshbret');?></li>
			<li><?php tt('float');?></li>
		</ul>
	</td>

<?php if (!$edit) { ?>
	<td style="width: 50%;">
		<form name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<p>
				<?php tt('applet_modify', $cfg['canvas_x'], $cfg['canvas_y']);?> 
			</p>

			<p>
				<?php tt('canvasx');?> <input type="text" name="xcord" value="<?php echo $xcord;?>" class="ptxtinput" /><br />
				<?php tt('canvasy');?> <input type="text" name="ycord" value="<?php echo $ycord;?>" class="ptxtinput" /><br />
			</p>
<?php if ($anim) { ?>
			<input type="hidden" name="anim" value="<?php echo $anim;?>" />
<?php } ?>
			<input type="submit" name="Submit" value="<?php tt('word_modify');?>" class="submit" />
		</form>
	</td>
<?php } ?>
	</tr>
</table>
<br />


<!-- Info -->
<div class="pheader">
	<?php tt('javaimfo');?> 
</div>

<div class="pinfotable">
	<p style="text-align: center; padding: 0 20% 0 20%;">
		<?php tt('javahlp', '<a href="http://www.Java.com">www.Java.com</a>');?> 
	</p>
</div>
<br />


<?php

include 'footer.php';