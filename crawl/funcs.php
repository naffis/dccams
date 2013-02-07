<?php


function get_chunks($pageContent) {
	$marker = "<option value";

	$currentPos = 0;

	while(strpos($pageContent, $marker, $currentPos)) {
		$indexStart = strpos($pageContent, $marker, $currentPos);
		echo "indexStart = ".$indexStart."<br>";

		if(strpos($pageContent, $marker, $indexStart + strlen($marker)))
			$indexEnd = strpos($pageContent, $marker, $indexStart + strlen($marker));
		else
			$indexEnd = strlen($pageContent);

		echo "indexEnd = ".$indexEnd."<br>";

		$currentPos = $indexEnd;
		echo "currentPos = ".$currentPos."<br>";

		$chunk = substr($pageContent, $indexStart, $indexEnd - $indexStart);
		//echo "chunk =".$chunk."<br>";

		// parse and insert the chunk into the database
		parse($chunk);

	}

}

function parse($chunk) {

	$currentP = 0;
	//echo "chunk =".$chunk."<br>";

	$dblink = mysql_connect("localhost", "naffis_naffis", "naffis04host") or die("ERROR: count not connect");
	mysql_select_db("naffis_mapdata", $dblink) or die("ERROR: count not select db");

	$camid = "";
	$address = "";

	// get the address
	if(strpos($chunk, "option value=\"", $currentP)) {
		$start = strpos($chunk, "option value=\"", $currentP) + strlen("option value=\"");
		echo "camid start = ".$start."<br>";

		$end = strpos($chunk, "\"", $start);
		echo "camid end = ".$end."<br>";

		$camid = substr($chunk, $start, $end-$start);
		echo "camid = ".$camid."<br>";
		$currentP = $end;
	}

	// get the address
	if(strpos($chunk, "\">", $currentP)) {
		$start = strpos($chunk, "\">", $currentP) + strlen("\">");
		echo "address start = ".$start."<br>";

		$end = strpos($chunk, "</option>", $start);
		echo "address end = ".$end."<br>";

		$address = substr($chunk, $start, $end-$start);
		echo "address = ".$address."<br>";
		$currentP = $end;
	}

	// now insert into the database;
	$sql = "INSERT INTO dccams (
					cam_id,
					address
				) VALUES (
					'$camid',
					'$address'
			)";

	echo $sql."<br>";
	$result = mysql_query($sql, $dblink) or die("ERROR: insert failed<br>sql = ".$sql."<br>");
	echo "inserted row<br>";

}

function escape($str) {
	return addslashes($str);
}

function getGooglePage($address) {
	$address = fixAddress($address);
	$url = "http://maps.google.com/maps?q=";
	$url = trim($url).trim($address);
	$url = trim($url)."+Washington+DC";
	$url = trim($url)."&output=js";
	echo "url = ".$url."<br>";
	return file_get_contents($url);
}

function fixAddress($address) {
	echo "address before fix = ".$address."<br>";

	$address = str_replace(" N W ", " NW ", $address);
	$address = str_replace(" N E ", " NE ", $address);
	$address = str_replace(" S E ", " SE ", $address);
	$address = str_replace(" S W ", " SW ", $address);
	$address = str_replace("Wisc ", " Wisconsin ", $address);
	$address = str_replace(" & ", " and ", $address);
	//$address = str_replace(".", "", $address);
	$address = str_replace(" ", "+", $address);
	$address = str_replace("++++", "+", $address);
	$address = str_replace("+++", "+", $address);
	$address = str_replace("++", "+", $address);

	echo "address = ".$address."<br>";

	return $address;
}

function getCoords() {
	$dblink = mysql_connect("localhost", "naffis_naffis", "naffis04host") or die("ERROR: count not connect");
	mysql_select_db("naffis_mapdata", $dblink) or die("ERROR: count not select db");

	$sql = "select cam_id, address from dccams";
	$result = mysql_query($sql, $dblink);
	$numrows = mysql_num_rows($result);

	if($numrows > 0) {
		for($i = 0; $i < $numrows; $i++) {
			echo "getCoords i = ".$i."<br>";

			$row = mysql_fetch_array($result);

			$camid = $row["cam_id"];
			$address = $row["address"];

			$content = getGooglePage($address);

			if(strpos($content, "<refinement><query>", $currentP)) {
				// the address was not correct so get the suggested one
				$start = strpos($content, "<refinement><query>", 0) + strlen("<refinement><query>");
				echo "start = ".$start."<br>";
				$end = strpos($content, "</query>", $start);
				echo "end = ".$end."<br>";
				$address = substr($content, $start, $end-$start);
				$content = getGooglePage($address);
			}

			$coords[2] = array("", "");
			$coords = parseCoords($content);

			$sqlInsert = "UPDATE dccams SET lat = '".$coords[0]."', lon = '".$coords[1]."' WHERE cam_id = ".$camid;
			echo "sqlInsert = ".$sqlInsert."<br>";
			$insertresult = mysql_query($sqlInsert, $dblink) or die("ERROR: insert failed<br>sqlInsert = ".$sqlInsert."<br>");
		}
	}
}

function parseCoords($content) {

	// <point lat="38.908890" lng="-77.004630"/>
	// <point lat="38.891011" lng="-76.982346"/>

	$currentP = 0;
	$coords[2] = array("", "");

	echo $content."<br>";

	// get the lat
	if(strpos($content, "<point lat=\"", $currentP)) {
		$start = strpos($content, "<point lat=", $currentP) + strlen("<point lat=\"");
		echo "start = ".$start."<br>";

		$end = strpos($content, "\"", $start+1);
		echo "end = ".$end."<br>";

		$coords[0] = substr($content, $start, $end-$start);
		echo "lat = ".$coords[0]."<br>";
		$currentP = $end;
	}

	// get the lon
	if(strpos($content, "lng=\"", $currentP)) {
		$start = strpos($content, "lng=", $currentP) + strlen("lng=\"");
		$end = strpos($content, "\"", $start+1);

		$coords[1] = substr($content, $start, $end-$start);
		echo "lon = ".$coords[1]."<br>";
		$currentP = $end;
	}

	return $coords;
}

function deleteNoCoords() {
	$dblink = mysql_connect("localhost", "naffis_naffis", "naffis04host") or die("ERROR: count not connect");
	mysql_select_db("naffis_mapdata", $dblink) or die("ERROR: count not select db");

	$sql = "delete from person where lat = '' OR lon = ''";
	$result = mysql_query($sql, $dblink);
}


?>