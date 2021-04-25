<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.10 - Last modified 2011-12-18
*/


/*
NOTES:
	Be careful with the CDATA tag, here.  Technically, it will only work with
	a valid XML (text/xml) MIME type, and Wacintaki uses texl/html with its
	XHTML source.  Firefox does not use CDATA tags correctly with an HTML MIME
	type, and IE... well, is IE.

	Wacintaki therefore uses hcp() to escape HTML tags.  Its usage should be
	self-explanatory.
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


if (!$flags['admin']) {
	report_err( t('err_editnew'));
}


include 'header.php';


$myfile = $cfg['res_path'].'/banner.php';
if (file_exists($myfile)) {
	if (!is_writable($myfile)) {
		report_err( t('fislockvred', $myfile));
	}
	$banner_contents = file_get_contents($myfile);
} else {
	$banner_contents = '';
}

$myfile = $cfg['res_path'].'/notice.php';
if (file_exists($cfg['res_path'].'/notice.php')) {
	if (!is_writable($myfile)) {
		report_err( t('fislockvred', $myfile));
	}
	$notice_contents = file_get_contents($myfile);
} else {
	$notice_contents = '';
}


// Applets present?
$more_applets = '';
if (file_exists('oekakibbs.jar')) {
	$more_applets .= '\t\t\t{{option value=\'oekakibbs\'}}OekakiBBS{{/option}}\n';
}
if (file_exists('chibipaint.jar')) {
	$more_applets .= '\t\t\t{{option value=\'chibipaint\'}}ChibiPaint{{/option}}\n';
}


?>
<div id="contentmain">
	<form name="form1" method="post" action="functions.php">
		<input name="action" type="hidden" value="editnotice" />

		<h1 class="header">
			<?php tt('header_ebanner');?> 
		</h1>

		<div class="infotable" style="text-align: center;">
			<script type="text/javascript">
				// &lt;![CDATA[
				function def_banner(num) {
					var text = document.getElementById("bannerarea");

					switch (num) {
						case 0:
							text.value = default_banner;
							break;
						case 1:
							text.value = '';
							break;
						case 2:
							text.value = hcp("{{img src='resource/banner.jpg' style='margin: auto;' alt='Banner' /}}\n");
							break;
					}
				}
				// ]]&gt;
			</script>

			<p>
				<?php tt('htmlphpallow');?><br />
				<?php tt('bannermsg');?><br />
				<br />
				<input type="button" value="<?php tt('word_default');?>" onclick="def_banner(0);" class="submit" style="color: white; background-color: #609050;" /> -
				<input type="button" value="<?php tt('word_erase');?>" onclick="def_banner(1);" class="submit" style="color: white; background-color: #C04040;" /> -
				<input type="button" value="<?php tt('btn_add_banner');?>" onclick="def_banner(2);" class="submit" />
			</p>

			<p>
				<textarea id="bannerarea" name="banneredit" cols="80" rows="20" class="multiline"><?php echo w_html_chars($banner_contents); ?></textarea>
			</p>
		</div>
		<br />


		<h1 class="header">
			<?php tt('enotice_title');?> 
		</h1>

		<div class="infotable" style="text-align: center;">
			<script type="text/javascript">
				// &lt;![CDATA[
				function def_news(num) {
					var text = document.getElementById("newsarea");

					switch (num) {
						case 0:
							text.value = default_notice;
							break;
						case 1:
							text.value = '';
							break;
						case 2:
							text.value = hcp("{{div class='infotable' style='margin: 0 50px 0 50px; text-align: center;'}}\n\tType in your text here.\n\tRemove (class='infotable') to get rid of the blank background.\n{{/div}}");
							break;
						case 3:
							text.value = hcp("{{div style='height: 200px; overflow: auto; text-align: center;'}}\n\tChange the height to whatever you need.\n\tIf you need something smaller, use a Centered Box instead.\n{{/div}}");
							break;
						case 4:
							// Contains PHP
							text.value = hcp("{{div class='infotable' style='text-align: center; margin: 0 50px 0 50px;'}}\n\t{{form action='draw.php' method='get' style='margin: 0; padding: 0;'}}\n\t\tApplet:\n\t\t{{select name='applet' class='multiline'}}\n\t\t\t{{option selected='selected' value='shi'}}ShiPainter{{/option}}\n\t\t\t{{option value='shipro'}}ShiPainter Pro{{/option}}\n\t\t\t{{option value='paintbbs'}}PaintBBS{{/option}}\n<?php echo $more_applets;?>\t\t{{/select}}\n\n\t\tAnim:\n\t\t{{input name='anim' type='checkbox' \/}}\n\n\t\tWidth:\n\t\t{{input name='canvasx' type='text' value='{{?=\$cfg['def_x'];?}}' size='4' class='txtinput' \/}}\n\t\tHeight:\n\t\t{{input name='canvasy' type='text' value='{{?=\$cfg['def_y'];?}}' size='4' class='txtinput' \/}}\n\t\t&nbsp;\n\t\t{{input type='submit' name='Submit' value='Submit' class='submit' \/}}\n\t{{/form}}\n{{/div}}");
							break;
					}
				}
				// ]]&gt;
			</script>

			<p>
				<?php tt('htmlphpallow');?><br />
				<?php tt('noticemsg');?><br />
				<br />
				<input type="button" value="<?php tt('word_default');?>" onclick="def_news(0);" class="submit" style="color: white; background-color: #609050;" /> -
				<input type="button" value="<?php tt('word_erase');?>" onclick="def_news(1);" class="submit" style="color: white; background-color: #C04040;" /> -
				<input type="button" value="<?php tt('centrebox');?>" onclick="def_news(2);" class="submit" /> -
				<input type="button" value="<?php tt('scrollbox');?>" onclick="def_news(3);" class="submit" /> - 
				<input type="button" value="<?php tt('quickdraw');?>" onclick="def_news(4);" class="submit" />
			</p>

			<p>
				<textarea id="newsarea" name="newsedit" cols="80" rows="20" class="multiline"><?php echo w_html_chars($notice_contents); ?></textarea>
			</p>
		</div>
		<br />


		<h1 class="header">
			<?php tt('submitchange');?> 
		</h1>

		<div class="infotable" style="text-align: center;">
			<p>
				<input type="submit" name="noticesub" value="<?php tt('word_edit');?>" class="submit" />
			</p>
		</div>
	</form>
</div>


<?php
	// Make sure this is after the textarea element.  Do not use
	// onload(), otherwise this will not work correctly if the
	// banner or images take a while to load.
?>
<script type="text/javascript">
	// &lt;![CDATA[
	var default_banner = document.getElementById("bannerarea").value;
	var default_notice = document.getElementById("newsarea").value;
	// ]]&gt;
</script>


<?php

include 'footer.php';