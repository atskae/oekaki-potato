<?php // Include only
if (!defined ('BOOT')) exit ('No bootstrap.  Exiting.');

/*
Wacintaki Poteto - Copyright 2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.0 - Last modified 2015-03-01
*/


if (!$glob['debug']) {
	w_exit('Tool disabled.');
}


$total_queries = db_get_num_queries();

if ($total_queries) {
	$queries = db_get_saved_queries();
}



?>
<style>
#db_debug_table {
	margin: auto;
	color: white;
	background-color: #606060;
	font-size: small;
	font-family: monospace;
	border-collapse: collapse;
}
#db_debug_table td, #db_debug_table th {
	border: 1px #A0A0A0 solid;
	padding: 0 5px 0 5px;
}
#db_debug_table th {
	background-color: black;
}
#db_debug_table td.time {
	background-color: black;
}
#db_debug_table tr.summary {
	background-color: black;
}
</style>


<div>
	<table id="db_debug_table">
	<tr class="summary">
	<th>
		Time
	</th>
	<th>
		SQL
	</th>
	</tr>

<?php
		if ($total_queries > 0) {
			foreach ($queries as $query) {
				$my_trace = '';
				if (!empty ($query[2])) {
					$my_trace  = "<br />\n<span style=\"color: #FF9090;\">".htmlspecialchars($query[2]).'</span>';
					$my_trace .= "<br />\n<span style=\"color: #FF9090;\">".htmlspecialchars($query[3]).'</span>';
				}

				echo "\t<tr>\n\t<td class=\"time\">".htmlspecialchars($query[1])."</td>\n\t<td>".htmlspecialchars($query[0]).$my_trace."</td>\n\t</tr>\n\n";
			}
		}
		echo "\t<tr class=\"summary\">\n\t<td>".sprintf('%.5f', db_get_time_queries() )."</td>\n\t<td class=\"summary\">".$total_queries." Queries</td>\n\t</tr>\n";
?>
	</table>
</div>
