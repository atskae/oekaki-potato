<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2018 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.6 - Last modified 2018-05-20
*/


/*
NOTES:
	Cookies are sent as the HTTP GET method, and thus don't [aren't supposed to] work with POST.  However, since OekakiBBS doesn't support header data, we have to mimick cookies by spoofing POST data as GET.  This is the technique Wacintaki used to use with PaintBBS and ShiPainter before v1.3, and has since been obsoleted.  Expect some major differences between the code used for this applet and the others, and some variables normally required for the other paint programs may not be used here.

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
$anim_type    = 'oeb'; // Extension for animation


// Check Draw access
if (!$flags['D']) {
	report_err( t('autoreg_p4'));
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


// Bug in Mac version of Java requires hard-coded values, rather than CSS.  >:(
// Typical viewable width of 800x600 browser is 740px.
	$xhack = '740';
// Typical height
	$yhack = '550';


// APPLET_NO_JPEG is in hacks.php
// Due to lack of documentation, I don't know how to set max size of JPEG (or even if it's possible)
$image_jpeg_value = (APPLET_NO_JPEG != 0) ? '0' : '1';


// Set up applet header
// NOTE:  It is illegal to use '&' in parameter fields of Java applets,
// but we have to do it anyway since OekakiBBS will not decode '&amp;'
if ($edit) {
	$app_head_mode = 'edit';
} elseif ($anim) {
	$app_head_mode = 'anim';
} else {
	$app_head_mode = 'norm';
}

$app_head = "mode={$app_head_mode}&edittimes=".time()
	."&uid={$OekakiID}".'&vcode='.urlencode($OekakiPass);
if ($edit) {
	$app_head .= "&edit={$edit}";
}
if (!empty($app_head_pw)) {
	// OekakiBBS requires CGI encoding, not header encoding
	$app_head .= '&pw='.urlencode($app_head_pw);
}

// Set up applet exit
$app_url_exit = 'comment.php?mode=res_msg';
if ($edit) {
	// Wax Poteto only supports edit, not import
	$app_url_exit .= "&edit={$edit}";
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
	<meta http-equiv="content-type" content="text/html; charset=<?php echo $charset;?>" />
	<meta http-equiv="content-language" content="<?php echo $metatag_language;?>" />

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
	<applet id="oekakibbs"
		code="a.p.class"
		archive="oekakibbs.jar"
		width="<?php echo $xhack;?>"
		height="<?php echo $yhack;?>">

		<!-- Disable Java DirectX support -->
		<param name="java_arguments" value="-Dsun.java2d.noddraw=true -Dsun.java2d.d3d=false" />

		<param name="picw" value="<?php echo $xcord;?>" />
		<param name="pich" value="<?php echo $ycord;?>" />

		<param name="tooljpgpng" value="0" />
		<param name="tooljpg" value="<?php echo $image_jpeg_value;?>" />

		<param name="anime" value="<?php echo $anim;?>" />
		<param name="animesimple" value="1" />

<?php
if ($import) { ?>
		<param name="readfilepath" value="./" />
		<param name="readpicpath" value="./" />
		<param name="readpic" value="<?php echo $p_pic.$import.'.'.$row['ptype']; ?>" />

<?php
	if ($anim) {
?>
		<param name="readanmpath" value="./" />
		<param name="readanm" value="<?php echo $p_pic.$import.'.oeb'; ?>" />

<?php
	}
}
?>
		<param name="cgi" value="getoekakibbs.php?<?php echo w_html_chars($app_head);?>" />
		<param name="url" value="<?php echo w_html_chars($app_url_exit);?>" />
		<param name="target" value="_self" />

		<param name="popup" value="0" />
		<param name="tooltype" value="full" />
		<param name="passwd" value="hardidhihihiahrih" />
		<param name="passwd2" value="I like to eat, ice cream." />

		<param name="baseC" value="888888" />
		<param name="brightC" value="aaaaaa" />
		<param name="darkC" value="666666" />
		<param name="backC" value="000000" />

		<param name="mask" value="5" />
		<param name="toolpaintmode" value="1" />
		<param name="toolmask" value="1" />
		<param name="toollayer" value="1" />
		<param name="toolalpha" value="1" />
		<param name="toolwidth" value="200" />

		<param name="catalog" value="0" />
		<param name="catalogwidth" value="100" />
		<param name="catalogheight" value="100" />

		<?php tt('paint_need_java_link', 'http://www.java.com');?> 
	</applet>
</div>


<p style="text-align: center;">
	<input onclick="maximize_app('oekakibbs')" type="button" value="<?php tt('resizeapplet');?>" class="submit" />
</p>


<p style="text-align: center;">
	<input onclick="size_app('oekakibbs');" type="button" value="<?php tt('resize_to_this');?>" class="submit" />
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
			<li><?php tt('oekakihlp1');?></li>
			<li><?php tt('oekakihlp2');?></li>
			<li><?php tt('canvchgdest');?></li>
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
			<input type="hidden" name="anim" value="<?php echo $anim;?>">
<?php } ?>
			<input type="submit" name="Submit" value="<?php tt('word_modify');?>" class="submit" />
		</form>
	</td>
<?php } ?>
	</tr>
</table>
<br />


<?php

include 'footer.php';