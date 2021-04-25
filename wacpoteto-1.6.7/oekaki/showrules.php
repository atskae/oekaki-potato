<?php
/*
Wacintaki Poteto - Copyright 2004-2010 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6 - Last modified 2011-01-14
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


include 'header.php';


?>
<div id="contentmain">
	<br />
	<h1 class="header">
		<?php tt('word_rules');?> 
	</h1>

	<div class="infotable">
<?php
	include $cfg['res_path'].'/rules.php';
?> 
	</div>
	<br />
</div>


<?php

include 'footer.php';