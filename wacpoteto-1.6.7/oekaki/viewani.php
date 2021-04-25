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


// Private?
if ($cfg['private'] == 'yes' && empty($OekakiU)) {
	all_done();
}

// Input
$recno = w_gpc('recno', 'i+');


// SQL
$result = db_query("SELECT * FROM {$db_p}oekakidta WHERE ID_2=".$recno);
if (!$result) {
	report_err('Unable to read picture #'.$recno);
}
$row = db_fetch_array($result);


// Set file info
$file_name = "{$cfg['op_pre']}{$row['PIC_ID']}.";

if ($row['datatype'] == 1) {
	$file_name .= 'oeb';
} elseif ($row['datatype'] == 4 || $row['datatype'] == 5) {
	$file_name .= 'chi';
} else {
	$file_name .= 'pch';
}
$file_path = "{$cfg['op_pics']}/{$file_name}";

$file_size = 0;
if (file_exists($file_path)) {
	$file_size = filesize($file_path);
	$file_size = round(($file_size / 1024), 2);
}

$have_animation = true;
if ($row['animation'] != 1 || $file_size == 0) {
	$have_animation = false;
}



// Start HTML
send_html_headers();
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
	<meta http-equiv="Content-Language" content="<?php echo $metatag_language;?>" />

	<title><?php echo $cfg['op_title'];?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo $cssinclude;?>" title="<?php echo $template_name;?>" />

	<script type="text/javascript" src="Poteto.js">
	</script>
</head>

<body>


<!-- View Info -->
<div class="pheader">
<?php if ($have_animation) { ?>
	[<strong><?php ne_echo($row['PIC_ID'], '0');?></strong>]
	<strong><?php tt('word_artist');?>:</strong> <a onclick="openWindow('profile.php?user=<?php echo urlencode($row['usrname']);?>', 400, 500); return false" href="profile.php?user=<?php echo urlencode($row['usrname']);?>"><?php echo w_html_chars($row['usrname']);?></a>
	| <strong><?php tt('install_title2');?>:</strong> <?php ne_echo(w_html_chars($row['title']), t('no_pic_title') );?> 
<?php

// Time
if ($row['edittime'] > 10 ) {
	$edit_s = ceil($row['edittime'] % 60);
	$edit_m = ceil($row['edittime'] / 60 % 60);
	$edit_h = ceil($row['edittime'] / 3600 % 60);

	if ($edit_h)
		$edit_s = 0;

	$edit_out = '';
	if ($edit_h)
		$edit_out .= "{$edit_h}h ";
	if ($edit_m)
		$edit_out .= "{$edit_m}m ";
	if ($edit_s)
		$edit_out .= "{$edit_s}s ";
} else {
	$edit_out = t('word_unknown');
}
echo "		| <strong>".t('word_time').":</strong> {$edit_out}\n";

?>
<?php } else {
	echo "&nbsp;\n";
} ?>
</div>

<?php
if ($have_animation) {
?>
<div class="pinfotable" style="text-align: center;">
	<hr />

<?php
	// Applet code

	// Works for PCH Viewer and OekakiBBS
	$app_x = $row['px'];
	if ($row['px'] < 100)
		$app_x += (100 - $row['px']);

	// Which applet?
	switch ($row['datatype']) {
		case 0:
		// PaintBBS
?>
	<applet name="pch" code="pch.PCHViewer.class" archive="shipainter/PCHViewer.jar" width="<?php echo $app_x;?>" height="<?php echo $row['py'] + 25;?>">
		<!-- Disable Java DirectX support -->
		<param name="java_arguments" value="-Dsun.java2d.noddraw=true -Dsun.java2d.d3d=false" />

		<param name="speed" value="2" />
		<param name="image_width" value="<?php echo $row['px'];?>" />
		<param name="image_height" value="<?php echo $row['py'];?>" />
		<param name="pch_file" value="<?php echo $file_path;?>" />
		<param name="run" value="true" />
		<param name="buffer_progress" value="true" />
		<param name="buffer_canvas" value="true" />
	</applet>
<?php
		break;


		case 1:
		// OekakiBBS requires "picw" to match applet width!
		// "pich" must be actual picture height.
?>
	<applet name="oeb" code="a.p.class" archive="oekakibbs.jar" width="<?php echo $app_x;?>" height="<?php echo $row['py'] + 38;?>">
		<!-- Disable Java DirectX support -->
		<param name="java_arguments" value="-Dsun.java2d.noddraw=true -Dsun.java2d.d3d=false" />

		<param name="popup" value="0" />
		<param name="anime" value="2" />
		<param name="readanm" value="<?php echo $file_path;?>" />
		<param name="readanmpath" value="./" />
		<param name="picw" value="<?php echo $app_x;?>" />
		<param name="pich" value="<?php echo $row['py'];?>" />
		<param name="baseC" value="9999FF" />
		<param name="brightC" value="C0C0FF" />
		<param name="darkC" value="6666FF" />
		<param name="backC" value="CCCCFF" />
		<param name="buffer_canvas" value="true" />
	</applet>
<?php
		break;


		case 2:
		case 3:
		// ShiPainter
?>
	<applet name="pch" codebase="./shipainter" code="pch2.PCHViewer.class" archive="PCHViewer.jar" width="<?php echo $app_x;?>" height="<?php echo $row['py'] + 25;?>">
		<!-- Disable Java DirectX support -->
		<param name="java_arguments" value="-Dsun.java2d.noddraw=true -Dsun.java2d.d3d=false" />

		<param name="speed" value="2" />
		<param name="image_width" value="<?php echo $row['px'];?>" />
		<param name="image_height" value="<?php echo $row['py'];?>" />
		<param name="pch_file" value="../<?php echo $file_path;?>" />

		<param name="res.zip" value="res.zip" />
		<param name="tt.zip" value="tt.zip" />

		<param name="run" value="true" />
		<param name="buffer_progress" value="false" />
		<param name="buffer_canvas" value="false" />

		<param name="layer_count" value="5" />
		<param name="layer_max" value="8" />
		<param name="layer_last" value="2" />
		<param name="quality" value="1" />
	</applet>
<?php
		break;


		case 4:
		case 5:
		// No animation support, yet
?>
	<p style="text-align: center;">
		Chibi Paint and ChickenPaint do not yet support animation.
	</p>
<?php
		break;
	}
?>
	<br />

	<?php tt('viewani_files').': '.$file_size.'KB';?> 
</div>
<?php
}

if (!$have_animation) {
?>
<div class="pinfotable" style="text-align: center;">
	<hr />

	<p style="text-align: center;"><?php tt('noanim');?></p>
</div>
<?php 
}
?>

<?php if (isset($row['PIC_ID']) && $row['PIC_ID']) { ?>
<p style="text-align: center;">
	<?php tt('word_download');?>: <a href="<?php echo $file_path;?>"><?php echo $file_name;?></a>
</p>

<?php } ?>
<p style="text-align: center;">
	<a onclick="window.close();" href="#"><?php tt('common_window');?></a>
</p>

</body>
</html>
<?php

db_close();