<?php

if (isset($_POST['getvalues']))
{
	
	// Database Connection
	$fieldname = $_POST['getvalues'];
	$table = "`databack_geoloc_01`.`bomb_data`";
	include("../../../../restricted/php_db_info.php");
	
	// connect to MySQL server (host,user,password)
	$db_connect =  mysql_connect("localhost", "$username", "$password") or die ("<h1>Error - No connection to MySQL</h1>\n".mysql_error());	   

	// select the correct database
	$er = mysql_select_db("$database")or die ("<h1>Error - No connection to Database</h1>\nProbably don't have Privileges\n".mysql_error());

	$sql_query = "SELECT distinct ('$fieldname') FROM $table";
	$resultset = @mysql_query($sql_query) or die("<h1>Error - Insert Item failed!</h1>\n".mysql_error());  
	
	if ($resultset)
	{
		$xml = '<?xml version="1.0" encoding="utf-8" ?>';
		$xml .= '<op>';
		while ($row = mysql_fetch_assoc($resultset))
		{
			$xml .= '<vl>'.$row['fieldname'].'</vl>';
		}
		$xml .= '</op>';
		header('Content-Type: text/xml'); 
		echo $xml;
	}
	
	mysql_close($db_connect);
}
?>