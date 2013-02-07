<?php

$type = $_GET['type'];
$zip_code = $_GET['zip_code'];

$sql = "select * from dccams order by cam_id asc";

$dblink = mysql_connect("localhost", "connexo_connexon", "90a!2$") or die("ERROR: count not connect");
mysql_select_db("connexo_mapdata", $dblink) or die("ERROR: count not select db");

$result = mysql_query($sql, $dblink) or die("ERROR: query failed<br>sql = ".$sql."<br>");
$num = mysql_numrows($result);

$xmlString = "";

if($type == "map") {
	$xmlString .= "<?xml version=\"1.0\"?>";
	$xmlString .= "<page>";
	$xmlString .= "<title></title>";
	$xmlString .= "<query></query>";
	$xmlString .= "<request>";
	$xmlString .= "<url></url>";
	$xmlString .= "<query></query>";
	$xmlString .= "</request>";

	$centerLat=mysql_result($result,0,"lat");
	$centerLon=mysql_result($result,0,"lon");

	$xmlString .= "<center lat=\"".$centerLat."\" lng=\"".$centerLon."\"/>";
	$xmlString .= "<span lat=\"0.062134\" lng=\"0.104253\"/>";
	$xmlString .= "<searchcenter lat=\"".$centerLat."\" lng=\"".$centerLon."\"/>";
	$xmlString .= "<searchspan lat=\"0.062134\" lng=\"0.104253\"/>";

	$xmlString .= "<overlay panelStyle=\"http://www.naffis.com/dccams/xsl/geocodepanel.xsl\">";

	$i=0;
	while ($i < $num) {
		$cam_id=mysql_result($result,$i,"cam_id");
		$address=mysql_result($result,$i,"address");
		$lat=mysql_result($result,$i,"lat");
		$lon=mysql_result($result,$i,"lon");

		$xmlString .= "<location infoStyle=\"http://www.naffis.com/dccams/xsl/infostyle_map.xsl\" id=\"".$i."\">\n";
		$xmlString .= "<point lat=\"".$lat."\" lng=\"".$lon."\"/>\n";
		$xmlString .= "<icon image=\"http://www.naffis.com/dccams/images/society.png\" class=\"local\"/>\n";
		$xmlString .= "<info>\n";
		$xmlString .= "<id>".$i."</id>\n";
		$xmlString .= "<title xml:space=\"preserve\">Camera View</title>\n";

		$xmlString .= "<camid>".$cam_id."</camid>\n";

		$address = str_replace("&", "&amp;", $address);
		$address = str_replace("&nbsp;", " ", $address);
		$xmlString .= "<address>".$address."</address>\n";

		$xmlString .= "</info>\n";
		$xmlString .= "</location>\n";

		$i++;
	}

	$xmlString .= "</overlay>";
	$xmlString .= "</page>";

}
else if($type == "list") {

	$xmlString = "<cams>";

	$i=0;
	while ($i < $num) {
		$address=mysql_result($result,$i,"address");

		$address = str_replace("&nbsp;", " ", $address);
		$address = str_replace("<br>", " ", $address);
		$address = str_replace("\n", "", $address);
		$address = str_replace("nbsp;", " ", $address);
		$address = str_replace("&", "&amp;", $address);
		$address = str_replace(".", "", $address);

		$address = trim($address);

		$xmlString .= "<cam>\n";
		$xmlString .= "<id>".$i."</id>\n";
		$xmlString .= "<address>".$address."</address>\n";
		$xmlString .= "</cam>\n";

		$i++;
	}
	$xmlString .= "</cams>";

}
	// content type is xml
	header("Content-Type: text/xml");

	// print out the xml file we sucked in
	print($xmlString)

?>