<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.0x - Last modified 2010-03-04 (x:2011-01-11)
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


include 'header.php';


?>
<div id="contentmain">
	<br />
	<form name="form1" method="post" action="functions.php">
		<input name="action" type="hidden" value="pretrieve" />

		<!-- Retrieve -->
		<h1 class="header">
			<?php tt('lostpwd_title');?> 
		</h1>

		<div class="infotable">
			<table class="infomain">
			<tr>
			<td class="infoask" valign="top">
				<?php tt('word_username');?> 
			</td>
			<td class="infoenter" valign="top">
				<input name="username" type="text" class="txtinput" style="width: 100%;" />
			</td>
			</tr>

			<tr>
			<td class="infoask">&nbsp;</td>
			<td class="infoenter" valign="top">
				<p class="subtext">
					<?php tt('lostpwd_directions');?><br />
					<br />
					<input type="submit" name="register" value="<?php tt('word_submit');?>" class="submit" />
				</p>
			</td>
			</tr>
			</table>
		</div>
	</form>
	<br />
</div>


<?php

include 'footer.php';