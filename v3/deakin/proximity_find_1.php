<?php
// Range of different functions. Need different post
if (isset($_POST["mapfunction"]))
{
	switch($_POST["mapfunction"])
	{
		case "window":
			// Show markers within window
			//echo showAllMarkers();
			echo showByWindow($_POST["swlat"],$_POST["swlong"],$_POST["nelat"],$_POST["nelong"]);
			break;
		case "date":
			// Animate the markers over their date		
			break;
		case "target":
			// Show all of a type of target
			break;
		case "ordnance":
			// Show all of a type of ordnance	
			break;
		case "cluster":
			// Show all of a large set of bombs. Greater than 400, generally, unless it's a b52 or a-class which is super large and has lots of bombs. Need airplace details. 
			break;
		case "bda":
			// Show bombs depending on the result of the bomb drop
			break;
		default:
			// Return error message, no option selected
			break;
	}
}
function showByWindow($swlat, $swlong, $nelat, $nelong)
{
	$result = buildResult($swlat, $swlong, $nelat, $nelong);
	constructXML($result);
}

function showByDate()
{
	
}
// Always get only what is in the map bounds, for speed.
function buildResult($swlat, $swlong, $nelat, $nelong)
{
	// Try sql connection, if that fails, use XML
	// try 
	// {
		// Database Connection

		$table = "`databack_geoloc_01`.`bomb_data`";
		include("../../../../restricted/php_db_info.php");
		
		// connect to MySQL server (host,user,password)
		$db_connect =  mysql_connect("localhost", "$username", "$password") or die ("<h1>Error - No connection to MySQL</h1>\n".mysql_error());	   

		// select the correct database
		$er = mysql_select_db("$database")or die ("<h1>Error - No connection to Database</h1>\nProbably don't have Privileges\n".mysql_error());

			$sql_query = "SELECT `lat`, `long`, `target`, `bda`, `date`, `ordnance`, `name`, `ord_class`, `total_bombs`, `load_lbs`, `category` FROM $table WHERE (`lat` BETWEEN '$swlat' AND '$nelat') AND (`long` BETWEEN '$swlong' AND '$nelong') ORDER BY `date` LIMIT 1000";
			$resultset = @mysql_query($sql_query) or die("<h1>Error - Insert Item failed!</h1>\n".mysql_error());  
		
		if($resultset){
				/*echo 		"<table ><caption>Data Table</caption>
								<tr>
								<th>Sortie ID</th>
								<th>Bombing Date</th>
								<th>Bomb Type General</th>
								
								<th>Lattitude</th>
								<th>Longitude</th>
								<th>Bomb Target Description</th>
								</tr>";*/
				$result = array();
				while ($row = mysql_fetch_assoc($resultset))
				{
					$result[] = $row;
				}
				//for ($result = array (); $row = mysql_fetch_assoc($resultset); $result[] = $row);				
				
			mysql_close($db_connect);

			
		}
	// }
	// catch (Exception $e)
	// {
		// // XML for test data 
		// $xml = simplexml_load_file("../resources/bombData.kml") or die('Error creating object');
		// foreach($xml->getDocNamespaces() as $strPrefix => $strNamespace) {
			// if(strlen($strPrefix)==0) {
				// $strPrefix="a"; //Assign an arbitrary namespace prefix.
			// }
			// $xml->registerXPathNamespace($strPrefix,$strNamespace);
		// }
		// $resulttop = $xml->xpath('//a:Placemark/a:description');
		// $result = array();
		// $row = array();
		// while(list( , $node) = each($resulttop)) {
			// $rowall = explode('<br />',$node);
			// $last = explode('=',$rowall[0]);
			// $row['lat'] = trim($last[1]);
			// $last = explode('=',$rowall[1]);
			// $row['long'] = trim($last[1]);
			// $last = explode('=',$rowall[2]);
			// $row['date'] = trim($last[1]);
			// $last = explode('=',$rowall[8]);
			// $row['load_lbs'] = trim($last[1]);
			// $last = explode('=',$rowall[9]);
			// $row['ordnance'] = trim($last[1]);
			// $last = explode('=',$rowall[10]);
			// $row['ord_class'] = trim($last[1]);
			// $last = explode('=',$rowall[11]);
			// $row['category'] = trim($last[1]);
			// $last = explode('=',$rowall[12]);
			// $row['target'] = trim($last[1]);
			// $last = explode('=',$rowall[13]);
			// $row['bda'] = trim($last[1]);
			// $last = explode('=',$rowall[14]);
			// $row['total_bombs'] = trim($last[1]);
			// if (((float)$row['lat'] > $swlat) && ((float)$row['lat'] < $nelat) && ((float)$row['long'] > $swlong) && ((float)$row['long'] < $nelong))
			// {
				// $result[] = $row;
			// }
		// }
	// }
	return $result;
}

function constructXML($result)
{
	if ($result)
	{
		//echo 'Result exists';
		$xml = '<?xml version="1.0" encoding="utf-8" ?>';
		$xml .= '<bomb_data>';
		
		// For XML
		foreach($result as $row)
		{
			$xml .= '<sortie>';
			$xml .=   '<target_info>';
			$xml .=   	'<lat>'.$row['lat'].'</lat>';
			$xml .=   	'<long>'.$row['long'].'</long>';
			$xml .=   	'<target>'.$row['target'].'</target>';
			$xml .=   	'<bda>'.$row['bda'].'</bda>';
			$xml .=   	'<date>'.$row['date'].'</date>';
			$xml .=   '</target_info>';
			$xml .=   '<bomb_info>';
			$xml .=       '<ordnance>'.$row['ordnance'].'</ordnance>';
			$xml .=       '<name>'.$row['name'].'</name>';
			$xml .=       '<ord_class>'.$row['ord_class'].'</ord_class>';
			$xml .=       '<total_bombs>'.$row['total_bombs'].'</total_bombs>';
			$xml .=       '<load_lbs>'.$row['load_lbs'].'</load_lbs>';
			$xml .=       '<category>'.$row['category'].'</category>';
			$xml .=   '</bomb_info>';
			$xml .= '</sortie>';

		}
		$xml .= '</bomb_data>';
	}
	header('Content-Type: text/xml'); 
	echo $xml;	
}
// function distance($lat1, $lon1, $lat2, $lon2, $unit) {
// /*::  Official Web site: http://www.geodatasource.com */
  // $theta = $lon1 - $lon2;
  // $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  // $dist = acos($dist);
  // $dist = rad2deg($dist);
  // $miles = $dist * 60 * 1.1515;
  // $unit = strtoupper($unit);
  // if ($unit == "K") {
    // return round(($miles * 1.609344),3)*1000;
  // } else if ($unit == "N") {
      // return ($miles * 0.8684);
    // } else {
        // return $miles;
      // }
// }

/*::  echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";*/
/*::  echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";*/
/*::  echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";*/
	?>

