<?php
/*
Wacintaki Poteto - Copyright 2004-2011 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.6 - Last modified 2011-02-02

WARNING:
This script is for diagnostic purposes only!
Do NOT put sensitive information into this script!
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


if (!$flags['owner']) {
	report_err( t('testvar1'));
}


include 'header.php';


// Input
$sql = trim(w_gpc('sql', 'raw'));
$sql_slashed = str_replace('"', '\"', $sql);


?>
<div id="contentmain">
<?php if (w_gpc('action') == 'sql_confirm') { ?>
	<form name="form2" method="post" action="sqltest.php">
		<input type="hidden" name="action" value="sql_query" />
		<input type="hidden" name="sql" value="<?php echo w_html_chars($sql);?>" />

		<h1 class="header">
			<?php echo('SQL confirm');?> 
		</h1>
		<div class="infotable" style="text-align: center;">
			<p>
				<?php tt('st_orig_query');?> <strong style="color: white; background-color: black;">&nbsp;<?php echo w_html_chars($sql);?>&nbsp;</strong>
			</p>
			<p>
				<?php tt('st_eval_query');?> <strong style="color: white; background-color: red;">&nbsp;<?php
					$sql_php = eval('return ("'.$sql_slashed.'" );');
					echo w_html_chars($sql_php);
				?>&nbsp;</strong>
			</p>

			<p>
				<input type="submit" name="sql_confirm" value="Run query" class="submit" />
			</p>
		</div>
	</form>
	<br />


<?php } ?>
<?php if (w_gpc('action') == 'sql_query') { ?>
	<input type="hidden" name="action" value="sql_query" />

	<h1 class="header">
		<?php tt('st_sql_header');?> 
	</h1>
	<div class="infotable" style="text-align: center;">
		<p>
<?php
		$sql_php = eval('return ("'.$sql_slashed.'" );');

	$result27 = db_query($sql_php);
	if ($result27) {
		web_line( t('st_query_ok'));

		$lines = 0;
		if ($result27 !== true && $result27 !== false) {
			$lines = db_num_rows($result27);
		}

		web_line( t('st_rows_aff', (int) $lines));
		if ($lines > 0 && $row = db_result($result27)) {
			echo web_line( t('st_first_res', w_html_chars($row) ));
		}
	} else {
		web_line( t('st_query_fail'));
		web_line(db_error());
	}
?>
		</p>
	</div>
	<br />


<?php } ?>
	<form name="form1" method="post" action="sqltest.php">
		<input type="hidden" name="action" value="sql_confirm" />

		<!-- Send -->
		<h1 class="header">
			<?php tt('st_sql_header');?> 
		</h1>
		<div class="infotable" style="text-align: center;">
			<p>
				<strong style="font-size: large; padding: 1px; color: white; background-color: red;"><?php tt('st_big_warn');?></strong>
			</p>

			<p>
				<?php tt('st_directions');?> 
			</p>

			<p>
<?php if (isset($db_p) && isset($db_mem)) { ?>
				&ldquo;$db_p&rdquo; = $OekakiPoteto_Prefix (comments, data, piccount, db version)<br />
				&ldquo;$db_mem&rdquo; = $OekakiPoteto_MemberPrefix (members, mailbox, log, all else);
<?php } else { ?>
				&ldquo;$OekakiPoteto_Prefix&rdquo; = comments, data, piccount, db version<br />
				&ldquo;$OekakiPoteto_MemberPrefix&rdquo; = members, mailbox, log, all else;
<?php } ?>
			</p>

			<script type="text/javascript">
				// &lt;![CDATA[
				function def_sql(num) {
					var text = document.getElementById("sql");

					switch (num) {
						case 0:
							text.value = '';
							break;
						case 1:
							text.value = hcp("SELECT `usrname` FROM {$db_mem}oekaki");
							break;
						case 2:
							text.value = hcp("INSERT INTO `{$db_mem}oekaki` SET `key`='value'");
							break;
						case 3:
							text.value = hcp("UPDATE `{$db_mem}oekaki` SET `piccount`=(piccount - 1) WHERE `usrname`='someone'");
							break;
						case 4:
							text.value = hcp("DELETE FROM `{$db_p}oekakicmt` WHERE `usrname`='somebody'");
							break;
						case 5:
							text.value = hcp("SELECT miscstring FROM {$OekakiPoteto_Prefix}oekakimisc WHERE miscname='dbversion'");
							break;
					}
				}
				// ]]&gt;
			</script>
			<p>
				<input type="button" value="<?php tt('word_erase');?>" onclick="def_sql(0);" class="submit" /> -
				<input type="button" value="SELECT" onclick="def_sql(1);" class="submit" /> -
				<input type="button" value="INSERT" onclick="def_sql(2);" class="submit" /> -
				<input type="button" value="UPDATE" onclick="def_sql(3);" class="submit" /> - 
				<input type="button" value="DELETE" onclick="def_sql(4);" class="submit" /> - 
				<input type="button" value="<?php tt('st_ver_btn');?>" onclick="def_sql(5);" class="submit" />
			</p>

			<p>
				<textarea id="sql" name="sql" cols="80" rows="5" class="multiline"><?php if (isset($sql)) {echo w_html_chars($sql);} ?></textarea>
			</p>

			<p>
				<input type="submit" name="sqlsub" value="<?php tt('submit_review');?>" class="submit" />
			</p>
		</div>
	</form>
</div>


<?php

include 'footer.php';