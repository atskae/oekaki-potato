<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2012 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.13 - Last modified 2012-10-07
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


// Private?
if ($cfg['private'] == 'yes' && empty($OekakiU)) {
	all_done();
}

// Disabled?
if ($cfg['use_chat'] == 'no') {
	report_err( t('charommdisable'), true);
}

if ($cfg['guest_post'] == 'no' && !$flags['member']) {
	report_err( t('memaccesschat'), true);
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

<iframe id="chatarea" name="chatarea" src="chat.php" style="width: 100%; height: 450px;" frameborder="0">
	<?php tt('iframechat');?>
</iframe>
<hr />

<form action="chat.php" method="post" name="form1" target="chatarea" onsubmit="setTimeout(&quot;MM_setTextOfTextfield('comment','','')&quot;, 10);">
	<div class="pheader">
		<?php tt('word_message');?> 
		<input type="text" name="comment" class="ptxtinput" size="25" />
		<input type="hidden" name="send" value="Send" />
		<input type="submit" name="submit" value="Send" class="submit" />
		<a href="chat.php#bottom" target="chatarea"><?php tt('chat_scroll');?></a>
		| <a href="chat.php" target="chatarea"><?php tt('word_refresh');?></a>
		<input onclick="resize_chat(80);" type="button" value="Fit Window" class="submit" />
	</div>

<?php

if (empty($OekakiU) && $cfg['guest_post'] == 'yes') {
	?>
	<div class="pheader">
		<?php tt('chat_chatinfo');?> 
	</div>

	<div>
		<table class="pinfotable" style="width: 100%;">
		<tr>
		<td class="pinfoask" valign="top">
			<?php tt('word_name');?> 
		</td>
		<td class="pinfoenter" valign="top">
			<input type="text" name="name" value="<?php echo w_gpc('guestName');?>" class="ptxtinput" />
		</td>
		</tr>

		<tr>
		<td class="pinfoask" valign="top">
			<?php tt('word_email');?> 
		</td>
		<td class="pinfoenter" valign="top">
			<input type="text" name="email" value="<?php echo w_gpc('guestEmail');?>" class="ptxtinput" />
		</td>
		</tr>

		<tr>
		<td class="pinfoask" valign="top">
			<?php tt('word_url');?> 
		</td>
		<td class="pinfoenter" valign="top">
			<input type="text" name="url" value="<?php echo w_gpc('guestURL');?>" class="ptxtinput" />
		</td>
		</tr>
		</table>
	</div>
	<br />
<?php
}
?>
</form>
<br />

<hr />

<div class="pheader">
	<?php tt('word_help');?> 
</div>

<div class="pinfotable">
	<?php tt('chat_warning');?> 
</div>

</body>
</html>
<?php

w_exit();