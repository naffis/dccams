<?php
	require_once('funcs.php');

	$newline = "<br />";

	$url = "http://www.naffis.com/dccams/crawl/data2.txt";

	// get the number of records found
	echo "Beginning parsing...<br>";

	$dblink = mysql_connect("localhost", "naffis_naffis", "naffis04host") or die("ERROR: count not connect");
	mysql_select_db("naffis_mapdata", $dblink) or die("ERROR: count not select db");
	$sql = "DELETE FROM dccams";
	$result = mysql_query($sql, $dblink) or die("ERROR: delete failed<br>sql = ".$sql."<br>");

	$content = file_get_contents($url);

	get_chunks($content);

	echo "Done parsing...<br>";

	echo "Getting coordinates...<br>";
	getCoords();
	echo "Done getting coordinates...<br>";
/*
	echo "deleting empty coordinates...<br>";
	deleteNoCoords();
	echo "Done deleting empty coordinates...<br>";
*/

?>