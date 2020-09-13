<?php
// Range of different functions. Need different post
if (isset($_POST["mapfunction"]))
{
	if (isset($_POST['filtervalue']))
	{
		$filtervalue = $_POST['filtervalue'];
	}
	else
	{
		$filtervalue = '';
	}
	if (isset($_POST['filterfield']))
	{
		$filterfield = $_POST['filterfield'];
	}
	else
	{
		$filterfield = '';
	}
	switch($_POST["mapfunction"])
	{
		case "window":
			// Show markers within window
			//echo showAllMarkers();
			// $filterfield = '';
			// $filtervalue = '';
			echo showByWindow($_POST["swlat"],$_POST["swlong"],$_POST["nelat"],$_POST["nelong"], $filterfield, $filtervalue);
			
			break;
		case "date":
			// Animate the markers over their date		
			$filterfield = 'date';
			break;
		case "target":
			// Show all of a type of target
			$filterfield = 'target';

			break;
		case "ordnance":
			// Show all of a type of ordnance	
			$filterfield = 'ord_class';
			break;
		case "cluster":
			// Show all of a large set of bombs. Greater than 400, generally, unless it's a b52 or a-class which is super large and has lots of bombs. Need airplane details. 
			$filterfield = 'total_bombs';
			break;
		case "bda":
			// Show bombs depending on the result of the bomb drop
			$filterfield = 'bda';
			break;
		default:
			// Return error message, no option selected
			$filterfield = '';
			$filtervalue = '';
			break;
	}
	
}
function showByWindow($swlat, $swlong, $nelat, $nelong, $filterfield, $filtervalue)
{
	$result = buildResult($swlat, $swlong, $nelat, $nelong, $filterfield, $filtervalue);
}

function showByDate()
{
	
}
// Always get only what is in the map bounds, for speed.
function buildResult($swlat, $swlong, $nelat, $nelong, $filterfield, $filtervalue)
{

	// Database Connection

	$table = "`databack_geoloc_01`.`bomb_data`";
	include("../../../../restricted/php_db_info.php");
	
	// connect to MySQL server (host,user,password)
	$db_connect =  mysql_connect("localhost", "$username", "$password") or die ("<h1>Error - No connection to MySQL</h1>\n".mysql_error());	   

	// select the correct database
	$er = mysql_select_db("$database")or die ("<h1>Error - No connection to Database</h1>\nProbably don't have Privileges\n".mysql_error());

		$sql_query = "SELECT `lat`, `long`, `target`, `bda`, `date`, `ordnance`, `name`, `ord_class`, `total_bombs`, `load_lbs`, `category` FROM $table WHERE (`lat` BETWEEN '$swlat' AND '$nelat') AND (`long` BETWEEN '$swlong' AND '$nelong') ";
		if ($filterfield != '')
		{
			$sql_query .= "AND $filterfield = '$filtervalue' ORDER BY `date` ";
		}
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
			$xml = '<?xml version="1.0" encoding="utf-8" ?>';
			$xml .= '<bd>';

			while ($row = mysql_fetch_assoc($resultset))
			{
					// For XML
					if (($filterfield != '') || ($row[$filterfield] == $filtervalue))
					{
						$xml .= '<st>';
						$xml .=   '<ti>';
						$xml .=   	'<lt>'.$row['lat'].'</lt>';
						$xml .=   	'<lg>'.$row['long'].'</lg>';
						$xml .=   	'<tg>'.$row['target'].'</tg>';
						$xml .=   	'<bda>'.$row['bda'].'</bda>';
						$xml .=   	'<date>'.$row['date'].'</date>';
						$xml .=   '</ti>';
						$xml .=   '<bi>';
						$xml .=       '<od>'.$row['ordnance'].'</od>';
						$xml .=       '<oc>'.$row['ord_class'].'</oc>';
						$xml .=       '<tb>'.$row['total_bombs'].'</tb>';
						$xml .=       '<ll>'.$row['load_lbs'].'</ll>';
						$xml .=       '<ct>'.$row['category'].'</ct>';
						$xml .=   '</bi>';
						$xml .= '</st>';
					}
			}
			$xml .= '</bd>';
			
			header('Content-Type: text/xml'); 
			echo $xml;				
			
		mysql_close($db_connect);

		
	}

	return $result;
}

function constructXML($result)
{

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

