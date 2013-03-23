<?php

/* This block updates the database if a rating star was clicked
*  This block requires the following variables: none
*/

$right_required = "CreateNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

If(!empty($_GET['rateband'])){
	$rating=$_GET['rateband'];
	//Find out if an exisiting rating is in
	$query="SELECT rating FROM Users, ratings WHERE band='$band' AND ratings.user=Users.id AND Users.username='".$_SESSION['user']."'";
	$query_rating = mysql_query($query, $main);
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