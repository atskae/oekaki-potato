<?php
/*
Wacintaki Poteto Config Editor
Copyright 2009 Marc "Waccoon" Leveille
Version 1.5.0 - Last modified 10/25/2009

Last verified 12/28/2010
	+ Wacintaki = 1.5.0 to 1.5.5
	+ Wax       = 5.8.2

This script will restore your ownership flags in the event you lose them.
To use, put in your username in $usrname and upload to your OP directory and run it.

Don't forget to delete this file when you are done.
*/


//put your username here
$usrname = '';


// ---------------

header('Content-type: text/plain');

require ('db_layer.php');
$dbconn = db_open();

if (empty ($usrname)) {
	db_close();
	exit ('You must enter your user name into this script before you run it.');
}

$result = db_query("UPDATE {$OekakiPoteto_MemberPrefix}oekaki SET usrflags='GDIMU', rank=9 WHERE usrname='$usrname'");
if (!$result) {
	echo db_error()."\n\n";
}

db_close();

exit ('Flags restored');

?>