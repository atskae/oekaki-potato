<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6 - Last modified 2011-01-25
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


db_close();



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
</head>

<body>


<!-- Usage -->
<div class="pheader">
	<?php tt('niftytoo_title');?> 
</div>

<div class="pinfotable">
	<?php tt('niftytoo_titlesub');?> 
</div>
<br />


<!-- Linking -->
<div class="pheader">
	<?php tt('niftytoo_linking');?> 
</div>

<table class="pinfotable" style="width: 100%;">
	<tr>
	<td class="pinfoask" style="width: 30%;">
		[url]<span style="font-weight: normal;">http://www.ninechime.com</span>[/url]
	</td>
	<td class="pinfoenter">
		<a href="#">http://www.ninechime.com</a>
	</td>
	</tr>

	<tr>
	<td class="pinfoask" style="width: 30%;">
		[url=http://www.ninechime.com]<span style="font-weight: normal;"><?php tt('lnksom');?></span>[/url]
	</td>
	<td class="pinfoenter">
		<a href="#"><?php tt('lnksom');?></a>
	</td>
	</tr>

	<tr><td colspan="2">&nbsp;</td></tr>


	<tr>
	<td class="pinfoask" style="width: 30%;">
		<span style="font-weight: normal;">http://www.ninechime.com</span>
	</td>
	<td class="pinfoenter">
		<a href="#">http://www.ninechime.com</a>
	</td>
	</tr>

	<tr>
	<td class="pinfoask" style="width: 30%;">
		&nbsp;
	</td>
	<td class="pinfoenter">
		<?php tt('urlswithot', '<strong>[url]</strong>');?> 
	</td>
	</tr>
</table>
<br />


<!-- Basics -->
<div class="pheader">
	<?php tt('niftytoo_basicfor');?> 
</div>

<table class="pinfotable" style="width: 100%;">
	<tr>
	<td class="pinfoask" style="width: 30%;">
		[color=#AA00AA]<span style="font-weight: normal;"><?php tt('nt_text');?></span>[/color]
	</td>
	<td class="pinfoenter">
		<?php echo nifty2_convert('[color=#AA00AA]'.t('nt_text').'[/color]');?> 
	</td>
	</tr>

	<tr>
	<td class="pinfoask" style="width: 30%;">
		[color=red]<span style="font-weight: normal;"><?php tt('nt_text');?></span>[/color]
	</td>
	<td class="pinfoenter">
		<?php echo nifty2_convert('[color=red]'.t('nt_text').'[/color]');?> 
	</td>
	</tr>

	<tr>
	<td class="pinfoask" style="width: 30%;">
		[color=yellow on black]<span style="font-weight: normal;"><?php tt('nt_text');?></span>[/color]
	</td>
	<td class="pinfoenter">
		<?php echo nifty2_convert('[color=yellow on black] '.t('nt_text').'[/color]')."\n";?> 
	</td>
	</tr>

	<tr><td colspan="2">&nbsp;</td></tr>


	<tr>
	<td class="pinfoask">
		[b]<span style="font-weight: normal;"><?php tt('nt_bold');?></span>[/b]
	</td>
	<td class="pinfoenter">
		<strong><?php tt('nt_bold');?></strong>
	</td>
	</tr>

	<tr>
	<td class="pinfoask">
		[i]<span style="font-weight: normal;"><?php tt('nt_italic');?></span>[/i]
	</td>
	<td class="pinfoenter">
		<i><?php tt('nt_italic');?></i>
	</td>
	</tr>

	<tr>
	<td class="pinfoask">
		[u]<span style="font-weight: normal;"><?php tt('nt_underline');?></span>[/u]
	</td>
	<td class="pinfoenter">
		<u><?php tt('nt_underline');?></u>
	</td>
	</tr>

	<tr>
	<td class="pinfoask">
		[del]<span style="font-weight: normal;"><?php tt('nt_strikethrough');?></span>[/del]
	</td>
	<td class="pinfoenter">
		<s><?php tt('nt_strikethrough');?></s>
	</td>
	</tr>
</table>
<br />


<!-- Intermediate -->
<div class="pheader">
	<?php tt('niftytoo_intermform');?> 
</div>

<table class="pinfotable" style="width: 100%;">
	<tr>
	<td class="pinfoask">
		[big]<span style="font-weight: normal;"><?php tt('nt_big');?></span>[/big]
	</td>
	<td class="pinfoenter">
		<big><?php tt('nt_big');?></big>
	</td>
	</tr>

	<tr>
	<td class="pinfoask">
		[small]<span style="font-weight: normal;"><?php tt('nt_small');?></span>[/small]
	</td>
	<td class="pinfoenter">
		<small><?php tt('nt_small');?></small>
	</td>
	</tr>

	<tr>
	<td class="pinfoask" style="width: 30%;">
		[code]<span style="font-weight: normal;"><?php tt('nt_preformatted');?></span>[/code]
	</td>
	<td class="pinfoenter">
		<code><?php tt('nt_preformatted');?></code>
	</td>
	</tr>

	</tr><td colspan="2">&nbsp;</td></tr>


	<tr>
	<td class="pinfoask">
		[[quote]]<span style="font-weight: normal;"><?php tt('nt_d_quote');?></span>[[/quote]]
	</td>
	<td class="pinfoenter">
		[quote]<?php tt('nt_d_quote');?>[/quote]
	</td>
	</tr>

	<tr>
	<td class="pinfoask">
		[[<?php tt('nt_ignore_tag');?>]]<span style="font-weight: normal;"><?php tt('nt_d_ignore');?></span>[[/<?php tt('nt_ignore_tag');?>]]
	</td>
	<td class="pinfoenter">
		[<?php tt('nt_ignore_tag');?>]<?php tt('nt_d_ignore');?>[/<?php tt('nt_ignore_tag');?>]
	</td>
	</tr>

	<tr>
	<td class="pinfoask">
		&nbsp;
	</td>
	<td class="pinfoenter">
		<?php tt('nt_use_double');?> 
	</td>
	</tr>

	</tr><td colspan="2">&nbsp;</td></tr>


	<tr>
	<td class="pinfoask">
		[quote]<span style="font-weight: normal;"><?php tt('nt_quoted');?></span>[/quote]
	</td>
	<td class="pinfoenter">
		<?php echo nifty2_convert('[quote]'.t('nt_quoted').'[/quote]')."\n";?><br />
	</td>
	</tr>
</table>


<p style="text-align: center;">
	<a onclick="window.close();" href="#"><?php tt('common_window');?></a>
</p>


</body>
</html>