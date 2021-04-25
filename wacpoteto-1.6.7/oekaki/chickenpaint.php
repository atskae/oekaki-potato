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



// Start HTML
send_html_headers('html5');
?>
<head>
	<meta charset="UTF-8">

	<title><?php echo $cfg['op_title'];?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo $cssinclude;?>" title="<?php echo $template_name;?>" />

	<script type="text/javascript" src="Poteto.js">
	</script>

	<script type="text/javascript">
		/* Check for native pointer event support before PEP adds its polyfill */
		if (window.PointerEvent) {
			window.hasNativePointerEvents = true;
		}
	</script>

	<script src="chickenpaint/lib/raf.js"></script>
	<script src="chickenpaint/lib/es6-promise.min.js"></script>
	<script src="chickenpaint/lib/jquery-2.2.1.min.js"></script>
	<script src="chickenpaint/lib/pep.min.js"></script>
	<script src="chickenpaint/lib/pako.min.js"></script>
	<script src="chickenpaint/lib/keymaster.js"></script>
	<script src="chickenpaint/lib/EventEmitter.min.js"></script>
	<script src="chickenpaint/lib/FileSaver.min.js"></script>
	<script src="chickenpaint/lib/bootstrap.min.js"></script>

	<script src="chickenpaint/js/chickenpaint.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			new ChickenPaint({
				// Warning: Do not HTML encode any values here

				uiElem: document.getElementById("chickenpaint-parent"),
				canvasWidth: <?php echo $xcord;?>,
				canvasHeight: <?php echo $ycord;?>,
<?php if (!empty($app_import)) { ?>
				loadImageUrl: "<?php echo $app_import;?>",
<?php } ?>
<?php if ($import && file_exists($p_pic.$import.'.chi')) { ?>
				loadChibiFileUrl: "<?php echo $p_pic.$import.'.chi';?>",
<?php } ?>
				saveUrl: "chickenpaintget.php?<?php echo $app_head;?>",
				postUrl: "<?php echo $app_url_exit;?>",
				//exitUrl: "index.php",
				allowSave: true,
				allowMultipleSends: false,
				resourcesRoot: "chickenpaint/"
			});
		});
	</script>

	<link rel="stylesheet" type="text/css" href="chickenpaint/css/chickenpaint.css" />
</head>

<body>


<div style="text-align: center;">
	<div id="chickenpaint-parent"></div>
</div>
<br />
<br />


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


<?php

include 'footer.php';