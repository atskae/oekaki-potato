<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2018 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.6 - Last modified 2018-05-20
*/


/*
NOTES:
	Cookies are sent as the HTTP GET method, and thus don't [aren't supposed to] work with POST.  However, since ChibiPaint doesn't support header data, we have to mimick cookies by spoofing POST data as GET.  This is the technique Wacintaki used to use with PaintBBS and ShiPainter before v1.3, and has since been obsoleted.  Expect some major differences between the code used for this applet and the others, and some variables normally required for the other paint programs may not be used here.

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
$anim_type    = 'chi'; // Extension for animation


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
	if ($import) {
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


// Recolor
if (empty($edit) && empty($import)) {
	// Name, such as "pic.png"
	$recolor = w_gpc('recolor');

	if (!empty($recolor)) {
		// Clean, add path
		$file_illegal = array('"', '?', '*', '/', '\\', ':', '|', '<', '>');
		$app_import = './recolor/'.str_replace($file_illegal, '', $recolor);

		if (file_exists($app_import)) {
			$size = @GetImageSize($app_import);
			$xcord = $size[0];
			$ycord = $size[1];
		} else {
			// Recolor used as import flag
			$recolor = '';
			$app_import = '';
			$xcord = $cfg['canvas_x'];
			$ycord = $cfg['canvas_y'];
		}
	}
}


// Verify that canvas sizes fit min/max
$xcord = fix_range($cfg['min_x'], $cfg['canvas_x'], $xcord);
$ycord = fix_range($cfg['min_y'], $cfg['canvas_y'], $ycord);


// Set applet area
{
	/* A bug in the Mac version of Java 1.5 requires hard-coded values, rather than CSS.  So, we set to a reasonable default, and grow the applet to fit the canvas. */

	/*
		Typical viewable width of 800x600 browser is 740px.
		BUT ChibiPaint really needs a larger screen, so we'll bump the screen
		size to 1024x768, giving a practical width of 964px
	*/
	$xhack = 964; // Applet x
	$yhack = 700; // Applet y
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


// Set up applet CGI
// These are sent as GET data, so they need ampersands, not semicolons!
if ($edit) {
	$app_head_mode = 'edit';
} elseif ($anim) {
	$app_head_mode = 'anim';
} else {
	$app_head_mode = 'norm';
}

$app_head = "mode={$app_head_mode}&edittimes=".time()."&uid={$OekakiID}".'&vcode='.urlencode($OekakiPass);
if ($edit) {
	$app_head .= "&edit={$edit}";
}
if ($import) {
	$app_head .= "&import={$import}";
}
if (!empty($app_head_pw)) {
	// Chibi Paint requires CGI encoding, not header encoding
	$app_head .= '&pw='.urlencode($app_head_pw);
}

// Set up applet exit
$app_url_exit = 'comment.php?mode=res_msg';
if ($import && !$edit) {
	$app_url_exit .= "&import={$import}";
} elseif ($import) {
	$app_url_exit .= "&import={$import}&edit={$edit}";
}
if (!empty($app_head_pw)) {
	$app_url_exit .= '&pw='.urlencode($app_head_pw);
}


// No footer
// db_close();



// Start HTML
send_html_headers();
?>
<head>
	<meta http-equiv="content-Type" content="text/html; charset=<?php echo $charset;?>" />
	<meta http-equiv="content-Language" content="<?php echo $metatag_language;?>" />

	<title><?php echo $cfg['op_title'];?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo $cssinclude;?>" title="<?php echo $template_name;?>" />

	<script type="text/javascript" src="Poteto.js">
	</script>
<?php if (w_gpc('noclose')) { ?>
	<script type="text/javascript">
		noBadClose();
	</script>
<?php } ?>
</head>

<body>


<div style="text-align: center;">
	<applet id="chibi"
		code="chibipaint.ChibiPaint.class" 
		archive="chibipaint.jar"
		width="<?php echo $xhack;?>"
		height="<?php echo $yhack;?>">

		<!--
			DirectDraw seems to accelerate ChibiPaint.  Unless there's screen
			corruption, diable Direct3D, but leave DirectDraw enabled.
		-->
		<!-- <param name="java_arguments" value="-Dsun.java2d.noddraw=true -Dsun.java2d.d3d=false" /> -->
		<param name="java_arguments" value="-Dsun.java2d.d3d=false" />

		<param name="canvasWidth" value="<?php echo $xcord;?>" />
		<param name="canvasHeight" value="<?php echo $ycord;?>" />

<?php if ($import || $recolor) { ?>
		<param name="loadImage" value="<?php echo w_html_chars($app_import);?>" />
<?php } ?>
<?php if ($anim) { ?>
		<param name="loadChibiFile" value="<?php echo $p_pic.$import.'.chi';?>" />
<?php } ?>

		<param name="postUrl" value="chibipaintget.php?<?php echo w_html_chars($app_head);?>" />
		<param name="exitUrl" value="<?php echo w_html_chars($app_url_exit);?>" />
		<param name="exitUrlTarget" value="_self" />

		<?php tt('paint_need_java_link', 'http://www.java.com');?> 
	</applet>
</div>


<p style="text-align: center;">
	<input onclick="maximize_app('chibi');" type="button" value="<?php tt('resizeapplet');?>" class="submit" />
</p>


<p style="text-align: center;">
	<input onclick="size_app('chibi');" type="button" value="<?php tt('resize_to_this');?>" class="submit" />
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