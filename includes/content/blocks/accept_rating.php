<?php
/*
//Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
*/ 


$right_required = "CreateNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

If(!empty($_GET['rateband'])){
	$rating=$_GET['rateband'];
	//Find out if an exisiting rating is in
	$query="SELECT rating FROM ratings WHERE band='$band' AND user='$user'";
	$query_rating = mysql_query($query, $main);
//	echo mysql_error();
	$rating_row = mysql_fetch_assoc($query_rating);

	If (!isset($rating_row['rating']) ) {
	$sql = "INSERT INTO ratings (band, user, rating) VALUES ('$band', '$user', '$rating')";
	$sql_run = mysql_query($sql, $main);
	$sql = "INSERT INTO ratings (band, user, rating, festival) VALUES ('$band_master_id', '$user', '$rating', '$fest_id')";
	$sql_run = mysql_query($sql, $master);	
	} else {
//	echo "With rating logic entered<br>";
	$sql = "UPDATE ratings SET rating='$rating' WHERE band='$band' AND user='$user'";
	$sql_run = mysql_query($sql, $main);
	$sql = "UPDATE ratings SET rating='$rating' WHERE band='$band_master_id' AND user='$user'";
	$sql_run = mysql_query($sql, $master);	
	}
}
}
?>
